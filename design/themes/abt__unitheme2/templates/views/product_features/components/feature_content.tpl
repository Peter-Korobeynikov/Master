<div id="product_features_{$block.block_id}">
    <div class="ut2-feat-container{if $settings.abt__ut2.features.description_position == 'bottom'} reverse{/if}">
        {if $settings.abt__ut2.features.description_position != 'none'}
            <div class="ty-feature">
                <div class="ty-feature__description ty-wysiwyg-content">
                    {if $variant_data.image_pair}
                        <div class="ty-feature__image">
                            {include file="common/image.tpl" images=$variant_data.image_pair}
                        </div>
                    {/if}
                    {if $variant_data.url}
                        <p><a href="{$variant_data.url}">{$variant_data.url}</a></p>
                    {/if}
                    {$variant_data.description nofilter}
                </div>
            </div>
        {/if}

        {if $products}
            <div class="ab-ut2-feature-content">
                {hook name="product_features:ab__additional_data"}
                {assign var="layouts" value=""|fn_get_products_views:false:0}
                {if $layouts.$selected_layout.template}
                    {include file="`$layouts.$selected_layout.template`" columns=$settings.Appearance.columns_in_products_list}
                {/if}
                {/hook}
            </div>
        {else}
            <p class="ty-no-items">{__("text_no_products")}</p>
        {/if}
    </div>
    <!--product_features_{$block.block_id}--></div>