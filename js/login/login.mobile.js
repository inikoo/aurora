var sms = function(){
    var checkAndShowSnackBar = function(){

        var snackbarContainer = document.querySelector('#notification');

        var data = {message: $('#error_message').html(), timeout: 5000};
        snackbarContainer.MaterialSnackbar.showSnackbar(data);

    };
    var timeout = function(fn, timeout){
        setTimeout(fn, timeout);
    };
    return{
        init: function(){
           // new WOW().init();

            timeout(checkAndShowSnackBar, 100);
        }
    }
}();

$(document).ready(function() {
    console.log($('#error_message').attr('error') )
    if ($('#error_message').attr('error') ==1) {

    sms.init();
}
});