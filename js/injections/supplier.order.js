/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 June 2016 at 12:46:38 GMT+8, Legian, Bali, Indonesia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


$(document).on('input propertychange', '.order_qty', function(evt) {

    if ($(this).val() == $(this).attr('ovalue')) {
        $(this).closest('span').find('i').removeClass('fa-cloud exclamation-circle error').addClass('fa-plus')

    } else {

        if (!validate_signed_integer($(this).val(), 4294967295) || $(this).val() == '') {
            $(this).closest('span').find('i').removeClass('fa-plus exclamation-circle error').addClass('fa-cloud')
            $(this).addClass('discreet')
        } else {
            $(this).closest('span').find('i').removeClass('fa-plus fa-cloud').addClass('fa-exclamation-circle error')

        }
    }
});



function save_order_qty_change(element) {

    $(element).addClass('fa-spinner fa-spin');


    var input = $(element).closest('span').find('input.order_qty')

    if ($(element).hasClass('fa-plus')) {

        if (isNaN(input.val()) || input.val() == '') {
            var qty = 1
        } else {
            qty = parseFloat(input.val()) + 1
        }

        input.val(qty).addClass('discreet')

    } else {
        qty = parseFloat(input.val())

    }

    if (qty == '') qty = 0;


    var table_metadata = JSON.parse(atob($('#table').data("metadata")))

    var request = '/ar_edit.php?tipo=edit_item_in_order&parent=' + table_metadata.parent + '&parent_key=' + table_metadata.parent_key + '&item_key=' + $(element).closest('span').attr('item_key') + '&item_historic_key=' + $(element).closest('span').attr('item_historic_key') + '&qty=' + qty
    console.log(request)
    // return;
    //=====
    var form_data = new FormData();

    form_data.append("tipo", 'edit_item_in_order')
    form_data.append("parent", table_metadata.parent)
    form_data.append("parent_key", table_metadata.parent_key)
    form_data.append("item_key", $(element).closest('span').attr('item_key'))
    form_data.append("item_historic_key", $(element).closest('span').attr('item_historic_key'))
    form_data.append("qty", qty)

    var request = $.ajax({

        url: "/ar_edit.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    })


    request.done(function(data) {

        $(element).removeClass('fa-spinner fa-spin fa-cloud').addClass('fa-plus');


        if (data.state == 200) {

            input.val(data.transaction_data.qty).removeClass('discreet')

            //$('#subtotals_potf_' + data.transaction_data.transaction_key).html(data.transaction_data.subtotals)
            
             $(element).closest('tr').find('.subtotals').html(data.transaction_data.subtotals)
            
            console.log('#subtotals_potf_' + data.transaction_data.transaction_key)
            console.log(data.transaction_data.subtotals)

            for (var key in data.metadata.class_html) {
                console.log(key)
                $('.' + key).html(data.metadata.class_html[key])
            }



        } else if (data.state == 400) {
            alert('error')

        }

    })


    request.fail(function(jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });




}
