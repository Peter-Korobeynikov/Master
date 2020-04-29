{script src="js/lib/owlcarousel/owl.carousel.min.js"}
<script>
(function(_, $) {
    $.ceEvent('on', 'ce.commoninit', function(context) {
        var elm = context.find('#scroll_list_{$block.block_id}');

        $('.ty-float-left:contains(.ty-scroller-list),.ty-float-right:contains(.ty-scroller-list)').css('width', '100%');

        var item = {$block.properties.item_quantity|default:5},
            itemsDesktop = 5,
            itemsDesktopSmall = 4;
            itemsTablet = 4;
            itemsTabletSmall = {$itemsTabletSmall|default:2};
            itemsMobile = 1;

        if (item > 3) {
            itemsDesktop = item;
            itemsDesktopSmall = item - {$itemsDesktopSmall|default:1};
            itemsTablet = item - {$itemsTablet|default:2};
        } else if (item == 1) {
            itemsDesktop = itemsDesktopSmall = itemsTablet = 1;
        } else {
            itemsDesktop = item;
            itemsDesktopSmall = itemsTablet = item - 1;
        }

        var desktop = [1199, itemsDesktop],
            desktopSmall = [1023, itemsDesktopSmall],
            tablet = [767, itemsTablet],
            tabletSmall = [479, itemsTabletSmall];
            mobile = [319, itemsMobile];

        {if $block.properties.outside_navigation == "Y"}
        function outsideNav () {
            if(this.options.items >= this.itemsAmount){
                $("#owl_outside_nav_{$block.block_id}").hide();
            } else {
                $("#owl_outside_nav_{$block.block_id}").show();
            }
        }
        {/if}

        if (elm.length) {
            elm.owlCarousel({
                direction: '{$language_direction}',
				items: item,
                itemsDesktop: desktop,
                itemsDesktopSmall: desktopSmall,
                itemsTablet: tablet,
                itemsTabletSmall: tabletSmall,
                itemsMobile: mobile,
                {if $block.properties.scroll_per_page == "Y"}
                scrollPerPage: true,
                {/if}
                {if $block.properties.not_scroll_automatically == "Y"}
                autoPlay: false,
                {else}
                autoPlay: '{$block.properties.pause_delay * 1000|default:0}',
                {/if}
                lazyLoad: true,
                slideSpeed: {$block.properties.speed|default:400},
                stopOnHover: true,
                {if $block.properties.outside_navigation == "N"}
                navigation: true,
                navigationText: ['<i class="ty-icon-left-open-thin"></i>', '<i class="ty-icon-right-open-thin"></i>'],
                {/if}
                pagination: false,

                afterUpdate: function(){
                    fn_abt__ut2_calc_cell('afterUpdate');
                },

            {if $block.properties.outside_navigation == "Y"}
                afterInit: outsideNav,
                afterUpdate : outsideNav
                });

                $('{$prev_selector}').click(function(){
                    elm.trigger('owl.prev');
                });

                $('{$next_selector}').click(function(){
                    elm.trigger('owl.next');
                });
            {else}
                });
            {/if}
        }
    });
}(Tygh, Tygh.$));
</script>
