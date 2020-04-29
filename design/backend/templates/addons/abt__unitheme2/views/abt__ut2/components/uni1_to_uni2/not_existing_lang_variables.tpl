<p>{__("abt__ut2.migaritions_from_unitheme1.update_po.not_exist.description")}</p>
<div class="table-responsive-wrapper">
<table class="table table-middle table-responsive ab-overflow-table" width="100%">
<thead>
<tr>
<th class="left mobile-hide" width="1%">
<div class="btn-group btn-checkbox cm-check-items">
<a href="" data-toggle="dropdown" class="btn dropdown-toggle">
<span class="caret pull-right"></span>
</a>
<input type="checkbox" name="check_all" value="Y" title="{__("check_uncheck_all")}" class="pull-left cm-check-items ">
<ul class="dropdown-menu">
<li><a class="cm-on">All</a></li>
<li><a class="cm-off">None</a></li>
</ul>
</div>
</th>
<th width="15%" style="text-align: center">{__("id")}</th>
{foreach $abt__ut2_langs as $lang}
<th width="{(100 - ( 1+15 ))/$languages|count}%">{$lang.name}</th>
{/foreach}
</tr>
</thead>
{strip}
<tbody>
{foreach $not_existing_vars.{$smarty.const.CART_LANGUAGE} as $var}
<tr class="cm-row-item">
<td data-th="{__("accept")}" class="mobile-hide">
<input type="hidden" name="lang_vars[{$not_existing_vars.{$smarty.const.CART_LANGUAGE}.{$var@key}.id}][set]" value="N">
<input class="cm-item" type="checkbox" name="lang_vars[{$not_existing_vars.{$smarty.const.CART_LANGUAGE}.{$var@key}.id}][set]" value="Y">
</td>
<td data-th="{__("id")}">
<code>{$not_existing_vars.{$smarty.const.CART_LANGUAGE}.{$var@key}.id}</code>
</td>
{foreach $abt__ut2_langs as $lang}
{$tmp = $not_existing_vars.{$lang.lang_code}.{$var@key}}
<td data-th="{$lang.name}"><textarea type="text" rows="1" name="lang_vars[{$tmp.id}][langs][{$lang.lang_code}]">
{if $tmp.msgstr[0]}
{$tmp.msgstr[0]}
{else}
{$not_existing_vars.{$smarty.const.CART_LANGUAGE}.{$var@key}.msgstr[0]}
{/if}
</textarea></td>
{/foreach}
</tr>
{/foreach}
</tbody>
{/strip}
</table>
</div>
{capture name="buttons"}
{$smarty.capture.add_button}
<span class="cm-tab-tools btn-group" id="tools_translations_save_button">
{include file="buttons/save.tpl" but_name="dispatch[abt__ut2.migaritions_from_unitheme1.update_po]" but_role="action" but_target_form="abt__ut2_po_update_form" but_meta="cm-submit cm-confirm cm-process-items cm-post"}
</span>
{/capture}