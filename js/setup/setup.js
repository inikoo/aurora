/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2016 at 23:15:29 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo
 Version 3.0*/

var onmySubmit = function() {

        
        var theform = document.getElementById("loginform");
      

        theform.submit();


    }

var submit_form_on_enter = function(e) {
        var key;
        if (window.event) Key = window.event.keyCode; //IE
        else Key = e.which; //firefox     
        if (Key == 13) {
            onmySubmit();

        }
    };


$(document).ready(function() {

    $("#login__password").focus();
   

    $("#error_message").animate({
        opacity: 0,
    }, 5000, function() {
        $("#error_message").css('visibility', 'hidden')
    });

})
