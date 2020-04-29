<?php
/***************************************************************************
*                                                                          *
*   (c) 2016 ThemeHills - Premium themes and addons					       *
*                                                                          *
****************************************************************************/

$schema['instagram_feed_2'] = array (
    'templates' => array(
        'addons/ath_instagram_2/blocks/instagram_feed_2_grid.tpl' => array(
	        'settings' => array(
	            'number_of_columns' =>  array (
	                'type' => 'input',
	                'default_value' => 3
	            ),
	            'ath_insta_indents' =>  array (
	                'type' => 'input',
	                'default_value' => 28
	            ),
				'hover_style' => array (
			        'type' => 'selectbox',
			        'values' => array (
			            'th_none' => 'th_none',
			            'info' => 'info',
			            'description' => 'description',
			            'dim' => 'dim',
			            'lighten' => 'lighten',
			        ),
			        'default_value' => 'info'
			    ),
			),
        ),
        
        'addons/ath_instagram_2/blocks/instagram_feed_2_scroller.tpl' => array (
	        'settings' => array(
	            'not_scroll_automatically' => array (
	                'type' => 'checkbox',
	                'default_value' => 'N'
	            ),
	            'scroll_per_page' =>  array (
	                'type' => 'checkbox',
	                'default_value' => 'N'
	            ),
	            'speed' =>  array (
	                'type' => 'input',
	                'default_value' => 400
	            ),
	            'pause_delay' =>  array (
	                'type' => 'input',
	                'default_value' => 3
	            ),
	            'item_quantity' =>  array (
	                'type' => 'input',
	                'default_value' => 3
	            ),
	            'outside_navigation' => array (
	                'type' => 'checkbox',
	                'default_value' => 'N'
	            ),
	            'hover_style' => array (
			        'type' => 'selectbox',
			        'values' => array (
			            'th_none' => 'th_none',
			            'info' => 'info',
			            'description' => 'description',
			            'dim' => 'dim',
			            'lighten' => 'lighten',
			        ),
			        'default_value' => 'info'
			    ),
	        ),
	    ),
	    
/*
	    'addons/ath_instagram_2/blocks/instagram_feed_2_posts.tpl' => array (),
	    'addons/ath_instagram_2/blocks/instagram_feed_2_posts_full.tpl' => array (),
	    'addons/ath_instagram_2/blocks/instagram_feed_2_masonry_grid.tpl' => array ()
*/
                
    ),
    'wrappers' => 'blocks/wrappers',
    'content' => array(
        'feed' => array(

            'type' => 'enum',
            'object' => 'feed',
            'items_function' => 'fn_get_instagram_feed_2',
            'remove_indent' => true,
            'hide_label' => true,
            'fillings' => array(
                'recent_for_self' => array(
	                'params' => array(
                        'filling' => 'self',
                    ),
                ),
				'instagram_tag' => array(
				    'params' => array(
                        'filling' => 'tags',
                    ),
	                'tag_name' => array(
	                    'type' => 'input',
	                    'default_value' => ''
	                ),
                ),
				'instagram_tag_top' => array(
				    'params' => array(
                        'filling' => 'tags_top',
                    ),
	                'tag_name' => array(
	                    'type' => 'input',
	                    'default_value' => ''
	                ),
                ),
                'instagram_buser' => array(
				    'params' => array(
                        'filling' => 'username',
                    ),
	                'username' => array(
	                    'type' => 'input',
	                    'default_value' => ''
	                ),
                ),
                'company_insta_user' => array(
	                'params' => array(
                        'filling' => 'company_insta_user',
                        'request' => array(
                            'company_id' => '%COMPANY_ID%'
                        ),
                    ),
                ),
            ),
        ),
    ),
    'settings' => array(
	    'limit' => array (
	        'type' => 'input',
	        'default_value' => 12
	    ),
/*
		'title_from_block' => array (
	        'type' => 'checkbox',
	        'default_value' => 'N'
	    ),
	    'title_link' => array (
	        'type' => 'checkbox',
	        'default_value' => 'Y'
	    ),	 
*/   
	    'img_link' => array (
	        'type' => 'selectbox',
	        'values' => array (
	            'none' => 'ath_insta_none',
	            'instagram' => 'ath_insta_instagram',
// 	            'popup' => 'ath_insta_popup',
	        ),
	        'default_value' => 'popup'
	    ),
	    
    ),
    //'cache' => false
);

return $schema;
