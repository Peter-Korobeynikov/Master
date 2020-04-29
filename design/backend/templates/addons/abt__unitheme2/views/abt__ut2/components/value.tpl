{$f_v="abt__unitheme2_data[`$f_section`][`$f_group``$f_name`]"}
{$f_disabled=''}
{if !$f_type}
{$f_v="abt__unitheme2_data[`$f_section`][`$f_group``$f_name`]"}
{$f_value = $f.value|default:''}
{else}
{$f_v="abt__unitheme2_data[`$f_section`][`$f_group``$f_name`][`$f_type`]"}
{$f_value = $f.value.$f_type|default:''}
{$f_id="`$f_id`.`$f_type`"}
{if $f.disabled.$f_type == 'Y'}{$f_disabled='disabled="disabled"'}{/if}
{/if}
{** Checkbox **}
{if $f.type == "checkbox"}
<input type="hidden" value="N" name="{$f_v}">
<input id="{$f_id}" type="checkbox" value="Y" name="{$f_v}" {if $f_value == 'Y'}checked="checked"{/if} {$f_disabled}>
{** selectbox **}
{elseif $f.type == 'selectbox'}
<select id="{$f_id}" name="{$f_v}" class="{$f.class|default:'span10'}" {$f_disabled}>
{foreach from=$f.variants item="v"}
<option value="{$v}" {if $v == $f_value}selected="selected"{/if}>
{if $f.variants_as_language_variable|default:'Y' == 'Y'}
{__({"`$ls`.`$f_section`.`$f_group``$f_name`.variants.`$v`"})}
{else}
{$v}
{/if}
</option>
{/foreach}
</select>
{if $f.suffix}&nbsp;{$f.suffix}{/if}
{** input **}
{elseif $f.type == 'input'}
<input id="{$f_id}" type="text" name="{$f_v}" value="{$f_value}" class="cm-trim {$f.class|default:'span10'}" {$f_disabled}>
{if $f.suffix}&nbsp;{$f.suffix}{/if}
{** textarea **}
{elseif $f.type == 'textarea'}
<textarea id="{$f_id}" name="{$f_v}" class="cm-trim {$f.class|default:'span10'}" {$f_disabled}>{$f_value}</textarea>
{** colorpicker **}
{elseif $f.type == 'colorpicker'}
<input id="{$f_id}" class="cm-abt-ut2-colorpicker" style="font-family: monospace;" type="text" name={$f_v} id="{"storage_elm_te_`$section`_`$f.name`"}" value="{$f_value|replace:"transparent":""|default:"#ffffff"}" {$f_disabled}/>
{/if}
