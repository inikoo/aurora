<?php  include_once('common.php');

$money_regex="^[^\\\\d\\\.\\\,]{0,3}(\\\\d{1,3}(\\\,\\\\d{3})*|(\\\\d+))(\\\.\\\\d{2})?$";
print 'var money_regex="'.$money_regex.'";';
$number_regex="^(\\\\d{1,3}(\\\,\\\\d{3})*|(\\\\d+))(\\\.\\\\d{1,})?$";
print 'var number_regex="'.$number_regex.'";';

$parts=preg_split('/\,/',$_REQUEST['parts']);



$_parts='';
foreach($parts as $part){
    if($part)
    $_parts.="'sku$part':{sku : $part, new:false, deleted:false } ,";
}
$_parts=preg_replace("/\,$/","",$_parts);
print "\nvar part_list={ $_parts };";




 ?>
//alert(Dom.get('store_key').value);
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

var scope='product';

var dialog_family_list;
var dialog_part_list;

var Editor_change_part;
var GeneralDescriptionEditor;
var HealthAndSafetyEditor;

var dialog_link_health_and_safety;
var dialog_link_tariff_code;
var dialog_link_properties;

function show_delete_product_dialog(){


}

function delete_product() {

    var request = 'ar_edit_assets.php?tipo=delete_product&pid=' + Dom.get('product_pid').value
    // alert(request);
    if (confirm('Are you sure, you want to delete product ' + Dom.get('product_pid').value + ' now?')) {
        YAHOO.util.Connect.asyncRequest('POST', request, {
            success: function(o) {
                alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {
                    window.location.href = 'family.php?id=' + r.family_key;
                } else {
                    alert(r.msg);
                }
            }
        });
    }

}




function change_state(newval, oldval){


	var request = 'ar_edit_assets.php?tipo=edit_product&key=web_configuration&newvalue='+newval+'&oldvalue='+oldval+'&pid=' + Dom.get('product_pid').value
	 //alert(request);
//return;
	YAHOO.util.Connect.asyncRequest('POST', request, {
		success: function(o) {
			//alert(o.responseText);
			var r = YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {				
				window.location.reload();
			} else {
				alert(r.msg);
			}
		}
	});	
}

function validate_product_name(query){
 validate_general('product_description','name',unescape(query));
}
function validate_product_code(query){
 validate_general('product_description','code',unescape(query));
}

function validate_Product_Barcode_Data(query){
 validate_general('product_description','Barcode_Data',unescape(query));
}



function validate_Product_Tariff_Code(query) {
	validate_general('product_description', 'Product_Tariff_Code', query);
}

function validate_Product_Duty_Rate(query) {
	validate_general('product_description', 'Product_Duty_Rate', query);
}


function validate_product_units(query){
 validate_general('product_description','units_per_case',unescape(query));
}


function validate_product_special_characteristic(query){
 validate_general('product_description','special_characteristic',unescape(query));
}
function validate_product_description(query){

 validate_general('product_description','description',unescape(query));
}

