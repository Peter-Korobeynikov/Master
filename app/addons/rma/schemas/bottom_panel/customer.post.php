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

$schema['rma.returns'] = [
    'from' => [
        'dispatch' => 'rma.returns',
    ],
    'to_admin' => [
        'dispatch' => 'rma.returns'
    ]
];

$schema['rma.details'] = [
    'from' => [
        'dispatch' => 'rma.details',
        'return_id'
    ],
    'to_admin' => [
        'dispatch'  => 'rma.details',
        'return_id' => '%return_id%'
    ]
];

return $schema;