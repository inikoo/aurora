/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 3:27 pm Thursday, 20 February 2020 (MYT) Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo
 Version 3.0*/


$(function () {



    $(document).on('click', '.open_notifications', function () {

        const request_data ={ "tipo":'notifications_control_panel',"device_prefix" :'' };
        $.ajax({

            url: '/ar_web_notifications.php', type: 'GET', dataType: 'json', data: request_data, success: function (data) {
                if (data.state == 200) {
                    $('.portfolio_sub_block').addClass('hide');
                    $('.notifications_sub_block').removeClass('hide');
                    $('.notifications_control_panel').html(data.html);
                }

            }
        });



    });



    $(document).on('click', '.notifications_control_panel .subscribe', function () {

        const container=$(this).closest('tr')
        $(this).addClass('hide')

        const end_point=container.find('.add_subscription_endpoint')
        end_point.removeClass('hide')
        validate_endpoint(end_point)


    });

    $(document).on('click', '.notifications_control_panel .save', function () {

        let save=$(this)
        if(!save.hasClass('valid') || save.hasClass('wait')){
            return;
        }

        save.addClass('fa-spin fa-spinner wait')




        const container=$(this).closest('table')

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'subscribe');
        ajaxData.append("channel", container.data('channel'));
        ajaxData.append("protocol", container.data('protocol'));
        ajaxData.append("endpoint", container.find('input').val());
        $.ajax({
            url: 'ar_web_notifications.php', type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

            complete: function () {

            }, success: function (data) {
                save.removeClass('fa-spin fa-spinner wait')




            }, error: function () {
                save.removeClass('fa-spin fa-spinner wait')
            }
        });


    });

});

function validate_endpoint(end_point){
    let input=$(end_point).find('input')
    let save_button=$(end_point).find('.save')
    console.log(save_button)
    switch(input.attr('type')){
        case 'email':
            let email=input.val();
            console.log(email)
            if(email===''){
                save_button.removeClass('valid changed invalid')
            }else{
                let  regex=
                    /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/;

                if(regex.test(email))   {
                    save_button.addClass('valid changed').removeClass('invalid')
                }else{
                    save_button.removeClass('valid changed').addClass('invalid')

                }
            }



            break;
    }
}

$(document).on('input propertychange', "#notifications_control_panel input.endpoint",function () {


    var delay = 100;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_on_change_endpoint_input($(this), delay)

});


function delayed_on_change_endpoint_input(object, timeout) {

    window.clearTimeout(object.data("timeout"));

    object.data("timeout", setTimeout(function () {

        validate_endpoint(object.closest('tr'))
    }, timeout));
}

