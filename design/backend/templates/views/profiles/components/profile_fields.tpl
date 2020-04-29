{$fields = []}

{if !$exclude && !$include}
    {$fields = $profile_fields.$section}
{else}
    {foreach $profile_fields.$section as $key => $field}
        {if $include}
            {if in_array($field.field_name, $include)}
                {$fields[$key] = $field}
            {/if}
        {elseif $exclude}
            {if !in_array($field.field_name, $exclude)}
                {$fields[$key] = $field}
            {/if}
        {/if}
    {/foreach}
{/if}

{if $fields}

{if !$nothing_extra}
    {include file="common/subheader.tpl" title=$title}
{/if}

{if $shipping_flag}
    <div class="shipping-flag">
        <input class="hidden" id="elm_ship_to_another" type="checkbox" name="ship_to_another" value="1" {if $ship_to_another}checked="checked"{/if} />
        
        <span class="shipping-flag-title">
            {if $section == "S"}
                {__("shipping_same_as_billing")}
            {else}
                {__("text_billing_same_with_shipping")}
            {/if}
        </span>

        <label class="radio inline">
            <input class="cm-switch-availability cm-switch-inverse " type="radio" name="ship_to_another" value="0" id="sw_{$body_id}_suffix_yes" {if !$ship_to_another}checked="checked"{/if} />
            {__("yes")}
        </label>
        
        <label class="radio inline">
            <input class=" cm-switch-availability" type="radio" name="ship_to_another" value="1" id="sw_{$body_id}_suffix_no" {if $ship_to_another}checked="checked"{/if} />
            {__("no")}
        </label>
    </div>
    
{elseif $section == "S"}
    {assign var="ship_to_another" value=true}
    <input type="hidden" name="ship_to_another" value="1" />
{/if}

{if $body_id}
    <div id="{$body_id}">
{/if}

{if $shipping_flag && !$ship_to_another}
    {assign var="disabled_param" value="disabled=\"disabled\""}
{else}
    {assign var="disabled_param" value=""}
{/if}

{$default_data_name = $default_data_name|default:"user_data"}
{$profile_data = $profile_data|default:$user_data}

{foreach from=$fields item=field}
{if $field.field_name && $field.is_default == "Y"}
    {assign var="data_name" value=$default_data_name}
    {assign var="data_id" value=$field.field_name}
    {assign var="value" value=$profile_data.$data_id}
{else}
    {assign var="data_name" value="`$default_data_name`[fields]"}
    {assign var="data_id" value=$field.field_id}
    {assign var="value" value=$profile_data.fields.$data_id}
{/if}

