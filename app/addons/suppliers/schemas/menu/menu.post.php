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

$schema['central']['customers']['items']['suppliers'] = array(
    'attrs' => array(
        'class'=>'is-addon'
    ),
    'href' => 'suppliers.manage',
    'position' => 400
);

$schema['top']['administration']['items']['notifications']['subitems']['suppliers.supplier_notifications'] = array(
    'href' => 'notification_settings.manage?receiver_type=S',
    'position' => 350,
);

return $schema;
