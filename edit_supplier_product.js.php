<?php
 include_once('common.php');
header('Content-Type: application/javascript'); 		
 		
 		$money_regex="^[^\\\\d\\\.\\\,]{0,3}(\\\\d{1,3}(\\\,\\\\d{3})*|(\\\\d+))(\\\.\\\\d{2})?$";
print 'var money_regex="'.$money_regex.'";';

$custom_field = Array();
$sql = sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Supplier Product'");
$res = mysql_query($sql);
while ($row = mysql_fetch_array($res))
 {
	$custom_field[$row['Custom Field Key']] = $row['Custom Field Name'];

	
}


$show_case = Array();
$sql = sprintf("select * from `Supplier Product Custom Field Dimension` where `Supplier Product ID`=%d", $_REQUEST['pid']);
$res = mysql_query($sql);
if ($row = mysql_fetch_array($res)) {

	foreach($custom_field as $key =>$value) {
		$show_case[$value] = Array('value' =>$row[$key], 'lable' =>$key);

		
	}

	
}


 ?>
 
 
	var Event = YAHOO.util.Event;
	var Dom = YAHOO.util.Dom;
	var Editor_change_part;
	var GeneralDescriptionEditor;
	var HealthAndSafetyEditor;
	var dialog_delete_MSDS_File;
	var dialog_change_weight_units;	
	var	dialog_change_lenght_units;




	var CellEdit = function(callback, newValue) {


        var record = this.getRecord(),
            column = this.getColumn(),
            oldValue = this.value,
            datatable = this.getDataTable(),
            recordIndex = datatable.getRecordIndex(record);



        if (column.object == 'family_page_properties') {
            request_page = 'ar_edit_sites.php';

        } 
        else if (column.object == 'supplier_product_part') {
            request_page = 'ar_edit_suppliers.php';

        } else {
            request_page = 'ar_edit_assets.php';
        }
        
        request='tipo=edit_' + column.object + '&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue) + myBuildUrl(datatable, record);
       //alert(request_page+'?'+request)
        
        //alert(column.object)		
        YAHOO.util.Connect.asyncRequest('POST', request_page, {
            success: function(o) {
               // alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {



                    if (column.key == 'web_configuration') {
                        datatable.updateCell(record, 'smallname', r.newdata['description']);
                        datatable.updateCell(record, 'formated_web_configuration', r.newdata['formated_web_configuration']);
                        datatable.updateCell(record, 'web_configuration', r.newdata['web_configuration']);


                        // alert(r.newdata['web_configuration'])   
                        callback(true, r.newdata['web_configuration']);

                    } else if (column.key == 'available') {
                        datatable.updateCell(record, 'available_state', r.available_state);

                        callback(true, r.newvalue);

                    } else if (column.key == 'status') {
                        datatable.updateCell(record, 'formated_status', r.formated_status);

						if(r.newvalue=='No'){
							Dom.setStyle('historic_supplier_products','display','')
							
							  table_id = 2
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
  
    datasource.sendRequest('', table.onDataReturnInitializeTable, table);
    
      table_id = 5
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];

    datasource.sendRequest('', table.onDataReturnInitializeTable, table);
							
							
						}

                        callback(true, r.newvalue);

                    } else {

                        callback(true, r.newvalue);

                    }
                } else {
                    alert(r.msg);
                    callback();
                }
            },
            failure: function(o) {
                alert(o.statusText);
                callback();
            },
            scope: this
        }, request

        );
    };


function validate_Supplier_Product_Name(query) {
	validate_general('supplier_product_unit', 'name', query);
}

function validate_Supplier_Product_Package_Weight_Display(query) {
	validate_general('supplier_product_properties', 'Supplier_Product_Package_Weight_Display', query);
}

function validate_Supplier_Product_Package_Dimensions_Width_Display(query) {
	validate_general('supplier_product_properties', 'Supplier_Product_Package_Dimensions_Width_Display', query);
}

function validate_Supplier_Product_Package_Dimensions_Depth_Display(query) {
	validate_general('supplier_product_properties', 'Supplier_Product_Package_Dimensions_Depth_Display', query);
}

function validate_Supplier_Product_Package_Dimensions_Length_Display(query) {
	validate_general('supplier_product_properties', 'Supplier_Product_Package_Dimensions_Length_Display', query);
}
function validate_Supplier_Product_Package_Dimensions_Diameter_Display(query) {
	validate_general('supplier_product_properties', 'Supplier_Product_Package_Dimensions_Diameter_Display', query);
}

function validate_Supplier_Product_Unit_Weight_Display(query) {
	validate_general('supplier_product_properties', 'Supplier_Product_Unit_Weight_Display', query);
}

function validate_Supplier_Product_Unit_Dimensions_Width_Display(query) {
	validate_general('supplier_product_properties', 'Supplier_Product_Unit_Dimensions_Width_Display', query);
}

function validate_Supplier_Product_Unit_Dimensions_Depth_Display(query) {
	validate_general('supplier_product_properties', 'Supplier_Product_Unit_Dimensions_Depth_Display', query);
}

function validate_Supplier_Product_Unit_Dimensions_Length_Display(query) {
	validate_general('supplier_product_properties', 'Supplier_Product_Unit_Dimensions_Length_Display', query);
}

function validate_Supplier_Product_Unit_Dimensions_Diameter_Display(query) {
	validate_general('supplier_product_properties', 'Supplier_Product_Unit_Dimensions_Diameter_Display', query);
}

function validate_Supplier_Product_Tariff_Code(query) {
	validate_general('supplier_product_unit', 'tariff_code', query);
}

function validate_Supplier_Product_Duty_Rate(query) {
	validate_general('supplier_product_unit', 'duty_rate', query);
}

function validate_Supplier_Product_Code(query) {
	validate_general('supplier_product_unit', 'code', query);
}

function validate_Supplier_Product_Cost_Per_Case(query) {
	validate_general('supplier_product_cost', 'Supplier_Product_Cost_Per_Case', query);
}


function validate_Supplier_Product_Barcode_Data(query) {
	validate_general('supplier_product_unit', 'Barcode_Data', query);
}


function validate_Supplier_Product_UN_Number(query) {
	validate_general('supplier_product_health_and_safety', 'UN_Number', query);
}


function validate_Supplier_Product_UN_Number_Class(query) {
	validate_general('supplier_product_health_and_safety', 'UN_Number_Class', query);
}


function validate_Supplier_Product_Proper_Shipping_Name(query) {
	validate_general('supplier_product_health_and_safety', 'Supplier_Product_Proper_Shipping_Name', query);
}

function validate_Supplier_Product_Hazard_Indentification_Number(query) {
	validate_general('supplier_product_health_and_safety', 'Supplier_Product_Hazard_Indentification_Number', query);
}



function validate_Supplier_Product_Unit_Type(query) {
	validate_general('supplier_product_unit', 'unit_type', query);
}

function validate_Supplier_Product_Units_Per_Case(query) {
	validate_general('supplier_product_unit', 'Units_Per_Case', query);
}

function validate_Supplier_Product_General_Description(query) {
	validate_general('supplier_product_description', 'general_description', query);
}

function validate_Supplier_Product_HAS_Description(query) {
	validate_general('supplier_product_description', 'has_description', query);

	
}

function delete_origin_country_code(){

Dom.get('Supplier_Product_Origin_Country_Code_formated').innerHTML=''
Dom.setStyle(['update_Supplier_Product_Origin_Country_Code','delete_Supplier_Product_Origin_Country_Code'],'display','none')
Dom.setStyle('set_Supplier_Product_Origin_Country_Code','display','')


 
   value='';
     
    validate_scope_data['supplier_product_unit']['origin']['value'] = value;
   
    Dom.get('Supplier_Product_Origin_Country_Code').value = value
    ovalue = Dom.get('Supplier_Product_Origin_Country_Code').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['supplier_product_unit']['origin']['changed'] = true;

    } else {
        validate_scope_data['supplier_product_unit']['origin']['changed'] = false;

    }
    validate_scope('supplier_product_unit')

}

function change_supplier(oArgs){

   dialog_supplier_list.hide();

    value = tables.table3.getRecord(oArgs.target).getData('key');

    Dom.get('Supplier_Product_Supplier_Key').value = value
    Dom.get('current_supplier_code').innerHTML = tables.table3.getRecord(oArgs.target).getData('code');
    Dom.get('current_supplier_name').innerHTML = tables.table3.getRecord(oArgs.target).getData('name');

    validate_scope_data['supplier_product_supplier']['Supplier_Product_Supplier_Key']['value'] = value;

    ovalue = Dom.get('Supplier_Product_Supplier_Key').getAttribute('ovalue');
    if (ovalue != value) {
        validate_scope_data['supplier_product_supplier']['Supplier_Product_Supplier_Key']['changed'] = true;
    } else {
        validate_scope_data['supplier_product_supplier']['Supplier_Product_Supplier_Key']['changed'] = false;
    }
    validate_scope('supplier_product_supplier')


}


function change_origin_country_code(oArgs) {

    country_code = tables.table4.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
    country_name = tables.table4.getRecord(oArgs.target).getData('name').replace(/<.*?>/g, '');

    Dom.get('Supplier_Product_Origin_Country_Code_formated').innerHTML = country_name
    Dom.setStyle(['update_Supplier_Product_Origin_Country_Code', 'delete_Supplier_Product_Origin_Country_Code'], 'display', '')
    Dom.setStyle('set_Supplier_Product_Origin_Country_Code', 'display', 'none')



    value = country_code;

    validate_scope_data['supplier_product_unit']['origin']['value'] = value;

    Dom.get('Supplier_Product_Origin_Country_Code').value = value
    ovalue = Dom.get('Supplier_Product_Origin_Country_Code').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['supplier_product_unit']['origin']['changed'] = true;
    } else {
        validate_scope_data['supplier_product_unit']['origin']['changed'] = false;
    }
    validate_scope('supplier_product_unit')
    dialog_country_list.hide();

}



 <?php

 foreach($show_case as $custom_key =>$custom_value) {

	printf("function validate_supplier_product_%s(query){validate_general('supplier_product_custom_field','custom_field_supplier_product_%s',query);}"
	, $custom_value['lable']
	, $custom_value['lable']
	);

	
}

 ?>

function change_state(value) {


  
    options = Dom.getElementsByClassName('option', 'button', 'Supplier_Product_State_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass('Supplier_Product_State_' + value, 'selected')

    //  alert('Supplier_Product_State_' + type+' '+value)
    Dom.get('Supplier_Product_State').value = value;

    validate_scope_data['supplier_product_state']['Supplier_Product_State']['value'] = value;

    ovalue = Dom.get('Supplier_Product_State').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['supplier_product_state']['Supplier_Product_State']['changed'] = true;
    } else {

        validate_scope_data['supplier_product_state']['Supplier_Product_State']['changed'] = false;
    }
    validate_scope('supplier_product_state')

}







