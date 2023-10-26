/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 23:30:22 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/




$(document).on('change', "#order_for_collection", function(ev){



    if($(this).is(':checked')){
        $('#order_delivery_address_fields').addClass('hide')

    }else{
        $('#order_delivery_address_fields').removeClass('hide')

    }
});

var special_instructions_timeout

$(document).on('input propertychange', "#special_instructions", function(ev){


    if (special_instructions_timeout) clearTimeout(special_instructions_timeout);

    value= $(this).val()

    special_instructions_timeout = setTimeout(function () {

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'special_instructions')
        ajaxData.append("value",value)

        $.ajax({
            url: "/ar_web_basket.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
            }, success: function (data) {



                if (data.state == '200') {



                } else if (data.state == '400') {
                }



            }, error: function () {

            }
        });

    }, 400);






});




$(document).on('change', "#order_invoice_country_select", function(){



    var selected=$( "#invoice_country_select option:selected" )

    var request= "ar_web_addressing.php?tipo=address_format&country_code="+selected.val()+'&website_key='+$('#ordering_settings').data('website_key')

    $.getJSON(request, function( data ) {
        $.each(data.hidden_fields, function(index, value) {
            $('#order_invoice_'+value).addClass('hide')
            $('#order_invoice_'+value).find('input').addClass('ignore')

        });

        $.each(data.used_fields, function(index, value) {
            $('#invoice_'+value).removeClass('hide')
            $('#order_invoice_'+value).find('input').removeClass('ignore')

        });

        $.each(data.labels, function(index, value) {
            $('#order_invoice_'+index).find('input').attr('placeholder',value)
            $('#order_invoice_'+index).find('b').html(value)
            $('#order_invoice_'+index).find('label.label').html(value)

        });

        $.each(data.no_required_fields, function(index, value) {



            $('#order_invoice_'+value+' input').rules( "remove" );




        });

        $.each(data.required_fields, function(index, value) {

            $('#order_invoice_'+value+' input').rules( "add", { required: true});

        });


    });


});

$(document).on('change', "#order_delivery_country_select", function(){


    var selected=$( "#order_delivery_country_select option:selected" )

    var request= "ar_web_addressing.php?tipo=address_format&country_code="+selected.val()+'&website_key='+$('#ordering_settings').data('website_key')
    $.getJSON(request, function( data ) {
        $.each(data.hidden_fields, function(index, value) {


            $('#order_delivery_'+value).addClass('hide')
            $('#order_delivery_'+value).find('input').addClass('ignore')

        });

        $.each(data.used_fields, function(index, value) {
            $('#order_delivery_'+value).removeClass('hide')
            $('#order_delivery_'+value).find('input').removeClass('ignore')

        });

        $.each(data.labels, function(index, value) {
            $('#order_delivery_'+index).find('input').attr('placeholder',value)
            $('#order_delivery_'+index).find('b').html(value)
            $('#order_delivery_'+index).find('label.label').html(value)

        });

        $.each(data.no_required_fields, function(index, value) {

            $('#order_delivery_'+value+' input').rules( "remove" );
        });

        $.each(data.required_fields, function(index, value) {


            $('#order_delivery_'+value+' input').rules( "add", { required: true});

        });


    });


});



