<?php
 if (!defined('BOOTSTRAP')) { die('Access denied'); } if ($_SERVER['REQUEST_METHOD'] == 'POST') { return array(CONTROLLER_STATUS_OK); } if ($mode == 'checkout') { $user_data = Tygh::$app['view']->getTemplateVars('user_data'); if (sd_YzE5ZWI5OWI1NDUzZmQ1Nzk0NjQ3MWI2($user_data)) { Tygh::$app['view']->assign('user_data', $user_data); } } $cart = Tygh::$app['view']->getTemplateVars('cart'); if (!isset($cart['user_data'])) { $cart['user_data'] = array(); } if (sd_YzE5ZWI5OWI1NDUzZmQ1Nzk0NjQ3MWI2($cart['user_data'])) { Tygh::$app['view']->assign('cart', $cart); }