{assign var="id" value=$ab__mb_id|default:"ab__mb_id_`$block.block_id`"}

{assign var="params" value=$smarty.request}

{if !$product && $params.product_id}
    {$product = $params.product_id|fn_get_product_data:$smarty.session.auth}
{/if}

{if $addons.ab__motivation_block.use_additional_categories == 'Y'}
    {$params.category_ids = $product.category_ids}
{else}
    {$params.category_ids = [$product.main_category]}
{/if}

{if "MULTIVENDOR"|fn_allowed_for && $product.company_id}
    {$params.company_id = $product.company_id}
{else}
    {$params.company_id = fn_get_runtime_company_id()}
{/if}

{$ab__mb_items = $params|fn_ab__mb_get_motivation_items:0:'C'}
{$ab__mb_items = $ab__mb_items|reset}


<div class="ab__motivation_block ab__{$addons.ab__motivation_block.template_variant}{if $ab__mb_items} loaded{/if}" data-ca-product-id="{$product.product_id}" data-ca-result-id="{$id}">
    <div id="{$id}">
        {if $ab__mb_items}
            <div class="ab__mb_items {$addons.ab__motivation_block.appearance_type_styles}{if $addons.ab__motivation_block.bg_color !="#ffffff"} colored{/if}{if $runtime.customization_mode.live_editor} ab-mb-live-editor{/if}"{if $addons.ab__motivation_block.bg_color !="#ffffff"} style="border-color: {$addons.ab__motivation_block.bg_color};"{/if}>
                {include file="addons/ab__motivation_block/blocks/components/`$addons.ab__motivation_block.template_variant`.tpl"}
            </div>
        {/if}
    <!--{$id}--></div>
</div>