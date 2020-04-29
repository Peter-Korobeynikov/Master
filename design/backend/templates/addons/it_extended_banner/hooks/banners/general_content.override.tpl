<div class="control-group">
	<label for="elm_banner_name" class="control-label cm-required">{__("name")}</label>
	<div class="controls">
	<input type="text" name="banner_data[banner]" id="elm_banner_name" value="{$banner.banner}" size="25" class="input-large" /></div>
</div>

{if "ULTIMATE"|fn_allowed_for}
	{include file="views/companies/components/company_field.tpl"
		name="banner_data[company_id]"
		id="banner_data_company_id"
		selected=$banner.company_id
	}
{/if}

<div class="control-group">
	<label for="elm_banner_position" class="control-label">{__("position_short")}</label>
	<div class="controls">
		<input type="text" name="banner_data[position]" id="elm_banner_position" value="{$banner.position|default:"0"}" size="3"/>
	</div>
</div>

<div class="control-group">
	<label for="elm_banner_type" class="control-label cm-required">{__("type")}</label>
	<div class="controls">
	<select name="banner_data[type]" id="elm_banner_type" {* onchange="Tygh.$('#banner_graphic').toggle();  Tygh.$('#banner_text').toggle(); Tygh.$('#banner_url').toggle();  Tygh.$('#banner_target').toggle();" *}>
		<option {if $banner.type == "G"}selected="selected"{/if} value="G">{__("graphic_banner")}</option>
		<option {if $banner.type == "T"}selected="selected"{/if} value="T">{__("text_banner")}</option>
		<option {if $banner.type == "I"}selected="selected"{/if} value="I">{__("it_extended_banner.i_type")}</option>
	</select>
	</div>
</div>

<div class="control-group {if $b_type != "G"}hidden{/if} graphic_banner" id="banner_graphic">
	<label class="control-label">{__("image")}</label>
	<div class="controls">
		{include file="common/attach_images.tpl" image_name="banners_main" image_object_type="promo" image_pair=$banner.main_pair image_object_id=$id no_detailed=true hide_titles=true}
	</div>
</div>

<div class="control-group {if $b_type == "G"}hidden{/if} text_banner it_extended_controls" id="banner_text">
	<label class="control-label" for="elm_banner_description">{__("description")}:</label>
	<div class="controls">
		<textarea id="elm_banner_description" name="banner_data[description]" cols="35" rows="8" class="cm-wysiwyg input-large">{$banner.description}</textarea>
	</div>
</div>

<div class="control-group {if $b_type == "T"}hidden{/if} text_banner" id="banner_target">
	<label class="control-label" for="elm_banner_target">{__("open_in_new_window")}</label>
	<div class="controls">
	<input type="hidden" name="banner_data[target]" value="T" />
	<input type="checkbox" name="banner_data[target]" id="elm_banner_target" value="B" {if $banner.target == "B"}checked="checked"{/if} />
	</div>
</div>

<div class="control-group {if $b_type != "I"}hidden{/if} it_extended_controls" id="it_banner_button_text">
	<label class="control-label" for="elm_banner_button_text">{__("it_extended_banner.it_button_text")}</label>
	<div class="controls">	
		<input type="text" name="banner_data[it_button_text]" id="elm_banner_button_text" value="{$banner.it_button_text}" />
	</div>
</div>

<div class="control-group {if $b_type != "I"}hidden{/if} it_extended_controls" id="it_banner_description">
	<label class="control-label" for="elm_banner_button_text">{__("it_extended_banner.it_description")}</label>
	<div class="controls">	
		<input type="text" name="banner_data[it_description]" id="elm_banner_button_text" value="{$banner.it_description}" />
	</div>
</div>

<div class="control-group {if $b_type != "I"}hidden{/if} it_extended_controls" id="it_banner_background_image">
	<label class="control-label">{__("image")}</label>
	<div class="controls">
		{include file="common/attach_images.tpl" image_name="banners_background_image" image_object_type="background_image" image_pair=$banner.background_image image_object_id=$id no_detailed=true hide_titles=true}
	</div>
</div>

<div class="control-group {if $b_type == "T"}hidden{/if} text_banner" id="banner_url">
	<label class="control-label" for="elm_banner_url">{__("url")}:</label>
	<div class="controls">
		<input type="text" name="banner_data[url]" id="elm_banner_url" value="{$banner.url}" size="25" class="input-large" />
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="elm_banner_timestamp_{$id}">{__("creation_date")}</label>
	<div class="controls">
	{include file="common/calendar.tpl" date_id="elm_banner_timestamp_`$id`" date_name="banner_data[timestamp]" date_val=$banner.timestamp|default:$smarty.const.TIME start_year=$settings.Company.company_start_year}
	</div>
</div>

{include file="views/localizations/components/select.tpl" data_name="banner_data[localization]" data_from=$banner.localization}

{include file="common/select_status.tpl" input_name="banner_data[status]" id="elm_banner_status" obj_id=$id obj=$banner hidden=true}

<script type="text/javascript">
	$(document).ready(function(){
		$('#elm_banner_type').change(function(){
			var type = $(this).find('option:selected').val();
			if (type == 'G'){
				$('.it_extended_controls').hide();
				$('.text_banner').hide();
				$('.graphic_banner').show();
			}
			if (type == 'T'){
				$('.it_extended_controls').hide();
				$('.graphic_banner').hide();
				$('.text_banner').show();
			}
			if (type == 'I'){
				$('.text_banner').hide();
				$('.graphic_banner').hide();
				$('.it_extended_controls').show();
			}
		});
	});
</script>