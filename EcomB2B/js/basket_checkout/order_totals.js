/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 November 2018 at 21:46:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo
 Version 3.0*/


 function show_charges_info(){

        var request = '/ar_web_basket.php?tipo=get_charges_info'
        $.getJSON(request, function (data) {

            if (data.state == 200) {
                swal({
                    html:true,
                    title: '',
                    text:data.text,
                })
            }
        })
    }


function show_client_charges_info(order_key){

    var request = '/ar_web_client_basket.php?tipo=get_charges_info&order_key='+order_key
    $.getJSON(request, function (data) {

        if (data.state == 200) {
            swal({
                html:true,
                title: '',
                text:data.text,
            })
        }
    })
}