function validate_product_unit_weight(query){
 validate_general('product_properties','Product_XHTML_Unit_Weight',unescape(query));
}
function validate_product_package_weight(query){
 validate_general('product_properties','Product_XHTML_Package_Weight',unescape(query));
}
function validate_product_unit_dimensions(query){
 validate_general('product_properties','Product_XHTML_Unit_Dimensions',unescape(query));
}
function validate_product_package_dimensions(query){
 validate_general('product_properties','Product_XHTML_Package_Dimensions',unescape(query));
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


function validate_Product_UN_Number(query) {
	validate_general('product_health_and_safety', 'Product_UN_Number', query);
}


function validate_Product_UN_Class(query) {
	validate_general('product_health_and_safety', 'Product_UN_Class', query);
}


function validate_Product_Proper_Shipping_Name(query) {
	validate_general('product_health_and_safety', 'Product_Proper_Shipping_Name', query);
}

function validate_Product_Hazard_Indentification_Number(query) {
	validate_general('product_health_and_safety', 'Product_Hazard_Indentification_Number', query);
}

function validate_Product_HAS_Description(query) {
	validate_general('product_health_and_safety', 'has_description', query);
}


function post_item_updated_actions(branch, r) {

    if (r.key == 'name') {
        Dom.get('product_name_title').innerHTML = r.newvalue
    } else if (r.key == 'code') {
        Dom.get('product_code_title').innerHTML = r.newvalue
    }

    table_id = 0
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';

    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

}

function change_block(e){
 
 	var ids = ["description","parts","web"]; 
 	var block_ids = ["d_description","d_parts","d_web"]; 

 
	
	
	Dom.setStyle(block_ids,'display','none');
		Dom.setStyle('d_'+this.id,'display','');

	

	
	
	Dom.removeClass(ids,'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=product-edit&value='+this.id,{} );
}


function change_properties_block(e) {

	var ids = ["description_block_family","description_block_type","description_block_description", "description_block_properties", "description_block_info","description_block_pictures","description_block_price","description_block_health_and_safety"];
	var block_ids = [ "d_description_block_family","d_description_block_type","d_description_block_description","d_description_block_properties", "d_description_block_info", "d_description_block_pictures","d_description_block_price","d_description_block_health_and_safety"];

	Dom.setStyle(block_ids, 'display', 'none');
	
	
	
	block_id=this.getAttribute('block_id');
	
	
	Dom.setStyle('d_description_block_' + block_id, 'display', '');


	Dom.removeClass(ids, 'selected');
	Dom.addClass(this, 'selected');

	YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=product-edit_description_block&value=' + block_id, {});

	
}

function save_edit_description(){
    save_edit_general('product_description');
}


function reset_edit_description() {
    reset_edit_general('product_description')

    val = Dom.get('Product_Unit_Type').getAttribute('ovalue')
    sel = Dom.get('Product_Unit_Type_Select')
    for (var i, j = 0; i = sel.options[j]; j++) {
        if (i.value == val) {
            sel.selectedIndex = j;
            break;
        }
    }
    Dom.get('Product_Unit_Type').value = val;

    type = Dom.get('Product_Barcode_Type').getAttribute('ovalue')

    options = Dom.getElementsByClassName('option', 'button', 'Product_Barcode_Type_options')

    Dom.removeClass(options, 'selected')
    Dom.addClass('Product_Barcode_Type_option_' + type, 'selected')


    if (type == 'none') {
        Dom.setStyle(['Product_Barcode_Data_Source_tr', 'Product_Barcode_Data_tr'], 'display', 'none')

    } else {
        Dom.setStyle('Product_Barcode_Data_Source_tr', 'display', '')
        if (Dom.get('Product_Barcode_Data_Source').value == 'Other') {
            Dom.setStyle('Product_Barcode_Data_tr', 'display', '')
        } else {
            Dom.setStyle('Product_Barcode_Data_tr', 'display', 'none')

        }
    }

    barcode_data_source = Dom.get('Product_Barcode_Data_Source').getAttribute('ovalue')

    options = Dom.getElementsByClassName('option', 'button', 'Product_Barcode_Data_Source_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass('Product_Barcode_Data_Source_option_' + barcode_data_source, 'selected')


    if (barcode_data_source == 'Other') {
        Dom.setStyle('Product_Barcode_Data_tr', 'display', '')
    } else {
        Dom.setStyle('Product_Barcode_Data_tr', 'display', 'none')
    }
}



function save_edit_price(){
    save_edit_general('product_price');
}
function reset_edit_price(){
    reset_edit_general('product_price')
}

function save_edit_weight(){
    save_edit_general('product_properties');
}
function reset_edit_weight(){
    reset_edit_general('product_properties')
}

function save_edit_product_units(){
    save_edit_general('product_units');
}
function reset_edit_product_units(){
    reset_edit_general('product_units')
}


function save_edit_product_health_and_safety() {
HealthAndSafetyEditor.saveHTML();
	save_edit_general('product_health_and_safety');
}



function reset_edit_product_health_and_safety() {
	reset_edit_general('product_health_and_safety')
    HealthAndSafetyEditor.setEditorHTML(Dom.get('Product_Health_And_Safety').value);
}

function save_edit_product_general_description() {
GeneralDescriptionEditor.saveHTML();
	save_edit_general('product_general_description');
}



function reset_edit_product_general_description() {
	reset_edit_general('product_general_description')
	
    GeneralDescriptionEditor.setEditorHTML(Dom.get('Product_Description').value);
}


function change_unit_type(o){

    var chosenoption = o.options[o.selectedIndex]

    value = chosenoption.value;
    validate_scope_data['product_description']['unit_type']['value'] = value;
    Dom.get('Product_Unit_Type').value = value
    ovalue = Dom.get('Product_Unit_Type').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['product_description']['unit_type']['changed'] = true;
    } else {
        validate_scope_data['product_description']['unit_type']['changed'] = false;
    }
    
    validate_scope('product_description')

}


function select_family(oArgs){

family_key=tables.table2.getRecord(oArgs.target).getData('key');
 dialog_family_list.hide();

	var request = 'ar_edit_assets.php?tipo=edit_product&key=' + 'family_key' + '&newvalue=' + family_key+ '&pid=' + Dom.get('product_pid').value
	 //alert(request);

	YAHOO.util.Connect.asyncRequest('POST', request, {
		success: function(o) {
			//alert(o.responseText);
			var r = YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {				
				Dom.get('current_family_code').innerHTML=r.newdata['code'];
			} else {
				}
		}
	});
}

function reset_part(key){

for(part_key in part_list){
	
	if(part_list[part_key].new  ){
		to_delete=Dom.get('part_list'+part_list[part_key].sku);
		to_delete.parentNode.removeChild(to_delete);
				to_delete=Dom.get('sup_tr2_'+part_list[part_key].sku);
		to_delete.parentNode.removeChild(to_delete);
				to_delete=Dom.get('sup_tr3_'+part_list[part_key].sku);
		to_delete.parentNode.removeChild(to_delete);
		
	}else if (part_list[part_key].deleted){

	}else{
		key=part_list[part_key].sku;
		Dom.get('parts_per_product'+key).value=Dom.get('parts_per_product'+key).getAttribute('ovalue')
		Dom.get('pickers_note'+key).value=Dom.get('pickers_note'+key).getAttribute('ovalue');
	}

}

part_render_save_buttons();

}

function save_part() {


    key = Dom.get("product_part_items").getAttribute("product_part_key");

    for (part_key in part_list) {
        part_list[part_key].ppp = Dom.get('parts_per_product' + part_list[part_key].sku).value;
        part_list[part_key].note = Dom.get('pickers_note' + part_list[part_key].sku).value;

    }
    json_value = YAHOO.lang.JSON.stringify(part_list);
    var request = 'ar_edit_assets.php?tipo=edit_part_list&key=' + key + '&newvalue=' + json_value + '&pid=' + Dom.get('product_pid').value;
    //alert(request);
    
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                if (r.new) {
                    //window.location.reload(true);
                    location.href = 'edit_product.php?pid=' + r.newvalue + '&new';
                } else if (r.changed) {

                    if (r.newvalue['Product Part Key'] != undefined) {
                        window.location.reload(true);
                        return;
                    }

                    for (sku in r.newvalue.items) {

                        if (r.newvalue.items[sku]['Product Part List Note'] != undefined)
                        Dom.get('pickers_note' + sku).value = r.newvalue.items[sku]['Product Part List Note'];
                        Dom.get('pickers_note' + sku).setAttribute('ovalue', r.newvalue.items[sku]['Product Part List Note']);
                    }
                }
                reset_part(key)
            } else {
            }

        }

    });



}


function part_render_save_buttons(){
var validated=true;
var changed=false;

 Dom.setStyle('reset_edit_part','visibility','hidden');
 Dom.setStyle('save_edit_part','visibility','hidden');

for(part_key in part_list){

if(part_list[part_key].new || ( !part_list[part_key].new && part_list[part_key].deleted  )  ){
changed=true;
}else{
if(Dom.get('parts_per_product'+part_list[part_key].sku).value!=Dom.get('parts_per_product'+part_list[part_key].sku).getAttribute('ovalue'))changed=true;
if(Dom.get('pickers_note'+part_list[part_key].sku).value!=Dom.get('pickers_note'+part_list[part_key].sku).getAttribute('ovalue'))changed=true;
}

if(!part_list[part_key].deleted ){
  if(!validate_parts_per_product(part_list[part_key].sku))
      validated=false;

}

}

if( changed){
    Dom.setStyle('reset_edit_part','visibility','visible');
}
if(validated && changed){
      Dom.setStyle('save_edit_part','visibility','visible');
}




}

function validate_parts_per_product(key) {
    var value = Dom.get('parts_per_product' + key).value;


 

    if (!is_numeric(value)) {
        msg = Dom.get('No_numeric_value').value;
            Dom.get("parts_per_product_msg" + key).innerHTML = msg;

         return false;
    }

    if (value == 0 || value < 0) {
        msg =  Dom.get('Invalid_value').value;
            Dom.get("parts_per_product_msg" + key).innerHTML = msg;

         return false;
    }
 Dom.get("parts_per_product_msg" + key).innerHTML = '';
    return true;

}


function part_changed(o){
part_render_save_buttons();
}

/*
function goto_search_result(subject){
elements_array=Dom.getElementsByClassName('selected', 'tr', subject+'_search_results_table');

tr=elements_array[0];
if(tr!= undefined)

var data={
sku:tr.getAttribute('key')
,fsku:tr.getAttribute('sku')
,description:tr.getAttribute('description')
};

select_part_from_list(data)

}
function go_to_result(){
var data={
sku:this.getAttribute('key')
,fsku:this.getAttribute('sku')
,description:this.getAttribute('description')
};

select_part_from_list(data)

}
*/

