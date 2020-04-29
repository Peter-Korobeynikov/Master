{if $user_data.user_id && $user_data.user_type == 'C'}
    {capture name="sidebar"}
        <div class="sidebar-row">
            <h6>{__("geo_maps.shipping_address_on_map")}</h6>
            {if $user_data.s_country_descr || $user_data.s_city || $user_data.s_address}
                <div class="cm-geo-map-container cm-aom-map-container"
                     data-ca-geo-map-controls-enable-zoom="true"
                     data-ca-geo-map-controls-enable-fullscreen="true"
                     data-ca-geo-map-controls-enable-layers="true"
                     data-ca-geo-map-controls-enable-ruler="true"
                     data-ca-geo-map-behaviors-enable-drag="true"
                     data-ca-geo-map-behaviors-enable-dbl-click-zoom="true"
                     data-ca-geo-map-behaviors-enable-multi-touch="true"
                     data-ca-geo-map-language="{$smarty.const.CART_LANGUAGE}"
                     data-ca-aom-country="{$user_data.s_country_descr}"
                     data-ca-aom-city="{$user_data.s_city}"
                     data-ca-aom-address="{$user_data.s_address}"
                ></div>
            {else}
                {__('no_data')}
            {/if}
        </div>
    {/capture}
{/if}
