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

use Tygh\Registry;
use Tygh\BlockManager\Layout;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_settings_variants_addons_facebook_store_layout_id()
{
    $variants = array('' => '--');

    /** @var Layout $layout */
    $layout = Layout::instance();
    $company_id = Registry::get('runtime.company_id');
    $companies = fn_get_short_companies();

    foreach ($layout->getList($company_id) as $layout) {
        $company = isset($layout['company_id']) ? implode('', array(' (', $companies[$layout['company_id']], ')')) : '';
        $variants[$layout['layout_id']] = sprintf('%s%s', $layout['name'], $company);
    }

    return $variants;
}
