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
$.ceEvent('on', 'ce.commoninit', function(context) {
context.find('.cm-abt-ut2-colorpicker').minicolors();
});
$(_.doc).on('change', '[name="abt__unitheme2_data[product_list][show_gallery]"]', function(e) {
if ($(this).prop('checked')){
$('[name="abt__unitheme2_data[product_list][lazy_load]"]').prop('checked', false);
}
});
$(_.doc).on('change', '[name="abt__unitheme2_data[product_list][lazy_load]"]', function(e) {
if ($(this).prop('checked')){
$('[name="abt__unitheme2_data[product_list][show_gallery]"]').prop('checked', false);
}
});
$(_.doc).on('change', '.abt-ut2-banner-use-own-value > input[id^=elm_banner_][id$=_use_own]', function(e) {
let elm = $('#overlay_' + $(this).data('field'));
if ($(this).prop('checked')){
elm.removeClass('active');
}else{
elm.addClass('active');
}
});
function abt__ut2_copy_to_clipboard( text ) {
if (window.clipboardData && window.clipboardData.setData) {
return clipboardData.setData("Text", text);
} else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
var textarea = document.createElement("textarea");
textarea.textContent = text;
textarea.style.position = "fixed"; // Prevent scrolling to bottom of page in MS Edge.
document.body.appendChild(textarea);
textarea.select();
try {
return document.execCommand("copy"); // Security exception may be thrown by some browsers.
} catch (ex) {
console.warn("Copy to clipboard failed", ex);
return false;
} finally {
document.body.removeChild(textarea);
}
}
}
$(".ab-ut2-icon-copy").click( function(e) {
var link = $(this);
var tr = link.parents("tr");
var text = tr.find("td:nth-child(3)").text();
abt__ut2_copy_to_clipboard(text);
$.ceNotification("show", {
type: 'N',
title: Tygh.tr("notice"),
message: "Icon class was copied to clipboard"
});
e.preventDefault();
});
}(Tygh, Tygh.$));
