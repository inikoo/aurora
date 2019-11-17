/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  16 November 2019  19:43::11  +0100, Mijas Costa, Spain
 Copyright (c) 2019, Inikoo
 Version 3.0*/

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


    console.log(element)

    element.removeClass('invalid')




    var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent(element.find('.item').val()) + '&scope=item' + '&metadata=' + atob(element.data("metadata")) + '&state=' + JSON.stringify(state)


    $.getJSON(request, function (data) {



        if (data.number_results > 0) {
            element.find('.search_results_container').removeClass('hide').addClass('show')
            element.find('.item').removeClass('invalid')

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

    })


}


function select_add_item_option(element) {

    let form=$(element).closest('.add_item_form')

    form.find('.item').val($(element).data('formatted_value'))
    form.find('.add_item_save').data('item_key', $(element).data('item_key')).data('item_historic_key', $(element).data('item_historic_key'))
    form.find('.search_results_container').addClass('hide').removeClass('show')

    form.find('.qty').focus()


    validate_add_item(form)


}


function show_add_item_form(element) {

    element.removeClass('hide')

    $('.table_button').addClass('hide')

    element.find('.add_item_save').data('item_key', '').data('item_historic_key', '').addClass('super_discreet').removeClass('invalid valid button')
    element.find('.add_item_save').data('item_historic_key', '')


    element.find('.item').val('').focus().removeClass('invalid')
    element.find('.qty').val('').removeClass('invalid')

}

function close_add_item(element) {

    let form=$(element).closest('.add_item_form')
    form.addClass('hide')
    $('#'+form.data('trigger')).removeClass('hide')
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


    let form=$(save_button).closest('.add_item_form')


    $(save_button).addClass('fa-spinner fa-spin');



    var table_metadata = $('#table').data("metadata")


    // var request = '/ar_edit_orders.php?tipo=edit_item_in_order&field=' + table_metadata.field + '&parent=' + table_metadata.parent + '&parent_key=' + table_metadata.parent_key + '&item_key=' + $('#add_item_save').data('item_key') + '&item_historic_key=' + $('#add_item_save').data('item_historic_key') + '&qty=' + $('#add_item_qty').val()


    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'edit_item_in_order')
    form_data.append("field", form.data('field'))
    form_data.append("parent", table_metadata.parent)
    form_data.append("parent_key", table_metadata.parent_key)
    form_data.append("item_key", $(save_button).data('item_key'))
    if($(save_button).data('item_historic_key')!=''){
        form_data.append("item_historic_key", $(save_button).data('item_historic_key'))

    }
    form_data.append("qty", form.find('.qty').val())

    var request = $.ajax({

        url: "/ar_edit_orders.php",
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
            form.find('.item').val('').focus().removeClass('invalid')
            form.find('.qty').val('').removeClass('invalid')
            $(save_button).addClass('super_discreet').removeClass('invalid valid button')


            post_modify_item_order(data)




        } else if (data.state == 400) {
            alert('error')

        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}

