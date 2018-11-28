/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 223 November 2018 at 14:25:58 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/


$('body').on('input propertychange', '.new_return_item', function (evt) {

    if($(this).val()!='' && validate_number($(this).val(),0,$(this).attr('max'))){
        $(this).addClass('error')
    }else{
        $(this).removeClass('error')
    }


    update_new_return_totals()


});



$('body').on('dblclick', 'span.new_return_ordered_quantity', function () {

    var input = $(this).closest('tr').find('.new_return_item')



    var max = parseFloat(input.attr('max'));



    change_item_return(max, input)
});

$('body').on('click', 'span.new_return_ordered_quantity', function () {

    var input = $(this).closest('tr').find('.new_return_item')

    var add_amount = 1
    var current_amount = input.val()
    if (current_amount == '') {
        current_amount = 0;
    }
    current_amount = parseFloat(current_amount)

    var to_add_amount = (current_amount + add_amount)

    var max = parseFloat(input.attr('max'));

    console.log(input.attr('max'))

    console.log(max)

    if (max < to_add_amount) {
        to_add_amount = max
    }


    change_item_return(to_add_amount, input)
});




function change_item_return(amount, input) {

    input.val(amount)

    if(validate_number(input.val(),0,input.attr('max'))){
        input.addClass('error')
    }else{
        input.removeClass('error')
    }


    update_new_return_totals()

}


function update_new_return_totals() {



    var number_affected_items=0;

    //var data = $('#order').data('object')



    $('.new_return_item').each(function (i, obj) {
        if ($(obj).val() != ''  && !$(obj).hasClass('hide')  && !$(obj).hasClass('error')  ) {

            if($(obj).hasClass('item')){
                number_affected_items++;
            }

        }

    });



    if(number_affected_items>0){
        $('.open_create_return_dialog_button').addClass('changed valid')
    }else{
        $('.open_create_return_dialog_button').removeClass('changed valid')

    }



    $('.affected_items').html(number_affected_items)






}

function save_return(){

    if (!$('.open_create_return_dialog_button').hasClass('valid')) {
        return;
    }

    $('.open_create_return_dialog_button').removeClass('valid').addClass('fa-spinner fa-spin')


    transactions = [];



    $('.new_return_item').each(function (i, obj) {

        if($(obj).val()!='' && !$(obj).hasClass('error') && !$(obj).hasClass('hided') ) {

            var transaction = {
                type: $(obj).attr('transaction_type'), id: $(obj).attr('transaction_id'), amount: $(obj).val()


            }

            transactions.push(transaction);
        }

    });

    var _data =$('#order').data('object')


    request='ar_edit_orders.php?tipo=create_return&key='+_data.key+'&transactions='+JSON.stringify(transactions);


    console.log(request)
    var ajaxData = new FormData();



    ajaxData.append("tipo", 'create_return')
    ajaxData.append("key", _data.key)
    ajaxData.append("transactions", JSON.stringify(transactions))


    $.ajax({
        url: "/ar_edit_orders.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {

                change_view('orders/'+data.store_key+'/'+data.order_key);

            } else if (data.state == '400') {
                $('.open_create_return_dialog_button').addClass('valid').removeClass('fa-spinner fa-spin')

                swal({
                    title: data.title, text: data.msg, confirmButtonText: "OK"
                });
            }



        }, error: function () {

        }
    });



}
