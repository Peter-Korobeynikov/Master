<div class="control-group" id="order_sb_buttons">
        <div class="control-group">
            <strong>{__('salesbeat')}</strong>
        </div>
        {if $order_info.courier_called!='Y'}
            <div class="control-group">
                <a class="btn cm-ajax" href="{"salesbeat.export_to_salesbeat?order_id=`$order_info.order_id`"|fn_url}" data-ca-target-id="content_general">{__('export_to_salesbeat')}</a>
            </div>
        {/if}
        {if $order_info.sb_id&&$order_info.courier_called!='Y'}
            {if $order_info.sb_id|fn_cp_check_sb_shipment}
                <div class="control-group">
                    <div class="courier-time">
                        {include file="common/calendar.tpl" date_id="courier_date" date_name="date" time_name="time" date_val=$smarty.const.TIME start_year=$settings.Company.company_start_year show_time=true}
                        <input type="hidden" name="order_id" value="{$order_info.order_id}">
                    </div>
                    {include file="buttons/button.tpl" but_text=__('call_courier') but_name="dispatch[salesbeat.call_courier]" but_meta="cm-ajax"}
                </div>
            {/if}
        {/if}
        {if $order_info.courier_called=='Y'}
            <div class="control-group">
                {__('sb_courier_called')}
            </div>
        {/if}
<!--order_sb_buttons--></div>