function change_block(e) {

	var ids = ["description", "parts"];
	var block_ids = ["d_description", "d_parts"];

	Dom.setStyle(block_ids, 'display', 'none');
	Dom.setStyle('d_' + this.id, 'display', '');

	Dom.removeClass(ids, 'selected');
	Dom.addClass(this, 'selected');

	YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier_product-edit&value=' + this.id, {});

	
}


function change_properties_block(e) {

    var ids = ["description_block_status", "description_block_description", "description_block_properties", "description_block_pictures", "description_block_info", "description_block_health_and_safety","description_block_supplier","description_block_cost"];
	var block_ids = ["d_description_block_status", "d_description_block_description" ,"d_description_block_properties", "d_description_block_info", "d_description_block_health_and_safety","d_description_block_pictures","d_description_block_supplier","d_description_block_cost"];

	Dom.setStyle(block_ids, 'display', 'none');
	block_id=this.getAttribute('block_id');
	Dom.setStyle('d_description_block_' + block_id, 'display', '');
	Dom.removeClass(ids, 'selected');
	Dom.addClass(this, 'selected');

	YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier_product-edit_description_block&value=' + block_id, {});

}


function post_item_updated_actions(branch, r) {
    
  
    if (r.key == 'description') {
        Dom.get('supplier_product_description_title').innerHTML = r.newvalue
    } else if (r.key == 'reference') {
        Dom.get('supplier_product_reference_title').innerHTML = r.newvalue
    }else if(r.key=='origin'){
   		Dom.get('Supplier_Product_Origin_Country_Code').setAttribute('ovalue_formated',Dom.get('Supplier_Product_Origin_Country_Code_formated').innerHTML)
    }else if(r.key=='Supplier_Product_Supplier_Key'){
    	 Dom.get('supplier_branch_link').innerHTML = r.newdata['code'];
        Dom.get('supplier_branch_link').href = "supplier.php?id=" + r.newdata['key'];
        Dom.get('supplier_branch_link').title = r.newdata['name'];
    }

    table_id = 0
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';

    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

}

function save_edit_supplier_product_state() {
    save_edit_general('supplier_product_state');
}

