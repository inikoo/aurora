var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;

var data={ 
    "Customer Type":''
    ,"Customer Name":''
    ,"Customer Main Contact Name":''
    ,"Customer Tax Number":""
    ,"Customer Registration Number":""
    ,"Customer Main Plain Email":''
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
     ,"Customer Address Country 2 Alpha Code":""
    ,"Customer Address Town Second Division":""
    ,"Customer Address Town First Division":""
    ,"Customer Address Country First Division":""
    ,"Customer Address Country Second Division":""
    ,"Customer Address Country Third Division":""
    ,"Customer Address Country Forth Division":""
    ,"Customer Address Country Fifth Division":""
    ,"Customer Send Newsletter":"Yes"
    ,"Customer Send Email Marketing":"Yes"
    ,"Customer Send Postal Marketing":"Yes"
    };

function isValidEmail(email){
    var RegExp = /^((([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+(\.([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+)*)@((((([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.))*([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/i
	if(RegExp.test(email)){
	    return true;
	}else{
	    return false;
	}
}


function change_allow(o){

    if(o.name=='newsletter'){
        data["Customer Send Email Marketing"]=o.value;
        data["Customer Send Email Marketing"]=o.value;
    }else if(o.name=='catalogue'){
        data["Customer Send Postal Marketing"]=o.value;
    }
}

function my_encodeURIComponent (str) {
str=encodeURIComponent (str);

return (str + '').replace(/'/g, '%27');


}


function update_category(o){
    var parent_category_key=o.getAttribute('cat_key');
    var category_key=o.options[o.selectedIndex].value;
    data['Cat'+parent_category_key]=category_key;
}

function register(){


    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;


data['Customer Name']=Dom.get('register_company_name').value;
data['Customer Main Contact Name']=Dom.get('register_contact_name').value;
data['Customer Main Plain Email']=Dom.get('confirmed_register_email').innerHTML
data['Customer Main Plain Telephone']=Dom.get('register_telephone').value;
data['Customer Address Line 1']=Dom.get('register_address_line1').value;
data['Customer Address Line 2']=Dom.get('register_address_line2').value;
data['Customer Address Town']=Dom.get('register_address_town').value;
data['Customer Address Postal Code']=Dom.get('register_address_postcode').value;
data['Customer Address Country 2 Alpha Code']=Dom.get('register_address_country_2alpha_code').value;
data['captcha_code']=Dom.get('captcha_code').value;

data['ep']=AESEncryptCtr(sha256_digest(Dom.get('register_password1').value),Dom.get('epw2').value,256);
  var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 

       var epwd=AESEncryptCtr(sha256_digest(Dom.get('register_password1').value),Dom.get('epw2').value,256);


Dom.setStyle('tr_register_part_2_buttons','display','none');
Dom.setStyle('tr_register_part_2_wait','display','');

     var request='../../inikoo_files/ar_register.php?tipo=register&values='+json_value+'&store_key='+store_key+'&site_key='+site_key+'&ep='+encodeURIComponent(epwd);
// alert(request);return;
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
						
					}else if(r.result=='capture_false'){
						
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

     var request='../../inikoo_files/ar_register.php?tipo=check_email&login_handle='+login_handle+'&store_key='+store_key+'&site_key='+site_key;
 
	//alert(request);
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		var r=YAHOO.lang.JSON.parse(o.responseText);
	
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
					
					
						show_email_in_db_dialog();
					
					
					}
    		    
    		    }
			    
			
	
			        
			        
		        }
		 
			

		},failure:function(o){
		    alert('Communication error')
		}
	    
	    });
}

function forgot_password(){

    var login_handle=Dom.get('forgot_password_handle').value;
    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;
	var captcha_code=Dom.get('captcha_code2').value;

var url ='http://'+ window.location.host + window.location.pathname;

var data={'login_handle':login_handle,'store_key':store_key,'site_key':site_key,'url':url, 'captcha_code':captcha_code}

  var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 


     var request='../../inikoo_files/ar_register.php?tipo=forgot_password&values='+json_value;
  //alert(request);
  Dom.setStyle('tr_forgot_password_buttons','display','none');
    Dom.setStyle('tr_forgot_password_wait','display','');

  
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			Dom.removeClass('captcha_code2','error');
		        if(r.result=='send'){
       Dom.setStyle('tr_forgot_password_wait','display','none');
			            Dom.setStyle('tr_forgot_password_send','display','');
    		    }else if(r.result=='handle_not_found'){
			        
			            Dom.setStyle('tr_forgot_password_wait','display','none');
			            Dom.setStyle('tr_forgot_password_not_found','display','');

			      }else if(r.result=='capture_false'){
		        
		            Dom.addClass('captcha_code2','error');
		              Dom.setStyle('tr_forgot_password_wait','display','none');
		               Dom.setStyle('tr_forgot_password_buttons','display','');
		        
		          
			        
		        }else{
		          Dom.setStyle('tr_forgot_password_wait','display','none');
			            Dom.setStyle('tr_forgot_password_error','display','');
		        }
		    }else{
		          Dom.setStyle('tr_forgot_password_wait','display','none');
			            Dom.setStyle('tr_forgot_password_error','display','');
		    }
			

		},failure:function(o){
		  //  alert(o)
		}
	    
	    });
}


function forgot_password2(){

    var login_handle=Dom.get('email_in_db').innerHTML;
    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;
	var captcha_code=Dom.get('captcha_code3').value;

var url ='http://'+ window.location.host + window.location.pathname;

var data={'login_handle':login_handle,'store_key':store_key,'site_key':site_key,'url':url, 'captcha_code':captcha_code}

  var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 


     var request='../../inikoo_files/ar_register.php?tipo=forgot_password&values='+json_value;
 
  Dom.setStyle('tr_email_in_db_buttons','display','none');
    Dom.setStyle('tr_forgot_password_wait2','display','');

    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
					            Dom.removeClass('captcha_code3','error');

		        if(r.result=='send'){
                        Dom.setStyle(['tr_forgot_password_wait2','email_in_db_instructions','tr_email_in_db_captcha'],'display','none');
			            Dom.setStyle('tr_forgot_password_send2','display','');
			            
    		    }else if(r.result=='handle_not_found'){
			        
			            Dom.setStyle('tr_forgot_password_wait2','display','none');
			            Dom.setStyle('tr_forgot_password_not_found2','display','');

			        
			        
		        }else if(r.result=='capture_false'){
		        
		            Dom.addClass('captcha_code3','error');
		              Dom.setStyle('tr_forgot_password_wait2','display','none');
		               Dom.setStyle('tr_email_in_db_buttons','display','');
		        
		        }else{
		          Dom.setStyle('tr_forgot_password_wait2','display','none');
			            Dom.setStyle('tr_forgot_password_error2','display','');
		        }
		    }else{
		          Dom.setStyle('tr_forgot_password_wait2','display','none');
			            Dom.setStyle('tr_forgot_password_error2','display','');
		    }
			

		},failure:function(o){
		  //  alert(o)
		}
	    
	    });
}


