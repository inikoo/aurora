var on_my_Submit = function () {

    var input_login = $("#login__username");
    var input_pwd = $("#login__password");
    var blow_fish = $("#blow_fish");
    var the_form = $("#login_form");



    var pwd = sha256_digest(input_pwd.val());

    console.log(blow_fish.val())
    console.log(pwd)
    var epwd = btoa(AESEncryptCtr(blow_fish.val(), pwd, 256));


    input_pwd.val('secret');
    $('#token').val(epwd);





    the_form.submit();


}

var submit_form_on_enter = function (e) {
    var key;
    if (window.event) Key = window.event.keyCode; //IE
    else Key = e.which; //firefox
    if (Key == 13) {
        on_my_Submit();

    }
};


$(document).ready(function () {
//console.log('caca')
    $("#login__username").focus();


    $("#login_form").submit(function (event) {
       // var pwd = sha256_digest($('#login__password').val());
       // var epwd = AESEncryptCtr($('#ep').val(), pwd, 256);
       // $('#ep').val(btoa(epwd))
       // $('#login__password').val('secret')
        console.log('caca')
    });

    $("#error_message").animate({
        opacity: 0,
    }, 5000, function () {
        $("#error_message").css('visibility', 'hidden')
    });

})
