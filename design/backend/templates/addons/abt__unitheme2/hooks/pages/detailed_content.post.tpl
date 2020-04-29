{include file="common/subheader.tpl" title=__("abt__unitheme2") target="#abt__ut2_markup"}
<div id="abt__ut2_markup" class="in collapse">
<fieldset>
<div class="control-group ">
<label class="control-label" for="abt__ut2_schema_type">{__("abt__ut2.microdata.schema_type")}</label>
<div class="controls">
<select name="page_data[abt__ut2_microdata_schema_type]" id="abt__ut2_schema_type" class="user-success">
<option value="" {if !$page_data.abt__ut2_microdata_schema_type} selected{/if}>{__("abt__ut2.microdata.schema_type.none")}</option>
{foreach ["Article","NewsArticle","BlogPosting"] as $type}
<option value="{$type}"{if $type == $page_data.abt__ut2_microdata_schema_type} selected{/if}>{__("abt__ut2.microdata.schema_type.`$type`")}</option>
{/foreach}
</select>
</div>
</div>
</fieldset>
</div>