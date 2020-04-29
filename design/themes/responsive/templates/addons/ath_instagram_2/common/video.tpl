<video playsinline="" poster="{if $media.images}{$media.images.$image_size.url}{else}{$alt_poster_url}{/if}" preload="none" src="{$media.videos.low_resolution.url}" type="video/mp4" controls="controls" style="width:100%"></video>
{* old variant
<video src="{$media.videos.low_resolution.url}" poster="{$media.images.$image_size.url}" style="width:100%" controls="controls">
    <a href="{$media.link}" class="ath_instagram_feed-post-img-link" target="_blank">
		<img src="{$media.images.$image_size.url}" alt="" class="ath_instagram_feed-post-img">
	</a>
</video>
*}
