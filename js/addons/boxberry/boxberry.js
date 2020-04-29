(function(window, document, undefined) {
    var firstScriptElement = document.getElementsByTagName("script")[0];
    var scriptElement = document.createElement("script");
    var placeWidgetScript = function () {
        firstScriptElement.parentNode.insertBefore(scriptElement, firstScriptElement);
    };
    var widget_src = "//points.boxberry.de/js/boxberry.js";

    if($('.bxb_link').data('widget_url')){
        widget_src = $('.bxb_link').data('widget_url');
    }

    scriptElement.type = "text/javascript";
    scriptElement.src = widget_src;
    if (window.opera == "[object Opera]") {
        document.addEventListener("DOMContentLoaded", placeWidgetScript, false);
    } else {
        placeWidgetScript();
    }
    document.addEventListener('click', function(e) {
        if (e.target && (e.target instanceof HTMLElement) && e.target.getAttribute('data-boxberry-open') == 'true') {
            e.preventDefault();
            var selectPointLink = e.target;
            (function(selectedPointLink) {
                var city = selectPointLink.getAttribute('data-boxberry-city') || undefined;
                var callbackInputAttr = selectPointLink.getAttribute('data-boxberry-point-input');
                var callbackFullNameInputAttr = selectPointLink.getAttribute('data-boxberry-point-full-name-input');
                var paymentSum = selectPointLink.getAttribute('data-paymentsum');
                var orderSum = selectPointLink.getAttribute('data-ordersum');
                var apiUrl = selectPointLink.getAttribute('data-api_url');
                var sucrh = selectPointLink.getAttribute('data-sucrh');
                var callbackInput;
                var callbackFullNameInput;
                if (callbackInputAttr[0] == '#') {
                    callbackInput = document.getElementById(callbackInputAttr.substr(1));
                } else {
                    callbackInput = document.getElementsByName(callbackInputAttr).item(0);
                }
                if (callbackFullNameInputAttr[0] == '#') {
                    callbackFullNameInput = document.getElementById(callbackFullNameInputAttr.substr(1));
                } else {
                    callbackFullNameInput = document.getElementsByName(callbackFullNameInputAttr).item(0);
                }

				if (typeof (callbackFullNameInput) == undefined || callbackFullNameInput==null){
                    inp_id  = document.getElementById('selected_point_id');
                    var input_bxb_name = inp_id.name;
                    var input_full_name = input_bxb_name.replace ('boxberry_selected_point','boxberry_selected_point_full_name');
					var callbackFullNameInput = document.createElement("input");
                        callbackFullNameInput.type = "hidden";
                        callbackFullNameInput.name = input_full_name;
                        console.log(callbackFullNameInput);
						document.getElementsByTagName('BODY')[0].appendChild(callbackFullNameInput);

				}
                var boxberryPointSelectedHandler = function (result) {
                    var selectedPointName = result.name + ' (' + result.address + ')';
                    selectedPointLink.textContent = selectedPointName;
                    callbackFullNameInput.value = selectedPointName;
                    callbackInput.value = result.id;
                    var data = {};
                    data[callbackInput.getAttribute('name')] = result.id;
                    data[callbackFullNameInput.getAttribute('name')] = selectedPointName;
                    $( "input[name='user_data[s_address]']" ).val(result.address);
					$.ceAjax('request', fn_url('checkout.update_steps' ), {
                        method: 'post',
                        caching: false,
                        recalculate: true,
                        data: data,
                        callback: function() {
                            fn_calculate_total_shipping_cost();
                        }
                    });

                };
				
                var token = selectPointLink.getAttribute('data-boxberry-token');
                var targetStart = selectedPointLink.getAttribute('data-boxberry-target-start');
                var weight = selectedPointLink.getAttribute('data-boxberry-weight');
                boxberry.versionAPI(apiUrl);
                boxberry.sucrh(sucrh);
				boxberry.open(boxberryPointSelectedHandler, token, city, '', orderSum, weight, paymentSum, 0, 0, 0);
            })(selectPointLink);
        }
    }, true);
})(window, document, undefined);

function token_callback(result){
	shipping_id = $('form[name="shippings_form"]').find(('input[name="shipping_id"]')).val();
    if (result.token != undefined){
        api_token_input = document.getElementById("password");
        reg_link = document.getElementById("reg_to_boxberry");
        api_token_input.value = result.token;
        $.ceAjax('request', fn_url('boxberry.save_token' ), {
            method: 'post',
            caching: false,
            data: {token:result.token,shipping_id:shipping_id },
            callback: function() {
                reg_link.remove();
            }
        });
    }
}

function callback_backend(result){
	order_id = $('form[name="order_info_form"]').find('input[name="order_id"]').val();
	$.ceAjax('request', fn_url('orders.update_details' ), {
		method: 'post',
		caching: false,
		data: {'point_id':result.id,'order_id':order_id},
		callback: function(){
			$('.bxb_office_number').html(result.id);
		}
	});	
}

jQuery(document).on('click','button[name="dispatch[checkout.update_steps]"]',function(e){

    var checked = jQuery('input[name="shipping_ids[0]"]:checked').val();

    if ( jQuery('input[name="boxberry_selected_point[0][' + checked + ']"]').length == 1 && jQuery('input[name="boxberry_selected_point_full_name[0][' + checked + ']"]').length == 1){
        if(!jQuery('input[name="boxberry_selected_point_full_name[0][' + checked + ']"]').val() || !jQuery('input[name="boxberry_selected_point[0][' + checked + ']"]').val() ){
                document.getElementById('bxb_link_' + checked).setAttribute('style','color:red');
             return false;
        } else {
            document.getElementById('bxb_link_' + checked).removeAttribute('style');
        }
    }

});
