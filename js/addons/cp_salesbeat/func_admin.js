(function(_, $) {

    $(document).ready(function() {

        if($("div").is("#sb-cart-pvz-map")) {
            if($('#sb-cart-pvz-map').text().length==0) {
                fn_salesbeat_pvz_init();
            }
        }

    });

    $.ceEvent('on', 'ce.ajaxdone', function(context) {
        if($("div").is("#sb-cart-pvz-map")) {
            if($('#sb-cart-pvz-map').text().length==0) {
                fn_salesbeat_pvz_init();
            }
        }
    });


    $.ceEvent('on', 'ce.commoninit', function(context) {

        $("[name='user_data[b_city]']").autocomplete({
            source: function( request, response ) {
                $.ceAjax('request',fn_url('salesbeat.get_cities?q='+encodeURIComponent(request.term)),{'callback': function(data) {
                    var cities=[];
                    result=JSON.parse(data.text);
                    if(result.cities) {
                        $.each(result.cities, function( index, val ){
                            cities[index]={label: val['name']+', '+val['region_name'], value : val['id']+'|'+val['region_name']};
                        });
                        response(cities);
                    }
                }});
            },
            change: function(event, ui) {
                if(!ui.item) {
                    $("[name='user_data[b_city]']").val("");
                }
            },
            select: function(event, ui) {
                if($("[name='ship_to_another']:checked").val()=='0') {
                    if($("[name='user_data[b_city]']").parents('form').find("[name='s_city_id']").length > 0) {
                        $("[name='s_city_id']").val(ui.item.value.split('|')[0]);

                    } else {
                        $("[name='user_data[b_city]']").parents('form').append("<input type=\"hidden\"  name=\"user_data[s_city_id]\" value=\""+ui.item.value.split('|')[0]+"\">");                      
                    }
                }
                $(".profile-field-b_region input").val(ui.item.value.split('|')[1]);
                ui.item.value=ui.item.label.split(',')[0];
            }
        });

        $("[name='user_data[s_city]']").autocomplete({
            source: function( request, response ) {
                $.ceAjax('request',fn_url('salesbeat.get_cities?q='+encodeURIComponent(request.term)),{'callback': function(data) {
                    var cities=[];
                    result=JSON.parse(data.text);
                    if(result.cities) {
                        $.each(result.cities, function( index, val ){
                            cities[index]={label: val['name']+', '+val['region_name'], value : val['id']+'|'+val['region_name']};
                        });
                        response(cities);
                    }
                }});
            },
            change: function(event, ui) {
                if(!ui.item) {
                    $("[name='user_data[s_city]']").val("");
                }
            },
            select: function(event, ui) {
                if($("[name='user_data[s_city]']").parents('form').find("[name='s_city_id']").length > 0) {
                    $("[name='s_city_id']").val(ui.item.value.split('|')[0]);

                } else {
                    $("[name='user_data[s_city]']").parents('form').append("<input type=\"hidden\"  name=\"user_data[s_city_id]\" value=\""+ui.item.value.split('|')[0]+"\">");                      
                }

                $(".profile-field-s_region input").val(ui.item.value.split('|')[1]);
                ui.item.value=ui.item.label.split(',')[0];
            }
        });
    });
        $.ceEvent('on', 'ce.ajaxdone', function(elms, scripts, params, response_data, response_text) {

        if(("dispatch[salesbeat.m_export_to_salesbeat]" in params.data)&&response_text) {
            if(!$("div").is("#info_message")) {
                $("<div id='info_message'></div>").appendTo("body");
            }

            var params = {
                width: '500',
                height: '300',
                title: 'Вызов курьеров'
            };


            $('#info_message').html(response_text);
            $('#info_message').ceDialog('open', params);
            $('#courier_date').datepicker({
                changeMonth: true,
                duration: 'fast',
                changeYear: true,
                numberOfMonths: 1,
                selectOtherMonths: true,
                showOtherMonths: true,
                firstDay: 0,
                dayNamesMin: ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'],
                monthNamesShort: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            });
        }

    });

    function fn_salesbeat_pvz_init() {
       var params=$('form[name="om_cart_form"]').serializeJSON();
       var products=params.products;
       params.products=[];
    
       $.each(products, function (index, product) {
            $.each(product, function (index1, attr) {
                product[index1]=parseInt(attr);
            });
            params.products.push(product);
       });
       SB.show_pvz_map({
           token: params.token,
           city_by: params.city_by,
           city_code: params.city_id,
           products: params.products,
           callback: function(data) {
                $.ceDialog('get_last').ceDialog('close');
                var request_params={};
                request_params['data']=data;
                request_params['method']='post';
                $.ceAjax('request',fn_url('salesbeat.change_pvz'),request_params);
           }
       },{
          delivery_method_id: params.delivery_method_id,
          pvz_id: params.pvz_id
      });
    }
}(Tygh, Tygh.$));
