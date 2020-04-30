<?php
 namespace Tygh\UpgradeCenter\Connectors\SdBackToTop; use Tygh\Addons\SchemesManager; use Tygh\Registry; use Tygh\Http; use Tygh\UpgradeCenter\Connectors\BaseAddonConnector; class Connector extends BaseAddonConnector { protected $addon_version; protected $dir_root; public function __construct() { parent::__construct(); $this->dir_root = Registry::get('config.dir.root'); $addon_scheme = SchemesManager::getScheme('sd_back_to_top'); $this->updates_server = str_rot13('uggc://hctenqr.fvzgrpuqri.pbz'); if (Registry::get('settings.Security.secure_admin') == 'Y') { $this->updates_server = str_rot13('uggcf://hctenqr.fvzgrpuqri.pbz'); } $this->product_name = $addon_scheme->getName(); $this->addon_id = $addon_scheme->getId(); $this->addon_version = $addon_scheme->getVersion(); $this->license_number = Registry::get(str_rot13('nqqbaf.fq_onpx_gb_gbc.yxrl')); $this->notification_key = 'upgrade_center:addon_sd_back_to_top'; } public function downloadPackage($schema, $package_path) { $download_url = $this->updates_server . '/uc/get_package/' . $schema['package_id'] . '/?license_number=' . $this->license_number; $request_result = Http::get($download_url, array(), array( 'write_to_file' => $package_path )); if (!$request_result || strlen($error = Http::getError())) { $download_result = array(false, __('text_uc_cant_download_package')); fn_rm($package_path); } else { if (md5_file($package_path) == $schema['md5']) { $download_result = array(true, ''); } else { $download_result = array(false, __('text_uc_broken_package')); fn_rm($package_path); } } return $download_result; } public function getConnectionData() { $data = array( 'a' => $this->addon_id, 'av' => $this->addon_version, 'l' => $this->language, 'k' => $this->license_number, 'h' => Registry::get('config.current_host'), 'v' => PRODUCT_VERSION, 'e' => PRODUCT_EDITION, 'b' => PRODUCT_BUILD, 'p' => CS_PHP_VERSION, 'm' => $this->getMemTotal(), 'mf' => $this->getMemFree(), 'd' => (int) disk_total_space($this->dir_root) / 1024, 'df' => (int) disk_free_space($this->dir_root) / 1024, ); $request_data = array( 'method' => 'get', 'url' => $this->updates_server . '/uc/get_available', 'data' => array( 'data' => base64_encode(json_encode($data)) ), 'headers' => array( 'Accept: application/xml' ) ); return $request_data; } private function getInfo($file, $key) { if (!isset($this->info[$file])) { if (@file_exists($file)) { $this->info[$file] = file_get_contents($file); } else { $this->info[$file] = ''; } } $value = false; if (!empty($this->info[$file])) { preg_match('/' . $key . ':\s+(\d+)\skB/', $this->info[$file], $pieces); $value = filter_var($pieces[1], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND); $value = $value / 1024; } return $value; } private function getMemFree() { return (int) $this->getInfo('/proc/meminfo', 'MemFree'); } private function getMemTotal() { return (int) $this->getInfo('/proc/meminfo', 'MemTotal'); } } 