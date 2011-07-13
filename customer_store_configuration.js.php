
<?php
  //@author Migara Ekanayake
  //Copyright (c) 2011 Inikoo
include_once('common.php');

$scope='customer';
$action_after_create='continue';

$store_key=$_REQUEST['store_key'];

print "var scope='$scope';\n";
print "var store_key='$store_key';\n";
print "var action_after_create='$action_after_create';\n";
?>
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

var validate_scope_data=
{
    'custom_field':{'Custom_Field_Name':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':true,'group':0,'type':'item', 'name':'Custom_Field_Name', 'dbname':'Custom Field Name'}
					,'Custom_Field_Store_Key':{'inputed':false,'validated':false,'regexp':"[^\\d]+",'required':false,'group':0,'type':'item', 'name':'Custom_Field_Store_Key', 'dbname':'Custom Field Store Key'}
					,'Custom_Field_Table':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'Custom_Field_Table', 'dbname':'Custom Field Table'}
					,'Custom_Field_Type':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'Custom_Field_Type', 'dbname':'Custom Field Type'}
					,'Custom_Field_In_New_Subject':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'Custom_Field_In_New_Subject', 'dbname':'Custom Field In New Subject'}
					,'Custom_Field_In_Showcase':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'Custom_Field_In_Showcase', 'dbname':'Custom Field In Showcase'}
					,'Default_Value':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'Default_Value', 'dbname':'Default Value'}
	},
	'email_field':{'Email':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':true,'group':0,'type':'item', 'name':'Email', 'dbname':'Email Address'}
					,'Password':{'inputed':false,'validated':false,'regexp':"[^\\d]+",'required':false,'group':0,'type':'item', 'name':'Password', 'dbname':'Password'}
					,'Incoming_Mail_Server':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'Incoming_Mail_Server', 'dbname':'Incoming Mail Server'}
					,'Outgoing_Mail_Server':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'Outgoing_Mail_Server', 'dbname':'Outgoing Mail Server'}
	}
}

var validate_scope_metadata={
 'custom_field':{'type':'new','ar_file':'ar_edit_contacts.php','key_name':'store_key','key':store_key}
,'email_field':{'type':'new','ar_file':'ar_edit_contacts.php','key_name':'store_key','key':store_key}
};

function validate_Email_Field (query) {
	alert(query);
	validate_general('email_field', 'Email', query)
	
};

function validate_Custom_Field_Name (query) {
	alert(query);
	validate_general('custom_field', 'Custom_Field_Name', query)
	
};

function save_new_custom_field()
{
	save_new_general('custom_field');
}

function save_new_email()
{
	save_new_general('email_field');
}


function cancel_add_custom_field(){
	window.location='customers.php';
}




function change_allow(o,key,value){

Dom.get(key).value=value;
Dom.removeClass(Dom.getElementsByClassName('option', 'span', o.parentNode ),'selected');
Dom.addClass(o,'selected');

}

function change_view(){
ids=['new_custom_fields','custom_form','email_config'];
block_ids=['block_new_custom_fields','block_custom_form','block_email_config'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');

Dom.removeClass(ids,'selected');

Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customer_store_configuration-view&value='+this.id ,{});
}

function init(){
    Event.addListener(['new_custom_fields','custom_form','email_config'], "click",change_view);

	Event.addListener("save_new_custom_field", "click", save_new_custom_field , true);
	Event.addListener("cancel_add_custom_field", "click", cancel_add_custom_field , true);
	Event.addListener("cancel_add_email", "click", cancel_add_custom_field , true);	
	Event.addListener("save_new_email", "click", save_new_email , true);	

	
	var Custom_Field_Name_oACDS = new YAHOO.util.FunctionDataSource(validate_Custom_Field_Name);
	Custom_Field_Name_oACDS.queryMatchContains = true;
	var Custom_Field_Name_oAutoComp = new YAHOO.widget.AutoComplete("Custom_Field_Name","Custom_Field_Name_Container", Custom_Field_Name_oACDS);
	Custom_Field_Name_oAutoComp.minQueryLength = 0; 
	Custom_Field_Name_oAutoComp.queryDelay = 0.75;
	
	var Custom_Field_Name_oACDS = new YAHOO.util.FunctionDataSource(validate_Email_Field);
	Custom_Field_Name_oACDS.queryMatchContains = true;
	var Custom_Field_Name_oAutoComp = new YAHOO.widget.AutoComplete("Email","Email_Container", Custom_Field_Name_oACDS);
	Custom_Field_Name_oAutoComp.minQueryLength = 0; 
	Custom_Field_Name_oAutoComp.queryDelay = 0.75;
    } 
YAHOO.util.Event.onDOMReady(init);
