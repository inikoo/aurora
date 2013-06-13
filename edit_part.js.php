<?php
 include_once('common.php');

//$money_regex="^[^\\\\d\\\.\\\,]{0,3}(\\\\d{1,3}(\\\,\\\\d{3})*|(\\\\d+))(\\\.\\\\d{2})?$";
//print 'var money_regex="'.$money_regex.'";';
//$number_regex="^(\\\\d{1,3}(\\\,\\\\d{3})*|(\\\\d+))(\\\.\\\\d{1,})?$";
//print 'var number_regex="'.$number_regex.'";';
//$parts=preg_split('/\,/',$_REQUEST['parts']);
//$_parts='';
//foreach($parts as $part){
//   $_parts.="'sku$part':{sku : $part, new:false, deleted:false } ,";
//}
//$_parts=preg_replace("/\,$/","",$_parts);
//print "\nvar part_list={ $_parts };";
//show case 		
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






var CellEdit = function (callback, newValue) {


		var record = this.getRecord(),
		column = this.getColumn(),
		oldValue = this.value,
		datatable = this.getDataTable(),
		recordIndex = datatable.getRecordIndex(record);


						
			if(column.object=='family_page_properties')	
					request_page=	'ar_edit_sites.php';			

			else
		request_page=	'ar_edit_assets.php';			
						
						
						
		YAHOO.util.Connect.asyncRequest(
						'POST',
						request_page, {
						    success:function(o) {
							//alert(o.responseText);
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {

							   
							    
							    if(column.key=='web_configuration'  ){
								 datatable.updateCell(record,'smallname',r.newdata['description']);
								 datatable.updateCell(record,'formated_web_configuration',r.newdata['formated_web_configuration']);
								 datatable.updateCell(record,'web_configuration',r.newdata['web_configuration']);


                             	// alert(r.newdata['web_configuration'])   
								callback(true, r.newdata['web_configuration']);
								
							    }
							    else if(column.key=='available'){
							    								 datatable.updateCell(record,'available_state',r.available_state);

								callback(true, r.newvalue);

							    }else{
							
								callback(true, r.newvalue);
								
							    }
							} else {
							    alert(r.msg);
							    callback();
							}
						    },
							failure:function(o) {
							alert(o.statusText);
							callback();
						    },
							scope:this
							},
						'tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + 
						encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ 
						myBuildUrl(datatable,record)
						
						);  
 };




