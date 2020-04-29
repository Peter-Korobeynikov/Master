{* rus_build_pack dbazhenov *}

<script type="text/javascript">
//<![CDATA[
(function(_, $) {
    $(document).ready(function() {
        fn_get_rbx_currencies();
        $('#rbx_get_currencies').on('click', fn_get_rbx_currencies);
    });

    function fn_get_rbx_currencies() {
        var merchantid = $('#rbx_merchantid').val();
        $.ceAjax('request', '{fn_url("payment_notification.rbx_get_currencies")}', {
            data: {
                payment: 'robokassa',
                merchantid: merchantid,
                result_ids: 'rbx_currency_div',
                payment_id: {$smarty.request.payment_id},
            },
        });
    }
}(Tygh, Tygh.$));
//]]>
</script>
{if $settings.Security.secure_storefront === "YesNo::YES"|enum}
    {$r_url="payment_notification.result?payment=robokassa"|fn_url:'C':'https'}
    {$p_url="payment_notification.return?payment=robokassa"|fn_url:'C':'https'}
    {$f_url="payment_notification.cancel?payment=robokassa"|fn_url:'C':'https'}
{else}
    {$r_url="payment_notification.result?payment=robokassa"|fn_url:'C':'http'}
    {$p_url="payment_notification.return?payment=robokassa"|fn_url:'C':'http'}
    {$f_url="payment_notification.cancel?payment=robokassa"|fn_url:'C':'http'}
{/if}

<div>
    {__("text_robokassa_notice", ["[r_url]" => $r_url, "[p_url]" => $p_url, "[f_url]" => $f_url])}
</div> 
<hr>

<div class="control-group">
    <label class="control-label" for="rbx_merchantid">{__("merchantid")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][merchantid]" id="rbx_merchantid" value="{$processor_params.merchantid}"  size="60"><a href="#" id="rbx_get_currencies">{__("payments.robokassa.get_currencies")}</a>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="rbx_password1">{__("password1")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][password1]" id="rbx_password1" value="{$processor_params.password1}"  size="60">
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="rbx_password2">{__("password2")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][password2]" id="rbx_password2" value="{$processor_params.password2}"  size="60">
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="rbx_descr">{__("description")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][details]" id="rbx_descr" value="{$processor_params.details}"  size="60">
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="rbx_mode">{__("test_live_mode")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][mode]" id="rbx_mode">
            <option value="test"{if $processor_params.mode == 'test'} selected="selected"{/if}>{__("test")}</option>
            <option value="live"{if $processor_params.mode == 'live'} selected="selected"{/if}>{__("live")}</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="rbx_commission">{__("commission")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][commission]" id="rbx_commission">
            <option value="customer"{if $processor_params.commission == 'customer'} selected="selected"{/if}>{__("customer")}</option>
            <option value="admin"{if $processor_params.commission == 'admin'} selected="selected"{/if}>{__("administrator")}</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="currency_{$payment_id}">{__("currency")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][currency]" id="currency_{$payment_id}">
            <option value="RUB"{if $processor_params.currency == "RUB"} selected="selected"{/if}>{__("currency_code_rub")}</option>
            <option value="USD"{if $processor_params.currency == "USD"} selected="selected"{/if}>{__("currency_code_usd")}</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="rbx_currency">{__("payment_method")}:</label>
    {include file="addons/rus_payments/views/payments/components/cc_processors/robokassa_cur_selectbox.tpl"}
</div>

{include file="common/subheader.tpl" title=__("rus_payments.robokassa.text_status_map") target="#text_status_map"}

<div id="text_status_map" class="in collapse">
    {assign var="statuses" value=$smarty.const.STATUSES_ORDER|fn_get_simple_statuses}

    <div class="control-group">
        <label class="control-label" for="elm_paid">{__("rus_payments.robokassa.paid")}:</label>
        <div class="controls">
            <select name="payment_data[processor_params][statuses][paid]" id="elm_paid">
                {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.statuses.paid) && $processor_params.statuses.paid == $k) || (!isset($processor_params.statuses.paid) && $k == 'P')}selected="selected"{/if}>{$s}</option>
                {/foreach}
            </select>
        </div>
    </div>
</div>
