<?php  include_once('common.php');

$money_regex="^[^\\\\d\\\.\\\,]{0,3}(\\\\d{1,3}(\\\,\\\\d{3})*|(\\\\d+))(\\\.\\\\d{2})?$";
print 'var money_regex="'.$money_regex.'";';
$number_regex="^(\\\\d{1,3}(\\\,\\\\d{3})*|(\\\\d+))(\\\.\\\\d{1,})?$";
print 'var number_regex="'.$number_regex.'";';
 ?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var product_pid='<?php echo $_REQUEST['product_id']?>';
var validate_scope_data=
{
    'product_description':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Customer Name')?>'}]}
	,'special_characteristic':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Special_Characteristic','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Special Characteristic')?>'}]}
    	,'description':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Description','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Description')?>'}]}

}
    , 'product_price':{
	'price':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Price','ar':false,'validation':[{'regexp':money_regex,'invalid_msg':'<?php echo _('Invalid Price')?>'}]}
	,'rrp':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_RRP','ar':false,'validation':[{'regexp':money_regex,'invalid_msg':'<?php echo _('Invalid Price')?>'}]}
    }
	
  , 'product_weight':{
	'unit_weight':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Unit_Weight','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Weight')?>'}]}
	,'outer_weight':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Outer_Weight','ar':false,'validation':[{'regexp':number_regex,'invalid_msg':'<?php echo _('Invalid Weight')?>'}]}	

	}

 , 'product_units':{
	'units_per_case':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Units_Per_Case','ar':false,'validation':[{'regexp':"\\d",'invalid_msg':'<?php echo _('Invalid Number')?>'}]}
	,'units_type':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Units_Type','ar':false,'validation':[{'regexp':"\\.+",'invalid_msg':'<?php echo _('Invalid Unit Type')?>'}]}	

	}

    };
var validate_scope_metadata={
    'product_description':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':product_pid}
    ,'product_price':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':product_pid}
    ,'product_weight':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':product_pid}

};

function validate_product_name(query){
 validate_general('product_description','name',unescape(query));
}

function validate_product_special_characteristic(query){
 validate_general('product_description','special_characteristic',unescape(query));
}
function validate_product_description(query){

 validate_general('product_description','description',unescape(query));
}

function validate_product_unit_weight(query){
 validate_general('product_weight','unit_weight',unescape(query));
}
function validate_product_outer_weight(query){
 validate_general('product_weight','outer_weight',unescape(query));
}

function validate_product_price(query){

 validate_general('product_price','price',unescape(query));
 
 if(validate_scope_data.product_price.price.validated){
     var td=Dom.get("price_per_unit");
     var units=parseFloat(td.getAttribute("units"));
     var value=Dom.get(validate_scope_data.product_price.price.name).value;
     price=parseFloat(value.replace(/^[^\d]*/i, ""));
     var rrp=Dom.get(validate_scope_data.product_price.rrp.name).value;
     rrp=parseFloat(rrp.replace(/^[^\d]*/i, ""));

     var cost=parseFloat(td.getAttribute("cost"));
     var old_price=parseFloat(td.getAttribute("old_price"));



     var new_price_per_unit=price/units; 
     Dom.get("price_per_unit").innerHTML=money(new_price_per_unit)+" <?php echo _('per unit')?>";
     Dom.get("price_margin").innerHTML="<?php echo _('Margin')?>: "+percentage(price-cost,price);
     Dom.get("rrp_margin").innerHTML="<?php echo _('Margin')?>: "+percentage(rrp-price,rrp);

     if(price>old_price){
	 diffence="<?php echo _('Price up')?> "+percentage(price-old_price,price);
     }else{
	 diffence="<?php echo _('Price down')?> "+percentage(price-old_price,price);
		 
     }
     
     Dom.get(validate_scope_data.product_price.price.name+"_msg").innerHTML=diffence;
     
 }


}


function validate_product_rrp(query){

 validate_general('product_price','rrp',unescape(query));
}




function change_block(e){
 
	if(this.id=='pictures'){
	    Dom.get('info_name').style.display='';
	}else
	    Dom.get('info_name').style.display='none';

	if(this.id=='xprices'){
	    Dom.get('info_price').style.display='';
	}else
	    Dom.get('info_price').style.display='none';
	Dom.get('d_parts').style.display='none';
	Dom.get('d_pictures').style.display='none';
	Dom.get('d_parts').style.display='none';
	Dom.get('d_prices').style.display='none';
	Dom.get('d_dimat').style.display='none';
	Dom.get('d_config').style.display='none';
	Dom.get('d_description').style.display='none';
	Dom.get('d_web').style.display='none';

	Dom.get('d_'+this.id).style.display='';
	
	var ids = ["description","pictures","prices","parts","dimat","config","web"]; 
	Dom.removeClass(ids,'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=product-edit&value='+this.id,{} );
}

function save_edit_description(){
    save_edit_general('product_description');
}
function reset_edit_description(){
    reset_edit_general('product_description')
}

function save_edit_price(){
    save_edit_general('product_price');
}
function reset_edit_price(){
    reset_edit_general('product_price')
}

function save_edit_weight(){
    save_edit_general('product_weight');
}
function reset_edit_weight(){
    reset_edit_general('product_weight')
}



function init(){
    var ids = ["description","pictures","prices","parts","dimat","config","web"]; 
    Event.addListener(ids, "click", change_block);
    
    Event.addListener('save_edit_product_description', "click", save_edit_description);
    Event.addListener('reset_edit_product_description', "click", reset_edit_description);
    
    Event.addListener('save_edit_product_price', "click", save_edit_price);
    Event.addListener('reset_edit_product_price', "click", reset_edit_price);

    Event.addListener('save_edit_product_weight', "click", save_edit_weight);
    Event.addListener('reset_edit_product_weight', "click", reset_edit_weight);

    
    var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_name);
    product_name_oACDS.queryMatchContains = true;
    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Name","Product_Name_Container", product_name_oACDS);
    product_name_oAutoComp.minQueryLength = 0; 
    product_name_oAutoComp.queryDelay = 0.1;
	
	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_special_characteristic);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Special_Characteristic","Product_Special_Characteristic_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_description);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Description","Product_Description_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_price);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Price","Product_Price_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;

	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_rrp);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_RRP","Product_RRP_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


   var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_unit_weight);
    product_name_oACDS.queryMatchContains = true;
    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Unit_Weight","Product_Unit_Weight_Container", product_name_oACDS);
    product_name_oAutoComp.minQueryLength = 0; 
    product_name_oAutoComp.queryDelay = 0.1;
	
	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_outer_weight);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Outer_Weight","Product_Outer_Weight_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;


}


YAHOO.util.Event.onDOMReady(init);