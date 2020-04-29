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
(function (_, $) {
var base_url;
var ajax_ids;
function setHandler() {
$(_.doc).on('click', '.ab__dotd_promotions-filter_item', function () {
var self = $(this);
var category_id = self.data('caCategoryId');
var url;
if (category_id !== undefined) {
url = $.attachToUrl(base_url, 'ab_category_id=' + category_id)
} else {
url = base_url;
}
return getProducts(url, self);
});
}
function setCallback() {
$.ceEvent('on', 'ce.commoninit', function (context) {
context.find('.ab__dotd_promotions-filter').each(function () {
var self = $(this);
if (self.data('caBaseUrl')) {
base_url = self.data('caBaseUrl');
ajax_ids = self.data('caTargetId');
}
});
});
}
function getProducts(url, obj) {
if (ajax_ids) {
$.ceAjax('request', url, {
result_ids: ajax_ids,
full_render: true,
save_history: true,
caching: false,
scroll: '.ab__dotd_promotion',
obj: obj,
callback: function (response) {
if (response.no_products) {
obj.removeClass('active');
}
}
});
} else {
$.redirect(url);
}
return false;
}
setCallback();
setHandler();
$.ceEvent('on', 'ce.commoninit', function (context) {
if (_.ab__dotd !== undefined) {
if (_.ab__dotd.chains_page === undefined) {
_.ab__dotd.chains_page = 1;
}
var desc = $('div.ab__dotd_promotion-description', context);
if (_.ab__dotd.current_dispatch == 'promotions.view' && desc.length && !desc.hasClass('ab__dotd_description')) {
_.ab__dotd.full_height_description = desc.outerHeight();
if (parseInt(_.ab__dotd.full_height_description) > parseInt(_.ab__dotd.max_height)) {
desc.addClass('ab__dotd_description').css({maxHeight: parseInt(_.ab__dotd.max_height) + "px",
overflow: "hidden"});
desc.after("<div class='ab__dotd_more'>" + _.ab__dotd.more + "</div>");
}
}
var container = $('div.ab__dotd_chains_content'),
button = $('.ab__dotd_chains-show_more', container);
if (_.ab__dotd.current_dispatch == 'promotions.list' && container.length && button.length) {
$(_.doc).off('click', '.ab__dotd_chains-show_more');
$(_.doc).on('click', '.ab__dotd_chains-show_more', function () {
button.addClass('loading');
$.ceAjax('request', fn_url('ab__dotd.get_chains'), {
hidden: true,
caching: false,
force_exec: true,
save_history: true,
method: 'post',
data: {
'page': ++_.ab__dotd.chains_page
},
callback: function(data) {
if (data.html !== undefined) {
button.before(data.html);
if (data.search !== undefined) {
if (data.search.page < data.search.total_pages) {
$('.ab__dotd-text_get_more').text(data.search.text_get_more);
$('.ab__dotd-text_showed').text(data.search.text_showed);
} else {
button.remove();
}
}
$.commonInit(container);
}
}
});
button.removeClass('loading');
});
}
}
});
$(document).on('click', 'div.ab__dotd_more:not(.inverse)', function() {
var button = $(this);
$('div.ab__dotd_promotion-description').animate({maxHeight: _.ab__dotd.full_height_description}, 800, function(){
$('.ab__dotd_description').addClass('inverse');
button.addClass('inverse').html(_.ab__dotd.less);
});
});
$(document).on('click', 'div.ab__dotd_more.inverse', function() {
var button = $(this);
$('div.ab__dotd_promotion-description').animate({maxHeight: parseInt(_.ab__dotd.max_height) + "px"}, 800, function(){
$('.ab__dotd_description').removeClass('inverse');
button.removeClass('inverse').html(_.ab__dotd.more);
});
});
$.ceEvent('on', 'ce.commoninit', function(context) {
ab__dotd_load_promos(context);
$.ceEvent('on', 'ce.ab__alp.products_loaded', function(ab__alp) {
ab__dotd_load_promos(context);
});
});
function ab__dotd_load_promos(context) {
var items = context.find('.ab-dotd-category-promo:not(.ab-dotd-loaded)');
var promotions_ids_list = [];
if (items.length) {
items.each(function() {
var promotion_id = $(this).data('caPromotionId');
if (promotion_id !== undefined && promotion_id !== _.ab__dotd.current_promotion_id && $.inArray(promotion_id. promotions_ids_list) === -1) {
promotions_ids_list.push(promotion_id);
}
});
if (promotions_ids_list.length) {
$.ceAjax('request', fn_url('ab__dotd.get_promos'), {
method: 'post',
data: {
promotions_ids: promotions_ids_list
},
caching: false,
hidden: true,
callback: function (d) {
if (d.promotions !== undefined) {
for (promo in d.promotions) {
items.filter('[data-ca-promotion-id=' + promo + ']').append(d.promotions[promo]).addClass('ab-dotd-loaded');
}
items.filter(':not(.ab-dotd-loaded)').remove();
}
}
});
} else {
items.remove();
}
}
}
}(Tygh, Tygh.$));