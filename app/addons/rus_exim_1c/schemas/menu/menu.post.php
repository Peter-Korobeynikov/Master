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

use \Tygh\Registry;

$schema['top']['addons']['items']['commerceml'] = array(
    'attrs' => array(
        'class' => 'is-addon'
    ),
    'position' => 310,
    'href' => 'commerceml.currencies',
    'subitems' => array(
        'commerceml_currencies' => array(
            'href' => 'commerceml.currencies',
            'position' => 100
        ),
        'commerceml_prices' => array(
            'href' => 'commerceml.offers',
            'position' => 200
        ),
        'commerceml_taxes' => array(
            'href' => 'commerceml.taxes',
            'position' => 300
        )
    ),
);

return $schema;
