<?php
  //@author Raul Perusquia <rulovico@gmail.com>
  //Copyright (c) 2009 LW
include_once('common.php');
include_once('class.Contact.php');
include_once('class.Company.php');
?>


	

function validate_part_description(query){ validate_general('part','part_description',unescape(query));}
function validate_gross_weight(query){ validate_general('part','gross_weight',unescape(query));}



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



function save_new_part(){
 save_new_general('part');
}

function post_action(branch,r){
	window.location.href='supplier_product.php?pid='+r.object_key;
}

function init(){

validate_scope_data=
{

    'part':{
	//'product_code':{'changed':false,'validated':false,'required':true,'group':1,'type':'item'
	//    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Product Code')?>'}],'name':'product_code'
	//    ,'ar':'find','ar_request':'ar_suppliers.php?tipo=is_supplier_product_code&supplier_key='+Dom.get('supplier_key').value+'&query=', 'dbname':'Supplier Product Code'}
	//,'units_per_case':{'changed':false,'validated':false,'required':true,'group':1,'type':'item','name':'units_per_case','ar':false,'dbname':'Supplier Product Units Per Case', 'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Units per Case'}]}
	'gross_weight':{'changed':false,'validated':false,'required':true,'dbname':'Part Gross Weight','group':1,'type':'item','name':'gross_weight','ar':false,'validation':[{'regexp':"[\\d]+",'invalid_msg':'Invalid Weight'}]}
,'part_description':{'changed':false,'validated':false,'required':true,'dbname':'Part Unit Description','group':1,'type':'item','name':'part_description','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Part Description'}]}


	}
};
	
	

	
validate_scope_metadata={
    'part':{'type':'new','ar_file':'ar_edit_assets.php','key_name':'sp_key', 'key':Dom.get('sp_key').value}
    

};





    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_part_description);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("part_description","part_description_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_gross_weight);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("gross_weight","gross_weight_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

 //  YAHOO.util.Event.addListener('reset_new_part', "click",reset_new_part)
   YAHOO.util.Event.addListener('save_new_part', "click",save_new_part)


}


YAHOO.util.Event.onDOMReady(init);
