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
var ab_lc_time = 300;
$(document).on('click', '.ab-lc-group .show-hidden-items-level-2', function() {
var ul = $(this).parent().find('ul.hidden-items-level-2');
if (ul.hasClass('opened')){
ul.removeClass('opened').stop().slideUp(ab_lc_time);
$(this).removeClass('opened');
}else{
ul.addClass('opened').slideDown(ab_lc_time);
$(this).addClass('opened');
}
});
$(document).on('click', '.ab-lc-group li[data-subcategories="Y"] > a', function() {
var ul = $(this).parent().find('ul.items-level-3');
var li_of_a = $(this).parent();
if (ul.hasClass('opened')){
ul.removeClass('opened').stop().slideUp(ab_lc_time);
li_of_a.removeClass('opened');
}else{
ul.addClass('opened').slideDown(ab_lc_time);
li_of_a.addClass('opened');
}
return false;
});
$(document).on('click', '.ab-lc-landing .show-hidden-items-level-2', function() {
var ul = $(this).parent().find('ul.hidden-items-level-2');
if (ul.hasClass('opened')){
ul.removeClass('opened').stop().slideUp(ab_lc_time);
$(this).removeClass('opened');
}else{
ul.addClass('opened').slideDown(ab_lc_time);
$(this).addClass('opened');
}
});
});
}(Tygh, Tygh.$));