function login(){

    var input_login=Dom.get('login_handle').value;
    var pwd=sha256_digest(Dom.get('login_password').value);
    var input_epwd=Dom.get('ep').value;
    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;
	var remember_me=Dom.get('remember_me').value;
    
 
     var epwd=AESEncryptCtr(Dom.get('ep').value,pwd,256);
  

//Dom.get('login_password').value='';
    //Dom.get('loginform').submit();
     var request='../../inikoo_files/ar_login.php?ep='+encodeURIComponent(epwd)+'&login_handle='+input_login+'&store_key='+store_key+'&site_key='+site_key+'&remember_me='+remember_me;
      alert(request);
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
Dom.setStyle(['show_login_dialog','show_register_dialog','dialog_register','dialog_forgot_password','dialog_register_part_2','tr_link_register_from_login2'],'display','none');
Dom.setStyle(['dialog_login'],'display','block');
Dom.setStyle(['tr_link_register_from_login'],'display','');
Dom.get('login_handle').focus();
}
function hide_login_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog'],'display','');
Dom.setStyle(['dialog_login'],'display','none');

Dom.get('login_handle').value='';
Dom.get('login_password').value='';
Dom.removeClass(['login_handle','login_password'],'error')

}

function show_register_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog','dialog_login','dialog_forgot_password','dialog_register_part_2'],'display','none');
Dom.setStyle('dialog_register','display','block');
Dom.get('register_email').focus();

}
function hide_register_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog'],'display','');
Dom.setStyle('dialog_register','display','none');
}

function show_register_part_2_dialog(){
Dom.get('captcha').src = '../../inikoo_files/securimage_show.php?height=40&' + Math.random();

Dom.setStyle(['show_login_dialog','dialog_login','dialog_forgot_password','dialog_register'],'display','none');
Dom.setStyle('dialog_register_part_2','display','block');
Dom.get('register_password1').focus()
}
function hide_register_part_2_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog','dialog_register'],'display','');
Dom.setStyle('dialog_register_part_2','display','none');
Dom.get('register_password1').value=''
Dom.get('register_password2').value='';
}

