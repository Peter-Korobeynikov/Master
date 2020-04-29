{if 'ab__deal_of_the_day.view'|fn_check_view_permissions}
<div id="content_ab__dotd" {if !'ab__deal_of_the_day.manage'|fn_check_view_permissions}class="cm-hide-inputs"{/if}>
<div class="control-group">
<label class="control-label" for="elm_ab__dotd_h1">{__("ab__dotd.form.h1")}</label>
<div class="controls">
<input type="text" name="promotion_data[h1]" id="elm_ab__dotd_h1" size="25" value="{$promotion_data.h1}" size="25" class="input-large" />
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_ab__dotd_page_title">{__("ab__dotd.form.page_title")}</label>
<div class="controls">
<input type="text" name="promotion_data[page_title]" id="elm_ab__dotd_page_title" value="{$promotion_data.page_title}" size="25" class="input-large" />
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_ab__dotd_meta_description">{__("ab__dotd.form.meta_description")}</label>
<div class="controls">
<input type="text" name="promotion_data[meta_description]" id="elm_ab__dotd_meta_description" value="{$promotion_data.meta_description}" size="25" class="input-large" />
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_ab__dotd_meta_keywords">{__("ab__dotd.form.meta_keywords")}</label>
<div class="controls">
<input type="text" name="promotion_data[meta_keywords]" id="elm_ab__dotd_meta_keywords" value="{$promotion_data.meta_keywords}" size="25" class="input-large" />
</div>
</div>
<div class="control-group">
<label class="control-label">{__("ab__dotd.form.page_image")}:</label>
<div class="controls">
{include file="common/attach_images.tpl" image_name="promotion_main" image_object_type="promotion" image_pair=$promotion_data.main_pair image_type="M" no_detailed=true}
</div>
</div>
<div class="control-group">
<label class="control-label">{__("ab__dotd.form.list_image")}:</label>
<div class="controls">
{include file="common/attach_images.tpl" image_name="promotion_list" image_object_type="promotion" image_pair=$promotion_data.list_pair image_type="A" no_detailed=true}
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_ab__dotd_filter">{__("ab__dotd.form.filter")}:</label>
<div class="controls">
<input type="hidden" name="promotion_data[filter]" value="N" />
<input type="checkbox" name="promotion_data[filter]" id="elm_ab__dotd_filter" value="Y" {if $promotion_data.filter == "Y"}checked="checked"{/if} />
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_use_products_filter">{__("ab__dotd.form.use_products_filter")}:</label>
<div class="controls">
<input type="hidden" name="promotion_data[use_products_filter]" value="N" />
<input type="checkbox" name="promotion_data[use_products_filter]" id="elm_use_products_filter" value="Y" {if !$promotion_data.use_products_filter || $promotion_data.use_products_filter == "Y"}checked="checked"{/if} />
</div>
</div>
<div class="control-group">
<label class="control-label" for="elm_hide_products_block">{__("ab__dotd.form.hide_products_block")}:</label>
<div class="controls">
<input type="hidden" name="promotion_data[hide_products_block]" value="N" />
<input type="checkbox" name="promotion_data[hide_products_block]" id="elm_hide_products_block" value="Y" {if $promotion_data.hide_products_block == "Y"}checked="checked"{/if} />
</div>
</div>
{if $addons.seo.status == 'A'}
{include file="addons/seo/common/seo_name_field.tpl" object_data=$promotion_data object_name="promotion_data" object_id=$promotion_data.page_id object_type="x"}
{/if}
{hook name='ab__deal_of_the_day:detailed_content'}{/hook}
<!--content_ab__dotd--></div>
{* promotion schedule *}
<div id="content_ab__dotd_schedule" {if !'ab__deal_of_the_day.manage'|fn_check_view_permissions}class="cm-hide-inputs"{/if}>
<div style="padding: 10px 20px; background: #f5f5f5;">{__('ab__dotd.tab.ab__dotd_schedule_header')}</div>
<div class="control-group">
<label class="control-label" for="elm_use_schedule">{__("ab__dotd.form.use_schedule")}{include file="common/tooltip.tpl" tooltip=__('ab__dotd.form.use_schedule.tooltip')}:</label>
<div class="controls">
<input type="hidden" name="promotion_data[use_schedule]" value="N" />
<input type="checkbox" name="promotion_data[use_schedule]" id="elm_use_schedule" value="Y" {if $promotion_data.use_schedule == "Y"}checked="checked"{/if} />
</div>
</div>
{* year selector *}
<select class="ab__dotd_year_selector" name="ab__dotd_active_year">
{foreach $ab__dotd_years as $key => $year}
<option{if $ab__dotd_active_year == $year} selected{/if}>{$year}</option>
{/foreach}
</select>
{* month selector *}
<select class="ab__dotd_month_selector" name="ab__dotd_active_month">
<option value="">{__('all')}</option>
{for $month=1 to 12}
<option value="{$month}"{if $ab__dotd_active_month == $month} selected{/if}>{__("month_name_`$month`")}</option>
{/for}
</select>
<button id="ab__dotd_clearall">{__('clear')}</button>
{* year render *}
{foreach $ab__dotd_years as $year}
{* month render *}
{for $month=1 to 12}
{assign var='timestamp' value=mktime(0, 0, 0, $month, 1, $year)}
{* fill days list *}
{$daysList = []}
{for $day = 1 to 't'|date:$timestamp}
{$date = $timestamp|fn_date_format:"%d"}
{* get day of week *}
{$week_day = 'w'|date:$timestamp}
{$string = "%s <br> %s"|sprintf:$date:__("weekday_abr_`$week_day`")}
{* mark weekends *}
{if $week_day|in_array:[0,6]}
{$string = "<span class='ab__dotd-weekend'>`$string`</span>"}
{/if}
{* push into array *}
{$daysList[] = [
'name' => $string,
'title' => $timestamp|fn_date_format:"`$settings.Appearance.date_format`"
]}
{* get next date *}
{$timestamp = '+1 day'|strtotime:$timestamp}
{/for}
{* output table *}
<div id="ab__dotd_timedsheet_{$year}_{$month}">
<table>
<thead><tr><th>{__("month_name_`$month`")} {$year}</th></tr></thead>
<tbody class="ab__dotd_month_schedule" data-ca-days-list="{$daysList|json_encode}" data-ca-input-id="ab__dotd_schedule_{$year}_{$month}"></tbody>
</table>
</div>
<input id="ab__dotd_schedule_{$year}_{$month}" name="promotion_data[ab__dotd_schedule][{$year}][{$month}]" type="hidden" value="{$promotion_data.ab__dotd_schedule.{$year}.{$month}|default:'[]'}">
{/for}
{/foreach}
<!--content_ab__dotd_schedule--></div>
{script src="js/addons/ab__deal_of_the_day/schedule.js"}
{/if}
{if $promotion_data.promotion_id}
<script>
(function(_,$) {
$(_.doc).ready(function() {
$('#actions_panel .dropdown-menu').prepend('<li><a target="_blank" href="{"promotions.view?promotion_id=`$promotion_data.promotion_id`"|fn_url:'C'}">{__('preview')}</a></li>');
});
}(Tygh, Tygh.$));
</script>
{/if}
