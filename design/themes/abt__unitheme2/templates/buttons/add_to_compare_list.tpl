{if !$config.tweaks.disable_dhtml}
    {$ajax_class = "cm-ajax cm-ajax-full-render"}
{/if}

{if !$hide_compare_list_button}
    {$c_url                = $redirect_url|default:         $config.current_url|escape:url}
    {$compare_button_type  = $compare_button_type|default:  "icon"}
    {$but_id               = $compare_but_id|default:       $but_id}
    {$but_name             = $compare_but_name|default:     $but_name}
    {$but_title            = $compare_but_title|default:    __("add_to_comparison_list")}
    {$but_meta             = $compare_but_meta|default:     "ut2-add-to-compare $ajax_class"}
    {$but_href             = $compare_but_href|default:     "product_features.add_product?product_id=$product_id&redirect_url=$c_url"|fn_url}

    <a	class="
	{if $but_meta}{$but_meta}{/if}
    {if $details_page} label{/if}
    {if $but_name} cm-submit{/if}
    {if !$runtime.customization_mode.live_editor} cm-tooltip{/if}"

    {if $but_title} title="{$but_title}"{/if}
    {if $but_id} id="{$but_id}"{/if}
  data-ca-target-id="comparison_list,account_info*,abt__ut2_compared_products"
    {if $but_href} href="{$but_href|fn_url}"{/if}>

    {if $compare_button_type == "icon"}<i class="ut2-icon-baseline-equalizer"></i>{/if}
    {if $details_page}{__("compare")}{/if}
    </a>
{/if}