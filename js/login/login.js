var on_my_Submit = function () {

    var input_pwd = $("#login__password");
    var blow_fish = $("#blow_fish");
    var the_form = $("#login_form");
    var pwd = sha256_digest(input_pwd.val());
    var epwd = Base64.encode(AESEncryptCtr(blow_fish.val(), pwd, 256));
    input_pwd.val('secret');
    $('#token').val(epwd);
    the_form.submit();


}

var submit_form_on_enter = function (e) {

    if (window.event) Key = window.event.keyCode; //IE
    else Key = e.which; //firefox
    if (Key == 13) {
        on_my_Submit();

    }
};


$(document).ready(function () {
    $("#login__username").focus();


    $("#login_form").submit(function (event) {

        console.log('caca')
    });

    $("#error_message").animate({
        opacity: 0,
    }, 5000, function () {
        $("#error_message").css('visibility', 'hidden')
    });

})
