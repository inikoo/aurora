/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 23:30:22 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/




$(function() {


    $(document).on('click', '.reminder', function (e) {

   

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
           // console.log('add')

           icon.removeClass('far').addClass('fas').addClass('marked')

        } else {
            //console.log('off')
            //console.log(icon)
            icon.removeClass('fa fas').addClass('far').removeClass('marked')



        }




        var request = 'ar_web_favourites.php?tipo=update_favourite&pid=' + $(this).data('product_id') +'&favourite_key=' + $(this).data('favourite_key')

        //console.log(request)
        $.getJSON(request, function (data) {

           // console.log(data)

            if(data.favourite_key){
                ga('auTracker.send', 'event', 'Favourites', 'add', icon.data('product_code'));

            }else{
                ga('auTracker.send', 'event', 'Favourites', 'remove', icon.data('product_code'));

            }


            icon.data('favourite_key',data.favourite_key)

        })


    });



    $(".label_when_log_out").each(function (index) {


        var len_fit = 10;
        var un = $(this)


        var len_user_name = un.html().length;
        if (len_fit < len_user_name) {

            var size_now = parseInt(un.css("font-size"));
            var size_new = size_now * len_fit / len_user_name;
            un.css("font-size", size_new);

        }

    });

    $(".order_button_text").each(function (index) {


        var len_fit = 9;
        var un = $(this)


        var len_user_name = un.html().length;
        if (len_fit < len_user_name) {

            var size_now = parseInt(un.css("font-size"));
            var size_new = size_now * len_fit / len_user_name;


            un.css("font-size", size_new);

        }

    });


    $(".item_name").each(function (index) {


        var len_fit = 50; // According to your question, 10 letters can fit in.
        var un = $(this)

        // Get the lenght of user name.
        var len_user_name = un.html().length;
        if (len_fit < len_user_name) {

            // Calculate the new font size.
            var size_now = parseInt(un.css("font-size"));
            var size_new = size_now * len_fit / len_user_name;

            // Set the new font size to the user name.
            un.css("font-size", size_new);

        }

    });



    function validate_integer(value, min_value,max_value) {

        if (!$.isNumeric(value)) {
            return {
                class: 'invalid', type: 'not_integer'
            }
        }

        if (value > max_value) {
            return {
                class: 'invalid',

                type: 'too_big'
            }
        }

        if (value < min_value) {
            return {
                class: 'invalid',

                type: 'too_small'
            }
        }
        if (Math.floor(value) != value) {


            return {
                class: 'invalid',

                type: 'not_integer'
            }
        }

        return false
    }




});
