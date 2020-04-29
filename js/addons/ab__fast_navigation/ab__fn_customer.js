/*******************************************************************************************
*   ___  _          ______                     _ _                _                        *
*  / _ \| |         | ___ \                   | (_)              | |              © 2020   *
* / /_\ | | _____  _| |_/ /_ __ __ _ _ __   __| |_ _ __   __ _   | |_ ___  __ _ _ __ ___   *
* |  _  | |/ _ \ \/ / ___ \ '__/ _` | '_ \ / _` | | '_ \ / _` |  | __/ _ \/ _` | '_ ` _ \  *
* | | | | |  __/>  <| |_/ / | | (_| | | | | (_| | | | | | (_| |  | ||  __/ (_| | | | | | | *
* \_| |_/_|\___/_/\_\____/|_|  \__,_|_| |_|\__,_|_|_| |_|\__, |  \___\___|\__,_|_| |_| |_| *
*                                                         __/ |                            *
*                                                        |___/                             *
* ---------------------------------------------------------------------------------------- *
* This is commercial software, only users who have purchased a valid license and accept    *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
(function (_, $) {
$.ceEvent('on', 'ce.ab__fn_init', function(context) {
if (_.ab__fn !== undefined) {
Object.keys(_.ab__fn.blocks).forEach(function (__item_id) {
var __settings = _.ab__fn.blocks[__item_id];
(function() {
var parent = context.find(`.ab-fn-block-${__item_id}`);
if (parent.length) {
if (__settings.first_level_scroller !== undefined) {
context.find(`#ab__fn-first-level-${__item_id}`).owlCarousel(__get_scroller_settings(__item_id, 1));
}
var items = parent.find(".ab-fn-first-level-item");
var subitems = parent.find(".ab-fn-second-level");
parent.find(".ab-fn-first-level").removeClass("ab-fn-clipped");
if (!subitems.hasClass("ab-fn-second-level-scroller")) {
var columns_opt = _.ab__fn.blocks[__item_id].columns;
if (columns_opt !== undefined) {
calculate_items_width(parent, columns_opt);
$(window).on("resize", function () {
calculate_items_width(parent, columns_opt);
});
}
}
items.on('click', function () {
var item = $(this);
var item_id = item.attr("data-item-id");
var subitems_wrap_id = "ab__fn-second-level-" + __item_id + '_' + item_id + '_' + _.cart_language;
var subitem_wrap = parent.find('#' + subitems_wrap_id);
if (!item.hasClass("active")) {
if (subitem_wrap.length && !subitem_wrap.is(".uploaded, .first")) {
subitem_wrap.addClass("uploaded");
var overlay = $(".ab-fn-overlay");
if (overlay.length) {
overlay.show();
} else {
overlay = $("<div class='ab-fn-overlay'><div class='ab-fn-load-progress'><span></span></div></div>").appendTo("body");
}
$.ceAjax('request', fn_url('ab__fast_navigation.load_subitems'), {
result_ids: subitems_wrap_id,
method: "POST",
data: {
id: subitems_wrap_id,
block_type: __settings["block_type"],
},
hidden: false,
caching: true,
callback: function () {
$.ceEvent('trigger', 'ce.ab__fn_init', [parent]);
overlay.hide();
items.removeClass("active");
subitems.removeClass("active");
item.addClass("active");
subitem_wrap.addClass("active");
}
});
} else {
items.removeClass("active");
subitems.removeClass("active");
item.addClass("active");
subitem_wrap.addClass("active");
}
}
});
parent.find(".delimeter-block").on('click', delimeter_action);
}
})();
if (__settings.second_level_scroller !== undefined) {
var second_level_scrollers = context.find(`[id^='ab__fn-second-level-${__item_id}'][id$='${_.cart_language}']:not(.inited-scroller)`);
second_level_scrollers.each(function(){
var wrapper = $(this);
if(wrapper.find("> *").length) {
wrapper.owlCarousel(__get_scroller_settings(__item_id, 2));
}
});
}
});
}
});
function calculate_items_width(parent, columns_opt) {
var window_width = window.innerWidth;
var columns = 0;
if(window_width > 1230) {
columns = columns_opt.number_of_columns_desktop;
} else if (window_width > 992 && window_width <= 1230) {
columns = columns_opt.number_of_columns_desktop_small;
} else if (window_width > 768 && window_width <= 992) {
columns = columns_opt.number_of_columns_tablet;
} else if (window_width > 576 && window_width <= 768) {
columns = columns_opt.number_of_columns_tablet_small;
} else {
columns = columns_opt.number_of_columns_mobile;
}
var subitems = parent.find(".ab-fn-second-level");
if (subitems.length) {
subitems.each(function () {
var subitem = $(this);
if (subitem.hasClass("clicked-delimeter")) return void(0);
subitem.find(".delimeter-block").detach();
var add_delimeter = ((!!(subitem.data("add-delimeter")) && !subitem.hasClass("clicked-delimeter")));
var subitem_siblings = subitem.find(".ab-fn-second-level-item");
var delimeter_iteration = 0;
if (add_delimeter && (subitem_siblings.length > columns)) {
subitem_siblings.each(function (index) {
var sibling = $(this);
var delimeter_str = this.className.split("delimeter-");
var second_part = ' ';
if (delimeter_str[1]) {
second_part += delimeter_str[1].substr(1);
}
this.className = delimeter_str[0] + " delimeter-" + delimeter_iteration + second_part;
if (!(index === subitem_siblings.length - 1) &&
((index + 2) === (delimeter_iteration * columns + columns))) {
delimeter_iteration = delimeter_iteration + 1;
var x = 0;
var y = subitem_siblings.length;
if ((subitem_siblings.length - (index + 1)) >= columns) {
x = columns;
} else {
x = subitem_siblings.length - (index + 1);
}
sibling.after(
"<div class='ab-fn-second-level-item ty-column" + columns + " delimeter-block' data-delimeter='" + delimeter_iteration + "'>" +
"<div>" +
"<div class='ab-fn-item-header'>" +
_.tr("ab__fn.delimeter_text").replace("[x]", x).replace("[y]", y) +
"</div>" +
"<span class='ab-fn-delimeter-plus'></span>" +
"</div>" +
"</div>"
);
}
});
} else {
subitem_siblings.addClass("delimeter-0");
}
});
}
$(".delimeter-block").on('click', delimeter_action);
var children = parent.find(".ab-fn-first-level-item, .ab-fn-second-level-item");
for ( var i = 0; i < children.length; i++ ) {
var str_arr = children[i].className.split("ty-column");
children[i].className = str_arr[0] + "ty-column" + columns + str_arr[1].substr(1);
$(children[i]).css("width", (100 / columns) + "%");
}
}
function delimeter_action() {
var delimeter = $(this);
var delimeter_parent = delimeter.parents(".ab-fn-second-level");
var delimeter_id = Number(delimeter.attr("data-delimeter"));
delimeter_parent.addClass("clicked-delimeter");
delimeter_parent.find(".delimeter-" + delimeter_id).css("display", "inline-flex");
delimeter_parent.find(".delimeter-block[data-delimeter='" + (delimeter_id + 1) + "']").css("display", "inline-flex");
delimeter.detach();
}
$.ceEvent('on', 'ce.commoninit', function (context) {
if ( window.innerWidth > 767 ) {
context.find(".ab-fn-parent").each(function () {
var parent = $(this);
var first = parent.find(".ab-fn-first-level-item:first");
var first_el_height = first.outerHeight();
parent.find(".ab-fn-second-level").css("min-height", first_el_height + "px");
});
}
$.ceEvent('trigger', 'ce.ab__fn_init', [context]);
});
function __get_scroller_settings(parent_id, level) {
return {
direction: _.language_direction,
navigation: true,
pagination: false,
navigationText: ["<i class='ty-icon-left-open-thin ab-fn-nav-buttons'></i>", "<i class='ty-icon-right-open-thin ab-fn-nav-buttons'></i>"],
items: _.ab__fn.blocks[parent_id].columns.number_of_columns_desktop,
itemsDesktopSmall: [1230, _.ab__fn.blocks[parent_id].columns.number_of_columns_desktop_small],
itemsTablet: [992, _.ab__fn.blocks[parent_id].columns.number_of_columns_tablet],
itemsTabletSmall: [768, _.ab__fn.blocks[parent_id].columns.number_of_columns_tablet_small],
itemsMobile: [576, _.ab__fn.blocks[parent_id].columns.number_of_columns_mobile],
afterInit: function() {
var scroller = $(this)[0];
var scroller_elem = scroller.$elem;
scroller_elem.addClass("inited-scroller");
},
afterMove: level === 1 ? function () {
var scroller = $(this)[0];
var tabs_content = $(`.ab-fn-second-level[id^='ab__fn-second-level-${parent_id}_']`);
var visible_elems = scroller.visibleItems;
var customer_elements = scroller.$userItems;
var active_index_of = visible_elems.indexOf(Number(customer_elements.filter(".active").attr("data-item-index")));
if (active_index_of === -1) {
if (Number(customer_elements.filter(".active").attr("data-item-index")) > visible_elems[visible_elems.length - 1]) {
customer_elements.removeClass("active");
customer_elements.eq(visible_elems[visible_elems.length - 1]).click();
tabs_content.removeClass("active");
tabs_content.eq(visible_elems[visible_elems.length - 1]).addClass("active");
} else {
customer_elements.removeClass("active");
customer_elements.eq(visible_elems[0]).click();
tabs_content.removeClass("active");
tabs_content.eq(visible_elems[0]).addClass("active");
}
}
} : function() {},
afterAction: function () {
var scroller = $(this)[0];
var scroller_elem = scroller.$elem;
if (!scroller_elem.hasClass("active") && level === 1)
return;
var visible_elems = scroller.visibleItems;
var customer_elements = scroller.$userItems;
if (visible_elems[0] === 0) {
scroller_elem.find(".owl-prev").addClass("ab-fn-hidden");
if (visible_elems[visible_elems.length - 1] !== customer_elements.length - 1) {
scroller_elem.find(".owl-next").removeClass("ab-fn-hidden");
}
} else if (visible_elems[visible_elems.length - 1] === customer_elements.length - 1) {
scroller_elem.find(".owl-next").addClass("ab-fn-hidden");
if (visible_elems[0] !== 0) {
scroller_elem.find(".owl-prev").removeClass("ab-fn-hidden");
}
} else {
scroller_elem.find(".owl-prev, .owl-next").removeClass("ab-fn-hidden");
}
if (_.ab__fn.blocks[parent_id].first_level_scroller.init_scrollbar === true && level === 1) {
var scrollbar = scroller_elem.siblings(".ab-fn-scrollbar").find(".ab-fn-scrollbar-plate");
var percents_by_item = 100 / (customer_elements.length - visible_elems.length);
var scroll_to = visible_elems[0] * percents_by_item;
scrollbar.css("width", Math.round(scroll_to) + '%');
}
},
}
}
})(Tygh, Tygh.$);