<?php
include_once('common.php');



?>

var audit_dialog;
var add_stock_dialog;

var Editor_lost_items;
var Editor_move_items;
var Editor_limit_quantities;
var dialog_locations;




function over_can_pick(o) {

    if (o.getAttribute('can_pick') == 'No') o.src = "art/icons/box.png";
    else o.src = "art/icons/basket.png";

}

function out_can_pick(o) {

    if (o.getAttribute('can_pick') == 'No') o.src = "art/icons/basket.png";
    else o.src = "art/icons/box.png";

}




function set_all_lost() {
    Dom.get('qty_lost').value = Dom.get('lost_max_value').innerHTML;
    Dom.get('lost_why').focus();
}

function save_lost_items() {


    if (Dom.get('qty_lost').value < 0) {
        Dom.setStyle('error_negative_number_in_lost_stock', 'display', '');

        return;
    } else {
        Dom.setStyle('error_negative_number_in_lost_stock', 'display', 'none');

    }


    Dom.setStyle('save_lost_waiting', 'display', '');
    Dom.setStyle('save_lost_btn', 'display', 'none');
    Dom.setStyle('cancel_lost_btn', 'display', 'none');

    var data = new Object();
    data['qty'] = Dom.get('qty_lost').value;
    data['why'] = Dom.get('lost_why').value;
    data['action'] = Dom.get('lost_action').value;
    data['type'] = Dom.get('lost_type').value;
    data['location_key'] = Dom.get('lost_location_key').value
    data['part_sku'] = Dom.get('lost_sku').value;
    location_key = Dom.get('lost_location_key').value;
    sku = Dom.get('lost_sku').value;
    var json_value = YAHOO.lang.JSON.stringify(data);
    var request = 'ar_edit_warehouse.php?tipo=lost_stock&values=' + my_encodeURIComponent(json_value);

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            // alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.action == 'ok') {


                close_lost_dialog();


                Dom.get('part_location_quantity_' + sku + '_' + location_key).setAttribute('quantity', r.qty);
                Dom.get('part_location_quantity_' + sku + '_' + location_key).innerHTML = r.formated_qty;
                if (r.qty <= 0) {
                    Dom.get("part_location_lost_items_" + sku + "_" + location_key).style.display = 'none';
                } else {
                    Dom.get("part_location_lost_items_" + sku + "_" + location_key).style.display = '';

                }



                if (r.qty == 0) {
                    Dom.get("part_location_delete_" + sku + "_" + location_key).style.display = '';

                } else {
                    Dom.get("part_location_delete_" + sku + "_" + location_key).style.display = 'none';

                }

                if (r.stock == 0) {
                    Dom.get("part_location_move_items_" + sku + "_" + location_key).style.display = 'none';

                } else {

                    Dom.get("part_location_move_items_" + sku + "_" + location_key).style.display = '';
                }

                Dom.get('stock').innerHTML = r.formated_qty;
                Dom.get('value_at_cost').innerHTML = r.value_at_cost;
                Dom.get('value_at_current_cost').innerHTML = r.value_at_current_cost;
                Dom.get('commercial_value').innerHTML = r.commercial_value;
                Dom.get('current_stock').innerHTML = r.current_stock;
                Dom.get('current_stock_picked').innerHTML = r.current_stock_picked;
                Dom.get('current_stock_in_process').innerHTML = r.current_stock_in_process;
                Dom.get('current_stock_available').innerHTML = r.current_stock_available;
				Dom.get('available_for_forecast').innerHTML=r.available_for_forecast;



                table_id = 1
                var table = tables['table' + table_id];
                var datasource = tables['dataSource' + table_id];

                var request = '&tableid=' + table_id;
                if (Dom.get('page_name').value == 'part') {
                    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
                    for (index = 0; index < r.product_data.length; ++index) {
                        Dom.get('product_web_state_' + r.product_data[index].pid).innerHTML = r.product_data[index].web_state;
                        Dom.get('product_web_state_configuration_' + r.product_data[index].pid).innerHTML = r.product_data[index].web_state_configuration;
                    }

                }

            } else if (r.action == 'error') {
                alert(r.msg);
            }



        }
    });

}

function audit(sku, location_key) {
    Dom.get("audit_location_key").value = location_key;
    Dom.get("audit_sku").value = sku;

    var pos = Dom.getXY('part_location_audit_' + sku + '_' + location_key);

    pos[0] = pos[0] - 280
    audit_dialog.show();
    Dom.setXY('Editor_audit', pos);
    Dom.get('qty_audit').focus();

}

