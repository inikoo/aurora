<?php
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;

var validate_scope_data=
{
    'company_position':{
	'position_code':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'position_code','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid position Code')?>'}],dbname:'Company Position Code'}
	,'position_name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'position_name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Department Name')?>'}], dbname:'Company Position Title'}
	,'position_description':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'position_description','ar':false,'validation':false, dbname:'Company Position Description'}
}};


var validate_scope_metadata={
'company_position':{'type':'new','ar_file':'ar_edit_contacts.php','key_name':'none','key':1}


};




 function init(){
    var company_position_code_oACDS = new YAHOO.util.FunctionDataSource(validate_company_position_code);
    company_position_code_oACDS.queryMatchContains = true;
    var company_position_code_oAutoComp = new YAHOO.widget.AutoComplete("position_code","position_code_Container", company_position_code_oACDS);
    company_position_code_oAutoComp.minQueryLength = 0; 
    company_position_code_oAutoComp.queryDelay = 0.1;
 }

YAHOO.util.Event.onDOMReady(init);
