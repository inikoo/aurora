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

function validate_employee_alias(query){

validate_general('employee','alias',unescape(query));


Dom.get('User_Handle').value=query;
validate_general('employee','user_handle',unescape(query));


}
function validate_employee_name(query){ validate_general('employee','name',unescape(query));}

function radio_changed_employee(o, select_id) {

	//alert(select_id)
    parent=o.parentNode;
    
    Dom.removeClass(['employee_type_Employee','employee_type_Temporal_Worker','employee_type_Volunteer','employee_type_Contractor','employee_type_Work_Experience'],'selected');
    
   // Dom.removeClass(parent.getAttribute('prefix')+parent.getAttribute('value'),'selected');

    Dom.addClass(o,'selected');

//alert(select_id)
    parent.setAttribute('value',o.getAttribute('name'));
	validate_scope_data['employee'][select_id].changed=true;
	validate_scope_data['employee'][select_id].validated=true;

	validate_scope_new('employee')
	Dom.get(select_id).value=o.getAttribute('name');

}

function reset_new_employee(){
	reset_edit_general('employee')
}

function post_action(branch,response) {
   window.location.href='edit_employe.php?id='+response.employee_id;
}

function save_new_employee(){
	Dom.setStyle('form_buttons','display','none')
	Dom.setStyle('waiting','display','')

 save_new_general('employee');

}

function display_select_position(){

Dom.setStyle('employee_positions_buttons','display','none')
Dom.setStyle('employee_positions','display','')

}

function select_position(el){
Dom.get('employee_position_key').value=el.value

Dom.setStyle(['employee_positions_buttons','display_select_position_bis'],'display','')
Dom.setStyle(['employee_positions','display_select_position'],'display','none')
Dom.get('selected_position').innerHTML=el.innerHTML
Dom.addClass(el,'selected')

	validate_scope_data['employee']['employee_position_key'].changed=true;
	validate_scope_data['employee']['employee_position_key'].validated=true;

	validate_scope_new('employee')

}

function init(){

   YAHOO.util.Event.addListener(['display_select_position','display_select_position_bis'], "click",display_select_position)



validate_scope_data=
{
    'employee':{
				'alias':{'changed':false,'validated':false,'required':true,'group':1,'type':'item'
	    		,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Alias')?>'}],'name':'Staff_Alias'
	    		,'ar':'find','ar_request':'ar_staff.php?tipo=is_employee_alias&query=', 'dbname':'Staff Alias'}
				,'name':{'changed':false,'validated':false,'required':true,'group':1,'type':'item','name':'Staff_Name','ar':false,'dbname':'Staff Name', 'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Name'}]}
				//,'employee_working':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'employee_working','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Name'}]}
			//	,'employee_supervisor':{'changed':false,'validated':false,'required':false,'dbname':'Staff Is Supervisor','group':1,'type':'item','name':'employee_supervisor','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Name'}]}
				,'employee_type':{'changed':true,'validated':true,'required':false,'dbname':'Staff Type','group':1,'type':'item','name':'employee_type','ar':false,'validation':false}
				,'employee_position_key':{'changed':true,'validated':false,'required':true,'dbname':'Position Key','group':1,'type':'item','name':'employee_position_key','ar':false,'validation':false}
			//	,'employee_department':{'changed':true,'validated':true,'required':false,'dbname':'Staff Department Key','group':1,'type':'item','name':'employee_department','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Name'}]}
		//		,'employee_area':{'changed':true,'validated':true,'required':false,'dbname':'Staff Area Key','group':1,'type':'item','name':'employee_area','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Name'}]}
	
				,'create_uer':{'changed':true,'validated':true,'required':false,'dbname':'create_user','group':1,'type':'item','name':'create_user','ar':false,'validation':false}
,'user_handle':{'changed':false,'validated':false,'required':false,'group':1,'type':'item'
	    		,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid user handle')?>'}],'name':'User Handle'
	    		,'ar':'find','ar_request':'ar_user.php?tipo=is_employee_alias&query=', 'dbname':'Staff Alias'}
	}
};
	
	

	
validate_scope_metadata={
    'employee':{'type':'new','ar_file':'ar_edit_staff.php','key_name':'employee_key'}
    

};





    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_employee_alias);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("Staff_Alias","Staff_Alias_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_employee_name);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("Staff_Name","Staff_Name_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;


 //  YAHOO.util.Event.addListener('reset_new_employee', "click",reset_new_employee)
   YAHOO.util.Event.addListener('save_new_employee', "click",save_new_employee)

}


YAHOO.util.Event.onDOMReady(init);
