/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 April 2018 at 23:57:43 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/




$(function() {



    $(document).on('click', '.reminder', function (e) {


        console.log('hello')

        if ($(this).hasClass('lock')) return;

        $(this).addClass('lock')

        var icon =  $(this)


        if (icon.hasClass('far')) {

            icon.removeClass('far').addClass('fas').attr('title',icon.data('label_remove_notification'))


            var request = 'ar_web_out_of_stock_reminders.php?tipo=add_out_of_stock_reminder&pid=' + icon.data('product_id')


        } else {


            icon.removeClass('fas').addClass('far').attr('title',icon.data('label_add_notification'))
            var request = 'ar_web_out_of_stock_reminders.php?tipo=remove_out_of_stock_reminder&out_of_stock_reminder_key=' +icon.data('out_of_stock_reminder_key')

        }




        $.getJSON(request, function (data) {

            if (data.state == 200) {
                icon.removeClass('lock')
                icon.data('out_of_stock_reminder_key', data.out_of_stock_reminder_key)

            }


        })


    });

        $(document).on('click', '.favourite', function (e) {



        var icon = $(this)




        if (icon.hasClass('far')) {
            console.log('add')

            icon.removeClass('far').addClass('fas').addClass('marked')

        } else {
            console.log('off')
            console.log(icon)
            icon.removeClass('fa fas').addClass('far').removeClass('marked')



        }




        var request = 'ar_web_favourites.php?tipo=update_favourite&pid=' + $(this).data('product_id') +'&favourite_key=' + $(this).data('favourite_key')

        console.log(request)
        $.getJSON(request, function (data) {

            console.log(data)

            icon.data('favourite_key',data.favourite_key)

        })


    });


    $('.logout').on("click", function () {

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'logout')

        ajaxData.append("webpage_key", $('#webpage_data').data('webpage_key'))

        $.ajax({
            url: "/ar_web_logout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,


            complete: function () {

            }, success: function (data) {

                // console.log(data)

                if (data.state == '200') {

                    ga('auTracker.send', 'event', 'Login', 'logout');
                    location.reload();

                } else if (data.state == '400') {
                    swal(data.msg);
                }


            }, error: function () {

            }
        });


    });




});
