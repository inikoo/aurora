/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 23:30:22 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo
 Version 3.0*/



function place_order(element) {


    const button = $(element);

    if(button.hasClass('wait')){
        return;
    }

    button.addClass('wait');
    button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin');

    const settings=$(element).data('settings');

    let ajaxData = new FormData();

    ajaxData.append("tipo", settings.tipo);
    ajaxData.append("payment_account_key", settings.payment_account_key);
    ajaxData.append("order_key", settings.order_key);


    $.ajax({
        url: "/ar_web_checkout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
        complete: function () {
        }, success: function (data) {



            if (data.state == '200') {


                $('.ordered_products_number').html('0');
                $('.order_total').html('');


                var d = new Date();
                var timestamp=d.getTime();
                d.setTime(timestamp + 300000);
                var expires = "expires="+ d.toUTCString();
                document.cookie = "au_pu_"+ data.order_key+"=" + data.order_key + ";" + expires + ";path=/";
                window.location.replace("thanks.sys?order_key="+data.order_key+'&t='+timestamp);

            } else if (data.state == '400') {
                button.removeClass('wait');
                button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin');
                swal("Error!", data.msg, "error")
            }



        }, error: function () {

        }
    });

}


