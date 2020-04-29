{script src="js/lib/owlcarousel/owl.carousel.min.js"}
<script>
(function(_, $) {
$.ceEvent('on', 'ce.commoninit', function(context) {
var elm = context.find('#scroll_list_{$block.block_id}');
$('.ty-float-left:contains(.ty-scroller-list),.ty-float-right:contains(.ty-scroller-list)').css('width', '100%');
{if $block.properties.outside_navigation == "Y"}
function outsideNav () {
if(this.options.items >= this.itemsAmount){
$("#owl_outside_nav_{$block.block_id}").hide();
} else {
$("#owl_outside_nav_{$block.block_id}").show();
}
}
{/if}
var items = {$block.properties.item_quantity|default:5};
var itemsDesktop = items - 1,
itemsDesktopSmall = items - 2,
itemsTablet = items - 3;
if ( items < 5 ) {
itemsDesktop = itemsDesktopSmall = 3;
itemsTablet = 2;
} else if ( items === 1 ) {
itemsDesktop = itemsDesktopSmall = itemsTablet = 1;
}
var itemsTabletSmall = 2,
itemsMobile = 1;
if (elm.length) {
elm.owlCarousel({
direction: '{$language_direction}',
items: items,
itemsDesktop: [1400, itemsDesktop],
itemsDesktopSmall: [1230, itemsDesktopSmall],
itemsTablet: [1060, itemsTablet],
itemsTabletSmall: [768, itemsTabletSmall],
itemsMobile: [576, itemsMobile],
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
navigationText: ['{__("prev_page")}', '{__("next")}'],
{/if}
pagination: false
{if $block.properties.outside_navigation == "Y"},
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