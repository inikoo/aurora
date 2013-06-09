
<?php
  //@author Migara Ekanayake
  //Copyright (c) 2011 Inikoo
include_once('common.php');

?>
var part_sku='4';
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
}}

var validate_scope_metadata={
'custom_field':{'type':'new','ar_file':'ar_edit_contacts.php','key_name':'sku','key':part_sku}
};


function validate_Custom_Field_Name (query) {
	//alert(query);
	validate_general('custom_field', 'Custom_Field_Name', query)
	
};
function save_new_custom_field()
{
	save_new_general('custom_field');
}

function cancel_add_custom_field(){
	window.location='customers.php';
}

function change_allow(o,key,value){

Dom.get(key).value=value;
Dom.removeClass(Dom.getElementsByClassName('option', 'span', o.parentNode ),'selected');
Dom.addClass(o,'selected');

}

function init(){
    
	//YAHOO.util.Event.addListener(['save_new_'+Subject,'save_when_founded','force_new'], "click",save_new_customer);
      //	YAHOO.util.Event.addListener(['cancel_add_'+Subject], "click",cancel_new_company);
	//YAHOO.util.Event.addListener('Company_Name', "blur",name_inputed);
	Event.addListener("save_new_custom_field", "click", save_new_custom_field , true);
	Event.addListener("cancel_add_custom_field", "click", cancel_add_custom_field , true);
	
	var Custom_Field_Name_oACDS = new YAHOO.util.FunctionDataSource(validate_Custom_Field_Name);
	Custom_Field_Name_oACDS.queryMatchContains = true;
	var Custom_Field_Name_oAutoComp = new YAHOO.widget.AutoComplete("Custom_Field_Name","Custom_Field_Name_Container", Custom_Field_Name_oACDS);
	Custom_Field_Name_oAutoComp.minQueryLength = 0; 
	Custom_Field_Name_oAutoComp.queryDelay = 0.75;
    } 
YAHOO.util.Event.onDOMReady(init);
