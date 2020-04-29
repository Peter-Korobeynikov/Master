{if "ab__search_motivation.view"|fn_check_view_permissions:"GET"}
{include file="common/subheader.tpl" title=__("ab__search_motivation") target="#ab__search_phrases"}
<fieldset>
<div id="ab__search_phrases" class="in collapse {if !"ab__search_motivation.update"|fn_check_view_permissions:"POST"}cm-hide-inputs{/if}">
<div class="control-group">
<label class="control-label" for="elm_category_search_phrases">{__("ab__search_motivation.search_phrases")}:</label>
<div class="controls">
<textarea name="category_data[search_phrases]" id="elm_category_search_phrases" cols="55" rows="4" class="input-large">{$category_data.search_phrases}</textarea>
</div>
</div>
</div>
</fieldset>
{/if}