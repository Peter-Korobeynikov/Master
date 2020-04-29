<div class="object-picker__destinations-main">
    <div class="object-picker__name">
        {if $view_mode === "external" && $type === "selection"}
            <a href="{literal}${data.url}{/literal}" class="object-picker__name-content object-picker__name-content--link">{literal}${data.destination}{/literal}</a>
        {else}
            <div class="object-picker__name-content">{literal}${data.destination}{/literal}</div>
        {/if}
    </div>
</div>
{if $type === "selection"}
    <div class="object-picker__shipping-delay">
        <input type="hidden"
               name="store_location_data[shipping_destinations][{literal}${data.destination_id}{/literal}][destination_id]"
               value="{literal}${data.destination_id}{/literal}"
        />
        <input type="hidden"
               name="store_location_data[shipping_destinations][{literal}${data.destination_id}{/literal}][position]"
               value="{literal}${data.position}{/literal}"
        />
        <input type="text"
               name="store_location_data[shipping_destinations][{literal}${data.destination_id}{/literal}][shipping_delay]"
               value="{literal}${data.shipping_delay}{/literal}"
               class="input-small"
        />
    </div>
    <div class="object-picker__warn-about-delay">
        <input type="hidden"
               name="store_location_data[shipping_destinations][{literal}${data.destination_id}{/literal}][warn_about_delay]"
               value="0"
        />
        <input type="checkbox"
               name="store_location_data[shipping_destinations][{literal}${data.destination_id}{/literal}][warn_about_delay]"
               value="1"
               class="checkbox--large"
               {literal}
                   ${data.warn_about_delay
                       ? `checked="checked"`
                       : ``
                   }
               {/literal}
        />
    </div>
{/if}
