{if !$ab__mb_viewed_templates.ab__mb_geo_maps}
    {include
        file = "addons/geo_maps/views/geo_maps/shipping_estimation.tpl"
        shipping_methods = null
        product_id = $product.product_id|default:null
    }
{/if}