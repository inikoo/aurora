/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2016 at 23:15:29 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo
 Version 3.0*/


$(document).ready(function () {

    $("#login__password").focus();


    $("#error_message").animate({
        opacity: 0,
    }, 5000, function () {
        $("#error_message").css('visibility', 'hidden')
    });

})