function hide_email_in_db_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog'],'display','');

Dom.setStyle('dialog_email_in_db','display','none')
    Dom.get('captcha_code3').value='';

}


function show_forgot_password_dialog(){
Dom.get('captcha2').src = '../../inikoo_files/securimage_show.php?height=40&' + Math.random();

Dom.setStyle(['show_login_dialog','show_forgot_password_dialog','dialog_login','dialog_register','dialog_register_part_2'],'display','none');
Dom.setStyle('dialog_forgot_password','display','block');


}



function show_forgot_password_from_login(){

Dom.get('forgot_password_handle').value=Dom.get('login_handle').value;
show_forgot_password_dialog();
}

function hide_forgot_password_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog',"tr_forgot_password_buttons"],'display','');
Dom.setStyle(['dialog_forgot_password',"tr_forgot_password_wait","tr_forgot_password_send","tr_forgot_password_error","tr_forgot_password_not_found"],'display','none');




}


function show_register_from_login(){
Dom.get('register_email').value=Dom.get('login_handle').value;
show_register_dialog();
}



function show_register_from_forgot_password(){
Dom.get('register_email').value=Dom.get('forgot_password_handle').value;
show_register_dialog();
}

function submit_login(){
if(Dom.get('remember_me').checked)
	Dom.get('remember_me').value=1;
else
	Dom.get('remember_me').value=0;
	

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


if( Dom.get('captcha_code2').value==''){
Dom.addClass(['captcha_code2'],'error');
error=true;
}else{
Dom.removeClass(['captcha_code2'],'error');
}


if(!error)
forgot_password()
}



function submit_forgot_password_from_email_in_db(){

var error=false;


if( Dom.get('captcha_code3').value==''){
Dom.addClass(['captcha_code3'],'error');
error=true;
}else{
Dom.removeClass(['captcha_code3'],'error');
}


if(!error)
forgot_password2()
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


if( Dom.get('captcha_code').value==''){
Dom.addClass(['captcha_code'],'error');
error=true;
}else{
Dom.removeClass(['captcha_code'],'error');
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


function show_email_in_db_dialog(){

Dom.get('captcha3').src = '../../inikoo_files/securimage_show.php?height=40&' + Math.random();

Dom.get('email_in_db').innerHTML=Dom.get('register_email').value;
Dom.get('register_email').value='';

Dom.setStyle('dialog_email_in_db',  'display','block');
Dom.setStyle(['dialog_register','tr_forgot_password_wait2','tr_forgot_password_send2','tr_forgot_password_error2'],'display','none');


    Dom.setStyle(['tr_email_in_db_buttons','email_in_db_instructions','tr_email_in_db_captcha'],'display','');

Dom.get('captcha_code3').focus();


}




function init(){


//     var epwd=AESEncryptCtr('a','a',256);
   
//    alert('a ->'+epwd+'<-')
//return;

Event.addListener("show_login_dialog", "click", show_login_dialog);
Event.addListener("show_register_dialog", "click", show_register_dialog);
Event.addListener("hide_login_dialog", "click", hide_login_dialog);
Event.addListener("hide_register_dialog", "click", hide_register_dialog);
Event.addListener(["hide_forgot_password_dialog","hide_forgot_password_dialog2","hide_forgot_password_dialog3","hide_forgot_password_dialog4"], "click", hide_forgot_password_dialog);
Event.addListener("hide_register_part_2_dialog", "click", hide_register_part_2_dialog);
Event.addListener(["hide_email_in_db_dialog","hide_email_in_db_dialog2","hide_email_in_db_dialog3"], "click", hide_email_in_db_dialog);



Event.addListener("submit_forgot_password", "click", submit_forgot_password);
Event.addListener("submit_check_email", "click", submit_check_email);
Event.addListener("submit_register", "click", submit_register);
Event.addListener("submit_login", "click", submit_login);
Event.addListener("submit_forgot_password_from_email_in_db", "click", submit_forgot_password_from_email_in_db);


Event.addListener(["link_forgot_password_from_login","tr_link_register_from_login2"], "click", show_forgot_password_from_login);
Event.addListener("link_register_from_login", "click", show_register_from_login);
Event.addListener("link_register_from_forgot_password", "click", show_register_from_forgot_password);





}
Event.onDOMReady(init);