function lost(sku, location_key) {
    Dom.setStyle('error_negative_number_in_lost_stock', 'display', 'none');


    Dom.removeClass(['lost_type_other_out', 'lost_type_broken'], 'selected');
    Dom.addClass('lost_type_lost', 'selected');
    Dom.get('lost_type').value = 'Lost';

    Dom.setStyle('save_lost_waiting', 'display', 'none');
    Dom.setStyle('save_lost_btn', 'display', '');
    Dom.setStyle('cancel_lost_btn', 'display', '');



    qty = Dom.get('part_location_quantity_' + sku + '_' + location_key).getAttribute('quantity');
    Dom.get('lost_max_value').innerHTML = qty;
    Dom.get('lost_sku').value = sku;
    Dom.get('lost_location_key').value = location_key;


    x = Dom.getX('part_location_lost_items_' + sku + '_' + location_key);
    y = Dom.getY('part_location_lost_items_' + sku + '_' + location_key);

    Dom.setX('Editor_lost_items', x - 258);
    Dom.setY('Editor_lost_items', y - 4);
    Dom.get('qty_lost').focus();
    Editor_lost_items.show();
}


function add_stock_part_location(sku, location_key) {


    Dom.setStyle('add_stock_waiting', 'display', 'none')

    Dom.setStyle('add_stock_save_btn', 'display', '')
    Dom.setStyle('add_stock_cancel_btn', 'display', '')




    Dom.get("add_stock_location_key").value = location_key;
    Dom.get("add_stock_sku").value = sku;

    var pos = Dom.getXY('part_location_add_stock_' + sku + '_' + location_key);

    pos[0] = pos[0] - 260
    add_stock_dialog.show();
    Dom.setXY('Editor_add_stock', pos);
    Dom.get('qty_add_stock').focus();

}

function change_lost_type(type) {

    Dom.removeClass(['lost_type_lost', 'lost_type_other_out', 'lost_type_broken'], 'selected');
    Dom.addClass('lost_type_' + type, 'selected');
    if (type == 'lost') Dom.get('lost_type').value = 'Lost';
    else if (type == 'broken') Dom.get('lost_type').value = 'Broken';
    else if (type == 'other_out') Dom.get('lost_type').value = 'Other Out';


}


function delete_part_location(sku, location_key) {

    ar_file = 'ar_edit_warehouse.php';
    YAHOO.util.Connect.asyncRequest('GET', ar_file + '?tipo=delete_part_location&part_sku=' + sku + '&location_key=' + location_key, {
        success: function(o) {

            if (o.responseText == 'Ok') {
                Dom.get('part_location_tr_' + sku + '_' + location_key).parentNode.removeChild(Dom.get('part_location_tr_' + sku + '_' + location_key));

                table_id = 1
                var table = tables['table' + table_id];
                var datasource = tables['dataSource' + table_id];
                var request = '&tableid=' + table_id;
                if (Dom.get('page_name').value == 'part') {
                    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
                    for (index = 0; index < r.product_data.length; ++index) {
                        Dom.get('product_web_state_' + r.product_data[index].pid).innerHTML = r.product_data[index].web_state;
                        Dom.get('product_web_state_configuration_' + r.product_data[index].pid).innerHTML = r.product_data[index].web_state_configuration;
                    }

                }


            } else {
                alert(o.responseText);
            }
        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });


}

function add_location(sku) {
    Dom.get('add_location_sku').value = sku;
    region1 = Dom.getRegion('add_location_button');
    region2 = Dom.getRegion('Editor_add_location');

    var pos = [region1.right - region2.width - 20, region1.bottom]
    Dom.setXY('Editor_add_location', pos);



    Dom.get('add_location_input').focus();
    Editor_add_location.show();
}

function move(sku, location_key) {
    Dom.setStyle('move_items_btn', 'display', '');
    Dom.get('location_move_other_locations').innerHTML = '';
    var request = 'ar_warehouse.php?tipo=other_locations_quick_buttons&sku=' + sku + '&location_key=' + location_key;
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            Dom.get('location_move_other_locations').innerHTML = r.other_locations_quick_buttons;

        }
    });


    part_location_element = Dom.get('part_location_move_items_' + sku + '_' + location_key);

    qty = Dom.get('part_location_quantity_' + sku + '_' + location_key).getAttribute('quantity');
    Dom.get('move_sku').value = sku;
    Dom.get('move_sku_formated').innerHTML = Dom.get('part_location_move_items_' + sku + '_' + location_key).getAttribute('sku_formated');
    Dom.get('this_location').innerHTML = Dom.get('part_location_move_items_' + sku + '_' + location_key).getAttribute('location');
    Dom.get('move_stock_left').innerHTML = qty;
    Dom.get('move_stock_left').setAttribute('ovalue', qty);
    Dom.get('move_stock_right').setAttribute('ovalue', 0);

    Dom.get('move_this_location_key').value = location_key;


    if (qty == 0) {
        Dom.get('flow').setAttribute('flow', 'left');
        Dom.get('flow').innerHTML = '<img src="art/icons/arrow_left.png"/>';
    }



    x = Dom.getX('part_location_move_items_' + sku + '_' + location_key);
    y = Dom.getY('part_location_move_items_' + sku + '_' + location_key);


    Dom.setX('Editor_move_items', x - 256);
    Dom.setY('Editor_move_items', y - 4);
    Dom.get('location_move_to_input').focus();

    Editor_move_items.show();

}

