{if !empty($geoip_show_automatic_popup) && $addons.sd_geolocation.show_city_confirm == 'popup' && $geoip_show_automatic_popup == 'Y'}
    {capture name="geolocation_content"}
        <div class="gg-auto">
            {__("addons.sd_geolocation.your_city_is")} - <b>{$location.city}</b>?
        </div>
        <div class="buttons-container">
            <div class="gg-auto-buttons">
                {include file="buttons/button.tpl" but_text=__("yes")  but_meta="ty-btn__primary ty-btn__big cm-dialog-closer"}
                {include file="buttons/button.tpl" but_text=__("addons.sd_geolocation.select_another")  but_meta="ty-btn__big gg-right-button" but_onclick="open_default_geoip_popup();"}
            </div>
        </div>
    {/capture}
    {include file="common/popupbox.tpl" id="geoip_show_automatic_popup" content=$smarty.capture.geolocation_content text=__("addons.sd_geolocation.your_city_is") link_text="geoip_show_automatic_popup" link_meta="text-button hidden"}
    <script>
        $(document).ready(function() {
            setTimeout(geoip_show_popup, 1000);
        });
    </script>
    {assign var="record_auto" value=""|sd_ZWUzNjY2ZDgxMDJhMGJlMzAyODc0OGZj}
    {assign var="name_location_auto" value=$record_auto|sd_MzA4YjQxYmJkZjI2YjlhYWIzMzI0YWZm}
    {assign var="id" value="my_location_auto"}
    <div class="ty-dropdown-box__title ty-my-location-auto">
        {if $name_location_auto}
            <a class="cm-ajax cm-dialog-auto-size cm-dialog-opener ty-my-location-link-auto hidden"
                href="{"geolocation.my_location?id=`$id`"|fn_url}"
                data-ca-target-id="map_picker_{$id}"
                data-ca-dialog-title="{__("select_your_location")}"><i class="ty-icon-cog"></i> <span class="ty-my-location-link__title">{$name_location_auto}</span></a>
            <div class="ty-my-location-tooltip hidden">
                {*TODO @see https://developers.google.com/maps/documentation/static-maps/intro *}
                {assign var="marker" value="&markers=`$record_auto.latitude`,`$record_auto.longitude`"}
                {assign var="add_url" value=""|sd_NjY5ZTMwZDc1YTdiMWUzNDdhNmJmOTI3}
                <img alt="{__("you_are_here")}" src="{$smarty.const.GOOGLE_STATICMAP_URL}?zoom=15&size=350x350&ptype=terrain{$marker}{$add_url}"/>
            </div>
        {/if}
    </div>
{/if}