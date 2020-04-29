{capture name="mainbox"}
{include file="addons/buy_together/views/buy_together/update.tpl" item=$chain_data}
{/capture}
{assign var="title" value="{__("abt__ut2_buy_together.editing")}: `$chain_data.name`"}
{include file="common/mainbox.tpl" title=$title content=$smarty.capture.mainbox buttons=$smarty.capture.buttons select_languages=true}