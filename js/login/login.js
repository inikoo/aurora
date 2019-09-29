var on_my_Submit = function () {

    var input_pwd = $("#login__password");
    var blow_fish = $("#blow_fish");
    var pwd = sha256_digest(input_pwd.val());
    var epwd = Base64.encode(AESEncryptCtr(blow_fish.val(), pwd, 256));
    $('#timezone').val(moment.tz.guess())

    input_pwd.val('secret');
    $('#token').val(epwd);
    $("#login_form").trigger('submit');


}


$(document).ready(function () {


    $("#login__username").trigger("focus")


    $("#login_form").on("submit", function (event) {

    })

    $( "#login_form" ).submit(function( event ) {

    });

    $("#error_message").animate({
        opacity: 0,
    }, 5000, function () {
        $("#error_message").css('visibility', 'hidden')
    });


})
