{if 'ab__video_gallery.view'|fn_check_view_permissions}
{$product_data.ab__vg_videos = $product_data.product_id|fn_ab__vg_get_videos:$smarty.const.DESCR_SL}
{$product_data.settings = $product_data.product_id|fn_ab__vg_get_setting}
<div class="hidden {if !'ab__video_gallery.manage'|fn_check_view_permissions}cm-hide-inputs{/if}" id="content_ab__video_gallery">
{include file="common/subheader.tpl" title=__("ab__vg.form.product_settings") target="#ab__vg-product_settings"}
<div id="ab__vg-product_settings" class="in collapse">
<div class="control-group">
<label class="control-label" for="ab__vg__replace_image">{__("ab__vg.form.replace_image")}</label>
<div class="controls">
<input type="hidden" name="product_data[ab__vg_settings][replace_image]" value="N" />
<input type="checkbox" name="product_data[ab__vg_settings][replace_image]" id="ab__vg__replace_image" value="Y"{if $product_data.settings.replace_image == "Y"}checked="checked"{/if} />
</div>
</div>
</div>
{include file="common/subheader.tpl" title=__("ab__vg.form.product_videos") target="#ab__vg-product_videos"}
<div id="ab__vg-product_videos" class="in collapse">
<table class="table table-middle" width="100%">
<thead class="cm-first-sibling">
<tr>
<th width="2%">
<span id="on_video_extra" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combinations-video_extra hidden"><span class="exicon-expand"></span></span>
<span id="off_video_extra" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combinations-video_extra"><span class="exicon-collapse"></span></span>
</th>
<th width="5%">{__("ab__vg.form.pos")}</th>
<th width="50%">{__("ab__vg.form.title")}</th>
<th width="25%">{__("ab__vg.form.status")}</th>
<th width="15%">&nbsp;</th>
</tr>
</thead>
{foreach from=$product_data.ab__vg_videos item="video" key="_key"}
<tbody class="cm-row-item">
<tr>
<td width="2%">
<span id="on_ab__vg_video_extra_{$_key}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand hidden cm-combination-video_extra"><span class="exicon-expand"></span></span>
<span id="off_ab__vg_video_extra_{$_key}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combination-video_extra"><span class="exicon-collapse"></span></span>
</td>
<td class="{$no_hide_input_if_shared_product}">
<input type="hidden" name="product_data[ab__vg_videos][{$_key}][video_id]" value="{$video.video_id}" />
<input type="text" name="product_data[ab__vg_videos][{$_key}][pos]" value="{$video.pos}" class="input-micro" />
</td>
<td class="{$no_hide_input_if_shared_product}">
<input type="text" name="product_data[ab__vg_videos][{$_key}][title]" value="{$video.title}" class="input-large" />
</td>
<td class="{$no_hide_input_if_shared_product}">
{include file="common/select_popup.tpl" popup_additional_class="dropleft" id=$video.video_id status=$video.status hidden=false object_id_name="video_id" table="ab__video_gallery"}
</td>
<td class="nowrap {$no_hide_input_if_shared_product} right">
{include file="buttons/clone_delete.tpl" microformats="cm-delete-row" no_confirm=true}
</td>
</tr>
<tr class="cr-table-detail" id="ab__vg_video_extra_{$_key}">
<td colspan="5">
<div class="control-group">
<label class="control-label" for="ab__vg__youtube_id__{$_key}">{__("ab__vg.form.youtube_id")}{include file="common/tooltip.tpl" tooltip=__("ab__vg.form.youtube_id.tooltip")}</label>
<div class="controls">
<input type="text" name="product_data[ab__vg_videos][{$_key}][youtube_id]" id="ab__vg__youtube_id__{$_key}" size="55" value="{$video.youtube_id}" />
</div>
</div>
<div class="control-group">
<label class="control-label" for="ab__vg__icon_type__{$_key}">{__("ab__vg.form.icon_type")}</label>
<div class="controls">
<label class="radio inline" for="ab__vg__icon_type__{$_key}__snapshot"><input type="radio" name="product_data[ab__vg_videos][{$_key}][icon_type]" id="ab__vg__icon_type__{$_key}__snapshot" {if $video.icon_type == 'snapshot'}checked="checked"{/if} value="snapshot" />{__("ab__vg.form.icon_type.snapshot")}</label>
<label class="radio inline" for="ab__vg__icon_type__{$_key}__icon"><input type="radio" name="product_data[ab__vg_videos][{$_key}][icon_type]" id="ab__vg__icon_type__{$_key}__icon" {if $video.icon_type == 'icon'}checked="checked"{/if} value="icon" />{__("ab__vg.form.icon_type.icon")}</label>
</div>
</div>
<div class="control-group">
<label class="control-label" for="ab__vg__icon__{$_key}">{__("ab__vg.form.icon")}</label>
<div class="controls">
{include file="common/attach_images.tpl" image_name="video_icon" image_object_type="ab__vg_video" image_key=$_key image_pair=$video.icon no_detailed=true hide_titles=true}
</div>
</div>
<div class="control-group">
<label class="control-label" for="ab__vg__description__{$_key}">{__("ab__vg.form.description")}</label>
<div class="controls">
<textarea id="ab__vg__description__{$_key}" name="product_data[ab__vg_videos][{$_key}][description]" cols="55" rows="8" class="cm-wysiwyg">{$video.description}</textarea>
</div>
</div>
</td>
</tr>
</tbody>
{/foreach}
{math equation="x+1" x=$_key|default:0 assign="new_key"}
<tbody id="box_add_ab__vg_video">
<tr class="{cycle values="table-row , " reset=1}{$no_hide_input_if_shared_product}">
<td width="2%">
<span id="on_ab__vg_video_extra_{$new_key}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand hidden cm-combination-video_extra"><span class="exicon-expand"></span></span>
<span id="off_ab__vg_video_extra_{$new_key}" alt="{__("expand_collapse_list")}" title="{__("expand_collapse_list")}" class="hand cm-combination-video_extra"><span class="exicon-collapse"></span></span>
</td>
<td>
<input type="hidden" name="product_data[ab__vg_videos][{$new_key}][video_id]" value="" />
<input type="text" name="product_data[ab__vg_videos][{$new_key}][pos]" value="" class="input-micro" />
</td>
<td><input type="text" name="product_data[ab__vg_videos][{$new_key}][title]" value="" class="input-large" /></td>
<td>
{include file="common/select_status.tpl" input_name="product_data[ab__vg_videos][{$new_key}][status]" id="ab__vg_status_`$new_key`" hidden=false display="popup"}
</td>
<td class="right">
{include file="buttons/multiple_buttons.tpl" item_id="add_ab__vg_video"}
</td>
</tr>
<tr class="cr-table-detail" id="ab__vg_video_extra_{$new_key}">
<td colspan="5">
<div class="control-group">
<label class="control-label" for="ab__vg__youtube_id__{$new_key}">{__("ab__vg.form.youtube_id")}{include file="common/tooltip.tpl" tooltip=__("ab__vg.form.youtube_id.tooltip")}</label>
<div class="controls">
<input type="text" name="product_data[ab__vg_videos][{$new_key}][youtube_id]" id="ab__vg__youtube_id__{$new_key}" size="55" value="" />
</div>
</div>
<div class="control-group">
<label class="control-label" for="ab__vg__icon_type__{$new_key}">{__("ab__vg.form.icon_type")}</label>
<div class="controls">
<label class="radio inline" for="ab__vg__icon_type__{$new_key}__snapshot"><input type="radio" name="product_data[ab__vg_videos][{$new_key}][icon_type]" id="ab__vg__icon_type__{$new_key}__snapshot" value="snapshot" />{__("ab__vg.form.icon_type.snapshot")}</label>
<label class="radio inline" for="ab__vg__icon_type__{$new_key}__icon"><input type="radio" name="product_data[ab__vg_videos][{$new_key}][icon_type]" id="ab__vg__icon_type__{$new_key}__icon" value="icon" />{__("ab__vg.form.icon_type.icon")}</label>
</div>
</div>
<div class="control-group">
<label class="control-label" for="ab__vg__icon__{$new_key}">{__("ab__vg.form.icon")}</label>
<div class="controls">
{include file="common/attach_images.tpl" image_name="video_icon" image_object_type="ab__vg_video" image_key=$new_key image_pair="" no_detailed=true hide_titles=true}
</div>
</div>
<div class="control-group">
<label class="control-label" for="ab__vg__description__{$new_key}">{__("ab__vg.form.description")}</label>
<div class="controls">
<textarea id="ab__vg__description__{$new_key}" name="product_data[ab__vg_videos][{$new_key}][description]" cols="55" rows="8" class="cm-wysiwyg"></textarea>
</div>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</div>
{/if}