function select_part_from_list(oArgs){

sku=tables.table1.getRecord(oArgs.target).getData('sku')


formated_sku=tables.table1.getRecord(oArgs.target).getData('formated_sku')
parts_per_product=1;
note='';
description=tables.table1.getRecord(oArgs.target).getData('description')


part_list['sku'+sku]={'sku':sku,'new':true,'deleted':false};




 oTbl=Dom.get('part_editor_table');
         

 
    oTR= oTbl.insertRow(-1);
    
               
    
    oTR.id='part_list'+sku;
  
    oTR.setAttribute('sku',sku);
 
    Dom.addClass(oTR,'top'); Dom.addClass(oTR,'title');

    var oTD= oTR.insertCell(0);
    oTD.innerHTML=  '<?php echo _('Part')?>';
    Dom.addClass(oTD,'label');
 
    var oTD= oTR.insertCell(1);
    Dom.addClass(oTD,'sku');
    oTD.innerHTML='<span class="id">'+formated_sku+'</span> '+description;
    //Dom.setStyle(oTD, 'width', '120px');
        
   oTD.colSpan = 2;
  
    var oTD= oTR.insertCell(2);
    Dom.setStyle(oTD,'text-align','right');
    oTD.innerHTML='<span style="cursor:pointer" onClick="remove_part('+sku+')" ><img src="art/icons/delete_bw.png"/> <?php echo _('Remove')?></span><span onClick="show_change_part_dialog('+sku+',this)"  style="display:none;cursor:pointer;margin-left:15px"><img  src="art/icons/arrow_refresh_bw.png"/> <?php echo _('Change')?></span>';
    oTR= oTbl.insertRow(-1);
      oTR.id="sup_tr2_"+sku;
  var oTD= oTR.insertCell(0);
    oTD.innerHTML=  '<?php echo _('Parts Per Product')?>:';
    Dom.addClass(oTD,'label');
    
   var oTD= oTR.insertCell(1);
   oTD.setAttribute('colspan',3);
   oTD.innerHTML='<input style="padding-left:2px;text-align:left;width:3em" value="'+parts_per_product+'" onblur="part_changed(this)"  onkeyup="part_changed(this)" ovalue="'+parts_per_product+'" id="parts_per_product'+sku+'"> <span  id="parts_per_product_msg'+sku+'"></span>';
   
     oTR= oTbl.insertRow(-1);
     oTR.id="sup_tr3_"+sku;
         Dom.addClass(oTR,'last');


  var oTD= oTR.insertCell(0);
    oTD.innerHTML=  '<?php echo _('Notes For Pickers')?>:';
    Dom.addClass(oTD,'label');
    
   var oTD= oTR.insertCell(1);
   oTD.setAttribute('colspan',3);
       Dom.setStyle(oTD, 'text-align', 'left');

   oTD.innerHTML='<input id="pickers_note'+sku+'" style=";width:400px"   onblur="part_changed(this)"  onkeyup="part_changed(this)"     value="'+note+'" ovalue="'+note+'" >';

part_render_save_buttons();


dialog_part_list.hide()
}





function change_barcode_type(o, type) {
    options = Dom.getElementsByClassName('option', 'button', 'Product_Barcode_Type_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    if (type == 'none') {
        Dom.setStyle(['Product_Barcode_Data_Source_tr', 'Product_Barcode_Data_tr'], 'display', 'none')

    } else {
        Dom.setStyle('Product_Barcode_Data_Source_tr', 'display', '')
        if (Dom.get('Product_Barcode_Data_Source').value == 'Other') {
            Dom.setStyle('Product_Barcode_Data_tr', 'display', '')
        } else {
            Dom.setStyle('Product_Barcode_Data_tr', 'display', 'none')
            Dom.get('Product_Barcode_Data').value = Dom.get('Product_Barcode_Data').getAttribute('ovalue')
            validate_scope_data['product_description']['Barcode_Type']['changed'] = false;
        }
    }
    value = type;
    ovalue = Dom.get('Product_Barcode_Type').getAttribute('ovalue');
    validate_scope_data['product_description']['Barcode_Type']['value'] = value;
    Dom.get('Product_Barcode_Type').value = value

    if (ovalue != value) {
        validate_scope_data['product_description']['Barcode_Type']['changed'] = true;
    } else {
        validate_scope_data['product_description']['Barcode_Type']['changed'] = false;
    }
    validate_scope('product_description')
}

function change_barcode_data_source(o, type) {
    options = Dom.getElementsByClassName('option', 'button', 'Product_Barcode_Data_Source_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    if (type == 'Other') {
        Dom.setStyle('Product_Barcode_Data_tr', 'display', '')
        Dom.get("Product_Barcode_Data").value = Dom.get("Product_Barcode_Data").getAttribute('ovalue')


    } else {
        Dom.setStyle('Product_Barcode_Data_tr', 'display', 'none')
        Dom.get("Product_Barcode_Data").value = '';

    }

    value = type;
    ovalue = Dom.get('Product_Barcode_Data_Source').getAttribute('ovalue');
    validate_scope_data['product_description']['Barcode_Data_Source']['value'] = value;
    Dom.get('Product_Barcode_Data_Source').value = value

    if (ovalue != value) {
        validate_scope_data['product_description']['Barcode_Data_Source']['changed'] = true;
    } else {
        validate_scope_data['product_description']['Barcode_Data_Source']['changed'] = false;
    }
    validate_scope('product_unit')
}


function change_packing_group(o, type) {
    options = Dom.getElementsByClassName('option', 'button', 'Product_Packing_Group_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    value = type;
    ovalue = Dom.get('Product_Packing_Group').getAttribute('ovalue');
    validate_scope_data['product_health_and_safety']['Product_Packing_Group']['value'] = value;
    Dom.get('Product_Packing_Group').value = value

    if (ovalue != value) {
        validate_scope_data['product_health_and_safety']['Product_Packing_Group']['changed'] = true;
    } else {
        validate_scope_data['product_health_and_safety']['Product_Packing_Group']['changed'] = false;
    }
    validate_scope('product_health_and_safety')
}

function change_package_type(o, type) {
    options = Dom.getElementsByClassName('option', 'button', 'Product_Package_Type_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    value = type;
    ovalue = Dom.get('Product_Package_Type').getAttribute('ovalue');
    validate_scope_data['product_properties']['Product_Package_Type']['value'] = value;
    Dom.get('Product_Package_Type').value = value

    if (ovalue != value) {
        validate_scope_data['product_properties']['Product_Package_Type']['changed'] = true;
    } else {
        validate_scope_data['product_properties']['Product_Package_Type']['changed'] = false;
    }
    validate_scope('product_properties')
}


function general_description_editor_changed() {

    validate_scope_data['product_general_description']['Product_Description']['changed'] = true;

   validate_scope('product_general_description')
 
}

function health_and_safety_editor_changed() {

    validate_scope_data['product_health_and_safety']['Product_Health_And_Safety']['changed'] = true;
    validate_scope('product_health_and_safety')
}


function unlock_product_health_and_safety() {

    Dom.setStyle('unlock_product_health_and_safety', 'display', 'none')
    Dom.setStyle('lock_product_health_and_safety_wait', 'display', '')

    key = 'Product Use Part H and S';
    var request = 'ar_edit_assets.php?tipo=edit_product&key=' + key + '&newvalue=No&pid=' + Dom.get('product_pid').value;
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('Product_UN_Number').disabled = false
                Dom.get('Product_UN_Class').disabled = false
                Dom.get('Product_Proper_Shipping_Name').disabled = false
                Dom.get('Product_Hazard_Indentification_Number').disabled = false
                Dom.setStyle('Product_Packing_Group_locked', 'display', 'none')
                Dom.setStyle('Product_Packing_Group_options', 'display', '')
                Dom.setStyle('edit_product_health_and_safety_buttons_tr', 'display', '')
                Dom.setStyle('lock_product_health_and_safety_wait', 'display', 'none')
                Dom.setStyle('lock_product_health_and_safety', 'display', '')
                Dom.setStyle('product_health_and_safety_editor_tbody', 'display', '')
                Dom.setStyle('product_health_and_safety_part_link', 'display', 'none')
                
            } else {}

        }

    });
}

