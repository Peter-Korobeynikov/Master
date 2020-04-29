<?php

$schema['banners']['templates']['addons/it_extended_banner/blocks/it_carousel.tpl'] = array (
	'settings' => array (
		'navigation' => array (
			'type' => 'selectbox',
			'values' => array (
				'N' => 'none',
				'D' => 'dots',
				'P' => 'pages',
				'A' => 'arrows'
			),
			'default_value' => 'D'
		),
		'delay' => array (
			'type' => 'input',
			'default_value' => '3'
		),
		'height' => array (
			'type' => 'input',
			'default_value' => '250'
		),
		'height_for_mobile' => array (
			'type' => 'input',
			'default_value' => '250'
		),
		'bg_position' => array (
			'type' => 'selectbox',
			'values' => array (
				'center center' => 'it_extended_banner.center_center',
				'center top' => 'it_extended_banner.center_top',
				'center bottom' => 'it_extended_banner.center_bottom',
				'center right' => 'it_extended_banner.center_right',
				'center left' => 'it_extended_banner.center_left',
				'left bottom' => 'it_extended_banner.left_bottom'
			),
			'default_value' => 'center center'
		),
		'bg_size' => array (
			'type' => 'selectbox',
			'values' => array (
				'100%' => 'it_extended_banner.100percent',
				'cover' => 'it_extended_banner.cover',
				'auto' => 'it_extended_banner.auto',
			),
			'default_value' => '100%'
		),
		'padding_top' => array (
			'type' => 'input',
			'default_value' => ''
		),
		'padding_left' => array (
			'type' => 'input',
			'default_value' => '',
		),
		'text_align' => array (
			'type' => 'selectbox',
			'values' => array (
				'' => '',
				'left' => 'it_extended_banner.left',
				'right' => 'it_extended_banner.right',
				'center' => 'it_extended_banner.center'
			),
			'default_value' => ''
		),
		'text_color' => array (
			'type' => 'template',
			'template' => 'addons/it_extended_banner/block_settings/color_picker.tpl',

		),
		'bgh_color' => array (
			'type' => 'template',
			'template' => 'addons/it_extended_banner/block_settings/color_picker2.tpl',

		),
		'btn_color' => array (
			'type' => 'template',
			'template' => 'addons/it_extended_banner/block_settings/color_picker3.tpl',

		),
		'btn_txt' => array (
			'type' => 'template',
			'template' => 'addons/it_extended_banner/block_settings/color_picker4.tpl',
			
		),
		'font_size' => array (
			'type' => 'input',
			'default_value' => ''
		),
		'btn_size' => array (
			'type' => 'input',
			'default_value' => ''
		),
		'blk_opacity' => array (
			'type' => 'input',
			'default_value' => '',
			
		),
	)
);

return $schema;