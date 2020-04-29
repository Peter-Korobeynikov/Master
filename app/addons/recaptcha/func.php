<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

use Tygh\Settings;
use Tygh\Registry;
use Tygh\Addons\Recaptcha\RecaptchaDriver;
use Tygh\Addons\Recaptcha\NativeCaptchaDriver;

/**
 * Instantiates captcha driver according to settings and detected country
 *
 * @return \Tygh\Addons\Recaptcha\NativeCaptchaDriver|\Tygh\Addons\Recaptcha\RecaptchaDriver
 */
function fn_recaptcha_get_captcha_driver()
{
    if (!isset(Tygh::$app['session']['recaptcha']['driver'])) {
        $ip = fn_get_ip(true);
        $forbidden_countries = (array) Registry::get('addons.recaptcha.forbidden_countries');
        $country = fn_get_country_by_ip($ip['host']);

        $is_google_blocked_in_country = array_key_exists($country, $forbidden_countries);
        if ($is_google_blocked_in_country && extension_loaded('gd')) {
            Tygh::$app['session']['recaptcha']['driver'] = 'native';
        } else {
            Tygh::$app['session']['recaptcha']['driver'] = 'recaptcha';
        }
    }

    if (Tygh::$app['session']['recaptcha']['driver'] === 'native') {
        return new NativeCaptchaDriver(Tygh::$app['session']);
    }

    return new RecaptchaDriver(Registry::get('addons.recaptcha'));
}

/**
 * @return string|null HTML code of Image verification settings inputs
 */
function fn_recaptcha_image_verification_settings_proxy()
{
    // For example, during the installation
    if (!isset(Tygh::$app['view'])) {
        return null;
    }

    /** @var \Tygh\SmartyEngine\Core $view */
    $view = Tygh::$app['view'];
    $settings = Settings::instance();
    $proxied_section = $settings->getSectionByName('Image_verification');
    $proxied_setting_objects = $settings->getList($proxied_section['section_id'], 0);

    $output = '';
    foreach ($proxied_setting_objects as $subsection_name => $setting_objects) {
        foreach ($setting_objects as $setting_object) {
            $view->assign('item', $setting_object);
            $view->assign('section', $proxied_section['section_id']);
            $view->assign('html_name', "addon_data[options][{$setting_object['object_id']}]");
            $view->assign('class', 'setting-wide');
            $view->assign('html_id', "addon_option_recaptcha_{$setting_object['name']}");

            $output .= $view->fetch('common/settings_fields.tpl');
        }
    }

    return $output;
}