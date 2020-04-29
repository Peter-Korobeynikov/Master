<div class="ath_insta_feed__grid__item ath_insta_feed__grid__item--{$media.media_type} ath_insta_feed__grid__item__hover-style--{$block.properties.hover_style}" style="padding: 0 0 {$indents}px {$indents}px;">
    <div class="ath_insta_feed__grid__item__media ath_insta_feed__grid__item__media__{$media.media_type}">
	{if $block.properties.img_link == "popup"}
		<a id="insta_img_link_{$media.id}" class="ath_insta_feed__grid__item__media-link ath_insta_feed__item__media-link--popup" href="{$media.media_url}">
	{elseif $block.properties.img_link == "instagram"}
		<a href="{$media.permalink}" class="ath_insta_feed__grid__item__media-link" target="_blank">
	{else}
		<span class="ath_insta_feed__grid__item__media-link">
    {/if}
    
    	{if $media.media_type == "VIDEO"}

	    	<i class="fa_insta fa-video ath_insta_feed__grid__item__video ath_insta_feed__grid__item__icon"></i>

            {if empty($media.thumbnail_url) }            
			    <video class="ath_insta_feed__grid__item__media-link__video lazyInst" data-src="{$media.media_url}" loop muted></video>
            {else}
                <img class="ath_insta_feed__grid__item__media-link__img lazyInst" data-src="{$media.thumbnail_url}" />
            {/if}
			
		{elseif $media.media_type == "CAROUSEL_ALBUM"}
			<i class="fa_insta fa-album ath_insta_feed__grid__item__album ath_insta_feed__grid__item__icon"></i>
			{if $template_type == "scroller"}
				{if $media.children.data.0.media_type == "VIDEO"}				
					<video class="ath_insta_feed__grid__item__media-link__video lazyInst" data-src="{$media.children.data.0.media_url}" loop muted></video>
				{else}
					<img class="ath_insta_feed__grid__item__media-link__img lazyInst" data-src="{$media.children.data.0.media_url}" />
				{/if}
			{else}
				<div class="ath_insta_feed__grid__item__media-link__album">
					{foreach from=$media.children.data item="media_children" key="key_children" name="feed_children"}
						<div class="ath_insta_feed__grid__item__media-link__album__item">
						{if $media_children.media_type == "VIDEO"}
							<video class="ath_insta_feed__grid__item__media-link__video lazyInst" data-src="{$media_children.media_url}" loop muted></video>
						{else}
							<img class="ath_insta_feed__grid__item__media-link__album__img lazyInst" data-src="{$media_children.media_url}" />
						{/if}
						</div>
					{/foreach}
				</div>
			{/if}
    	{else}
    		<img class="ath_insta_feed__grid__item__media-link__img lazyInst" data-src="{$media.media_url}" />
    	{/if}
    	{if $block.properties.hover_style == "info"}
    	<span class="ath_insta_feed__grid__item__media-info">
    		<span class="ath_insta_feed__grid__item__media__inner">
		    	<span class="ath_insta_feed__grid__item__media-info__count ath_insta_feed__grid__item__media-info__count--likes">
		    		<i class="ath_insta_feed__grid__item__media-info__count__icon fa_insta fa-heart"></i> {$media.like_count}
		    	</span>
		    	<span  class="ath_insta_feed__grid__item__media-info__count ath_insta_feed__grid__item__media-info__count--comments">
		    		<i class="ath_insta_feed__grid__item__media-info__count__icon fa_insta fa-comment"></i> {$media.comments_count}
		    	</span>
	    	</span>
    	</span>
    	{elseif $block.properties.hover_style == "description"}
    	<span class="ath_insta_feed__grid__item__media-info ath_insta_feed__grid__item__media-info--description">
			<span class="ath_insta_feed__grid__item__media-info__text">

				{$media.caption nofilter}

			</span>
    	</span>
    	{/if}
    	
    {if $block.properties.img_link == "popup" || $block.properties.img_link == "instagram"}
		</a>
	{else}
		</span>
    {/if}
    	
	</div>
</div>
