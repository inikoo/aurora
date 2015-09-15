var onmySubmit = function() {


        var input_login = document.getElementById("login__username");
        var input_pwd = document.getElementById("login__password");
        var input_epwd = document.getElementById("ep");
        var theform = document.getElementById("loginform");

        var pwd = sha256_digest(input_pwd.value);

        //	var pwd='hola';
        var epwd = AESEncryptCtr(input_epwd.value, pwd, 256);
        input_pwd.value = 'secret';
        input_epwd.value = epwd;

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

    $("#login_form").submit(function(event) {


        var pwd = sha256_digest($('#login__password').val());
        var epwd = AESEncryptCtr($('#ep').val(), pwd, 256);
        $('#ep').val(btoa(epwd))
        $('#login__password').val('secret')
       
      
      //  $("#login_form").submit();

      // event.preventDefault();
    });

   
  $( "#error_message" ).animate({
    opacity: 0,
   
  }, 5000, function() {
     $( "#error_message" ).css('visibility','hidden')
  });

})
