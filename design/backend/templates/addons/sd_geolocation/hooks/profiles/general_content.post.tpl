{script src="/js/addons/sd_geolocation/google.js"}
<div class="control-group">
    <label class="control-label">{__("user_location")} ({__("sd_geolocation_latitude_short")} &times; {__("sd_geolocation_longitude_short")}):</label>
    <label class="control-label hidden" for="elm_latitude">{__("sd_geolocation_latitude")}</label>
    <label class="control-label hidden" for="elm_longitude">{__("sd_geolocation_latitude")}</label>
    <div class="controls">
        <input type="hidden" id="elm_latitude_hidden" value="{$user_data.latitude}" />
        <input type="hidden" id="elm_longitude_hidden" value="{$user_data.longitude}" />
        <input type="text" name="user_data[latitude]" id="elm_latitude" value="{$user_data.latitude}" class="input-small">
        &times;
        <input type="text" name="user_data[longitude]" id="elm_longitude" value="{$user_data.longitude}" class="input-small">
        {assign var="target_id" value='sd_geo_user_location'}
        {include file="buttons/button.tpl"
            but_text=__("select")
            but_role="action"
            but_onclick="$.ceSDMap('showDialog', '`$target_id`')"
            allow_href=false
            but_meta="btn-primary cm-geo-user-location"}
    </div>
    <div title="{__('user_location')}" id="{$target_id}"><!--{$target_id}--></div>
</div>