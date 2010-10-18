 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;




function submit_password(){
    
    if(check_password()){
   var password=sha256_digest(Dom.get('password').value);
	var request='ar_register.php?tipo=change_password&password='+password;
	alert(request);
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	       alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			if(r.result=='ok'){
			    
			    
			    
			    
			    			    Dom.setStyle('password_changed','display','');
			    			    Dom.setStyle('change_password','display','none');


			}else{
						    			    Dom.setStyle('error','display','');

			
			}
			

		    }else{
			  Dom.setStyle('error','display','');
		    }
			

		}
	    
	    });

    }
    
   
}









function init(){
   
    
    Event.addListener('submit_password', "click",submit_password);
    
   

    
   
}



Event.onDOMReady(init);
