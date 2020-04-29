{if ($product.avail_since > $smarty.const.TIME)}
	{include file="common/coming_soon_notice.tpl" avail_date=$product.avail_since add_to_cart=$product.out_of_stock_actions}
{/if}