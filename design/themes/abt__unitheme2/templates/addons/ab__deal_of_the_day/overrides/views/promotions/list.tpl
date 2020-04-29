{include file="common/pagination.tpl"}

{$image_width="290"}
{$image_height="176"}

<div class="ab__dotd_promotions clearfix">
    {foreach $promotions as $promotion}
        {if $promotion.promotion_id}
        <div class="ab__dotd_promotions-item {if !$promotion.ab__dotd_active}ab__dotd_promotion_expired{/if}">
            <div class="ab__dotd_promotions-item_image">
                <a href="{"promotions.view?promotion_id=`$promotion.promotion_id`"|fn_url}">
                    {include file="common/image.tpl" images=$promotion.list_pair image_width=$image_width image_height=$image_height lazy_load=true no_ids=true}
                </a>
            </div>
            {if $promotion.to_date && $promotion.ab__dotd_active}
                {assign var="days_left" value=(($promotion.to_date-$smarty.now)/86400)|ceil}
                <div class="ab__dotd_promotions-item_days_left{if $days_left <= $addons.ab__deal_of_the_day.highlight_when_left} ab__dotd_highlight{/if}">
                    {if $days_left > 1}
                        {__('ab__dotd.days_left', [$days_left])}
                    {else}
                        {__('ab__dotd.today_only')}
                    {/if}
                </div>
            {elseif $promotion.ab__dotd_awaited}
                {assign var="days_left" value=(1-($smarty.now-$promotion.from_date)/86400)|floor}
                <div class="ab__dotd_promotions-item_days_left">
                    {__('ab__dotd.days_to_start', [$days_left])}
                </div>
            {elseif $promotion.ab__dotd_expired}
                <div class="ab__dotd_promotions-item_days_left">
                    {__('ab__dotd.promotion_expired')}
                </div>
            {/if}

            <div class="ab__dotd_promotions-item_date">
                {if $promotion.from_date}
                    {__('ab__dotd.from')} {$promotion.from_date|date_format:"`$settings.Appearance.date_format`"}
                {/if}
                {if $promotion.to_date}
                    {__('ab__dotd.to')} {$promotion.to_date|date_format:"`$settings.Appearance.date_format`"}
                {/if}
            </div>
            <div class="ab__dotd_promotions-item_title"><a href="{"promotions.view?promotion_id=`$promotion.promotion_id`"|fn_url}">{$promotion.name nofilter}</a></div>
        </div>
        {/if}
        {foreachelse}
        <p>{__("text_no_active_promotions")}</p>
    {/foreach}
</div>
{include file="common/pagination.tpl"}

{if $show_chains && $chains}
    <div class="ab__dotd_chains">
        <div class="ab__dotd_chains_title ty-subheader">{__('ab__dotd.chains_list.title')}</div>
        <div class="ab__dotd_chains_content">
            {include file="addons/buy_together/blocks/product_tabs/buy_together.tpl"}
            {if $chains_search.total_pages > 1}
                <div class="ab__dotd_chains-show_more">
                    <span class="ab__dotd-text_get_more">{__('ab__dotd.get_more_combinations', [$chains_search.more])}</span>
                    <span class="ab__dotd-text_showed">{__('ab__dotd.showed_combinations', ['[n]' => $chains_search.items_per_page, '[total]' => $chains_search.total_items])}</span>
                </div>
            {/if}
        </div>
    </div>
{/if}

{capture name="mainbox_title"}{__("promotions")}{/capture}