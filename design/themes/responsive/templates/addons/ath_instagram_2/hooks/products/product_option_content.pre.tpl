{if $addons.ath_instagram.hastag_in_product_options == "Y" && $product.product_hastag}
<div class="ty-product-block__insta">
	
	<div class="ty-control-group">
		<label class="ty-control-group__label ty-product-options__item-label"><i class="fa_insta fa-instagram"></i> {__("instagram")}</label>
		<span class="ty-control-group__item"><a href="//www.instagram.com/explore/tags/{$product.product_hastag}" target="_blank">#{$product.product_hastag}</a></span>
	</div>

</div>
{/if}