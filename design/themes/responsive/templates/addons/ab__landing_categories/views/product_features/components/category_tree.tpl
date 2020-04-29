<div class="row-fluid {if $settings.Appearance.columns_in_products_list =="5"}v-col{/if}{if $settings.Appearance.columns_in_products_list =="4"}f-col{/if}{if $settings.Appearance.columns_in_products_list =="3"}t-col{/if}">
    {$show_max_item=$addons.ab__landing_categories.maximum_number_of_displayed_items|default:5}
    {foreach from=$items item="item1" name="item1"}
        {if $item1}
            <div class="ab-lc-group">
                <div class="head">
                    <div class="cat-title"><a href="{fn_ab__lc_prepare_url_params($item1.category_id, $ab__lc_variant_id)|fn_url}">{$item1.category}</a></div>
                </div>

                {if !empty($item1.subcategories)}
                    <ul class="items-level-2">
                        {foreach from=$item1.subcategories item="item2" name="item2"}
                            {if $item2}
                                
                                {if $smarty.foreach.item2.iteration > $show_max_item}{break}{/if}
                                <li data-subcategories="{if !empty($item2.subcategories)}Y{else}N{/if}">
                                <a href="{fn_ab__lc_prepare_url_params($item2.category_id, $ab__lc_variant_id)|fn_url}">{$item2.category}</a>
                                    {if !empty($item2.subcategories)}
                                        <ul class="items-level-3">
                                            {foreach from=$item2.subcategories item="item3"}
                                                <li><a href="{fn_ab__lc_prepare_url_params($item3.category_id, $ab__lc_variant_id)|fn_url}">{$item3.category}</a></li>
                                            {/foreach}
                                        </ul>
                                    {/if}
                                </li>
                            {/if}
                        {/foreach}
                    </ul>

                    {if count($item1.subcategories) > $show_max_item}
                        <ul class="hidden-items-level-2">
                            {foreach from=$item1.subcategories item="item2" name="item2"}
                                {if $item2}
                                    
                                    {if $smarty.foreach.item2.iteration <= $show_max_item}{continue}{/if}
                                    <li data-subcategories="{if !empty($item2.subcategories)}Y{else}N{/if}">
                                        <a href="{fn_ab__lc_prepare_url_params($item2.category_id, $ab__lc_variant_id)|fn_url}" data-subcategories="{if !empty($item2.subcategories)}Y{else}N{/if}">{$item2.category}</a>
                                        {if !empty($item2.subcategories)}
                                            <ul class="items-level-3">
                                                {foreach from=$item2.subcategories item="item3"}
                                                    <li><a href="{fn_ab__lc_prepare_url_params($item3.category_id, $ab__lc_variant_id)|fn_url}">{$item3.category}</a></li>
                                                {/foreach}
                                            </ul>
                                        {/if}
                                    </li>
                                {/if}
                            {/foreach}
                        </ul>
                        <span class="show-hidden-items-level-2">{__("ab__lc.catalog.show_more")}</span>
                    {/if}
                {/if}
            </div>
        {/if}
    {/foreach}
</div>
