{** block-description:instagram_feed_posts **}

{if $feed.images}
<div class="ath_insta_feed{if $block.properties.img_link == "popup"} ath_insta_feed--popup{/if}">

	<div class="post-list ath_insta_feed__posts">
	{foreach from=$feed.images item="media" key="key" name="feed"}
		{if $media}

		    <div class="ath_insta_feed__post ath_insta_feed__post--{$media.type}">
			    <div class="ath_insta_feed__post__header">
				    <a href="https://www.instagram.com/{$media.user.username}" class="ath_insta_feed__post__header__user-link" target="_blank">
					    <img class="ath_insta_feed__post__header__user-photo" src="{$media.user.profile_picture}">
					    <span class="ath_insta_feed__post__header__user-name">{$media.user.username}</span>
				    </a>
				    
				    <span class="ath_insta_feed__post__header__media-time">
				    	{$media.created_time|date_format:$settings.Appearance.date_format}
				    </span>
			    </div>
				
				{include file="addons/ath_instagram/common/image.tpl" link=$media.link}
				
			    <div class="ath_insta_feed__post__info">
			    	<div class="ath_insta_feed__post__media-info">
				    	<span class="ath_insta_feed__post__media-info__count ath_insta_feed__post__media-info__count--likes">
					    	<a href="{$media.link}" class="ath_insta_feed__post__media-info__count" target="_blank">
					    		<i class="ath_insta_feed__post__media-info__count__icon fa_insta fa-heart"></i> {$media.likes.count}
					    	</a>
				    	</span>
				    	<span class="ath_insta_feed__post__media-info__count ath_insta_feed__post__media-info__count--comments">
					    	<a href="{$media.link}" class="ath_insta_feed__post__media-info__count" target="_blank">
					    		<i class="ath_insta_feed__post__media-info__count__icon fa_insta fa-comment"></i> {$media.comments.count}
					    	</a>
				    	</span>
			    	</div>
			    	
			    	{if $media.caption.text}
					<div class="ath_insta_feed__post__info__caption">
						{$media.caption.text nofilter}
					</div>
					{/if}
			    </div>
			</div>

		{/if}
	{/foreach}
	</div>

</div>

{if $block.properties.title_from_block != "Y"}
	{if $block.properties.title_link == "Y"}
		{capture name="title"}<span class="ath_insta_feed__title"><i class="fa_insta fa-instagram ath_insta_feed__title-logo"></i>{__("title_gallery")}{$feed.title_and_link nofilter}</span>{/capture}
	{else}
		{capture name="title"}<span class="ath_insta_feed__title"><i class="fa_insta fa-instagram ath_insta_feed__title-logo"></i>{__("title_gallery")}{$feed.title}</span>{/capture}
	{/if}
{/if}


{if $block.properties.img_link == "popup"}
	{include file="addons/ath_instagram/common/previewer.tpl"}
{/if}

{/if}
