{if $product.promotions}
    {$promotion = $product.promotions|key|fn_ab__dotd_get_cached_promotion_data}
    <div class="ab__deal_of_the_day">
    		<div class="col1">
	        <div class="action-title">{$promotion.name}</div>
	        <div class="promotion-descr">{$promotion.short_description nofilter}</div>
        </div>
		{if $promotion.to_date && $promotion.to_date > $smarty.now}
        <div class="col2">
	            <b class="time-left">{__('ab__dotd_time_left')}:</b>
	            {include file="addons/ab__deal_of_the_day/components/init_countdown.tpl"}
		</div>
		{/if}
	      
        <div class="actions-link">
	        {if $product.promotions|count > 1}
	        	<i class="cm-tooltip ty-icon-help-circle" title="{__('ab__dotd.all_promotions.title')}"></i>
	        	<a class="also-in-promos-link cm-external-click" 
	        	data-ca-scroll="content_ab__deal_of_the_day" 
	        	data-ca-external-click-id="ab__deal_of_the_day">{__('ab__dotd.all_promotions')}</a>
	        {/if}
            {if $promotion.status == 'A'}
	        	<a class="ty-float-right" href="{"promotions.view?promotion_id=`$promotion.promotion_id`"|fn_url}" title="" target="_blank">{__('ab__dotd.detailed')}</a>
            {/if}
		</div>
    </div>
{/if}