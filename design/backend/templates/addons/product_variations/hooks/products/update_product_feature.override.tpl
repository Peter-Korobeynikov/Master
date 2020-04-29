{if $feature.product_variation_group}
    <div class="control-group">
        <label class="control-label" for="feature_{$feature_id}">{$feature.description}</label>
        <div class="controls">
            {foreach $feature.variants as $variant}
                {if $variant.selected}
                    <span class="shift-input">{$variant.variant}</span>
                {/if}
            {/foreach}
            {include file="common/tooltip.tpl" tooltip=__("product_variations.feature_used_by_variation_group.tooltip", ["[code]" => $feature.product_variation_group.code])}
        </div>
    </div>
{/if}