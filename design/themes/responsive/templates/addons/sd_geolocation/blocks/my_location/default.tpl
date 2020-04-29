{** block-description:default **}

{assign var="record" value=""|sd_ZWUzNjY2ZDgxMDJhMGJlMzAyODc0OGZj}
{assign var="name_location" value=$record|sd_MzA4YjQxYmJkZjI2YjlhYWIzMzI0YWZm}
{assign var="id" value="my_location"}
<div class="ty-my-location">
    {if $name_location}
        <a class="cm-ajax cm-dialog-auto-size cm-dialog-opener ty-my-location-link"
            href="{"geolocation.my_location?id=`$id`"|fn_url}"
            data-ca-target-id="map_picker_{$id}"
            data-ca-dialog-title="{__("select_your_location")}">
            {include file="addons/sd_geolocation/common/icon.tpl"}
            <span class="ty-my-location-link__title">{$name_location}</span>
        </a>
        <div class="ty-my-location-tooltip">
            {if $addons.sd_geolocation.show_city_confirm == 'tooltip' && $geoip_show_automatic_popup == 'Y'}
                <div class="ty-my-location-tooltip__controls">
                    <div class="gg-auto">
                        <span>{__("addons.sd_geolocation.your_city_is")}</span> - <span class="ty-strong">{$name_location}?</span>
                    </div>
                    <div class="buttons-container">
                        <div class="gg-auto-buttons">
                            {include file="buttons/button.tpl" but_text=__("yes") but_meta="ty-btn__primary ty-btn__big"}
                            {include file="buttons/button.tpl" but_text=__("addons.sd_geolocation.select_another")  but_meta="ty-btn__tertiary ty-btn__big gg-right-button" but_onclick="$('.ty-my-location-link').click();"}
                        </div>
                    </div>
                </div>
            {/if}
            {if $addons.sd_geolocation.show_map_on_hover == 'Y'}
                <div class="ty-my-location-tooltip__map">
                    {assign var="marker" value="&markers=`$record.latitude`,`$record.longitude`"}
                    {assign var="add_url" value=true|sd_NjY5ZTMwZDc1YTdiMWUzNDdhNmJmOTI3}
                    <img alt="{__("you_are_here")}" src="{$smarty.const.GOOGLE_STATICMAP_URL}?zoom=15&size=350x350&ptype=terrain{$marker}{$add_url}"/>
                </div>
            {/if}

        </div>
    {/if}
</div>