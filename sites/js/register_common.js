var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;




var data={
    'email':''
    , 'password':''
    ,'customer_type':''
    ,'customer_type_other':''
    ,'customer_is_company':''
    ,'contact_name':''
    ,'company_name':''
    ,'tax_number':''

    ,'tel':''
    ,'country':''
    ,'country_d1':''
    ,'country_d2':''
    ,'postal_code':''
    ,'town':''
    ,'town_d1':''
    ,'town_d2':''
    ,'street':''
    ,'building':''
    ,'internal':''
    ,'newsletter':''
    ,'emarketing':''
    ,'catalogue':''
}


function validate_email(address) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   if(reg.test(address) == false) {
      return false;
   }else
       return true;

}












function submit_email(){
    
    var store_key=Dom.get('get_email').getAttribute('store_key');
    var email=Dom.get('email').value;
    if(email==''){
	Dom.get('email_instructions').innerHTML=Dom.get('email_error_msg_1').innerHTML;
	Dom.addClass(['email','email_label'],'error');

    }else if(!validate_email(email)){
	msg_id=1+Math.floor(Math.random()*2);
	
	Dom.get('email_instructions').innerHTML=Dom.get('email_not_valid_msg_'+msg_id).innerHTML;
	Dom.addClass(['email','email_label'],'error');
		

    }else if(email!=Dom.get('email_confirmation').value){
	Dom.get('email_confirmation').setAttribute('confirmed','yes');

	if(Dom.get('email_confirmation').value==''){
	    Dom.get('email_instructions').innerHTML=Dom.get('email_not_confirmed').innerHTML;
	
	}else{
	    Dom.get('email_instructions').innerHTML=Dom.get('email_error_confirmed').innerHTML;


	}
	Dom.addClass(['email_confirmation','email_confirmation_label'],'error');

    }else{
	var request='ar_register.php?tipo=check_email&store_key='+store_key+'&email='+email;
	
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	       //alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			if(r.result=='new'){
			    Dom.setStyle('submit_email','display','none');
			    Dom.setStyle('get_customer_type','display','');
			    Dom.setStyle('get_password','display','');
			    Dom.get('email_confirmation').setAttribute('confirmed','yes');
		    	    Dom.get('email_instructions').innerHTML=Dom.get('email_ok').innerHTML;

			    data.email=email;
			}else if(r.result=='found'){
			    Dom.get('registered_email').innerHTML=email;
			    Dom.setStyle('get_email','display','none');
			    Dom.setStyle('found_email','display','');


			}
			

		    }else{
			window.location='register.php?we';
		    }
			

		}
	    
	    });

    }
    
   
}

function password_changed(){

    if(Dom.get('password').getAttribute('confirmed')=='yes'){
	check_password();
    }
}

function email_confirmation_changed(){
    if(this.getAttribute('confirmed')=='yes'){

	if(this.value==Dom.get('email').value){
	    Dom.removeClass(['email_confirmation','email_confirmation_label'],'error');
	    Dom.get('email_instructions').innerHTML=Dom.get('email_ok').innerHTML;

	}else{
	    	if(this.value==''){
	    Dom.get('email_instructions').innerHTML=Dom.get('email_not_confirmed').innerHTML;
	
	}else{
	    Dom.get('email_instructions').innerHTML=Dom.get('email_error_confirmed').innerHTML;


	}
	Dom.addClass(['email_confirmation','email_confirmation_label'],'error');

	}

    }

}


function check_password(){
    var error_tab=false;
    

    if(Dom.get('password').value!='' && Dom.get('password').value!=Dom.get('password_confirmation').value){
	error_tab=true;
	if(Dom.get('password_confirmation').value==''){
	    Dom.get('password_instructions').innerHTML=Dom.get('password_msg1').innerHTML;
	Dom.addClass(['password_confirmation_label','password_confirmation'],'error');
	
	}else{
	    Dom.get('password_instructions').innerHTML=Dom.get('password_msg2').innerHTML;
	    Dom.addClass(['password_label','password','password_confirmation_label','password_confirmation'],'error');
		
	}

    }else{
	Dom.removeClass(['password_label','password','password_confirmation_label','password_confirmation'],'error');

    }



    //    alert('x');
    if(Dom.get('password').value==''){
	Dom.get('password_instructions').innerHTML=Dom.get('password_msg4').innerHTML;
	Dom.addClass(['password_label','password'],'error');
	error_tab=true;
    }
    if(Dom.get('password').value.length<6){
	Dom.get('password_instructions').innerHTML=Dom.get('password_msg3').innerHTML;
	Dom.addClass(['password_label','password'],'error');
	error_tab=true;
	Dom.get('password_confirmation').value='';
	

    }else{
	Dom.removeClass(['password_label','password'],'error');

    }





    return !error_tab;

}

function init(){
   
    
    Event.addListener('submit_email', "click",submit_email);
    
    Event.addListener('customer_type_other', "click",customer_type_other_selected);
    ids=['customer_type_wholesaler','customer_type_big_shop','customer_type_small_shop','customer_type_internet','customer_type_market','customer_type_special'];
    Event.addListener(ids, "click",customer_type_selected);
    Event.addListener('other_type', "keyup",other_type_changed);
    Event.addListener('confirmation_trade_only', "click",confirmation_trade_only_changed);
    Event.addListener('submit_customer_type', "click",submit_customer_type);

    Event.addListener('company', "click",company_choosen);
    Event.addListener('person', "click",person_choosen);
    Event.addListener(['company_name','company_contact','person_contact'],"keyup",name_changed);
  
    Event.addListener('telephone',"keyup",telephone_changed);
    Event.addListener('email_confirmation',"keyup",email_confirmation_changed);
    Event.addListener(['password','password_confirmation'],"keyup",password_changed);



    Event.addListener('submit_details', "click",submit_details);

    Event.addListener('submit', "click",submit);
    
        Event.addListener('password_reminder', "click",send_password_reminder);

    
   
}
Event.onDOMReady(init);