function reset_edit_supplier_product_state() {
    reset_edit_general('supplier_product_state')

    val = Dom.get('Supplier_Product_State').getAttribute('ovalue')
   
    options = Dom.getElementsByClassName('option', 'button', 'Supplier_Product_State_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass('Supplier_Product_State_' + val, 'selected')



}


function save_edit_supplier_product_unit() {
    save_edit_general('supplier_product_unit');
}

function reset_edit_supplier_product_unit() {
    reset_edit_general('supplier_product_unit')

    val = Dom.get('Supplier_Product_Unit_Type').getAttribute('ovalue')
    sel = Dom.get('Supplier_Product_Unit_Type_Select')
    for (var i, j = 0; i = sel.options[j]; j++) {
        if (i.value == val) {
            sel.selectedIndex = j;
            break;
        }
    }
    Dom.get('Supplier_Product_Unit_Type').value = val;

    type = Dom.get('Supplier_Product_Barcode_Type').getAttribute('ovalue')

    options = Dom.getElementsByClassName('option', 'button', 'Supplier_Product_Barcode_Type_options')

    Dom.removeClass(options, 'selected')
    Dom.addClass('Supplier_Product_Barcode_Type_option_' + type, 'selected')


    if (type == 'none') {
        Dom.setStyle(['Supplier_Product_Barcode_Data_Source_tr', 'Supplier_Product_Barcode_Data_tr'], 'display', 'none')

    } else {
        Dom.setStyle('Supplier_Product_Barcode_Data_Source_tr', 'display', '')
        if (Dom.get('Supplier_Product_Barcode_Data_Source').value == 'Other') {
            Dom.setStyle('Supplier_Product_Barcode_Data_tr', 'display', '')
        } else {
            Dom.setStyle('Supplier_Product_Barcode_Data_tr', 'display', 'none')

        }
    }

    barcode_data_source = Dom.get('Supplier_Product_Barcode_Data_Source').getAttribute('ovalue')

    options = Dom.getElementsByClassName('option', 'button', 'Supplier_Product_Barcode_Data_Source_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass('Supplier_Product_Barcode_Data_Source_option_' + barcode_data_source, 'selected')


    if (barcode_data_source == 'Other') {
        Dom.setStyle('Supplier_Product_Barcode_Data_tr', 'display', '')
    } else {
        Dom.setStyle('Supplier_Product_Barcode_Data_tr', 'display', 'none')
    }
    
    
     origin = Dom.get('Supplier_Product_Origin_Country_Code').getAttribute('ovalue')
      origin_formated = Dom.get('Supplier_Product_Origin_Country_Code').getAttribute('ovalue_formated')
      
      Dom.get('Supplier_Product_Origin_Country_Code_formated').innerHTML=origin_formated
      
      if(origin==''){
      
      Dom.setStyle(['update_Supplier_Product_Origin_Country_Code','delete_Supplier_Product_Origin_Country_Code'],'display','none')
      Dom.setStyle('set_Supplier_Product_Origin_Country_Code','display','')
      
      
      
      }else{
       Dom.setStyle(['update_Supplier_Product_Origin_Country_Code','delete_Supplier_Product_Origin_Country_Code'],'display','')
      Dom.setStyle('set_Supplier_Product_Origin_Country_Code','display','none')
      }
   
    
}

function reset_edit_supplier() {
    Dom.get('current_supplier_code').innerHTML = Dom.get('Supplier_Product_Supplier_Key').getAttribute('oformatedvalue')
    Dom.get('current_supplier_name').innerHTML = Dom.get('Supplier_Product_Supplier_Key').getAttribute('oformatedvalue_bis')

    Dom.get('Supplier_Product_Supplier_Key').innerHTML = Dom.get('Supplier_Product_Supplier_Key').getAttribute('ovalue')

    reset_edit_general('supplier_product_supplier');
}


function save_edit_supplier_product_description() {
GeneralDescriptionEditor.saveHTML();
	save_edit_general('supplier_product_description');
}

function save_edit_supplier_product_properties() {
	save_edit_general_bulk('supplier_product_properties');
}


function reset_edit_supplier_product_properties() {
    reset_edit_general('supplier_product_properties');

    type = Dom.get('Supplier_Product_Package_Type').getAttribute('ovalue')
    options = Dom.getElementsByClassName('option', 'button', 'Supplier_Product_Package_Type_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass('Supplier_Product_Package_Type_option_' + type, 'selected')

}

function reset_edit_supplier_product_description() {
	reset_edit_general('supplier_product_description')
    GeneralDescriptionEditor.setEditorHTML(Dom.get('supplier_product_general_description').value);
}


function save_edit_supplier_product_health_and_safety() {
HealthAndSafetyEditor.saveHTML();
	save_edit_general('supplier_product_health_and_safety');
}
function reset_edit_supplier_product_health_and_safety() {
	reset_edit_general('supplier_product_health_and_safety')
    HealthAndSafetyEditor.setEditorHTML(Dom.get('supplier_product_health_and_safety').value);
}





function save_edit_custom_field() {
	save_edit_general('supplier_product_custom_field');
}

function save_edit_supplier() {
    save_edit_general('supplier_product_supplier');
}

function save_edit_cost(){
    save_edit_general('supplier_product_cost');

}

function reset_edit_cost(){
    reset_edit_general('supplier_product_cost');

}

function reset_edit_custom_field() {
	reset_edit_general('supplier_product_custom_field')
}

function change_barcode_type(o, type) {
    options = Dom.getElementsByClassName('option', 'button', 'Supplier_Product_Barcode_Type_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    if (type == 'none') {
        Dom.setStyle(['Supplier_Product_Barcode_Data_Source_tr', 'Supplier_Product_Barcode_Data_tr'], 'display', 'none')

    } else {
        Dom.setStyle('Supplier_Product_Barcode_Data_Source_tr', 'display', '')
        if (Dom.get('Supplier_Product_Barcode_Data_Source').value == 'Other') {
            Dom.setStyle('Supplier_Product_Barcode_Data_tr', 'display', '')
        } else {
            Dom.setStyle('Supplier_Product_Barcode_Data_tr', 'display', 'none')
            Dom.get('Supplier_Product_Barcode_Data').value = Dom.get('Supplier_Product_Barcode_Data').getAttribute('ovalue')
            validate_scope_data['supplier_product_unit']['Barcode_Type']['changed'] = false;
        }
    }
    value = type;
    ovalue = Dom.get('Supplier_Product_Barcode_Type').getAttribute('ovalue');
    validate_scope_data['supplier_product_unit']['Barcode_Type']['value'] = value;
    Dom.get('Supplier_Product_Barcode_Type').value = value

    if (ovalue != value) {
        validate_scope_data['supplier_product_unit']['Barcode_Type']['changed'] = true;
    } else {
        validate_scope_data['supplier_product_unit']['Barcode_Type']['changed'] = false;
    }
    validate_scope('supplier_product_unit')
}

function change_packing_group(o, type) {
    options = Dom.getElementsByClassName('option', 'button', 'Supplier_Product_Packing_Group_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    value = type;
    ovalue = Dom.get('Supplier_Product_Packing_Group').getAttribute('ovalue');
    validate_scope_data['supplier_product_health_and_safety']['Packing_Group']['value'] = value;
    Dom.get('Supplier_Product_Packing_Group').value = value

    if (ovalue != value) {
        validate_scope_data['supplier_product_health_and_safety']['Packing_Group']['changed'] = true;
    } else {
        validate_scope_data['supplier_product_health_and_safety']['Packing_Group']['changed'] = false;
    }
    validate_scope('supplier_product_health_and_safety')
}



function change_barcode_data_source(o, type) {
    options = Dom.getElementsByClassName('option', 'button', 'Supplier_Product_Barcode_Data_Source_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    if (type == 'Other') {
        Dom.setStyle('Supplier_Product_Barcode_Data_tr', 'display', '')
        Dom.get("Supplier_Product_Barcode_Data").value = Dom.get("Supplier_Product_Barcode_Data").getAttribute('ovalue')


    } else {
        Dom.setStyle('Supplier_Product_Barcode_Data_tr', 'display', 'none')
        Dom.get("Supplier_Product_Barcode_Data").value = '';

    }

    value = type;
    ovalue = Dom.get('Supplier_Product_Barcode_Data_Source').getAttribute('ovalue');
    validate_scope_data['supplier_product_unit']['Barcode_Data_Source']['value'] = value;
    Dom.get('Supplier_Product_Barcode_Data_Source').value = value

    if (ovalue != value) {
        validate_scope_data['supplier_product_unit']['Barcode_Data_Source']['changed'] = true;
    } else {
        validate_scope_data['supplier_product_unit']['Barcode_Data_Source']['changed'] = false;
    }
    validate_scope('supplier_product_unit')
}








function change_supplier_product_unit_type(o) {

    var choose_option = o.options[o.selectedIndex]

    value = choose_option.value;
    validate_scope_data['supplier_product_unit']['unit_type']['value'] = value;
    Dom.get('Supplier_Product_Unit_Type').value = value
    ovalue = Dom.get('Supplier_Product_Unit_Type').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['supplier_product_unit']['unit_type']['changed'] = true;
    } else {
        validate_scope_data['supplier_product_unit']['unit_type']['changed'] = false;
    }
    validate_scope('supplier_product_unit')

}

function change_package_type(o, type) {
    options = Dom.getElementsByClassName('option', 'button', 'Supplier_Product_Package_Type_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    value = type;
    ovalue = Dom.get('Supplier_Product_Package_Type').getAttribute('ovalue');
    validate_scope_data['supplier_product_properties']['Supplier_Product_Package_Type']['value'] = value;
    Dom.get('Supplier_Product_Package_Type').value = value

    if (ovalue != value) {
        validate_scope_data['supplier_product_properties']['Supplier_Product_Package_Type']['changed'] = true;
    } else {
        validate_scope_data['supplier_product_properties']['Supplier_Product_Package_Type']['changed'] = false;
    }
    validate_scope('supplier_product_properties')
}





function change_dimensions_shape_type(o, shape, parent) {

dialog_change_lenght_units.hide()

    options = Dom.getElementsByClassName('option', 'button', 'Supplier_Product_' + parent + '_Dimensions_Type_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    value = shape;
    ovalue = Dom.get('Supplier_Product_' + parent + '_Dimensions_Type').getAttribute('ovalue');
    validate_scope_data['supplier_product_properties']['Supplier_Product_' + parent + '_Dimensions_Type']['value'] = value;
    Dom.get('Supplier_Product_' + parent + '_Dimensions_Type').value = value

    if (ovalue != value) {
        validate_scope_data['supplier_product_properties']['Supplier_Product_' + parent + '_Dimensions_Type']['changed'] = true;
    } else {
        validate_scope_data['supplier_product_properties']['Supplier_Product_' + parent + '_Dimensions_Type']['changed'] = false;
    }

     Dom.setStyle(['Supplier_Product_' + parent + '_Dimensions_Width_tr', 'Supplier_Product_' + parent + '_Dimensions_Depth_tr', 'Supplier_Product_' + parent + '_Dimensions_Length_tr', 'Supplier_Product_' + parent + '_Dimensions_Diameter_tr'], 'display', 'none')
    Dom.setStyle(['Supplier_Product_' + parent + '_Dimensions_Display_Units_Width', 'Supplier_Product_' + parent + '_Dimensions_Display_Units_Length', 'Supplier_Product_' + parent + '_Dimensions_Display_Units_Diameter'], 'display', 'none')
  
 
  if (value == 'Rectangular') {
        Dom.setStyle(['Supplier_Product_' + parent + '_Dimensions_Width_tr', 'Supplier_Product_' + parent + '_Dimensions_Depth_tr', 'Supplier_Product_' + parent + '_Dimensions_Length_tr'], 'display', '')
        Dom.setStyle(['Supplier_Product_' + parent + '_Dimensions_Display_Units_Width'], 'display', '')
        
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Width_Display').value=Dom.get('Supplier_Product_'+parent+'_Dimensions_Width_Display').getAttribute('ovalue');
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Depth_Display').value=Dom.get('Supplier_Product_'+parent+'_Dimensions_Depth_Display').getAttribute('ovalue');
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Length_Display').value=Dom.get('Supplier_Product_'+parent+'_Dimensions_Length_Display').getAttribute('ovalue');
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Diameter_Display').value='';
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Diameter_Display', '');
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Width_Display', Dom.get('Supplier_Product_'+parent+'_Dimensions_Width_Display').value);
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Depth_Display', Dom.get('Supplier_Product_'+parent+'_Dimensions_Depth_Display').value);
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Length_Display', Dom.get('Supplier_Product_'+parent+'_Dimensions_Length_Display').value);


    } else if (value == 'Cilinder') {
        Dom.setStyle(['Supplier_Product_' + parent + '_Dimensions_Length_tr', 'Supplier_Product_' + parent + '_Dimensions_Diameter_tr'], 'display', '')
        Dom.setStyle(['Supplier_Product_' + parent + '_Dimensions_Display_Units_Length'], 'display', '')
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Length_Display').value=Dom.get('Supplier_Product_'+parent+'_Dimensions_Length_Display').getAttribute('ovalue');
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Diameter_Display').value=Dom.get('Supplier_Product_'+parent+'_Dimensions_Diameter_Display').getAttribute('ovalue');

        Dom.get('Supplier_Product_'+parent+'_Dimensions_Width_Display').value='';
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Depth_Display').value='';        
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Width_Display', '');
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Depth_Display', '');
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Length_Display', Dom.get('Supplier_Product_'+parent+'_Dimensions_Length_Display').value);
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Diameter_Display', Dom.get('Supplier_Product_'+parent+'_Dimensions_Diameter_Display').value);


    } else if (value == 'Sphere') {
        Dom.setStyle(['Supplier_Product_' + parent + '_Dimensions_Diameter_tr'], 'display', '')
        Dom.setStyle(['Supplier_Product_' + parent + '_Dimensions_Display_Units_Diameter'], 'display', '')
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Width_Display').value='';
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Depth_Display').value='';        
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Length_Display').value='';        
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Diameter_Display').value=Dom.get('Supplier_Product_'+parent+'_Dimensions_Diameter_Display').getAttribute('ovalue');
        
       // alert('Supplier_Product_'+parent+'_Dimensions_Width_Display')
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Width_Display', '');
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Depth_Display', '');
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Length_Display', '');
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Diameter_Display', Dom.get('Supplier_Product_'+parent+'_Dimensions_Diameter_Display').value);

    } else if (value == 'String') {
        Dom.setStyle(['Supplier_Product_' + parent + '_Dimensions_Length_tr'], 'display', '')
        Dom.setStyle(['Supplier_Product_' + parent + '_Dimensions_Display_Units_Length'], 'display', '')
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Width_Display').value='';
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Depth_Display').value='';        
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Diameter_Display').value='';
          Dom.get('Supplier_Product_'+parent+'_Dimensions_Length_Display').value=Dom.get('Supplier_Product_'+parent+'_Dimensions_Length_Display').getAttribute('ovalue');
      
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Width_Display', '');
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Depth_Display', '');
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Diameter_Display', '');
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Length_Display', Dom.get('Supplier_Product_'+parent+'_Dimensions_Length_Display').value);



    } else if (value == 'Sheet') {
        Dom.setStyle(['Supplier_Product_' + parent + '_Dimensions_Width_tr', 'Supplier_Product_' + parent + '_Dimensions_Length_tr'], 'display', '')
        Dom.setStyle(['Supplier_Product_' + parent + '_Dimensions_Display_Units_Width'], 'display', '')
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Depth_Display').value='';        
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Diameter_Display').value='';
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Width_Display').value=Dom.get('Supplier_Product_'+parent+'_Dimensions_Width_Display').getAttribute('ovalue');
        Dom.get('Supplier_Product_'+parent+'_Dimensions_Length_Display').value=Dom.get('Supplier_Product_'+parent+'_Dimensions_Length_Display').getAttribute('ovalue');

        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Depth_Display', '');
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Diameter_Display', '');
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Width_Display', Dom.get('Supplier_Product_'+parent+'_Dimensions_Width_Display').value);
        validate_general('supplier_product_properties', 'Supplier_Product_'+parent+'_Dimensions_Length_Display', Dom.get('Supplier_Product_'+parent+'_Dimensions_Length_Display').value);

    }
 

    validate_scope('supplier_product_properties')
}






function general_description_editor_changed(){
validate_scope_data['supplier_product_description']['general_description']['changed']=true;
validate_scope('supplier_product_description')
}



function health_and_safety_editor_changed(){
validate_scope_data['supplier_product_health_and_safety']['health_and_safety']['changed']=true;
validate_scope('supplier_product_health_and_safety')
}


function show_dialog_delete(delete_type, subject) {
    if (delete_type == 'delete' && subject == 'supplier_product_location_transaction') {
        dialog_delete_supplier_product_location_transaction.show()
    }
}


	

function show_dialog_change_units(e,type) {


    Dom.get('change_'+type+'_units_id').value = this.id;
    Dom.get('change_'+type+'_units_field').value = this.getAttribute('field');
    Dom.get('change_'+type+'_units_field_parent').value = this.getAttribute('parent');
    
    region1 = Dom.getRegion(this);
    region2 = Dom.getRegion('dialog_change_'+type+'_units');
    var pos = [region1.right - region2.width - 1, region1.bottom - 2]
    Dom.setXY('dialog_change_'+type+'_units', pos);
   
    if(type=='lenght')
    dialog_change_lenght_units.show()
	else
    dialog_change_weight_units.show()
	
}



function change_units(unit, type) {

    var parent = Dom.get('change_' + type + '_units_field_parent').value
    var element = Dom.get(Dom.get('change_' + type + '_units_id').value)
    element.innerHTML = '&#x21b6 ' + unit
    
    
    

    value = unit;
    ovalue = Dom.get(Dom.get('change_' + type + '_units_field').value).getAttribute('ovalue');
    validate_scope_data['supplier_product_properties'][Dom.get('change_' + type + '_units_field').value]['value'] = value;
    Dom.get(Dom.get('change_' + type + '_units_field').value).value = value
    //alert(value+' '+ovalue)
    if (ovalue != value) {
        validate_scope_data['supplier_product_properties'][Dom.get('change_' + type + '_units_field').value]['changed'] = true;
    } else {
        validate_scope_data['supplier_product_properties'][Dom.get('change_' + type + '_units_field').value]['changed'] = false;
    }

    validate_scope('supplier_product_properties')

    if (type == 'lenght'){ 
    
   	 Dom.get('Supplier_Product_'+parent+'_Dimensions_Display_Units_Width').innerHTML = '&#x21b6 ' + unit
	 Dom.get('Supplier_Product_'+parent+'_Dimensions_Display_Units_Length').innerHTML = '&#x21b6 ' + unit
   	 Dom.get('Supplier_Product_'+parent+'_Dimensions_Display_Units_Diameter').innerHTML = '&#x21b6 ' + unit
   
    
    	dialog_change_lenght_units.hide()
    }else{ 
    	dialog_change_weight_units.hide()
	}
}



function hide_dialog_delete(delete_type, subject) {
    if (delete_type == 'delete' && subject == 'supplier_product_location_transaction') {
        dialog_delete_supplier_product_location_transaction.hide()
    }
}

function show_supplier_product_health_and_safety_editor() {
    Dom.setStyle('show_supplier_product_health_and_safety_editor', 'display', 'none')

    Dom.setStyle('supplier_product_health_and_safety_editor_tr', 'display', '')
    Dom.setStyle('edit_supplier_product_health_and_safety_buttons', 'margin-left', '700px')
}


function check_if_MSDS_File_selected() {

    if (Dom.get('upload_MSDS_File_file').value == '') {
        Dom.addClass('upload_MSDS_File_button', 'disabled')
    } else {
        Dom.removeClass('upload_MSDS_File_button', 'disabled')
    }
}

function save_MSDS_File_attachment() {

    YAHOO.util.Connect.setForm('upload_MSDS_File_form', true, true);
    var request = 'ar_edit_suppliers.php?tipo=add_MSDS_file&sp_id=' + Dom.get('supplier_product_pid').value

    var uploadHandler = {

        upload: function(o) {
        	
           // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(base64_decode(o.responseText));

            if (r.state == 200) {
            	Dom.get('MSDS_File').innerHTML=r.newvalue.attach_info;
            	Dom.get('upload_MSDS_File_file').value='';
            	
				Dom.setStyle(['upload_MSDS_File_form','upload_MSDS_File_button'],'display','none')
				Dom.setStyle(['delete_MSDS_File','replace_MSDS_File'],'display','')
					var table = tables['table0'];
                        var datasource = table.getDataSource();
                        datasource.sendRequest('', table.onDataReturnInitializeTable, table);
				
            } else {
                dialog_attach.show();
                Dom.get('MSDS_File_msg').innerHTML = r.msg;
            }
        },

       

    };

    YAHOO.util.Connect.asyncRequest('POST', request, uploadHandler);

}

function delete_MSDS_File() {
    region1 = Dom.getRegion('delete_MSDS_File');
    region2 = Dom.getRegion('dialog_delete_MSDS_File');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_delete_MSDS_File', pos);
    dialog_delete_MSDS_File.show()
}

function save_delete_MSDS_File() {

    var request = 'ar_edit_suppliers.php?tipo=delete_MSDS_file&sp_id=' + Dom.get('supplier_product_pid').value
    //alert(request);
    //return;
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
        
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
				Dom.get('MSDS_File').innerHTML='';
            	Dom.get('upload_MSDS_File_file').value='';
            	
				Dom.setStyle(['upload_MSDS_File_form','upload_MSDS_File_button'],'display','')
				Dom.setStyle(['delete_MSDS_File','replace_MSDS_File'],'display','none');
				cancel_delete_MSDS_File();
				
				var table = tables['table0'];
                        var datasource = table.getDataSource();
                        datasource.sendRequest('', table.onDataReturnInitializeTable, table);
				
            } else {
                alert(r.msg)
            }
        }
    });


}

function cancel_delete_MSDS_File() {
    dialog_delete_MSDS_File.hide()
}


function replace_MSDS_File(){
            	Dom.get('upload_MSDS_File_file').value='';
            	
				Dom.setStyle(['upload_MSDS_File_form','upload_MSDS_File_button'],'display','')
				Dom.setStyle(['delete_MSDS_File','replace_MSDS_File'],'display','none');
				
}

function save_supplier_product_availability(state) {
    spp_key = Dom.get('edit_supplier_product_availability_spp_key').value;
    table_record_index = Dom.get('edit_supplier_product_availability_table_record_index').value;
    table_id = Dom.get('edit_supplier_product_availability_table_id').value;


    var request = 'ar_edit_suppliers.php?tipo=edit_supplier_product_part&key=state&newvalue=' + state + '&sppl_key=' + spp_key + '&okey=state&table_record_index=' + table_record_index + '&sp_id=' + Dom.get('supplier_product_pid').value
    alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
               alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);


            if (r.state == 200) {

                var table = tables['table' + Dom.get('edit_supplier_product_availability_table_id').value];
                record = table.getRecord(r.record_index);
                var data = record.getData();
                data['state'] = r.state_formated;
                data['state_value'] = r.state_value;

                table.updateRow(r.record_index, data);

                dialog_edit_supplier_product_availability.hide()

                if (r.state_value == 'Unlink') {

                    table.deleteRow(parseInt(r.record_index));

                    Dom.setStyle('historic_supplier_products', 'display', '')
                    
                      table_id = 5


                    var table = tables['table' + table_id];
                    var datasource = tables['dataSource' + table_id];
                    datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                    
                    table_id = 0


                    var table = tables['table' + table_id];
                    var datasource = tables['dataSource' + table_id];
                    datasource.sendRequest('', table.onDataReturnInitializeTable, table);

                }
/*
                if(Dom.get('edit_flag_table_id').value==0){
                table_id = 3
                }else{
                table_id = 0
                }

                var table = tables['table' + table_id];
                var datasource = tables['dataSource' + table_id];
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
*/

            }

        }
    });

}



