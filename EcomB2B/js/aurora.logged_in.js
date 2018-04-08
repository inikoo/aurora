/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 23:30:22 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/




$(function() {



   
    $('.reminder').click(function () {

        if ($(this).hasClass('lock')) return;

        $(this).addClass('lock')

        var icon = $(this)


        if (icon.hasClass('fa-envelope')) {

            icon.removeClass('fa-envelope').addClass('fa-envelope').addClass('marked').attr('title', '{t}Click to remove notification{/t}')


            var request = 'ar_reminders.php?tipo=send_reminder&pid=' + $(this).closest('.product_showcase').data('product_id')


        } else {


            icon.removeClass('fa-envelope').addClass('fa-envelope').removeClass('marked').attr('title', '{t}Click to be notified by email{/t}')
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



        var icon = $(this)


        console.log(icon)


        console.log(icon)

        if (icon.hasClass('far')) {
            console.log('add')

           icon.removeClass('far').addClass('fa').addClass('marked')

        } else {
            console.log('off')
            icon.removeClass('fa').addClass('far').removeClass('marked')



        }




        var request = 'ar_web_basket.php?tipo=update_favourite&pid=' + $(this).data('product_id') + '&customer_key=' + $('#webpage_data').data('customer_key') + '&favourite_key=' + $(this).data('favourite_key')

        console.log(request)
        $.getJSON(request, function (data) {

            console.log(data)

            icon.data('favourite_key',data.favourite_key)

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



 $('.order_row .label').click(function () {

        var element = $(this);
        var order_row = $(this).closest('.order_row');
        if ($(this).find('i').hasClass('fa-spinner')) return;


        var input = order_row.find('.order_input')

        var order_qty = input.val()
        $(this).find('i').removeClass('fa-hand-pointer').addClass('fa-spinner fa-spin  ')
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



});