function  web_toggle_selected_by_customer_charge(element){


    if($(element).hasClass('wait')){
        return;
    }

    if($(element).hasClass('fa-toggle-on')){
        var operation='remove_charge';
    }else{
        var operation='add_charge';
    }


    $(element).addClass('wait fa-spin fa-spinner').removeClass('fa-toggle-on fa-toggle-off')



    var ajaxData = new FormData();

    ajaxData.append("tipo",'web_toggle_charge')
    ajaxData.append("operation",operation)
    ajaxData.append("charge_key", $(element).data('charge_key'))





    $.ajax({
        url: "/ar_web_basket.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {




            $(element).removeClass('wait')



            console.log(data)

            if (data.state == '200') {



                if(data.operation=='add_charge' ) {
                    $(element).addClass('fa-toggle-on').removeClass('fa-spinner fa-spin');
                    $(element).closest('tr').find('.selected_by_customer_charge').html(data.transaction_data.amount)


                    $('.Order_Priority_Icon').removeClass('hide')
                    $('.priority_label').removeClass('hide')


                }else{

                    $(element).addClass('fa-toggle-off').removeClass('fa-spinner fa-spin');
                    $(element).closest('tr').find('.selected_by_customer_charge').html('')

                    $('.Order_Priority_Icon').addClass('hide')
                    $('.priority_label').addClass('hide')

                }



                $('.Total_Amount').attr('amount', data.metadata.to_pay)
                $('.Order_To_Pay_Amount').attr('amount', data.metadata.to_pay)


                $('#Shipping_Net_Amount_input').val(data.metadata.shipping).attr('ovalue', data.metadata.shipping)
                $('#Charges_Net_Amount_input').val(data.metadata.charges).attr('ovalue', data.metadata.charges)

                if (data.metadata.to_pay == 0) {
                    $('.Order_Payments_Amount').addClass('hide')
                    $('.Order_To_Pay_Amount').addClass('hide')

                } else {
                    $('.Order_Payments_Amount').removeClass('hide')
                    $('.Order_To_Pay_Amount').removeClass('hide')

                }

                if (data.metadata.to_pay != 0 || data.metadata.payments == 0) {
                    $('.Order_Paid').addClass('hide')
                } else {
                    $('.Order_Paid').removeClass('hide')
                }

                if (data.metadata.to_pay <= 0) {
  $('.add_payment_to_order_button').addClass('fa-lock super_discreet').removeClass('fa-plus')
} else {
  $('.add_payment_to_order_button').removeClass('fa-lock super_discreet').addClass('fa-plus')
}


                if (data.metadata.to_pay == 0) {
                    $('.Order_To_Pay_Amount').removeClass('button').attr('amount', data.metadata.to_pay)

                } else {
                    $('.Order_To_Pay_Amount').addClass('button').attr('amount', data.metadata.to_pay)

                }



                for (var key in data.metadata.class_html) {
                    $('.' + key).html(data.metadata.class_html[key])
                }


                for (var key in data.metadata.hide) {
                    $('#' + data.metadata.hide[key]).addClass('hide')
                }
                for (var key in data.metadata.show) {
                    $('#' + data.metadata.show[key]).removeClass('hide')
                }



            }
            else {

                swal("Error!",'', "error")


                console.log(element)
                $(element).removeClass('fa-spinner fa-spin');

                if(operation=='remove_charge'){
                    $(element).addClass('fa-toggle-on')
                }else{
                    $(element).addClass('fa-toggle-off')
                }
            }


        }, error: function () {
            $(element).removeClass('fa-spinner fa-spin');

            if(operation=='remove_charge'){
                $(element).addClass('fa-toggle-on')
            }else{
                $(element).addClass('fa-toggle-off')
            }


        }
    });



}


function web_select_deal_component_choose_by_customer(element) {

    var container=$(element).closest('.deal_component_choose_by_customer')


    if($(element).data('product_id')==container.data('selected')){
        return;
    }


    if(container.hasClass('wait')){
        return;
    }

    container.addClass('wait')


    container.find('i').removeClass('fa-dot-circle').addClass('fa-circle')

    $(element).find('i').addClass('fa-spin fa-spinner').removeClass('fa-circle')






    var ajaxData = new FormData();

    ajaxData.append("tipo",'web_toggle_deal_component_choose_by_customer')


    ajaxData.append("deal_component_key", container.data('deal_component_key'))
    ajaxData.append("order_transaction_deal_bridge_key", container.data('order_transaction_deal_bridge_key'))
    ajaxData.append("product_id",$(element).data('product_id'))





    $.ajax({
        url: "/ar_web_basket.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
        }, success: function (data) {






            console.log(data)

            if (data.state == '200') {



                $(element).find('i').addClass('fa-spin fa-spinner').removeClass('fa-circle')


                var tr=$('#transaction_item_net_'+data.transaction_deal_data.otf_key).closest('tr')


                console.log(data.transaction_deal_data.Code)

                tr.find('.item_code').html(data.transaction_deal_data.Code)
                tr.find('.item_description').html(data.transaction_deal_data.Description)



                container.removeClass('wait').data('selected',data.transaction_deal_data.product_id)

                $(element).find('i').removeClass('fa-spin fa-spinner').addClass('fa-dot-circle')





            }
            else {


            }


        }, error: function () {
            $(element).removeClass('fa-spinner fa-spin');



        }
    });



}

function fixedEncodeURIComponent(str) {
    return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
}


$(document).on('input propertychange', '.add_item_form', function (evt) {
    if ($(evt.target).hasClass('item')) {
        var delay = 100;
        delayed_on_change_add_item_field($(this), delay)
    } else {
        validate_add_item($(this).closest('.add_item_form'))
    }
});

function delayed_on_change_add_item_field(object, timeout) {
    window.clearTimeout(object.data("timeout"));
    object.data("timeout", setTimeout(function () {
        get_items_select_for_add_item_to_order(object)
    }, timeout));
}


