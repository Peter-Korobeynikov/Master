{capture name="mainbox"}
<form action="{""|fn_url}" method="post" class="form-horizontal form-edit {if ""|fn_check_form_permissions} cm-hide-inputs{/if}" name="search_motivation_form">
<div id="content_general">
<div class="control-group">
<label class="control-label" for="elm_search_phrases">{__("ab__search_motivation.default_search_phrases")}:</label>
<div class="controls">
<textarea name="search_phrases" id="elm_search_phrases" cols="55" rows="4" class="input-large">{$search_phrases}</textarea>
</div>
</div>
</div>
{capture name="buttons"}
{include file="buttons/save.tpl" but_role="submit-link" but_target_form="search_motivation_form" but_name="dispatch[ab__search_motivation.update]"}
{/capture}
</form>
{/capture}
{include file="common/mainbox.tpl" title=__("ab__search_motivation.default_search_phrases") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons select_languages=true}