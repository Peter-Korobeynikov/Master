{capture name="mainbox_title"}
{__("ab__smc")}: {__("ab__smc.help")}
{/capture}
{capture name="mainbox"}
<p>{__('ab__smc.help.doc')}</p>
{/capture}
{include file="common/mainbox.tpl" title=$smarty.capture.mainbox_title title_start = __("ab__smc") title_end = __("ab__smc.help") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar}