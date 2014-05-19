function validate_parcels_weight(query) {

    validate_general('delivery_note', 'parcels_weight', unescape(query));
}

function validate_number_parcels(query) {
    validate_general('delivery_note', 'number_parcels', unescape(query));

}

function validate_consignment_number(query) {
    validate_general('delivery_note', 'consignment_number', unescape(query));
}


function save_delivery_note() {
    save_edit_general('delivery_note');
}

function reset_delivery_note() {



    Dom.removeClass(Dom.getElementsByClassName('parcel_type', 'button', 'parcel_type_options'), 'selected')
    Dom.addClass('parcel_' + Dom.get('parcel_type').getAttribute('ovalue'), 'selected')


    Dom.removeClass(Dom.getElementsByClassName('option', 'button', 'shipper_code_options'), 'selected')
    Dom.addClass('shipper_code_' + Dom.get('shipper_code').getAttribute('ovalue'), 'selected')


    reset_edit_general('delivery_note');

}


function change_parcel_type(o) {
    value = o.getAttribute('valor')
    Dom.removeClass(Dom.getElementsByClassName('parcel_type', 'button', 'parcel_type_options'), 'selected')
    Dom.addClass('parcel_' + value, 'selected')


    ovalue = Dom.get('parcel_type').getAttribute('ovalue');

    validate_scope_data['delivery_note']['parcel_type']['value'] = value;
    Dom.get('parcel_type').value = value

    if (ovalue != value) {
        validate_scope_data['delivery_note']['parcel_type']['changed'] = true;
    } else {
        validate_scope_data['delivery_note']['parcel_type']['changed'] = false;
    }
    validate_scope('delivery_note')

}

function change_shipper(value) {
    Dom.removeClass(Dom.getElementsByClassName('option', 'button', 'shipper_code_options'), 'selected')
    Dom.addClass('shipper_code_' + value, 'selected')


    ovalue = Dom.get('shipper_code').getAttribute('ovalue');

    validate_scope_data['delivery_note']['shipper_code']['value'] = value;
    Dom.get('shipper_code').value = value

    if (ovalue != value) {
        validate_scope_data['delivery_note']['shipper_code']['changed'] = true;
    } else {
        validate_scope_data['delivery_note']['shipper_code']['changed'] = false;
    }
    validate_scope('delivery_note')

}

function post_save_actions(r) {



}

function show_dialog_set_dn_data(){

	region1 = Dom.getRegion('control_panel'); 
    region2 = Dom.getRegion('dialog_set_dn_data'); 

	var pos =[region1.right-region2.width,region1.top]
	Dom.setXY('dialog_set_dn_data', pos);
		

	dialog_set_dn_data.show()


}


function init_common_edit_delivery_note() {





    validate_scope_data = {

        'delivery_note': {

        
            'parcels_weight': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'parcels_weight',
                'ar': false,
                'validation': [{
                    'numeric': 'positive',
                    'invalid_msg': Dom.get('label_invalid_number').value
                }]
            },
            'number_parcels': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'number_parcels',
                'ar': false,
                'validation': [{
                    'numeric': 'positive',
                    'invalid_msg': Dom.get('label_invalid_number').value
                }]
            },
            'parcel_type': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'parcel_type',
                'ar': false,
                'validation': false
            },
            'shipper_code': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'shipper_code',
                'ar': false,
                'validation': false
            },
            'consignment_number': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'consignment_number',
                'ar': false,
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('label_invalid_number').value
                }]
            }

        },








    };
    validate_scope_metadata = {
        'delivery_note': {
            'type': 'edit',
            'ar_file': 'ar_edit_orders.php',
            'key_name': 'dn_key',
            'key': Dom.get('dn_key').value
        },


    };

    var parcels_weight_oACDS = new YAHOO.util.FunctionDataSource(validate_parcels_weight);
    parcels_weight_oACDS.queryMatchContains = true;
    var parcels_weight_oAutoComp = new YAHOO.widget.AutoComplete("parcels_weight", "parcels_weight_Container", parcels_weight_oACDS);
    parcels_weight_oAutoComp.minQueryLength = 0;
    parcels_weight_oAutoComp.queryDelay = 0.1;



    var parcels_oACDS = new YAHOO.util.FunctionDataSource(validate_number_parcels);
    parcels_oACDS.queryMatchContains = true;
    var parcels_oAutoComp = new YAHOO.widget.AutoComplete("number_parcels", "number_parcels_Container", parcels_oACDS);
    parcels_oAutoComp.minQueryLength = 0;
    parcels_oAutoComp.queryDelay = 0.1;

    var consignment_number_oACDS = new YAHOO.util.FunctionDataSource(validate_consignment_number);
    consignment_number_oACDS.queryMatchContains = true;
    var consignment_number_oAutoComp = new YAHOO.widget.AutoComplete("consignment_number", "consignment_number_Container", consignment_number_oACDS);
    consignment_number_oAutoComp.minQueryLength = 0;
    consignment_number_oAutoComp.queryDelay = 0.1;


    Event.addListener('save_edit_delivery_note', "click", save_delivery_note);
    Event.addListener('reset_edit_delivery_note', "click", reset_delivery_note);


    dialog_set_dn_data = new YAHOO.widget.Dialog("dialog_set_dn_data", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_set_dn_data.render();
    
       Event.addListener('edit_parcels', "click", show_dialog_set_dn_data);
       Event.addListener('edit_weight', "click", show_dialog_set_dn_data);
       Event.addListener('edit_consignment', "click", show_dialog_set_dn_data);




    
}

YAHOO.util.Event.onDOMReady(init_common_edit_delivery_note);
