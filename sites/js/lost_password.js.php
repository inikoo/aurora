 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;





function submit_email(){
    
    var email=Dom.get('email').value;
    if(email==''){
	Dom.get('email_instructions').innerHTML=Dom.get('email_error_msg_1').innerHTML;
	Dom.addClass(['email','email_label'],'error');

    }else if(!validate_email(email)){
	msg_id=1+Math.floor(Math.random()*2);
	
	Dom.get('email_instructions').innerHTML=Dom.get('email_not_valid_msg_'+msg_id).innerHTML;
	Dom.addClass(['email','email_label'],'error');
		

    }else{
	var request='ar_register.php?tipo=send_lost_password_email&email='+email;
	
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	       alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			if(r.result=='new'){
			    
			    			    Dom.setStyle('not_registed','display','');

			    
			}else if(r.result=='send'){
			    
			    
			    
			    
			    			    Dom.setStyle('email_send','display','');


			}else{
						    			    Dom.setStyle('error','display','');

			
			}
			

		    }else{
			window.location='register.php?we';
		    }
			

		}
	    
	    });

    }
    
   
}





function submit(){

    Dom.get('telephone').setAttribute('check','yes');

    
    if(data.customer_is_company){
	data.contact_name=Dom.get('company_contact').value;

    }else{
	data.contact_name=Dom.get('person_contact').value;
    }

    data.company_name=Dom.get('company_name').value;
    data.tax_number=Dom.get('company_tax_number').value;

   
    valid_tel=telephone_changed();
    if(!valid_tel){
	Dom.setStyle('final_tel_error_msg','display','');
	Dom.setStyle('final_msg','display','none');

	return;
    }else{
	Dom.setStyle('final_msg','display','');
	Dom.setStyle('final_tel_error_msg','display','none');
    }
    data.password=sha256_digest((Dom.get('password').value));

    data.tel=Dom.get('telephone').value;
    data.country=Dom.get('address_country').value;
    data.country_d1=Dom.get('address_country_d1').value;
    data.country_d2=Dom.get('address_country_d2').value;
    data.postal_code=Dom.get('address_postal_code').value;
    data.town=Dom.get('address_town').value;
    data.town_d1=Dom.get('address_town_d1').value;
    data.town_d2=Dom.get('address_town_d2').value;
    data.street=Dom.get('address_street').value;
    data.building=Dom.get('address_building').value;
    data.internal=Dom.get('address_internal').value;

    data.newsletter=Dom.get('newsletter').checked;
    data.emarketing=Dom.get('emarketing').checked;
    data.catalogue=Dom.get('catalogue').checked;

    var jsonStr = YAHOO.lang.JSON.stringify(data);
    
    Dom.setStyle('submit','display','none');
    Dom.setStyle('wait','display','');

    
    var request='ar_register.php?tipo=register_customer&values='+jsonStr;
    //    alert(request)
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			
		      if(r.action=='found'){

			  Dom.get('registered_email').innerHTML=email;
			    Dom.setStyle('get_email','display','none');
			    Dom.setStyle('found_email','display','');
		      }else if(r.action=='created'){
			  previous_page='index.php';
			  window.location=previous_page+'?wellcome';
			  
		      }
		      
		      

		    }else{
			window.location='register.php?we';
		    }
			

		}
	    
	    });





}



function init(){
   
    
    Event.addListener('submit_email', "click",submit_email);
    
   

    
   
}



Event.onDOMReady(init);
