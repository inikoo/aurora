/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 February 2018 at 15:28:49 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2010, Inikoo
 Version 3.0*/








function save_email_campaign_operation(element) {

    var data = $(element).data("data")



    var object_data = JSON.parse(atob($('#object_showcase div.order').data("object")))

    var dialog_name = data.dialog_name
    var field = data.field
    var value = data.value
    var object = object_data.object
    var key = object_data.key


    if (!$('#' + dialog_name + '_save_buttons').hasClass('button')) {
        console.log('#' + dialog_name + '_save_buttons')
        return;
    }

    $('#' + dialog_name + '_save_buttons').removeClass('button');
    $('#' + dialog_name + '_save_buttons i').addClass('fa-spinner fa-spin')
    $('#' + dialog_name + '_save_buttons .label').addClass('hide')


    var metadata = {}

    //console.log('#' + dialog_name + '_dialog')

    $('#' + dialog_name + '_dialog  .option_input_field').each(function () {
        var settings = $(this).data("settings")



        if (settings.type == 'datetime') {
            metadata[settings.field] = $('#' + settings.id).val() + ' ' + $('#' + settings.id + '_time').val()

        }


    });



    var request = '/ar_edit.php?tipo=edit_field&object=' + object + '&key=' + key + '&field=' + field + '&value=' + value + '&metadata=' + JSON.stringify(metadata)



    console.log(request)
     // return;
    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'edit_field')
    form_data.append("object", object)
    form_data.append("key", key)
    form_data.append("field", field)
    form_data.append("value", value)
    form_data.append("metadata", JSON.stringify(metadata))

    var request = $.ajax({

        url: "/ar_edit.php", data: form_data, processData: false, contentType: false, type: 'POST', dataType: 'json'

    })


    request.done(function (data) {

        $('#' + dialog_name + '_save_buttons').addClass('button');
        $('#' + dialog_name + '_save_buttons i').removeClass('fa-spinner fa-spin')
        $('#' + dialog_name + '_save_buttons .label').removeClass('hide')


        if (data.state == 200) {

            close_dialog(dialog_name)




            if (data.value == 'Cancelled') {
                change_view(state.request, {
                    reload_showcase: true
                })
            }


            for (var key in data.update_metadata.class_html) {
                $('.' + key).html(data.update_metadata.class_html[key])
            }


            $('.order_operation').addClass('hide')
           // $('.items_operation').addClass('hide')




            for (var key in data.update_metadata.operations) {

                console.log('#' + data.update_metadata.operations[key])

                $('#' + data.update_metadata.operations[key]).removeClass('hide')
            }




            $('.timeline .li').removeClass('complete')






                if (data.update_metadata.state_index >= 30) {
                    $('#submitted_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 40) {
                    $('#in_warehouse_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 80) {
                    $('#packed_done_node').addClass('complete')
                }
                if (data.update_metadata.state_index >=90) {
                    $('#approved_node').addClass('complete')
                }
                if (data.update_metadata.state_index >= 100) {
                    $('#dispatched_node').addClass('complete')
                }











        } else if (data.state == 400) {


            swal($('#_labels').data('labels').error, data.msg, "error")
        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });


}




function picked_offline_items_qty_change(element) {


    var input = $(element).closest('span').find('input')
    var icon = $(element)

    if ($(element).hasClass('fa-plus')) {


        var _icon = 'fa-plus'

        if (isNaN(input.val()) || input.val() == '') {
            var qty = 1
        } else {
            qty = parseFloat(input.val()) + 1
        }






    } else if ($(element).hasClass('fa-minus')) {

        if (isNaN(input.val()) || input.val() == '' || input.val() == 0) {
            var qty = 0
        } else {
            qty = parseFloat(input.val()) - 1
        }


        var _icon = 'fa-minus'

    } else {
        qty = parseFloat(input.val())

        var _icon = 'fa-cloud'

    }

    if(qty>input.attr('max')){

        qty=input.attr('max')
    }


    input.val(qty).addClass('discreet')


    console.log(_icon)

    $(element).addClass(_icon)

    if (qty == '') qty = 0;


    var settings = $(element).closest('span').data('settings')


    var table_metadata = JSON.parse(atob($('#table').data("metadata")))








}
