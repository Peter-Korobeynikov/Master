{** block-description:carousel **}

{if $items}
	<div id="banner_slider_{$block.snapping_id}" class="banners owl-carousel{if $block.properties.skin != "D"} {$block.properties.skin} owl-carousel-not-default{/if}">
		{foreach from=$items item="banner" key="key"}
			<div class="ty-banner__image-item">
				{if $banner.type == "G" && $banner.main_pair.image_id}
					{if $banner.url != ""}<a class="banner__link" href="{$banner.url|fn_url}" {if $banner.target == "B"}target="_blank"{/if}>{/if}
						{include file="common/image.tpl" images=$banner.main_pair class="ty-banner__image"}
					{if $banner.url != ""}</a>{/if}
				{else}
					<div class="ty-wysiwyg-content">
						{$banner.description nofilter}
					</div>
				{/if}
			</div>
		{/foreach}
	</div>
{/if}

<script type="text/javascript">
	$(document).ready(function() {
		
		{if $block.properties.progress_bar != "N"}
			var time = {$block.properties.speed|default:4};
		{/if}

		$('#banner_slider_{$block.snapping_id}').owlCarousel({
			items: 1,
			singleItem : true,
			slideSpeed: {$block.properties.speed|default:400},
			autoPlay: '{$block.properties.delay * 1000|default:false}',
			{if $block.properties.stop_on_hover == "Y"}
				stopOnHover: true,
			{/if}
			transitionStyle : '{$block.properties.transitionStyle|default:fadeup}',
			{if $block.properties.pager == "N"}
				pagination: false,
			{/if}
			{if $block.properties.pager == "D"}
				pagination: true,
			{/if}
			{if $block.properties.pager == "P"}
				pagination: true,
				paginationNumbers: true,
			{/if}
			
			{if $block.properties.arrows == "Y"}
				navigation: true,
				navigationText: ['{__("prev_page")}', '{__("next")}'],
			{/if}
			
			{if $block.properties.progress_bar != "N"}
				afterInit : progressBar,
				afterMove : moved,
				startDragging : pauseOnDragging,
			{/if}
		});

		{if $block.properties.progress_bar != "N"}
			function progressBar(elem){
				$elem = elem;
				//build progress bar elements
				buildProgressBar();
				//start counting
				start();
			}

			function buildProgressBar(){
				$progressBar = $("<div>",{
					id:"progressBar",
					{if $block.properties.progress_bar == "B"}
					class:"progressBar-bottom"
					{/if}
				});
				$bar = $("<div>",{
					id:"bar"
				});
				$progressBar.append($bar).prependTo( $elem.find(".owl-wrapper-outer") );
			}

			function start() {
				//reset timer
				percentTime = 0;
				isPause = false;
				//run interval every 0.01 second
				tick = setInterval(interval, 10);
			};

			function interval() {
				if(isPause === false){
					percentTime += 1 / time;
					$bar.css({
					width: percentTime+"%"
					});
					//if percentTime is equal or greater than 100
					if(percentTime >= 100){
					//slide to next item 
					$elem.trigger('owl.next')
					}
				}
			}

			function pauseOnDragging(){
				isPause = true;
			}

			function moved(){
				//clear interval
				clearTimeout(tick);
				//start again
				start();
			}
			
			{if $block.properties.stop_on_hover == "Y"}
				$elem.on('mouseover',function(){
					isPause = true;
				})
				$elem.on('mouseout',function(){
					isPause = false;
				})
			{/if}
		{/if}
		
	});
</script>


{*
//! Alternative

<script type="text/javascript">
(function(_, $) {
    $.ceEvent('on', 'ce.commoninit', function(context) {
	    
        var slider = context.find('#banner_slider_{$block.snapping_id}');
		
		{if $block.properties.progress_bar != "N"}
			var time = {$block.properties.speed|default:4};
		{/if}

		slider.owlCarousel({
			items: 1,
			singleItem : true,
			slideSpeed: {$block.properties.speed|default:400},
			autoPlay: '{$block.properties.delay * 1000|default:false}',
			{if $block.properties.stop_on_hover == "Y"}
				stopOnHover: true,
			{/if}
			transitionStyle : '{$block.properties.transitionStyle|default:fadeup}',
			{if $block.properties.pager == "N"}
				pagination: false,
			{/if}
			{if $block.properties.pager == "D"}
				pagination: true,
			{/if}
			{if $block.properties.pager == "P"}
				pagination: true,
				paginationNumbers: true,
			{/if}
			
			{if $block.properties.arrows == "Y"}
				navigation: true,
				navigationText: ['{__("prev_page")}', '{__("next")}'],
			{/if}
			
			{if $block.properties.progress_bar != "N"}
				afterInit : progressBar,
				afterMove : moved,
				startDragging : pauseOnDragging,
			{/if}
		});

		{if $block.properties.progress_bar != "N"}
			function progressBar(elem){
				$elem = elem;
				//build progress bar elements
				buildProgressBar();
				//start counting
				start();
			}

			function buildProgressBar(){
				$progressBar = $("<div>",{
					id:"progressBar",
					{if $block.properties.progress_bar == "B"}
					class:"progressBar-bottom"
					{/if}
				});
				$bar = $("<div>",{
					id:"bar"
				});
				$progressBar.append($bar).prependTo( $elem.find(".owl-wrapper-outer") );
			}

			function start() {
				//reset timer
				percentTime = 0;
				isPause = false;
				//run interval every 0.01 second
				tick = setInterval(interval, 10);
			};

			function interval() {
				if(isPause === false){
					percentTime += 1 / time;
					$bar.css({
					width: percentTime+"%"
					});
					//if percentTime is equal or greater than 100
					if(percentTime >= 100){
					//slide to next item 
					$elem.trigger('owl.next')
					}
				}
			}

			function pauseOnDragging(){
				isPause = true;
			}

			function moved(){
				//clear interval
				clearTimeout(tick);
				//start again
				start();
			}
			
			{if $block.properties.stop_on_hover == "Y"}
				$elem.on('mouseover',function(){
					isPause = true;
				})
				$elem.on('mouseout',function(){
					isPause = false;
				})
			{/if}
		{/if}
		
    });
}(Tygh, Tygh.$));
</script>

*}