function lock_product_health_and_safety() {

    Dom.setStyle(['lock_product_health_and_safety', 'lock_product_health_and_safety_buttons'], 'display', 'none')
    Dom.setStyle(['lock_product_health_and_safety_wait', 'locking_product_health_and_safety_wait'], 'display', '')

    key = 'Product Use Part H and S';
    var request = 'ar_edit_assets.php?tipo=edit_product&key=' + key + '&newvalue=Yes&pid=' + Dom.get('product_pid').value;
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            // alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('Product_UN_Number').disabled = true
                Dom.get('Product_UN_Class').disabled = true
                Dom.get('Product_Proper_Shipping_Name').disabled = true
                Dom.get('Product_Hazard_Indentification_Number').disabled = true
                Dom.setStyle('Product_Packing_Group_locked', 'display', '')
                Dom.setStyle('Product_Packing_Group_options', 'display', 'none')
                Dom.setStyle('edit_product_health_and_safety_buttons_tr', 'display', 'none')
                Dom.setStyle(['lock_product_health_and_safety_wait', 'locking_product_health_and_safety_wait'], 'display', 'none')
                Dom.setStyle('lock_product_health_and_safety', 'display', 'none')
                Dom.setStyle('unlock_product_health_and_safety', 'display', '')
                Dom.setStyle('lock_product_health_and_safety_buttons', 'display', '')


                Dom.get('product_health_and_safety_part_link').innerHTML = r.xhtml_part_links;


                Dom.setStyle('product_health_and_safety_editor_tbody', 'display', 'none')
                hide_dialog_link_health_and_safety();


                for (x in r.data) {
                    Dom.get(x).setAttribute('ovalue', r.data[x])
                }
                reset_edit_general('product_health_and_safety')
                HealthAndSafetyEditor.setEditorHTML(Dom.get('Product_Health_And_Safety').value);


            } else {}

        }

    });

}

function show_product_health_and_safety_editor() {
    Dom.setStyle('show_product_health_and_safety_editor', 'display', 'none')
    Dom.setStyle('product_health_and_safety_editor_tr', 'display', '')
    Dom.setStyle('edit_product_health_and_safety_buttons', 'margin-left', '700px')
}

function show_dialog_link_health_and_safety() {

    region1 = Dom.getRegion('product_health_and_safety_title_td');
    region2 = Dom.getRegion('dialog_link_health_and_safety');
    var pos = [region1.left + 8, region1.bottom - 2]
    Dom.setXY('dialog_link_health_and_safety', pos);
    dialog_link_health_and_safety.show()
}

function hide_dialog_link_health_and_safety() {
    dialog_link_health_and_safety.hide()
}

function unlock_product_tariff_code() {

    Dom.setStyle('unlock_product_tariff_code', 'display', 'none')
    Dom.setStyle('lock_product_tariff_code_wait', 'display', '')

    key = 'Product Use Part Tariff Data';
    var request = 'ar_edit_assets.php?tipo=edit_product&key=' + key + '&newvalue=No&pid=' + Dom.get('product_pid').value;
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('Product_Tariff_Code').disabled = false
                Dom.get('Product_Duty_Rate').disabled = false
            
                Dom.setStyle('edit_product_tariff_code_buttons_tr', 'display', '')
                Dom.setStyle('lock_product_tariff_code_wait', 'display', 'none')
                Dom.setStyle('lock_product_tariff_code', 'display', '')
                Dom.setStyle('product_tariff_code_part_link', 'display', 'none')
                
            } else {}

        }

    });
}

function lock_product_tariff_code() {

    Dom.setStyle(['lock_product_tariff_code', 'lock_product_tariff_code_buttons'], 'display', 'none')
    Dom.setStyle(['lock_product_tariff_code_wait', 'locking_product_tariff_code_wait'], 'display', '')

    key = 'Product Use Part Tariff Data';
    var request = 'ar_edit_assets.php?tipo=edit_product&key=' + key + '&newvalue=Yes&pid=' + Dom.get('product_pid').value;
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            // alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('Product_Tariff_Code').disabled = true
                Dom.get('Product_Duty_Rate').disabled = true
 
                
                Dom.setStyle('edit_product_tariff_code_buttons_tr', 'display', 'none')
                Dom.setStyle(['lock_product_tariff_code_wait', 'locking_product_tariff_code_wait'], 'display', 'none')
                Dom.setStyle('lock_product_tariff_code', 'display', 'none')
                Dom.setStyle('unlock_product_tariff_code', 'display', '')
                Dom.setStyle('lock_product_tariff_code_buttons', 'display', '')
				
				                Dom.setStyle('product_tariff_code_part_link', 'display', '')

				
				
                Dom.get('product_tariff_code_part_link').innerHTML = r.xhtml_part_links;


                hide_dialog_link_tariff_code();


                for (x in r.data) {
                    Dom.get(x).setAttribute('ovalue', r.data[x])
                    Dom.get(x).value=r.data[x];
                    validate_scope_data['product_description'][x]['changed'] = false;

                }
                
    
                validate_scope('product_description')


            } else {}

        }

    });

}



function unlock_product_properties() {
    Dom.setStyle('unlock_product_properties', 'display', 'none')
    Dom.setStyle('lock_product_properties_wait', 'display', '')

    key = 'Product Use Part Properties';
    var request = 'ar_edit_assets.php?tipo=edit_product&key=' + key + '&newvalue=No&pid=' + Dom.get('product_pid').value;
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
           // alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
            
            
            
            
                Dom.get('Product_XHTML_Package_Weight').disabled = false
                Dom.get('Product_XHTML_Unit_Weight').disabled = false
                Dom.get('Product_XHTML_Package_Dimensions').disabled = false
                Dom.get('Product_XHTML_Unit_Dimensions').disabled = false
            
             Dom.setStyle('Product_Package_Type_locked', 'display', 'none')
                Dom.setStyle('Product_Package_Type_options', 'display', '')
            
                Dom.setStyle('edit_product_properties_buttons_tr', 'display', '')
                Dom.setStyle('lock_product_properties_wait', 'display', 'none')
                Dom.setStyle('lock_product_properties', 'display', '')
                Dom.setStyle('product_properties_part_link', 'display', 'none')
                Dom.setStyle('product_properties_part_unlinked_msg', 'display', '')
                
                
                
            } else {}

        }

    });
}