function show_cell_dialog(datatable, oArgs) {

    var target = oArgs.target;
    var column = datatable.getColumn(target);
    var record = datatable.getRecord(target);
    var recordIndex = datatable.getRecordIndex(record);
    switch (column.object) {
    

    case 'supplier_product_part':

       Dom.get('edit_supplier_product_availability_spp_key').value = record.getData('sppl_key');
       Dom.get('edit_supplier_product_availability_table_record_index').value=recordIndex;
       Dom.get('edit_supplier_product_availability_table_id').value=2;
       
       
       
      
       
    //   Dom.removeClass(Dom.getElementsByClassName('buttons','button','supplier_product_availability_operations'),'selected')
     
      // Dom.addClass('supplier_product_availability_'+record.getData('state_value'),'selected')
       
       

        region1 = Dom.getRegion(target);
        region2 = Dom.getRegion('dialog_edit_supplier_product_availability');
        var pos = [region1.left-region2.width , region1.top]
        Dom.setXY('dialog_edit_supplier_product_availability', pos);
        dialog_edit_supplier_product_availability.show();
        break;
        
       
    }

}

function show_dialog_supplier_list(){
 region1 = Dom.getRegion('edit_supplier_product_supplier');
    region2 = Dom.getRegion('dialog_supplier_list');
    var pos = [region1.right + 8, region1.top - 2]
    Dom.setXY('dialog_supplier_list', pos);

dialog_supplier_list.show()
}

