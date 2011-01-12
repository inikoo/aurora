<?php
include_once('common.php');
?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

function select_store(store_key){

ids=Dom.getElementsByClassName('option', 'td', 'store_options');
Dom.removeClass(ids,'selected');
Dom.addClass('store_button'+store_key,'selected');
Dom.get('store_key').value=store_key;

}

function cancel_new_email_campaign(){
location.href='marketing.php'
}

function save_new_email_campaign(){

var store_key=Dom.get('store_key').value;
if(store_key==0){
alert('Choose a Store');
return;
}
var email_campaign_name=Dom.get('email_campaign_name').value;
if(email_campaign_name==''){
alert('Choose a name');
return;
}



  var request='ar_edit_marketing.php?tipo=create_email_marketing&store_key='+encodeURIComponent(store_key)+'&name='+encodeURIComponent(email_campaign_name)+'&objective='+encodeURIComponent(Dom.get('email_campaign_objetive').value);
alert(request)
  YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
            
            location.href="email_campaign.php?id="+r.email_campaign_key;
		   
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