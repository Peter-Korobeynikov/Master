{if "ab__hpd.view"|fn_check_view_permissions}
{if $addons.ab__hide_product_description.hide_in_product == 'Y'}
<li id="ab__smc_{$html_id}" class="cm-js{if $active_tab == "block_ab__smc_`$html_id`"} active{/if}">
<a>{__("ab__smc")}</a>
</li>
{/if}
{/if}