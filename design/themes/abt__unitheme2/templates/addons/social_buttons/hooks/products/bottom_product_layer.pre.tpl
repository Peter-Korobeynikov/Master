{strip}
{if $provider_settings && $settings.abt__ut2.products.addon_social_buttons.view[$settings.abt__device] == 'Y'}
	<div class="ut2-pb__share-block">
	    <div class="share-link"><i class="ut2-icon-share"></i>{__("abt__ut2.addon_social_buttons.share")}</div>
	    <div class="ut2-social-buttons ut2-upload-block" data-ut2-object-type="product" data-ut2-object-dispatch="products.view" data-ut2-action="social_buttons" data-ut2-object-id="{$product.product_id}"></div>
	</div>
{/if}
{/strip}