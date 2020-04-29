{capture name="mainbox"}
<form action="{""|fn_url}" method="post" name="abt__ut2_po_update_form" id="abt__ut2_po_update_form">
{if $not_existing_vars}
{include file="addons/abt__unitheme2/views/abt__ut2/components/uni1_to_uni2/not_existing_lang_variables.tpl"}
{else}
{include file="addons/abt__unitheme2/views/abt__ut2/components/uni1_to_uni2/existing_lang_variables.tpl"}
{/if}
</form>
{/capture}