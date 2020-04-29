<?php
 $schema['sd_wide_banners'] = array ( 'content' => array ( 'items' => array ( 'remove_indent' => true, 'hide_label' => true, 'type' => 'enum', 'object' => 'banners', 'items_function' => 'fn_get_banners', 'fillings' => array ( 'manually' => array ( 'picker' => 'addons/banners/pickers/banners/picker.tpl', 'picker_params' => array ( 'type' => 'links', ), 'params' => array ( 'sort_by' => 'position', 'sort_order' => 'asc' ) ), 'newest' => array ( 'params' => array ( 'sort_by' => 'timestamp', 'sort_order' => 'desc', 'request' => array ( 'cid' => '%CATEGORY_ID%' ) ) ), ), ), ), 'templates' => array ( 'addons/sd_wide_banner/blocks/original.tpl' => array(), 'addons/sd_wide_banner/blocks/carousel.tpl' => array( 'settings' => array ( 'navigation' => array ( 'type' => 'selectbox', 'values' => array ( 'N' => 'none', 'D' => 'dots', 'P' => 'pages', 'A' => 'arrows', 'DA' => 'dots_arrows', 'PA' => 'pages_arrows' ), 'default_value' => 'D' ), 'delay' => array ( 'type' => 'input', 'default_value' => '15' ), ), ) ), 'wrappers' => 'blocks/wrappers', 'cache' => array( 'update_handlers' => array( 'banners', 'banner_descriptions', 'banner_images' ) ) ); return $schema; 