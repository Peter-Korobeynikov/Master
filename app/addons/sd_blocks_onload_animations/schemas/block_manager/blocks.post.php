<?php
 foreach ($schema as $block_type => $value) { $schema[$block_type] = array_replace_recursive($schema[$block_type], array( 'settings' => array ( 'animation_effect' => array ( 'type' => 'selectbox', 'values' => array ( 'N' => 'none', ' sd-scale-up' => 'scale-up', ' sd-scale-down' => 'scale-down', ' sd-slide-left' => 'slide-left', ' sd-slide-right' => 'slide-right', ' sd-slide-up' => 'slide-up', ' sd-slide-down' => 'slide-down', ' sd-fade-in' => 'fade-in' ), 'default_value' => 'N' ), 'animation_speed' => array ( 'type' => 'selectbox', 'values' => array ( 'N' => 'none', ' sd-ease-in' => 'ease-in', ' sd-ease-in-out' => 'ease-in-out', ' sd-linear' => 'linear' ), 'default_value' => 'N' ), 'animation_duration' => array ( 'type' => 'input', 'tooltip' => __('ttc_animation_duration'), 'default_value' => 0 ), 'animation_delay' => array ( 'type' => 'input', 'default_value' => 0 ), 'number_of_impressions' => array ( 'type' => 'input', 'tooltip' => __('ttc_number_of_impressions'), 'default_value' => 0 ), ), )); } return $schema;