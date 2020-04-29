(function(_, $) {

    $(document).on('sbCityChanged', function(event) {
        console.log('Данные о новом городе тут:', event.originalEvent.detail);
        var params={};
        params['data']=event.originalEvent.detail;
        $.ceAjax('request',fn_url('salesbeat.change_choosen_shipping'),params);
    });

    $(document).ready(function() {

        if($("div").is("#sb-cart-widget")) {
            if($('#sb-cart-widget').text().length==0) {
                fn_salesbeat_cart_init();
            }
        }

        if($("div").is("#sb-cart-pvz-map")) {
            if($('#sb-cart-pvz-map').text().length==0) {
                fn_salesbeat_pvz_init();
            }
        }

    });

    $.ceEvent('on', 'ce.ajaxdone', function(context) {
        if($("div").is("#sb-cart-widget")) {
            if($('#sb-cart-widget').text().length==0) {
                fn_salesbeat_cart_init();
            }
        }

        if($("div").is("#sb-cart-pvz-map")) {
            if($('#sb-cart-pvz-map').text().length==0) {
                fn_salesbeat_pvz_init();
            }
        }
    });

    $.ceEvent('on', 'ce.commoninit', function(context) {
        $("[name='customer_location[city]']").autocomplete({
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
                    $("[name='customer_location[city]']").val("");
                }
            },
            select: function(event, ui) {
                if($("[name='customer_location[city]']").parents('form').find("[name='city_id']").length > 0) {
                    $("[name='city_id']").val(ui.item.value.split('|')[0]);

                } else {
                    $("[name='customer_location[city]']").parents('form').append("<input type=\"hidden\"  name=\"user_data[city_id]\" value=\""+ui.item.value.split('|')[0]+"\">");                      
                }
                $("[name='customer_location[region]']").val(ui.item.value.split('|')[1]);
                ui.item.value=ui.item.label.split(',')[0];
            }
        });


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
                $("[name='user_data[b_city]']").parents('.checkout__block').find(".ty-shipping-state input").val(ui.item.value.split('|')[1]);
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
                if($("[name='user_data[s_city]']").parents('form').find("[name='city_id']").length > 0) {
                    $("[name='city_id']").val(ui.item.value.split('|')[0]);

                } else {
                    $("[name='user_data[s_city]']").parents('form').append("<input type=\"hidden\"  name=\"user_data[s_city_id]\" value=\""+ui.item.value.split('|')[0]+"\">");                      
                }

                $("[name='user_data[s_city]']").parents('.checkout__block').find(".ty-shipping-state input").val(ui.item.value.split('|')[1]);
                ui.item.value=ui.item.label.split(',')[0];
            }
        });
    });

    function fn_salesbeat_cart_init() {
       var params=$('form#sb_widget_params').serializeJSON();
       var products=params.products;
       params.products=[];

       $.each(products, function (index, product) {
            $.each(product, function (index1, attr) {
                product[index1]=parseInt(attr);
            });
            params.products.push(product);
       });

       SB.init_cart({
           token: params.token,
           city_by: params.city_by,
           city_code: params.city_id,
           products: params.products,
           callback: function(data) {
                $.ceDialog('get_last').ceDialog('close');
                var request_params={};
                request_params['result_ids']=params.result_ids;
                request_params['full_render']=true;
                data['redirect_mode']=params.redirect_mode;
                data['update_salesbeat_step']='Y';
                if(params.next_step) {
                    data['next_step']=params.next_step;
                }
                if(params.update_step) {
                    data['update_step']=params.update_step;
                }
                if(params.update_salesbeat_step) {
                    data['update_salesbeat_step']=params.update_salesbeat_step;
                }
                request_params['data']=data;
                request_params['method']='post';
                $.ceAjax('request',params.target_url,request_params);
               
           }
       },{
          delivery_method_id: params.delivery_method_id,
          street: params.street, 
          house: params.house, 
          house_block: params.house_block,  
          flat: params.flat,  
          index: params.index,
          comment: params.comment,
          pvz_id: params.pvz_id
      });
    }

    function fn_salesbeat_pvz_init() {
       var params=$('form[name="step_three_payment_and_shipping"]').serializeJSON();

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
                request_params['result_ids']=params.result_ids;
                request_params['full_render']=true;
                data['redirect_mode']=params.redirect_mode;
                data['update_salesbeat_step']='Y';
                if(params.next_step) {
                    data['next_step']=params.next_step;
                }
                if(params.update_step) {
                    data['update_step']=params.update_step;
                }
                if(params.update_salesbeat_step) {
                    data['update_salesbeat_step']=params.update_salesbeat_step;
                }
                request_params['data']=data;
                request_params['method']='post';
                $.ceAjax('request',params.target_url,request_params);
           }
       },{
          delivery_method_id: params.delivery_method_id,
          street: params.street, 
          house: params.house, 
          house_block: params.house_block,  
          flat: params.flat,  
          index: params.index,
          comment: params.comment,
          pvz_id: params.pvz_id
      });
    }
}(Tygh, Tygh.$));
