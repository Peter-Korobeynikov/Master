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
function abt__ut2_gt_cloneTools(tab_id, prev_id){
if (!tab_id || !prev_id) {
return;
}
var _prev_tools = abt__ut2_gt_getTabTools(prev_id);
_prev_tools.each(function() {
var self = $(this);
var _new_id = self.prop('id').replace(prev_id, tab_id);
if (!$('#' + _new_id).length) {
var _new_tool = self.clone();
_new_tool.children().remove();
_new_tool.prop('id', _new_id).hide().appendTo(self.parent());
}
});
}
function abt__ut2_gt_getTabTools(id){
return $('.cm-tab-tools[id^="tools_' + id + '"]');
}
function abt__ut2_gt_getTabIds(id){
var result_ids = ['content_' + id];
var additional_ids = $('#content_' + id).data('caTabTargetId');
if (additional_ids) {
result_ids.push(additional_ids);
}
abt__ut2_gt_getTabTools(id).each(function() {
result_ids.push($(this).prop('id'));
});
return result_ids.join(',');
}
var active_id = $('li.abt__ut2_grid_tabs.cm-ajax.cm-js:first').prop('id');
var abt__tabs = $('li.abt__ut2_grid_tabs.cm-ajax.cm-js');
if (abt__tabs.length){
var blocks = [];
abt__tabs.each(function(){
var self = $(this);
var tab_id = self.prop('id');
if (self.hasClass('active')) {
var block = $('#content_' + tab_id);
if (block.length){
content = block.html().replace(/<!--.*?-->/, '').replace(/(^\s+|\s+$)/, '');
if (content.length) {
return true;
}
}
}
if (!self.data('passed')) {
self.data('passed', true);
var id = 'content_' + tab_id;
var block = $('#' + id);
if (!block.length) {
self.parents('.cm-j-tabs').eq(0).next().prepend('<div id="' + id + '"></div>');
block = $('#' + id);
}
if (!self.hasClass('active')) {
block.addClass('hidden');
}
abt__ut2_gt_cloneTools(tab_id, active_id);
blocks.push('content_abt__ut2_grid_tab_' + self.data('block'));
}
});
if (blocks.length) {
$.ceAjax('request', fn_url('abt__ut2_grid_tabs.load'), {
result_ids: blocks.join(','),
hidden: true,
repeat_on_error: true
});
}
}
});
}(Tygh, Tygh.$));