function save_add_stock() {


    if (Dom.get('qty_add_stock').value < 0) {
        Dom.setStyle('error_negative_number_in_add_stock', 'display', '');

        return;
    } else {
        Dom.setStyle('error_negative_number_in_add_stock', 'display', 'none');

    }

    Dom.setStyle('add_stock_waiting', 'display', '')

    Dom.setStyle('add_stock_save_btn', 'display', 'none')
    Dom.setStyle('add_stock_cancel_btn', 'display', 'none')


    var data = new Object();
    data['qty'] = Dom.get('qty_add_stock').value;
    data['note'] = Dom.get('note_add_stock').value;
    data['location_key'] = Dom.get('add_stock_location_key').value
    data['part_sku'] = Dom.get('add_stock_sku').value;

    var json_value = YAHOO.lang.JSON.stringify(data);
    var request = 'ar_edit_warehouse.php?tipo=add_stock&values=' + my_encodeURIComponent(json_value);

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
             //  alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('part_location_quantity_' + r.sku + '_' + r.location_key).setAttribute('quantity', r.qty);
                Dom.get('part_location_quantity_' + r.sku + '_' + r.location_key).innerHTML = r.formated_qty;
                if (r.newvalue <= 0) {
                    Dom.get("part_location_lost_items_" + r.sku + "_" + r.location_key).style.display = 'none';
                } else {
                    Dom.get("part_location_lost_items_" + r.sku + "_" + r.location_key).style.display = '';

                }

                if (r.newvalue == 0) {
                    Dom.get("part_location_delete_" + r.sku + "_" + r.location_key).style.display = '';

                } else {
                    Dom.get("part_location_delete_" + r.sku + "_" + r.location_key).style.display = 'none';

                }



                if (r.stock == 0) {
                    Dom.get("part_location_move_items_" + r.sku + "_" + r.location_key).style.display = 'none';

                } else {

                    Dom.get("part_location_move_items_" + r.sku + "_" + r.location_key).style.display = '';
                }


                Dom.get('stock').innerHTML = r.formated_qty;
                Dom.get('value_at_cost').innerHTML = r.value_at_cost;
                Dom.get('value_at_current_cost').innerHTML = r.value_at_current_cost;
                Dom.get('commercial_value').innerHTML = r.commercial_value;
                Dom.get('current_stock').innerHTML = r.current_stock;
                Dom.get('current_stock_picked').innerHTML = r.current_stock_picked;
                Dom.get('current_stock_in_process').innerHTML = r.current_stock_in_process;
                Dom.get('current_stock_available').innerHTML = r.current_stock_available;

				Dom.get('available_for_forecast').innerHTML=r.available_for_forecast;


                close_add_stock_dialog();
                table_id = 1
                var table = tables['table' + table_id];
                var datasource = tables['dataSource' + table_id];

                var request = '&tableid=' + table_id;

                if (Dom.get('page_name').value == 'part') {
                    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
                    for (index = 0; index < r.product_data.length; ++index) {
                        Dom.get('product_web_state_' + r.product_data[index].pid).innerHTML = r.product_data[index].web_state;
                        Dom.get('product_web_state_configuration_' + r.product_data[index].pid).innerHTML = r.product_data[index].web_state_configuration;
                    }

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

}


function save_audit() {


    var data = new Object();
    data['qty'] = Dom.get('qty_audit').value;
    data['note'] = Dom.get('note_audit').value;
    data['location_key'] = Dom.get('audit_location_key').value
    data['part_sku'] = Dom.get('audit_sku').value;

    sku = Dom.get('lost_sku').value;
    var json_value = YAHOO.lang.JSON.stringify(data);
    var request = 'ar_edit_warehouse.php?tipo=audit_stock&values=' + my_encodeURIComponent(json_value);
    //alert(request)
    Dom.setStyle('Editor_audit_buttons', 'display', 'none')
    Dom.setStyle('Editor_audit_wait', 'display', '')

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('part_location_quantity_' + r.sku + '_' + r.location_key).setAttribute('quantity', r.qty);
                Dom.get('part_location_quantity_' + r.sku + '_' + r.location_key).innerHTML = r.formated_qty;
                if (r.newvalue <= 0) {
                    Dom.get("part_location_lost_items_" + r.sku + "_" + r.location_key).style.display = 'none';
                } else {
                    Dom.get("part_location_lost_items_" + r.sku + "_" + r.location_key).style.display = '';

                }

                if (r.newvalue == 0) {
                    Dom.get("part_location_delete_" + r.sku + "_" + r.location_key).style.display = '';

                } else {
                    Dom.get("part_location_delete_" + r.sku + "_" + r.location_key).style.display = 'none';

                }



                if (r.stock == 0) {
                    Dom.get("part_location_move_items_" + r.sku + "_" + r.location_key).style.display = 'none';

                } else {

                    Dom.get("part_location_move_items_" + r.sku + "_" + r.location_key).style.display = '';
                }

                Dom.get('stock').innerHTML = r.formated_qty;
                Dom.get('value_at_cost').innerHTML = r.value_at_cost;
                Dom.get('value_at_current_cost').innerHTML = r.value_at_current_cost;
                Dom.get('commercial_value').innerHTML = r.commercial_value;
                Dom.get('current_stock').innerHTML = r.current_stock;
                Dom.get('current_stock_picked').innerHTML = r.current_stock_picked;
                Dom.get('current_stock_in_process').innerHTML = r.current_stock_in_process;
                Dom.get('current_stock_available').innerHTML = r.current_stock_available;
				Dom.get('available_for_forecast').innerHTML=r.available_for_forecast;

                close_audit_dialog();
                table_id = 1
                var table = tables['table' + table_id];
                var datasource = tables['dataSource' + table_id];

                var request = '&tableid=' + table_id;

                if (Dom.get('page_name').value == 'part') {
                    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
                    for (index = 0; index < r.product_data.length; ++index) {
                        Dom.get('product_web_state_' + r.product_data[index].pid).innerHTML = r.product_data[index].web_state;
                        Dom.get('product_web_state_configuration_' + r.product_data[index].pid).innerHTML = r.product_data[index].web_state_configuration;
                    }

                }



            } else {
                alert(r.msg);
                callback();
            }
            Dom.setStyle('Editor_audit_buttons', 'display', '')
            Dom.setStyle('Editor_audit_wait', 'display', 'none')
        },
        failure: function(o) {
            alert(o.statusText);
            callback();
        },
        scope: this
    }, request

    );

}

