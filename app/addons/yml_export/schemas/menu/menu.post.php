<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

$schema['top']['addons']['items']['yml_export'] = array(
    'attrs' => array(
        'class' => 'is-addon'
    ),
    'href' => 'yml.manage',
    'type' => 'title',
    'position' => 1000,
    'subitems' => array(
        'yml_export.price_list' => array(
            'href' => 'yml.manage',
            'position' => 10,
        ),
        'yml_export.offers_params' => array(
            'href' => 'yml.offers_params',
            'position' => 20,
        )
    ),
);

return $schema;
