{$current_dispatch = $smarty.request.dispatch}
{strip}
    <script>
        (function(_, $) {
            $.extend(_, {
                ab__smc: {
                    max_height: '{$addons.ab__hide_product_description.max_height|intval|default:250|escape:"javascript"}',

                    description_element_classes: "{if $addons.ab__hide_product_description.appearance == "button"} ab-smc-button-set{/if}",

                    additional_classes_for_parent: "{if $addons.ab__hide_product_description.align == "right"} ab-smc-right-text{/if}"
                            {if $addons.ab__hide_product_description.align == "center"}+ " ab-smc-center-text"{/if},
                    additional_classes: "{$addons.ab__hide_product_description.custom_class|default:""}"
                            {if $addons.ab__hide_product_description.appearance == "button"}
                        + " ab-smc-button ty-btn__secondary ty-btn"
                            {elseif $addons.ab__hide_product_description.appearance == "text2"}
                        + " ab-smc-text-2"
                    {/if},
                    show_button: {if $addons.ab__hide_product_description.show_button_after_action == 'Y'}true{else}false{/if},

                    transition: {$addons.ab__hide_product_description.transition|intval / 1000},
                    exclude: {fn_get_schema("ab__hpd", "excluded_selectors")|json_encode nofilter},
                    selector: "{$addons.ab__hide_product_description.selectors|default:".ab-smc"|escape:"javascript"}"
                            {if $current_dispatch == 'products.view' && $addons.ab__hide_product_description.hide_in_product == 'Y'}
                        + ",.ty-product-block div.ty-wysiwyg-content[data-ab-smc-tab-hide^='Y']"
                            {elseif $current_dispatch == 'product_features.view' && $addons.ab__hide_product_description.hide_in_brand == 'Y'}
                        + ",.ty-feature__description"
                            {elseif $current_dispatch == 'categories.view' && $addons.ab__hide_product_description.hide_in_category == 'Y'}
                        + ",.ty-wysiwyg-content.ty-mb-s,.ty-wysiwyg-content.ty-mb-l"
                            {/if}
                            {if $runtime.layout.theme_name == "abt__youpitheme"} + ",.abt-yt-hc,.ypi-popup-descr"
                            {/if},
                    additional_selector: {fn_get_schema("ab__hpd", "included_selectors")|json_encode nofilter}
                }
            });

            _.tr({
                "ab__smc.more": '{"ab__smc.more"|__|escape:"javascript"}',
                "ab__smc.less": '{"ab__smc.less"|__|escape:"javascript"}',
            });
        }(Tygh, Tygh.$));
    </script>
{/strip}
{script src="js/addons/ab__hide_product_description/ab__smc.js"}