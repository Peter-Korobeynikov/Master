/*******************************************************************************************
*   ___  _          ______                     _ _                _                        *
*  / _ \| |         | ___ \                   | (_)              | |              © 2019   *
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
function fn_ab__mb_load_block (context) {
$('.ab__motivation_block:not(.loaded)', context).each(function () {
var self = $(this);
if (self.hasClass('loading')) {
return;
} else {
self.addClass('loading');
}
$.ceAjax('request', fn_url('ab__motivation_block.view?product_id=' + self.data('caProductId')), {
result_ids: self.data('caResultId'),
hidden: true,
callback: function () {
self.addClass('loaded').removeClass('loading');
$.ceEvent('trigger', 'ab__mb.ajax_block_uploaded', [ self ]);
}
});
});
}
$.ceEvent('on', 'ce.commoninit', function (context) {
fn_ab__mb_load_block(context);
if(_.ab__mb.addon_settings.save_element_state === 'Y') {
context.find(".ab-mb-save-state:not(.inited)").each(function(){
var toggler = $(this).addClass("inited");
var toggler_key = _.ab__mb.addon_settings.template_variant === "vertical_tabs" ? toggler.prop("id").replace(/^(sw_)/, '') : toggler.data("mbId");
toggler.click(function() {
if ($.cookie.get(toggler_key) !== 'Y') {
$.cookie.set(toggler_key, 'Y', new Date(new Date().getTime() + 24 * 60 * 60 * 1000), '/', _.current_host);
} else if (_.ab__mb.addon_settings.template_variant !== "horizontal_tabs") {
$.cookie.set(toggler_key, 'N', new Date(new Date().getTime() + 24 * 60 * 60 * 1000), '/', _.current_host);
}
});
});
}
var horizontal_tabs = context.find(".ab-mb-horizontal__item-tab");
if (horizontal_tabs.length) {
horizontal_tabs.click(function () {
var tab = $(this);
var active_class_name = "active";
if (!tab.hasClass(active_class_name)) {
var id = tab.data("mbId");
var parent = tab.parents(".ab-mb-horizontal-tabs-wrap");
var tab_content = parent.find(".ab-mb-horizontal__item[data-mb-id='" + id + "']");
var now_active = context.find(".ab-mb-horizontal__item-tab.active, .ab-mb-horizontal__item.active");
if (_.ab__mb.addon_settings.save_element_state === 'Y') {
var remove_cookie_id = now_active.data("mbId");
if (remove_cookie_id != void(0)) {
$.cookie.set(remove_cookie_id, 'N', new Date(new Date().getTime() + 24 * 60 * 60 * 1000), '/', _.current_host);
}
}
now_active.removeClass(active_class_name);
tab.addClass(active_class_name);
tab_content.addClass(active_class_name);
}
});
if (!horizontal_tabs.filter(".active").length) {
horizontal_tabs.first().click();
}
}
});
$.ceEvent('on', 'ce:geomap:location_set_after', function (location, $container, response, auto_detect) {
$('.ab__motivation_block.loaded').removeClass('loaded');
fn_ab__mb_load_block(_.doc);
});
}(Tygh, Tygh.$));