var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;

function isValidEmail(email){
    var RegExp = /^((([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+(\.([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+)*)@((((([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.))*([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/i
	if(RegExp.test(email)){
	    return true;
	}else{
	    return false;
	}
}

function check_email(){

    var login_handle=Dom.get('register_email').value;
    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;

     var request='../ar_register.php?tipo=check_email&login_handle='+login_handle+'&store_key='+store_key+'&site_key='+site_key;
   alert(request);
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			
			if(r.result=='error'){
                Dom.addClass('register_email','error');
    		    }else{
    		            Dom.removeClass('register_email','error');

    		    if(r.result=='not_found'){
    		        Dom.get('confirmed_register_email').innerHTML=r.login_handle;
    		        show_register_part_2_dialog();
    		    
    		    }else if(r.result=='found'){
    		    
    		    
    		    }
    		    
    		    }
			    
			
	
			        
			        
		        }
		 
			

		},failure:function(o){
		    alert(o)
		}
	    
	    });
}

function forgot_password(){

    var login_handle=Dom.get('forgot_password_handle').value;
    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;

     var request='../ar_register.php?tipo=forgot_password&login_handle='+login_handle+'&store_key='+store_key+'&site_key='+site_key;
   
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			
		        if(r.result=='send'){
      
    		    }else if(r.result=='handle_not_found'){
			        
			        
			        
			        
		        }
		    }else{
		    
		    }
			

		},failure:function(o){
		    alert(o)
		}
	    
	    });
}

function login(){

    var input_login=Dom.get('login_handle').value;
    var pwd=sha256_digest(Dom.get('login_password').value);
    var input_epwd=Dom.get('ep').value;
    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;

    
    
     var epwd=AESEncryptCtr(Dom.get('ep').value,pwd,256);
    // var epwd=AESEncryptCtr('hola1234567890123456789','caca',256);
    //    alert(AESDecryptCtr(epwd, 'caca',256)+"\n"+epwd);
    //  return;
//Dom.get('login_password').value='';
    //Dom.get('loginform').submit();
     var request='../ar_login.php?ep='+encodeURIComponent(epwd)+'&login_handle='+input_login+'&store_key='+store_key+'&site_key='+site_key;
     // alert(request);
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			
		      if(r.result=='ok'){

			  location.reload(true);
			  
		   }else if(r.result=='no_valid'){
			  Dom.setStyle('invalid_credentials','display','');
                Dom.addClass(['login_password','login_handle'],'error');
		      }
			    }else{
			window.location='index.php?le';
		    }
			

		},failure:function(o){
		    alert(o)
		}
	    
	    });
}

function show_login_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog','dialog_register','dialog_forgot_password','dialog_register_part_2'],'display','none');
Dom.setStyle('dialog_login','display','block');
}
function hide_login_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog'],'display','');
Dom.setStyle('dialog_login','display','none');
Dom.get('login_handle').value='';
Dom.get('login_password').value='';
}

function show_register_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog','dialog_login','dialog_forgot_password','dialog_register_part_2'],'display','none');
Dom.setStyle('dialog_register','display','block');
}
function hide_register_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog'],'display','');
Dom.setStyle('dialog_register','display','none');
}

function show_register_part_2_dialog(){
Dom.setStyle(['show_login_dialog','dialog_login','dialog_forgot_password','dialog_register'],'display','none');
Dom.setStyle('dialog_register_part_2','display','block');
}
function hide_register_part_2_dialog(){
Dom.setStyle(['show_login_dialog'],'display','');
Dom.setStyle('dialog_register_part_2','display','none');
}


function show_forgot_password_dialog(){
Dom.setStyle(['show_login_dialog','show_forgot_password_dialog','dialog_login','dialog_register','dialog_register_part_2'],'display','none');
Dom.setStyle('dialog_forgot_password','display','block');
}
function hide_forgot_password_dialog(){
Dom.setStyle(['show_login_dialog','show_forgot_password_dialog'],'display','');
Dom.setStyle('dialog_forgot_password','display','none');
}


function show_register_from_login(){
Dom.get('register_email').value=Dom.get('login_handle').value;
show_register_dialog();
}

function submit_login(){
var error=false;
if(Dom.get('login_password').value==''){
Dom.addClass('login_password','error');
error=true;
}else{
Dom.removeClass('login_password','error');

}
if(  !isValidEmail(Dom.get('login_handle').value)){
Dom.addClass('login_handle','error');
error=true;
}else{
Dom.removeClass('login_handle','error');

}

if(!error)
login()


}


function show_forgot_password_from_login(){
Dom.get('forgot_password_handle').value=Dom.get('login_handle').value;
show_forgot_password_dialog();
}

function submit_check_email(){
var error=false;
if(  !isValidEmail(Dom.get('register_email').value)){
Dom.addClass('register_email','error');
error=true;
}else{
Dom.removeClass('register_email','error');

}

if(!error)
check_email()

}

function submit_forgot_password(){

var error=false;
if(  !isValidEmail(Dom.get('forgot_password_handle').value)){
Dom.addClass('forgot_password_handle','error');
error=true;
}else{
Dom.removeClass('forgot_password_handle','error');

}

if(!error)
forgot_password()
}

function init(){
Event.addListener("show_login_dialog", "click", show_login_dialog);
Event.addListener("show_register_dialog", "click", show_register_dialog);
Event.addListener("hide_login_dialog", "click", hide_login_dialog);
Event.addListener("hide_register_dialog", "click", hide_register_dialog);
Event.addListener("submit_forgot_password", "click", submit_forgot_password);

Event.addListener("submit_check_email", "click", submit_check_email);


Event.addListener("submit_login", "click", submit_login);
Event.addListener("link_forgot_password_from_login", "click", show_forgot_password_from_login);
Event.addListener("link_register_from_login", "click", show_register_from_login);


}
Event.onDOMReady(init);