function close_audit_dialog() {
    Dom.get('qty_audit').value = '';
    Dom.get('note_audit').value = '';
    audit_dialog.hide();
}

function close_add_stock_dialog() {
    Dom.setStyle('error_negative_number_in_add_stock', 'display', 'none');

    Dom.get('qty_add_stock').value = '';
    Dom.get('note_add_stock').value = '';
    add_stock_dialog.hide();
}

function close_lost_dialog() {
    Dom.get('qty_lost').value = '';
    Dom.get('lost_why').value = '';
    Dom.get('lost_action').value = '';



    Editor_lost_items.cfg.setProperty('visible', false);
}


function create_part_location_tr(tag,r) {


    var sku=r.sku;
    var formated_sku=r._formated_sku;
    if (tag=='from') {
        var location_key=r.location_key_from;
        var location_code=r.location_code_from;
        var formated_qty=r.formated_qty_from
        var qty=r.qty_from;
    } else if(tag=='to'){
        var location_key=r.location_key_to;
        var location_code=r.location_code_to;
        var formated_qty=r.formated_qty_to;
           var qty=r.qty_to;
    } else{
        var location_key=r.location_key;
        var location_code=r.location_code;
        var formated_qty=r.formated_qty;
       var qty=r.qty;
    }



    oTbl=Dom.get('part_locations');
    oTR= oTbl.insertRow(0);
    oTR.id='part_location_tr_'+sku+'_'+location_key;

    var oTD= oTR.insertCell(0);
    if(r.can_pick=='Yes')
    	oTD.innerHTML=  '<a href="location.php?id='+location_key+'">'+location_code+'</a>  <img style="cursor:pointer" sku_formated="'+sku+'" location="'+location_code+'" id="part_location_can_pick_'+sku+'_'+location_key+'"   onmouseover="over_can_pick(this)" onmouseout="out_can_pick(this)"  can_pick="Yes"   src="art/icons/basket.png" title="<?php echo _('Can pick')?>"  alt="<?php echo _('Can Pick')?>"   onClick="save_can_pick('+sku+','+location_key+')" /> ';
	else
	    oTD.innerHTML=  '<a href="location.php?id='+location_key+'">'+location_code+'</a>  <img style="cursor:pointer" sku_formated="'+sku+'" location="'+location_code+'" id="part_location_can_pick_'+sku+'_'+location_key+'"   onmouseover="over_can_pick(this)" onmouseout="out_can_pick(this)"  can_pick="No"   src="art/icons/box.png" title="<?php echo _('Can pick')?>"  alt="<?php echo _('Can Pick')?>"   onClick="save_can_pick('+sku+','+location_key+')" /> ';

	
 	var oTD= oTR.insertCell(1);
    //Dom.addClass(oTD,'quantity');
    oTD.id='picking_limit_quantities_'+sku+'_'+location_key;
  
    oTD.innerHTML=r.limits;
	
	Dom.get(oTD).onclick=function (){show_picking_limit_quantities(this)};
	Dom.get(oTD).setAttribute('min_value','')
	Dom.get(oTD).setAttribute('max_value','')
	Dom.get(oTD).setAttribute('location_key',r.location_key)

	Dom.setStyle(oTD,'cursor','pointer')
	Dom.setStyle(oTD,'color','#808080')
if(r.can_pick!='Yes')
		Dom.setStyle(oTD,'display','none')





    var oTD= oTR.insertCell(2);
    Dom.addClass(oTD,'quantity');
    oTD.id='part_location_quantity_'+sku+'_'+location_key;
    oTD.setAttribute('quantity',qty);
    oTD.innerHTML=formated_qty;

    var oTD= oTR.insertCell(3);
    Dom.addClass(oTD,'button');
    oTD.innerHTML='<img  id="part_location_audit_'+sku+'_'+location_key+'" src="art/icons/note_edit.png"  title="<?php echo _('audit')?>" alt="<?php echo _('audit')?>" onClick="audit('+sku+','+location_key+')" />';

   var oTD= oTR.insertCell(4);
    Dom.addClass(oTD,'button');
    oTD.innerHTML='<img  sku_formated="'+formated_sku+'" location="'+location_code+'" id="part_location_add_stock_'+sku+'_'+location_key+'"  src="art/icons/lorry.png" title="<?php echo _('add stock')?>"  alt="<?php echo _('add stock')?>" onClick="add_stock_part_location('+sku+','+location_key+')" />';


    var oTD= oTR.insertCell(5);
    Dom.addClass(oTD,'button');
    oTD.innerHTML='<img  sku_formated="'+formated_sku+'" location="'+location_code+'"   id="part_location_delete_'+sku+'_'+location_key+'" src="art/icons/cross_bw.png" title="<?php echo _('delete')?>"  alt="<?php echo _('delete')?>" onClick="delete_part_location('+sku+','+location_key+')" /><img id="part_location_lost_items_'+sku+'_'+location_key+'" src="art/icons/package_delete.png" alt="{t}lost{/t}" onClick="lost('+sku+','+location_key+')" />';

    var oTD= oTR.insertCell(6);
    Dom.addClass(oTD,'button');
    oTD.innerHTML='<img sku_formated="'+formated_sku+'" location="'+location_code+'" id="part_location_move_items_'+sku+'_'+location_key+'"  src="art/icons/package_go.png" alt="{t}move{/t}" onClick="move('+sku+','+location_key+')" />';



}

