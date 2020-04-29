<div class="control-group">
    <label for="elm_banner_name" class="control-label cm-required">{__("name")}</label>
    <div class="controls">
        <input type="text" name="banner_data[banner]" id="elm_banner_name" value="{$banner.banner}" size="25" class="input-large" />
    </div>
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
    <select name="banner_data[type]" id="elm_banner_type" onchange="Tygh.$('#banner_graphic').toggle(); Tygh.$('#text_banner_instructions').toggle(); Tygh.$('#banner_text').toggle(); Tygh.$('#banner_url').toggle();  Tygh.$('#banner_target').toggle(); Tygh.$('#graphic_settings').toggle();">
        <option {if $banner.type == "G"}selected="selected"{/if} value="G">{__("graphic_banner")}</option>
        <option {if $banner.type == "T"}selected="selected"{/if} value="T">{__("text_banner")}</option>
    </select>
    </div>
</div>

<div class="control-group {if $b_type != "G"}hidden{/if}" id="banner_graphic">
    <label class="control-label">{__("image")}</label>
    <div class="controls">
        {include file="common/attach_images.tpl" image_name="banners_main" image_object_type="promo" image_pair=$banner.main_pair image_object_id=$id no_detailed=true hide_titles=true}
    </div>
</div>

<div class="{if $b_type == "T"}hidden{/if}" id="graphic_settings">
    <div class="control-group">
        <label for="elm_text of banner" class="control-label">{__("sd_wide_banner.banner_title")}</label>
        <div class="controls">
            <input type="text" name="banner_data[banner_title]" id="elm_text_of_banner" value="{$banner.banner_title}" size="25" class="input-large" />
        </div>
    </div>
    <div class="control-group">
        <label for="elm_text of banner" class="control-label">{__("sd_wide_banner.banner_subtitle")}</label>
        <div class="controls">
            <input type="text" name="banner_data[banner_subtitle]" id="elm_text_of_banner" value="{$banner.banner_subtitle}" size="25" class="input-large" />
        </div>
    </div>
    <div class="control-group">
        <label for="elm_button_text" class="control-label">{__("sd_wide_banner.button_text")}</label>
        <div class="controls">
            <input type="text" name="banner_data[button_text]" id="elm_text_of_button" value="{$banner.button_text}" size="20" class="input-short" />
        </div>
    </div>
    <div class="control-group">
        <label for="elm_font_color" class="control-label">{__("sd_wide_banner.font_color")}</label>
        <div class="controls">
            {include file="common/colorpicker.tpl" cp_name="banner_data[font_color]" cp_id="elm_font_color" cp_value=$banner.font_color|default:'#ffffff'}
        </div>
    </div>
    <div class="control-group">
        <label for="elm_button_color" class="control-label">{__("sd_wide_banner.button_color")}</label>
        <div class="controls">
            {include file="common/colorpicker.tpl" cp_name="banner_data[button_color]" cp_id="elm_button_color" cp_value=$banner.button_color|default:'#000000'}
        </div>
    </div>
</div>

<div class="control-group {if $b_type == "G"}hidden{/if}" id="banner_text">
    <label class="control-label" for="elm_banner_description">{__("description")}:</label>
    <div class="controls">
        <textarea id="elm_banner_description" name="banner_data[description]" cols="35" rows="8" class="cm-wysiwyg input-large">{$banner.description}</textarea>
    </div>
</div>

<div class="control-group {if $b_type == "T"}hidden{/if}" id="banner_target">
    <label class="control-label" for="elm_banner_target">{__("open_in_new_window")}</label>
    <div class="controls">
        <input type="hidden" name="banner_data[target]" value="T" />
        <input type="checkbox" name="banner_data[target]" id="elm_banner_target" value="B" {if $banner.target == "B"}checked="checked"{/if} />
    </div>
</div>

<div class="control-group {if $b_type == "T"}hidden{/if}" id="banner_url">
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
