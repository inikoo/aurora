/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 23:30:22 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/






function use_other_credit_card(){

    $('.credit_cards_list').addClass('hide')
    $('.credit_card_form').removeClass('hide')
    $('.show_saved_cards_list').removeClass('hide')

}

function show_saved_cards(){

    $('.credit_cards_list').removeClass('hide')
    $('.credit_card_form').addClass('hide')
    $('.show_saved_cards_list').addClass('hide')
}

function use_this_credit_card(element){
    console.log(element)


    $(element).closest('fieldset').find('.row').addClass('hide')



    $(element).closest('div.row').find('.delete_this_credit_card').addClass('hide')
    $(element).closest('div.row').find('.cancel_use_this_card').removeClass('hide')


    $(element).closest('div.row').find('.check_icon_saved_card').removeClass('fa-circle').addClass('fa-check-circle success')






    $('.cvv_for_saved_card').addClass('invisible')
    $(element).closest('div.row').removeClass('hide').find('.cvv_for_saved_card').removeClass('invisible')
}


function cancel_use_this_card(element){

    $(element).closest('div.row').find('.delete_this_credit_card').removeClass('hide')
    $(element).closest('div.row').find('.cancel_use_this_card').addClass('hide')

    $('.cvv_for_saved_card').addClass('invisible')

    $('.check_icon_saved_card').addClass('fa-circle').removeClass('fa-check-circle success')


    $(element).closest('fieldset').find('.row').removeClass('hide')
}


function place_order(element) {


    var button=$(element);

    if(button.hasClass('wait')){
        return;
    }

    button.addClass('wait')
    button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')



    var settings=$(element).data('settings')

    var ajaxData = new FormData();

    ajaxData.append("tipo", settings.tipo)
    ajaxData.append("payment_account_key", settings.payment_account_key)
    ajaxData.append("order_key", settings.order_key)


    $.ajax({
        url: "/ar_web_checkout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {



            if (data.state == '200') {


                $('.ordered_products_number').html('0')
                $('.order_total').html('')

                window.location.replace("thanks.sys?order_key="+data.order_key);

            } else if (data.state == '400') {
                button.removeClass('wait')
                button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
                swal("Error!", data.msg, "error")
            }



        }, error: function () {

        }
    });

}
