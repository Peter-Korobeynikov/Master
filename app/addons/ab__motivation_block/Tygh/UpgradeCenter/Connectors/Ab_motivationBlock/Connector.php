<?php
/*******************************************************************************************
*   ___  _          ______                     _ _                _                        *
*  / _ \| |         | ___ \                   | (_)              | |              © 2019   *
* / /_\ | | _____  _| |_/ /_ __ __ _ _ __   __| |_ _ __   __ _   | |_ ___  __ _ _ __ ___   *
* |  _  | |/ _ \ \/ / ___ \ '__/ _` | '_ \ / _` | | '_ \ / _` |  | __/ _ \/ _` | '_ ` _ \  *
* | | | | |  __/>  <| |_/ / | | (_| | | | | (_| | | | | | (_| |  | ||  __/ (_| | | | | | | *
* \_| |_/_|\___/_/\_\____/|_|  \__,_|_| |_|\__,_|_|_| |_|\__, |  \___\___|\__,_|_| |_| |_| *
*                                                         __/ |                            *
*                                                        |___/                             *
* ---------------------------------------------------------------------------------------- *
* This is commercial software, only users who have purchased a valid license and  accept   *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
namespace Tygh\UpgradeCenter\Connectors\Ab_motivationBlock;
use Tygh\Addons\SchemesManager;
use Tygh\Http;
use Tygh\Registry;
use Tygh\Settings;
use Tygh\Tools\Url;
use Tygh\UpgradeCenter\Connectors\BaseAddonConnector;
use Tygh\UpgradeCenter\Connectors\IConnector;
use Tygh\ABAManager;
class Connector extends BaseAddonConnector implements IConnector
{
protected $addon_id;
protected $addon;
protected $manager;
protected $s;
protected $m;
protected $url;
public function __construct()
{
parent::__construct();
$this->addon_id = ab_____(base64_decode('YmNgYG5wdWp3YnVqcG9gY21wZGw='));
$this->s = ab_____(base64_decode('dGZ1dWpvaHQ='));
$this->m = ab_____(base64_decode('YmNgYGJlZXBvdGBuYm9iaGZz'));
$this->u = ab_____(base64_decode('aXV1cXQ7MDBkdC5kYnN1L2JtZnljc2JvZWpvaC9kcG4wYnFqMzA='));
$this->manager = call_user_func(ab_____(base64_decode('VXpoaV1CQ0JOYm9iaGZzOztoYGI=')),$this->m);
$this->addon = call_user_func(ab_____(base64_decode('VXpoaV1CQ0JOYm9iaGZzOztoYGI=')),$this->addon_id);
if (call_user_func(ab_____(base64_decode('VXpoaV1TZmhqdHVzejs7aGZ1')),ab_____(base64_decode('YmVlcG90Lw==')) . $this->m . ab_____(base64_decode('L2N2am1l'))) == 26){
$this->addon[$this->addon_id][ab_____(base64_decode('dw=='))] = $this->addon[$this->addon_id][ab_____(base64_decode('d2ZzdGpwbw=='))];
$this->addon[$this->addon_id][ab_____(base64_decode('ZA=='))] = (call_user_func(ab_____(base64_decode('dHVzbWZv')),call_user_func(ab_____(base64_decode('VXpoaV1TZmhqdHVzejs7aGZ1')),ab_____(base64_decode('YmVlcG90Lw==')) . $this->addon_id . ab_____(base64_decode('L2RwZWY='))))) ? call_user_func(ab_____(base64_decode('VXpoaV1TZmhqdHVzejs7aGZ1')),ab_____(base64_decode('YmVlcG90Lw==')) . $this->addon_id . ab_____(base64_decode('L2RwZWY='))):ab_____(base64_decode('Li4='));
$this->addon[$this->addon_id][ab_____(base64_decode('Yw=='))] = (call_user_func(ab_____(base64_decode('dHVzbWZv')),call_user_func(ab_____(base64_decode('VXpoaV1TZmhqdHVzejs7aGZ1')),ab_____(base64_decode('YmVlcG90Lw==')) . $this->addon_id . ab_____(base64_decode('L2N2am1l'))))) ? call_user_func(ab_____(base64_decode('VXpoaV1TZmhqdHVzejs7aGZ1')),ab_____(base64_decode('YmVlcG90Lw==')) . $this->addon_id . ab_____(base64_decode('L2N2am1l'))):ab_____(base64_decode('Li4='));
$this->manager[$this->m][ab_____(base64_decode('dw=='))] = $this->manager[$this->m][ab_____(base64_decode('d2ZzdGpwbw=='))];
$this->manager[$this->m][ab_____(base64_decode('ZA=='))] = (call_user_func(ab_____(base64_decode('dHVzbWZv')),call_user_func(ab_____(base64_decode('VXpoaV1TZmhqdHVzejs7aGZ1')),ab_____(base64_decode('YmVlcG90Lw==')) . $this->m . ab_____(base64_decode('L2RwZWY='))))) ? call_user_func(ab_____(base64_decode('VXpoaV1TZmhqdHVzejs7aGZ1')),ab_____(base64_decode('YmVlcG90Lw==')) . $this->m . ab_____(base64_decode('L2RwZWY='))):ab_____(base64_decode('Li4='));
$this->manager[$this->m][ab_____(base64_decode('Yw=='))] = (call_user_func(ab_____(base64_decode('dHVzbWZv')),call_user_func(ab_____(base64_decode('VXpoaV1TZmhqdHVzejs7aGZ1')),ab_____(base64_decode('YmVlcG90Lw==')) . $this->m . ab_____(base64_decode('L2N2am1l'))))) ? call_user_func(ab_____(base64_decode('VXpoaV1TZmhqdHVzejs7aGZ1')),ab_____(base64_decode('YmVlcG90Lw==')) . $this->m . ab_____(base64_decode('L2N2am1l'))):ab_____(base64_decode('Li4='));
}
}
public function getConnectionData()
{
Http::$logging = false;
return array(
ab_____(base64_decode('bmZ1aXBl')) => ab_____(base64_decode('cXB0dQ==')),
ab_____(base64_decode('dnNt')) => $this->u,
ab_____(base64_decode('ZWJ1Yg==')) => array(
ab_____(base64_decode('cw==')) => ab_____(base64_decode('dmQvZHQ=')),
ab_____(base64_decode('bA==')) => $this->manager[$this->m][ab_____(base64_decode('ZA=='))],
ab_____(base64_decode('Yw==')) => $this->manager[$this->m][ab_____(base64_decode('Yw=='))],
ab_____(base64_decode('aQ==')) => call_user_func(ab_____(base64_decode('Z29gYm1tcHhmZWBncHM=')),ab_____(base64_decode('TlZNVUpXRk9FUFM='))) ? call_user_func(ab_____(base64_decode('VXpoaV1TZmhqdHVzejs7aGZ1')),ab_____(base64_decode('ZHBvZ2poL2l1dXFgaXB0dQ=='))) : call_user_func(ab_____(base64_decode('ZWNgaGZ1YGdqZm1ldA==')),ab_____(base64_decode('VEZNRkRVIXR1cHNmZ3Nwb3UhR1NQTiFAO2RwbnFib2pmdCFYSUZTRiF0dWJ1dnQhPiEoQighQk9FIXR1cHNmZ3Nwb3UhIj4hKCg='))),
ab_____(base64_decode('bQ==')) => call_user_func(ab_____(base64_decode('ZHBvdHVib3U=')),ab_____(base64_decode('REJTVWBNQk9IVkJIRg=='))),
ab_____(base64_decode('cXc=')) => call_user_func(ab_____(base64_decode('ZHBvdHVib3U=')),ab_____(base64_decode('UVNQRVZEVWBXRlNUSlBP'))),
ab_____(base64_decode('cWY=')) => call_user_func(ab_____(base64_decode('ZHBvdHVib3U=')),ab_____(base64_decode('UVNQRVZEVWBGRUpVSlBP'))),
ab_____(base64_decode('cWM=')) => call_user_func(ab_____(base64_decode('ZHBvdHVib3U=')),ab_____(base64_decode('UVNQRVZEVWBDVkpNRQ=='))),
ab_____(base64_decode('Yg==')) => $this->addon,
),
);
}
public function processServerResponse($response, $show_upgrade_notice){
$pd = array();
$rd = call_user_func(ab_____(base64_decode('a3Rwb2BlZmRwZWY=')),$response, true);
if (!empty($rd) and !empty($rd[ab_____(base64_decode('Z2ptZg=='))])){
$pd = $rd;
$pd[ab_____(base64_decode('b2JuZg=='))] = $this->addon[$this->addon_id][ab_____(base64_decode('b2JuZg=='))] . $pd[ab_____(base64_decode('b2JuZg=='))];
if ($show_upgrade_notice) {
call_user_func(ab_____(base64_decode('Z29gdGZ1YG9wdWpnamRidWpwbw==')),ab_____(base64_decode('WA==')), __(ab_____(base64_decode('b3B1amRm'))), __(ab_____(base64_decode('dWZ5dWB2cWhzYmVmYGJ3YmptYmNtZg==')), array(
ab_____(base64_decode('XHFzcGV2ZHVe')) => '<b>' . $pd[ab_____(base64_decode('b2JuZg=='))] . '</b>',
ab_____(base64_decode('XG1qb2xe')) => call_user_func(ab_____(base64_decode('Z29gdnNt')),ab_____(base64_decode('dnFoc2JlZmBkZm91ZnMvbmJvYmhm')))
)), ab_____(base64_decode('VA==')));
}
}
return $pd;
}
public function downloadPackage($schema, $package_path)
{
$r = array(false, __(ab_____(base64_decode('dWZ5dWB2ZGBkYm91YGVweG9tcGJlYHFiZGxiaGY='))));
$schema['type'] = $schema['id'];
if (!empty($schema[ab_____(base64_decode('bGZ6'))])){
Http::$logging = false;
$res = call_user_func(ab_____(base64_decode('Z29gcXZ1YGRwb3Vmb3V0')),$package_path, call_user_func(ab_____(base64_decode('VXpoaV1JdXVxOztxcHR1')),$this->u, array(ab_____(base64_decode('cw==')) => ab_____(base64_decode('dmQvaGI=')), ab_____(base64_decode('bA==')) => $schema[ab_____(base64_decode('bGZ6'))],), array(ab_____(base64_decode('dWpuZnB2dQ=='))=>15,)));
if (!$res || call_user_func(ab_____(base64_decode('dHVzbWZv')),$error = call_user_func(ab_____(base64_decode('VXpoaV1JdXVxOztoZnVGc3Nwcw==')))))
{ call_user_func(ab_____(base64_decode('Z29gc24=')),$package_path); } else { call_user_func(ab_____(base64_decode('Z29gcXZ1YGRwb3Vmb3V0')),call_user_func(ab_____(base64_decode('VXpoaV1TZmhqdHVzejs7aGZ1')),ab_____(base64_decode('ZHBvZ2poL2Vqcy92cWhzYmVm'))) . ab_____(base64_decode('cWJkbGJoZnQw')) . $schema[ab_____(base64_decode('amU='))] . ab_____(base64_decode('MHRkaWZuYi9rdHBv')), call_user_func(ab_____(base64_decode('a3Rwb2Bmb2RwZWY=')),$schema)); $r = array(true, ''); }
}
return $r;
}
public function onSuccessPackageInstall($content_schema, $information_schema)
{
parent::onSuccessPackageInstall($content_schema, $information_schema);
$s_id = call_user_func(ab_____(base64_decode('ZWNgaGZ1YGdqZm1l')),ab_____(base64_decode('VEZNRkRVIXRmZHVqcG9gamUhR1NQTiFAO3RmdXVqb2h0YHRmZHVqcG90IVhJRlNGIW9ibmYhPiFAdCFCT0UhdXpxZiE+IShCRUVQTyg=')), $this->addon_id);
if ($s_id){
$st_id = call_user_func(ab_____(base64_decode('ZWNgaGZ1YGdqZm1l')),ab_____(base64_decode('VEZNRkRVIXRmZHVqcG9gamUhR1NQTiFAO3RmdXVqb2h0YHRmZHVqcG90IVhJRlNGIXFic2ZvdWBqZSE+IUBqIUJPRSFvYm5mIT4hQHQ=')), $s_id, $this->s);
if ($st_id){
$b = call_user_func(ab_____(base64_decode('ZWNgaGZ1YGdqZm1l')),ab_____(base64_decode('VEZNRkRVIXdibXZmIUdTUE4hQDt0ZnV1am9odGBwY2tmZHV0IVhJRlNGIXRmZHVqcG9gamUhPiFAaiFCT0UhdGZkdWpwb2B1YmNgamUhPiFAaiFCT0Uhb2JuZiE+IUB0')), $s_id, $st_id, ab_____(base64_decode('Y3ZqbWU=')));
$b and call_user_func(ab_____(base64_decode('ZWNgcnZmc3o=')),ab_____(base64_decode('VlFFQlVGIUA7dGZ1dWpvaHRgcGNrZmR1dCFURlUhd2JtdmYhPiFAdCFYSUZTRiF0ZmR1anBvYGplIT4hQGohQk9FIXRmZHVqcG9gdWJjYGplIT4hQGohQk9FIW9ibmYhPiFAdA==')), $information_schema[ab_____(base64_decode('Y3ZqbWU='))], $s_id, $st_id, ab_____(base64_decode('Y3ZqbWU=')));
}
}
}
}