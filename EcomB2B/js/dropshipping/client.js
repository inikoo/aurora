/*Author: Raul Perusquia <raul@inikoo.com>
 Created:   01 March 2020  16:40::28  +0800 Kuala Lumpur Malaysia
 Copyright (c) 2020, Inikoo
 Version 3.0*/

"use strict";
$(function () {

    $(document).on('click', ' .delete_customer_client', function () {


        const icon=   $(this).find('i');
        if(icon.hasClass('wait')){
            return ;
        }

        $(this).find('i').addClass('fa-spin fa-spinner wait').removeClass('fa-user-slash')


        const ajaxData = new FormData();

        ajaxData.append("tipo", 'delete_client');
        ajaxData.append("client_key", $('.client').data('client_key'));

        $.ajax({
            url: 'ar_web_client.php', type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,

            complete: function () {

            }, success: function (data) {

                if(data.state==200){
                    window.location.replace("/clients.sys");

                }



            }, error: function () {

            }
        });



    });
});