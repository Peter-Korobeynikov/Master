<form action="{""|fn_url}" method="post" class="form-table" name="info_message" enctype="multipart/form-data">
    <div class="info-message">
        {$info->info_message}
    </div>
    <div class="info-message-time">
        {include file="common/calendar.tpl" date_id="courier_date" date_name="date" time_name="time" date_val=$smarty.const.TIME start_year=$settings.Company.company_start_year show_time=true}
    </div>
    <div class="info-message-orders control-group">
        <label class="cm-required control-label hidden" for="sb_order_ids"></label>
        <div class="controls">
            <select name="order_ids[]" id="sb_order_ids" multiple>
                {foreach from=$info->items item="order"}
                    <option selected value="{$order->shop_order_id}">{__('order')} #{$order->shop_order_id}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="buttons-container">
        {include file="buttons/button.tpl" but_text=__("close") but_role="action" but_meta="cm-dialog-closer"}
        {include file="buttons/button.tpl" but_text=__('sb_call_couriers') but_name="dispatch[salesbeat.m_call_courier]" but_meta="cm-ajax cm-dialog-closer" but_role="button_main" }
    </div>
</form>
