/*Author: Raul Perusquia <raul@inikoo.com>
 Created:  20 December 2019  13:20::21  +0800, Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo
 Version 3.0*/



function change_dispatching_times_parent(parent) {
    $('.widget_types .widget').removeClass('selected')
    $('#store_' + parent).addClass('selected')
    get_dashboard_dispatching_times_data(parent)
}


function get_dashboard_dispatching_times_data(parent) {

    var request = "/ar_dashboard.php?tipo=dispatching_times&parent=" + parent
    $.getJSON(request, function (r) {
        $('#dispatching_times_parent').val(parent)

        for (var record in r.data) {
            $('.' + record).html(r.data[record].value)
            if(r.data[record].title!= undefined ) {
                $('.' + record).prop('title', r.data[record].title);
            }
        }
    });

}



function toggle_pending_orders_currency() {
    if ($('#pending_orders_currency_switch').hasClass('fa-toggle-off')) {
        var currency = 'store'
        $('#pending_orders_currency_switch').removeClass('fa-toggle-off').addClass('fa-toggle-on')
    } else {
        var currency = 'account'
        $('#pending_orders_currency_switch').addClass('fa-toggle-off').removeClass('fa-toggle-on')
    }
    $('#pending_orders_currency').val(currency)

    get_dashboard_pending_orders_data($('#pending_orders_parent').val(), $('#pending_orders_currency').val())

}


function change_pending_orders_parent(parent) {


    $('.widget_types .widget').removeClass('selected')
    $('#store_' + parent).addClass('selected')

    if (parent == '') {
        $('#pending_orders_currency_container').addClass('hide')

    } else {
        $('#pending_orders_currency_container').removeClass('hide')

    }

    get_dashboard_pending_orders_data(parent, $('#pending_orders_currency').val())
    get_dashboard_customers_data(parent, $('#pending_orders_currency').val())


}


function get_dashboard_pending_orders_data(parent,  currency) {

    var request = "/ar_dashboard.php?tipo=pending_orders&parent=" + parent + '&currency=' + currency
    console.log(request)
    $.getJSON(request, function (r) {


        $('#pending_orders_parent').val(parent)

        for (var record in r.data) {

            console.log(record)
            console.log(r.data[record].value)

            $('.' + record).html(r.data[record].value)

            if(r.data[record].title!= undefined ) {
                $('.' + record).prop('title', r.data[record].title);
            }




        }


    });

}

function go_to_orders(tag){

    var parent_key= $('#pending_orders_parent').val();
    if(parent_key==''){

        change_view('orders/all/dashboard/'+tag)


    }else{
        change_view('orders/'+parent_key+'/dashboard/'+tag)

    }


}

function go_to_pending_delivery_notes() {
    const parent =$('#dispatching_times_parent').val();
    if (parent> 0) {
        change_view('delivery_notes/'+parent,
            { 'parameters':{ elements_type:'dispatch' } ,element:{ dispatch:{ Ready:1,Picking:1,Packing:1,Done:1,Sent:'',Returned:''}}}
        )



    } else {
        change_view('delivery_notes/all',
            { 'parameters':{ elements_type:'dispatch' } ,element:{ dispatch:{ Ready:1,Picking:1,Packing:1,Done:1,Sent:'',Returned:''}}}
        )
    }
}