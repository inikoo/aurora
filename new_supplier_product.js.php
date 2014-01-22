<?php
  //@author Raul Perusquia <rulovico@gmail.com>
  //Copyright (c) 2009 LW
include_once('common.php');
include_once('class.Contact.php');
include_once('class.Company.php');
?>




function validate_product_code(query){ validate_general('product','product_code',unescape(query));}
function validate_units_per_case(query){ validate_general('product','units_per_case',unescape(query));}
function validate_case_cost(query){ validate_general('product','case_cost',unescape(query));}
function validate_product_name(query){ validate_general('product','product_name',unescape(query));}
function validate_product_description(query){ validate_general('product','product_description',unescape(query));}


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

function reset_new_employee(){
	reset_edit_general('staff')
}


function save_new_product(){
 save_new_general('product');
}

function post_action(branch,r){
	window.location.href='supplier_product.php?pid='+r.object_key;
}

function init(){

validate_scope_data=
{

    'product':{
	'product_code':{'changed':false,'validated':false,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Product Code')?>'}],'name':'product_code'
	    ,'ar':'find','ar_request':'ar_suppliers.php?tipo=is_supplier_product_code&supplier_key='+Dom.get('supplier_key').value+'&query=', 'dbname':'Supplier Product Code'}
,'product_name':{'changed':false,'validated':false,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Prodct Name')?>'}],'name':'product_name'
	    ,'ar':'find','ar_request':'ar_suppliers.php?tipo=is_supplier_product_name&supplier_key='+Dom.get('supplier_key').value+'&query=', 'dbname':'Supplier Product Name'}
	,'units_per_case':{'changed':false,'validated':false,'required':true,'group':1,'type':'item','name':'units_per_case','ar':false,'dbname':'Supplier Product Units Per Case', 'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Units per Case'}]}
	,'case_cost':{'changed':false,'validated':false,'required':true,'dbname':'Supplier Product Cost Per Case','group':1,'type':'item','name':'case_cost','ar':false,'validation':[{'regexp':"[\\d]+",'invalid_msg':'Invalid Case Cost'}]}
,'product_description':{'changed':false,'validated':false,'required':true,'dbname':'Supplier Product Description','group':1,'type':'item','name':'product_description','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Product Description'}]}


	}
};
	
	

	
validate_scope_metadata={
    'product':{'type':'new','ar_file':'ar_edit_suppliers.php','key_name':'supplier_key', 'key':Dom.get('supplier_key').value}
    

};





    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_code);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_code","product_code_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_units_per_case);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("units_per_case","units_per_case_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_case_cost);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("case_cost","case_cost_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_name);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_name","product_name_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_description);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_description","product_description_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;


 //  YAHOO.util.Event.addListener('reset_new_employee', "click",reset_new_employee)
   YAHOO.util.Event.addListener('save_new_product', "click",save_new_product)
}


YAHOO.util.Event.onDOMReady(init);
