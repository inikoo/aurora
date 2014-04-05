<?php
 include_once('common.php');
 		
 		

$custom_field = Array();
$sql = sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Part'");
$res = mysql_query($sql);
while ($row = mysql_fetch_array($res))
 {
	$custom_field[$row['Custom Field Key']] = $row['Custom Field Name'];

	
}


$show_case = Array();
$sql = sprintf("select * from `Part Custom Field Dimension` where `Part SKU`=%d", $_REQUEST['sku']);
$res = mysql_query($sql);
if ($row = mysql_fetch_array($res)) {

	foreach($custom_field as $key =>$value) {
		$show_case[$value] = Array('value' =>$row[$key], 'lable' =>$key);

		
	}

	
}


 ?>
var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;
var part_sku = <?php echo $_REQUEST['sku'] ?>;
var scope_key = <?php echo $_REQUEST['sku'] ?>;
var scope='part';
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

        } else if (column.object == 'supplier_product_part') {
            request_page = 'ar_edit_parts.php';

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







function validate_Part_Unit_Description(query) {
	validate_general('part_unit', 'description', query);

	
}

function validate_Part_Package_Weight_Display(query) {
	validate_general('part_properties', 'Part_Package_Weight_Display', query);
}
function validate_Part_Package_Dimensions_Width_Display(query) {
	validate_general('part_properties', 'Part_Package_Dimensions_Width_Display', query);
}
function validate_Part_Package_Dimensions_Depth_Display(query) {
	validate_general('part_properties', 'Part_Package_Dimensions_Depth_Display', query);
}
function validate_Part_Package_Dimensions_Length_Display(query) {
	validate_general('part_properties', 'Part_Package_Dimensions_Length_Display', query);
}
function validate_Part_Package_Dimensions_Diameter_Display(query) {
	validate_general('part_properties', 'Part_Package_Dimensions_Diameter_Display', query);
}

function validate_Part_Unit_Weight_Display(query) {
	validate_general('part_properties', 'Part_Unit_Weight_Display', query);
}
function validate_Part_Unit_Dimensions_Width_Display(query) {
	validate_general('part_properties', 'Part_Unit_Dimensions_Width_Display', query);
}
function validate_Part_Unit_Dimensions_Depth_Display(query) {
	validate_general('part_properties', 'Part_Unit_Dimensions_Depth_Display', query);
}
function validate_Part_Unit_Dimensions_Length_Display(query) {
	validate_general('part_properties', 'Part_Unit_Dimensions_Length_Display', query);
}
function validate_Part_Unit_Dimensions_Diameter_Display(query) {
	validate_general('part_properties', 'Part_Unit_Dimensions_Diameter_Display', query);
}


function validate_Part_Tariff_Code(query) {
	validate_general('part_unit', 'tariff_code', query);
}

function validate_Part_Duty_Rate(query) {
	validate_general('part_unit', 'duty_rate', query);
}

function validate_Part_Reference(query) {
	validate_general('part_unit', 'reference', query);
}
function validate_Part_Barcode_Data(query) {
	validate_general('part_unit', 'Barcode_Data', query);
}


function validate_Part_UN_Number(query) {
	validate_general('part_health_and_safety', 'UN_Number', query);
}


function validate_Part_UN_Number_Class(query) {
	validate_general('part_health_and_safety', 'UN_Number_Class', query);
}


function validate_Part_Proper_Shipping_Name(query) {
	validate_general('part_health_and_safety', 'Part_Proper_Shipping_Name', query);
}

function validate_Part_Hazard_Indentification_Number(query) {
	validate_general('part_health_and_safety', 'Part_Hazard_Indentification_Number', query);
}



function validate_Part_Unit_Type(query) {
	validate_general('part_unit', 'unit_type', query);

	
}

function validate_Part_General_Description(query) {
	validate_general('part_description', 'general_description', query);
}

function validate_Part_HAS_Description(query) {
	validate_general('part_description', 'has_description', query);

	
}

function delete_origin_country_code(){

Dom.get('Part_Origin_Country_Code_formated').innerHTML=''
Dom.setStyle(['update_Part_Origin_Country_Code','delete_Part_Origin_Country_Code'],'display','none')
Dom.setStyle('set_Part_Origin_Country_Code','display','')


 
   value='';
     
    validate_scope_data['part_unit']['origin']['value'] = value;
   
    Dom.get('Part_Origin_Country_Code').value = value
    ovalue = Dom.get('Part_Origin_Country_Code').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['part_unit']['origin']['changed'] = true;
    } else {
        validate_scope_data['part_unit']['origin']['changed'] = false;
    }
    validate_scope('part_unit')

}

function change_origin_country_code(oArgs){

country_code=tables.table4.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
country_name=tables.table4.getRecord(oArgs.target).getData('name').replace(/<.*?>/g, '');

Dom.get('Part_Origin_Country_Code_formated').innerHTML=country_name
Dom.setStyle(['update_Part_Origin_Country_Code','delete_Part_Origin_Country_Code'],'display','')
Dom.setStyle('set_Part_Origin_Country_Code','display','none')


 
    value = country_code;
     
    validate_scope_data['part_unit']['origin']['value'] = value;
   
    Dom.get('Part_Origin_Country_Code').value = value
    ovalue = Dom.get('Part_Origin_Country_Code').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['part_unit']['origin']['changed'] = true;
    } else {
        validate_scope_data['part_unit']['origin']['changed'] = false;
    }
    validate_scope('part_unit')
     dialog_country_list.hide();





}


 <?php

 foreach($show_case as $custom_key =>$custom_value) {

	printf("function validate_part_%s(query){validate_general('part_custom_field','custom_field_part_%s',query);}"
	, $custom_value['lable']
	, $custom_value['lable']
	);

	
}

 ?>

function change_status(value) {


    if (value == 'In Use') type = 'In_Use'
    else type = 'Not_In_Use'
    options = Dom.getElementsByClassName('option', 'button', 'Part_Status_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass('Part_Status_' + type, 'selected')

    //  alert('Part_Status_' + type+' '+value)
    Dom.get('Part_Status').value = value;

    validate_scope_data['part_status']['Part_Status']['value'] = value;

    ovalue = Dom.get('Part_Status').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['part_status']['Part_Status']['changed'] = true;
    } else {

        validate_scope_data['part_status']['Part_Status']['changed'] = false;
    }
    validate_scope('part_status')

}







function change_block(e) {

	var ids = ["description", "products", "suppliers","transactions"];
	var block_ids = ["d_description", "d_products", "d_suppliers","d_transactions"];

	Dom.setStyle(block_ids, 'display', 'none');
	Dom.setStyle('d_' + this.id, 'display', '');

	Dom.removeClass(ids, 'selected');
	Dom.addClass(this, 'selected');

	YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=part-edit&value=' + this.id, {});

	
}


function change_properties_block(e) {

	var ids = ["description_block_status", "description_block_description", "description_block_properties", "description_block_info", "description_block_health_and_safety","description_block_pictures"];
	var block_ids = ["d_description_block_status", "d_description_block_description" ,"d_description_block_properties", "d_description_block_info", "d_description_block_health_and_safety","d_description_block_pictures"];

	Dom.setStyle(block_ids, 'display', 'none');
	block_id=this.getAttribute('block_id');
	Dom.setStyle('d_description_block_' + block_id, 'display', '');
	Dom.removeClass(ids, 'selected');
	Dom.addClass(this, 'selected');

	YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=part-edit_description_block&value=' + block_id, {});

}


