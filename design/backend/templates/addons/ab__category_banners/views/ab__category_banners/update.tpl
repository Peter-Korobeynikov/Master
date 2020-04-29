{if $category_banner_data}
{assign var="id" value=$category_banner_data.category_banner_id}
{else}
{assign var="id" value=0}
{/if}
{capture name="mainbox"}
<form action="{""|fn_url}" method="post" class="form-horizontal form-edit {if ""|fn_check_form_permissions} cm-hide-inputs{/if}" name="category_banner_form" id="category_banner_form" enctype="multipart/form-data">
<input type="hidden" class="cm-no-hide-input" name="category_banner_id" value="{$id}" />
<div id="content_general">
<div class="control-group">
<label for="elm_category_banner" class="control-label cm-required">{__("name")}</label>
<div class="controls">
<input type="text" name="category_banner_data[category_banner]" id="elm_category_banner" value="{$category_banner_data.category_banner}" size="25" class="input-large" />
</div>
</div>
<div class="control-group" id="category_banner_categories">
{math equation="rand()" assign="rnd"}
<label for="categories_{$rnd}_ids" class="control-label cm-required">{__("categories")}</label>
<div class="controls">
{include file="pickers/categories/picker.tpl" hide_input="Y" rnd=$rnd data_id="categories" input_name="category_banner_data[category_ids]" item_ids=$category_banner_data.category_ids hide_link=true hide_delete_button=true display_input_id="category_ids" disable_no_item_text=true view_mode="list" but_meta="btn" show_active_path=true}
</div>
<!--category_banner_categories--></div>
<div class="control-group">
<label for="elm_include_subcategories" class="control-label">{__("ab__cb.form.include_subcategories")}</label>
<div class="controls">
<input type="hidden" name="category_banner_data[include_subcategories]" value="N" />
<input type="checkbox" name="category_banner_data[include_subcategories]" id="elm_include_subcategories" value="Y" {if $category_banner_data.include_subcategories == "Y"}checked="checked"{/if} />
</div>
</div>
<div class="control-group">
<label class="control-label">{__("ab__cb.form.grid_image")}</label>
<div class="controls">
{include file="common/attach_images.tpl" image_name="category_banners_main" image_object_type="category_banner" image_pair=$category_banner_data.main_pair no_detailed=true hide_titles=true}
</div>
</div>
<div class="control-group">
<label class="control-label">{__("ab__cb.form.list_image")}</label>
<div class="controls">
{include file="common/attach_images.tpl" image_name="category_banners_list_image" image_object_type="category_banner" image_pair=$category_banner_data.list_pair no_detailed=true hide_titles=true image_type="L"}
</div>
</div>
<div class="control-group">
<label for="elm_url" class="control-label">{__("ab__cb.form.url")}</label>
<div class="controls">
<input type="text" name="category_banner_data[url]" id="elm_url" value="{$category_banner_data.url}" size="25" class="input-large" />
</div>
</div>
<div class="control-group">
<label for="elm_position" class="control-label">{__("ab__cb.form.position")}</label>
<div class="controls">
<input type="text" name="category_banner_data[position]" id="elm_position" value="{$category_banner_data.position}" size="25" class="input-large" />
</div>
</div>
<div class="control-group">
<label for="elm_target_blank" class="control-label">{__("ab__cb.form.target_blank")}</label>
<div class="controls">
<input type="hidden" name="category_banner_data[target_blank]" value="N" />
<input type="checkbox" name="category_banner_data[target_blank]" id="elm_target_blank" value="Y" {if $category_banner_data.target_blank == "Y"}checked="checked"{/if} />
</div>
</div>
{include file="common/select_status.tpl" input_name="category_banner_data[status]" id="elm_category_banner_status" obj_id=$id obj=$category_banner_data hidden=false}
<div class="control-group">
<label class="control-label" for="elm_from_date_{$id}">{__("ab__cb.form.from_date")}</label>
<div class="controls">
{include file="common/calendar.tpl" date_id="elm_from_date_`$id`" date_name="category_banner_data[from_date]" date_val=$category_banner_data.from_date start_year=$settings.Company.company_start_year}
<input type="text" name="category_banner_data[from_time]" id="elm_url" value="{if $category_banner_data.from_date}{"H:i"|date:$category_banner_data.from_date}{/if}" size="3" class="input-small" placeholder="00:00" />
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_to_date_{$id}">{__("ab__cb.form.to_date")}</label>
<div class="controls">
{include file="common/calendar.tpl" date_id="elm_to_date_`$id`" date_name="category_banner_data[to_date]" date_val=$category_banner_data.to_date start_year=$settings.Company.company_start_year}
<input type="text" name="category_banner_data[to_time]" id="elm_url" value="{if $category_banner_data.to_date}{"H:i"|date:$category_banner_data.to_date}{/if}" size="3" class="input-small" placeholder="00:00" />
</div>
</div>
<div class="control-group"{if $category_banner_data.repeat.1.active == "Y" || !$category_banner_data.repeat.1.active} style="background-color:#f5f5f5"{/if}>
<label class="control-label" for="elm_repeat_1">{__("ab__cb.form.monday")}</label>
<div class="controls">
<input type="hidden" name="category_banner_data[repeat][1][active]" value="N" />
<input class="ab__cb-form-checkbox" type="checkbox" name="category_banner_data[repeat][1][active]" id="elm_repeat_1" value="Y" {if $category_banner_data.repeat.1.active == "Y" || !$category_banner_data.repeat.1.active}checked="checked"{/if} />
<input type="text" name="category_banner_data[repeat][1][time_from]" value="{if $category_banner_data.repeat.1.time_from}{"H:i"|gmdate:$category_banner_data.repeat.1.time_from}{/if}" size="3" class="input-small" placeholder="00:00" />
- <input type="text" name="category_banner_data[repeat][1][time_to]" value="{if $category_banner_data.repeat.1.time_to}{"H:i"|gmdate:$category_banner_data.repeat.1.time_to}{/if}" size="3" class="input-small" placeholder="00:00" />
</div>
</div>
<div class="control-group"{if $category_banner_data.repeat.2.active == "Y" || !$category_banner_data.repeat.2.active} style="background-color:#f5f5f5"{/if}>
<label class="control-label" for="elm_repeat_2">{__("ab__cb.form.tuesday")}</label>
<div class="controls">
<input type="hidden" name="category_banner_data[repeat][2][active]" value="N" />
<input class="ab__cb-form-checkbox" type="checkbox" name="category_banner_data[repeat][2][active]" id="elm_repeat_2" value="Y" {if $category_banner_data.repeat.2.active == "Y" || !$category_banner_data.repeat.2.active}checked="checked"{/if} />
<input type="text" name="category_banner_data[repeat][2][time_from]" value="{if $category_banner_data.repeat.2.time_from}{"H:i"|gmdate:$category_banner_data.repeat.2.time_from}{/if}" size="3" class="input-small" placeholder="00:00" />
- <input type="text" name="category_banner_data[repeat][2][time_to]" value="{if $category_banner_data.repeat.2.time_to}{"H:i"|gmdate:$category_banner_data.repeat.2.time_to}{/if}" size="3" class="input-small" placeholder="00:00" />
</div>
</div>
<div class="control-group"{if $category_banner_data.repeat.3.active == "Y" || !$category_banner_data.repeat.3.active} style="background-color:#f5f5f5"{/if}>
<label class="control-label" for="elm_repeat_3">{__("ab__cb.form.wednesday")}</label>
<div class="controls">
<input type="hidden" name="category_banner_data[repeat][3][active]" value="N" />
<input class="ab__cb-form-checkbox" type="checkbox" name="category_banner_data[repeat][3][active]" id="elm_repeat_3" value="Y" {if $category_banner_data.repeat.3.active == "Y" || !$category_banner_data.repeat.3.active}checked="checked"{/if} />
<input type="text" name="category_banner_data[repeat][3][time_from]" value="{if $category_banner_data.repeat.3.time_from}{"H:i"|gmdate:$category_banner_data.repeat.3.time_from}{/if}" size="3" class="input-small" placeholder="00:00" />
- <input type="text" name="category_banner_data[repeat][3][time_to]" value="{if $category_banner_data.repeat.3.time_to}{"H:i"|gmdate:$category_banner_data.repeat.3.time_to}{/if}" size="3" class="input-small" placeholder="00:00" />
</div>
</div>
<div class="control-group"{if $category_banner_data.repeat.4.active == "Y" || !$category_banner_data.repeat.4.active} style="background-color:#f5f5f5"{/if}>
<label class="control-label" for="elm_repeat_4">{__("ab__cb.form.thursday")}</label>
<div class="controls">
<input type="hidden" name="category_banner_data[repeat][4][active]" value="N" />
<input class="ab__cb-form-checkbox" type="checkbox" name="category_banner_data[repeat][4][active]" id="elm_repeat_4" value="Y" {if $category_banner_data.repeat.4.active == "Y" || !$category_banner_data.repeat.4.active}checked="checked"{/if} />
<input type="text" name="category_banner_data[repeat][4][time_from]" value="{if $category_banner_data.repeat.4.time_from}{"H:i"|gmdate:$category_banner_data.repeat.4.time_from}{/if}" size="3" class="input-small" placeholder="00:00" />
- <input type="text" name="category_banner_data[repeat][4][time_to]" value="{if $category_banner_data.repeat.4.time_to}{"H:i"|gmdate:$category_banner_data.repeat.4.time_to}{/if}" size="3" class="input-small" placeholder="00:00" />
</div>
</div>
<div class="control-group"{if $category_banner_data.repeat.5.active == "Y" || !$category_banner_data.repeat.5.active} style="background-color:#f5f5f5"{/if}>
<label class="control-label" for="elm_repeat_5">{__("ab__cb.form.friday")}</label>
<div class="controls">
<input type="hidden" name="category_banner_data[repeat][5][active]" value="N" />
<input class="ab__cb-form-checkbox" type="checkbox" name="category_banner_data[repeat][5][active]" id="elm_repeat_5" value="Y" {if $category_banner_data.repeat.5.active == "Y" || !$category_banner_data.repeat.5.active}checked="checked"{/if} />
<input type="text" name="category_banner_data[repeat][5][time_from]" value="{if $category_banner_data.repeat.5.time_from}{"H:i"|gmdate:$category_banner_data.repeat.5.time_from}{/if}" size="3" class="input-small" placeholder="00:00" />
- <input type="text" name="category_banner_data[repeat][5][time_to]" value="{if $category_banner_data.repeat.5.time_to}{"H:i"|gmdate:$category_banner_data.repeat.5.time_to}{/if}" size="3" class="input-small" placeholder="00:00" />
</div>
</div>
<div class="control-group"{if $category_banner_data.repeat.6.active == "Y" || !$category_banner_data.repeat.6.active} style="background-color:#f5f5f5"{/if}>
<label class="control-label" for="elm_repeat_6">{__("ab__cb.form.saturday")}</label>
<div class="controls">
<input type="hidden" name="category_banner_data[repeat][6][active]" value="N" />
<input class="ab__cb-form-checkbox" type="checkbox" name="category_banner_data[repeat][6][active]" id="elm_repeat_6" value="Y" {if $category_banner_data.repeat.6.active == "Y" || !$category_banner_data.repeat.6.active}checked="checked"{/if} />
<input type="text" name="category_banner_data[repeat][6][time_from]" value="{if $category_banner_data.repeat.6.time_from}{"H:i"|gmdate:$category_banner_data.repeat.6.time_from}{/if}" size="3" class="input-small" placeholder="00:00" />
- <input type="text" name="category_banner_data[repeat][6][time_to]" value="{if $category_banner_data.repeat.6.time_to}{"H:i"|gmdate:$category_banner_data.repeat.6.time_to}{/if}" size="3" class="input-small" placeholder="00:00" />
</div>
</div>
<div class="control-group"{if $category_banner_data.repeat.7.active == "Y" || !$category_banner_data.repeat.7.active} style="background-color:#f5f5f5"{/if}>
<label class="control-label" for="elm_repeat_7">{__("ab__cb.form.sunday")}</label>
<div class="controls">
<input type="hidden" name="category_banner_data[repeat][7][active]" value="N" />
<input class="ab__cb-form-checkbox" type="checkbox" name="category_banner_data[repeat][7][active]" id="elm_repeat_7" value="Y" {if $category_banner_data.repeat.7.active == "Y" || !$category_banner_data.repeat.7.active}checked="checked"{/if} />
<input type="text" name="category_banner_data[repeat][7][time_from]" value="{if $category_banner_data.repeat.7.time_from}{"H:i"|gmdate:$category_banner_data.repeat.7.time_from}{/if}" size="3" class="input-small" placeholder="00:00" />
- <input type="text" name="category_banner_data[repeat][7][time_to]" value="{if $category_banner_data.repeat.7.time_to}{"H:i"|gmdate:$category_banner_data.repeat.7.time_to}{/if}" size="3" class="input-small" placeholder="00:00" />
</div>
</div>
</div>
{capture name="buttons"}
{include file="buttons/save_cancel.tpl" but_role="submit-link" but_target_form="category_banner_form" but_name="dispatch[ab__category_banners.update]" save=$id}
{/capture}
</form>
<script>
(function (_, $) {
$('input[id^=elm_repeat_]').change(function() {
if ($(this).is(":checked")) {
$(this).closest('.control-group').css('background-color', '#f5f5f5');
} else {
$(this).closest('.control-group').css('background-color', 'transparent');
}
});
})(Tygh, Tygh.$);
</script>
{/capture}
{if $id}
{$title_start = __("ab__category_banners.editing")}
{$title_end = $category_banner_data.category_banner}
{else}
{capture name="mainbox_title"}
{__("ab__category_banners.adding")}
{/capture}
{/if}
{include file="common/mainbox.tpl"
title = $smarty.capture.mainbox_title
title_start = $title_start
title_end = $title_end
content = $smarty.capture.mainbox
buttons = $smarty.capture.buttons
select_languages=true
}