function get_locations(e, part_sku) {
    //alert(sku);
    var request = 'ar_edit_orders.php?tipo=get_locations&part_sku=' + part_sku;
    //alert(request);  
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('location_content').innerHTML = r.result;

            } else {
                alert(r.msg);
            }
        }
    });

    region1 = Dom.getRegion(e);
    region2 = Dom.getRegion('dialog_locations');
    var pos = [region1.right, region1.top]

    Dom.setXY('dialog_locations', pos);
    dialog_locations.show();
}

function save_can_pick(sku, location_key) {

    if (!Dom.get('modify_stock').value) {
        return;
    }

    ar_file = 'ar_edit_warehouse.php';

    request = ar_file + '?tipo=part_location_update_can_pick&sku=' + sku + '&location_key=' + location_key + '&can_pick=' + Dom.get('part_location_can_pick_' + sku + '_' + location_key).getAttribute('can_pick');


    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {



                if (r.can_pick == 'Yes') {
                    Dom.get('part_location_can_pick_' + r.sku + '_' + r.location_key).setAttribute('can_pick', 'No');
                    Dom.get('part_location_can_pick_' + r.sku + '_' + r.location_key).src = "art/icons/basket.png";
                } else {
                    Dom.get('part_location_can_pick_' + r.sku + '_' + r.location_key).src = "art/icons/box.png";

                    Dom.get('part_location_can_pick_' + r.sku + '_' + r.location_key).setAttribute('can_pick', 'Yes');

                }
                window.location.reload();
            }

        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });

}



function change_move_flow() {

}

function save_move_items() {
    Dom.setStyle('move_items_btn', 'display', 'none');
    var data = new Object();
    data['qty'] = Dom.get('move_qty').value;
    data['part_sku'] = Dom.get('move_sku').value;
    sku = Dom.get('move_sku').value;
    if (Dom.get('flow').getAttribute('flow') == 'right') {
        data['from_key'] = Dom.get('move_this_location_key').value;
        data['to_key'] = Dom.get('move_other_location_key').value;
    } else {
        data['from_key'] = Dom.get('move_other_location_key').value;
        data['to_key'] = Dom.get('move_this_location_key').value;
    }



    //if(data['from_key']<=0 || data['to_key']<=0   || data['qty']<=0){
    //    return;
    //}



    var json_value = YAHOO.lang.JSON.stringify(data);
    var request = 'ar_edit_warehouse.php?tipo=move_stock&values=' + encodeURIComponent(json_value);
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //       alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.action == 'ok') {
                close_lost_dialog();



                if (Dom.get('part_location_quantity_' + sku + '_' + r.location_key_from) == undefined) {
                    create_part_location_tr('from', r);
                }
                //alert(Dom.get('part_location_quantity_'+sku+'_'+r.location_key_to))
                if (Dom.get('part_location_quantity_' + sku + '_' + r.location_key_to) == undefined) {
                    create_part_location_tr('to', r);
                }
                Dom.get('part_location_quantity_' + sku + '_' + r.location_key_from).setAttribute('quantity', r.qty_from);
                Dom.get('part_location_quantity_' + sku + '_' + r.location_key_from).innerHTML = r.formated_qty_from;
                Dom.get('part_location_quantity_' + sku + '_' + r.location_key_to).setAttribute('quantity', r.qty_to);
                Dom.get('part_location_quantity_' + sku + '_' + r.location_key_to).innerHTML = r.formated_qty_to;



                Dom.get("part_location_quantity_" + sku + "_" + r.location_key_from).innerHTML = r.formated_qty_from;
                if (r.qty_from <= 0) {
                    Dom.get("part_location_lost_items_" + sku + "_" + r.location_key_from).style.display = 'none';
                } else {
                    Dom.get("part_location_lost_items_" + sku + "_" + r.location_key_from).style.display = '';

                }

                if (r.qty_from == 0) {
                    Dom.get("part_location_delete_" + sku + "_" + r.location_key_from).style.display = '';

                } else {
                    Dom.get("part_location_delete_" + sku + "_" + r.location_key_from).style.display = 'none';

                }

                Dom.get("part_location_quantity_" + sku + "_" + r.location_key_to).innerHTML = r.formated_qty_to;
                if (r.qty_to <= 0) {
                    Dom.get("part_location_lost_items_" + sku + "_" + r.location_key_to).style.display = 'none';
                } else {
                    Dom.get("part_location_lost_items_" + sku + "_" + r.location_key_to).style.display = '';

                }

                if (r.qty_to == 0) {
                    Dom.get("part_location_delete_" + sku + "_" + r.location_key_to).style.display = '';

                } else {
                    Dom.get("part_location_delete_" + sku + "_" + r.location_key_to).style.display = 'none';

                }






                close_move_dialog();
                table_id = 1
                var table = tables['table' + table_id];
                var datasource = tables['dataSource' + table_id];
                var request = '&tableid=' + table_id;
                if (Dom.get('page_name').value == 'part') {
                    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);




                }







            } else if (r.action == 'error') {
                alert(r.msg);
            }



        }
    });




}




