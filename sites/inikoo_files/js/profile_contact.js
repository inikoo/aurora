var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;
var dialog_quick_edit_Customer_Name;
var validate_scope_metadata;
var validate_scope_data;

 
    


function save_quick_edit_name(){
    save_edit_general_bulk('customer_quick');
}

function show_edit_name(){
	dialog_quick_edit_Customer_Name.show();
}

function validate_customer_name(query){
 validate_general('customer_quick','name',unescape(query));
}

function post_item_updated_actions(branch,r){
	alert('Done')
}

function init(){
	
	 validate_scope_data=
{
    'customer_quick':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Customer_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Customer Name'}]}
	//,'contact':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Contact_Name','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Contact Name')?>'}]}
	//,'telephone':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Telephone','validation':[{'regexp':regex_valid_tel,'invalid_msg':'<?php echo _('Invalid Telephone')?>'}]}
	//,'mobile':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Mobile','validation':[{'regexp':"^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*$",'invalid_msg':'<?php echo _('Invalid Mobile')?>'}]}
    }};


	
 validate_scope_metadata={
'customer_quick':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'customer_key','key':Dom.get('customer_key').value}
};
	
	
dialog_quick_edit_Customer_Name = new YAHOO.widget.Dialog("dialog_quick_edit_Customer_Name", {context:["customer_name","tl","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_quick_edit_Customer_Name.render();


Event.addListener('close_quick_edit_name', "click", dialog_quick_edit_Customer_Name.hide,dialog_quick_edit_Customer_Name , true);


var customer_name_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_name);
customer_name_oACDS.queryMatchContains = true;
var customer_name_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Name","Customer_Name_Container", customer_name_oACDS);
customer_name_oAutoComp.minQueryLength = 0; 
customer_name_oAutoComp.queryDelay = 0.1;

}
Event.onDOMReady(init);