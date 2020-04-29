<select id="block_{$block.block_id}_content_ab__fn_common_btn_type" name="block_data[content][ab__fn_common_btn_type]">
<option value="ab__fn_cbt_btn"{if $block.content.ab__fn_common_btn_type == 'ab__fn_cbt_btn'} selected="selected"{/if}>{__('ab__fn_cbt_as_btn')}</option>
<option value="ab__fn_cbt_text"{if $block.content.ab__fn_common_btn_type == 'ab__fn_cbt_text'} selected="selected"{/if}>{__('ab__fn_cbt_as_txt')}</option>
</select>