{hook name="profiles:profile_fields"}
<div class="control-group profile-field-{$field.field_name}">
    <label
        for="{$id_prefix}elm_{$field.field_id}"
        class="control-label cm-profile-field {if $field.required == "Y"}cm-required{/if}{if $field.field_type == "ProfileFieldTypes::PHONE"|enum || ($field.autocomplete_type == "phone-full")} cm-mask-phone-label {/if}{if $field.field_type == "Z"} cm-zipcode{/if}{if $field.field_type == "E"} cm-email{/if} {if $field.field_type == "Z"}{if $section == "S"}cm-location-shipping{else}cm-location-billing{/if}{/if}"
    >{$field.description}:</label>

    <div class="controls">

    {if $field.field_type == "ProfileFieldTypes::STATE"|enum}
        {$_country = $settings.Checkout.default_country}
        {$_state = $value|default:$settings.Checkout.default_state}

        <select class="cm-state {if $section == "S"}cm-location-shipping{else}cm-location-billing{/if}" id="{$id_prefix}elm_{$field.field_id}" name="{$data_name}[{$data_id}]" {$disabled_param nofilter}>
            <option value="">- {__("select_state")} -</option>
            {if $states && $states.$_country}
                {foreach from=$states.$_country item=state}
                    <option {if $_state == $state.code}selected="selected"{/if} value="{$state.code}">{$state.state}</option>
                {/foreach}
            {/if}
        </select>
        <input type="text" id="elm_{$field.field_id}_d" name="{$data_name}[{$data_id}]" size="32" maxlength="64" value="{$_state}" disabled="disabled" class="cm-state {if $section == "S"}cm-location-shipping{else}cm-location-billing{/if} input-large hidden cm-skip-avail-switch" />

    {elseif $field.field_type == "ProfileFieldTypes::COUNTRY"|enum}
        {assign var="_country" value=$value|default:$settings.Checkout.default_country}
        <select id="{$id_prefix}elm_{$field.field_id}" class="cm-country {if $section == "S"}cm-location-shipping{else}cm-location-billing{/if}" name="{$data_name}[{$data_id}]" {$disabled_param nofilter}>
            {hook name="profiles:country_selectbox_items"}
            <option value="">- {__("select_country")} -</option>
            {foreach from=$countries item="country" key="code"}
            <option {if $_country == $code}selected="selected"{/if} value="{$code}">{$country}</option>
            {/foreach}
            {/hook}
        </select>

    {elseif $field.field_type == "ProfileFieldTypes::CHECKBOX"|enum}
        <input type="hidden" name="{$data_name}[{$data_id}]" value="N" {$disabled_param nofilter} />
        <label class="checkbox">
        <input type="checkbox" id="{$id_prefix}elm_{$field.field_id}" name="{$data_name}[{$data_id}]" value="Y" {if $value == "Y"}checked="checked"{/if} {$disabled_param nofilter} /></label>

    {elseif $field.field_type == "ProfileFieldTypes::TEXT_AREA"|enum}
        <textarea class="input-large" id="{$id_prefix}elm_{$field.field_id}" name="{$data_name}[{$data_id}]" cols="32" rows="3" {$disabled_param nofilter}>{$value}</textarea>

    {elseif $field.field_type == "ProfileFieldTypes::DATE"|enum}
        {include file="common/calendar.tpl" date_id="elm_`$field.field_id`" date_name="`$data_name`[`$data_id`]" date_val=$value extra=$disabled_param}

    {elseif $field.field_type == "ProfileFieldTypes::SELECT_BOX"|enum}
        <select id="{$id_prefix}elm_{$field.field_id}" name="{$data_name}[{$data_id}]" {$disabled_param nofilter}>
            {if $field.required != "Y"}
            <option value="">--</option>
            {/if}
            {foreach from=$field.values key=k item=v}
            <option {if $value == $k}selected="selected"{/if} value="{$k}">{$v}</option>
            {/foreach}
        </select>

    {elseif $field.field_type == "ProfileFieldTypes::RADIO"|enum}
        <div class="select-field">
        {foreach from=$field.values key=k item=v name="rfe"}
        <input class="radio" type="radio" id="{$id_prefix}elm_{$field.field_id}_{$k}" name="{$data_name}[{$data_id}]" value="{$k}" {if (!$value && $smarty.foreach.rfe.first) || $value == $k}checked="checked"{/if} {$disabled_param nofilter} /><label for="{$id_prefix}elm_{$field.field_id}_{$k}">{$v}</label>
        {/foreach}
        </div>

    {elseif $field.field_type == "ProfileFieldTypes::ADDRESS_TYPE"|enum}
        <input class="radio valign {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if}" type="radio" id="{$id_prefix}elm_{$field.field_id}_residential" name="{$data_name}[{$data_id}]" value="residential" {if !$value || $value == "residential"}checked="checked"{/if} {if !$skip_field}{$disabled_param nofilter}{/if} /><span class="radio">{__("address_residential")}</span>
        <input class="radio valign {if !$skip_field}{$_class}{else}cm-skip-avail-switch{/if}" type="radio" id="{$id_prefix}elm_{$field.field_id}_commercial" name="{$data_name}[{$data_id}]" value="commercial" {if $value == "commercial"}checked="checked"{/if} {if !$skip_field}{$disabled_param nofilter}{/if} /><span class="radio">{__("address_commercial")}</span>

    {else}  {* Simple input (or another types of input) *}
        <input
            type="text"
            id="{$id_prefix}elm_{$field.field_id}"
            name="{$data_name}[{$data_id}]"
            size="32"
            value="{$value}"
            class="input-large {if ($field.autocomplete_type == "phone-full") || ($field.field_type == "ProfileFieldTypes::PHONE"|enum)} cm-mask-phone{/if}"
            {$disabled_param nofilter}
        />
    {/if}
    </div>
</div>
{/hook}
{/foreach}
{if $body_id}
</div>
{/if}

{/if}
