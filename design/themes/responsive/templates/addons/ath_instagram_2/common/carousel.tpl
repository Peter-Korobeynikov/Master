<div id="ath_instagram_feed_post_{$media.id}" class="owl-carousel">
	{foreach from=$media.carousel_media item="media_attachment" key="key" name="attachment"}
		

		{if $media_attachment.images.$image_size.url}
			{include file="addons/ath_instagram/common/`$media_attachment.type`.tpl" media=$media_attachment link=$media.link}
		{else}
			{include file="addons/ath_instagram/common/`$media_attachment.type`.tpl" media=$media_attachment link=$media.link alt_poster_url=$media.images.$image_size.url}
		{/if}
		
	
		
	{/foreach}
</div>


<script type="text/javascript">
(function(_, $) {
    $.ceEvent('on', 'ce.commoninit', function(context) {
        var slider = context.find('#ath_instagram_feed_post_{$media.id}');
        if (slider.length) {
            slider.owlCarousel({
                direction: '{$language_direction}',
                items: 1,
                singleItem : true,
                slideSpeed:400,
                autoPlay: '5000',
                stopOnHover: true,

                    pagination: false,
                    navigation: true,
                    navigationText: ['{__("prev_page")}', '{__("next")}']

            });
        }
    });
}(Tygh, Tygh.$));
</script>