function lock_product_properties() {

    Dom.setStyle(['lock_product_properties', 'lock_product_properties_buttons'], 'display', 'none')
    Dom.setStyle(['lock_product_properties_wait', 'locking_product_properties_wait'], 'display', '')

    key = 'Product Use Part Properties';
    var request = 'ar_edit_assets.php?tipo=edit_product&key=' + key + '&newvalue=Yes&pid=' + Dom.get('product_pid').value;
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
          //   alert(o.responseText);
          //  return;
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                
                
                Dom.get('Product_XHTML_Package_Weight').disabled = true
                Dom.get('Product_XHTML_Unit_Weight').disabled = true
                if(r.ratio==1)
                Dom.get('Product_XHTML_Package_Dimensions').disabled = true
                if(r.units_ratio==1)
                Dom.get('Product_XHTML_Unit_Dimensions').disabled = true
 
  if(r.ratio==1 && r.units_ratio==1)
                  Dom.setStyle('edit_product_properties_buttons_tr', 'display', 'none')

 
                 Dom.setStyle('Product_Package_Type_locked', 'display', '')
                Dom.setStyle('Product_Package_Type_options', 'display', 'none')
                Dom.setStyle(['lock_product_properties_wait', 'locking_product_properties_wait'], 'display', 'none')
                Dom.setStyle('lock_product_properties', 'display', 'none')
                Dom.setStyle('unlock_product_properties', 'display', '')
                Dom.setStyle('lock_product_properties_buttons', 'display', '')
				
				Dom.setStyle('product_properties_part_link', 'display', '')
                Dom.setStyle('product_properties_part_unlinked_msg', 'display', 'none')

				
				
                Dom.get('product_properties_part_link').innerHTML = r.xhtml_part_links;


                hide_dialog_link_properties();


                for (x in r.data) {
                    Dom.get(x).setAttribute('ovalue', r.data[x])
                    Dom.get(x).value=r.data[x];
                    validate_scope_data['product_properties'][x]['changed'] = false;

                }
                
    
                validate_scope('product_properties')


            } else {}

        }

    });

}


function show_product_tariff_code_editor() {
    Dom.setStyle('show_product_tariff_code_editor', 'display', 'none')
    Dom.setStyle('product_tariff_code_editor_tr', 'display', '')
    Dom.setStyle('edit_product_tariff_code_buttons', 'margin-left', '700px')
}

function show_dialog_link_tariff_code() {

    region1 = Dom.getRegion('product_tariff_code_title_td');
    region2 = Dom.getRegion('dialog_link_tariff_code');
    var pos = [region1.left + 8, region1.bottom - 2]
    Dom.setXY('dialog_link_tariff_code', pos);
    dialog_link_tariff_code.show()
}

function hide_dialog_link_tariff_code() {
    dialog_link_tariff_code.hide()
}

function show_dialog_link_properties() {

    region1 = Dom.getRegion('product_properties_title_div');
    region2 = Dom.getRegion('dialog_link_properties');
    var pos = [region1.left + 8, region1.bottom - 2]
    Dom.setXY('dialog_link_properties', pos);
    dialog_link_properties.show()
}

function hide_dialog_link_properties() {
    dialog_link_properties.hide()
}


function init(){


validate_scope_data={
    'product_description':{
		'units_per_case':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Units_Per_Case','ar':false,'validation':[{'regexp':"\\d",'invalid_msg':'<?php echo _('Invalid Number')?>'}]}
	,'unit_type':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Unit_Type','ar':false,'validation':false}	

	,'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Product Name')?>'}]}
	
	,'code':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Code','ar':false,'validation':[{'regexp':"[a-z\\d\\-]+",'invalid_msg':'<?php echo _('Invalid Code')?>'}]}

	,'special_characteristic':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Special_Characteristic','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Special Characteristic')?>'}]}
		,
		'Barcode_Type': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'name': 'Product_Barcode_Type',
			'ar': false,
			'validation':false
			
		}
				,
		'Barcode_Data_Source': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'name': 'Product_Barcode_Data_Source',
			'ar': false,
			'validation':false
			
		},
		'Barcode_Data': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'name': 'Product_Barcode_Data',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d]+",
				'invalid_msg': '<?php echo _('Invalid Barcode')?>'
				
			}]
			
		},
		
		'Product_Tariff_Code': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Product Tariff Code',
			'name': 'Product_Tariff_Code',
			'ar': false,
			'validation': [{
				'regexp': "\\d",
				'invalid_msg': '<?php echo _('Invalid Tariff Code')?>'
				
			}]
			
		},
				'Product_Duty_Rate': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Product Duty Rate',
			'name': 'Product_Duty_Rate',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d]+",
				'invalid_msg': '<?php echo _('Invalid Duty Rate')?>'
				
			}]
			
		}


	}
    , 'product_price':{
	'price':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Price','ar':false,'validation':[{'regexp':money_regex,'invalid_msg':'<?php echo _('Invalid Price')?>'}]}
	,'rrp':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_RRP','ar':false,'validation':[{'regexp':money_regex,'invalid_msg':'<?php echo _('Invalid Price')?>'}]}
    }
	
  , 'product_properties':{
'Product_Package_Type': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Product Package Type',
			'name': 'Product_Package_Type',
			'ar': false
			
		}

	,'Product_XHTML_Unit_Weight':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Product_XHTML_Unit_Weight','ar':false,'validation':false}
	,'Product_XHTML_Package_Weight':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Product_XHTML_Package_Weight','ar':false,'validation':false}	
	,'Product_XHTML_Unit_Dimensions':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Product_XHTML_Unit_Dimensions','ar':false,'validation':false}	
	,'Product_XHTML_Package_Dimensions':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Product_XHTML_Package_Dimensions','ar':false,'validation':false}	

	}
,'product_health_and_safety': {
		'Product_UN_Number': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Product UN Number',
			'name': 'Product_UN_Number',
			'ar': false,
			'validation': [{
	'regexp': "^\\d{0,4}$",

				'invalid_msg': '<?php echo _('Invalid UN Number')?>'
				
			}]
			
		}
		,'Product_UN_Class': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Product UN Class',
			'name': 'Product_UN_Class',
			'ar': false,
			'validation': [{
				'regexp': "^[\\d\\.]{0,2}$",
				'invalid_msg': '<?php echo _('Invalid UN Number Class')?>'
				
			}]
			
		}
		,'Product_Packing_Group': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Product Packing Group',
			'name': 'Product_Packing_Group',
			'ar': false
			
		}
		,'Product_Proper_Shipping_Name': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Product Proper Shipping Name',
			'name': 'Product_Proper_Shipping_Name',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d]+",
				'invalid_msg': '<?php echo _('Proper Shipping Name')?>'
				
			}]
			
		}		,'Product_Hazard_Indentification_Number': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Product Hazard Indentification Number',
			'name': 'Product_Hazard_Indentification_Number',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d]+",
				'invalid_msg': '<?php echo _('Invalid Hazard Indentification Number')?>'
				
			}]
			
		}
		,'Product_Health_And_Safety': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 2,
			'type': 'item',
			'dbname': 'Product Health And Safety',
			'name': 'Product_Health_And_Safety',
			'ar': false,
			'validation': false
		}
	}
	,'product_general_description': {
		'Product_Description': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 2,
			'type': 'item',
			'dbname': 'Product Description',
			'name': 'Product_Description',
			'ar': false,
			'validation': false
		}
	},
	
	

    };
