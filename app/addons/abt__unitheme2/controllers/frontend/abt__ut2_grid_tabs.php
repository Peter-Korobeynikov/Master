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
* This is commercial software, only users who have purchased a valid license and accept    *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
use Tygh\BlockManager\RenderManager;
use Tygh\BlockManager\Block;
use Tygh\BlockManager\SchemesManager;
if ($mode == 'load' && !empty($_REQUEST['result_ids'])) {
$params = $_REQUEST;
if (!empty($_REQUEST['redirect_url'])) {
Tygh::$app['view']->assign('redirect_url', $_REQUEST['redirect_url']);
}
foreach ((array) explode(',', $_REQUEST['result_ids']) as $result_id) {
$data = explode('_', str_replace('content_abt__ut2_grid_tab_', '', $result_id));
$grid_id = !empty($data[0]) ? $data[0] : 0;
$block_id = !empty($data[1]) ? $data[1] : 0;
$snapping_id = !empty($data[2]) ? $data[2] : 0;
$object_type = !empty($data[3]) ? $data[3] : '';
$object_id = !empty($data[4]) ? $data[4] : 0;
$_REQUEST['object_type'] = $object_type;
$_REQUEST['object_id'] = $object_id;
if (!empty($params['dispatch'])) {
$dispatch = $params['dispatch'];
} else {
$dispatch = !empty($_REQUEST['dispatch']) ? $_REQUEST['dispatch'] : 'index.index';
}
$area = !empty($params['area']) ? $params['area'] : AREA;
$dynamic_object = array();
if (!empty($object_type) && !empty($object_id)) {
$params['dynamic_object']['object_type'] = $object_type;
$params['dynamic_object']['object_id'] = $object_id;
}
if (!empty($params['dynamic_object'])) {
$dynamic_object = $params['dynamic_object'];
} elseif (!empty($_REQUEST['dynamic_object']) && $area != 'C') {
$dynamic_object = $_REQUEST['dynamic_object'];
} else {
$dynamic_object_scheme = SchemesManager::getDynamicObject($dispatch, $area);
if (!empty($dynamic_object_scheme) && !empty($_REQUEST[$dynamic_object_scheme['key']])) {
$dynamic_object['object_type'] = $dynamic_object_scheme['object_type'];
$dynamic_object['object_id'] = $_REQUEST[$dynamic_object_scheme['key']];
} else {
$dynamic_object = array();
}
}
if (!empty($params['redirect_url'])) {
Tygh::$app['view']->assign('redirect_url', $params['redirect_url']);
}
$block = Block::instance()->getById($block_id, $snapping_id, $dynamic_object, DESCR_SL);
Tygh::$app['ajax']->assignHtml($result_id, RenderManager::renderBlock($block));
}
exit;
}
