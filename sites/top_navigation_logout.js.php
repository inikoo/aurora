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


function register(){


    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;

 

var data={ 
    "Customer Type":''
    ,"Customer Name":Dom.get('register_company_name').value
    ,"Customer Main Contact Name":Dom.get('register_contact_name').value
    ,"Customer Tax Number":""
    ,"Customer Registration Number":""
    ,"Customer Main Plain Email":Dom.get('confirmed_register_email').innerHTML
    ,"Customer Main Plain Telephone":""
    ,"Customer Main Plain FAX":""
    ,"Customer Main Plain Mobile":""
    ,"Customer Address Line 1":""
    ,"Customer Address Line 2":""
    ,"Customer Address Line 3":""
    ,"Customer Address Town":""
    ,"Customer Address Postal Code":""
    ,"Customer Address Country Name":""
    ,"Customer Address Country Code":""
    ,"Customer Address Town Second Division":""
    ,"Customer Address Town First Division":""
    ,"Customer Address Country First Division":""
    ,"Customer Address Country Second Division":""
    ,"Customer Address Country Third Division":""
    ,"Customer Address Country Forth Division":""
    ,"Customer Address Country Fifth Division":""};

  var json_value = YAHOO.lang.JSON.stringify(data); 

       var epwd=AESEncryptCtr(sha256_digest(Dom.get('register_password1').value),Dom.get('epw2').value,256);


     var request='../ar_register.php?tipo=register&values='+json_value+'&store_key='+store_key+'&site_key='+site_key+'&ep='+encodeURIComponent(epwd);
 
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
		       if(r.action='logged_in'){
		       window.location ='http://'+ window.location.host + window.location.pathname+'?welcome=1';

		       }
		    
		    
			
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


function check_email(){

    var login_handle=Dom.get('register_email').value;
    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;

     var request='../ar_register.php?tipo=check_email&login_handle='+login_handle+'&store_key='+store_key+'&site_key='+site_key;
   //alert(request);
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			
			if(r.result=='error'){
                Dom.addClass('register_email','error');
    		    }else{
    		            Dom.removeClass('register_email','error');

    		    if(r.result=='not_found'){
    		        Dom.get('confirmed_register_email').innerHTML=r.login_handle;
    		        
    		        Dom.get('epw2').value=r.epw2;
    		        show_register_part_2_dialog();
    		    
    		    }else if(r.result=='found'){
    		    
    		        //show_login_dialog_from_registration();
    		    
    		    
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
	//	alert(o.responseText)
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
  

//Dom.get('login_password').value='';
    //Dom.get('loginform').submit();
     var request='../ar_login.php?ep='+encodeURIComponent(epwd)+'&login_handle='+input_login+'&store_key='+store_key+'&site_key='+site_key;
    //  alert(request);
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			
		      if(r.result=='ok'){
            window.location ='http://'+ window.location.host + window.location.pathname;

			  
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



function submit_register(){

var error=false;


if(  Dom.get('register_password1').value=='' &&  Dom.get('register_password1').value==Dom.get('register_password2').value ){
Dom.addClass(['register_password1','register_password2'],'error');
error=true;
Dom.setStyle('register_error_no_password','display','')

}else{
Dom.removeClass(['register_password1','register_password2'],'error');
Dom.setStyle('register_error_no_password','display','none')

}



if(!error){
if( Dom.get('register_password1').value!=Dom.get('register_password2').value ){
Dom.addClass(['register_password1','register_password2'],'error');
if(!error)
Dom.setStyle('register_error_password_not_march','display','')
error=true;

}else{
Dom.removeClass(['register_password1','register_password2'],'error');
Dom.setStyle('register_error_password_not_march','display','none')

}
}
if(!error){
if(!error &&   Dom.get('register_password1').value.length<6){
Dom.addClass(['register_password1'],'error');

if(!error)
    Dom.setStyle('register_error_password_too_short','display','')

    
error=true;
}else{
Dom.removeClass(['register_password1'],'error');
    Dom.setStyle('register_error_password_too_short','display','none')

}
}



if( Dom.get('register_company_name').value=='' &&  Dom.get('register_contact_name').value=='' ){
Dom.addClass(['register_company_name','register_contact_name'],'error');
error=true;
}else{
Dom.removeClass(['register_company_name','register_contact_name'],'error');
}

if(   Dom.get('register_contact_name').value=='' ){
Dom.addClass('register_contact_name','error');
error=true;
}else{
Dom.removeClass('register_contact_name','error');
}

if(!error)
register()
}



//if log ini

function logout(){
var url = window.location;
alert(url)

}



function init(){


//     var epwd=AESEncryptCtr('a','a',256);
   
//    alert('a ->'+epwd+'<-')
//return;

Event.addListener("show_login_dialog", "click", show_login_dialog);
Event.addListener("show_register_dialog", "click", show_register_dialog);
Event.addListener("hide_login_dialog", "click", hide_login_dialog);
Event.addListener("hide_register_dialog", "click", hide_register_dialog);
Event.addListener("submit_forgot_password", "click", submit_forgot_password);
Event.addListener("submit_check_email", "click", submit_check_email);
Event.addListener("submit_register", "click", submit_register);
Event.addListener("submit_login", "click", submit_login);
Event.addListener("link_forgot_password_from_login", "click", show_forgot_password_from_login);
Event.addListener("link_register_from_login", "click", show_register_from_login);


//if log ini


Event.addListener("logout", "click", logout);



}
Event.onDOMReady(init);
