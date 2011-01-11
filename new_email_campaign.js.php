<?php
include_once('common.php');
?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

function cancel_new_email_campaign(){
location.href='marketing.php'
}

function save_new_email_campaign(){

  var request='ar_edit_marketing.php?tipo=create_email_marketing&name='+encodeURIComponent(Dom.get('email_campaign_name').value)+'&objective='+encodeURIComponent(Dom.get('email_campaign_objetive').value);
  alert(request)
  YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		   
		   
		}else{
		    Dom.get('new_store_messages').innerHTML='<span class="error">'+r.msg+'</span>';

		}
	    }
	    
	    });


}



function init(){
    Event.addListener('cancel_new_email_campaign', "click", cancel_new_email_campaign);
    Event.addListener('save_new_email_campaign', "click", save_new_email_campaign);

}

Event.onDOMReady(init);