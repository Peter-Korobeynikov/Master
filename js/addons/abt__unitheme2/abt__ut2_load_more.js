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
$(document).ready(function(){
$(_.doc).on('click', '.ut2-load-more:not(.hidden):not(.ut2-load-more-loading)', function() {
$(this).addClass('ut2-load-more-loading');
let current_position = 0;
$.ceAjax('request', $(this).data('ut2-load-more-url'), {
save_history: true,
result_ids: $(this).data('ut2-load-more-result-ids'),
append: true,
hidden: true,
pre_processing: function (){
current_position = $(window).scrollTop();
$('html').addClass('dialog-is-open');
},
callback: function(data) {
$(window).scrollTop(current_position);
$('html').removeClass('dialog-is-open');
$('.ut2-load-more-loading').addClass('hidden');
if (data.html.pagination_block_bottom !== undefined) {
$('#pagination_block_bottom').empty().html(data.html.pagination_block_bottom);
}
if (data.html.pagination_block !== undefined){
$('#pagination_block').empty().html(data.html.pagination_block);
}
$.ceEvent('trigger', 'ce.ut2-load-more', [data]);
},
});
});
if (_.abt__ut2.settings.load_more.mode === 'auto'){
$(window).on("scroll", function(e){
if($(window).scrollTop() + $(window).height() >= $(document).height() - parseInt(_.abt__ut2.settings.load_more.before_end)) {
var load_more_button = $('.ut2-load-more:not(.hidden):not(.ut2-load-more-loading)');
if (load_more_button.length){
load_more_button.click();
}
}
});
}
});
}(Tygh, Tygh.$));