function post_item_updated_actions(branch, r) {
    if (r.key == 'description') {
        Dom.get('part_description_title').innerHTML = r.newvalue
    } else if (r.key == 'reference') {
        Dom.get('part_reference_title').innerHTML = r.newvalue
    }

    table_id = 0
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';

    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

}

function save_edit_part_status() {
    save_edit_general('part_status');
}

function reset_edit_part_status() {
    reset_edit_general('part_status')

    val = Dom.get('Part_Status').getAttribute('ovalue')
    if (val == 'In Use') type = 'In_Use'
    else type = 'Not_In_Use'
    options = Dom.getElementsByClassName('option', 'button', 'Part_Status_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass('Part_Status_' + type, 'selected')



}


function save_edit_part_unit() {
    save_edit_general('part_unit');
}

function reset_edit_part_unit() {
    reset_edit_general('part_unit')

    val = Dom.get('Part_Unit_Type').getAttribute('ovalue')
    sel = Dom.get('Part_Unit_Type_Select')
    for (var i, j = 0; i = sel.options[j]; j++) {
        if (i.value == val) {
            sel.selectedIndex = j;
            break;
        }
    }
    Dom.get('Part_Unit_Type').value = val;

    type = Dom.get('Part_Barcode_Type').getAttribute('ovalue')

    options = Dom.getElementsByClassName('option', 'button', 'Part_Barcode_Type_options')

    Dom.removeClass(options, 'selected')
    Dom.addClass('Part_Barcode_Type_option_' + type, 'selected')


    if (type == 'none') {
        Dom.setStyle(['Part_Barcode_Data_Source_tr', 'Part_Barcode_Data_tr'], 'display', 'none')

    } else {
        Dom.setStyle('Part_Barcode_Data_Source_tr', 'display', '')
        if (Dom.get('Part_Barcode_Data_Source').value == 'Other') {
            Dom.setStyle('Part_Barcode_Data_tr', 'display', '')
        } else {
            Dom.setStyle('Part_Barcode_Data_tr', 'display', 'none')

        }
    }

    barcode_data_source = Dom.get('Part_Barcode_Data_Source').getAttribute('ovalue')

    options = Dom.getElementsByClassName('option', 'button', 'Part_Barcode_Data_Source_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass('Part_Barcode_Data_Source_option_' + barcode_data_source, 'selected')


    if (barcode_data_source == 'Other') {
        Dom.setStyle('Part_Barcode_Data_tr', 'display', '')
    } else {
        Dom.setStyle('Part_Barcode_Data_tr', 'display', 'none')
    }
}

function save_edit_part_description() {
GeneralDescriptionEditor.saveHTML();
	save_edit_general('part_description');
}

function save_edit_part_properties() {
	save_edit_general_bulk('part_properties');
}


function reset_edit_part_properties() {
    reset_edit_general('part_properties');

    type = Dom.get('Part_Package_Type').getAttribute('ovalue')
    options = Dom.getElementsByClassName('option', 'button', 'Part_Package_Type_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass('Part_Package_Type_option_' + type, 'selected')

}

function reset_edit_part_description() {
	reset_edit_general('part_description')
    GeneralDescriptionEditor.setEditorHTML(Dom.get('part_general_description').value);
}


function save_edit_part_health_and_safety() {
HealthAndSafetyEditor.saveHTML();
	save_edit_general('part_health_and_safety');
}
function reset_edit_part_health_and_safety() {
	reset_edit_general('part_health_and_safety')
    HealthAndSafetyEditor.setEditorHTML(Dom.get('part_health_and_safety').value);
}





function save_edit_custom_field() {
	save_edit_general('part_custom_field');
}

function reset_edit_custom_field() {
	reset_edit_general('part_custom_field')
}

function change_barcode_type(o, type) {
    options = Dom.getElementsByClassName('option', 'button', 'Part_Barcode_Type_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    if (type == 'none') {
        Dom.setStyle(['Part_Barcode_Data_Source_tr', 'Part_Barcode_Data_tr'], 'display', 'none')

    } else {
        Dom.setStyle('Part_Barcode_Data_Source_tr', 'display', '')
        if (Dom.get('Part_Barcode_Data_Source').value == 'Other') {
            Dom.setStyle('Part_Barcode_Data_tr', 'display', '')
        } else {
            Dom.setStyle('Part_Barcode_Data_tr', 'display', 'none')
            Dom.get('Part_Barcode_Data').value = Dom.get('Part_Barcode_Data').getAttribute('ovalue')
            validate_scope_data['part_unit']['Barcode_Type']['changed'] = false;
        }
    }
    value = type;
    ovalue = Dom.get('Part_Barcode_Type').getAttribute('ovalue');
    validate_scope_data['part_unit']['Barcode_Type']['value'] = value;
    Dom.get('Part_Barcode_Type').value = value

    if (ovalue != value) {
        validate_scope_data['part_unit']['Barcode_Type']['changed'] = true;
    } else {
        validate_scope_data['part_unit']['Barcode_Type']['changed'] = false;
    }
    validate_scope('part_unit')
}

function change_packing_group(o, type) {
    options = Dom.getElementsByClassName('option', 'button', 'Part_Packing_Group_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    value = type;
    ovalue = Dom.get('Part_Packing_Group').getAttribute('ovalue');
    validate_scope_data['part_health_and_safety']['Packing_Group']['value'] = value;
    Dom.get('Part_Packing_Group').value = value

    if (ovalue != value) {
        validate_scope_data['part_health_and_safety']['Packing_Group']['changed'] = true;
    } else {
        validate_scope_data['part_health_and_safety']['Packing_Group']['changed'] = false;
    }
    validate_scope('part_health_and_safety')
}



function change_barcode_data_source(o, type) {
    options = Dom.getElementsByClassName('option', 'button', 'Part_Barcode_Data_Source_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    if (type == 'Other') {
        Dom.setStyle('Part_Barcode_Data_tr', 'display', '')
        Dom.get("Part_Barcode_Data").value = Dom.get("Part_Barcode_Data").getAttribute('ovalue')


    } else {
        Dom.setStyle('Part_Barcode_Data_tr', 'display', 'none')
        Dom.get("Part_Barcode_Data").value = '';

    }

    value = type;
    ovalue = Dom.get('Part_Barcode_Data_Source').getAttribute('ovalue');
    validate_scope_data['part_unit']['Barcode_Data_Source']['value'] = value;
    Dom.get('Part_Barcode_Data_Source').value = value

    if (ovalue != value) {
        validate_scope_data['part_unit']['Barcode_Data_Source']['changed'] = true;
    } else {
        validate_scope_data['part_unit']['Barcode_Data_Source']['changed'] = false;
    }
    validate_scope('part_unit')
}








function change_part_unit_type(o) {

    var choose_option = o.options[o.selectedIndex]

    value = choose_option.value;
    validate_scope_data['part_unit']['unit_type']['value'] = value;
    Dom.get('Part_Unit_Type').value = value
    ovalue = Dom.get('Part_Unit_Type').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['part_unit']['unit_type']['changed'] = true;
    } else {
        validate_scope_data['part_unit']['unit_type']['changed'] = false;
    }
    validate_scope('part_unit')

}

function change_package_type(o, type) {
    options = Dom.getElementsByClassName('option', 'button', 'Part_Package_Type_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    value = type;
    ovalue = Dom.get('Part_Package_Type').getAttribute('ovalue');
    validate_scope_data['part_properties']['Part_Package_Type']['value'] = value;
    Dom.get('Part_Package_Type').value = value

    if (ovalue != value) {
        validate_scope_data['part_properties']['Part_Package_Type']['changed'] = true;
    } else {
        validate_scope_data['part_properties']['Part_Package_Type']['changed'] = false;
    }
    validate_scope('part_properties')
}





function change_dimensions_shape_type(o, shape, parent) {

dialog_change_lenght_units.hide()

    options = Dom.getElementsByClassName('option', 'button', 'Part_' + parent + '_Dimensions_Type_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    value = shape;
    ovalue = Dom.get('Part_' + parent + '_Dimensions_Type').getAttribute('ovalue');
    validate_scope_data['part_properties']['Part_' + parent + '_Dimensions_Type']['value'] = value;
    Dom.get('Part_' + parent + '_Dimensions_Type').value = value

    if (ovalue != value) {
        validate_scope_data['part_properties']['Part_' + parent + '_Dimensions_Type']['changed'] = true;
    } else {
        validate_scope_data['part_properties']['Part_' + parent + '_Dimensions_Type']['changed'] = false;
    }

     Dom.setStyle(['Part_' + parent + '_Dimensions_Width_tr', 'Part_' + parent + '_Dimensions_Depth_tr', 'Part_' + parent + '_Dimensions_Length_tr', 'Part_' + parent + '_Dimensions_Diameter_tr'], 'display', 'none')
    Dom.setStyle(['Part_' + parent + '_Dimensions_Display_Units_Width', 'Part_' + parent + '_Dimensions_Display_Units_Length', 'Part_' + parent + '_Dimensions_Display_Units_Diameter'], 'display', 'none')
  
 
  if (value == 'Rectangular') {
        Dom.setStyle(['Part_' + parent + '_Dimensions_Width_tr', 'Part_' + parent + '_Dimensions_Depth_tr', 'Part_' + parent + '_Dimensions_Length_tr'], 'display', '')
        Dom.setStyle(['Part_' + parent + '_Dimensions_Display_Units_Width'], 'display', '')
        
        Dom.get('Part_'+parent+'_Dimensions_Width_Display').value=Dom.get('Part_'+parent+'_Dimensions_Width_Display').getAttribute('ovalue');
        Dom.get('Part_'+parent+'_Dimensions_Depth_Display').value=Dom.get('Part_'+parent+'_Dimensions_Depth_Display').getAttribute('ovalue');
        Dom.get('Part_'+parent+'_Dimensions_Length_Display').value=Dom.get('Part_'+parent+'_Dimensions_Length_Display').getAttribute('ovalue');
        Dom.get('Part_'+parent+'_Dimensions_Diameter_Display').value='';
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Diameter_Display', '');
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Width_Display', Dom.get('Part_'+parent+'_Dimensions_Width_Display').value);
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Depth_Display', Dom.get('Part_'+parent+'_Dimensions_Depth_Display').value);
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Length_Display', Dom.get('Part_'+parent+'_Dimensions_Length_Display').value);


    } else if (value == 'Cilinder') {
        Dom.setStyle(['Part_' + parent + '_Dimensions_Length_tr', 'Part_' + parent + '_Dimensions_Diameter_tr'], 'display', '')
        Dom.setStyle(['Part_' + parent + '_Dimensions_Display_Units_Length'], 'display', '')
        Dom.get('Part_'+parent+'_Dimensions_Length_Display').value=Dom.get('Part_'+parent+'_Dimensions_Length_Display').getAttribute('ovalue');
        Dom.get('Part_'+parent+'_Dimensions_Diameter_Display').value=Dom.get('Part_'+parent+'_Dimensions_Diameter_Display').getAttribute('ovalue');

        Dom.get('Part_'+parent+'_Dimensions_Width_Display').value='';
        Dom.get('Part_'+parent+'_Dimensions_Depth_Display').value='';        
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Width_Display', '');
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Depth_Display', '');
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Length_Display', Dom.get('Part_'+parent+'_Dimensions_Length_Display').value);
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Diameter_Display', Dom.get('Part_'+parent+'_Dimensions_Diameter_Display').value);


    } else if (value == 'Sphere') {
        Dom.setStyle(['Part_' + parent + '_Dimensions_Diameter_tr'], 'display', '')
        Dom.setStyle(['Part_' + parent + '_Dimensions_Display_Units_Diameter'], 'display', '')
        Dom.get('Part_'+parent+'_Dimensions_Width_Display').value='';
        Dom.get('Part_'+parent+'_Dimensions_Depth_Display').value='';        
        Dom.get('Part_'+parent+'_Dimensions_Length_Display').value='';        
        Dom.get('Part_'+parent+'_Dimensions_Diameter_Display').value=Dom.get('Part_'+parent+'_Dimensions_Diameter_Display').getAttribute('ovalue');
        
       // alert('Part_'+parent+'_Dimensions_Width_Display')
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Width_Display', '');
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Depth_Display', '');
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Length_Display', '');
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Diameter_Display', Dom.get('Part_'+parent+'_Dimensions_Diameter_Display').value);

    } else if (value == 'String') {
        Dom.setStyle(['Part_' + parent + '_Dimensions_Length_tr'], 'display', '')
        Dom.setStyle(['Part_' + parent + '_Dimensions_Display_Units_Length'], 'display', '')
        Dom.get('Part_'+parent+'_Dimensions_Width_Display').value='';
        Dom.get('Part_'+parent+'_Dimensions_Depth_Display').value='';        
        Dom.get('Part_'+parent+'_Dimensions_Diameter_Display').value='';
          Dom.get('Part_'+parent+'_Dimensions_Length_Display').value=Dom.get('Part_'+parent+'_Dimensions_Length_Display').getAttribute('ovalue');
      
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Width_Display', '');
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Depth_Display', '');
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Diameter_Display', '');
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Length_Display', Dom.get('Part_'+parent+'_Dimensions_Length_Display').value);



    } else if (value == 'Sheet') {
        Dom.setStyle(['Part_' + parent + '_Dimensions_Width_tr', 'Part_' + parent + '_Dimensions_Length_tr'], 'display', '')
        Dom.setStyle(['Part_' + parent + '_Dimensions_Display_Units_Width'], 'display', '')
        Dom.get('Part_'+parent+'_Dimensions_Depth_Display').value='';        
        Dom.get('Part_'+parent+'_Dimensions_Diameter_Display').value='';
        Dom.get('Part_'+parent+'_Dimensions_Width_Display').value=Dom.get('Part_'+parent+'_Dimensions_Width_Display').getAttribute('ovalue');
        Dom.get('Part_'+parent+'_Dimensions_Length_Display').value=Dom.get('Part_'+parent+'_Dimensions_Length_Display').getAttribute('ovalue');

        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Depth_Display', '');
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Diameter_Display', '');
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Width_Display', Dom.get('Part_'+parent+'_Dimensions_Width_Display').value);
        validate_general('part_properties', 'Part_'+parent+'_Dimensions_Length_Display', Dom.get('Part_'+parent+'_Dimensions_Length_Display').value);

    }
 

    validate_scope('part_properties')
}






function general_description_editor_changed(){
validate_scope_data['part_description']['general_description']['changed']=true;
validate_scope('part_description')
}



function health_and_safety_editor_changed(){
validate_scope_data['part_health_and_safety']['health_and_safety']['changed']=true;
validate_scope('part_health_and_safety')
}


function show_dialog_delete(delete_type, subject) {
    if (delete_type == 'delete' && subject == 'part_location_transaction') {
        dialog_delete_part_location_transaction.show()
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
    validate_scope_data['part_properties'][Dom.get('change_' + type + '_units_field').value]['value'] = value;
    Dom.get(Dom.get('change_' + type + '_units_field').value).value = value
    //alert(value+' '+ovalue)
    if (ovalue != value) {
        validate_scope_data['part_properties'][Dom.get('change_' + type + '_units_field').value]['changed'] = true;
    } else {
        validate_scope_data['part_properties'][Dom.get('change_' + type + '_units_field').value]['changed'] = false;
    }

    validate_scope('part_properties')

    if (type == 'lenght'){ 
    
   	 Dom.get('Part_'+parent+'_Dimensions_Display_Units_Width').innerHTML = '&#x21b6 ' + unit
	 Dom.get('Part_'+parent+'_Dimensions_Display_Units_Length').innerHTML = '&#x21b6 ' + unit
   	 Dom.get('Part_'+parent+'_Dimensions_Display_Units_Diameter').innerHTML = '&#x21b6 ' + unit
   
    
    	dialog_change_lenght_units.hide()
    }else{ 
    	dialog_change_weight_units.hide()
	}
}



function hide_dialog_delete(delete_type, subject) {
    if (delete_type == 'delete' && subject == 'part_location_transaction') {
        dialog_delete_part_location_transaction.hide()
    }
}

function show_part_health_and_safety_editor() {
    Dom.setStyle('show_part_health_and_safety_editor', 'display', 'none')

    Dom.setStyle('part_health_and_safety_editor_tr', 'display', '')
    Dom.setStyle('edit_part_health_and_safety_buttons', 'margin-left', '700px')
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
    var request = 'ar_edit_parts.php?tipo=add_MSDS_file&sku=' + Dom.get('part_sku').value

    var uploadHandler = {

        upload: function(o) {
        	
            //alert(o.responseText)
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

    var request = 'ar_edit_parts.php?tipo=delete_MSDS_file&sku=' + Dom.get('part_sku').value
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




function init() {

    init_search('parts');


get_part_transaction_numbers('','')

 validate_scope_data = {
	'part_status': {

		'Part_Status': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Status',
			'name': 'Part_Status',
			'ar': false,
			'validation':false
			
		}
		

		
	},
	'part_unit': {
		'description': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Unit Description',
			'name': 'Part_Unit_Description',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d]+",
				'invalid_msg': '<?php echo _('Invalid Part Unit Description')?>'
				
			}]
			
		}
		,'tariff_code': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Tariff Code',
			'name': 'Part_Tariff_Code',
			'ar': false,
			'validation': [{
				'regexp': "\\d",
				'invalid_msg': '<?php echo _('Invalid Tariff Code')?>'
				
			}]
			
		}
		,'reference': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Reference',
			'name': 'Part_Reference',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d\\-]+",
				'invalid_msg': '<?php echo _('Invalid Reference')?>'
				
			}]
			
		}
		,'duty_rate': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Duty Rate',
			'name': 'Part_Duty_Rate',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d]+",
				'invalid_msg': '<?php echo _('Invalid Duty Rate')?>'
				
			}]
			
		}
		,'origin': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Origin Country Code',
			'name': 'Part_Origin_Country_Code',
			'ar': false,
			'validation':false
			
		}
				,'unit_type': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Unit',
			'name': 'Part_Unit_Type',
			'ar': false,
			'validation':false
			
		}
		,'Barcode_Type': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Barcode Type',
			'name': 'Part_Barcode_Type',
			'ar': false,
			'validation':false
			
		}
		,'Barcode_Data_Source': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Barcode Data Source',
			'name': 'Part_Barcode_Data_Source',
			'ar': false,
			'validation':false
			
		}
		,'Barcode_Data': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Barcode Data',
			'name': 'Part_Barcode_Data',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d]+",
				'invalid_msg': '<?php echo _('Invalid Barcode')?>'
				
			}]
			
		}

		
	},
	'part_properties': {
		
	    'Part_Package_Type': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Package Type',
			'name': 'Part_Package_Type',
			'ar': false
			
		},
		'Part_Package_Weight_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Package Weight Display',
			'name': 'Part_Package_Weight_Display',
			'ar': false,
			'validation': [{
				'regexp': "(\\d|\.)",
				'invalid_msg': '<?php echo _('Invalid Weight')?>'}]
		},
		'Part_Package_Weight_Display_Units': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Package Weight Display Units',
			'name': 'Part_Package_Weight_Display_Units',
			'ar': false
			
		},
		'Part_Package_Dimensions_Type': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Package Dimensions Type',
			'name': 'Part_Package_Dimensions_Type',
			'ar': false
			
		},
		'Part_Package_Dimensions_Width_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Package Dimensions Width Display',
			'name': 'Part_Package_Dimensions_Width_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},
		'Part_Package_Dimensions_Depth_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Package Dimensions Depth Display',
			'name': 'Part_Package_Dimensions_Depth_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},
		'Part_Package_Dimensions_Length_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Package Dimensions Length Display',
			'name': 'Part_Package_Dimensions_Length_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},		
		'Part_Package_Dimensions_Diameter_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Package Dimensions Diameter Display',
			'name': 'Part_Package_Dimensions_Diameter_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},
		'Part_Package_Dimensions_Display_Units': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Package Dimensions Display Units',
			'name': 'Part_Package_Dimensions_Display_Units',
			'ar': false
			
		},
	    'Part_Unit_Type': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Unit Type',
			'name': 'Part_Unit_Type',
			'ar': false
			
		},
		'Part_Unit_Weight_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Unit Weight Display',
			'name': 'Part_Unit_Weight_Display',
			'ar': false,
			'validation': [{
				'regexp': "(\\d|\.)",
				'invalid_msg': '<?php echo _('Invalid Weight')?>'}]
		},
		'Part_Unit_Weight_Display_Units': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Unit Weight Display Units',
			'name': 'Part_Unit_Weight_Display_Units',
			'ar': false
			
		},
		'Part_Unit_Dimensions_Type': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Unit Dimensions Type',
			'name': 'Part_Unit_Dimensions_Type',
			'ar': false
			
		},
		'Part_Unit_Dimensions_Width_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Unit Dimensions Width Display',
			'name': 'Part_Unit_Dimensions_Width_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},
		'Part_Unit_Dimensions_Depth_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Unit Dimensions Depth Display',
			'name': 'Part_Unit_Dimensions_Depth_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},
		'Part_Unit_Dimensions_Length_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Unit Dimensions Length Display',
			'name': 'Part_Unit_Dimensions_Length_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},		
		'Part_Unit_Dimensions_Diameter_Display': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Unit Dimensions Diameter Display',
			'name': 'Part_Unit_Dimensions_Diameter_Display',
			'ar': false,
			'validation': [{
				'numeric': "empty_ok",
				'invalid_msg': '<?php echo _('Invalid Number')?>'}]
		},
		'Part_Unit_Dimensions_Display_Units': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Unit Dimensions Display Units',
			'name': 'Part_Unit_Dimensions_Display_Units',
			'ar': false
			
		},		
		
		
		
		
	},
	'part_description': {
		'general_description': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 2,
			'type': 'item',
			'dbname': 'Part General Description',
			'name': 'part_general_description',
			'ar': false,
			'validation': false
		}
	},
	'part_health_and_safety': {
		'UN_Number': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part UN Number',
			'name': 'Part_UN_Number',
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
			'dbname': 'Part UN Class',
			'name': 'Part_UN_Number_Class',
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
			'dbname': 'Part Packing Group',
			'name': 'Part_Packing_Group',
			'ar': false
			
		}
		,'Part_Proper_Shipping_Name': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Proper Shipping Name',
			'name': 'Part_Proper_Shipping_Name',
			'ar': false,
			'validation': [{
				'regexp': "[a-z\\d]+",
				'invalid_msg': '<?php echo _('Part Proper Shipping Name')?>'
				
			}]
			
		}		,'Part_Hazard_Indentification_Number': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Hazard Indentification Number',
			'name': 'Part_Hazard_Indentification_Number',
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
			'dbname': 'Part Health And Safety',
			'name': 'part_health_and_safety',
			'ar': false,
			'validation': false
		}
	},
	
	'part_custom_field': {
		 <?php
		 $i = 0;
		foreach($show_case as $custom_key =>$custom_value) {
			if ($i) print ",";
			printf("'custom_field_part_%s':{'changed':false,'validated':true,'required':true,'group':3,'type':'item','name':'Part_%s', 'dbname':'%s','ar':false, 'validation':[{'regexp':\"[a-z\\d]+\",'invalid_msg':'Invalid %s'}]}\n", 
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
	'part_status': {
		'type': 'edit',
		'ar_file': 'ar_edit_parts.php',
		'key_name': 'sku',
		'key': Dom.get('part_sku').value
		
	},
		'part_unit': {
		'type': 'edit',
		'ar_file': 'ar_edit_parts.php',
		'key_name': 'sku',
		'key': Dom.get('part_sku').value
		
	},
	'part_properties': {
		'type': 'edit',
		'ar_file': 'ar_edit_parts.php',
		'key_name': 'sku',
		'key': Dom.get('part_sku').value
		
	},
	'part_description': {
		'type': 'edit',
		'ar_file': 'ar_edit_parts.php',
		'key_name': 'sku',
		'key': Dom.get('part_sku').value
		
	},
	'part_health_and_safety': {
		'type': 'edit',
		'ar_file': 'ar_edit_parts.php',
		'key_name': 'sku',
		'key': Dom.get('part_sku').value
		
	},
	'part_custom_field': {
		'type': 'edit',
		'ar_file': 'ar_edit_parts.php',
		'key_name': 'sku',
		'key': Dom.get('part_sku').value
		
	}

	
};


 	dialog_delete_part_location_transaction = new YAHOO.widget.Dialog("dialog_delete_part_location_transaction", {visible : false,close:true,underlay: "none",draggable:false});
	dialog_delete_part_location_transaction.render();
	
	
	
 	dialog_change_weight_units = new YAHOO.widget.Dialog("dialog_change_weight_units", {visible : false,close:true,underlay: "none",draggable:false});
	dialog_change_weight_units.render();	
	dialog_change_lenght_units = new YAHOO.widget.Dialog("dialog_change_lenght_units", {visible : false,close:true,underlay: "none",draggable:false});
	dialog_change_lenght_units.render();	
		
    var ids = ["description", "products", "suppliers", "transactions"];
    Event.addListener(ids, "click", change_block);

    var ids = ["description_block_status", "description_block_description", "description_block_properties", "description_block_pictures", "description_block_info", "description_block_health_and_safety"];
    Event.addListener(ids, "click", change_properties_block);



    YAHOO.util.Event.on('show_part_health_and_safety_editor', 'click', show_part_health_and_safety_editor);

    YAHOO.util.Event.on('uploadButton', 'click', upload_image);

    Event.addListener("upload_MSDS_File_file", "change", check_if_MSDS_File_selected);
    Event.addListener("upload_MSDS_File_button", "click", save_MSDS_File_attachment);
    Event.addListener("delete_MSDS_File", "click", delete_MSDS_File);
    Event.addListener("replace_MSDS_File", "click", replace_MSDS_File);

    Event.addListener("Part_Unit_Dimensions_Display_Units_Length", "click", show_dialog_change_units,'lenght');
    Event.addListener("Part_Unit_Dimensions_Display_Units_Width", "click", show_dialog_change_units,'lenght');


    Event.addListener("Part_Unit_Dimensions_Display_Units_Diameter", "click", show_dialog_change_units,'lenght');
    Event.addListener("Part_Unit_Weight_Display_Units_button", "click", show_dialog_change_units,'weight');
    Event.addListener("Part_Package_Dimensions_Display_Units_Length", "click", show_dialog_change_units,'lenght');
    Event.addListener("Part_Package_Dimensions_Display_Units_Width", "click", show_dialog_change_units,'lenght');
    Event.addListener("Part_Package_Dimensions_Display_Units_Diameter", "click", show_dialog_change_units,'lenght');
    Event.addListener("Part_Package_Weight_Display_Units_button", "click", show_dialog_change_units,'weight');







    Event.addListener("save_delete_MSDS_File", "click", save_delete_MSDS_File);
    Event.addListener("cancel_delete_MSDS_File", "click", cancel_delete_MSDS_File);




dialog_delete_MSDS_File =  new YAHOO.widget.Dialog("dialog_delete_MSDS_File", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_delete_MSDS_File.render();
    

    Event.addListener('save_edit_part_status', "click", save_edit_part_status);
    Event.addListener('reset_edit_part_status', "click", reset_edit_part_status);


  	Event.addListener('save_edit_part_unit', "click", save_edit_part_unit);
    Event.addListener('reset_edit_part_unit', "click", reset_edit_part_unit);


    Event.addListener('save_edit_part_description', "click", save_edit_part_description);
    Event.addListener('reset_edit_part_description', "click", reset_edit_part_description);

    Event.addListener('save_edit_part_health_and_safety', "click", save_edit_part_health_and_safety);
    Event.addListener('reset_edit_part_health_and_safety', "click", reset_edit_part_health_and_safety);




    Event.addListener('save_edit_part_custom_field', "click", save_edit_custom_field);
    Event.addListener('reset_edit_part_custom_field', "click", reset_edit_custom_field);

  Event.addListener('save_edit_part_properties', "click", save_edit_part_properties);
    Event.addListener('reset_edit_part_properties', "click", reset_edit_part_properties);




    // Event.addListener('save_edit_part_price', "click", save_edit_price);
    //Event.addListener('reset_edit_part_price', "click", reset_edit_price);
    //Event.addListener('save_edit_part_weight', "click", save_edit_weight);
    //Event.addListener('reset_edit_part_weight', "click", reset_edit_weight);


    var part_unit_description_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Unit_Description);
    part_unit_description_oACDS.queryMatchContains = true;
    var part_unit_description_oAutoComp = new YAHOO.widget.AutoComplete("Part_Unit_Description", "Part_Unit_Description_Container", part_unit_description_oACDS);
    part_unit_description_oAutoComp.minQueryLength = 0;
    part_unit_description_oAutoComp.queryDelay = 0.1;



    var Part_Package_Weight_Display_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Package_Weight_Display);
    Part_Package_Weight_Display_oACDS.queryMatchContains = true;
    var Part_Package_Weight_Display_oAutoComp = new YAHOO.widget.AutoComplete("Part_Package_Weight_Display", "Part_Package_Weight_Display_Container", Part_Package_Weight_Display_oACDS);
    Part_Package_Weight_Display_oAutoComp.minQueryLength = 0;
    Part_Package_Weight_Display_oAutoComp.queryDelay = 0.1;
    
      var Part_Unit_Weight_Display_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Unit_Weight_Display);
    Part_Unit_Weight_Display_oACDS.queryMatchContains = true;
    var Part_Unit_Weight_Display_oAutoComp = new YAHOO.widget.AutoComplete("Part_Unit_Weight_Display", "Part_Unit_Weight_Display_Container", Part_Unit_Weight_Display_oACDS);
    Part_Unit_Weight_Display_oAutoComp.minQueryLength = 0;
    Part_Unit_Weight_Display_oAutoComp.queryDelay = 0.1;
  
    
    var Part_Package_Dimensions_Width_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Package_Dimensions_Width_Display);
    Part_Package_Dimensions_Width_oACDS.queryMatchContains = true;
    var Part_Package_Dimensions_Width_oAutoComp = new YAHOO.widget.AutoComplete("Part_Package_Dimensions_Width_Display", "Part_Package_Dimensions_Width_Display_Container", Part_Package_Dimensions_Width_oACDS);
    Part_Package_Dimensions_Width_oAutoComp.minQueryLength = 0;
    Part_Package_Dimensions_Width_oAutoComp.queryDelay = 0.1;
    
    var Part_Package_Dimensions_Depth_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Package_Dimensions_Depth_Display);
    Part_Package_Dimensions_Depth_oACDS.queryMatchContains = true;
    var Part_Package_Dimensions_Depth_oAutoComp = new YAHOO.widget.AutoComplete("Part_Package_Dimensions_Depth_Display", "Part_Package_Dimensions_Depth_Display_Container", Part_Package_Dimensions_Depth_oACDS);
    Part_Package_Dimensions_Depth_oAutoComp.minQueryLength = 0;
    Part_Package_Dimensions_Depth_oAutoComp.queryDelay = 0.1;
    
    var Part_Package_Dimensions_Length_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Package_Dimensions_Length_Display);
    Part_Package_Dimensions_Length_oACDS.queryMatchContains = true;
    var Part_Package_Dimensions_Length_oAutoComp = new YAHOO.widget.AutoComplete("Part_Package_Dimensions_Length_Display", "Part_Package_Dimensions_Length_Display_Container", Part_Package_Dimensions_Length_oACDS);
    Part_Package_Dimensions_Length_oAutoComp.minQueryLength = 0;
    Part_Package_Dimensions_Length_oAutoComp.queryDelay = 0.1;
   
    var Part_Package_Dimensions_Diameter_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Package_Dimensions_Diameter_Display);
    Part_Package_Dimensions_Diameter_oACDS.queryMatchContains = true;
    var Part_Package_Dimensions_Diameter_oAutoComp = new YAHOO.widget.AutoComplete("Part_Package_Dimensions_Diameter_Display", "Part_Package_Dimensions_Diameter_Display_Container", Part_Package_Dimensions_Diameter_oACDS);
    Part_Package_Dimensions_Diameter_oAutoComp.minQueryLength = 0;
    Part_Package_Dimensions_Diameter_oAutoComp.queryDelay = 0.1;    
    
 
    var Part_Unit_Dimensions_Width_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Unit_Dimensions_Width_Display);
    Part_Unit_Dimensions_Width_oACDS.queryMatchContains = true;
    var Part_Unit_Dimensions_Width_oAutoComp = new YAHOO.widget.AutoComplete("Part_Unit_Dimensions_Width_Display", "Part_Unit_Dimensions_Width_Display_Container", Part_Unit_Dimensions_Width_oACDS);
    Part_Unit_Dimensions_Width_oAutoComp.minQueryLength = 0;
    Part_Unit_Dimensions_Width_oAutoComp.queryDelay = 0.1;
    
    var Part_Unit_Dimensions_Depth_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Unit_Dimensions_Depth_Display);
    Part_Unit_Dimensions_Depth_oACDS.queryMatchContains = true;
    var Part_Unit_Dimensions_Depth_oAutoComp = new YAHOO.widget.AutoComplete("Part_Unit_Dimensions_Depth_Display", "Part_Unit_Dimensions_Depth_Display_Container", Part_Unit_Dimensions_Depth_oACDS);
    Part_Unit_Dimensions_Depth_oAutoComp.minQueryLength = 0;
    Part_Unit_Dimensions_Depth_oAutoComp.queryDelay = 0.1;
    
    var Part_Unit_Dimensions_Length_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Unit_Dimensions_Length_Display);
    Part_Unit_Dimensions_Length_oACDS.queryMatchContains = true;
    var Part_Unit_Dimensions_Length_oAutoComp = new YAHOO.widget.AutoComplete("Part_Unit_Dimensions_Length_Display", "Part_Unit_Dimensions_Length_Display_Container", Part_Unit_Dimensions_Length_oACDS);
    Part_Unit_Dimensions_Length_oAutoComp.minQueryLength = 0;
    Part_Unit_Dimensions_Length_oAutoComp.queryDelay = 0.1;
   
    var Part_Unit_Dimensions_Diameter_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Unit_Dimensions_Diameter_Display);
    Part_Unit_Dimensions_Diameter_oACDS.queryMatchContains = true;
    var Part_Unit_Dimensions_Diameter_oAutoComp = new YAHOO.widget.AutoComplete("Part_Unit_Dimensions_Diameter_Display", "Part_Unit_Dimensions_Diameter_Display_Container", Part_Unit_Dimensions_Diameter_oACDS);
    Part_Unit_Dimensions_Diameter_oAutoComp.minQueryLength = 0;
    Part_Unit_Dimensions_Diameter_oAutoComp.queryDelay = 0.1;    




    var part_Tariff_Code_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Tariff_Code);
    part_Tariff_Code_oACDS.queryMatchContains = true;
    var part_gross_weight_oAutoComp = new YAHOO.widget.AutoComplete("Part_Tariff_Code", "Part_Tariff_Code_Container", part_Tariff_Code_oACDS);
    part_gross_weight_oAutoComp.minQueryLength = 0;
    part_gross_weight_oAutoComp.queryDelay = 0.1;


    var part_Duty_Rate_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Duty_Rate);
    part_Duty_Rate_oACDS.queryMatchContains = true;
    var part_duty_rate_oAutoComp = new YAHOO.widget.AutoComplete("Part_Duty_Rate", "Part_Duty_Rate_Container", part_Duty_Rate_oACDS);
    part_duty_rate_oAutoComp.minQueryLength = 0;
    part_duty_rate_oAutoComp.queryDelay = 0.1;


    var part_reference_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Reference);
    part_reference_oACDS.queryMatchContains = true;
    var part_reference_oAutoComp = new YAHOO.widget.AutoComplete("Part_Reference", "Part_Reference_Container", part_reference_oACDS);
    part_reference_oAutoComp.minQueryLength = 0;
    part_reference_oAutoComp.queryDelay = 0.1;


    var part_barcode_data_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Barcode_Data);
    part_barcode_data_oACDS.queryMatchContains = true;
    var part_barcode_data_oAutoComp = new YAHOO.widget.AutoComplete("Part_Barcode_Data", "Part_Barcode_Data_Container", part_barcode_data_oACDS);
    part_barcode_data_oAutoComp.minQueryLength = 0;
    part_barcode_data_oAutoComp.queryDelay = 0.1;
    
    
    var part_un_number_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_UN_Number);
    part_un_number_oACDS.queryMatchContains = true;
    var part_un_number_oAutoComp = new YAHOO.widget.AutoComplete("Part_UN_Number", "Part_UN_Number_Container", part_un_number_oACDS);
    part_un_number_oAutoComp.minQueryLength = 0;
    part_un_number_oAutoComp.queryDelay = 0.1;
    
    var part_un_number_class_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_UN_Number_Class);
    part_un_number_class_oACDS.queryMatchContains = true;
    var part_un_number_class_oAutoComp = new YAHOO.widget.AutoComplete("Part_UN_Number_Class", "Part_UN_Number_Class_Container", part_un_number_class_oACDS);
    part_un_number_class_oAutoComp.minQueryLength = 0;
    part_un_number_class_oAutoComp.queryDelay = 0.1;
        
  	var part_proper_shipping_name_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Proper_Shipping_Name);
    part_proper_shipping_name_oACDS.queryMatchContains = true;
    var part_proper_shipping_name_oAutoComp = new YAHOO.widget.AutoComplete("Part_Proper_Shipping_Name", "Part_Proper_Shipping_Name_Container", part_proper_shipping_name_oACDS);
    part_proper_shipping_name_oAutoComp.minQueryLength = 0;
    part_proper_shipping_name_oAutoComp.queryDelay = 0.1;
    
      var part_hin_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Hazard_Indentification_Number);
    part_hin_oACDS.queryMatchContains = true;
    var part_hin_oAutoComp = new YAHOO.widget.AutoComplete("Part_Hazard_Indentification_Number", "Part_Hazard_Indentification_Number_Container", part_hin_oACDS);
    part_hin_oAutoComp.minQueryLength = 0;
    part_hin_oAutoComp.queryDelay = 0.1;


    var part_general_description_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_General_Description);
    part_general_description_oACDS.queryMatchContains = true;
    var part_general_description_oAutoComp = new YAHOO.widget.AutoComplete("Part_General_Description", "Part_General_Description_Container", part_general_description_oACDS);
    part_general_description_oAutoComp.minQueryLength = 0;
    part_general_description_oAutoComp.queryDelay = 0.1;

    var part_has_description_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_HAS_Description);
    part_has_description_oACDS.queryMatchContains = true;
    var part_gross_weight_oAutoComp = new YAHOO.widget.AutoComplete("Part_Health_And_Safety", "Part_Health_And_Safety_Container", part_has_description_oACDS);
    part_gross_weight_oAutoComp.minQueryLength = 0;
    part_gross_weight_oAutoComp.queryDelay = 0.1;


	 <?php

	 foreach($show_case as $custom_key =>$custom_value) {
		printf("var part_%s_oACDS = new YAHOO.util.FunctionDataSource(validate_part_%s);\npart_%s_oACDS.queryMatchContains = true;\nvar part_%s_oAutoComp = new YAHOO.widget.AutoComplete('Part_%s','Part_%s_Container', part_%s_oACDS);\npart_%s_oAutoComp.minQueryLength = 0;\npart_%s_oAutoComp.queryDelay = 0.1;", 
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
    
  
    GeneralDescriptionEditor = new YAHOO.widget.Editor('part_general_description', myConfig);
    GeneralDescriptionEditor.on('toolbarLoaded', function() {
         this.on('editorKeyUp', general_description_editor_changed, this, true);
                this.on('editorDoubleClick', general_description_editor_changed, this, true);
                this.on('editorMouseDown', general_description_editor_changed, this, true);
                this.on('buttonClick', general_description_editor_changed, this, true);
    }, GeneralDescriptionEditor, true);
    yuiImgUploader(GeneralDescriptionEditor, 'part_general_description', 'ar_upload_file_from_editor.php','image');
    GeneralDescriptionEditor.render();

    HealthAndSafetyEditor = new YAHOO.widget.Editor('part_health_and_safety', myConfig);
    HealthAndSafetyEditor.on('toolbarLoaded', function() {
         this.on('editorKeyUp',  health_and_safety_editor_changed, this, true);
                this.on('editorDoubleClick', health_and_safety_editor_changed, this, true);
                this.on('editorMouseDown', health_and_safety_editor_changed, this, true);
                this.on('buttonClick', health_and_safety_editor_changed, this, true);
    }, HealthAndSafetyEditor, true);
    yuiImgUploader(HealthAndSafetyEditor, 'part_health_and_safety', 'ar_upload_file_from_editor.php','image');
    HealthAndSafetyEditor.render();



    dialog_country_list = new YAHOO.widget.Dialog("dialog_country_list", {
       
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_country_list.render();
    Event.addListener("set_Part_Origin_Country_Code", "click", show_dialog_country_list);
    Event.addListener("update_Part_Origin_Country_Code", "click", show_dialog_country_list);


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

		var CustomersColumnDefs = [{key: "date",label: "<?php echo _('Date')?>",width: 200,
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
		request="ar_history.php?tipo=history&type=part&tableid="+tableid+"&part_sku="+Dom.get('part_sku').value+"&sf=0"
		
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


	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
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

 


		var tableid = 1;
		var tableDivEL = "table" + tableid;



		var CustomersColumnDefs = [
						    {key:"pid", label:"", hidden:true,action:"none",isPrimaryKey:true}

		 ,{key: "relation",label: "<?php echo _('Relation')?>",width: 50,sortable: false,className: "aleft"}
		,{key:"store",label: "<?php echo _('Store')?>",width: 50,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}		
		,{key:"code",label: "<?php echo _('Code')?>",width: 80,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"link_health_and_safety",label: "<?php echo _('H&S Data')?>",width: 60,<?php echo($_SESSION['state']['part']['products']['view']=='links'?'':'hidden:true,')?>sortable: true,className: "aright"
		,formatter:link_formatter
		,editor: new YAHOO.widget.RadioCellEditor({
					radioOptions:[{label:"<?php echo _('Link')?>", value:"Yes"}, {label:"<?php echo _('Unlink')?>", value:"No"}]
			      	,asyncSubmitter:edit_link }) 
		}
		,{key:"link_tariff",label: "<?php echo _('Tariff Data')?>",width: 60,<?php echo($_SESSION['state']['part']['products']['view']=='links'?'':'hidden:true,')?>sortable: false,className: "aright"}
		,{key:"link_properties",label: "<?php echo _('Properties')?>",width: 60,<?php echo($_SESSION['state']['part']['products']['view']=='links'?'':'hidden:true,')?>sortable: false,className: "aright"}
		,{key:"link_pictures",label: "<?php echo _('Pictures')?>",width: 60,<?php echo($_SESSION['state']['part']['products']['view']=='links'?'':'hidden:true,')?>sortable: false,className: "aright"}

		,{key:"notes",label: "<?php echo _('Notes for Pickers')?>",width: 340, <?php echo($_SESSION['state']['part']['products']['view']=='notes'?'':'hidden:true,')?>sortable: false,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
				  
				  
		];
		
	
		
		
		this.dataSource1 = new YAHOO.util.DataSource("ar_edit_parts.php?tipo=products_in_part&sku="+part_sku+"&tableid="+tableid+"&sf=0");
		this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.dataSource1.connXhrMode = "queueRequests";
		this.dataSource1.responseSchema = {
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
			"sku", "relation", 'code', 'store', 'notes','sales_state','sales_state','formated_web_configuration','web_configuration','pid','link_health_and_safety','link_tariff','link_properties','link_pictures'
			 
			]
			
		};

		this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs, this.dataSource1, {

			renderLoopSize: 50,
			generateRequest: myRequestBuilder,
			paginator: new YAHOO.widget.Paginator({
				rowsPerPage: <?php echo $_SESSION['state']['part']['products']['nr'] ?>,
				containers: 'paginator1',
				alwaysVisible: false,
				pageReportTemplate: '(<?php echo _('Page ')?> {currentPage} <?php echo _('of ')?> {totalPages})',
				previousPageLinkLabel: "<",
				nextPageLinkLabel: ">",
				firstPageLinkLabel: "<<",
				lastPageLinkLabel: ">>",
				rowsPerPageOptions: [10, 25, 50, 100, 250, 500]
				,
				template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"

				
			})

			,
			sortedBy: {
				Key: "<?php echo $_SESSION['state']['part']['products']['order']?>",
				dir: "<?php echo $_SESSION['state']['part']['products']['order_dir']?>"

				
			},
			dynamicData: true


			
		}

		);

		this.table1.handleDataReturnPayload = myhandleDataReturnPayload;
		this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

    this.table1.table_id=tableid;
        this.table1.subscribe("renderEvent", myrenderEvent);

  this.table1.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table1.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table1.subscribe("cellClickEvent", onCellClick);


		this.table1.filter = {
			key: '<?php echo $_SESSION['state']['part']['products']['f_field']?>',
			value: '<?php echo $_SESSION['state']['part']['products']['f_value']?>'
			
		};



function formater_available  (el, oRecord, oColumn, oData) {
		
		     el.innerHTML = oRecord.getData("available_state");
	    }
	    function formater_status  (el, oRecord, oColumn, oData) {
		
		     el.innerHTML = oRecord.getData("formated_status");
	    }

		var tableid = 2;
		var tableDivEL = "table" + tableid;

		var CustomersColumnDefs = [
				    {key:"sppl_key", label:"", hidden:true,action:"none",isPrimaryKey:true}
				    ,				    {key:"sku", label:"", hidden:true,action:"none",isPrimaryKey:true}

		 ,{key: "relation",label: "<?php echo _('SP &rarr; P')?>",width: 70,sortable: false,className: "aleft"}
		,{key:"supplier",label: "<?php echo _('Supplier')?>",width: 80,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}		
		,{key:"code",label: "<?php echo _('SP Code')?>",width: 100,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"name",label: "<?php echo _('Sp Description')?>",width: 300,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}


,{key:"available" ,formatter: formater_available , label:"<?php echo _('Availability')?>",width:100, sortable:false,className:"aright",object:'supplier_product_part',
                    editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:[
				    {'value':"Yes",'label':"<?php echo _('Available')?><br/>"},
				    {'value':"No",'label':"<?php echo _('No available')?>"},
				  
				    ],disableBtns:true})}
				    
				    ,{key:"status" ,formatter: formater_status , label:"<?php echo _('SP Status')?>",width:100, sortable:false,className:"aright",object:'supplier_product_part',
                    editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:[
				    {'value':"Yes",'label':"<?php echo _('Ok')?><br/>"},
				    {'value':"No",'label':"<?php echo _('Remove')?>"},
				  
				    ],disableBtns:true})}
				    
				                
          
				    
				    ,{key:"available_state" , label:"",hidden:true}

				    ,{key:"formated_status" , label:"",hidden:true}


		];
request="ar_edit_parts.php?tipo=supplier_products_in_part&sku="+part_sku+"&tableid="+tableid+"&sf=0";
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
			"sku", "relation", 'code', 'supplier','name','available','available_state','sppl_key','status','formated_status','sku'
			]
			
		};

		this.table2 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs, this.dataSource2, {

			renderLoopSize: 50,
			generateRequest: myRequestBuilder,
			paginator: new YAHOO.widget.Paginator({
				rowsPerPage: <?php echo $_SESSION['state']['part']['supplier_products']['nr'] ?>,
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
				Key: "<?php echo $_SESSION['state']['part']['supplier_products']['order']?>",
				dir: "<?php echo $_SESSION['state']['part']['supplier_products']['order_dir']?>"

				
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
			key: '<?php echo $_SESSION['state']['part']['supplier_products']['f_field']?>',
			value: '<?php echo $_SESSION['state']['part']['supplier_products']['f_value']?>'
			
		};




		    var tableid=3;
		    var tableDivEL="table"+tableid;

   var ColumnDefs = [
   				       {key:"transaction_key", label:"", hidden:true,isPrimaryKey:true} 
						, {key:"id", label:"", hidden:true} 
				      ,{key:"date", label:"<?php echo _('Date')?>", width:150,sortable:false,className:"aright"}
				      
				      ,{key:"type", label:"<?php echo _('Type')?>", width:50,sortable:false,className:"aleft"}
				       ,{key:"user", label:"<?php echo _('User')?>", width:50,sortable:false,className:"aleft"}
				     ,{key:"location", label:"<?php echo _('Location')?>", width:60,sortable:false,className:"aleft"}

				      ,{key:"note", label:"<?php echo _('Note')?>", width:300,sortable:false,className:"aleft"}
				      ,{key:"change", label:"<?php echo _('Change')?>", width:60,sortable:false,className:"aright"}
				      ,{key:"delete", label:"", width:10,sortable:false,object:'part_location_transaction',action:'delete'}
				      ,{key:"edit", label:"", width:10,sortable:false}
				      , {key:"subject_data", label:"", hidden:true} 

				      ];
		 
		    
			request="ar_edit_parts.php?tipo=part_transactions&parent=part&from=&to=&parent_key="+Dom.get('part_sku').value+"&sf=0&tableid="+tableid
			//alert(request)
		    this.dataSource3 = new YAHOO.util.DataSource(request);
		    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
		    this.dataSource3.connXhrMode = "queueRequests";
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
		    totalRecords: "resultset.total_records"			
			
			 
			},
			
			fields: [
				 "date","change","type","location","note","user","edit","delete","transaction_key","id","subject_data"

				 ]};
	    
		    this.table3 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource3, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
								   
									 rowsPerPage:<?php echo$_SESSION['state']['part']['transactions']['nr']?>,containers : 'paginator3', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								   key: "<?php echo$_SESSION['state']['part']['transactions']['order']?>",
								    dir: "<?php echo$_SESSION['state']['part']['transactions']['order_dir']?>"
								  }
								 ,dynamicData : true
								 
							     }
							     );


		    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
		    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
		    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    
		       this.table3.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table3.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table3.subscribe("cellClickEvent", onCellClick);

 this.table3.table_id=tableid;


   this.table3.filter={key:'<?php echo$_SESSION['state']['part']['transactions']['f_field']?>',value:'<?php echo$_SESSION['state']['part']['transactions']['f_value']?>'};




//============


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
		,{key:"supplier",label: "<?php echo _('Supplier')?>",width: 70,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}		
		,{key:"code",label: "<?php echo _('Supplier Product')?>",width: 100,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"name",label: "<?php echo _('Description')?>",width: 230,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"from",label: "<?php echo _('From')?>",width: 150,sortable: true,className: "aright",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"to",label: "<?php echo _('To')?>",width: 150,sortable: true,className: "aright",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}



		];
request="ar_parts.php?tipo=supplier_products_in_part_historic&sku="+part_sku+"&tableid="+tableid+"&sf=0";

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
			"sku", "relation", 'code', 'supplier','name','sppl_key','from','to'
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

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=part-show_history&value=1', {});

}

function hide_history() {
    Dom.setStyle(['show_history', ''], 'display', '')
    Dom.setStyle(['hide_history', 'history_table'], 'display', 'none')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=part-show_history&value=0', {});

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


