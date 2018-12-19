/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2017 at 14:47:35 GMT+8, Legian, Bali, Indonesia
 Copyright (c) 2017, Inikoo
 Version 3.0*/


$('body').on('input propertychange', '.new_refund_item', function (evt) {

    if($(this).val()!='' && validate_number($(this).val(),0,$(this).attr('max'))){
        $(this).addClass('error')
    }else{
        $(this).removeClass('error')
    }


    update_new_refund_totals()


});


$('body').on('click', 'span.new_refund_ordered_quantity', function () {

    var input = $(this).closest('tr').find('.new_refund_item')

    var add_amount = parseFloat($(this).attr('unit_amount'))
    var current_amount = input.val()
    if (current_amount == '') {
        current_amount = 0;
    }
    current_amount = parseFloat(current_amount)

    var to_add_amount = (current_amount + add_amount)

    var max = parseFloat(input.attr('max'));


    if (max < to_add_amount) {
        to_add_amount = max
    }


    change_item_refund(to_add_amount.toFixed(2), input)
});

$('body').on('click', 'span.new_refund_order_item_net', function () {

    var input = $(this).closest('tr').find('.new_refund_item')

    var to_add_amount = parseFloat($(this).attr('amount'))


    var max = parseFloat(input.attr('max'));


    if (max < to_add_amount) {
        to_add_amount = max
    }


    change_item_refund(to_add_amount.toFixed(2), input)
});


function change_item_refund(amount, input) {

    input.val(amount)

    if(validate_number(input.val(),0,input.attr('max'))){
        input.addClass('error')
    }else{
        input.removeClass('error')
    }


    update_new_refund_totals()

}


function update_new_refund_totals() {

    var number_affected_items=0;

    var data = $('#order').data('object')

    symbol = data.symbol
    tax_rate = parseFloat(data.tax_rate)
    available_to_refund= parseFloat(data.available_to_refund)



    var net = 0.00;

    $('.new_refund_item').each(function (i, obj) {
        if ($(obj).val() != ''  && !$(obj).hasClass('hide')  && !$(obj).hasClass('error')  ) {
            net = net + parseFloat($(obj).val());
            if($(obj).hasClass('item'))
            number_affected_items++;
        }

    });

    net = parseFloat(net);
    tax = parseFloat(net * tax_rate);
    total = tax + net;

    if(total>0){
        $('.open_create_refund_dialog_button').addClass('changed valid')
    }else{
        $('.open_create_refund_dialog_button').removeClass('changed valid')

    }


    $('.Refund_Net_Amount').html(symbol + net.toFixed(2))
    $('.Refund_Tax_Amount').html(symbol + tax.toFixed(2))
    $('.Refund_Total_Amount').html(symbol + total.toFixed(2))

    $('.affected_items').html(number_affected_items)

    $('.percentage_refunded').html((100*(total/available_to_refund)).toFixed(2))





}

function create_refund(){

    if (!$('.open_create_refund_dialog_button').hasClass('valid')) {
        return;
    }

    $('.open_create_refund_dialog_button').removeClass('valid').addClass('fa-spinner fa-spin')


    transactions = [];



    $('.new_refund_item').each(function (i, obj) {

        if($(obj).val()!='' && !$(obj).hasClass('error') && !$(obj).hasClass('hided') ) {

            var transaction = {
                type: $(obj).attr('transaction_type'), id: $(obj).attr('transaction_id'), amount: $(obj).val()


            }

            transactions.push(transaction);
        }

    });

    var _data =$('#order').data('object')


    request='ar_edit_orders.php?tipo=create_refund&key='+_data.key+'&transactions='+JSON.stringify(transactions);


    console.log(request)
    var ajaxData = new FormData();



    ajaxData.append("tipo", 'create_refund')
    ajaxData.append("key", _data.key)
    ajaxData.append("transactions", JSON.stringify(transactions))


    $.ajax({
        url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                change_view('orders/'+data.store_key+'/'+data.order_key+'/invoice/'+data.refund_key);

            } else if (data.state == '400') {
                $('.open_create_refund_dialog_button').addClass('valid').removeClass('fa-spinner fa-spin')

                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }



        }, error: function () {

        }
    });



}
