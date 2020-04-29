<div class="control-group">
    <label class="control-label" for="tinkoff_merchant_id">{__("tinkoff_merchant_id")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][merchant_id]" id="tinkoff_merchant_id" value="{$processor_params.merchant_id}"  size="60">
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="tinkoff_secret_key">{__("tinkoff_secret_key")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][secret_key]" id="tinkoff_secret_key" value="{$processor_params.secret_key}"  size="60">
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="tinkoff_cheque">{__("tinkoff_cheque")}:</label>
    <div class="controls">
        <div>{__("tinkoff_cheque_comment")}</div>
        <select onchange="getValue()"name="payment_data[processor_params][cheque]" id="tinkoff_cheque" value="{$processor_params.cheque}">
            <option value="tinkoff_no"{if $processor_params.cheque == "tinkoff_no"} selected="selected"{/if}>{__("tinkoff_no")}</option>
            <option value="tinkoff_yes"{if $processor_params.cheque == "tinkoff_yes"} selected="selected"{/if}>{__("tinkoff_yes")}</option>
        </select>
    </div>
</div>

<div class="control-group tinkoffPayment">
    <label class="control-label" for="tinkoff_email_comapny">{__("tinkoff_email_company")}:</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][email_company]" id="tinkoff_email_company" value="{$processor_params.email_company}"  size="60">
    </div>
</div>

<div class="control-group tinkoffPayment">
    <label class="control-label" for="tinkoff_taxation">{__("tinkoff_taxation")}:</label>
    <div class="controls">
        <div>{__("tinkoff_taxation_comment")}</div>
        <select name="payment_data[processor_params][taxation]" id="tinkoff_taxation" value="{$processor_params.taxation}">
            <option value="tinkoff_osn"{if $processor_params.taxation == "tinkoff_osn"} selected="selected"{/if}>{__("tinkoff_osn")}</option>
            <option value="tinkoff_usn_income"{if $processor_params.taxation == "tinkoff_usn_income"} selected="selected"{/if}>{__("tinkoff_usn_income")}</option>
            <option value="tinkoff_usn_income_outcome"{if $processor_params.taxation == "tinkoff_usn_income_outcome"} selected="selected"{/if}>{__("tinkoff_usn_income_outcome")}</option>
            <option value="tinkoff_envd"{if $processor_params.taxation == "tinkoff_envd"} selected="selected"{/if}>{__("tinkoff_envd")}</option>
            <option value="tinkoff_esn"{if $processor_params.taxation == "tinkoff_esn"} selected="selected"{/if}>{__("tinkoff_esn")}</option>
            <option value="tinkoff_patent"{if $processor_params.taxation == "tinkoff_patent"} selected="selected"{/if}>{__("tinkoff_patent")}</option>
        </select>
    </div>
</div>

<div class="control-group tinkoffPayment">
    <label class="control-label" for="tinkoff_payment_method">{__("tinkoff_payment_method")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][payment_method]" id="tinkoff_payment_method" value="{$processor_params.payment_method}">
            <option value="tinkoff_full_prepayment"{if $processor_params.payment_method == "tinkoff_full_prepayment"} selected="selected"{/if}>{__("tinkoff_full_prepayment")}</option>
            <option value="tinkoff_prepayment"{if $processor_params.payment_method == "tinkoff_prepayment"} selected="selected"{/if}>{__("tinkoff_prepayment")}</option>
            <option value="tinkoff_advance"{if $processor_params.payment_method == "tinkoff_advance"} selected="selected"{/if}>{__("tinkoff_advance")}</option>
            <option value="tinkoff_full_payment "{if $processor_params.payment_method == "tinkoff_full_payment "} selected="selected"{/if}>{__("tinkoff_full_payment ")}</option>
            <option value="tinkoff_partial_payment"{if $processor_params.payment_method == "tinkoff_partial_payment"} selected="selected"{/if}>{__("tinkoff_partial_payment")}</option>
            <option value="tinkoff_credit"{if $processor_params.payment_method == "tinkoff_credit"} selected="selected"{/if}>{__("tinkoff_credit")}</option>
            <option value="tinkoff_credit_payment"{if $processor_params.payment_method == "tinkoff_credit_payment"} selected="selected"{/if}>{__("tinkoff_credit_payment")}</option>
        </select>
    </div>
