/*Author: Raul Perusquia <raul@inikoo.com>
 Created:   14 January 2020  15:04::34  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo
 Version 3.0*/


$(function () {

    var tab=$('#tab');

    tab.on('click', '#table .order_select_box', function () {


        if ($(this).hasClass('fa-square')) {
            $(this).removeClass('fa-square').addClass('fa-check-square')
        } else {
            $(this).addClass('fa-square').removeClass('fa-check-square')
        }

        order_select_box_changed()

    });


    tab.on('click', '.select_all_orders', function () {

        var operation;
        if ($(this).hasClass('fa-square')) {
            operation = 'select';
            $(this).removeClass('fa-square').addClass('fa-check-square')
        } else {
            operation = 'deselect';
            $(this).addClass('fa-square').removeClass('fa-check-square')

        }

        $('#table .order_select_box').each(function (i, obj) {

            if (operation == 'select') {
                $(obj).removeClass('fa-square').addClass('fa-check-square')
            } else {
                $(obj).addClass('fa-square').removeClass('fa-check-square')
            }


        });

        order_select_box_changed()

    });

    tab.on('click', '.orders_operation', function () {

        if($(this).hasClass('super_discreet')){
            return;
        }

        var icon =$(this).find('i');
        if(icon.hasClass('wait')){
            return;
        }
        $('.select_all_orders').addClass('invisible')
        icon.addClass('wait fa-spin fa-spinner')

        var order_keys=[];

        $('#table .order_select_box').each(function (i, obj) {

            if ($(obj).hasClass('fa-square')) {
                $(obj).addClass('invisible')

            } else {
                $(obj).addClass('wait fa-spin fa-spinner')
                order_keys.push( $(obj).data('order_key'))
            }


        });

        switch($(this).data('type')){
            case 'send_orders_to_warehouse':
                send_orders_to_warehouse(order_keys)

        }


    });

    tab.on('click', '.orders_pdf i', function () {

        if($(this).closest('div').find('.orders_pdf').hasClass('super_discreet')){
            return;
        }

        var icon =$(this)
        if(icon.hasClass('wait')){
            return;
        }

        var pdf_scope_keys=[];

        $('#table .order_select_box').each(function (i, obj) {

            if (!$(obj).hasClass('fa-square')) {

                $.each($(obj).data('pdf_scope_keys'), function(index, value) {
                    pdf_scope_keys.push(value )
                });


            }


        });


        switch($(this).data('type')){
            case 'picking_aid':
                window.open('/pdf/order_pick_aid.pdf.php?ids='+pdf_scope_keys.join(','), '_blank');
                break;
            case 'picking_aid_with_labels':
                window.open('/pdf/order_pick_aid.pdf.php?with_labels&ids='+pdf_scope_keys.join(','), '_blank');
                break;

        }
        icon.removeClass('wait fa-spin fa-spinner')






    });


});

function send_orders_to_warehouse(order_keys) {

    var form_data = new FormData();

    form_data.append("tipo", 'send_orders_to_warehouse')
    form_data.append("order_keys", JSON.stringify(order_keys))


    var request = $.ajax({

        url: "/ar_edit_orders.php",
        data: form_data,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json'

    })


    request.done(function (data) {


        if (data.state == 200) {

            $('.select_all_orders').removeClass('invisible wait fa-spin fa-spinner fa-check-square').addClass('fa-square')

            $('.orders_operations .orders_operation').addClass('super_discreet').removeClass('button').find('i').removeClass('invisible wait fa-spin fa-spinner');

            $('.send_orders_to_warehouse_msg').html(data.msg)

            rows.fetch({reset: true});


        } else if (data.state == 400) {
            alert('error')

        }

    })


    request.fail(function (jqXHR, textStatus) {
        console.log(textStatus)

        console.log(jqXHR.responseText)


    });




}

function order_select_box_changed() {

    var selected=0;
    var no_selected=0;

    $('#table .order_select_box').each(function (i, obj) {

        if ($(obj).hasClass('fa-square')) {
            no_selected++;
        } else {
            selected++;
        }


    });




    if(no_selected>0){
        $('.select_all_orders').addClass('fa-square').removeClass('fa-check-square')
    }else{
        $('.select_all_orders').removeClass('fa-square').addClass('fa-check-square')

    }

    if(selected>0) {
        $('.orders_operations .orders_op').removeClass('super_discreet');
        $('.orders_operations .orders_operation').addClass('button')
        $('.orders_operations .orders_pdf i').addClass('button')

    }else{
        $('.orders_operations .orders_op').addClass('super_discreet');
        $('.orders_operations .orders_operation').removeClass('button')
        $('.orders_operations .orders_pdf i').removeClass('button')


    }

}