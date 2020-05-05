/*Author: Raul Perusquia <raul@inikoo.com>
 Created:   05 May 2020  21:35::44  +0800. Kuala Lumpur, , Malaysia
 Copyright (c) 2020, Inikoo
 Version 3.0*/


function go_to_sales_report() {


    switch ($('.go_to_sales_report').data('type')) {
        case 'invoices':
            change_view('report/sales&tab=sales', $('.go_to_sales_report').data('metadata'));
            break;
        case 'invoice_categories':
            change_view('report/sales&tab=sales_invoice_category', $('.go_to_sales_report').data('metadata'));
            break;

    }


}

function change_sales_overview_type(type) {


    $('.widget_types .widget').removeClass('selected')
    $('#' + type).addClass('selected')

    $('#sales_overview_orders_view_type_container').addClass('hide')


    if (type == 'invoices') {

        $('.date_chooser').removeClass('invisible')

        $('.category').addClass('hide')
        $('.store').removeClass('hide')

        $('.refunds,.invoices,.sales').removeClass('hide')
        $('#sales_overview_currency_container').removeClass('hide')
        $('.replacements ,.delivery_notes,.orders ,.orders_amount').addClass('hide')


        $('.go_to_sales_report').removeClass('invisible')


    } else if (type == 'invoice_categories') {
        $('.date_chooser').removeClass('invisible')

        $('.category').removeClass('hide')
        $('.store').addClass('hide')

        $('.refunds,.invoices,.sales').removeClass('hide')
        $('#sales_overview_currency_container').removeClass('hide')
        $('.replacements ,.delivery_notes,.orders ,.orders_amount').addClass('hide')
        $('#sales_overview_currency_container').removeClass('hide')
        $('.go_to_sales_report').removeClass('invisible')

    } else if (type == 'delivery_notes') {
        $('.date_chooser').removeClass('invisible')

        $('.category').addClass('hide')
        $('.store').removeClass('hide')
        $('.refunds,.invoices,.sales,.orders ,.orders_amount').addClass('hide')
        $('.replacements ,.delivery_notes').removeClass('hide')
        $('#sales_overview_currency_container').addClass('hide')
        $('.go_to_sales_report').addClass('invisible')

    } else if (type == 'orders') {
        $('.date_chooser').addClass('invisible')

        $('.category').addClass('hide')
        $('.store').removeClass('hide')

        $('.refunds,.invoices,.sales').addClass('hide')
        $('.replacements ,.delivery_notes,.replacements ,.delivery_notes,.orders ,.orders_amount,#sales_overview_currency_container').addClass('hide')

        if ($('#order_overview_orders_view_type').val() == 'numbers') {
            $('.orders ').removeClass('hide')
        } else {
            $('.orders_amount,#sales_overview_currency_container').removeClass('hide')

        }


        $('#sales_overview_orders_view_type_container').removeClass('hide')
        // $('#sales_overview_currency_container').removeClass('hide')
        $('.go_to_sales_report').addClass('invisible')

    }

    console.log('caca')
    get_order_overview_data(type, $('#order_overview_period').val(), $('#order_overview_currency').val(), $('#order_overview_orders_view_type').val())


    $('.go_to_sales_report').data('type', type)


}

function toggle_sales_overview_orders_view_type() {


    if ($('#sales_overview_orders_view_type_container .fa-hashtag').hasClass('selected')) {
        var orders_view_type = 'amounts'
        $('#sales_overview_orders_view_type_container .fa-hashtag').removeClass('selected')
        $('#sales_overview_orders_view_type_container .fa-dollar-sign').addClass('selected')
    } else {
        var orders_view_type = 'numbers'
        $('#sales_overview_orders_view_type_container .fa-hashtag').addClass('selected')
        $('#sales_overview_orders_view_type_container .fa-dollar-sign').removeClass('selected')
    }
    $('#order_overview_orders_view_type').val(orders_view_type)

    $('.orders ,.orders_amount,#sales_overview_currency_container').addClass('hide')
    if ($('#order_overview_orders_view_type').val() == 'numbers') {
        $('.orders ').removeClass('hide')
    } else {
        $('.orders_amount').removeClass('hide')
        $('#sales_overview_currency_container ').removeClass('hide')

    }

    get_order_overview_data($('#order_overview_type').val(), $('#order_overview_period').val(), $('#order_overview_currency').val(), $('#order_overview_orders_view_type').val())

}

function toggle_sales_overview_currency() {
    if ($('#sales_overview_currency').hasClass('fa-toggle-off')) {
        var currency = 'store'
        $('#sales_overview_currency').removeClass('fa-toggle-off').addClass('fa-toggle-on')
    } else {
        var currency = 'account'
        $('#sales_overview_currency').addClass('fa-toggle-off').removeClass('fa-toggle-on')
    }
    $('#order_overview_currency').val(currency)

    get_order_overview_data($('#order_overview_type').val(), $('#order_overview_period').val(), $('#order_overview_currency').val(), $('#order_overview_orders_view_type').val())

    var metadata = $('.go_to_sales_report').data('metadata')
    metadata.parameters.currency = currency;

    $('.go_to_sales_report').data('metadata', metadata)


}

function change_sales_overview_period(period) {

    $('.date_chooser .fixed_interval').removeClass('selected')
    $('#' + period).addClass('selected')

    $('#order_overview_period').val(period)


    get_order_overview_data($('#order_overview_type').val(), period, $('#order_overview_currency').val())

    console.log($('#order_overview_type').val() + ' ' + period)


    var metadata = $('.go_to_sales_report').data('metadata')
    metadata.parameters.period = period;

    $('.go_to_sales_report').data('metadata', metadata)


}

function get_order_overview_data(type, period, currency, orders_view_type) {

    var request = "/ar_dashboard.php?tipo=sales_overview&type=" + type + "&subtype=sales&period=" + period + '&currency=' + currency + '&orders_view_type=' + orders_view_type
    console.log(request)
    $.getJSON(request, function (r) {


        $('#order_overview_type').val(type)

        for (var record in r.data) {

            $('#' + record).html(r.data[record].value)

            if (r.data[record].request != undefined) {

                if (r.data[record].special_type != undefined) {
                    if (r.data[record].special_type == 'invoice') {
                        $('#' + record).attr("onclick", "change_view('" + r.data[record].request + "',{ parameters:{ period:'" + period + "',elements_type:'type' } ,element:{ type:{ Refund:'',Invoice:1}} })")
                    } else {
                        $('#' + record).attr("onclick", "change_view('" + r.data[record].request + "',{ parameters:{ period:'" + period + "',elements_type:'type' } ,element:{ type:{ Refund:1,Invoice:''}} })")
                    }
                } else {
                    $('#' + record).attr("onclick", "change_view('" + r.data[record].request + "',{ parameters:{ period:'" + period + "' }})")
                }
            }
            if (r.data[record].title != undefined) {
                $('#' + record).attr('title', r.data[record].title)

            }

        }


    });

}

