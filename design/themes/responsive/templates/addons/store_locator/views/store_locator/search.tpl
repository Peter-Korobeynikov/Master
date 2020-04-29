{if $addons.geo_maps && $addons.geo_maps.status == "A"}
    {if $shipping.service_params.display}
        {$display_type = $shipping.service_params.display}
    {else}
        {$display_type = "ML"}
    {/if}
{else}
    {$display_type = "L"}
{/if}

{if $display_type != "L"}
    {$display_pickup_map = true}
{/if}

<form action="{"store_locator.search"|fn_url}" id="store_locator_search_form">
    <input type="hidden" name="result_ids" value="store_locator_search_controls,store_locator_location,store_locator_search_block_*" />
    <input type="hidden" name="full_render" value="Y" />

    <div id="store_locator_search_controls" class="store-locator__location--wrapper">
        <h2 class="store-locator__step-title">{__("store_locator.stores_and_pickup_points")}</h2>

        {if $cities}
            <div class="store-locator__location store-locator__location--city sl-search-control">
                <label class="store-locator__select-label" for="store_locator_search_city">{__("city")}:</label>
                <select name="sl_search[city]" id="store_locator_search_city" class="store-locator__select">
                    <option value="">{__("all")}</option>
                    {foreach $cities as $city}
                        <option value="{$city}" {if $sl_search.city === $city}selected{/if}>{$city}</option>
                    {/foreach}
                </select>
            </div>
        {/if}

    <!--store_locator_search_controls--></div>

<div id="store_locator_location">

    {if $store_locations}
        <div class="store-locator">
            {include file="addons/store_locator/components/map_and_list_store.tpl" store_locations=$store_locations group_key=0 store_locations_count=$store_locations_count}
        </div>
    {else}
        <p class="ty-no-items">{__("no_data")}</p>
    {/if}
<!--store_locator_location--></div>
{script src="js/addons/store_locator/pickup_search.js"}
{script src="js/addons/store_locator/map.js"}
</form>
