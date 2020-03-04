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

        var icon =$(this).find('i');
        if(icon.hasClass('wait')){
            return;
        }
        $('.select_all_orders').addClass('invisible')
        icon.addClass('wait fa-spin fa-spinner')

        switch($(this).data('type')){
            case 'send_orders_to_warehouse':
                send_orders_to_warehouse()

        }


    });


});

function send_orders_to_warehouse() {


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
        $('.orders_operations').removeClass('super_discreet');
        $('.orders_operations .orders_operation').addClass('button')
    }else{
        $('.orders_operations').addClass('super_discreet');
        $('.orders_operations .orders_operation').removeClass('button')

    }

}