function move_stock_right() {



    if (isNaN(parseFloat(Dom.get("move_stock_right").getAttribute('ovalue')))) {
        return;
    }
    var qty_left = Dom.get("move_stock_left").innerHTML;
    if (qty_left > 0) {
        var _qty_change = Dom.get('move_qty').value;
        if (_qty_change == '') _qty_change = 0;
        var qty_change = parseFloat(_qty_change + ' ' + qty_change);


        qty_change = qty_change + 1;
        Dom.get('move_qty').value = qty_change;
        move_qty_changed();
    }
}

function move_stock_left() {

    if (isNaN(parseFloat(Dom.get("move_stock_left").getAttribute('ovalue')))) {
        return;
    }

    var qty_right = Dom.get("move_stock_right").innerHTML;
    if (qty_right > 0) {
        var _qty_change = Dom.get('move_qty').value;
        if (_qty_change == '') _qty_change = 0;
        var qty_change = parseFloat(_qty_change + ' ' + qty_change);
        qty_change = qty_change + 1;
        Dom.get('move_qty').value = qty_change;
        move_qty_changed();
    }
}

function roundNumber(num, dec) {
    var result = Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
    return result;
}


function move_qty_changed() {




    var _qty_change = Dom.get('move_qty').value;
    if (_qty_change == '') _qty_change = 0;



    var qty_change = parseFloat(_qty_change + ' ' + qty_change);
    if (isNaN(qty_change)) return;


    left_old_value = parseFloat(Dom.get("move_stock_left").getAttribute('ovalue'));
    right_old_value = parseFloat(Dom.get("move_stock_right").getAttribute('ovalue'));


    if (qty_change < 0 && left_old_value >= 0) {
        Dom.addClass('move_qty', 'error');
        return;
    } else Dom.removeClass('move_qty', 'error');





    if (Dom.get('flow').getAttribute('flow') == 'right') {
        //   if (left_old_value < qty_change) {
        //       Dom.addClass('move_qty','error');
        //       qty_change=left_old_value;
        //   } else
        //       Dom.removeClass('move_qty','error');


        left_value = left_old_value - qty_change;
        right_value = right_old_value + qty_change;
    } else {
        //    if (right_old_value < qty_change) {
        //       Dom.addClass('move_qty','error');
        //       qty_change=right_old_value;
        //   } else
        //      Dom.removeClass('move_qty','error');
        left_value = roundNumber(left_old_value + qty_change, 3);
        right_value = roundNumber(right_old_value - qty_change, 3);


    }

    Dom.get("move_stock_left").innerHTML = left_value;
    Dom.get("move_stock_right").innerHTML = right_value;

    //alert(left_value+' '+right_value)
}

function close_add_location_dialog() {
    Dom.get('move_stock_right').innerHTML = '';

    Editor_add_location.cfg.setProperty('visible', false);
}

function close_move_dialog() {
    Dom.get('move_stock_right').innerHTML = '';
    Dom.get('move_qty').value = '';
    Dom.get('location_move_to_input').value = '';

    Dom.get('flow').setAttribute('flow', 'right');

    Dom.get('flow').innerHTML = '<img src="art/icons/arrow_right.png"/>';





    Editor_move_items.cfg.setProperty('visible', false);
}

function select_move_location(location_key, location_code, stock) {

    Dom.get('move_stock_right').innerHTML = stock;
    Dom.get('move_stock_right').setAttribute('ovalue', stock);
    Dom.get('move_other_location_key').value = location_key;
    Dom.get('location_move_to_input').value = location_code;
    Dom.get('move_qty').value = '';

    move_qty_changed();
}


