{if $addons.ab__motivation_block.display_attached_mb_items_on_cat_page == 'Y' && fn_check_view_permissions("ab__motivation_block.manage", "GET")}
{$params = ['category_ids' => [
$category_data.category_id]
]}
{$motivation_items = fn_ab__mb_get_motivation_items($params)}
{if $motivation_items.0}
{$hide_inputs = !fn_check_view_permissions("ab__motivation_block.manage", "POST")}
{include file="common/subheader.tpl" title=__("ab__motivation_block") target="#acc_addon_ab__motivation_block"}
<p>
{__('ab__mb.admin.categories_update.description',
['[href]' => "ab__motivation_block.manage&category_ids[]=`$category_data.category_id`"|fn_url,
'[text]' => __('ab__mb.admin.categories_update.description.tooltip',
['[href]' => __('ab__mb.admin.categories_update.description.tooltip.link')])]
)}
</p>
<div id="acc_addon_ab__motivation_block" class="collapsed in{if $hide_inputs} cm-hide-inputs{/if}">
<div class="table-responsive-wrapper">
<table class="table table-middle table-responsive">
<thead>
<tr>
<th width="90%">{__("name")}</th>
<th width="10%" class="right">{__("status")}</th>
</tr>
</thead>
<tbody>
{foreach $motivation_items.0 as $motivation_item}
<tr class="cm-row-status-{$motivation_item.status|lower}">
<td><a class="row-status" href="{"ab__motivation_block.update?motivation_item_id=`$motivation_item.motivation_item_id`"|fn_url}" target="_blank">{$motivation_item.name}</a></td>
<td class="right nowrap" data-th="{__("status")}">{include file="common/select_popup.tpl" id=$motivation_item.motivation_item_id status=$motivation_item.status hidden=true object_id_name="motivation_item_id" table="ab__mb_motivation_items" popup_additional_class="cm-no-hide-input dropleft" non_editable=true}</td>
</tr>
{/foreach}
</tbody>
</table>
</div>
</div>
{/if}
{/if}