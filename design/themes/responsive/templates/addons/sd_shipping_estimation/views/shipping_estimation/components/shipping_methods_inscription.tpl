<div class="ty-shipping-methods-inscription hidden" id="shipping_methods_inscription_{$block_id}">
    {if $resourse == 'sd_geolocation'}
        {__('destination')}:
        {if !$hide_change_location}
            <a class="cm-ajax cm-dialog-auto-size cm-dialog-opener ty-my-location-link"
                href="{"geolocation.my_location?id=`$location_id`"|fn_url}"
                data-ca-target-id="map_picker_{$location_id}"
                data-ca-dialog-title="{__("change_location")}">
                <span>{$location}</span>
            </a>
        {else}
            <b>{$location}</b>
        {/if}
    {else}
        {if !$hide_change_location}
            {if !empty($location)}
                {__('destination')}: <span id="shipping_methods_inscription_change_location_{$block_id}"><b>{$location}</b></span>
            {else}
                {__('destination')}: <span id="shipping_methods_inscription_change_location_{$block_id}"><b>{__('cant_detect_city')}</b></span>
            {/if}
        {else}
            <b>{$location}</b>
        {/if}
    {/if}
</div>
