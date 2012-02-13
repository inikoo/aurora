var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;


function submit_change_password(){

var error=false;
	if(  Dom.get('change_password_password1').value=='' &&  Dom.get('change_password_password1').value==Dom.get('change_password_password2').value ){
		Dom.addClass(['change_password_password1','change_password_password2'],'error');
		error=true;
		Dom.setStyle('change_password_error_no_password','display','')

	}else{
	Dom.removeClass(['change_password_password1','change_password_password2'],'error');
	Dom.setStyle('change_password_error_no_password','display','none')

	}

	if(!error){
		if( Dom.get('change_password_password1').value!=Dom.get('change_password_password2').value ){
			Dom.addClass(['change_password_password1','change_password_password2'],'error');
			if(!error)
				Dom.setStyle('change_password_error_password_not_march','display','')
				error=true;

		}else{
			Dom.removeClass(['change_password_password1','change_password_password2'],'error');
			Dom.setStyle('change_password_error_password_not_march','display','none')

		}
	}
	if(!error){
		if(!error &&   Dom.get('change_password_password1').value.length<6){
			Dom.addClass(['change_password_password1'],'error');

			if(!error)
				Dom.setStyle('change_password_error_password_too_short','display','')

			
			error=true;
		}else{
			Dom.removeClass(['change_password_password1'],'error');
			Dom.setStyle('change_password_error_password_too_short','display','none')

		}
	}

	if(!error)
	change_password()
}

function change_password(){
remove_change_password_message_blocks();

Dom.setStyle('change_password_buttons','visibility','hidden')
Dom.setStyle('processing_change_password','display','')

    var user_key=Dom.get('user_key').value;
	

    var store_key=Dom.get('store_key').value;
    var site_key=Dom.get('site_key').value;
//	alert(Dom.get('epwcp1').value)
	ep1=AESEncryptCtr(sha256_digest(Dom.get('change_password_password1').value),Dom.get('epwcp1').value,256);
  //   var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 
    ep2=Dom.get('epwcp2').value;
	
//alert(ep1)	
	
//	njZJTjk5OTmzOnIBTJwt8i0K1bb//h4HnojRs+CN0ZmYHxR6F0DQpw8YUCg051J8fj/saZOj+70jYLIuh7OmqjkamiYef5y7
    //njZJTjk5OTmzOnIBTJwt8i0K1bb//h4HnojRs+CN0ZmYHxR6F0DQpw8YUCg051J8fj/saZOj+70jYLIuh7OmqjkamiYef5y7
var url ='http://'+ window.location.host + window.location.pathname;

var data={'user_key':user_key,'store_key':store_key,'site_key':site_key,'ep1':ep1, 'ep2':ep2}


  var json_value = encodeURIComponent(YAHOO.lang.JSON.stringify(data)); 


     var request='ar_profile.php?tipo=change_password&values='+json_value;
//alert(request);
  Dom.setStyle('tr_email_in_db_buttons','display','none');
    Dom.setStyle('tr_forgot_password_wait2','display','');

    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){

             Dom.setStyle('change_password_buttons','visibility','visible')
Dom.setStyle('processing_change_password','display','none')


		        if(r.result=='ok'){
             window.location = 'http://'+ window.location.host+'/logout.php'
                
               cancel_change_password()
   Dom.setStyle('change_password_ok','display','');
               // Dom.setStyle('change_password_form','display','none');
    		    }else{
		        Dom.setStyle('change_password_ok','display','none');
              //  Dom.setStyle('change_password_form','display','');
		        }
		    }else{
		          Dom.setStyle('change_password_ok','display','none');
                Dom.setStyle('change_password_form','display','');
		    }
			

		},failure:function(o){
		Dom.setStyle('change_password_buttons','visibility','visible')
Dom.setStyle('processing_change_password','display','none')
		  //  alert(o)
		}
	    
	    });



}

var submit_change_password_on_enter=function(e){

remove_change_password_message_blocks()

     var key;     
     if(window.event)
         Key = window.event.keyCode; //IE
     else
         Key = e.which; //firefox     

     if (Key == 13){
	 submit_change_password();
	 
	 }
};

function cancel_change_password(){
   Dom.get('change_password_password1').value='';
                Dom.get('change_password_password2').value='';
                Dom.removeClass(['change_password_password1','change_password_password2'],'error');
                Dom.get('change_password_password1').focus();
remove_change_password_message_blocks();
}

function remove_change_password_message_blocks(){
Dom.setStyle(['processing_change_password','change_password_ok','change_password_error_no_password','change_password_error_password_not_march','change_password_error_password_too_short'],'display','none');
}




function init(){


Event.addListener('submit_change_password', "click", submit_change_password);
Event.addListener('change_password_password2', "keydown", submit_change_password_on_enter);
Event.addListener('change_password_password1', "keydown", remove_change_password_message_blocks);

Event.addListener('cancel_change_password', "click", cancel_change_password);

}
Event.onDOMReady(init);