<div class="ut2-qd">
    <div class="ut2-qd__title">{__("text_qty_discounts")}:</div>
    <div class="ut2-qd__wrap">
        <div class="ut2-qd__col">
	        <div class="ut2-qd__label">{__("quantity")}</div>
	        {foreach from=$product.prices item="price"}
	            <div class="ut2-qd__item">{$price.lower_limit}+</div>
	        {/foreach}
        </div>
        <div class="ut2-qd__col">
	        <div class="ut2-qd__label">{__("price")}</div>
	        {foreach from=$product.prices item="price"}
	            <div class="ut2-qd__price">{include file="common/price.tpl" value=$price.price}</div>
	        {/foreach}
        </div>
    </div>
</div>