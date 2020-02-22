/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  24 November 2019  19:15::16  +0100, Mijas Costa, Spain
 Copyright (c) 2017, Inikoo
 Version 3.0*/






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

                if(item_input.data('device')=='Desktop'){
                    search_results_container.removeClass('hide').addClass('show').offset({ top: item_input.offset().top-14, left: save_button.offset().left-search_results_container.width()   })

                }else if(item_input.data('device')=='Tablet'){
                    search_results_container.removeClass('hide').addClass('show').offset({ top: item_input.offset().top+30, left: save_button.offset().left-search_results_container.width()+20   })

                }else if(item_input.data('device')=='Mobile'){
                    search_results_container.removeClass('hide').addClass('show').offset({ top: item_input.offset().top+30, left: save_button.offset().left-search_results_container.width()+20   })

                }



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
        save_button.addClass('invalid').removeClass('super_discreet valid  changed')
    } else {
        save_button.removeClass('invalid')

        if (save_button.data('item_key') != '' && input_item.val() != '' && input_qty.val() != '') {
            save_button.addClass('valid  changed').removeClass('super_discreet')
        } else {
            save_button.removeClass('valid  changed').addClass('super_discreet')
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

    form_data.append("tipo", 'update_client_order_item')
    form_data.append("webpage_key", $('#webpage_data').data('webpage_key'))
    form_data.append("client_key", $('.client_basket').data('client_key'))

    form_data.append("order_key", $('.client_basket').data('order_key'))


    form_data.append("page_section_type", 'Add_Basket')

    form_data.append("product_id", $(save_button).data('item_key'))

    form_data.append("qty", form.find('.qty').val())

    var request = $.ajax({

        url: "/ar_web_update_client_order_item.php",
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
            $(save_button).addClass('super_discreet').removeClass('invalid valid ')


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