function location_move_to_selected(sType, aArgs) {

    var locData = aArgs[2];
    var data = {
        "location_code": locData[0],
        "location_key": locData[1],
        "stock": locData[2]
    };
    Dom.get('move_stock_right').innerHTML = data['stock'];
    Dom.get('move_stock_right').setAttribute('ovalue', data['stock']);
    Dom.get('move_other_location_key').value = data['location_key'];
    Dom.get('move_qty').value = '';
    move_qty_changed();
};

function add_location_selected(sType, aArgs) {

    var locData = aArgs[2];
    var data = {
        "location_code": locData[0],
        "location_key": locData[1],
        "stock": locData[2]
    };

    var sku = Dom.get('add_location_sku').value;

    var json_value = YAHOO.lang.JSON.stringify(data);
    var request = 'ar_edit_warehouse.php?tipo=add_part_to_location&location_key=' + data.location_key + '&part_sku=' + sku;

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.action == 'added') {
                close_add_location_dialog();



                if (Dom.get('part_location_quantity_' + sku + '_' + r.location_key) == undefined) {
                    create_part_location_tr('', r);
                }

                Dom.get('part_location_quantity_' + sku + '_' + r.location_key).setAttribute('quantity', r.qty);
                Dom.get('part_location_quantity_' + sku + '_' + r.location_key).innerHTML = r.formated_qty;
                if (r.qty <= 0) {
                    Dom.get("part_location_lost_items_" + sku + "_" + r.location_key).style.display = 'none';
                } else {
                    Dom.get("part_location_lost_items_" + sku + "_" + r.location_key).style.display = '';

                }

                if (r.qty == 0) {
                    Dom.get("part_location_delete_" + sku + "_" + r.location_key).style.display = '';

                } else {
                    Dom.get("part_location_delete_" + sku + "_" + r.location_key).style.display = 'none';

                }




                table_id = 1
                var table = tables['table' + table_id];
                var datasource = tables['dataSource' + table_id];
                var request = '&tableid=' + table_id;
                if (Dom.get('page_name').value == 'part') {
                    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
                    for (index = 0; index < r.product_data.length; ++index) {
                        Dom.get('product_web_state_' + r.product_data[index].pid).innerHTML = r.product_data[index].web_state;
                        Dom.get('product_web_state_configuration_' + r.product_data[index].pid).innerHTML = r.product_data[index].web_state_configuration;
                    }


                }







            } else if (r.action == 'error') {
                alert(r.msg);
            } else if (r.action == 'nochange') {
                close_add_location();
            }



        }
    });




};


function show_picking_limit_quantities(o) {
    Dom.setStyle('dialog_qty_msg', 'display', 'none')

    if (!Dom.get('modify_stock').value) return;

    region1 = Dom.getRegion(o);
    region2 = Dom.getRegion('Editor_limit_quantities');

    var pos = [region1.right - region2.width, region1.bottom]

    Dom.setXY('Editor_limit_quantities', pos);


    Dom.get('min_qty').value = (o.getAttribute('min_value') == '?' ? '' : o.getAttribute('min_value'));
    Dom.get('max_qty').value = (o.getAttribute('max_value') == '?' ? '' : o.getAttribute('max_value'));
    Dom.get('quantity_limits_location_key').value = o.getAttribute('location_key');

    Dom.get('quantity_limits_part_sku').value = o.getAttribute('part_sku');

    Editor_limit_quantities.show();
    Dom.get('min_qty').focus();
}

function save_picking_quantity_limits() {

    var request = 'ar_edit_warehouse.php?tipo=update_save_picking_location_quantity_limits&newvalue_min=' + Dom.get('min_qty').value + '&newvalue_max=' + Dom.get('max_qty').value + '&location_key=' + Dom.get('quantity_limits_location_key').value + '&part_sku=' + Dom.get('quantity_limits_part_sku').value
    // alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {


                if (r.action == 'error') {
                    Dom.setStyle('dialog_qty_msg', 'display', '')
                    Dom.get('dialog_qty_msg_text').innerHTML = r.msg

                } else {

                    Editor_limit_quantities.hide();

                    if (r.action == 'updated') {
                        o = Dom.get('picking_limit_quantities_' + r.sku + '_' + r.location_key)
                        o.setAttribute('min_value', r.min_value)
                        o.setAttribute('max_value', r.max_value)
                        Dom.get('picking_limit_min_' + r.sku + '_' + r.location_key).innerHTML = r.min_value;
                        Dom.get('picking_limit_max_' + r.sku + '_' + r.location_key).innerHTML = r.max_value;


                    }
                }

                //window.location.reload();
            } else {
                alert(r.msg);
            }
        }
    });
}



function show_move_quantities(o) {

    if (!Dom.get('modify_stock').value) return;

    region1 = Dom.getRegion(o);
    region2 = Dom.getRegion('dialog_move_qty');

    var pos = [region1.right - region2.width, region1.bottom]

    Dom.setXY('dialog_move_qty', pos);

    Dom.get('move_qty_part').value = (o.getAttribute('move_qty') == '?' ? '' : o.getAttribute('move_qty'));
    Dom.get('move_qty_location_key').value = Dom.get(o).getAttribute('location_key');
    Dom.get('move_qty_part_sku').value = Dom.get(o).getAttribute('part_sku');

    dialog_move_qty.show();
    Dom.get('move_qty_part').focus();
}