function init() {

init_search('supplier_products_supplier');



 validate_scope_data = {
   'supplier_product_cost':{
    'Supplier_Product_Cost_Per_Case': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
						'dbname': 'Supplier Product Cost Per Case',

			'name': 'Supplier_Product_Cost_Per_Case',
			'ar':false,'validation':[{'regexp':money_regex,'invalid_msg':'<?php echo _('Invalid Cost')?>'}]
    }}
     , 'supplier_product_supplier':{
    'Supplier_Product_Supplier_Key': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'name': 'Supplier_Product_Supplier_Key',
			'ar': false,
			'validation':false
    }}
	,'supplier_product_state': {

		'Supplier_Product_State': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product State',
			'name': 'Supplier_Product_State',
			'ar': false,
			'validation':false
			
		}
		

		
	}
	,'supplier_product_unit': {
		'name': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Name',
			'name': 'Supplier_Product_Name',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d]+",
				'invalid_msg': '<?php echo _('Invalid supplier product name')?>'
				
			}]
			
		}
		,'tariff_code': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Tariff Code',
			'name': 'Supplier_Product_Tariff_Code',
			'ar': false,
			'validation': [{
				'regexp': "\\d",
				'invalid_msg': '<?php echo _('Invalid Tariff Code')?>'
				
			}]
			
		}
		,'code': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Code',
			'name': 'Supplier_Product_Code',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d\\-\\s]+",
				'invalid_msg': '<?php echo _('Invalid code')?>'
				
			}]
			
		}
				,'Units_Per_Case': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Units Per Case',
			'name': 'Supplier_Product_Units_Per_Case',
			'ar': false,
			'validation':[{'regexp':"\\d",'invalid_msg':'<?php echo _('Invalid Number')?>'}]
			
		}
		,'duty_rate': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Duty Rate',
			'name': 'Supplier_Product_Duty_Rate',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d]+",
				'invalid_msg': '<?php echo _('Invalid Duty Rate')?>'
				
			}]
			
		}
		,'origin': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Origin Country Code',
			'name': 'Supplier_Product_Origin_Country_Code',
			'ar': false,
			'validation':false
			
		}
		
		,'Barcode_Type': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Barcode Type',
			'name': 'Supplier_Product_Barcode_Type',
			'ar': false,
			'validation':false
			
		}
		,'Barcode_Data_Source': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Barcode Data Source',
			'name': 'Supplier_Product_Barcode_Data_Source',
			'ar': false,
			'validation':false
			
		}
		,'Barcode_Data': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Barcode Data',
			'name': 'Supplier_Product_Barcode_Data',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d]+",
				'invalid_msg': '<?php echo _('Invalid Barcode')?>'
				
			}]
			
		}

		
	}
	,'supplier_product_properties': {
		
	    'Supplier_Product_Package_Type': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Package Type',
			'name': 'Supplier_Product_Package_Type',
			'ar': false
			
		},
		'Supplier_Product_Package_Weight_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Package Weight Display',
			'name': 'Supplier_Product_Package_Weight_Display',
			'ar': false,
			'validation': [{
				'regexp': "(\\d|\.)",
				'invalid_msg': '<?php echo _('Invalid Weight')?>'}]
		},
		'Supplier_Product_Package_Weight_Display_Units': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Package Weight Display Units',
			'name': 'Supplier_Product_Package_Weight_Display_Units',
			'ar': false
			
		},
		'Supplier_Product_Package_Dimensions_Type': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Package Dimensions Type',
			'name': 'Supplier_Product_Package_Dimensions_Type',
			'ar': false
			
		},
		'Supplier_Product_Package_Dimensions_Width_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Package Dimensions Width Display',
			'name': 'Supplier_Product_Package_Dimensions_Width_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},
		'Supplier_Product_Package_Dimensions_Depth_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Package Dimensions Depth Display',
			'name': 'Supplier_Product_Package_Dimensions_Depth_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},
		'Supplier_Product_Package_Dimensions_Length_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Package Dimensions Length Display',
			'name': 'Supplier_Product_Package_Dimensions_Length_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},		
		'Supplier_Product_Package_Dimensions_Diameter_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Package Dimensions Diameter Display',
			'name': 'Supplier_Product_Package_Dimensions_Diameter_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},
		'Supplier_Product_Package_Dimensions_Display_Units': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Package Dimensions Display Units',
			'name': 'Supplier_Product_Package_Dimensions_Display_Units',
			'ar': false
			
		},
	    'Supplier_Product_Unit_Type': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Unit Type',
			'name': 'Supplier_Product_Unit_Type',
			'ar': false
			
		},
		'Supplier_Product_Unit_Weight_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Unit Weight Display',
			'name': 'Supplier_Product_Unit_Weight_Display',
			'ar': false,
			'validation': [{
				'regexp': "(\\d|\.)",
				'invalid_msg': '<?php echo _('Invalid Weight')?>'}]
		},
		'Supplier_Product_Unit_Weight_Display_Units': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Unit Weight Display Units',
			'name': 'Supplier_Product_Unit_Weight_Display_Units',
			'ar': false
			
		},
		'Supplier_Product_Unit_Dimensions_Type': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Unit Dimensions Type',
			'name': 'Supplier_Product_Unit_Dimensions_Type',
			'ar': false
			
		},
		'Supplier_Product_Unit_Dimensions_Width_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Unit Dimensions Width Display',
			'name': 'Supplier_Product_Unit_Dimensions_Width_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},
		'Supplier_Product_Unit_Dimensions_Depth_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Unit Dimensions Depth Display',
			'name': 'Supplier_Product_Unit_Dimensions_Depth_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},
		'Supplier_Product_Unit_Dimensions_Length_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Unit Dimensions Length Display',
			'name': 'Supplier_Product_Unit_Dimensions_Length_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},		
		'Supplier_Product_Unit_Dimensions_Diameter_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Unit Dimensions Diameter Display',
			'name': 'Supplier_Product_Unit_Dimensions_Diameter_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},
		'Supplier_Product_Unit_Dimensions_Display_Units': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Unit Dimensions Display Units',
			'name': 'Supplier_Product_Unit_Dimensions_Display_Units',
			'ar': false
			
		},		
		
		
		
		
	}
	,'supplier_product_description': {
		'general_description': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 2,
			'type': 'item',
			'dbname': 'Supplier Product Description',
			'name': 'supplier_product_general_description',
			'ar': false,
			'validation': false
		}
	}
	,'supplier_product_health_and_safety': {
		'UN_Number': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product UN Number',
			'name': 'Supplier_Product_UN_Number',
			'ar': false,
			'validation': [{
	'regexp': "^\\d{0,4}$",

				'invalid_msg': '<?php echo _('Invalid UN Number')?>'
				
			}]
			
		}
		,'UN_Number_Class': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product UN Class',
			'name': 'Supplier_Product_UN_Number_Class',
			'ar': false,
			'validation': [{
				'regexp': "^[\\d\\.]{0,2}$",
				'invalid_msg': '<?php echo _('Invalid UN Number')?>'
				
			}]
			
		}
		,'Packing_Group': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Packing Group',
			'name': 'Supplier_Product_Packing_Group',
			'ar': false
			
		}
		,'Supplier_Product_Proper_Shipping_Name': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Proper Shipping Name',
			'name': 'Supplier_Product_Proper_Shipping_Name',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d]+",
				'invalid_msg': '<?php echo _('Part Proper Shipping Name')?>'
				
			}]
			
		}		,'Supplier_Product_Hazard_Indentification_Number': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Supplier Product Hazard Indentification Number',
			'name': 'Supplier_Product_Hazard_Indentification_Number',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d]+",
				'invalid_msg': '<?php echo _('Invalid Hazard Indentification Number')?>'
				
			}]
			
		}
		,'health_and_safety': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 2,
			'type': 'item',
			'dbname': 'Supplier Product Health And Safety',
			'name': 'supplier_product_health_and_safety',
			'ar': false,
			'validation': false
		}
	}
	
	,'supplier_product_custom_field': {
		 <?php
		 $i = 0;
		foreach($show_case as $custom_key =>$custom_value) {
			if ($i) print ",";
			printf("'custom_field_supplier_product_%s':{'changed':false,'validated':true,'required':true,'group':3,'type':'item','name':'Supplier_Product_%s', 'dbname':'%s','ar':false, 'validation':[{'regexp':\"[a-z\\d]+\",'invalid_msg':'Invalid %s'}]}\n", 
			$custom_value['lable'], 
			$custom_value['lable'], 
			$custom_value['lable'], 
			$custom_key
			);
			$i++;

			
		}

		 ?>

		
	}

	
};



 validate_scope_metadata = {
     'supplier_product_supplier':{'type':'edit','ar_file':'ar_edit_suppliers.php','key_name':'sp_id','key':Dom.get('supplier_product_pid').value}
     ,'supplier_product_cost':{'type':'edit','ar_file':'ar_edit_suppliers.php','key_name':'sp_id','key':Dom.get('supplier_product_pid').value}

	,'supplier_product_state': {
		'type': 'edit',
		'ar_file': 'ar_edit_suppliers.php',
		'key_name': 'sp_id',
		'key': Dom.get('supplier_product_pid').value
		
	}
		,'supplier_product_unit': {
		'type': 'edit',
		'ar_file': 'ar_edit_suppliers.php',
		'key_name': 'sp_id',
		'key': Dom.get('supplier_product_pid').value
		
	}
	,'supplier_product_properties': {
		'type': 'edit',
		'ar_file': 'ar_edit_suppliers.php',
		'key_name': 'sp_id',
		'key': Dom.get('supplier_product_pid').value
		
	}
	,'supplier_product_description': {
		'type': 'edit',
		'ar_file': 'ar_edit_suppliers.php',
		'key_name': 'sp_id',
		'key': Dom.get('supplier_product_pid').value
		
	}
	,'supplier_product_health_and_safety': {
		'type': 'edit',
		'ar_file': 'ar_edit_suppliers.php',
		'key_name': 'sp_id',
		'key': Dom.get('supplier_product_pid').value
		
	}
	,'supplier_product_custom_field': {
		'type': 'edit',
		'ar_file': 'ar_edit_suppliers.php',
		'key_name': 'sp_id',
		'key': Dom.get('supplier_product_pid').value
		
	}

	
};


 	
	
	
 	dialog_change_weight_units = new YAHOO.widget.Dialog("dialog_change_weight_units", {visible : false,close:true,underlay: "none",draggable:false});
	dialog_change_weight_units.render();	
	dialog_change_lenght_units = new YAHOO.widget.Dialog("dialog_change_lenght_units", {visible : false,close:true,underlay: "none",draggable:false});
	dialog_change_lenght_units.render();	
		
    var ids = ["description", "parts"];
    Event.addListener(ids, "click", change_block);



    var ids = ["description_block_status", "description_block_description", "description_block_properties", "description_block_pictures", "description_block_info", "description_block_health_and_safety","description_block_supplier","description_block_cost"];
    Event.addListener(ids, "click", change_properties_block);



    YAHOO.util.Event.on('show_supplier_product_health_and_safety_editor', 'click', show_supplier_product_health_and_safety_editor);

    YAHOO.util.Event.on('uploadButton', 'click', upload_image);

    Event.addListener("upload_MSDS_File_file", "change", check_if_MSDS_File_selected);
    Event.addListener("upload_MSDS_File_button", "click", save_MSDS_File_attachment);
    Event.addListener("delete_MSDS_File", "click", delete_MSDS_File);
    Event.addListener("replace_MSDS_File", "click", replace_MSDS_File);

    Event.addListener("Supplier_Product_Unit_Dimensions_Display_Units_Length", "click", show_dialog_change_units,'lenght');
    Event.addListener("Supplier_Product_Unit_Dimensions_Display_Units_Width", "click", show_dialog_change_units,'lenght');


    Event.addListener("Supplier_Product_Unit_Dimensions_Display_Units_Diameter", "click", show_dialog_change_units,'lenght');
    Event.addListener("Supplier_Product_Unit_Weight_Display_Units_button", "click", show_dialog_change_units,'weight');
    Event.addListener("Supplier_Product_Package_Dimensions_Display_Units_Length", "click", show_dialog_change_units,'lenght');
    Event.addListener("Supplier_Product_Package_Dimensions_Display_Units_Width", "click", show_dialog_change_units,'lenght');
    Event.addListener("Supplier_Product_Package_Dimensions_Display_Units_Diameter", "click", show_dialog_change_units,'lenght');
    Event.addListener("Supplier_Product_Package_Weight_Display_Units_button", "click", show_dialog_change_units,'weight');






    Event.addListener("save_delete_MSDS_File", "click", save_delete_MSDS_File);
    Event.addListener("cancel_delete_MSDS_File", "click", cancel_delete_MSDS_File);


 dialog_edit_supplier_product_availability = new YAHOO.widget.Dialog("dialog_edit_supplier_product_availability", {
       
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_edit_supplier_product_availability.render();




dialog_delete_MSDS_File =  new YAHOO.widget.Dialog("dialog_delete_MSDS_File", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_delete_MSDS_File.render();
    

    Event.addListener('save_edit_supplier_product_state', "click", save_edit_supplier_product_state);
    Event.addListener('reset_edit_supplier_product_state', "click", reset_edit_supplier_product_state);


  	Event.addListener('save_edit_supplier_product_unit', "click", save_edit_supplier_product_unit);
    Event.addListener('reset_edit_supplier_product_unit', "click", reset_edit_supplier_product_unit);


    Event.addListener('save_edit_supplier_product_description', "click", save_edit_supplier_product_description);
    Event.addListener('reset_edit_supplier_product_description', "click", reset_edit_supplier_product_description);

    Event.addListener('save_edit_supplier_product_health_and_safety', "click", save_edit_supplier_product_health_and_safety);
    Event.addListener('reset_edit_supplier_product_health_and_safety', "click", reset_edit_supplier_product_health_and_safety);




    Event.addListener('save_edit_supplier_product_custom_field', "click", save_edit_custom_field);
    Event.addListener('reset_edit_supplier_product_custom_field', "click", reset_edit_custom_field);

  Event.addListener('save_edit_supplier_product_properties', "click", save_edit_supplier_product_properties);
    Event.addListener('reset_edit_supplier_product_properties', "click", reset_edit_supplier_product_properties);

Event.addListener('save_edit_supplier_product_cost', "click", save_edit_cost);
    Event.addListener('reset_edit_supplier_product_cost', "click", reset_edit_cost);


Event.addListener('save_edit_supplier_product_supplier', "click", save_edit_supplier);
    Event.addListener('reset_edit_supplier_product_supplier', "click", reset_edit_supplier);

    // Event.addListener('save_edit_supplier_product_price', "click", save_edit_price);
    //Event.addListener('reset_edit_supplier_product_price', "click", reset_edit_price);
    //Event.addListener('save_edit_supplier_product_weight', "click", save_edit_weight);
    //Event.addListener('reset_edit_supplier_product_weight', "click", reset_edit_weight);


    var supplier_product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Name);
    supplier_product_name_oACDS.queryMatchContains = true;
    var supplier_product_name_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Name", "Supplier_Product_Name_Container", supplier_product_name_oACDS);
    supplier_product_name_oAutoComp.minQueryLength = 0;
    supplier_product_name_oAutoComp.queryDelay = 0.1;

 var supplier_product_cost_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Cost_Per_Case);
    supplier_product_cost_oACDS.queryMatchContains = true;
    var supplier_product_cost_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Cost_Per_Case", "Supplier_Product_Cost_Per_Case_Container", supplier_product_cost_oACDS);
    supplier_product_cost_oAutoComp.minQueryLength = 0;
    supplier_product_cost_oAutoComp.queryDelay = 0.1;



    var Supplier_Product_Package_Weight_Display_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Package_Weight_Display);
    Supplier_Product_Package_Weight_Display_oACDS.queryMatchContains = true;
    var Supplier_Product_Package_Weight_Display_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Package_Weight_Display", "Supplier_Product_Package_Weight_Display_Container", Supplier_Product_Package_Weight_Display_oACDS);
    Supplier_Product_Package_Weight_Display_oAutoComp.minQueryLength = 0;
    Supplier_Product_Package_Weight_Display_oAutoComp.queryDelay = 0.1;
    
      var Supplier_Product_Unit_Weight_Display_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Unit_Weight_Display);
    Supplier_Product_Unit_Weight_Display_oACDS.queryMatchContains = true;
    var Supplier_Product_Unit_Weight_Display_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Unit_Weight_Display", "Supplier_Product_Unit_Weight_Display_Container", Supplier_Product_Unit_Weight_Display_oACDS);
    Supplier_Product_Unit_Weight_Display_oAutoComp.minQueryLength = 0;
    Supplier_Product_Unit_Weight_Display_oAutoComp.queryDelay = 0.1;
  
    
    var Supplier_Product_Package_Dimensions_Width_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Package_Dimensions_Width_Display);
    Supplier_Product_Package_Dimensions_Width_oACDS.queryMatchContains = true;
    var Supplier_Product_Package_Dimensions_Width_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Package_Dimensions_Width_Display", "Supplier_Product_Package_Dimensions_Width_Display_Container", Supplier_Product_Package_Dimensions_Width_oACDS);
    Supplier_Product_Package_Dimensions_Width_oAutoComp.minQueryLength = 0;
    Supplier_Product_Package_Dimensions_Width_oAutoComp.queryDelay = 0.1;
    
    var Supplier_Product_Package_Dimensions_Depth_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Package_Dimensions_Depth_Display);
    Supplier_Product_Package_Dimensions_Depth_oACDS.queryMatchContains = true;
    var Supplier_Product_Package_Dimensions_Depth_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Package_Dimensions_Depth_Display", "Supplier_Product_Package_Dimensions_Depth_Display_Container", Supplier_Product_Package_Dimensions_Depth_oACDS);
    Supplier_Product_Package_Dimensions_Depth_oAutoComp.minQueryLength = 0;
    Supplier_Product_Package_Dimensions_Depth_oAutoComp.queryDelay = 0.1;
    
    var Supplier_Product_Package_Dimensions_Length_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Package_Dimensions_Length_Display);
    Supplier_Product_Package_Dimensions_Length_oACDS.queryMatchContains = true;
    var Supplier_Product_Package_Dimensions_Length_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Package_Dimensions_Length_Display", "Supplier_Product_Package_Dimensions_Length_Display_Container", Supplier_Product_Package_Dimensions_Length_oACDS);
    Supplier_Product_Package_Dimensions_Length_oAutoComp.minQueryLength = 0;
    Supplier_Product_Package_Dimensions_Length_oAutoComp.queryDelay = 0.1;
   
    var Supplier_Product_Package_Dimensions_Diameter_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Package_Dimensions_Diameter_Display);
    Supplier_Product_Package_Dimensions_Diameter_oACDS.queryMatchContains = true;
    var Supplier_Product_Package_Dimensions_Diameter_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Package_Dimensions_Diameter_Display", "Supplier_Product_Package_Dimensions_Diameter_Display_Container", Supplier_Product_Package_Dimensions_Diameter_oACDS);
    Supplier_Product_Package_Dimensions_Diameter_oAutoComp.minQueryLength = 0;
    Supplier_Product_Package_Dimensions_Diameter_oAutoComp.queryDelay = 0.1;    
    
 
    var Supplier_Product_Unit_Dimensions_Width_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Unit_Dimensions_Width_Display);
    Supplier_Product_Unit_Dimensions_Width_oACDS.queryMatchContains = true;
    var Supplier_Product_Unit_Dimensions_Width_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Unit_Dimensions_Width_Display", "Supplier_Product_Unit_Dimensions_Width_Display_Container", Supplier_Product_Unit_Dimensions_Width_oACDS);
    Supplier_Product_Unit_Dimensions_Width_oAutoComp.minQueryLength = 0;
    Supplier_Product_Unit_Dimensions_Width_oAutoComp.queryDelay = 0.1;
    
    var Supplier_Product_Unit_Dimensions_Depth_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Unit_Dimensions_Depth_Display);
    Supplier_Product_Unit_Dimensions_Depth_oACDS.queryMatchContains = true;
    var Supplier_Product_Unit_Dimensions_Depth_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Unit_Dimensions_Depth_Display", "Supplier_Product_Unit_Dimensions_Depth_Display_Container", Supplier_Product_Unit_Dimensions_Depth_oACDS);
    Supplier_Product_Unit_Dimensions_Depth_oAutoComp.minQueryLength = 0;
    Supplier_Product_Unit_Dimensions_Depth_oAutoComp.queryDelay = 0.1;
    
    var Supplier_Product_Unit_Dimensions_Length_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Unit_Dimensions_Length_Display);
    Supplier_Product_Unit_Dimensions_Length_oACDS.queryMatchContains = true;
    var Supplier_Product_Unit_Dimensions_Length_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Unit_Dimensions_Length_Display", "Supplier_Product_Unit_Dimensions_Length_Display_Container", Supplier_Product_Unit_Dimensions_Length_oACDS);
    Supplier_Product_Unit_Dimensions_Length_oAutoComp.minQueryLength = 0;
    Supplier_Product_Unit_Dimensions_Length_oAutoComp.queryDelay = 0.1;
   
    var Supplier_Product_Unit_Dimensions_Diameter_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Unit_Dimensions_Diameter_Display);
    Supplier_Product_Unit_Dimensions_Diameter_oACDS.queryMatchContains = true;
    var Supplier_Product_Unit_Dimensions_Diameter_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Unit_Dimensions_Diameter_Display", "Supplier_Product_Unit_Dimensions_Diameter_Display_Container", Supplier_Product_Unit_Dimensions_Diameter_oACDS);
    Supplier_Product_Unit_Dimensions_Diameter_oAutoComp.minQueryLength = 0;
    Supplier_Product_Unit_Dimensions_Diameter_oAutoComp.queryDelay = 0.1;    




    var supplier_product_Tariff_Code_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Tariff_Code);
    supplier_product_Tariff_Code_oACDS.queryMatchContains = true;
    var supplier_product_gross_weight_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Tariff_Code", "Supplier_Product_Tariff_Code_Container", supplier_product_Tariff_Code_oACDS);
    supplier_product_gross_weight_oAutoComp.minQueryLength = 0;
    supplier_product_gross_weight_oAutoComp.queryDelay = 0.1;


    var supplier_product_Duty_Rate_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Duty_Rate);
    supplier_product_Duty_Rate_oACDS.queryMatchContains = true;
    var supplier_product_duty_rate_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Duty_Rate", "Supplier_Product_Duty_Rate_Container", supplier_product_Duty_Rate_oACDS);
    supplier_product_duty_rate_oAutoComp.minQueryLength = 0;
    supplier_product_duty_rate_oAutoComp.queryDelay = 0.1;


  var supplier_product_code_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Units_Per_Case);
    supplier_product_code_oACDS.queryMatchContains = true;
    var supplier_product_code_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Units_Per_Case", "Supplier_Product_Units_Per_Case_Container", supplier_product_code_oACDS);
    supplier_product_code_oAutoComp.minQueryLength = 0;
    supplier_product_code_oAutoComp.queryDelay = 0.1;


    var supplier_product_code_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Code);
    supplier_product_code_oACDS.queryMatchContains = true;
    var supplier_product_code_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Code", "Supplier_Product_Code_Container", supplier_product_code_oACDS);
    supplier_product_code_oAutoComp.minQueryLength = 0;
    supplier_product_code_oAutoComp.queryDelay = 0.1;


    var supplier_product_barcode_data_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Barcode_Data);
    supplier_product_barcode_data_oACDS.queryMatchContains = true;
    var supplier_product_barcode_data_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Barcode_Data", "Supplier_Product_Barcode_Data_Container", supplier_product_barcode_data_oACDS);
    supplier_product_barcode_data_oAutoComp.minQueryLength = 0;
    supplier_product_barcode_data_oAutoComp.queryDelay = 0.1;
    
    
    var supplier_product_un_number_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_UN_Number);
    supplier_product_un_number_oACDS.queryMatchContains = true;
    var supplier_product_un_number_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_UN_Number", "Supplier_Product_UN_Number_Container", supplier_product_un_number_oACDS);
    supplier_product_un_number_oAutoComp.minQueryLength = 0;
    supplier_product_un_number_oAutoComp.queryDelay = 0.1;
    
    var supplier_product_un_number_class_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_UN_Number_Class);
    supplier_product_un_number_class_oACDS.queryMatchContains = true;
    var supplier_product_un_number_class_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_UN_Number_Class", "Supplier_Product_UN_Number_Class_Container", supplier_product_un_number_class_oACDS);
    supplier_product_un_number_class_oAutoComp.minQueryLength = 0;
    supplier_product_un_number_class_oAutoComp.queryDelay = 0.1;
        
  	var supplier_product_proper_shipping_name_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Proper_Shipping_Name);
    supplier_product_proper_shipping_name_oACDS.queryMatchContains = true;
    var supplier_product_proper_shipping_name_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Proper_Shipping_Name", "Supplier_Product_Proper_Shipping_Name_Container", supplier_product_proper_shipping_name_oACDS);
    supplier_product_proper_shipping_name_oAutoComp.minQueryLength = 0;
    supplier_product_proper_shipping_name_oAutoComp.queryDelay = 0.1;
    
      var supplier_product_hin_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_Hazard_Indentification_Number);
    supplier_product_hin_oACDS.queryMatchContains = true;
    var supplier_product_hin_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Hazard_Indentification_Number", "Supplier_Product_Hazard_Indentification_Number_Container", supplier_product_hin_oACDS);
    supplier_product_hin_oAutoComp.minQueryLength = 0;
    supplier_product_hin_oAutoComp.queryDelay = 0.1;


    var supplier_product_general_description_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_General_Description);
    supplier_product_general_description_oACDS.queryMatchContains = true;
    var supplier_product_general_description_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_General_Description", "Supplier_Product_General_Description_Container", supplier_product_general_description_oACDS);
    supplier_product_general_description_oAutoComp.minQueryLength = 0;
    supplier_product_general_description_oAutoComp.queryDelay = 0.1;

    var supplier_product_has_description_oACDS = new YAHOO.util.FunctionDataSource(validate_Supplier_Product_HAS_Description);
    supplier_product_has_description_oACDS.queryMatchContains = true;
    var supplier_product_gross_weight_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Product_Health_And_Safety", "Supplier_Product_Health_And_Safety_Container", supplier_product_has_description_oACDS);
    supplier_product_gross_weight_oAutoComp.minQueryLength = 0;
    supplier_product_gross_weight_oAutoComp.queryDelay = 0.1;


	 <?php

	 foreach($show_case as $custom_key =>$custom_value) {
		printf("var supplier_product_%s_oACDS = new YAHOO.util.FunctionDataSource(validate_supplier_product_%s);\nsupplier_product_%s_oACDS.queryMatchContains = true;\nvar supplier_product_%s_oAutoComp = new YAHOO.widget.AutoComplete('Supplier_Product_%s','Supplier_Product_%s_Container', supplier_product_%s_oACDS);\nsupplier_product_%s_oAutoComp.minQueryLength = 0;\nsupplier_product_%s_oAutoComp.queryDelay = 0.1;", 
		$custom_value['lable'], 
		$custom_value['lable'], 
		$custom_value['lable'], 
		$custom_value['lable'], 
		$custom_value['lable'], 
		$custom_value['lable'], 
		$custom_value['lable'], 
		$custom_value['lable'], 
		$custom_value['lable']
		);

		
	}

	 ?>



  


	
       var myConfig = {
        height: '300px',
        width: '890px',
        animate: true,
        dompath: true,
        focusAtStart: false,
          toolbar: {
        titlebar: 'My Editor',
        buttons: [
    { group: 'fontstyle', label: 'Font Name and Size',
        buttons: [
            { type: 'select', label: 'Arial', value: 'fontname', disabled: true,
                menu: [
                    { text: 'Arial', checked: true },
                    { text: 'Arial Black' },
                    { text: 'Comic Sans MS' },
                    { text: 'Courier New' },
                    { text: 'Lucida Console' },
                    { text: 'Tahoma' },
                    { text: 'Times New Roman' },
                    { text: 'Trebuchet MS' },
                    { text: 'Verdana' }
                ]
            },
            { type: 'spin', label: '13', value: 'fontsize', range: [ 9, 75 ], disabled: true }
        ]
    },
    { type: 'separator' },
    { group: 'textstyle', label: 'Font Style',
        buttons: [
            { type: 'push', label: 'Bold CTRL + SHIFT + B', value: 'bold' },
            { type: 'push', label: 'Italic CTRL + SHIFT + I', value: 'italic' },
            { type: 'push', label: 'Underline CTRL + SHIFT + U', value: 'underline' },
          
            { type: 'separator' },
            { type: 'color', label: 'Font Color', value: 'forecolor', disabled: true },
            { type: 'color', label: 'Background Color', value: 'backcolor', disabled: true },
            { type: 'separator' },
            { type: 'push', label: 'Remove Formatting', value: 'removeformat', disabled: true },
          
        ]
    },
    { type: 'separator' },
    { group: 'alignment', label: 'Alignment',
        buttons: [
            { type: 'push', label: 'Align Left CTRL + SHIFT + [', value: 'justifyleft' },
            { type: 'push', label: 'Align Center CTRL + SHIFT + |', value: 'justifycenter' },
            { type: 'push', label: 'Align Right CTRL + SHIFT + ]', value: 'justifyright' },
            { type: 'push', label: 'Justify', value: 'justifyfull' }
        ]
    },
    { type: 'separator' },
    { group: 'parastyle', label: 'Style',
        buttons: [
        { type: 'select', label: 'Normal', value: 'heading', disabled: true,
            menu: [
                { text: 'Normal', value: 'none', checked: true },
                { text: 'Header 1', value: 'h1' },
                { text: 'Header 2', value: 'h2' },
                { text: 'Header 3', value: 'h3' },
                { text: 'Header 4', value: 'h4' },
                { text: 'Header 5', value: 'h5' },
                { text: 'Header 6', value: 'h6' }
            ]
        }
        ]
    },
    { type: 'separator' },
    { group: 'indentlist', label: 'Lists',
        buttons: [
          
            { type: 'push', label: 'Create an Unordered List', value: 'insertunorderedlist' },
            { type: 'push', label: 'Create an Ordered List', value: 'insertorderedlist' }
        ]
    },
    { type: 'separator' },
    { group: 'insertitem', label: 'Insert Item',
        buttons: [
            { type: 'push', label: 'HTML Link CTRL + SHIFT + L', value: 'createlink', disabled: true },
            { type: 'push', label: 'Insert Image', value: 'insertimage' }
        ]
    }
	]

    }
        
    };
    
  
    GeneralDescriptionEditor = new YAHOO.widget.Editor('supplier_product_general_description', myConfig);
    GeneralDescriptionEditor.on('toolbarLoaded', function() {
         this.on('editorKeyUp', general_description_editor_changed, this, true);
                this.on('editorDoubleClick', general_description_editor_changed, this, true);
                this.on('editorMouseDown', general_description_editor_changed, this, true);
                this.on('buttonClick', general_description_editor_changed, this, true);
    }, GeneralDescriptionEditor, true);
    yuiImgUploader(GeneralDescriptionEditor, 'supplier_product_general_description', 'ar_upload_file_from_editor.php','image');
    GeneralDescriptionEditor.render();

    HealthAndSafetyEditor = new YAHOO.widget.Editor('supplier_product_health_and_safety', myConfig);
    HealthAndSafetyEditor.on('toolbarLoaded', function() {
         this.on('editorKeyUp',  health_and_safety_editor_changed, this, true);
                this.on('editorDoubleClick', health_and_safety_editor_changed, this, true);
                this.on('editorMouseDown', health_and_safety_editor_changed, this, true);
                this.on('buttonClick', health_and_safety_editor_changed, this, true);
    }, HealthAndSafetyEditor, true);
    yuiImgUploader(HealthAndSafetyEditor, 'supplier_product_health_and_safety', 'ar_upload_file_from_editor.php','image');
    HealthAndSafetyEditor.render();



    dialog_country_list = new YAHOO.widget.Dialog("dialog_country_list", {
       
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_country_list.render();
    Event.addListener("set_Supplier_Product_Origin_Country_Code", "click", show_dialog_country_list);
    Event.addListener("update_Supplier_Product_Origin_Country_Code", "click", show_dialog_country_list);



 dialog_supplier_list = new YAHOO.widget.Dialog("dialog_supplier_list", {
       
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_supplier_list.render();
    Event.addListener("edit_supplier_product_supplier", "click", show_dialog_supplier_list);


	var oACDS0 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS0.queryMatchContains = true;
    oACDS0.table_id = 0;
    var oAutoComp0 = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS0);
    oAutoComp0.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    YAHOO.util.Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);

	var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS1.queryMatchContains = true;
    oACDS1.table_id = 1;
    var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS1);
    oAutoComp1.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
    YAHOO.util.Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);

    var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS2.queryMatchContains = true;
    oACDS2.table_id = 2;
    var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2", "f_container2", oACDS2);
    oAutoComp2.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
    YAHOO.util.Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);

    var oACDS3 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS3.queryMatchContains = true;
    oACDS3.table_id = 3;
    var oAutoComp3 = new YAHOO.widget.AutoComplete("f_input3", "f_container3", oACDS3);
    oAutoComp3.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show3', "click", show_filter, 3);
    YAHOO.util.Event.addListener('clean_table_filter_hide3', "click", hide_filter, 3);

    var oACDS4 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS4.queryMatchContains = true;
    oACDS4.table_id = 4;
    var oAutoComp4 = new YAHOO.widget.AutoComplete("f_input4", "f_container4", oACDS4);
    oAutoComp4.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show4', "click", show_filter, 4);
    YAHOO.util.Event.addListener('clean_table_filter_hide4', "click", hide_filter, 4);


}


