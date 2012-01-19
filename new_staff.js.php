<?php
  //@author Raul Perusquia <rulovico@gmail.com>
  //Copyright (c) 2009 LW
include_once('common.php');
include_once('class.Contact.php');
include_once('class.Company.php');
?>

var contact_data={
    "Contact Name":"" 
    ,"Contact Main Plain Email":""
    ,"Contact Main XHTML Telephone":""
    ,"Contact Address Line 1":""
    ,"Contact Address Line 2":""
    ,"Contact Address Line 3":""
    ,"Contact Address Town":""
    ,"Contact Address Postal Code":""
    ,"Contact Address Country Name":""
    ,"Contact Address Country Code":""
    ,"Contact Address Town Second Division":""
    ,"Contact Address Town First Division":""
    ,"Contact Address Country First Division":""
    ,"Contact Address Country Second Division":""
    ,"Contact Address Country Third Division":""
    ,"Contact Address Country Forth Division":""
    ,"Contact Address Country Fifth Division":""
};  

function validate_staff_alias(){

}

function validate_staff_name(){

}


function init(){

validate_scope_data=
{

    'staff_description':{
	'alias':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Staff_Alias','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Alias'}]}
	,'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Staff_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Name'}]}
	}
    ,'staff_pin':{
	'pin':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Staff_PIN','ar':false,'validation':[{'regexp':"\\d{4}",'invalid_msg':'Invalid PIN'}]}
	,'pin_confirm':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Staff_PIN_Confirm','ar':false,'validation':[{'regexp':"\\d{4}",'invalid_msg':'Invalid PIN'}]}
	}
};
	
	

	
validate_scope_metadata={
    'staff_description':{'type':'new','ar_file':'ar_edit_staff.php','key_name':'staff_key','key':Dom.get('staff_key').value}
	,'staff_pin':{'type':'new','ar_file':'ar_edit_staff.php','key_name':'staff_key','key':Dom.get('staff_key').value}
    

};

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_staff_alias);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("Staff_Alias","Staff_Alias_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_staff_name);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("Staff_Name","Staff_Name_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;


}

function validate_staff_name()
YAHOO.util.Event.onDOMReady(init);
