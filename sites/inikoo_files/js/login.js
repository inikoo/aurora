
var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;


function submit_forgot_password(){

    var login_handle=Dom.get('forgot_password_handle').value;
    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;
	var captcha_code=Dom.get('captcha_code2').value;

remove_forgot_password_message_blocks()
			            Dom.setStyle('processing_change_password','display','');

var url ='http://'+window.location.host + window.location.pathname;

var data={'login_handle':login_handle,'store_key':store_key,'site_key':site_key,'url':url, 'captcha_code':captcha_code}

  var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 


     var request='ar_register.php?tipo=forgot_password&values='+json_value;
 //alert(request);
  Dom.setStyle('message_forgot_password_buttons','display','none');
    Dom.setStyle('message_forgot_password_wait','display','');

  
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
//		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			Dom.removeClass('captcha_code2','error');
		        if(r.result=='send'){
		          remove_forgot_password_message_blocks()
		          
		       
		          
		          
		          
                    show_login_dialog();
			            Dom.setStyle('message_forgot_password_send','display','');
			            
    		    }else if(r.result=='handle_not_found'){
    		 //    cancel_forgot_password()
			   
			   
			   
			//   alert(r.msg)
			   
			   			Dom.addClass('forgot_password_handle','error');

                

			   remove_forgot_password_message_blocks()
			          
			   
			   	Dom.removeClass('captcha_code2','error');
Dom.get('captcha2').src ='securimage_show.php?height=40&' + Math.random();
Dom.get('captcha_code2').value='';
Dom.get('forgot_password_handle').focus();

			   
			   
			            Dom.setStyle('message_forgot_password_not_found','display','');
			            
			              

			      }else if(r.result=='capture_false'){
		        remove_forgot_password_message_blocks()
		            Dom.addClass('captcha_code2','error');
		             			            Dom.setStyle('message_forgot_password_error_captcha','display','');
		        }else{
  cancel_forgot_password()
			          remove_forgot_password_message_blocks()
			            Dom.setStyle('message_forgot_password_error','display','');
		        }
		    }else{
		          Dom.setStyle('message_forgot_password_wait','display','none');
			            Dom.setStyle('message_forgot_password_error','display','');
		    }
			

		},failure:function(o){
		   alert(o)
		}
	    
	    });
}


function login(){

    var input_login=Dom.get('email').value;
    var pwd=sha256_digest(Dom.get('password').value);
    var input_epwd=Dom.get('ep').value;
    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;
	var remember_me=Dom.get('remember_me').value;
    
 
     var epwd=AESEncryptCtr(Dom.get('ep').value,pwd,256);
  

//Dom.get('password').value='';
    //Dom.get('loginform').submit();
     var request='ar_login.php?ep='+encodeURIComponent(epwd)+'&login_handle='+input_login+'&store_key='+store_key+'&site_key='+site_key+'&remember_me='+remember_me;
    // alert(request);
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			
		      if(r.result=='ok'){
		      
		      if(Dom.get('referral').value){
		      window.location ='page.php?id='+Dom.get('referral').value;
		      }else{
		       window.location ='profile.php';
		      }
		      
           // window.location ='http://'+ window.location.host + window.location.pathname;
        
			  
		   }else if(r.result=='no_valid'){
			  Dom.setStyle('invalid_credentials','display','');
                Dom.addClass(['password','email'],'error');
                Dom.get('email').focus();
		      }
			    }else{
			window.location='index.php?le';
		    }
			

		},failure:function(o){
		   
		}
	    
	    });
}



function isValidEmail(email){
        return /^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*$/.test( email );
}


