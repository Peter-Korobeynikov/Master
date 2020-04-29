{capture name="mainbox"}
{* main help *}
<p>{__('ab__cb.help.doc')}</p>
{* cron links *}
{assign var="links" value=''|fn_ab__cb_cron_links}
{include file="common/subheader.tpl" meta="" title="{__('ab__cb.help.cron.title')}" target="#ab__cb.help.cron"}
<div id="ab__cb.help.cron" class="in collapse" style="padding: 0 20px">{$links nofilter}</div>
{/capture}
{include file="common/mainbox.tpl"
title = __("ab__category_banners.help")
title_start = __("ab__category_banners")
title_end = __("ab__category_banners.help")
content = $smarty.capture.mainbox
buttons = $smarty.capture.buttons
adv_buttons = $smarty.capture.adv_buttons
sidebar = $smarty.capture.sidebar
}