function get_items_select_for_add_item_to_order(element) {

    element.removeClass('invalid')


    var form_data = new FormData();
    form_data.append("tipo", 'find_product')
    form_data.append("product_code", element.find('.item').val() )


    var request = $.ajax({

        url: "/ar_web_find_product.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    })

    request.done(function (data) {


        if (data.state == 200) {

            if (data.number_results > 0) {

                var item_input=element.find('.item')
                var save_button=element.find('.add_item_save')
                var search_results_container=element.find('.search_results_container');

                item_input.removeClass('invalid')
                search_results_container.removeClass('hide').addClass('show').offset({ top: item_input.offset().top-14, left: save_button.offset().left-search_results_container.width()   })


            } else {

                element.find('.search_results_container').addClass('hide').removeClass('show')

                if (element.find('.item').val() != '') {
                    element.find('.item').addClass('invalid')
                } else {
                    element.find('.item').removeClass('invalid')
                }

                element.find('.add_item_save').data('item_key', '')
                element.find('.add_item_save').data('item_historic_key', '')

                validate_add_item(element)

            }


            element.find(".add_item_results .result").remove();

            var first = true;

            for (var result_key in data.results) {



                var clone = element.find(".add_item_search_result_template").clone()

                clone.addClass(data.results[result_key].state)
                clone.prop('id', 'add_item_result_' + result_key);
                clone.addClass('result').removeClass('hide add_item_search_result_template')


                clone.data('item_key', data.results[result_key].value)
                clone.data('item_historic_key', data.results[result_key].item_historic_key)

                clone.data('formatted_value', data.results[result_key].formatted_value)
                if (first) {
                    clone.addClass('selected')
                    first = false
                }

                clone.children(".label").html(data.results[result_key].description)
                clone.children(".code").html(data.results[result_key].code)

                element.find(".add_item_results").append(clone)


            }




        } else if (data.state == 400) {
            alert('error')

        }

    })



}

function select_add_item_option(element) {

    if($(element).hasClass('out_of_stock')){
        return;
    }

    var form=$(element).closest('.add_item_form')
    form.find('.item').val($(element).data('formatted_value'))
    form.find('.add_item_save').data('item_key', $(element).data('item_key')).data('item_historic_key', $(element).data('item_historic_key'))
    form.find('.search_results_container').addClass('hide').removeClass('show')

    form.find('.qty').trigger( "focus" )
    validate_add_item(form)
}


function validate_add_item(form) {


    var invalid = false;
    var input_qty=$(form).find('.qty')
    var input_item=$(form).find('.item')
    var save_button=$(form).find('.add_item_save')


    if (input_qty.val() == '') {
        input_qty.removeClass('invalid')

    } else {

        var qty_val = validate_signed_integer(input_qty.val(), 4294967295);
        if (!qty_val) {
            input_qty.removeClass('invalid')
        } else {
            input_qty.addClass('invalid')
            invalid = true
        }
    }

    if (input_item.hasClass('invalid')) {
        invalid = true;
    }

    if (invalid) {
        save_button.addClass('invalid').removeClass('super_discreet valid button changed')
    } else {
        save_button.removeClass('invalid')

        if (save_button.data('item_key') != '' && input_item.val() != '' && input_qty.val() != '') {
            save_button.addClass('valid button changed').removeClass('super_discreet')
        } else {
            save_button.removeClass('valid button changed').addClass('super_discreet')
        }

    }


}



function save_add_item(save_button) {

    if( !$(save_button).hasClass('valid')){
        return
    }



    var form=$(save_button).closest('.add_item_form')


    $(save_button).addClass('fa-spinner fa-spin');

    

    var form_data = new FormData();

    form_data.append("tipo", 'update_order_item')
    form_data.append("webpage_key", $('#webpage_data').data('webpage_key'))
    form_data.append("page_section_type", 'Add_Basket')

    form_data.append("product_id", $(save_button).data('item_key'))

    form_data.append("qty", form.find('.qty').val())

    var request = $.ajax({

        url: "/ar_web_update_order_item.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    })


    request.done(function (data) {

        $(save_button).removeClass('fa-spinner fa-spin');

        if (data.state == 200) {

            $(save_button).data('item_key', '')
            $(save_button).data('item_historic_key', '')
            form.find('.item').val('').trigger('focus').removeClass('invalid')
            form.find('.qty').val('').removeClass('invalid')
            $(save_button).addClass('super_discreet').removeClass('invalid valid button')


            var transaction_tr=$('.order_item_otf_'+data.otf_key)
            transaction_tr.find('.order_qty').val(data.quantity)
            transaction_tr.find('.order_qty').attr('ovalue',data.quantity)



            post_save_change_item_in_basket(data)




        } else if (data.state == 400) {
            alert('error')

        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}


