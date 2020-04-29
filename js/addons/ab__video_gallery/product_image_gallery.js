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
var ITEMS_COUNT_DEFAULT = 3;
var scroller_type;
var methods = {
init: function() {
var container = $(this);
var params = {
items_count: container.data('caItemsCount') ? container.data('caItemsCount') : ITEMS_COUNT_DEFAULT,
items_responsive: container.data('caItemsResponsive') ? true : false,
cycle: container.data('caCycle') == 'Y' ? true : false,
vertical: container.data('caVertical') == 'Y' ? true : false,
};
if (params.vertical && $(window).width() > 767) {
scroller_type = 'jcarousel';
} else {
scroller_type = 'owlcarousel';
}
if (methods.countElms(container) <= params.items_count) {
container.removeClass('owl-carousel');
}
if (methods.countElms(container) > params.items_count || scroller_type == 'jcarousel') {
if (container.data('owl-carousel') || container.data('jcarousel')) {
return true;
}
methods.check(container, params);
}
methods.bind(container);
container.addClass('ab__vg_loaded');
return true;
},
load: function(container, params) {
if (scroller_type == 'owlcarousel') {
container.owlCarousel({
direction: _.language_direction,
items: params.items_count,
singleItem: params.items_count == 1 ? true : false,
responsive: params.items_responsive,
pagination: false,
navigation: true,
lazyLoad: true,
navigationText: params.items_count == 1 ? ['<i class="icon-left-circle ty-icon-left-circle"></i>', '<i class="icon-right-circle ty-icon-right-circle"></i>'] : ['<i class="icon-left-open-thin ty-icon-left-open-thin"></i>', '<i class="icon-right-open-thin ty-icon-right-open-thin"></i>'],
theme: params.items_count == 1 ? 'owl-one-theme' : 'owl-more-theme',
afterInit: function(item) {

let elem = $('.cm-thumbnails-mini.active', item);
if (elem.length) {
var pos = elem.data('caImageOrder') || 0;
for (let i=2;i<pos;i++) {
item.trigger('owl.next');
}
}
}
});
} else {
container.css({
'height': (params.items_count * params.i_height),
'overflow': 'hidden',
'padding': 0,
'margin': 0,
'position': 'relative',
'z-index': 'auto'
});
var wrapper = container.parent();
var nav_prev = $('<i class="icon-up-open ty-icon-up-open hand ty-hand" />').prependTo(wrapper);
var nav_next = $('<i class="icon-down-open ty-icon-down-open hand ty-hand" />').appendTo(wrapper);
var wrapper_height = container.outerHeight(true)+nav_prev.outerHeight(true)+nav_next.outerHeight(true);
if (params.main_image_height < wrapper_height && params.items_count > 1) {
params.items_count--;
container.height(params.items_count * params.i_height);
}
var scrolled = 0;
if (!params.cycle) {
nav_prev.css('visibility', 'hidden');
}
nav_next.on('click', function(){
if ((scrolled + params.items_count) < params.items_amount) {
scrolled += 1;
if ((scrolled + params.items_count) == params.items_amount && !params.cycle) {
nav_next.css('visibility', 'hidden');
}
} else if ((scrolled + params.items_count) == params.items_amount && params.cycle) {
scrolled = 0;
} else {
return false;
}
nav_prev.css('visibility', 'visible');
container.stop(true, false).animate({
scrollTop: scrolled*params.i_height
});
});
nav_prev.on('click', function(){
if (scrolled > 0) {
scrolled -= 1;
if (scrolled == 0 && !params.cycle) {
nav_prev.css('visibility', 'hidden');
}
} else if (scrolled == 0 && params.cycle) {
scrolled = params.items_amount - params.items_count;
} else {
return false;
}
nav_next.css('visibility', 'visible');
container.stop(true, false).animate({
scrollTop: scrolled*params.i_height
});
});
let elem = $('.cm-thumbnails-mini.active', container);
if (elem.length) {
let pos = elem.data('caImageOrder') || 0;
while (scrolled <= (pos - params.items_count)) {
nav_next.trigger('click');
}
}
}
},
check: function(container, params) {
if (container.data('owl-carousel') || container.data('jcarousel')) {
return true;
}
if (!params.i_width || !params.i_height) {
var t_elm = false;
var wrapper = container.closest('.ab_vg-images-wrapper');
if ($('.cm-thumbnails-mini', container).length) {
var load = false;
$('.cm-thumbnails-mini', container).each(function() {
var elm = $(this);
var i_elm = $('img', elm);
if (i_elm.length) {
if (!i_elm.height() || !i_elm.width()) {
load = true;
return false;
}
}
});
if (!t_elm) {
t_elm = $('.cm-thumbnails-mini:first', container);
if ($('.cm-image:first', wrapper).innerHeight() < t_elm.innerHeight()) {
load = true;
}
if (load) {
var check_load = function() {
methods.check(container, params);
};
setTimeout(check_load, 500);
return false;
}
}
} else {
t_elm = $('img:first', container);
}
params.i_width = t_elm.outerWidth(true);
params.i_height = t_elm.outerHeight(true);
params.c_width = params.i_width * params.items_count;
params.main_image_height = $('.cm-preview-wrapper', wrapper).outerHeight(false);
if (scroller_type == 'owlcarousel') {
container.closest('.cm-image-gallery-wrapper').width(params.c_width);
} else {
params.items_count = parseInt(params.main_image_height/params.i_height);
params.items_amount = methods.countElms(container);
if (params.items_count < 1) {
params.items_count = 1;
}
if (params.main_image_height > params.items_amount*params.i_height) {
return false;
} else {
container.data('jcarousel', true);
}
}
}
return methods.load(container, params);
},
bind: function(container) {
container.click(function(e) {
var jelm = $(e.target);
var pjelm;
var in_elm;
if (scroller_type == 'owlcarousel') {
in_elm = jelm.parents('.cm-item-gallery') || jelm.parents('div.cm-thumbnails-mini') ? true : false;
} else {
in_elm = jelm.parents('.cm-item-gallery') || jelm.parents('.cm-thumbnails-mini') ? true : false;
}
if (in_elm && !jelm.is('img')) {
return false;
}
if (jelm.hasClass('cm-thumbnails-mini') || (pjelm = jelm.parents('a:first.cm-thumbnails-mini'))) {
jelm = (pjelm && pjelm.length) ? pjelm : jelm;
var c_id = jelm.data('caGalleryLargeId');
$('#' + c_id).closest('.cm-preview-wrapper').trigger('owl.goTo', $(jelm).data('caImageOrder') || 0);
}
});
},
countElms: function(container) {
if (scroller_type == 'owlcarousel') {
return $('.cm-thumbnails-mini', container).length;
} else {
return $('.cm-thumbnails-mini', container).length;
}
}
};
$.fn.AB_ceProductImageGallery = function( method ) {
if (!$().owlCarousel) {
var gelms = $(this);
$.getScript('js/lib/owlcarousel/owl.carousel.min.js', function() {
gelms.AB_ceProductImageGallery();
});
return false;
}
return $(this).each(function(i, elm) {
var errors = { };
if (methods[method]) {
return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
} else if ( typeof method === 'object' || !method ) {
return methods.init.apply(this, arguments);
} else {
$.error('ty.productimagegallery: method ' + method + ' does not exist');
}
});
};
$.ceEvent('on', 'ce.commoninit', function(context) {
$('.cm-ab__vg-gallery:not(.ab__vg_loaded)', context).each(function () {
let self = $(this);
let timer;
timer = setInterval(function () {
if ($('.owl-wrapper', context).width() > 0) {
self.AB_ceProductImageGallery();
clearInterval(timer);
}
}, 400);
});
});
$.ceEvent('on', 'ce.commoninit', function (context) {
$('.cm-preview-wrapper', context).owlCarousel({
direction: _.language_direction,
pagination: false,
singleItem: true,
addClassActive: true,
afterInit: function (item) {
var thumbnails = $('.cm-thumbnails-mini', item.parents('[data-ca-previewer]')),
previewers = $('.cm-image-previewer', item.parents('[data-ca-previewer]')),
previousScreenX = 0,
newScreenX = 0,
swipeThreshold = 7;
previewers.each(function (index, elm) {
$(elm).data('caImageOrder', index);
});
thumbnails.on('click', function () {
item.trigger('owl.goTo', $(this).data('caImageOrder') ? $(this).data('caImageOrder') : 0);
});
item.on('touchstart', function (e) {
previousScreenX = e.changedTouches[0].screenX;
});
item.on('touchmove', function (e) {
newScreenX = e.changedTouches[0].screenX;
if (Math.abs(newScreenX - previousScreenX) > swipeThreshold && e.cancelable) {
e.preventDefault();
}
previousScreenX = newScreenX;
});
$('.cm-image-previewer.hidden', item).toggleClass('hidden', false);
$.ceEvent('trigger', 'ce.product_image_gallery.ready');
},
beforeMove: function (item) {
$('.ty-image-zoom__flyout--visible').removeClass('ty-image-zoom__flyout--visible');
},
afterMove: function (item) {
var _parent = item.parent();
$('.cm-thumbnails-mini', _parent)
.toggleClass('active', false);
var elmOrderInGallery = $('.active', item).index();
$('[data-ca-image-order=' + elmOrderInGallery + ']', _parent)
.toggleClass('active', true);
$('.owl-carousel.cm-image-gallery', _parent)
.trigger('owl.goTo', elmOrderInGallery);
$.ceEvent('trigger', 'ce.product_image_gallery.image_changed');
}
});
});
})(Tygh, Tygh.$);
