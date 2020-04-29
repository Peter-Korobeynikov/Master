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
(function(_, $) {
var wrapper_class = "ab-hm-first-level-section";
var prev_w_size = 0;
$(window).on("resize", function() {
_init_horizontal_menu_scroller($(_.doc));
});
function _init_horizontal_menu_scroller(context) {
if (prev_w_size !== window.innerWidth) {
prev_w_size = window.innerWidth;
var items = context.find(".ut2-h__menu .cm-responsive-menu > ." + wrapper_class + " > .ty-menu__item");
if (items.length) {
items.removeClass("added-to-selection").unwrap();
context.find(".ab-hm-first-level-toggler").remove();
_rebuild_horizontal_menu(context);
} else {
_rebuild_horizontal_menu(context);
}
}
}
function _rebuild_horizontal_menu(context) {
var items_wrapper = context.find(".ut2-h__menu .cm-responsive-menu");
if (items_wrapper.length) {
var available_space = items_wrapper.outerWidth();
var subitems = items_wrapper.find("> .ty-menu__item");
var items_width = get_elements_width(subitems);
if (available_space < items_width) {
items_wrapper.css("visibility", "hidden");
available_space -= 25 * 2;
var slides_count = Math.ceil(items_width / available_space);
var elems_arr = [];
var subitems_wrap_collection = [];
for (var i = 0; i < slides_count; i++) {
var slide_w = 0;
elems_arr[i] = [];
subitems.each(function() {
var item = $(this);
if (!item.hasClass("added-to-selection")) {
var item_w = item.outerWidth();
if (((slide_w + item_w) <= available_space)) {
slide_w += item_w;
elems_arr[i].push(this);
item.addClass("added-to-selection");
}
}
});
var temp_subitems = $(elems_arr[i]);
subitems_wrap_collection.push(
temp_subitems.wrapAll("<div class='" + wrapper_class + " clearfix'>").parent().addClass(i === 0 ? '' : "hidden")
);
}
subitems_wrap_collection.forEach(function(wrapper, index) {
if (wrapper.prev().hasClass(wrapper_class)) {
wrapper.addClass("has-prev").prepend(
"<div class='ab-hm-first-level-toggler hm-prev-toggler cm-tooltip' title='" + _.tr("abt__ut2_go_back") + "'>" +
"<span>" + (index + 1) + '/' + slides_count + "</span>" +
"</div>");
}
if (wrapper.next().hasClass(wrapper_class)) {
wrapper.addClass("has-next").append("" +
"<div class='ab-hm-first-level-toggler hm-next-toggler cm-tooltip' title='" + _.tr("abt__ut2_go_next") + "'>" +
"<span>" + (index + 1) + '/' + slides_count + "</span>" +
"</div>");
}
});
$(".ab-hm-first-level-toggler").click(function() {
var btn = $(this);
var parent = btn.parent();
if (btn.hasClass("hm-prev-toggler")) {
parent.addClass("hidden").prev().removeClass("hidden");
} else {
parent.addClass("hidden").next().removeClass("hidden");
}
});
items_wrapper.css("visibility", '');
}
}
}
function get_elements_width(elems) {
var total_w = 0;
elems.each(function() {
total_w += $(this).outerWidth();
});
return total_w;
}
})(Tygh, Tygh.$);