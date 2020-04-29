{if $block.properties.img_link == "popup"}
	<a id="insta_img_link_{$media.id}" class="ath_insta_feed__post__media-link ath_insta_feed__item__media-link--popup" href="{$media.images.standard_resolution.url}">
{elseif $block.properties.img_link == "instagram"}
	<a href="{$link}" class="ath_insta_feed__post__media-link" target="_blank">
{else}
	<span class="ath_insta_feed__post__media-link">
{/if}	

	{if $media.type == "video"}
	<i class="fa_insta fa-videocam ath_insta_feed__post__video"></i>
	{/if}
	<img src="{$media.images.$image_size.url}" />
	
{if $block.properties.img_link == "popup" || $block.properties.img_link == "instagram"}
	</a>
{else}
	</span>
{/if}