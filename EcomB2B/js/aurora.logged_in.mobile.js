/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 April 2018 at 23:57:43 GMT+8, Kuala Lumpur, Malaysia
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




});