</div>

<div class="control-group tinkoffPayment">
    <label class="control-label" for="tinkoff_payment_object">{__("tinkoff_payment_object")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][payment_object]" id="tinkoff_payment_object" value="{$processor_params.payment_object}">
            <option value="tinkoff_commodity"{if $processor_params.payment_object == "tinkoff_commodity"} selected="selected"{/if}>{__("tinkoff_commodity")}</option>
            <option value="tinkoff_excise"{if $processor_params.payment_object == "tinkoff_excise"} selected="selected"{/if}>{__("tinkoff_excise")}</option>
            <option value="tinkoff_job"{if $processor_params.payment_object == "tinkoff_job"} selected="selected"{/if}>{__("tinkoff_job")}</option>
            <option value="tinkoff_service"{if $processor_params.payment_object == "tinkoff_service"} selected="selected"{/if}>{__("tinkoff_service")}</option>
            <option value="tinkoff_gambling_bet"{if $processor_params.payment_object == "tinkoff_gambling_bet"} selected="selected"{/if}>{__("tinkoff_gambling_bet")}</option>
            <option value="tinkoff_gambling_prize"{if $processor_params.payment_object == "tinkoff_gambling_prize"} selected="selected"{/if}>{__("tinkoff_gambling_prize")}</option>
            <option value="tinkoff_lottery"{if $processor_params.payment_object == "tinkoff_lottery"} selected="selected"{/if}>{__("tinkoff_lottery")}</option>
            <option value="tinkoff_lottery_prize"{if $processor_params.payment_object == "tinkoff_lottery_prize"} selected="selected"{/if}>{__("tinkoff_lottery_prize")}</option>
            <option value="tinkoff_intellectual_activity"{if $processor_params.payment_object == "tinkoff_intellectual_activity"} selected="selected"{/if}>{__("tinkoff_intellectual_activity")}</option>
            <option value="tinkoff_payment"{if $processor_params.payment_object == "tinkoff_payment"} selected="selected"{/if}>{__("tinkoff_payment")}</option>
            <option value="tinkoff_agent_commission"{if $processor_params.payment_object == "tinkoff_agent_commission"} selected="selected"{/if}>{__("tinkoff_agent_commission")}</option>
            <option value="tinkoff_composite"{if $processor_params.payment_object == "tinkoff_composite"} selected="selected"{/if}>{__("tinkoff_composite")}</option>
            <option value="tinkoff_another"{if $processor_params.payment_object == "tinkoff_another"} selected="selected"{/if}>{__("tinkoff_another")}</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="tinkoff_language">{__("tinkoff_language")}:</label>
    <div class="controls">
        <select name="payment_data[processor_params][language]" id="tinkoff_language" value="{$processor_params.language}">
            <option value="tinkoff_ru"{if $processor_params.language == "tinkoff_ru"} selected="selected"{/if}>{__("tinkoff_ru")}</option>
            <option value="tinkoff_en"{if $processor_params.language == "tinkoff_en"} selected="selected"{/if}>{__("tinkoff_en")}</option>
        </select>
    </div>
</div>
<script>
    function showTinkoffTaxation(checked) {
        var $tinkoffTaxation = document.getElementsByClassName("tinkoffPayment");
        [].forEach.call($tinkoffTaxation, function (item) {
            switch(checked){
                case "tinkoff_no" :
                    item.style.display = "none";
                    break;
                case "tinkoff_yes" :
                    item.style.display = "block";
                    break;
            }
        })
    }

    function getValue() {
        var select = document.getElementById("tinkoff_cheque");
        var value = select.options[select.selectedIndex].value;
        this.showTinkoffTaxation(value);
    }
    getValue();
</script>