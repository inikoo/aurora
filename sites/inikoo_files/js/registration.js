
var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;

var categories_other_data ={}

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

function get_category_other_value_data(){
	for ( category_key in categories_other_data)
	{
		data['Cat'+category_key+'_Other_Value']=Dom.get('category_other_value_textarea_'+category_key).value;
	}
}

function update_category(o){
    var parent_category_key=o.getAttribute('cat_key');
    var category_key=o.options[o.selectedIndex].value;
    data['Cat'+parent_category_key]=category_key;
//alert('Cat'+parent_category_key+' : '+category_key)
    var category_object=o.options[o.selectedIndex];
    
    
    if(Dom.get(category_object).getAttribute('other')=='true'){
   
        Dom.get('other_tbody_'+parent_category_key).style.display='';
	categories_other_data [parent_category_key]=parent_category_key;
        return;
    }
    else{
	Dom.get('other_tbody_'+parent_category_key).style.display='none';
	delete categories_other_data [parent_category_key]
    }
}


function isValidEmail(email){
    var RegExp = /^((([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+(\.([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+)*)@((((([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.))*([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/i
	if(RegExp.test(email)){
	    return true;
	}else{
	    return false;
	}
}
function my_encodeURIComponent (str) {
str=encodeURIComponent (str);

return (str + '').replace(/'/g, '%27');


}


function hide_register_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog'],'display','');
Dom.setStyle('dialog_register','display','none');
}
function show_register_dialog(){

Dom.get('captcha').src = 'securimage_show.php?height=40&' + Math.random();

Dom.setStyle(['dialog_email_in_db','dialog_check_email'],'display','none');
Dom.setStyle('dialog_register','display','block');
Dom.get('register_password1').focus()
}
function hide_register_part_2_dialog(){
Dom.setStyle(['show_login_dialog','show_register_dialog','dialog_register'],'display','');
Dom.setStyle('dialog_register_part_2','display','none');
Dom.get('register_password1').value=''
Dom.get('register_password2').value='';
}
function hide_email_in_db_dialog(){
Dom.setStyle('dialog_register','display','');

Dom.setStyle('dialog_email_in_db','display','none')
    Dom.get('captcha_code3').value='';
Dom.get('register_email').focus()
}
function show_register_from_login(){
Dom.get('register_email').value=Dom.get('login_handle').value;
show_register_dialog();
}
function show_register_from_forgot_password(){
Dom.get('register_email').value=Dom.get('forgot_password_handle').value;
show_register_dialog();
}




function show_email_in_db_dialog(){
	Dom.get('captcha3').src = 'securimage_show.php?height=40&' + Math.random();

	Dom.get('email_in_db').innerHTML=Dom.get('check_email').value;
	Dom.get('check_email').value='';

	Dom.setStyle('dialog_email_in_db',  'display','block');
	Dom.setStyle(['dialog_check_email','dialog_register','tr_forgot_password_wait2','tr_forgot_password_send2','tr_forgot_password_error2'],'display','none');

	Dom.setStyle(['tr_email_in_db_buttons','email_in_db_instructions','tr_email_in_db_captcha'],'display','');

	Dom.get('captcha_code3').focus();
}




var submit_check_email_on_enter=function(e){
     var key;     
     if(window.event)
         Key = window.event.keyCode; //IE
     else
         Key = e.which; //firefox     

     if (Key == 13){
	 check_email();
     }
};






function submit_register(){


    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;

data['Customer Registration Number']=Dom.get('register_registration_number').value;

data['Customer Name']=Dom.get('register_company_name').value;
data['Customer Main Contact Name']=Dom.get('register_contact_name').value;
data['Customer Main Plain Email']=Dom.get('confirmed_register_email').innerHTML
data['Customer Main Plain Telephone']=Dom.get('register_telephone').value;
data['Customer Address Line 1']=Dom.get('register_address_line1').value;
data['Customer Address Line 2']=Dom.get('register_address_line2').value;
data['Customer Address Town']=Dom.get('register_address_town').value;
data['Customer Address Postal Code']=Dom.get('register_address_postcode').value;
data['Customer Address Country 2 Alpha Code']=Dom.get('register_address_country_2alpha_code').value;
data['Customer Store Key']=Dom.get('store_key').value;
data['captcha_code']=Dom.get('captcha_code').value;

get_category_other_value_data();

data['ep']=AESEncryptCtr(sha256_digest(Dom.get('register_password1').value),Dom.get('epw2').value,256);
  var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 

       var epwd=AESEncryptCtr(sha256_digest(Dom.get('register_password1').value),Dom.get('epw2').value,256);


Dom.setStyle('processing_register','display','');
Dom.setStyle(['submit_register','cancel_register'],'visibility','hidden');

     var request='ar_register.php?tipo=register&values='+json_value+'&store_key='+store_key+'&site_key='+site_key+'&ep='+encodeURIComponent(epwd);
// alert(request);//return;
 
     
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	Dom.setStyle(['submit_register','cancel_register'],'visibility','visible');

		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
		       if(r.result=='logged_in'){
		       
		           // remove_register_message_blocks()
		       
				window.location ='http://'+ window.location.host + '/welcome.php';

		       }
		        else if(r.result=='error'){
                remove_register_message_blocks()
				Dom.setStyle('message_register_error','display','');

				}else if(r.result=='handle_found'){
				remove_register_message_blocks()

						show_email_in_db_dialog();
				}else if(r.result=='capture_false'){
                    remove_register_message_blocks()
		            Dom.addClass('captcha_code','error');
		            Dom.setStyle('message_register_error_captcha','display','');
				}else{
				remove_register_message_blocks()
				Dom.setStyle('message_register_error','display','');
				
				}
			    
			
	
			        
			        
		    }
		 
			

		},failure:function(o){
		    alert(o)
		}
	    
	    });
}




function register(){


remove_register_message_blocks()

var error=false;


if(  Dom.get('register_password1').value=='' &&  Dom.get('register_password1').value==Dom.get('register_password2').value ){
Dom.addClass(['register_password1','register_password2'],'error');
error=true;
Dom.setStyle('message_register_fields_missing','display','')


}else{
Dom.removeClass(['register_password1','register_password2'],'error');

}



if(!error){

    if( Dom.get('register_password1').value!=Dom.get('register_password2').value ){
        Dom.addClass(['register_password1','register_password2'],'error');
    if(!error)
        Dom.setStyle('register_error_password_not_march','display','')
        error=true;

    }else{
        Dom.removeClass(['register_password1','register_password2'],'error');
    }
}

if(!error){
    if(!error &&   Dom.get('register_password1').value.length<6){
    Dom.addClass(['register_password1'],'error');
Dom.addClass(['register_password2'],'error');
    if(!error)
        Dom.setStyle('register_error_password_too_short','display','')

    error=true;
}else{
    Dom.removeClass(['register_password1'],'error');
    }
}



if( Dom.get('register_company_name').value=='' &&  Dom.get('register_contact_name').value=='' ){
Dom.addClass(['register_company_name','register_contact_name'],'error');
Dom.setStyle('message_register_fields_missing','display','')

error=true;
}else{
Dom.removeClass(['register_company_name','register_contact_name'],'error');
}


if( Dom.get('captcha_code').value==''){
Dom.addClass(['captcha_code'],'error');
Dom.setStyle('message_register_fields_missing','display','')

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


submit_register()
}







function submit_check_email(){




    var login_handle=Dom.get('check_email').value;
    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;

     var request='ar_register.php?tipo=check_email&login_handle='+login_handle+'&store_key='+store_key+'&site_key='+site_key;
 //	alert(request);
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		var r=YAHOO.lang.JSON.parse(o.responseText);
	
		    if(r.state=='200'){
			
				if(r.result=='error'){
					
					Dom.addClass('check_email','error');
    		    }else{
					
    		            Dom.removeClass('check_email','error');

					if(r.result=='not_found'){
						Dom.get('confirmed_register_email').innerHTML=r.login_handle;
						
						Dom.get('epw2').value=r.epw2;
						
						Dom.setStyle('dialog_check_email','display','none')
						remove_check_email_message_blocks();
						show_register_dialog();
					
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


function check_email(){

remove_check_email_message_blocks()

var error=false;

if( Dom.get('check_email').value==''){
Dom.setStyle('message_check_email_fields_missing','display','');
Dom.addClass('check_email','error');
error=true;
}else if(  !isValidEmail(Dom.get('check_email').value)){
Dom.setStyle('message_check_email_wrong_email','display','');

Dom.addClass('check_email','error');
error=true;
}else{
Dom.removeClass('check_email','error');

}

if(!error)
submit_check_email()

}


function cancel_register(){
 window.location.reload()
}


function remove_check_email_message_blocks(){
Dom.setStyle(['message_check_email_fields_missing','message_check_email_wrong_email'],'display','none');
}
function remove_register_message_blocks(){
Dom.setStyle(['processing_register','message_register_fields_missing','register_error_password_not_march','register_error_password_too_short','message_register_error_captcha'],'display','none');
}

function submit_forgot_password_from_email_in_db(){

var error=false;


if( Dom.get('captcha_code3').value==''){
Dom.addClass(['captcha_code3'],'error');
Dom.setStyle('message_email_in_db_missing_captcha','display','')
error=true;
}else{
Dom.removeClass(['captcha_code3'],'error');
Dom.setStyle('message_email_in_db_missing_captcha','display','none')

}


if(error){
return;
}


    var login_handle=Dom.get('email_in_db').innerHTML;
    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;
	var captcha_code=Dom.get('captcha_code3').value;

var url ='http://'+window.location.host + window.location.pathname;

var data={'login_handle':login_handle,'store_key':store_key,'site_key':site_key,'url':url, 'captcha_code':captcha_code}

  var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 


     var request='ar_register.php?tipo=forgot_password&values='+json_value;
// alert(request);return;
  Dom.setStyle('tr_email_in_db_buttons','display','none');
    Dom.setStyle('tr_forgot_password_wait2','display','');

    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	//	alert(o.responseText)
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


function hide_email_in_db_dialog(){

Dom.get('email_in_db').innerHTML=Dom.get('check_email').value;
Dom.get('check_email').value='';

Dom.setStyle('dialog_email_in_db',  'display','none');
Dom.setStyle(['dialog_check_email'],'display','');


    Dom.setStyle(['tr_email_in_db_buttons','email_in_db_instructions','tr_email_in_db_captcha'],'display','none');

Dom.get('check_email').focus();

}

function submit_forgot_password_from_email_in_db_on_enter(e){
Dom.removeClass(['captcha_code3'],'error');
Dom.setStyle('message_email_in_db_missing_captcha','display','none')

  var key;     
     if(window.event)
         Key = window.event.keyCode; //IE
     else
         Key = e.which; //firefox     

     if (Key == 13){
	 submit_forgot_password_from_email_in_db();
	 
	 }

}


function init(){
//alert("xx")
//Event.addListener(["hide_email_in_db_dialog"], "click", hide_email_in_db_dialog);

Event.addListener("cancel_register", "click", cancel_register);

Event.addListener("submit_register", "click", register);

Event.addListener("submit_check_email", "click", check_email);


Event.addListener('check_email', "keydown", submit_check_email_on_enter);
Event.addListener('captcha_code3', "keydown", submit_forgot_password_from_email_in_db_on_enter);


Event.addListener("submit_forgot_password_from_email_in_db", "click", submit_forgot_password_from_email_in_db);
Event.addListener(["hide_email_in_db_dialog","hide_email_in_db_dialog2"], "click", hide_email_in_db_dialog);


Dom.get('check_email').focus()
}
Event.onDOMReady(init);
