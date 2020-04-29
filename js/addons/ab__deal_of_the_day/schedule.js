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
var year = $('.ab__dotd_year_selector'),
month = $('.ab__dotd_month_selector'),
availPeriod = $('input[name="avail_period"]'),
useSchedule = $('#elm_use_schedule');
/* year selector */
year.change(function () {
month.trigger('change');
});
/* month selector */
month.change(function () {
var self = $(this);
$('div[id^="ab__dotd_timedsheet_"]').hide();
if (self.val()) {
$('div[id="ab__dotd_timedsheet_' + year.val() + '_' + self.val() + '"]').show();
} else {
$('div[id^="ab__dotd_timedsheet_' + year.val() + '"]').show();
}
});
/* useSchedule change */
useSchedule.change(function () {
availPeriod.prop('checked') && $(this).prop('checked') && availPeriod.trigger('click') && fn_activate_calendar(availPeriod);
});
/* availPeriod change */
availPeriod.change(function () {
if ($(this).prop('checked') && useSchedule.prop('checked')) {
$.ceNotification('show', {
type: 'E',
title: _.tr('error'),
message: _.tr('ab__dotd.error.use_schedule_is_on')
});
$(this).prop('checked', false);
fn_activate_calendar(availPeriod)
}
});
$(document).ready(function () {
year.trigger('change');
useSchedule.trigger('change');
});
/* init timesheets */
$.getScript('js/addons/ab__deal_of_the_day/lib/timesheet/TimeSheet.js', function () {
var hourList = [
{name:"00:00:00-00:59:59"},
{name:"01:00:00-01:59:59"},
{name:"02:00:00-02:59:59"},
{name:"03:00:00-03:59:59"},
{name:"04:00:00-04:59:59"},
{name:"05:00:00-05:59:59"},
{name:"06:00:00-06:59:59"},
{name:"07:00:00-07:59:59"},
{name:"08:00:00-08:59:59"},
{name:"09:00:00-09:59:59"},
{name:"10:00:00-10:59:59"},
{name:"11:00:00-11:59:59"},
{name:"12:00:00-12:59:59"},
{name:"13:00:00-13:59:59"},
{name:"14:00:00-14:59:59"},
{name:"15:00:00-15:59:59"},
{name:"16:00:00-16:59:59"},
{name:"17:00:00-17:59:59"},
{name:"18:00:00-18:59:59"},
{name:"19:00:00-19:59:59"},
{name:"20:00:00-20:59:59"},
{name:"21:00:00-21:59:59"},
{name:"22:00:00-22:59:59"},
{name:"23:00:00-23:59:59"}
];
$('.ab__dotd_month_schedule').each(function () {
var self = $(this);
var dayList = self.data('caDaysList');
var input = $('#' + self.data('caInputId'));
var sheet = self.TimeSheet({
data: {
dimensions: [24,dayList.length],
colHead: dayList,
rowHead: hourList,
sheetHead: {
name: _.tr('ab__dotd.schedule.table_header')
},
sheetData: JSON.parse(input.val())
},
end: function() {
input.val(JSON.stringify(sheet.getSheetStates()));
}
});
self.data('sheet', sheet);
});
});
$('#ab__dotd_clearall').click(function () {
$('.ab__dotd_month_schedule:visible').each(function () {
var self = $(this);
var input = $('#' + self.data('caInputId'));
var sheet = self.data('sheet');
sheet.clean();
input.val(JSON.stringify(sheet.getSheetStates()))
});
return false;
});
}(Tygh, Tygh.$));