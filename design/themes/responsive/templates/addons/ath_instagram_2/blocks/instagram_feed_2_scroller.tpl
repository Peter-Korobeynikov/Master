{** block-description:instagram_feed_scroller **}

{if $feed}
{$feed_has_video = false}

<div class="ath_insta_feed{if $block.properties.img_link == "popup"} ath_insta_feed--popup{/if}">

	{if $block.properties.outside_navigation == "Y"}
	    <div class="owl-theme ty-owl-controls">
	        <div class="owl-controls clickable owl-controls-outside"  id="owl_outside_nav_{$block.block_id}">
	            <div class="owl-buttons">
	                <div id="owl_prev_{$obj_prefix}" class="owl-prev"><i class="ty-icon-left-open-thin"></i></div>
	                <div id="owl_next_{$obj_prefix}" class="owl-next"><i class="ty-icon-right-open-thin"></i></div>
	            </div>
	        </div>
	    </div>
	{/if}


	{strip}
	<div class="ath_insta_feed__scroller-wrapper">
	<div id="scroll_list_{$block.block_id}" class="owl-carousel  ty-scroller-list ath_insta_feed__scroller">
	{foreach from=$feed item="media" key="key" name="feed"}
		{if $media}

			{include file="addons/ath_instagram_2/common/square_default.tpl" template_type="scroller"}

			{if $media.media_type == "VIDEO"}
				{$feed_has_video = true}			
			{/if}

			{if $smarty.foreach.feed.iteration == $block.properties.limit}
				{break}
			{/if}	
		{/if}
	{/foreach}
	</div>
	</div>
	{/strip}
	
	{include file="common/scroller_init.tpl" prev_selector="#owl_prev_`$obj_prefix`" next_selector="#owl_next_`$obj_prefix`"}
</div>

	{if $feed_has_video}
		<script>
			
			$('.ath_insta_feed__grid__item').mouseover(function() {
				$(this).find('.ath_insta_feed__grid__item__media-link__video').trigger('play');
			});
			$('.ath_insta_feed__grid__item').mouseout(function() {
				$(this).find('.ath_insta_feed__grid__item__media-link__video').trigger('pause');
			});
			
		</script>
	{/if}

{/if}