function show_dialog_country_list(){
	region1 = Dom.getRegion(this); 
    region2 = Dom.getRegion('dialog_country_list'); 
	var pos =[region1.right+5,region1.top-120]
	Dom.setXY('dialog_country_list', pos);
dialog_country_list.show()
}

 
 function edit_link(callback, newValue) {

    var record = this.getRecord(),
        column = this.getColumn(),
        oldValue = this.value,
        datatable = this.getDataTable();

 pid = record.getData('pid');
    var request = 'ar_edit_assets.php?tipo=edit_product&pid=' + pid + '&key=' + column.key + '&newvalue=' + escape(newValue) + '&oldvalue=' + escape(oldValue)
  //  alert(request);                                                                                                                                                                              
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                

                callback(true, r.newvalue);



            } else {
                alert(r.msg);
                callback();
            }
        },
        failure: function(o) {
            alert(o.statusText);
            callback();
        },
        scope: this
    }

    );
}

 var link_formatter = function(el, oRecord, oColumn, oData) {

        if (oData == 'No') el.innerHTML = '<img src="art/icons/link_break.png" />';
        else el.innerHTML = '<img src="art/icons/link.png" />';
    };
 
 
	    
YAHOO.util.Event.addListener(window, "load", 
function() {
	tables = new
	function() {



		var tableid = 0;
		var tableDivEL = "table" + tableid;

		var ChangelogColumnDefs = [{key: "date",label: "<?php echo _('Date')?>",width: 200,
			sortable: true,
			className: "aright",
			sortOptions: {
				defaultDir: YAHOO.widget.DataTable.CLASS_ASC
				
			}
			
		}
		,
		{
			key: "author",
			label: "<?php echo _('Author')?>",
			width: 70,
			sortable: true,
			formatter: this.customer_name,
			className: "aleft",
			sortOptions: {
				defaultDir: YAHOO.widget.DataTable.CLASS_ASC
				
			}
			
		}
		,
		{
			key: "abstract",
			label: "<?php echo _('Description')?>",
			width: 370,
			sortable: true,
			formatter: this.customer_name,
			className: "aleft",
			sortOptions: {
				defaultDir: YAHOO.widget.DataTable.CLASS_ASC
				
			}
			
		}
		];
		request="ar_history.php?tipo=history&type=supplier_product&tableid="+tableid+"&parent_key="+Dom.get('supplier_product_pid').value+"&sf=0"
	//	alert(request)
		this.dataSource0 = new YAHOO.util.DataSource(request);
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
			, "note"
			, 'author', 'date', 'tipo', 'abstract', 'details'
			]
			
		};


	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ChangelogColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['part']['history']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['part']['history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['part']['history']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    

	
		this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
		this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
 		this.table0.table_id=tableid;
     	this.table0.subscribe("renderEvent", myrenderEvent);

		this.table0.filter = {
			key: '<?php echo $_SESSION['state']['part']['history']['f_field']?>',
			value: '<?php echo $_SESSION['state']['part']['history']['f_value']?>'
			
		};

 


		var tableid = 2;
		var tableDivEL = "table" + tableid;

		var CustomersColumnDefs = [
				    {key:"sppl_key", label:"", hidden:true,action:"none",isPrimaryKey:true}
				    ,				    {key:"sku", label:"", hidden:true,action:"none",isPrimaryKey:true}

		 ,{key: "relation",label: "<?php echo _('SP &rarr; P')?>",width: 70,sortable: false,className: "aleft"}
		//,{key:"supplier",label: "<?php echo _('Supplier')?>",width: 60,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}		

		,{key:"formated_sku",label: "<?php echo _('SKU')?>",width: 100,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"reference",label: "<?php echo _('Reference')?>",width: 90,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"name",label: "<?php echo _('Description')?>",width: 310,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}

,{key:"available_state", label:"<?php echo _('Part State')?>", width:90,sortable:false,className:"aleft"}

,{key:"state", label:"<?php echo _('Supplier Availability')?>", width:110,sortable:false,className:"aleft",action:'dialog',object:'supplier_product_part'}
				    ,{key:"state_value", label:"",hidden:true}




		];
		request="ar_edit_suppliers.php?tipo=parts_in_supplier_product&parent_key="+Dom.get('supplier_product_pid').value+"&tableid="+tableid+"&sf=0"
//alert(request)
		this.dataSource2 = new YAHOO.util.DataSource(request);
		this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.dataSource2.connXhrMode = "queueRequests";
		this.dataSource2.responseSchema = {
			resultsList: "resultset.data",
			metaFields: {
				rowsPerPage: "resultset.records_perpage",
				rtext: "resultset.rtext",
				rtext_rpp: "resultset.rtext_rpp",
				sort_key: "resultset.sort_key",
				sort_dir: "resultset.sort_dir",
				tableid: "resultset.tableid",
				filter_msg: "resultset.filter_msg",
				totalRecords: "resultset.total_records"

				
			},
			fields: [
			"sku", "relation", 'code', 'supplier','name','available','available_state','sppl_key','status','formated_status','sku','state','state_value','reference','available_state','formated_sku'
			]
			
		};
		this.table2 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs, this.dataSource2, {

			renderLoopSize: 50,
			generateRequest: myRequestBuilder,
			paginator: new YAHOO.widget.Paginator({
				rowsPerPage: <?php echo $_SESSION['state']['supplier_product']['parts']['nr'] ?>,
				containers: 'paginator2',
				alwaysVisible: false,
				pageReportTemplate: '(<?php echo _('Page ')?> {currentPage} <?php echo _('of ')?> {totalPages})',
				previousPageLinkLabel: "<",
				nextPageLinkLabel: ">",
				firstPageLinkLabel: "<<",
				lastPageLinkLabel: ">>",
				rowsPerPageOptions: [10, 25, 50, 100, 250, 500]
				,
				template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"

				
			})

			,
			sortedBy: {
				Key: "<?php echo $_SESSION['state']['supplier_product']['parts']['order']?>",
				dir: "<?php echo $_SESSION['state']['supplier_product']['parts']['order_dir']?>"

				
			},
			dynamicData: true


			
		}

		);

		this.table2.handleDataReturnPayload = myhandleDataReturnPayload;
		this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;

 this.table2.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table2.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table2.subscribe("cellClickEvent", onCellClick);

		this.table2.filter = {
			key: '<?php echo $_SESSION['state']['supplier_product']['parts']['f_field']?>',
			value: '<?php echo $_SESSION['state']['supplier_product']['parts']['f_value']?>'
			
		};




 var tableid=3; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
                   {key:"key", label:"",width:25,sortable:false,hidden:true}
                   ,{key:"code", label:"<?php echo _('Code')?>",width:65,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", label:"<?php echo _('Name')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

		];
			       
	    this.dataSource3 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=supplier_list&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    	    this.dataSource3.table_id=tableid;

	    this.dataSource3.responseSchema = {
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
			 "name","key",'code'
			 ]};

	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource3
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table3.subscribe("cellClickEvent", this.table3.onEventShowCellEditor);

 this.table3.subscribe("rowMouseoverEvent", this.table3.onEventHighlightRow);
       this.table3.subscribe("rowMouseoutEvent", this.table3.onEventUnhighlightRow);
      this.table3.subscribe("rowClickEvent", change_supplier);
     


	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table3.filter={key:'code',value:''};



   var tableid=4; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"code", label:"<?php echo _('Code')?>",width:25,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", label:"<?php echo _('Name')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"wregion", label:"<?php echo _('Region')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

		];
			       
	    this.dataSource4 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=country_list&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource4.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource4.connXhrMode = "queueRequests";
	    	    this.dataSource4.table_id=tableid;

	    this.dataSource4.responseSchema = {
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
			 "name","flag",'code','wregion'
			 ]};

	    this.table4 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource4
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator4', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table4.subscribe("cellClickEvent", this.table4.onEventShowCellEditor);

 this.table4.subscribe("rowMouseoverEvent", this.table4.onEventHighlightRow);
       this.table4.subscribe("rowMouseoutEvent", this.table4.onEventUnhighlightRow);
      this.table4.subscribe("rowClickEvent", change_origin_country_code);
     


	    this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table4.filter={key:'code',value:''};


