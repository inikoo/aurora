/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 23:30:22 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/




$(function() {



    $("form").on('submit', function (e) {

        e.preventDefault();
        e.returnValue = false;

    });

    $('.reminder').click(function () {

        if ($(this).hasClass('lock')) return;

        $(this).addClass('lock')

        var icon = $(this)


        if (icon.hasClass('fa-envelope-o')) {

            icon.removeClass('fa-envelope-o').addClass('fa-envelope').addClass('marked').attr('title', '{t}Click to remove notification{/t}')


            var request = 'ar_reminders.php?tipo=send_reminder&pid=' + $(this).closest('.product_showcase').data('product_id')


        } else {


            icon.removeClass('fa-envelope').addClass('fa-envelope-o').removeClass('marked').attr('title', '{t}Click to be notified by email{/t}')
            var request = 'ar_reminders.php?tipo=cancel_send_reminder&esr_key=' + $(this).data('reminder_key')

        }

        element = $(this)

        console.log(request)
        $.getJSON(request, function (data) {
            console.log(data)

            if (data.state == 200) {
                element.removeClass('lock')
                element.data('reminder_key', data.id)

            }


        })


    });


    $('.favourite').click(function () {


        var element=$(this)

        var icon = $(this).find('i');


        if (icon.hasClass('fa-heart-o')) {
            icon.removeClass('fa-heart-o').addClass('fa-heart').addClass('marked')

        } else {

            icon.removeClass('fa-heart').addClass('fa-heart-o').removeClass('marked')


        }


        var request = 'ar_web_basket.php?tipo=update_favourite&pid=' + $(this).data('product_id') + '&customer_key=' + $('#webpage_data').data('customer_key') + '&favourite_key=' + $(this).data('favourite_key')

        console.log(request)
        $.getJSON(request, function (data) {

            console.log(data)

            element.data('favourite_key',data.favourite_key)

        })


    });



    $('#logout').on("click", function () {

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'logout')

        ajaxData.append("webpage_key", $('#webpage_data').data('webpage_key'))

        $.ajax({
            url: "/ar_web_logout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {

            }, success: function (data) {

                // console.log(data)

                if (data.state == '200') {

                    location.reload();

                } else if (data.state == '400') {
                    swal(data.msg);
                }


            }, error: function () {

            }
        });


    });





    $('#header_search_icon').on("click", function () {

        window.location.href = "search.sys?q=" + encodeURIComponent($('#header_search_input').val());


    });

    $('#search_icon').on("click", function () {

        search($('#search_input').val());


    });


    $("#header_search_input").on('keyup', function (e) {
        if (e.keyCode == 13) {
            window.location.href = "search.sys?q=" + encodeURIComponent($(this).val());
        }
    });

    $(document).on('keyup', '#search_input', function (e) {
        if (e.keyCode == 13) {
            search($(this).val())
        }
    });



});


function search(query){


    var request = "/ar_web_search.php?tipo=search&query=" +encodeURIComponent(query)
    console.log(request)

    $.getJSON(request, function (data) {

      $('#search_results').html(data.results)


    })

}



$(function() {

    $('.order_row .label').click(function () {

        var element = $(this);
        var order_row = $(this).closest('.order_row');
        if ($(this).find('i').hasClass('fa-spinner')) return;


        var input = order_row.find('.order_input')

        var order_qty = input.val()
        $(this).find('i').removeClass('fa-hand-pointer-o').addClass('fa-spinner fa-spin  ')
        input.prop('readonly', true);

        var order_key = $('#webpage_data').data('order_key');
        if (order_key == '') order_key = 0;

        if (order_qty > 0) {
            order_row.addClass('ordered').removeClass('empty')
        } else {
            //   order_row.removeClass('ordered').addClass('empty')

        }


        var request = 'ar_web_basket.php?tipo=update_item&product_id=' + $(this).closest('.product_container').data('product_id') + '&order_key=' + order_key + '&qty=' + order_qty + '&webpage_key=' + $('#webpage_data').data('webpage_key') + '&page_section_type=Family'

        console.log(request)
        $.getJSON(request, function (data) {


            if (data.state == 200) {



                for (var key in data.metadata.class_html) {
                    $('.' + key).html(data.metadata.class_html[key])
                }


                if (data.quantity > 0) {
                    element.html($('#ordering_settings').data('labels').ordered)
                    order_row.addClass('ordered').removeClass('empty')
                } else {
                    element.html($('#ordering_settings').data('labels').order)
                    order_row.removeClass('ordered').addClass('empty')
                }

                if (data.quantity == 0) data.quantity = ''

                input.val(data.quantity).data('ovalue', data.quantity).prop('readonly', false);

            } else if (data.state == 201) {

                window.location.href = 'waiting_payment_confirmation.php?referral_key=' + $('#webpage_data').data('webpage_key')


            }


        })


    });


})



$("#_special_instructions").on("input propertychange", function (evt) {

    console.log('xx')

    var delay = 100;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_save_special_instructions($(this),delay)
});



function delayed_save_special_instructions(object, timeout) {

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function () {

        save_special_instructions()
    }, timeout));
}


function save_special_instructions(){



    var ajaxData = new FormData();

    ajaxData.append("tipo", 'update_order')
    ajaxData.append("order_key", $('webpage_data').data('order_key'))
    ajaxData.append("field", 'Order Special Instructions')
    ajaxData.append("value",$('#_special_instructions').val())



    $.ajax({
        url: "/ar_web_basket.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {

            if (data.state == '200') {




            } else if (data.state == '400') {

            }



        }, error: function () {

        }
    });



}


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


    $(element).closest('div.row').find('.check_icon_saved_card').removeClass('fa-circle-o').addClass('fa-check-circle success')






    $('.cvv_for_saved_card').addClass('invisible')
    $(element).closest('div.row').removeClass('hide').find('.cvv_for_saved_card').removeClass('invisible')
}


function cancel_use_this_card(element){

    $(element).closest('div.row').find('.delete_this_credit_card').removeClass('hide')
    $(element).closest('div.row').find('.cancel_use_this_card').addClass('hide')

    $('.cvv_for_saved_card').addClass('invisible')

    $('.check_icon_saved_card').addClass('fa-circle-o').removeClass('fa-check-circle success')


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
