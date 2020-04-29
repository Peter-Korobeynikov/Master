<div class="sidebar-row">
<h6>{__("search")}</h6>
<form action="{""|fn_url}" name="chains_search_form" method="get" class="chains_search_form">
{capture name="simple_search"}
<div class="sidebar-field">
<label for="chains_search-q">{__("abt__ut2_bt.form.find_by_name")}</label>
<input id="chains_search-q" type="text" name="q" size="20" value="{$search.q}" />
</div>
<div class="sidebar-field">
<label>{__("abt__ut2_bt.form.find_by_base_product")}</label>
<a class="clear-picker icon-remove" onclick="var container = $(this).closest('.control-group');$('input', container).val('');"></a>
{include file="pickers/products/picker.tpl" data_id="base_product_id" item_ids=$search.base_product_id product=$search.base_product_id|fn_get_product_name input_name="base_product_id" type="single"}
</div>
<div class="sidebar-field">
<label>{__("abt__ut2_bt.form.find_by_additional_product")}</label>
<a class="clear-picker icon-remove" onclick="var container = $(this).closest('.control-group');$('input', container).val('');"></a>
{include file="pickers/products/picker.tpl" data_id="additional_product_id" item_ids=$search.additional_product_id product=$search.additional_product_id|fn_get_product_name input_name="additional_product_id" type="single"}
</div>
{/capture}
{include file="common/advanced_search.tpl" no_adv_link=true simple_search=$smarty.capture.simple_search not_saved=true dispatch="abt__ut2_buy_together.manage"}
</form>
</div><hr>