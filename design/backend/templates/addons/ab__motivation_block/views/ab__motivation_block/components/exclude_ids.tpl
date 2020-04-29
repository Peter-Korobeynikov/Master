<div class="control-group">
<label class="control-label" for="ab__mb_exclude_{$type}">{__("ab__mb.exclude.{$type}")}{include file="common/tooltip.tpl" tooltip=__("ab__mb.exclude.`$type`.tooltip")}:</label>
<div class="controls">
<select name="motivation_item_data[exclude_{$type}]" id="ab__mb_exclude_{$type}">
<option{if $motivation_item_data.{"exclude_$type"} == "N"} selected{/if} value="N">{__("ab__mb.exclude.not_exclude")}</option>
<option{if $motivation_item_data.{"exclude_$type"} == "Y"} selected{/if} value="Y">{__("ab__mb.exclude.exclude")}</option>
</select>
</div>
</div>
<hr style="margin-bottom: 25px">