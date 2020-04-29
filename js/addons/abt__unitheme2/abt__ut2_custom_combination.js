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
if( _.abt__ut2.controller === "products" && _.abt__ut2.mode === "view") {
$(".ut2-bt__products a.cm-dialog-opener[id^='opener_buy_together_options']").click(function () {
var link = $(this);
var product = link.parents(".ut2-bt__product");
setTimeout(function () {
var overlay = product.find(".ui-widget-overlay");
var modal = overlay.next().detach();
overlay.detach();
overlay.insertBefore("#ut2__buy-together");
modal.insertBefore("#ut2__buy-together");
var form_name = link.parents("form").attr("name");
modal.attr("data-ut2-pfn", form_name);
}, 0);
});
$.ceEvent('on', 'ce.product_option_changed_post', function (objId, id, optionId, updateIds, formData, data, params) {
var active_modal = $(document.querySelector(".ui-dialog.ui-widget:not([style*='display: none'])"));
var overlay = $(".ui-widget-overlay");
overlay.insertBefore("#ut2__buy-together");
active_modal.insertBefore("#ut2__buy-together");
});
$("#ut2__buy-together form").each(function () {
var form_name = this.name;
$.ceEvent("on", "ce.formpre_" + form_name, function (form, elm) {
var inputs = $(".ui-dialog.ui-widget[data-ut2-pfn='" + form_name + "']").find("input, select").serializeObject();
$.each(inputs, function (key, value) {
form.append('<input type="hidden" name="' + key + '" value="' + value + '" />');
});
});
});
}
});
}(Tygh, Tygh.$));