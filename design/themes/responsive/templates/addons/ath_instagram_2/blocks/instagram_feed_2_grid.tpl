{** block-description:instagram_feed_grid **}
 
{assign var="columns" value=$block.properties.number_of_columns|default:"3"}
{assign var="indents" value=$block.properties.ath_insta_indents|default:"28"}

{* {$feed|fn_print_r} *}

{if $feed}

	{$feed_has_video = false}
	{$feed_has_album = false}
	<div class="ath_insta_feed{if $block.properties.img_link == "popup"} ath_insta_feed--popup{/if}">
	
		{strip}
		<div class="grid-list ath_insta_feed__grid" style="margin-left: -{$indents}px;">
		{foreach from=$feed item="media" key="key" name="feed"}		
			{if $media}
			<div class="ty-column{$columns}">
	
			    {include file="addons/ath_instagram_2/common/square_default.tpl"}
				
				{if $media.media_type == "VIDEO"}
					{$feed_has_video = true}			
				{/if}
				{if $media.media_type == "CAROUSEL_ALBUM"}
					{$feed_has_album = true}			
				{/if}				
			</div>
			{/if}
			{if $smarty.foreach.feed.iteration == $block.properties.limit}
				{break}
			{/if}			
		{/foreach}
		</div>
		{/strip}
		
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
	
	{if $feed_has_album}	
		<script type="text/javascript">
		(function(_, $) {
		    $.ceEvent('on', 'ce.commoninit', function(context) {
		        var slider = context.find('.ath_insta_feed__grid__item__media-link__album');
		        if (slider.length) {
		            slider.owlCarousel({
		                direction: '{$language_direction}',
		                items: 1,
		                singleItem : true,                
		                autoPlay : true,
		                stopOnHover: true,
		                pagination: false
		            });
		        }
		    });
		}(Tygh, Tygh.$));
		</script>
	{/if}
{/if}
