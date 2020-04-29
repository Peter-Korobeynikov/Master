{capture name="mainbox_title"}{__("abt__ut2_buy_together.generate")}{/capture}
{capture name="mainbox"}
<form id='form' action="{""|fn_url}" method="post" name="generate_form" class="form-horizontal form-edit cm-disable-empty-files">
<div id="content_tab_products_abt__ut2_generator">
{include file="common/subheader.tpl" title=__("abt__ut2.form.header.general") target="#abt__ut2_bt-general"}
<div id="abt__ut2_bt-general" class="in collapse">
<div class="control-group">
<label for="elm_buy_together_name" class="control-label cm-required">{__("name")}:</label>
<div class="controls">
<input type="text" name="item_data[name]" id="elm_buy_together_name" size="55" value="{$item_data.name}" class="input-large">
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_buy_together_description">{__("description")}:</label>
<div class="controls">
<textarea id="elm_buy_together_description" name="item_data[description]" cols="55" rows="8" class="cm-wysiwyg input-textarea-long">{$item.description}</textarea>
</div>
</div>
{if !$item.date_from && !$item.date_to}
{$date_disabled = 'disabled="disabled"'}
{else}
{$date_disabled = false}
{/if}
<div class="control-group">
<label class="control-label" for="elm_use_avail_period">{__("use_avail_period")}:</label>
<div class="controls">
<input type="checkbox" name="avail_period" class="use_avail_period" data-id="abt__ut2" {if !$date_disabled} checked="checked"{/if} value="Y" id="elm_use_avail_period" />
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_buy_together_avail_from">{__("avail_from")}:</label>
<div class="controls">
<input type="hidden" name="item_data[date_from]" value="0" />
{include file="common/calendar.tpl" date_id="elm_buy_together_avail_from_abt__ut2" date_name="item_data[date_from]" date_val=$smarty.const.TIME start_year=$settings.Company.company_start_year extra=$date_disabled}
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_buy_together_avail_till">{__("avail_till")}:</label>
<div class="controls">
<input type="hidden" name="item_data[date_to]" value="0" />
{include file="common/calendar.tpl" date_id="elm_buy_together_avail_till_abt__ut2" date_name="item_data[date_to]" date_val=$smarty.const.TIME start_year=$settings.Company.company_start_year extra=$date_disabled}
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_buy_together_promotions">{__("display_in_promotions")}:</label>
<div class="controls">
<input type="hidden" name="item_data[display_in_promotions]" value="N">
<input type="checkbox" name="item_data[display_in_promotions]" id="elm_buy_together_promotions" value="Y" {if $item.display_in_promotions == "Y"}checked="checked"{/if}>
</div>
</div>
{include file="common/select_status.tpl" input_name="item_data[status]" obj=$item hidden=false}
</div>
{include file="common/subheader.tpl" title=__("abt__ut2.form.header.base_products") target="#abt__ut2_bt-base_products"}
<div id="abt__ut2_bt-base_products" class="in collapse clearfix">
{include file="pickers/products/picker.tpl" data_id="abt__ut2_bt_base_products" input_name="item_data[base_products]" type="table" colspan="7" placement="right" amount_input="text" abt__ut2_bt_generator=true}
<ul class="pull-right unstyled right span6">
<li><a class="btn" onclick="fn_buy_together_recalculate('abt__ut2_generator');">{__("recalculate")}</a></li>
</ul>
</div>
{include file="common/subheader.tpl" title=__("abt__ut2.form.header.additional_products") target="#abt__ut2_bt-base_products"}
<div id="abt__ut2_bt-additional_products" class="in collapse clearfix">
{include file="pickers/products/picker.tpl" data_id="abt__ut2_bt_additional_products" input_name="item_data[products]" type="table" aoc=true colspan="7" placement="right" abt__ut2_bt_generator=true}
<ul class="pull-right unstyled right span6">
<li><a class="btn" onclick="fn_buy_together_recalculate('abt__ut2_generator');">{__("recalculate")}</a></li>
</ul>
</div>
</div>
{capture name="buttons"}
{include file="buttons/button.tpl" but_text=__("abt__ut2.form.generate") but_role="submit-link" but_name="dispatch[abt__ut2_buy_together.generate]" but_meta="btn-primary" but_target_form="generate_form"}
{/capture}
</form>
{/capture}
{include file="common/mainbox.tpl" title=$smarty.capture.mainbox_title content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar}
