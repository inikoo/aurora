<?php
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;

var validate_scope_data=
{
    'company_area':{
	'area_code':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'area_code','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Area Code')?>'}],dbname:'Company Area Code'}
	,'area_name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'area_name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Area Name')?>'}], dbname:'Company Area Name'}
	,'area_description':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'area_description','ar':false,'validation':false, dbname:'Company Area Description'}
}};


var validate_scope_metadata={
'company_area':{'type':'new','ar_file':'ar_edit_contacts.php','key_name':'none','key':1}


};




 function init(){

var company_area_code_oACDS = new YAHOO.util.FunctionDataSource(validate_company_area_code);
    company_area_code_oACDS.queryMatchContains = true;
    var company_area_code_oAutoComp = new YAHOO.widget.AutoComplete("area_code","area_code_Container", company_area_code_oACDS);
    company_area_code_oAutoComp.minQueryLength = 0; 
    company_area_code_oAutoComp.queryDelay = 0.1;

var company_area_name_oACDS1 = new YAHOO.util.FunctionDataSource(validate_company_area_name);
    company_area_name_oACDS1.queryMatchContains = true;
    var company_area_name_oAutoComp1 = new YAHOO.widget.AutoComplete("area_name","area_name_Container", company_area_name_oACDS1);
    company_area_name_oAutoComp1.minQueryLength = 0; 
    company_area_name_oAutoComp1.queryDelay = 0.1;


var company_area_description_oACDS2 = new YAHOO.util.FunctionDataSource(validate_company_area_description);
    company_area_description_oACDS2.queryMatchContains = true;
    var company_area_description_oAutoComp2 = new YAHOO.widget.AutoComplete("area_description","area_description_Container", company_area_description_oACDS2);
    company_area_description_oAutoComp2.minQueryLength = 0; 
    company_area_description_oAutoComp2.queryDelay = 0.1;


 }

YAHOO.util.Event.onDOMReady(init);
