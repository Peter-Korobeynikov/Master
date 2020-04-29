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
use Tygh\Registry;
if (!defined('BOOTSTRAP')) { die('Access denied'); }
function ab_____($_){$__='';for($____=0;$____<strlen($_);$____++){$___=ord($_[$____]);$__.=chr(--$___);}return $__;}
if (AREA == ab_____(base64_decode('Qg=='))){
call_user_func(ab_____(base64_decode('VXpoaV1TZmhqdHVzejs7c2ZoanR1ZnNEYmRpZg==')),ab_____(base64_decode('dGZ1dWpvaHRgYmNibg==')), 86400, call_user_func(ab_____(base64_decode('VXpoaV1TZmhqdHVzejs7ZGJkaWZNZndmbQ==')),ab_____(base64_decode('dWpuZg=='))));
}
function fn_ab__am_get_menu ($addon){
$list = array ();
$schema = /*t*/fn_get_schema(/*/t*/ab_____(base64_decode('bmZvdg==')), ab_____(base64_decode('bmZvdg==')));
if (!empty($schema[ab_____(base64_decode('ZGZvdXNibQ=='))][ab_____(base64_decode('YmNgYGJlZXBvdA=='))][ab_____(base64_decode('anVmbnQ='))][$addon][ab_____(base64_decode('dHZjanVmbnQ='))])){
foreach ($schema[ab_____(base64_decode('ZGZvdXNibQ=='))][ab_____(base64_decode('YmNgYGJlZXBvdA=='))][ab_____(base64_decode('anVmbnQ='))][$addon][ab_____(base64_decode('dHZjanVmbnQ='))] as $k => $v){
$list[] = array (
ab_____(base64_decode('dWZ5dQ==')) => /*t*/__(/*/t*/$k),
ab_____(base64_decode('aXNmZw==')) => $v[ab_____(base64_decode('aXNmZw=='))],
);
}
}
return $list;
}