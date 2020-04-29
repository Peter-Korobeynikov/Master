<tbody>
{$set_cookie="set_{$s}_{$s_key}"}
{$set_openned=$smarty.cookies.$set_cookie}
<tr>
<td class="row-gray">
<div class="pm-arrow">
<span name="plus_minus" id="on_set_{$s}_{$s_key}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combination-sets{if $set_openned} hidden{/if}"><span class="exicon-expand"> </span></span>
<span name="minus_plus" id="off_set_{$s}_{$s_key}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combination-sets{if !$set_openned} hidden{/if}"><span class="exicon-collapse"> </span></span>
</div>
<div id="sw_set_{$s}_{$s_key}" class="hand cm-combination-sets cm-save-state">
<span class="ab-am-set-name">{$set.name} {__('ab__am.order', ['[order_id]' => $set.order_id])}</span>
{if $set.available_updates}<span class="ab-am-available-updates">{__('ab__am.set.available_updates', ['[n]' => $set.available_updates])}</span>{/if}
</div>
</td>
<td class="row-gray right">
<div class="ab-am-subscription {$set.status}">
{__("ab__am.addon.subscription.`$set.status`", ['[date]' => $set.subscription_updates|date_format:"`$settings.Appearance.date_format`"])}
</div>
</td>
<td class="row-gray right" nowrap>
<div class="ab-am-subscription {$set.status}">
{btn type="text" text=__("ab__am.addon.subscription.`$set.status`.button.value") title=__("ab__am.addon.subscription.`$set.status`.button.tooltip") class="cm-tooltip btn btn-primary btn-{$set.status}" href="`$set.link`" target="_blank"}
</div>
</td>
</tr>
<tr id="set_{$s}_{$s_key}" class="{if !$set_openned}hidden {/if}no-hover row-more">
<td colspan="3">
{include file="addons/ab__addons_manager/views/ab__am/components/addons.tpl" addons=$set.addons type='set'}
</td>
</tr>
</tbody>