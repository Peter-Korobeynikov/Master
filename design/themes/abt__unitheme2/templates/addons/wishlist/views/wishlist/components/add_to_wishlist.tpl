{$wishlist_button_type = $wishlist_button_type|default:  "icon"}
{$but_id               = $wishlist_but_id|default:       $but_id}
{$but_name             = $wishlist_but_name|default:     $but_name}
{$but_title            = $wishlist_but_title|default:    __("abt__ut2.add_to_wishlist.tooltip")}
{$but_meta             = $wishlist_but_meta|default:     "ut2-add-to-wish $ajax_class"}
{$but_href             = $wishlist_but_href|default:     $but_href}

<a class="
	{if $but_meta}{$but_meta}{/if}
	{if $details_page} label{/if}
	{if $but_name} cm-submit{/if}
	{if !$runtime.customization_mode.live_editor} cm-tooltip{/if}"

    {if $but_title} title="{$but_title}"{/if}
    {if $but_id} id="{$but_id}"{/if}
    {if $but_name} data-ca-dispatch="{$but_name}"{/if}
    {if $but_href} href="{$but_href|fn_url}"{/if}>
    {if $wishlist_button_type == "icon"}<i class="ut2-icon-baseline-favorite"></i>{/if}
    {if $details_page}{__("add_to_wishlist")}{/if}
</a>