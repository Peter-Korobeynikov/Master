<?php
 if (!defined('BOOTSTRAP')) { die('Access denied'); } fn_register_hooks( 'before_dispatch', 'get_user_info', 'login_user_post', 'set_admin_notification' ); 