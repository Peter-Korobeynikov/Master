<?php
 use Tygh\Embedded; if (!defined('BOOTSTRAP')) { die('Access denied'); } if ($_SERVER['REQUEST_METHOD'] == 'POST') { return; } Tygh::$app['view']->assign('sd_back_to_topEmbedded', Embedded::isEnabled()); 