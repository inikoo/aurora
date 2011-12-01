<?php
include_once('common.php');
?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

var validate_scope_data;
var validate_scope_metadata;



function cancel_new_email_campaign(){
location.href='marketing.php';
}


 function post_new_found_actions(branch,response) {
  Dom.get('new_email_campaign_messages').innerHTML='<span class="error">'+r.msg+'</span>';
}

function save_new_email_campaign(){
save_new_general('email_campaign')
}

function post_new_create_actions(branch,response) {
location.href='email_campaign.php?id='+response.email_campaign_key;
    cancel_new_general(branch);
    return;
}

function validate_email_campaign_name(query){
 validate_general('email_campaign','name',unescape(query));
}
function validate_email_campaign_objective(query){
 validate_general('email_campaign','objective',unescape(query));
}
function init(){
store_key=Dom.get('store_id').value;
validate_scope_data=
{
    'email_campaign':{
	'name':{'dbname':'Email Campaign Name',
	        'changed':false,
	        'validated':false,
	        'required':true,
	        'group':1,
	        'type':'item',
	        'name':'email_campaign_name',
	        'ar':false,
	        'ar':'find','ar_request':'ar_marketing.php?tipo=is_email_campaign_name&store_key='+store_key+'&query=',
	        'validation':[{'regexp':"[a-z\\d]+",
	        'invalid_msg':Dom.get('invalid_email_campaign_name')}]}
	,'objective':{'dbname':'Email Campaign Objective','changed':false,'validated':false,'required':false,'group':1,'type':'item','name':'email_campaign_objective','validation':[{'regexp':"[a-z\\d]+",
	'invalid_msg':Dom.get('invalid_email_campaign_objective')}]}
	
  }
  
};
validate_scope_metadata={
'email_campaign':{'type':'new','ar_file':'ar_edit_marketing.php','key_name':'store_key','key':<?php echo $_SESSION['state']['marketing']['store']?>}

};
 var customer_name_oACDS = new YAHOO.util.FunctionDataSource(validate_email_campaign_name);
    customer_name_oACDS.queryMatchContains = true;
    var customer_name_oAutoComp = new YAHOO.widget.AutoComplete("email_campaign_name","email_campaign_name_Container", customer_name_oACDS);
    customer_name_oAutoComp.minQueryLength = 0; 
    customer_name_oAutoComp.queryDelay = 0.1;
    
    var customer_name_oACDS = new YAHOO.util.FunctionDataSource(validate_email_campaign_objective);
    customer_name_oACDS.queryMatchContains = true;
    var customer_name_oAutoComp = new YAHOO.widget.AutoComplete("email_campaign_objective","email_campaign_objective_Container", customer_name_oACDS);
    customer_name_oAutoComp.minQueryLength = 0; 
    customer_name_oAutoComp.queryDelay = 0.1;
    

    Event.addListener('reset_new_email_campaign', "click", cancel_new_email_campaign);
    Event.addListener('save_new_email_campaign', "click", save_new_email_campaign);

}

Event.onDOMReady(init);