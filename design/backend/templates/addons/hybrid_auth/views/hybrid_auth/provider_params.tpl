<div id="content_params_{$id}">
    {foreach from=$providers_schema[$provider].params item="param" key="param_id"}
        {if $param.type == "input"}
            <div class="control-group">
                <label for="section_{$param_id}_{$id}" class="control-label{if $param.required} cm-required{/if}">{__($param.label)}:</label>
                <div class="controls">
                    <input type="text" name="provider_data[params][{$param_id}]" size="30" value="{$provider_data['params'][$param_id]|default:$param.default}" id="section_{$param_id}_{$id}">
                </div>
            </div>

        {elseif $param.type == "checkbox"}
            <div class="control-group">
                <label for="section_{$param_id}_{$id}" class="control-label{if $param.required} cm-required{/if}">{__($param.label)}:</label>
                <div class="controls">
                    <input type="hidden" name="provider_data[params][{$param_id}]" value="N" />
                    <input type="checkbox" name="provider_data[params][{$param_id}]" value="Y" id="section_{$param_id}_{$id}"
                        {if (!isset($provider_data['params'][$param_id]) && $param.default == "Y") || (isset($provider_data['params'][$param_id]) && $provider_data['params'][$param_id] == "Y")}checked="checked"{/if}>
                </div>
            </div>

        {elseif $param.type == "template"}
            {include file=$param.template label=$param.label callback_url=$param.callback_url}

        {elseif $param.type == "select"}
            <div class="control-group">
                <label for="section_{$param_id}_{$id}" class="control-label{if $param.required} cm-required{/if}">{__($param.label)}{include file="common/tooltip.tpl" tooltip=__($param.tooltip)} :</label>
                <div class="controls">
                    <select name="provider_data[params][{$param_id}]" id="section_{$param_id}_{$id}">
                        {foreach from=$param.options item=option key="value"}
                        <option value={$value} {if $value == $provider_data['params'][$param_id]|default:$param.default}selected="selected"{/if}>{__($option)}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        {/if}
    {/foreach}
<!--content_params_{$id}--></div>