//======================

		


		var tableid = 5;
		var tableDivEL = "table" + tableid;

		var CustomersColumnDefs = [
				    {key:"sppl_key", label:"", hidden:true,action:"none",isPrimaryKey:true}
		 ,{key: "relation",label: "<?php echo _('Relation')?>",width: 70,sortable: false,className: "aleft"}
		,{key:"formated_sku",label: "<?php echo _('SKU')?>",width: 90,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}

		,{key:"reference",label: "<?php echo _('Reference')?>",width: 100,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"name",label: "<?php echo _('Description')?>",width: 200,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"from",label: "<?php echo _('From')?>",width: 150,sortable: true,className: "aright",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"to",label: "<?php echo _('To')?>",width: 150,sortable: true,className: "aright",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}



		];
request="ar_suppliers.php?tipo=part_in_supplier_product_historic&sp_id="+Dom.get('supplier_product_pid').value+"&tableid="+tableid+"&sf=0";
//alert(request)
		this.dataSource5 = new YAHOO.util.DataSource(request);
		this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.dataSource5.connXhrMode = "queueRequests";
		this.dataSource5.responseSchema = {
			resultsList: "resultset.data",
			metaFields: {
				rowsPerPage: "resultset.records_perpage",
				rtext: "resultset.rtext",
				rtext_rpp: "resultset.rtext_rpp",
				sort_key: "resultset.sort_key",
				sort_dir: "resultset.sort_dir",
				tableid: "resultset.tableid",
				filter_msg: "resultset.filter_msg",
				totalRecords: "resultset.total_records"

				
			},
			fields: [
			"sku", "relation", 'reference','name','sppl_key','from','to','formated_sku'
			]
			
		};

		this.table5 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs, this.dataSource5, {

			renderLoopSize: 50,
			generateRequest: myRequestBuilder,
			paginator: new YAHOO.widget.Paginator({
				rowsPerPage: <?php echo $_SESSION['state']['part']['historic_supplier_products']['nr'] ?>,
				containers: 'paginator5',
				alwaysVisible: false,
				pageReportTemplate: '(<?php echo _('Page ')?> {currentPage} <?php echo _('of ')?> {totalPages})',
				previousPageLinkLabel: "<",
				nextPageLinkLabel: ">",
				firstPageLinkLabel: "<<",
				lastPageLinkLabel: ">>",
				rowsPerPageOptions: [10, 25, 50, 100, 250, 500]
				,
				template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"

				
			})

			,
			sortedBy: {
				Key: "<?php echo $_SESSION['state']['part']['historic_supplier_products']['order']?>",
				dir: "<?php echo $_SESSION['state']['part']['historic_supplier_products']['order_dir']?>"

				
			},
			dynamicData: true


			
		}

		);

		this.table5.handleDataReturnPayload = myhandleDataReturnPayload;
		this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;


		this.table5.filter = {
			key: '<?php echo $_SESSION['state']['part']['historic_supplier_products']['f_field']?>',
			value: '<?php echo $_SESSION['state']['part']['historic_supplier_products']['f_value']?>'
			
		};



		
		
	};

	
});