validate_scope_metadata={
    'product_description':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':Dom.get('product_pid').value}
       ,'product_general_description':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':Dom.get('product_pid').value}

   ,'product_price':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':Dom.get('product_pid').value}
    ,'product_properties':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'pid','key':Dom.get('product_pid').value}
    ,
	'product_health_and_safety': {
		'type': 'edit',
		'ar_file': 'ar_edit_assets.php'
		,'key_name':'pid','key':Dom.get('product_pid').value
		
	},

};

	Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 	Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
	Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 	Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);

  	var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 	oACDS2.queryMatchContains = true;
 	oACDS2.table_id=2;
 	var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);
 	oAutoComp2.minQueryLength = 0; 
	YAHOO.util.Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 	YAHOO.util.Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);
 
 	var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 	oACDS.queryMatchContains = true;
  	oACDS.table_id=0;
 	var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 	oAutoComp.minQueryLength = 0; 
 
 	var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 	oACDS1.queryMatchContains = true;
  	oACDS1.table_id=1;
 	var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 	oAutoComp1.minQueryLength = 0; 
 
 	init_search('products_store');
 
	Event.on('uploadButton', 'click', upload_image);

    var ids = ["description","parts","web"]; 
    Event.addListener(ids, "click", change_block);
    
    
	var ids = ["description_block_family","description_block_type","description_block_description","description_block_properties", "description_block_info", "description_block_pictures","description_block_price","description_block_health_and_safety"];
	Event.addListener(ids, "click", change_properties_block);

    
    
    Event.addListener('save_edit_product_description', "click", save_edit_description);
    Event.addListener('reset_edit_product_description', "click", reset_edit_description);
    
    Event.addListener('save_edit_product_price', "click", save_edit_price);
    Event.addListener('reset_edit_product_price', "click", reset_edit_price);

    Event.addListener('save_edit_product_properties', "click", save_edit_weight);
    Event.addListener('reset_edit_product_properties', "click", reset_edit_weight);

    Event.addListener('save_edit_product_units', "click", save_edit_product_units);
    Event.addListener('reset_edit_product_units', "click", reset_edit_product_units);
    
    Event.addListener('save_edit_product_health_and_safety', "click", save_edit_product_health_and_safety);
    Event.addListener('reset_edit_product_health_and_safety', "click", reset_edit_product_health_and_safety);

    Event.addListener('save_edit_product_general_description', "click", save_edit_product_general_description);
    Event.addListener('reset_edit_product_general_description', "click", reset_edit_product_general_description);




  	dialog_link_health_and_safety = new YAHOO.widget.Dialog("dialog_link_health_and_safety",  {visible : false,close:true,underlay: "none",draggable:false});
    dialog_link_health_and_safety.render();
    
    Event.addListener("lock_product_health_and_safety", "click",show_dialog_link_health_and_safety);
    Event.addListener("cancel_lock_product_health_and_safety", "click",hide_dialog_link_health_and_safety);
 	Event.addListener('unlock_product_health_and_safety', "click",unlock_product_health_and_safety);
  	Event.addListener('save_lock_product_health_and_safety', "click",lock_product_health_and_safety);
  	
  	dialog_link_tariff_code = new YAHOO.widget.Dialog("dialog_link_tariff_code",  {visible : false,close:true,underlay: "none",draggable:false});
    dialog_link_tariff_code.render();
    
    
    
      	dialog_link_properties = new YAHOO.widget.Dialog("dialog_link_properties",  {visible : false,close:true,underlay: "none",draggable:false});
    dialog_link_properties.render();
    
    Event.addListener("lock_product_tariff_code", "click",show_dialog_link_tariff_code);
    Event.addListener("cancel_lock_product_tariff_code", "click",hide_dialog_link_tariff_code);
 	Event.addListener('unlock_product_tariff_code', "click",unlock_product_tariff_code);
  	Event.addListener('save_lock_product_tariff_code', "click",lock_product_tariff_code);

  Event.addListener("lock_product_properties", "click",show_dialog_link_properties);
    Event.addListener("cancel_lock_product_properties", "click",hide_dialog_link_properties);
 	Event.addListener('unlock_product_properties', "click",unlock_product_properties);
  	Event.addListener('save_lock_product_properties', "click",lock_product_properties);

 
    dialog_part_list = new YAHOO.widget.Dialog("dialog_part_list",  {visible : false,close:true,underlay: "none",draggable:false});
    dialog_part_list.render();
    
    Event.addListener("add_part", "click",show_dialog_part_list);

    
    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_units);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("Product_Units_Per_Case","Product_Units_Per_Case_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;
    
    var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_name);
    product_name_oACDS.queryMatchContains = true;
    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Name","Product_Name_Container", product_name_oACDS);
    product_name_oAutoComp.minQueryLength = 0; 
    product_name_oAutoComp.queryDelay = 0.1;
    
    
       var product_code_oACDS = new YAHOO.util.FunctionDataSource(validate_product_code);
    product_code_oACDS.queryMatchContains = true;
    var product_code_oAutoComp = new YAHOO.widget.AutoComplete("Product_Code","Product_Code_Container", product_code_oACDS);
    product_code_oAutoComp.minQueryLength = 0; 
    product_code_oAutoComp.queryDelay = 0.1;
	
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

	var product_barcode_data_oACDS = new YAHOO.util.FunctionDataSource(validate_Product_Barcode_Data);
	product_barcode_data_oACDS.queryMatchContains = true;
	var product_barcode_data_oAutoComp = new YAHOO.widget.AutoComplete("Product_Barcode_Data", "Product_Barcode_Data_Container", product_barcode_data_oACDS);
	product_barcode_data_oAutoComp.minQueryLength = 0;
	product_barcode_data_oAutoComp.queryDelay = 0.1;



   var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_unit_weight);
    product_name_oACDS.queryMatchContains = true;
    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_XHTML_Unit_Weight","Product_XHTML_Unit_Weight_Container", product_name_oACDS);
    product_name_oAutoComp.minQueryLength = 0; 
    product_name_oAutoComp.queryDelay = 0.1;
	
	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_package_weight);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_XHTML_Package_Weight","Product_XHTML_Package_Weight_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;
	
	
	  var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_unit_dimensions);
    product_name_oACDS.queryMatchContains = true;
    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_XHTML_Unit_Dimensions","Product_XHTML_Unit_Dimensions_Container", product_name_oACDS);
    product_name_oAutoComp.minQueryLength = 0; 
    product_name_oAutoComp.queryDelay = 0.1;
	
	var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_package_dimensions);
	product_name_oACDS.queryMatchContains = true;
	var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_XHTML_Package_Dimensions","Product_XHTML_Package_Dimensions_Container", product_name_oACDS);
	product_name_oAutoComp.minQueryLength = 0; 
	product_name_oAutoComp.queryDelay = 0.1;
	
	
	    var product_Tariff_Code_oACDS = new YAHOO.util.FunctionDataSource(validate_Product_Tariff_Code);
    product_Tariff_Code_oACDS.queryMatchContains = true;
    var product_gross_weight_oAutoComp = new YAHOO.widget.AutoComplete("Product_Tariff_Code", "Product_Tariff_Code_Container", product_Tariff_Code_oACDS);
    product_gross_weight_oAutoComp.minQueryLength = 0;
    product_gross_weight_oAutoComp.queryDelay = 0.1;

    var product_Duty_Rate_oACDS = new YAHOO.util.FunctionDataSource(validate_Product_Duty_Rate);
    product_Duty_Rate_oACDS.queryMatchContains = true;
    var product_duty_rate_oAutoComp = new YAHOO.widget.AutoComplete("Product_Duty_Rate", "Product_Duty_Rate_Container", product_Duty_Rate_oACDS);
    product_duty_rate_oAutoComp.minQueryLength = 0;
    product_duty_rate_oAutoComp.queryDelay = 0.1;
	
	
	var product_un_number_oACDS = new YAHOO.util.FunctionDataSource(validate_Product_UN_Number);
    product_un_number_oACDS.queryMatchContains = true;
    var product_un_number_oAutoComp = new YAHOO.widget.AutoComplete("Product_UN_Number", "Product_UN_Number_Container", product_un_number_oACDS);
    product_un_number_oAutoComp.minQueryLength = 0;
    product_un_number_oAutoComp.queryDelay = 0.1;
    
    var product_un_number_class_oACDS = new YAHOO.util.FunctionDataSource(validate_Product_UN_Class);
    product_un_number_class_oACDS.queryMatchContains = true;
    var product_un_number_class_oAutoComp = new YAHOO.widget.AutoComplete("Product_UN_Class", "Product_UN_Class_Container", product_un_number_class_oACDS);
    product_un_number_class_oAutoComp.minQueryLength = 0;
    product_un_number_class_oAutoComp.queryDelay = 0.1;
        
  var product_proper_shipping_name_oACDS = new YAHOO.util.FunctionDataSource(validate_Product_Proper_Shipping_Name);
    product_proper_shipping_name_oACDS.queryMatchContains = true;
    var product_proper_shipping_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Proper_Shipping_Name", "Product_Proper_Shipping_Name_Container", product_proper_shipping_name_oACDS);
    product_proper_shipping_name_oAutoComp.minQueryLength = 0;
    product_proper_shipping_name_oAutoComp.queryDelay = 0.1;
    
      var product_hin_oACDS = new YAHOO.util.FunctionDataSource(validate_Product_Hazard_Indentification_Number);
    product_hin_oACDS.queryMatchContains = true;
    var product_hin_oAutoComp = new YAHOO.widget.AutoComplete("Product_Hazard_Indentification_Number", "Product_Hazard_Indentification_Number_Container", product_hin_oACDS);
    product_hin_oAutoComp.minQueryLength = 0;
    product_hin_oAutoComp.queryDelay = 0.1;
	  var product_has_description_oACDS = new YAHOO.util.FunctionDataSource(validate_Product_HAS_Description);
    product_has_description_oACDS.queryMatchContains = true;
    var product_gross_weight_oAutoComp = new YAHOO.widget.AutoComplete("Product_Health_And_Safety", "Product_Health_And_Safety_Container", product_has_description_oACDS);
    product_gross_weight_oAutoComp.minQueryLength = 0;
    product_gross_weight_oAutoComp.queryDelay = 0.1;


    dialog_family_list = new YAHOO.widget.Dialog("dialog_family_list", {context:["family","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_family_list.render();
	
	
    Event.addListener("edit_family", "click", dialog_family_list.show,dialog_family_list , true);
    
       Event.addListener("filter_name1", "click",change_part_list_filter);

 
       var myConfig = {
       
         height: '300px',
        width: '935px',
        animate: true,
        dompath: true,
        focusAtStart: true,
         autoHeight: true
    };

 var state = 'off';
    GeneralDescriptionEditor = new YAHOO.widget.Editor('Product_Description', myConfig);
    GeneralDescriptionEditor.on('toolbarLoaded', function() {
    
     var codeConfig = {
            type: 'push', label: 'Edit HTML Code', value: 'editcode'
        };
        this.toolbar.addButtonToGroup(codeConfig, 'insertitem');
        
         this.toolbar.on('editcodeClick', function() {
        

        
            var ta = this.get('element'),iframe = this.get('iframe').get('element');

            if (state == 'on') {
                state = 'off';
                this.toolbar.set('disabled', false);
                          this.setEditorHTML(ta.value);
                if (!this.browser.ie) {
                    this._setDesignMode('on');
                }

                Dom.removeClass(iframe, 'editor-hidden');
                Dom.addClass(ta, 'editor-hidden');
                this.show();
                this._focusWindow();
            } else {
                state = 'on';
                
                this.cleanHTML();
               
                Dom.addClass(iframe, 'editor-hidden');
                Dom.removeClass(ta, 'editor-hidden');
                this.toolbar.set('disabled', true);
                this.toolbar.getButtonByValue('editcode').set('disabled', false);
                this.toolbar.selectButton('editcode');
                this.dompath.innerHTML = 'Editing HTML Code';
                this.hide();
            
            }
            return false;
        }, this, true);

        this.on('cleanHTML', function(ev) {
            this.get('element').value = ev.html;
        }, this, true);
        
        
         this.on('afterRender', function() {
            var wrapper = this.get('editor_wrapper');
            wrapper.appendChild(this.get('element'));
            this.setStyle('width', '100%');
            this.setStyle('height', '100%');
            this.setStyle('visibility', '');
            this.setStyle('top', '');
            this.setStyle('left', '');
            this.setStyle('position', '');

            this.addClass('editor-hidden');
        }, this, true);
    
    this.on('cleanHTML', function(ev) {
            this.get('element').value = ev.html;
        }, this, true);
        
        
         this.on('editorKeyUp', general_description_editor_changed, this, true);
                this.on('editorDoubleClick', general_description_editor_changed, this, true);
                this.on('editorMouseDown', general_description_editor_changed, this, true);
                this.on('buttonClick', general_description_editor_changed, this, true);
    }, GeneralDescriptionEditor, true);
    yuiImgUploader(GeneralDescriptionEditor, 'Product_Description', 'ar_upload_file_from_editor.php','image');
    GeneralDescriptionEditor.render();
//alert("x")












//=========================


    HealthAndSafetyEditor = new YAHOO.widget.Editor('Product_Health_And_Safety', myConfig);
    HealthAndSafetyEditor.on('toolbarLoaded', function() {
         this.on('editorKeyUp',  health_and_safety_editor_changed, this, true);
                this.on('editorDoubleClick', health_and_safety_editor_changed, this, true);
                this.on('editorMouseDown', health_and_safety_editor_changed, this, true);
                this.on('buttonClick', health_and_safety_editor_changed, this, true);
    }, HealthAndSafetyEditor, true);
    yuiImgUploader(HealthAndSafetyEditor, 'product_health_and_safety', 'ar_upload_file_from_editor.php','image');
    HealthAndSafetyEditor.render();
        YAHOO.util.Event.on('show_product_health_and_safety_editor', 'click', show_product_health_and_safety_editor);

    
    
}

function show_history() {
    Dom.setStyle(['show_history', ''], 'display', 'none')
    Dom.setStyle(['hide_history', 'history_table'], 'display', '')

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=product-show_history&value=1', {});

}

function hide_history() {
    Dom.setStyle(['show_history', ''], 'display', '')
    Dom.setStyle(['hide_history', 'history_table'], 'display', 'none')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=product-show_history&value=0', {});

}


function change_part_list_filter(){

if(this.getAttribute('state')==undefined){
this.setAttribute('state','sku')
change_filter('sku','SKU',1)
}else if(this.getAttribute('state')=='sku'){
this.setAttribute('state','used_in')
change_filter('used_in','Used In',1)

}else if(this.getAttribute('state')=='used_in'){
this.setAttribute('state','sku')
change_filter('sku','SKU',1)
}
}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

 var tableid=0; 
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"abstract", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	   

	    this.dataSource0 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=product&tableid=0");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		
		fields: [
			 "id"
			 ,"note"
			 ,'author','date','tipo','abstract','details'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource0
						     , {

							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['product']['history']['nr']?>,containers : 'paginator0', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['product']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['product']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table0.table_id=tableid;
     	this.table0.subscribe("renderEvent", myrenderEvent);	
		    
		    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['product']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['product']['history']['f_value']?>'};





var tableid=1;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
		      		{key:"formated_sku", label:"SKU",width:60, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"description", label:"<?php echo _('Description')?>",width:200, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			     	,{key:"used_in", label:"<?php echo _('Used In')?>",width:140, sortable:false,className:"aleft"}
			     	,{key:"status", label:"",width:70, sortable:false,className:"aleft"}
                   
					];
		    
		      
		      this.dataSource1 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=part_list&tableid=1");
		      
			      this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		      this.dataSource1.connXhrMode = "queueRequests";
		      	    this.dataSource1.table_id=tableid;

		      this.dataSource1.responseSchema = {
			  resultsList: "resultset.data", 
			  metaFields: {
			  rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",

		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" 
			  },
			  
			  fields: [
				  "sku","description","used_in","status","formated_sku"
				   ]};
		      
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								   
								   ,sortedBy : {
								      key: "formated_sku",
								       dir: ""
								   }
								   ,dynamicData : true
								 
							       }
							       );
		      this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		      this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		      this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
            this.table1.table_id=tableid;
     			this.table1.subscribe("renderEvent", myrenderEvent);
     
                   this.table1.subscribe("rowMouseoverEvent", this.table1.onEventHighlightRow);
       this.table1.subscribe("rowMouseoutEvent", this.table1.onEventUnhighlightRow);
      this.table1.subscribe("rowClickEvent", select_part_from_list);
     

                   
	    this.table1.filter={key:'used_in',value:''};



		
   var tableid=2; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
			 {key:"key", label:"",width:100,hidden:true}
                    ,{key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						
			];
		this.dataSource2 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=family_list&store_key="+Dom.get('store_key').value+"&tableid="+tableid+"&nr=20&sf=0");
		//alert("ar_quick_tables.php?tipo=family_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    	    this.dataSource2.table_id=tableid;

	    this.dataSource2.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code",'name','key'
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table2.subscribe("rowMouseoverEvent", this.table2.onEventHighlightRow);
       this.table2.subscribe("rowMouseoutEvent", this.table2.onEventUnhighlightRow);
      this.table2.subscribe("rowClickEvent", select_family);
        this.table2.table_id=tableid;
           this.table2.subscribe("renderEvent", myrenderEvent);


	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'code',value:''};


};
    });




YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });


YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });


/*
YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu1 = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu1.render();
	 oMenu1.subscribe("show", oMenu1.focus);
	
    });
*/
function close_change_part_dialog(){

Dom.get('change_part').value='';
Dom.setStyle('change_part_selector','display','');
Dom.setStyle('save_change_part','display','none');
Dom.setStyle('change_part_confirmation','display','none');
 Editor_change_part.hide();
}

function change_part_selected(sType, aArgs){
alert("caca")
//remove_part(Dom.get('change_part_sku').value)
//add_part_selected(sType, aArgs);
//close_change_part_dialog();


//alert("s")

//var myAC = aArgs[0]; // reference back to the AC instance 
  //      var elLI = aArgs[1]; // reference to the selected LI element 
//	        var oData = aArgs[2]; // object literal of selected item's result data 

//Dom.get('change_part_new_part').innerHTML=oData[0];

//Dom.get('change_part').value='';
//Dom.setStyle('change_part_selector','display','none');
//Dom.setStyle('save_change_part','display','');
//Dom.setStyle('change_part_confirmation','display','');
}

function show_change_part_dialog(sku,o){

  Dom.get('change_part_sku').value=sku;
   x= Dom.getX(o)-455;
   y= Dom.getY(o);
   Dom.setX('Editor_change_part', x);
   Dom.setY('Editor_change_part', y);
   Dom.get('change_part').focus();
   Editor_change_part.show();
  
}