function my_encodeURIComponent (str) {
str=encodeURIComponent (str);

return (str + '').replace(/'/g, '%27');


}





function show_login_dialog(){

   remove_login_message_blocks()
   Dom.get('email').value='';
   Dom.get('password').value='';
Dom.removeClass(['email','password'],'error');

	Dom.setStyle('dialog_forgot_password','display','none');
Dom.setStyle('dialog_login','display','');
	Dom.get('email').focus();
cancel_forgot_password();

}

function show_forgot_password_dialog(){
remove_forgot_password_message_blocks()

Dom.removeClass(['forgot_password_handle','captcha_code2'],'error');


Dom.get('forgot_password_handle').value=Dom.get('email').value;

Dom.get('captcha2').src ='securimage_show.php?height=40&' + Math.random();

Dom.setStyle('dialog_login','display','none');
Dom.setStyle('dialog_forgot_password','display','block');
Dom.get('forgot_password_handle').focus();

}
function cancel_forgot_password(){

Dom.setStyle('dialog_login','display','');
Dom.setStyle('dialog_forgot_password','display','none');
	Dom.removeClass(['captcha_code2','forgot_password_handle'],'error');
Dom.get('captcha2').src ='securimage_show.php?height=40&' + Math.random();
Dom.get('forgot_password_handle').value='';
Dom.get('captcha_code2').value='';
Dom.get('email').focus();


remove_forgot_password_message_blocks()



  Dom.removeClass('password','error');
  Dom.removeClass('email','error');
  Dom.get('password').value='';
remove_login_message_blocks();





}



function submit_login(){

remove_login_message_blocks()

if(Dom.get('remember_me').checked)
	Dom.get('remember_me').value=1;
else
	Dom.get('remember_me').value=0;
	
var error=false;

if(Dom.get('password').value==''){
    Dom.addClass('password','error');
    
    Dom.setStyle('message_login_fields_missing','display','');
    Dom.get('password').focus();
    error=true;
}else{
    Dom.removeClass('password','error');
}


if(Dom.get('password').value==''){
Dom.addClass('email','error');
    
    Dom.setStyle('message_login_fields_missing','display','');
    Dom.get('email').focus();
    error=true;
}else if(  !isValidEmail(Dom.get('email').value)){
    Dom.addClass('email','error');
        Dom.setStyle('message_login_wrong_email','display','');
Dom.get('email').focus();
    error=true;
}else{
Dom.removeClass('email','error');

}

if(!error)
login()


}
function forgot_password(){

remove_forgot_password_message_blocks()

var error=false;

if(Dom.get('forgot_password_handle').value==''){
Dom.addClass('forgot_password_handle','error');
Dom.setStyle('message_forgot_password_fields_missing','display','')
error=true;
}else if(  !isValidEmail(Dom.get('forgot_password_handle').value)){
Dom.addClass('forgot_password_handle','error');
Dom.setStyle('message_forgot_password_wrong_email','display','')
error=true;
}else{
Dom.removeClass('forgot_password_handle','error');
}


if( Dom.get('captcha_code2').value==''){
Dom.addClass(['captcha_code2'],'error');
Dom.setStyle('message_forgot_password_fields_missing','display','')

error=true;
}else{
Dom.removeClass(['captcha_code2'],'error');
}


if(!error)
submit_forgot_password()
}


function submit_login_on_enter(e){
    Dom.removeClass('password','error');
  Dom.removeClass('email','error');
remove_login_message_blocks()

     var key;     
     if(window.event)
         Key = window.event.keyCode; //IE
     else
         Key = e.which; //firefox     

     if (Key == 13){
	 submit_login();
	 
	 }
};
function forgot_password_on_enter(e){


Dom.setStyle('message_forgot_password_fields_missing','display','none')


if(Dom.get('forgot_password_handle').value!=''){

Dom.removeClass('forgot_password_handle','error');
Dom.setStyle('message_forgot_password_wrong_email','display','none')

}


if( Dom.get('captcha_code2').value!=''){

Dom.removeClass(['captcha_code2'],'error');

}



     var key;     
     if(window.event)
         Key = window.event.keyCode; //IE
     else
         Key = e.which; //firefox     

     if (Key == 13){
	 forgot_password();
	 
	 }
};

function handle_changed(e){

    Dom.removeClass('email','error');
remove_login_message_blocks()
return;
/*
 var key;     
     if(window.event)
         Key = window.event.keyCode; //IE
     else
         Key = e.which; //firefox     

     if (Key == 13){
	 submit_login();
	 
	 }
*/
}

function remove_forgot_password_message_blocks(){
Dom.setStyle(['message_forgot_password_wrong_email','message_forgot_password_error_captcha','message_forgot_password_fields_missing','message_forgot_password_error','message_forgot_password_not_found','processing_change_password'],'display','none');
}
function remove_login_message_blocks(){
Dom.setStyle(['message_log_out','invalid_credentials','message_login_fields_missing','message_login_wrong_email','message_forgot_password_send'],'display','none');
}

function init(){

Event.addListener("submit_login", "click", submit_login);
Event.addListener("link_forgot_password_from_login", "click", show_forgot_password_dialog);
Event.addListener(['show_login_dialog3','show_login_dialog2'], "click", show_login_dialog);
Event.addListener('submit_forgot_password', "click", forgot_password);
Event.addListener(['forgot_password_handle','captcha_code2'], "keydown", forgot_password_on_enter);



//Event.addListener('captcha_code2', "keydown", submit_forgot_password_form_on_enter);
Event.addListener('cancel_forgot_password', "click", cancel_forgot_password);


Event.addListener('email', "keydown", handle_changed);
Event.addListener(['password'], "keydown", submit_login_on_enter);



Dom.get('email').focus()
}
Event.onDOMReady(init);

