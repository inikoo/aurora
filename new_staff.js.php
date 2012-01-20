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

function validate_staff_alias(query){ validate_general('staff','alias',unescape(query));}
function validate_staff_name(query){ validate_general('staff','name',unescape(query));}


function radio_changed_staff(o, select_id) {
    parent=o.parentNode;
    Dom.removeClass(parent.getAttribute('prefix')+parent.getAttribute('value'),'selected');

    Dom.addClass(o,'selected');


    parent.setAttribute('value',o.getAttribute('name'));
validate_scope_data['staff'][select_id].changed=true;
validate_scope_data['staff'][select_id].validated=true;

validate_scope_new('staff')
Dom.get(select_id).value=o.getAttribute('name');

}

function reset_new_staff(){
	reset_edit_general('staff')
}

function post_action(branch,response) {

   window.location.href='staff.php?id='+response.staff_id;
}

function save_new_staff(){
 save_new_general('staff');
/*
    get_staff_data();
    var json_value = YAHOO.lang.JSON.stringify(individual_location_data);
    var request='ar_edit_warehouse.php?tipo=new_location&values=' + encodeURIComponent(json_value); 
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	  //alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		
		
		    window.location='location.php?r=nl&id='+r.location_key;
		
		    //reset_location_data();
		    //var table=tables['table0']
		    //var datasource=tables['dataSource0'];    
		    //datasource.sendRequest('',table.onDataReturnInitializeTable, table);
		    
		    
		}else if(r.action=='error'){
		    alert(r.msg);
		}
			    

			
	    }
	});
*/
}



function init(){

validate_scope_data=
{

    'staff':{
	'alias':{'changed':false,'validated':false,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Alias')?>'}],'name':'Staff_Alias'
	    ,'ar':'find','ar_request':'ar_staff.php?tipo=is_staff_alias&query=', 'dbname':'Staff Alias'}
	,'name':{'changed':false,'validated':false,'required':true,'group':1,'type':'item','name':'Staff_Name','ar':false,'dbname':'Staff Name', 'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Name'}]}
	//,'staff_working':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'staff_working','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Name'}]}
	,'staff_supervisor':{'changed':false,'validated':false,'required':true,'dbname':'Staff Is Supervisor','group':1,'type':'item','name':'staff_supervisor','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Name'}]}
,'staff_type':{'changed':false,'validated':false,'required':true,'dbname':'Staff Type','group':1,'type':'item','name':'staff_type','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Name'}]}
	,'staff_position':{'changed':true,'validated':true,'required':true,'dbname':'Position Key','group':1,'type':'item','name':'staff_position','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Name'}]}
,'staff_department':{'changed':true,'validated':true,'required':true,'dbname':'Staff Department Key','group':1,'type':'item','name':'staff_department','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Name'}]}
,'staff_area':{'changed':true,'validated':true,'required':true,'dbname':'Staff Area Key','group':1,'type':'item','name':'staff_area','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Staff Name'}]}
	

	}
};
	
	

	
validate_scope_metadata={
    'staff':{'type':'new','ar_file':'ar_edit_staff.php','key_name':'staff_key'}
    

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


 //  YAHOO.util.Event.addListener('reset_new_staff', "click",reset_new_staff)
   YAHOO.util.Event.addListener('save_new_staff', "click",save_new_staff)
}


YAHOO.util.Event.onDOMReady(init);
