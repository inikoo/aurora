<?php
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;

var validate_scope_data=
{
    'company_department':{
	'department_code':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'department_code','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Department Code')?>'}],dbname:'Company Department Code'}
	,'department_name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'department_name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Department Name')?>'}], dbname:'Company Department Name'}
	,'department_description':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'department_description','ar':false,'validation':false, dbname:'Company Department Description'}
}};


var validate_scope_metadata={
'company_department':{'type':'new','ar_file':'ar_edit_contacts.php','key_name':'none','key':1}


};




 function init(){
    var company_department_code_oACDS = new YAHOO.util.FunctionDataSource(validate_company_department_code);
    company_department_code_oACDS.queryMatchContains = true;
    var company_department_code_oAutoComp = new YAHOO.widget.AutoComplete("department_code","department_code_Container", company_department_code_oACDS);
    company_department_code_oAutoComp.minQueryLength = 0; 
    company_department_code_oAutoComp.queryDelay = 0.1;
 }

YAHOO.util.Event.onDOMReady(init);
