
 var Event = YAHOO.util.Event;
 var Dom = YAHOO.util.Dom;


 function send_reminder(pid) {
    Dom.setStyle('send_reminder_container_'+pid,'display','none')
    Dom.setStyle('send_reminder_wait_'+pid,'display','')
    
    var request = 'ar_reminders.php?tipo=send_reminder&pid='+pid
  // alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
           // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
              Dom.setStyle('send_reminder_container_'+pid,'display','')
    			Dom.setStyle('send_reminder_wait_'+pid,'display','none')
            
            if (r.state == 200) {
				 Dom.get('send_reminder_container_' + r.pid).innerHTML = r.txt
	 
				 
				 

            } else {
            	 Dom.get('send_reminder_msg_' + r.pid).innerHTML = r.msg
                
            }
        }
    });
}
    



function cancel_send_reminder(esr_key,pid) {
    Dom.setStyle('send_reminder_container_'+pid,'display','none')
    Dom.setStyle('send_reminder_wait_'+pid,'display','')
    
    var request = 'ar_reminders.php?tipo=cancel_send_reminder&esr_key='+esr_key
  
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
//alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
              Dom.setStyle('send_reminder_container_'+pid,'display','')
    			Dom.setStyle('send_reminder_wait_'+pid,'display','none')
            
            if (r.state == 200) {
				 Dom.get('send_reminder_container_' + r.pid).innerHTML = r.txt
	 
				 
				 

            } else {
            	 Dom.get('send_reminder_msg_' + r.pid).innerHTML = r.msg
                
            }
        }
    });
}
    