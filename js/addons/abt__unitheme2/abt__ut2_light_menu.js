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
$(".ut2-sp-n, .ut2-sp-f").on("click", function () {
var swiper = $(this);
var swiper_parent = swiper.parents(".ut2-sw-w").toggleClass("active");
var bolster = swiper_parent.siblings(".ut2-sw-b").toggleClass("active");
$("body").toggleClass("swipe-no-scroll");
bolster.on("click", function () {
swiper_parent.removeClass("active");
$(this).removeClass("active");
$("body").toggleClass("swipe-no-scroll");
});
});
$(".ut2-mt").each(function(){
$(this).parent().css("max-height", $(this).parent().height() + "px").addClass("toggle-it");
}).on("click", function () {
$(this).toggleClass("active").parent().toggleClass("toggle-it");
});
if ($(window).width() > 1200) {
$(".ut2-lfl").hover(function () {
var parent = $(this);
var parent_pos = parent[0].getBoundingClientRect();
var child = parent.find(".ut2-slw");
if (child.length) {
child.css("top", parent_pos.top + "px");
var child_pos = child[0].getBoundingClientRect();
if (child_pos.top < 0) {
child.addClass('no-translate');
} else if (child_pos.bottom > $(window).height()) {
child.addClass('no-translate bottom');
}
}
}, function () {
});
} else {
$(".ut2-lfl i").on("click", function () {
var item = $(this);
var parent = item.parent();
var siblings = parent.siblings(".ut2-lfl, .ut2-lsl");
siblings.toggleClass("hidden");
var back_to_main = parent.parents(".ut2-lm").find(".ut2-lm-back-to-main");
if (back_to_main.hasClass("hidden")) {
back_to_main.removeClass("hidden");
} else if (!parent.hasClass("ut2-lsl")) {
back_to_main.addClass("hidden");
}
parent.toggleClass("active");
});
$(".ut2-lm-back-to-main").on("click", function () {
var wrapper = $(this).addClass("hidden").parent();
wrapper.find(".ut2-lfl, .ut2-lsl").removeClass("hidden active");
});
}
$(".ut2-lsl bdi").on("click", function () {
$(this).parent().attr("style", '');
$(this).remove();
});
});
}(Tygh, Tygh.$));