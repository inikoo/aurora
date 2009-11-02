
    var onmySubmit =function(){

	var input_login=document.getElementById("_login_");
	var input_pwd=document.getElementById("_passwd_");
	var input_epwd=document.getElementById("ep");
	var theform=document.getElementById("loginform");

	var pwd=sha256_digest(input_pwd.value);

	//	var pwd='hola';
	var epwd=AESEncryptCtr(input_epwd.value,pwd,256);
	input_pwd.value='secret';
	input_epwd.value=epwd;

	theform.submit();
    

}

var submit_form_on_enter=function(e){
     var key;     
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox     

     if (key == 13)
	 onmySubmit()
};

function init() {

    YAHOO.util.Event.addListener('_passwd_', "keydown", submit_form_on_enter);


    document.getElementById("_passwd_").value='';



    // var log_in=document.getElementById("_login_");
    //log_in.focus();
    
    var oPushButton1 = new YAHOO.widget.Button("login_go"); 
    oPushButton1.on("click", onmySubmit); 
    


 }


 YAHOO.util.Event.onDOMReady(init);

