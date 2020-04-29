<?php
use Tygh\Registry;
use Tygh\Settings;
defined('BOOTSTRAP') or die('Access denied');
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if ($mode == 'update' && $_REQUEST['addon'] == 'ath_instagram_2' && $action == 'save')
    {
        if (isset($_REQUEST['token']))
        {
            Settings::instance()->updateValue('access_token', $_REQUEST['token'], 'ath_instagram_2');
        }
        if (isset($_REQUEST['app_id']))
        {
            Settings::instance()->updateValue('app_id', $_REQUEST['app_id'], 'ath_instagram_2');
        }
        exit();
    }
}
if ($mode == 'update' && $_REQUEST['addon'] == 'ath_instagram_2')
{
    if (!empty(Registry::get('addons.ath_instagram_2.app_id')))
    {
        $fb = init_fb();
        if (!empty($_REQUEST['code']))
        {
            $helper = $fb->getRedirectLoginHelper();
            try
            {
                $accessToken = $helper->getAccessToken();
            }
            catch(Facebook\Exceptions\FacebookResponseException $e)
            {
                fn_set_notification('E', __('error') , __('ath_instagram_2.error_facebook_graph', array(
                    '[message]' => $e->getMessage()
                )));
            }
            catch(Facebook\Exceptions\FacebookSDKException $e)
            {
                fn_set_notification('E', __('error') , __('ath_instagram_2.error_facebook_sdk', array(
                    '[message]' => $e->getMessage()
                )));
            }
            if (!empty($accessToken))
            {
                Settings::instance()->updateValue('access_token', $accessToken->getValue() , 'ath_instagram_2');
            }
            fn_redirect('addons.update?addon=ath_instagram_2');
        }
        $url = fn_url('addons.update?addon=ath_instagram_2');
        $helper = $fb->getRedirectLoginHelper();
        $scope = array(
            'manage_pages',
            'instagram_basic',
            'pages_show_list'
        );
        $login_url = $helper->getLoginUrl($url, $scope);
        Tygh::$app['view']->assign('url_get_access_token', $login_url);
    }
}
return array(
    CONTROLLER_STATUS_OK
);