function show_family_list(o){
	Dom.setStyle('family_list','display','')
}


YAHOO.util.Event.onContentReady("change_part", function () {
  
  var new_loc_oDS = new YAHOO.util.XHRDataSource("ar_parts.php");
    new_loc_oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
    new_loc_oDS.responseSchema = {
resultsList : "data"
        ,

       fields : ["info","sku","description","usedin","formated_sku"]
    };
    var new_loc_oAC = new YAHOO.widget.AutoComplete("change_part", "change_part_container", new_loc_oDS);
  
  
  new_loc_oAC.generateRequest = function(sQuery) {

        sku=Dom.get("change_part_sku").value;
        request=  "?tipo=find_part&except_part="+sku+"&query=" + sQuery ;  
     
     return request;
    };
    new_loc_oAC.forceSelection = true;
    new_loc_oAC.itemSelectEvent.subscribe(change_part_selected);
    
});


function remove_part(sku){


if(part_list['sku'+sku].new  ){
	to_delete=Dom.get('part_list'+part_list[part_key].sku);
		to_delete.parentNode.removeChild(to_delete);
				to_delete=Dom.get('sup_tr2_'+part_list[part_key].sku);
		to_delete.parentNode.removeChild(to_delete);
				to_delete=Dom.get('sup_tr3_'+part_list[part_key].sku);
		to_delete.parentNode.removeChild(to_delete);
delete part_list['sku'+sku];


}else{

part_list['sku'+sku].deleted=true;
Dom.setStyle(['part_list'+sku+'_label1','part_list'+sku+'_label2'],'opacity',0.6);
Dom.setStyle(['part_list'+sku+'_label2'],'text-decoration','line-through');


Dom.setStyle(['sup_tr2_'+sku,'sup_tr3_'+sku],'opacity',0.4);
Dom.setStyle(['part_list'+sku+'_controls'],'display','none');
Dom.setStyle(['part_list'+sku+'_controls2'],'display','');

}

Dom.setStyle(['add_part'],'display','');



part_render_save_buttons();
}


function show_dialog_part_list(){


	region1 = Dom.getRegion('add_part'); 
    region2 = Dom.getRegion('dialog_part_list'); 
	var pos =[region1.right-region2.width+10,region1.bottom]
	Dom.setXY('dialog_part_list', pos);

dialog_part_list.show()
}


function unremove_part(sku){

part_list['sku'+sku].deleted=false;
Dom.setStyle(['part_list'+sku+'_label1','part_list'+sku+'_label2'],'opacity',1);
Dom.setStyle(['part_list'+sku+'_label2'],'text-decoration','none');


Dom.setStyle(['sup_tr2_'+sku,'sup_tr3_'+sku],'opacity',1);
Dom.setStyle(['part_list'+sku+'_controls'],'display','');
Dom.setStyle(['part_list'+sku+'_controls2'],'display','none');


part_render_save_buttons();
}
