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
$.ceEvent('on', 'ce.commoninit', function(context) {
var selector = _.ab__smc.selector + (_.ab__smc.additional_selector.parent_selectors.length ? ',' + _.ab__smc.additional_selector.parent_selectors.join(',') : '');
if( selector ) {
var elems = context.find(selector);
if (elems.length) {
$.each(elems, function ( ) {
var elem = $(this);
if (elem.is(_.ab__smc.exclude.parent_selectors.join(','))) {
return;
}
if (elem.find(_.ab__smc.exclude.selectors_in_content.join(',')).length) {
return;
}
var more_txt = _.tr("ab__smc.more");
var less_txt = _.tr("ab__smc.less");
var max_height = parseInt(_.ab__smc.max_height);
var attr = elem.attr("data-ab-smc-tab-hide");
if ( attr != void(0) ) {
more_txt = elem.attr("data-ab-smc-more");
less_txt = elem.attr("data-ab-smc-less");
attr = attr.split('|')[1];
if ( attr === 'Y' ) {
max_height = parseInt(elem.attr("data-ab-smc-height"));
}
}
var elem_height = parseInt(elem.outerHeight());
if (elem_height > max_height) {
elem.find("> *").wrapAll("<div>");
var t = _.ab__smc.transition;
elem.addClass('ab-smc-description' + _.ab__smc.description_element_classes).css({
maxHeight: max_height + "px",
transition: "max-height " + t + "s ease",
opacity: "0.999999",
});
var clickable = $("<div class='ab-smc-more" + _.ab__smc.additional_classes_for_parent + "'>" +
"<span class='" + _.ab__smc.additional_classes + "'>" + more_txt + "</span>" +
"<i class='ab-smc-arrow'></i>" +
"</div>").appendTo(elem);
var timeout = t * 0.65 * 1000;
clickable.on("click", function() {
var btn = $(this);
var parent = btn.parent();
var elem_height = parent.addClass("ab-smc-opened").find("> div:first-child").css({
paddingBottom: btn.outerHeight() + "px",
}).outerHeight();
btn.parent().css({
maxHeight: elem_height + "px"
});
setTimeout(function(){
btn.addClass("ab-smc-opened");
if ( !_.ab__smc.show_button ) {
elem.find(".ab-smc-more").detach();
return;
}
elem.find(".ab-smc-more").html("<span class='" + _.ab__smc.additional_classes + "'>" + less_txt + "</span><i class='ab-smc-arrow'></i>");
}, timeout);
});
_.ab__smc.show_button && elem.on("click", ".ab-smc-more.ab-smc-opened", function(){
var btn = $(this);
btn.parent().css({
maxHeight: max_height + "px"
});
setTimeout(function(){
btn.removeClass("ab-smc-opened");
btn.parent().removeClass("ab-smc-opened");
elem.find(".ab-smc-more").html("<span>" + more_txt + "</span><i class='ab-smc-arrow'></i>");
elem.find(".ab-smc-more span").addClass(_.ab__smc.additional_classes);
}, timeout);
});
}
});
$.ceEvent('trigger', 'ab.hide_product_description.hide', [context, elems, _.ab__smc]);
}
}
});
}(Tygh, Tygh.$));