function save_move_qty() {
    //alert(sku);
    //alert(Dom.get('part_location').value + ':'+Dom.get('part_sku').value);//return;
    //ar_edit_warehouse.php?tipo=edit_part_location&key=min&newvalue=4&oldvalue=null&location_key=&part_sku=7
    var request = 'ar_edit_warehouse.php?tipo=update_move_qty&move_qty=' + Dom.get('move_qty_part').value + '&location_key=' + Dom.get('move_qty_location_key').value + '&part_sku=' + Dom.get('move_qty_part_sku').value
    // alert(request);  
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //			alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                dialog_move_qty.hide();

                if (r.action = 'updated') {
                    o = Dom.get('store_limit_quantities_' + r.sku + '_' + r.location_key)
                    o.setAttribute('move_qty', r.move_qty)
                    Dom.get('store_limit_move_qty_' + r.sku + '_' + r.location_key).innerHTML = r.move_qty;


                }


                //window.location.reload();
            } else {
                alert(r.msg);
            }
        }
    });





}



function init() {

    dialog_locations = new YAHOO.widget.Dialog("dialog_locations", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_locations.render();



    dialog_move_qty = new YAHOO.widget.Dialog("dialog_move_qty", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_move_qty.render();


    audit_dialog = new YAHOO.widget.Dialog("Editor_audit", {
        visible: true,
        close: true,
        underlay: "none",
        draggable: false
    });
    audit_dialog.render();

    add_stock_dialog = new YAHOO.widget.Dialog("Editor_add_stock", {
        visible: true,
        close: true,
        underlay: "none",
        draggable: false
    });
    add_stock_dialog.render();


    Editor_lost_items = new YAHOO.widget.Dialog("Editor_lost_items", {
        close: true,
        visible: false,
        draggable: false
    });
    Editor_lost_items.render();
    Editor_move_items = new YAHOO.widget.Dialog("Editor_move_items", {
        close: true,
        visible: false,
        underlay: "none",
        draggable: false
    });
    Editor_move_items.render();


    Editor_add_location = new YAHOO.widget.Dialog("Editor_add_location", {
        close: true,
        visible: true,
        underlay: "none",
        draggable: false
    });
    Editor_add_location.render();

    Editor_limit_quantities = new YAHOO.widget.Dialog("Editor_limit_quantities", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    Editor_limit_quantities.render();

    // Editor_lost_items = new YAHOO.widget.Dialog("Editor_lost_items", {  visible : false,close:true,underlay: "none",draggable:false});
    // Editor_lost_items.render();
    // Editor_move_items = new YAHOO.widget.Dialog("Editor_move_items", {  visible : false,close:true,underlay: "none",draggable:false});
    // Editor_move_items.render();
}

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("location_move_from", function() {

    var oDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
    oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
    oDS.responseSchema = {
        resultsList: "data",
        fields: ["code", "key", "stock"]
    };
    var oAC = new YAHOO.widget.AutoComplete("location_move_from_input", "location_move_from_container", oDS);
    oAC.generateRequest = function(sQuery) {

        var sku = Dom.get("move_sku").value
        //  var location_key=Dom.get("this_location_key").value
        return "?tipo=find_location&except_location=" + location_key + "&get_data=sku" + sku + "&with=stock&query=" + sQuery;
    };
    oAC.forceSelection = true;
    oAC.itemSelectEvent.subscribe(location_move_to_selected);



});

YAHOO.util.Event.onContentReady("location_move_to", function() {
    var oDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
    oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
    oDS.responseSchema = {
        resultsList: "data",
        fields: ["code", "key", "stock"]
    };
    var oAC = new YAHOO.widget.AutoComplete("location_move_to_input", "location_move_to_container", oDS);
    oAC.generateRequest = function(sQuery) {

        var sku = Dom.get("move_sku").value
        var location_key = Dom.get("move_this_location_key").value
        return "?tipo=find_location&except_location=" + location_key + "&get_data=sku" + sku + "&query=" + sQuery;
    };
    oAC.forceSelection = true;
    oAC.itemSelectEvent.subscribe(location_move_to_selected);
});

YAHOO.util.Event.onContentReady("add_location_input", function() {



    var new_loc_oDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
    new_loc_oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
    new_loc_oDS.responseSchema = {
        resultsList: "data",
        fields: ["code", "key", "stock"]
    };
    var new_loc_oAC = new YAHOO.widget.AutoComplete("add_location_input", "add_location_container", new_loc_oDS);
    new_loc_oAC.maxResultsDisplayed = 5;

    new_loc_oAC.generateRequest = function(sQuery) {

        var sku = Dom.get("add_location_sku").value

        return "?tipo=find_location&except_part_location=1&get_data=sku" + sku + "&query=" + sQuery;
    };
    new_loc_oAC.forceSelection = true;
    new_loc_oAC.itemSelectEvent.subscribe(add_location_selected);

});


