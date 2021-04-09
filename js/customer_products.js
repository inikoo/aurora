/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  09 April 2021  17:08::25  +0800. Kuala Lumpur, , Malaysia
 Copyright (c) 2020, Inikoo
 Version 3.0*/


$(function () {

   

    $(document).on('input propertychange', "#add_product_to_customer_form",function () {


        var delay = 100;
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_add_product_to_customer_field($(this), delay)

    });


})



function delayed_on_change_add_product_to_customer_field(object, timeout) {

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function () {
        get_items_add_to_customer_select()
    }, timeout));
}

function get_items_add_to_customer_select() {


    $('#add_product_to_customer_form').removeClass('invalid');



    var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent($('#add_product_to_customer_input').val()) + '&scope=item' + '&metadata=' + atob($('#add_product_to_customer_form').data("metadata")) + '&state=' + JSON.stringify(state);
    console.log(request);
    $.getJSON(request, function (data) {



        const offset=$('#add_product_to_customer_form').offset().left+$('#add_product_to_customer_form').width();
        if (data.number_results > 0) {
            $('#add_product_to_customer_results_container').removeClass('hide').addClass('show').offset({
                'left':offset-$('#add_product_to_customer_results_container').width()
            });





            $('#add_product_to_customer_input').removeClass('invalid')

        } else {


            $('#add_product_to_customer_results_container').addClass('hide').removeClass('show');

            //console.log(data)
            if ($('#add_product_to_customer_input').val() != '') {
                $('#add_product_to_customer_input').addClass('invalid')
            } else {
                $('#add_product_to_customer_input').removeClass('invalid')
            }

            $('#save_add_product_to_customer').data('item_key', '')


        }


        $("#add_product_to_customer_results .result").remove();

        var first = true;

        for (var result_key in data.results) {



            var clone = $("#add_product_to_customer_search_result_template").clone();
            clone.prop('id', 'add_product_to_customer_result_' + result_key);
            clone.addClass('result').removeClass('hide');


            clone.data('item_key', data.results[result_key].value);

            clone.data('formatted_value', data.results[result_key].formatted_value);
            if (first) {
                clone.addClass('selected');
                first = false
            }

            clone.children(".label").html(data.results[result_key].description);
            clone.children(".code").html(data.results[result_key].code);

            $("#add_product_to_customer_results").append(clone)


        }


        $('#save_add_product_to_customer').data('item_key', '');
        $('#add_product_to_customer_save').addClass('super_discreet').removeClass('invalid valid button')



    })


}

function select_add_product_to_customer_option(element) {


    $('#add_product_to_customer_input').val($(element).data('formatted_value'));
    $('#add_product_to_customer_save').data('item_key', $(element).data('item_key'));



    $('#add_product_to_customer_results_container').addClass('hide').removeClass('show');


    $('#add_product_to_customer_save').addClass('valid button changed').removeClass('super_discreet')



}

function show_add_product_to_customer() {

    $('#add_product_to_customer_msg').html('').removeClass('error success');
    $('#add_product_to_customer_form').removeClass('hide');
    $('.table_button').addClass('hide');

    $('#save_add_product_to_customer').data('item_key', '');
    $('#add_product_to_customer_input').val('').focus().removeClass('invalid');
    $('#add_product_to_customer_save').addClass('super_discreet').removeClass('invalid valid button')

}

function close_add_product_to_customer() {
    $('#add_product_to_customer_form').addClass('hide');
    $('.table_button').removeClass('hide')
}


function save_add_product_to_customer() {

    if(!$('#add_product_to_customer_save').hasClass('valid')){
        return
    }


    $('#add_product_to_customer_save').addClass('fa-spinner fa-spin');



    var table_metadata = $('#table').data("metadata");






    var form_data = new FormData();

    form_data.append("tipo", 'add_product_to_customer');

    form_data.append("customer_key", table_metadata.parent_key);
    form_data.append("product_id", $('#add_product_to_customer_save').data('item_key'));


    var request = $.ajax({

        url: $('#add_product_to_customer_form').data("ar_url"),
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    });


    request.done(function (data) {

        $('#add_product_to_customer_save').removeClass('fa-spinner fa-spin');

        if (data.state == 200) {

            $('#save_add_product_to_customer').data('item_key', '');
            $('#add_product_to_customer_input').val('').focus().removeClass('invalid');
            $('#add_product_to_customer_save').addClass('super_discreet').removeClass('invalid valid button');

            rows.fetch({
                reset: true
            });

            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }

            for (var key in data.update_metadata.hide) {
                $('#' + data.update_metadata.hide[key]).addClass('hide')
            }
            for (var key in data.update_metadata.show) {
                $('#' + data.update_metadata.show[key]).removeClass('hide')
            }





        } else if (data.state == 400) {
            Swal.fire({
                type: 'error', title: data.msg
            })

        }

    });


    request.fail(function (jqXHR, textStatus) {



    });


}


function remove_product_from_customer(element,product_id) {

    $(element).removeClass('fa-trash-alt').addClass('fa-spinner fa-spin');

    var form_data = new FormData();

    form_data.append("tipo", 'remove_product_from_customer');
    form_data.append("product_id", product_id);


    var request = $.ajax({

        url: '/ar_edit_customers.php',
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    });


    request.done(function (data) {



        if (data.state == 200) {
            $(element).closest('tr').remove();
            for (var key in data.update_metadata.class_html) {
                console.log(key);
                $('.' + key).html(data.update_metadata.class_html[key])
            }

            for (var key in data.update_metadata.hide) {
                $('#' + data.update_metadata.hide[key]).addClass('hide')
            }
            for (var key in data.update_metadata.show) {
                $('#' + data.update_metadata.show[key]).removeClass('hide')
            }






        } else if (data.state == 400) {
            $(element).removeClass('fa-spinner fa-spin').addClass('fa-trash-alt');


            Swal.fire({
                type: 'error', title: data.msg
            })

        }

    });


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus);

        console.log(jqXHR.responseText)


    });


}