 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;


function validate_email(address) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   if(reg.test(address) == false) {
      return false;
   }else
       return true;

}



function submit_email(){
    
    var email=Dom.get('email').value;
    if(email==''){
	Dom.get('email_instructions').innerHTML=Dom.get('email_error_msg_1').innerHTML;
       

    }else if(!validate_email(email)){
	msg_id=1+Math.floor(Math.random()*2);
	
	Dom.get('email_instructions').innerHTML=Dom.get('email_not_valid_msg_'+msg_id).innerHTML;


    }else{
	var request='ar_register.php?tipo=check_email&email='+email;
	
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			
		    }else{
			
		    }
			

		}
	    
	    });

    }
    
   
}


function init(){
   
 

  
 Event.addListener('submit_email', "click",submit_email)

}
Event.onDOMReady(init);
