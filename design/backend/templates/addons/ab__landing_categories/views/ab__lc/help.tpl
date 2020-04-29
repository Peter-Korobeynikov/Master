{capture name="mainbox_title"}
{__("ab__landing_categories")}: {__("ab__lc.help")}
{/capture}
{capture name="mainbox"}
<p>{__('ab__lc.help.doc')}</p>
{/capture}
{include file="common/mainbox.tpl" title=$smarty.capture.mainbox_title title_start = __("ab__landing_categories") title_end = __("ab__lc.help") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar}
