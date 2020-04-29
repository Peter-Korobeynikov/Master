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
* This is commercial software, only users who have purchased a valid license and  accept   *
* to the terms of the License Agreement can install and use this program.                  *
* ---------------------------------------------------------------------------------------- *
* website: https://cs-cart.alexbranding.com                                                *
*   email: info@alexbranding.com                                                           *
*******************************************************************************************/
(function(_, $) {
$(document).ready(function() {
if ( $(".abt-ut2-draggable").length && _.abt__ut2.functions.in_array(_.abt__ut2.device, ["mobile", "tablet"])) {
var PERCENTS_TO_CLOSE = 35;
var PERCENTS_TO_OVERFLOW = 4;
$(".ty-dropdown-box.abt-ut2-draggable:not(.calculated)").each(function() {
var box = $(this).addClass("calculated");
var child = box.find(".ty-dropdown-box__content:first");
if( child.length ) {
var liner = $("<div class='ut2-swipe-liner'></div>").prependTo(box);
var toggler = box.find(".ty-dropdown-box__title");
var pixels_to_percent = parseInt(window.innerWidth / 100);
toggler.on("click", function ( e ) {
var child_display = child.css("display");
if ( !_.abt__ut2.functions.in_array(child_display, ["block", "flex"]) ) {
var body = $("body, html");
body.css("overflow", "hidden");
liner.addClass("active");
var prev_x = 10000;
var timestamps = {};
var swipe_function = function (down_e) {
var target = $( down_e.target );
var return_list = [
".ty-product-filters__block",
"li.ut2-item",
".ty-product-filters__tools"
];
if ( target.is(return_list.join(',')) || target.parents(return_list.join(',')).length ) {
return false;
}
var bias_percents = 0;
var duration = Number(child.css("transition-duration").replace('s', '')) * 1000;
var touch_start_pos = down_e.touches != void(0) ? down_e.touches[0] : { clientX: 0 };
var touch_start_x = touch_start_pos.clientX;
var prev_percent = 0;
timestamps.start = parseInt( down_e.timeStamp );
var ab__tmf = function(e) {
var touch = e.touches != void(0) ? e.touches[0] : { clientX: 0 };
var touch_x = touch.clientX;
timestamps.move = parseInt( e.timeStamp );
if ( prev_x > touch_x &&
((timestamps.move - timestamps.start) > 200)) {
var bias_pixels = touch_start_x - touch_x;
bias_percents = parseInt(bias_pixels / pixels_to_percent);
if ( bias_percents > PERCENTS_TO_OVERFLOW &&
bias_percents !== prev_percent ) {
child.css({
"transform": "translateX(-" + (bias_percents - PERCENTS_TO_OVERFLOW) + "%)",
"overflow": "hidden"
});
prev_percent = bias_percents;
prev_x = touch_x;
}
}
};
var ab__mouseup = function () {
if ( (timestamps.move - timestamps.start) > 100 ) {
if (bias_percents > PERCENTS_TO_CLOSE) {
child.css({
"transform": "translateX(-100%)",
"overflow": ''
});
setTimeout(unset_all, duration);
} else {
child.css({
"transform": "translateX(0%)",
"overflow": ""
});
}
} else {
unset_all();
}
function unset_all() {
toggler.removeClass("open");
child.hide().css("transform", '');
body.css("overflow", '');
setTimeout(function() {
liner.removeClass("active");
}, 325);
document.removeEventListener("mouseup", ab__mouseup);
document.removeEventListener("touchend", ab__mouseup);
document.removeEventListener("mousemove", ab__tmf);
document.removeEventListener("touchmove", ab__tmf);
document.removeEventListener("mousedown", swipe_function);
document.removeEventListener("touchstart", swipe_function);
}
};
document.addEventListener("mouseup", ab__mouseup);
document.addEventListener("touchend", ab__mouseup);
document.addEventListener("mousemove", ab__tmf);
document.addEventListener("touchmove", ab__tmf);
return false;
};
document.addEventListener("mousedown", swipe_function);
document.addEventListener("touchstart", swipe_function);
}
});
}
});
};
});
}(Tygh, Tygh.$));