var validate_scope_data = 
{
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
		,
		'gross_weight': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Gross Weight',
			'name': 'Part_Gross_Weight',
			'ar': false,
			'validation': [{
				'regexp': "\\d",
				'invalid_msg': '<?php echo _('Invalid Weight')?>'
				
			}]
			
		}
		,
		'package_volume': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Package Volume',
			'name': 'Part_Package_Volume',
			'ar': false,
			'validation': [{
				'regexp': "\\d",
				'invalid_msg': '<?php echo _('Invalid Volume')?>'
				
			}]
			
		}
		,
		'package_mov': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Package Minimun Orthogonal Volume',
			'name': 'Part_Package_MOV',
			'ar': false,
			'validation': [{
				'regexp': "\\d",
				'invalid_msg': '<?php echo _('Invalid MOV')?>'
				
			}]
			
		}
		,
		'tariff_code': {
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
		,
				'reference': {
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
		,
				'duty_rate': {
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
		,
		'unit_type': {
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
		,
		'Barcode_Type': {
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
				,
		'Barcode_Data_Source': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'dbname': 'Part Barcode Data Source',
			'name': 'Part_Barcode_Data_Source',
			'ar': false,
			'validation':false
			
		},
		'Barcode_Data': {
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

		//	,'special_characteristic':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'part_Special_Characteristic','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Special Characteristic')?>'}]}
		//  	,'description':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'part_Description','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Description')?>'}]}
		
	}
	

	,
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
		'health_and_safety': {
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
var validate_scope_metadata = {
	'part_unit': {
		'type': 'edit',
		'ar_file': 'ar_edit_parts.php',
		'key_name': 'sku',
		'key': part_sku
		
	},

	'part_description': {
		'type': 'edit',
		'ar_file': 'ar_edit_parts.php',
		'key_name': 'sku',
		'key': part_sku
		
	},
	'part_health_and_safety': {
		'type': 'edit',
		'ar_file': 'ar_edit_parts.php',
		'key_name': 'sku',
		'key': part_sku
		
	},
	'part_custom_field': {
		'type': 'edit',
		'ar_file': 'ar_edit_parts.php',
		'key_name': 'sku',
		'key': part_sku
		
	}

	
};

function validate_Part_Unit_Description(query) {
	validate_general('part_unit', 'description', query);

	
}

function validate_Part_Gross_Weight(query) {
	validate_general('part_unit', 'gross_weight', query);

	
}

function validate_Part_Package_Volume(query) {
	validate_general('part_unit', 'package_volume', query);

	
}

function validate_Part_Package_MOV(query) {
	validate_general('part_unit', 'package_mov', query);
}

function validate_Part_Tariff_Code(query) {
	validate_general('part_unit', 'tariff_code', query);
}
function validate_Part_Reference(query) {
	validate_general('part_unit', 'reference', query);
}
function validate_Part_Barcode_Data(query) {
	validate_general('part_unit', 'Barcode_Data', query);
}




function validate_Part_Duty_Rate(query) {
	validate_general('part_unit', 'duty_rate', query);
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

 <?php

 foreach($show_case as $custom_key =>$custom_value) {

	printf("function validate_part_%s(query){validate_general('part_custom_field','custom_field_part_%s',query);}"
	, $custom_value['lable']
	, $custom_value['lable']
	);

	
}

 ?>

/*
function validate_part_special_characteristic(query){
 validate_general('part_description','special_characteristic',unescape(query));
}
function validate_part_description(query){

 validate_general('part_description','description',unescape(query));
}


function validate_part_unit_weight(query){
 validate_general('part_weight','unit_weight',unescape(query));
}

function validate_part_outer_weight(query){
 validate_general('part_weight','outer_weight',unescape(query));
}
*/
function validate_part_price(query) {

	validate_general('part_price', 'price', unescape(query));

	if (validate_scope_data.part_price.price.validated) {
		var td = Dom.get("price_per_unit");
		var units = parseFloat(td.getAttribute("units"));
		var value = Dom.get(validate_scope_data.part_price.price.name).value;
		price = parseFloat(value.replace(/^[^\d]*/i, ""));
		var rrp = Dom.get(validate_scope_data.part_price.rrp.name).value;
		rrp = parseFloat(rrp.replace(/^[^\d]*/i, ""));

		var cost = parseFloat(td.getAttribute("cost"));
		var old_price = parseFloat(td.getAttribute("old_price"));



		var new_price_per_unit = price / units;
		Dom.get("price_per_unit").innerHTML = money(new_price_per_unit) + " <?php echo _('per unit')?>";
		Dom.get("price_margin").innerHTML = "<?php echo _('Margin')?>: " + percentage(price - cost, price);
		Dom.get("rrp_margin").innerHTML = "<?php echo _('Margin')?>: " + percentage(rrp - price, rrp);

		if (price > old_price) {
			diffence = "<?php echo _('Price up')?> " + percentage(price - old_price, price);

			
		} else {
			diffence = "<?php echo _('Price down')?> " + percentage(price - old_price, price);


			
		}

		Dom.get(validate_scope_data.part_price.price.name + "_msg").innerHTML = diffence;


		
	}



	
}


function validate_part_rrp(query) {

	validate_general('part_price', 'rrp', unescape(query));

	
}

function save_status(key, value) {

    var request = 'ar_edit_parts.php?tipo=edit_part&key=' + key + '&newvalue=' + value + '&sku=' + part_sku + '&okey=' + key
    //alert(request);return;
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
           // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);


            if (r.state == 200) {


                Dom.removeClass([r.key + ' Not In Use', r.key + ' In Use'], 'selected');

                Dom.addClass(r.key + ' ' + r.newvalue, 'selected');

            } else {
                alert(r.msg)


            }
        }
    });

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

	var ids = ["description_block_status", "description_block_properties", "description_block_info", "description_block_health_and_safety","description_block_pictures"];
	var block_ids = ["d_description_block_status", "d_description_block_properties", "d_description_block_info", "d_description_block_health_and_safety","d_description_block_pictures"];

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

function save_edit_part_health_and_safety() {
HealthAndSafetyEditor.saveHTML();
	save_edit_general('part_health_and_safety');
}


function reset_edit_part_description() {
	reset_edit_general('part_description')
    GeneralDescriptionEditor.setEditorHTML(Dom.get('part_general_description').value);
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






function add_part_selected(sType, aArgs) {

	var part_data = aArgs[2];
	var data = {

		"sku": 
		part_data[1]
		,
		"formated_sku": 
		part_data[4]
		,
		"description": 
		part_data[2]

		
	};


	sku = data['sku'];
	formated_sku = data['formated_sku'];
	parts_per_product = 1;
	note = '';
	description = data['description'];


	part_list['sku' + sku] = {
		'sku': sku,
		'new': true,
		'deleted': false
		
	};



	oTbl = Dom.get('part_editor_table');



	oTR = oTbl.insertRow( - 1);




	oTR.id = 'part_list' + sku;

	oTR.setAttribute('sku', sku);

	Dom.addClass(oTR, 'top');
	Dom.addClass(oTR, 'title');

	var oTD = oTR.insertCell(0);
	oTD.innerHTML = '<?php echo _('Part ')?>';
	Dom.addClass(oTD, 'label');

	var oTD = oTR.insertCell(1);
	Dom.addClass(oTD, 'sku');
	oTD.innerHTML = '<span>' + formated_sku + '</span>';
	Dom.setStyle(oTD, 'width', '120px');

	var oTD = oTR.insertCell(2);
	Dom.addClass(oTD, 'description');
	Dom.setStyle(oTD, 'width', '350px');
	oTD.innerHTML = description;

	var oTD = oTR.insertCell(3);
	oTD.innerHTML = '<span style="cursor:pointer" onClick="remove_part(' + sku + ')" ><img src="art/icons/delete_bw.png"/> <?php echo _('Remove ')?></span><span onClick="show_change_part_dialog(' + sku + ',this)"  style="cursor:pointer;margin-left:15px"><img  src="art/icons/arrow_refresh_bw.png"/> <?php echo _('Change ')?></span>';
	oTR = oTbl.insertRow( - 1);
	oTR.id = "sup_tr2_" + sku;
	var oTD = oTR.insertCell(0);
	oTD.innerHTML = '<?php echo _('Parts Per Product ')?>:';
	Dom.addClass(oTD, 'label');

	var oTD = oTR.insertCell(1);
	oTD.setAttribute('colspan', 3);
	oTD.innerHTML = '<input style="padding-left:2px;text-align:left;width:3em" value="' + parts_per_product + '" onblur="part_changed(this)"  onkeyup="part_changed(this)" ovalue="' + parts_per_product + '" id="parts_per_product' + sku + '"> <span  id="parts_per_part_msg' + sku + '"></span>';

	oTR = oTbl.insertRow( - 1);
	oTR.id = "sup_tr3_" + sku;
	Dom.addClass(oTR, 'last');


	var oTD = oTR.insertCell(0);
	oTD.innerHTML = '<?php echo _('Notes For Pickers ')?>:';
	Dom.addClass(oTD, 'label');

	var oTD = oTR.insertCell(1);
	oTD.setAttribute('colspan', 3);
	Dom.setStyle(oTD, 'text-align', 'left');

	oTD.innerHTML = '<input id="pickers_note' + sku + '" style=";width:400px"   onblur="part_changed(this)"  onkeyup="part_changed(this)"     value="' + note + '" ovalue="' + note + '" >';

	part_render_save_buttons();
	Dom.get('add_part_input').value = '';
	close_add_part_dialog();



	
};


function change_part_unit_type(o) {

    var chosenoption = o.options[o.selectedIndex]

    value = chosenoption.value;
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


function geneal_description_editor_changed(){
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

function hide_dialog_delete(delete_type, subject) {
    if (delete_type == 'delete' && subject == 'part_location_transaction') {
        dialog_delete_part_location_transaction.hide()
    }
}


function init() {
init_search('parts');


dialog_delete_part_location_transaction = new YAHOO.widget.Dialog("dialog_delete_part_location_transaction", {visible : false,close:true,underlay: "none",draggable:false});
	dialog_delete_part_location_transaction.render();



	var ids = ["description", "products", "suppliers","transactions"];
	Event.addListener(ids, "click", change_block);
	
	var ids = ["description_block_status", "description_block_properties","description_block_pictures",  "description_block_info", "description_block_health_and_safety"];
	Event.addListener(ids, "click", change_properties_block);
	

	Editor_change_part = new YAHOO.widget.Dialog("Editor_change_part", {
		width: '450px',
		close: false,
		visible: false,
		underlay: "none",
		draggable: false
		
	});
	Editor_change_part.render();


	YAHOO.util.Event.on('uploadButton', 'click', upload_image);



	Editor_add_part = new YAHOO.widget.Dialog("Editor_add_part", {
		close: false,
		visible: false,
		underlay: "none",
		draggable: false
		
	});
	Editor_add_part.render();


	Event.addListener('save_edit_part_unit', "click", save_edit_part_unit);
	Event.addListener('reset_edit_part_unit', "click", reset_edit_part_unit);

	Event.addListener('save_edit_part_description', "click", save_edit_part_description);
	Event.addListener('reset_edit_part_description', "click", reset_edit_part_description);
	
	Event.addListener('save_edit_part_health_and_safety', "click", save_edit_part_health_and_safety);
	Event.addListener('reset_edit_part_health_and_safety', "click", reset_edit_part_health_and_safety);	
	
	
	

	Event.addListener('save_edit_part_custom_field', "click", save_edit_custom_field);
	Event.addListener('reset_edit_part_custom_field', "click", reset_edit_custom_field);

	// Event.addListener('save_edit_part_price', "click", save_edit_price);
	//Event.addListener('reset_edit_part_price', "click", reset_edit_price);
	//Event.addListener('save_edit_part_weight', "click", save_edit_weight);
	//Event.addListener('reset_edit_part_weight', "click", reset_edit_weight);
	



var part_unit_description_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Unit_Description);
	part_unit_description_oACDS.queryMatchContains = true;
	var part_unit_description_oAutoComp = new YAHOO.widget.AutoComplete("Part_Unit_Description", "Part_Unit_Description_Container", part_unit_description_oACDS);
	part_unit_description_oAutoComp.minQueryLength = 0;
	part_unit_description_oAutoComp.queryDelay = 0.1;


	var part_gross_weight_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Gross_Weight);
	part_gross_weight_oACDS.queryMatchContains = true;
	var part_gross_weight_oAutoComp = new YAHOO.widget.AutoComplete("Part_Gross_Weight", "Part_Gross_Weight_Container", part_gross_weight_oACDS);
	part_gross_weight_oAutoComp.minQueryLength = 0;
	part_gross_weight_oAutoComp.queryDelay = 0.1;

	var part_package_volume_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Package_Volume);
	part_package_volume_oACDS.queryMatchContains = true;
	var part_gross_weight_oAutoComp = new YAHOO.widget.AutoComplete("Part_Package_Volume", "Part_Package_Volume_Container", part_package_volume_oACDS);
	part_gross_weight_oAutoComp.minQueryLength = 0;
	part_gross_weight_oAutoComp.queryDelay = 0.1;

	var part_package_mov_oACDS = new YAHOO.util.FunctionDataSource(validate_Part_Package_MOV);
	part_package_mov_oACDS.queryMatchContains = true;
	var part_gross_weight_oAutoComp = new YAHOO.widget.AutoComplete("Part_Package_MOV", "Part_Package_MOV_Container", part_package_mov_oACDS);
	part_gross_weight_oAutoComp.minQueryLength = 0;
	part_gross_weight_oAutoComp.queryDelay = 0.1;


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


	/*
	var part_name_oACDS = new YAHOO.util.FunctionDataSource(validate_part_description);
	part_name_oACDS.queryMatchContains = true;
	var part_name_oAutoComp = new YAHOO.widget.AutoComplete("part_Description","part_Description_Container", part_name_oACDS);
	part_name_oAutoComp.minQueryLength = 0; 
	part_name_oAutoComp.queryDelay = 0.1;


	var part_name_oACDS = new YAHOO.util.FunctionDataSource(validate_part_price);
	part_name_oACDS.queryMatchContains = true;
	var part_name_oAutoComp = new YAHOO.widget.AutoComplete("part_Price","part_Price_Container", part_name_oACDS);
	part_name_oAutoComp.minQueryLength = 0; 
	part_name_oAutoComp.queryDelay = 0.1;

	var part_name_oACDS = new YAHOO.util.FunctionDataSource(validate_part_rrp);
	part_name_oACDS.queryMatchContains = true;
	var part_name_oAutoComp = new YAHOO.widget.AutoComplete("part_RRP","part_RRP_Container", part_name_oACDS);
	part_name_oAutoComp.minQueryLength = 0; 
	part_name_oAutoComp.queryDelay = 0.1;

/*
   var part_name_oACDS = new YAHOO.util.FunctionDataSource(validate_part_unit_weight);
    part_name_oACDS.queryMatchContains = true;
    var part_name_oAutoComp = new YAHOO.widget.AutoComplete("part_Unit_Weight","part_Unit_Weight_Container", part_name_oACDS);
    part_name_oAutoComp.minQueryLength = 0; 
    part_name_oAutoComp.queryDelay = 0.1;
	
	var part_name_oACDS = new YAHOO.util.FunctionDataSource(validate_part_outer_weight);
	part_name_oACDS.queryMatchContains = true;
	var part_name_oAutoComp = new YAHOO.widget.AutoComplete("part_Outer_Weight","part_Outer_Weight_Container", part_name_oACDS);
	part_name_oAutoComp.minQueryLength = 0; 
	part_name_oAutoComp.queryDelay = 0.1;
*/
	
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
         this.on('editorKeyUp', geneal_description_editor_changed, this, true);
                this.on('editorDoubleClick', geneal_description_editor_changed, this, true);
                this.on('editorMouseDown', geneal_description_editor_changed, this, true);
                this.on('buttonClick', geneal_description_editor_changed, this, true);
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





}
 function formater_web_configuration  (el, oRecord, oColumn, oData) {
		     el.innerHTML = oRecord.getData("formated_web_configuration");
	    }
	    
YAHOO.util.Event.addListener(window, "load", 
function() {
	tables = new
	function() {



		var tableid = 0;
		var tableDivEL = "table" + tableid;

		var CustomersColumnDefs = [
		{
			key: "date",
			label: "<?php echo _('Date')?>",
			width: 200,
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
		request="ar_history.php?tipo=history&type=part&tableid=0&part_sku="+Dom.get('part_sku').value
		
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
			key: '<?php echo $_SESSION['state']['product']['history']['f_field']?>',
			value: '<?php echo $_SESSION['state']['product']['history']['f_value']?>'
			
		};

 


		var tableid = 1;
		var tableDivEL = "table" + tableid;



		var CustomersColumnDefs = [
						    {key:"pid", label:"", hidden:true,action:"none",isPrimaryKey:true}

		 ,{key: "relation",label: "<?php echo _('Relation')?>",width: 90,sortable: false,className: "aleft"}
		,{key:"store",label: "<?php echo _('Store')?>",width: 50,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}		
		,{key:"code",label: "<?php echo _('Code')?>",width: 80,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"notes",label: "<?php echo _('Notes for Pickers')?>",width: 300,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
	    ,{key:"sales_type", label:"",hidden:true,width:100, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'product',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:["<?php echo _('Public Sale')?>","<?php echo _('Private Sale')?>","<?php echo _('Discontinue')?>","<?php echo _('Not For Sale')?>"],disableBtns:true})}
				  
				  ,{key:"web_configuration" ,formatter: formater_web_configuration , label:"<?php echo _('Web/Sale Status')?>",hidden:true,width:120, sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'product',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:[
				    {'value':"Online Auto",'label':"<?php echo _('Automatic')?><br/>"},
				    {'value':"Online Force For Sale",'label':"<?php echo _('Force Online')?><br/>"},
				    {'label':"<?php echo _('Force Out of Stock')?><br/>",'value':"Online Force Out of Stock"},
				    {'label':"<?php echo _('Force Offline')?><br/>",'value':'Offline'},
				    {'label':"<?php echo _('Private Sale')?><br/>",'value':'Private Sale'},
				    {'label':"<?php echo _('Not For Sale')?>",'value':'Not for Sale'}
				    ],disableBtns:true})}
				    ,{key:"formated_web_configuration" , label:"",hidden:true}
		];
		
	
		
		
		this.dataSource1 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=products_in_part&sku="+part_sku+"&tableid=1");
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
			"sku", "relation", 'code', 'store', 'notes','sales_state','sales_state','formated_web_configuration','web_configuration','pid'
			 
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

		var tableid = 2;
		var tableDivEL = "table" + tableid;

		var CustomersColumnDefs = [
				    {key:"sppl_key", label:"", hidden:true,action:"none",isPrimaryKey:true}
		 ,{key: "relation",label: "<?php echo _('Relation')?>",width: 70,sortable: false,className: "aleft"}
		,{key:"supplier",label: "<?php echo _('Supplier')?>",width: 80,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}		
		,{key:"code",label: "<?php echo _('Code')?>",width: 100,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"name",label: "<?php echo _('Description')?>",width: 300,sortable: true,className: "aleft",sortOptions: {defaultDir: YAHOO.widget.DataTable.CLASS_ASC}}


,{key:"available" ,formatter: formater_available , label:"<?php echo _('Availability')?>",width:100, sortable:false,className:"aright",object:'supplier_product_part',
                    editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:[
				    {'value':"Yes",'label':"<?php echo _('Available')?><br/>"},
				    {'value':"No",'label':"<?php echo _('No available')?>"},
				  
				    ],disableBtns:true})}
				    ,{key:"available_state" , label:"",hidden:true}



		];

		this.dataSource2 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=supplier_products_in_part&sku="+part_sku+"&tableid=2");
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
			"sku", "relation", 'code', 'supplier','name','available','available_state','sppl_key'
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
		 
		    
//alert("ar_assets.php?tipo=part_transactions&parent=part&parent_key="+Dom.get('part_sku').value+"&sf=0&tableid="+tableid)
		    this.dataSource3 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=part_transactions&parent=part&parent_key="+Dom.get('part_sku').value+"&sf=0&tableid="+tableid);
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


function close_change_part_dialog() {

	Dom.get('change_part').value = '';
	Dom.setStyle('change_part_selector', 'display', '');
	Dom.setStyle('save_change_part', 'display', 'none');
	Dom.setStyle('change_part_confirmation', 'display', 'none');
	Editor_change_part.hide();

	
}

function change_part_selected(sType, aArgs) {
	//alert("caca")
	remove_part(Dom.get('change_part_sku').value)
	 add_part_selected(sType, aArgs);
	close_change_part_dialog();
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

function show_change_part_dialog(sku, o) {

	Dom.get('change_part_sku').value = sku;
	x = Dom.getX(o) - 455;
	y = Dom.getY(o);
	Dom.setX('Editor_change_part', x);
	Dom.setY('Editor_change_part', y);
	Dom.get('change_part').focus();
	Editor_change_part.show();


	
}




YAHOO.util.Event.onContentReady("change_part", 
function() {

	var new_loc_oDS = new YAHOO.util.XHRDataSource("ar_parts.php");
	new_loc_oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
	new_loc_oDS.responseSchema = {
		resultsList: "data"
		,
		
fields: ["info", "sku", "description", "usedin", "formated_sku"]

		
	};
	var new_loc_oAC = new YAHOO.widget.AutoComplete("change_part", "change_part_container", new_loc_oDS);

	new_loc_oAC.generateRequest = function(sQuery) {

		sku = Dom.get("change_part_sku").value;
		request = "?tipo=find_part&except_part=" + sku + "&query=" + sQuery;
		return request;
		
	};
	new_loc_oAC.forceSelection = true;
	new_loc_oAC.itemSelectEvent.subscribe(change_part_selected);


	
});


function remove_part(sku) {

	part_list['sku' + sku].deleted = true;
	Dom.setStyle(['part_list' + sku, 'sup_tr2_' + sku, 'sup_tr3_' + sku], 'display', 'none');
	part_render_save_buttons();

	
}


YAHOO.util.Event.onContentReady("rppmenu0", 
function() {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {
		trigger: "rtext_rpp0"
		
	});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);

	
});

YAHOO.util.Event.onContentReady("filtermenu0", 
function() {
	var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
		trigger: "filter_name0"
		
	});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);

	
});


YAHOO.util.Event.onContentReady("rppmenu1", 
function() {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {
		trigger: "rtext_rpp1"
		
	});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);

	
});

YAHOO.util.Event.onContentReady("filtermenu1", 
function() {
	var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
		trigger: "filter_name1"
		
	});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);

	
});

YAHOO.util.Event.onContentReady("rppmenu3", 
function() {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu3", {
		trigger: "rtext_rpp3"
		
	});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);

	
});

YAHOO.util.Event.onContentReady("filtermenu3", 
function() {
	var oMenu = new YAHOO.widget.ContextMenu("filtermenu3", {
		trigger: "filter_name3"
		
	});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);

	
});