YAHOO.util.Event.onDOMReady(init);

function show_history() {
    Dom.setStyle(['show_history', ''], 'display', 'none')
    Dom.setStyle(['hide_history', 'history_table'], 'display', '')

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier_product-show_history&value=1', {});

}

function hide_history() {
    Dom.setStyle(['show_history', ''], 'display', '')
    Dom.setStyle(['hide_history', 'history_table'], 'display', 'none')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier_product-show_history&value=0', {});

}


YAHOO.util.Event.onContentReady("rppmenu0", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {
        trigger: "rtext_rpp0"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});

YAHOO.util.Event.onContentReady("filtermenu0", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
        trigger: "filter_name0"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});



YAHOO.util.Event.onContentReady("rppmenu0", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {
        trigger: "rtext_rpp0"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});

YAHOO.util.Event.onContentReady("filtermenu0", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
        trigger: "filter_name0"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});


YAHOO.util.Event.onContentReady("rppmenu1", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {
        trigger: "rtext_rpp1"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});

YAHOO.util.Event.onContentReady("filtermenu1", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
        trigger: "filter_name1"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});

YAHOO.util.Event.onContentReady("rppmenu2", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {
        trigger: "rtext_rpp2"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});

YAHOO.util.Event.onContentReady("filtermenu2", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {
        trigger: "filter_name2"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});


YAHOO.util.Event.onContentReady("rppmenu3", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu3", {
        trigger: "rtext_rpp3"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});

YAHOO.util.Event.onContentReady("filtermenu3", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu3", {
        trigger: "filter_name3"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});

YAHOO.util.Event.onContentReady("rppmenu4", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu4", {
        trigger: "rtext_rpp4"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});

YAHOO.util.Event.onContentReady("filtermenu4", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu4", {
        trigger: "filter_name4"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});


