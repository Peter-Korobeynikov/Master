{if $content|trim}
    <div class="litecheckout__container bm-block-manager__block bm-block-manager__block--{$location_data.dispatch}" data-ca-block-manager-snapping-id="{$block.snapping_id}">
        {include file="backend:views/block_manager/frontend_render/components/block_menu.tpl"}
        <div class="litecheckout__group">
            <div class="litecheckout__item">
                <h2 class="litecheckout__step-title">{$block.name}</h2>
            </div>
        </div>
        {$content nofilter}
    </div>
{/if}