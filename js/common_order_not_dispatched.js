var dialog_sending_to_warehouse;
var dialog_add_credit;
var dialog_edit_credits;
var dialog_edit_tax_category;

function save_use_calculated_shipping() {
    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=use_calculated_shipping&order_key=' + Dom.get('order_key').value;

    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                for (x in r.data) {

                    Dom.get(x).innerHTML = r.data[x];
                }
                Dom.get('order_shipping_method').innerHTML = r.order_shipping_method;
                Dom.get('shipping_amount').value = r.shipping_amount
            }
            reset_set_shipping()
        },
        failure: function(o) {
            alert('EC18'+o.statusText);

        },
        scope: this
    }, request

    );

}



function save_use_calculated_items_charges() {
    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=use_calculated_items_charges&order_key=' + Dom.get('order_key').value;

    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //	alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                for (x in r.data) {

                    Dom.get(x).innerHTML = r.data[x];
                }
                // Dom.get('order_items_charges_method').innerHTML=r.order_items_charges_method;
                Dom.get('items_charges_amount').value = r.items_charges_amount
            }
            reset_set_items_charges()
        },
        failure: function(o) {
           // alert(o.statusText);

        },
        scope: this
    }, request

    );

}

function save_set_shipping() {
    value = Dom.get("shipping_amount").value;
    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=set_order_shipping&value=' + value + '&order_key=' + Dom.get('order_key').value;
    Dom.setStyle('save_set_shipping_wait', 'display', '');
    Dom.setStyle('save_set_shipping', 'display', 'none');

    //return;
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.setStyle('save_set_shipping_wait', 'display', 'none');
                Dom.setStyle('save_set_shipping', 'display', '');
                for (x in r.data) {

                    Dom.get(x).innerHTML = r.data[x];
                }
                Dom.get('order_shipping_method').innerHTML = r.order_shipping_method;
                Dom.get('shipping_amount').value = r.shipping_amount
            }
            reset_set_shipping()
        },
        failure: function(o) {
           // alert(o.statusText);

        },
        scope: this
    }, request

    );

}

function reset_set_shipping() {
    dialog_edit_shipping.hide();
}


function save_set_items_charges() {
    value = Dom.get("items_charges_amount").value;
    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=set_order_items_charges&value=' + value + '&order_key=' + Dom.get('order_key').value;
    Dom.setStyle('save_set_items_charges_wait', 'display', '');
    Dom.setStyle('save_set_items_charges', 'display', 'none');


    //alert(request);return;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //	alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.setStyle('save_set_items_charges_wait', 'display', 'none');
                Dom.setStyle('save_set_items_charges', 'display', '');
                for (x in r.data) {
                    //alert(x+' '+r.data[x])
                    Dom.get(x).innerHTML = r.data[x];
                }
                //  Dom.get('order_items_charges_method').innerHTML=r.order_items_charges_method;
                Dom.get('items_charges_amount').value = r.items_charges_amount
            }
            reset_set_items_charges()
        },
        failure: function(o) {
            //alert(o.statusText);

        },
        scope: this
    }, request

    );

}

function reset_set_items_charges() {
    dialog_edit_items_charges.hide();
}


function close_edit_delivery_address_dialog() {
    edit_delivery_address.hide();
}

function change_delivery_address() {


    region1 = Dom.getRegion('control_panel');
    region2 = Dom.getRegion('edit_delivery_address_splinter_dialog');
    var pos = [region1.left, region1.top]
    Dom.setXY('edit_delivery_address_splinter_dialog', pos);

    edit_delivery_address.show();
}


function post_change_main_delivery_address() {}

function post_create_delivery_address_function(r) {

    hide_new_delivery_address();
    use_this_address_in_order(r.address_key,false)

    //alert(r.address_key)
    //hide_new_delivery_address();
    //  window.location.reload()
}



function use_this_address_in_order(address_key,hide_edit_delivery_address) {



    var ar_file = 'ar_edit_orders.php';
    request = 'tipo=update_ship_to_key_from_address&order_key=' + Dom.get('order_key').value + '&address_key=' + address_key;
//alert(request)
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                if (r.result == 'updated') {

                    Dom.get('delivery_address').innerHTML = r.new_value;
                    Dom.setStyle('tr_order_shipping', 'display', '');
                    Dom.setStyle('shipping_address', 'display', '');
                    Dom.setStyle('for_collection', 'display', 'none');
                }
                if(hide_edit_delivery_address)
                edit_delivery_address.hide()
            } else {
                alert('EC19'+r.msg);
                //	callback();
            }
        },
        failure: function(o) {
            //alert(o.statusText);
            // callback();
        },
        scope: this
    }, request

    );
}

function change_shipping_type() {


    new_value = this.getAttribute('value');
    var ar_file = 'ar_edit_orders.php';
    request = 'tipo=edit_new_order_shipping_type&id=' + Dom.get('order_key').value + '&key=collection&newvalue=' + new_value;
   // alert(request);
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                if (r.result == 'updated') {
                    if (r.new_value == 'Yes') {
                        Dom.setStyle('tr_order_shipping', 'display', 'none');
                        Dom.setStyle('shipping_address', 'display', 'none');
                        Dom.setStyle('for_collection', 'display', '');


                    } else {
                        Dom.setStyle('tr_order_shipping', 'display', '');
                        Dom.setStyle('shipping_address', 'display', '');
                        Dom.setStyle('for_collection', 'display', 'none');

                    }
                    
                     for (x in r.data) {

                    Dom.get(x).innerHTML = r.data[x];
                }
                Dom.get('order_shipping_method').innerHTML = r.order_shipping_method;
                Dom.get('shipping_amount').value = r.shipping_amount


                }


            } else {
                alert('EC20'+r.msg);
                //	callback();
            }
        },
        failure: function(o) {
            //alert(o.statusText);
            // callback();
        },
        scope: this
    }, request

    );


}






function validate_discount_percentage(o) {



    if (o.value == '') {
        percentage = 0;
    } else {
        percentage = parseFloat(o.value);

    }



    if (!isNaN(percentage) && percentage >= 0 && percentage <= 100) {
        Dom.removeClass('change_discount_save', 'disabled')

    } else {
        Dom.addClass('change_discount_save', 'disabled')

    }


}




function change_discount(o, transaction_key, record_key, value) {


    if (!transaction_key) return;

    region1 = Dom.getRegion(o);
    region2 = Dom.getRegion('change_staff_discount');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('change_staff_discount', pos);
    change_staff_discount.show()
    Dom.get('change_discount_transaction_key').value = transaction_key;
    Dom.get('change_discount_record_key').value = record_key;
    Dom.get('change_discount_value').value = value;

    Dom.get('change_discount_value').focus();
}

function cancel_change_discount() {

    Dom.get('change_discount_value').value = '';


    Dom.addClass('change_discount_save', 'disabled');
    change_staff_discount.hide();

}



function create_delivery_note_from_list(o,order_key) {
    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=send_to_warehouse&order_key=' + order_key;


if(Dom.hasClass( 'send_to_warehouse_button_'+order_key,'disabled')){
return;

}
   // Dom.addClass(['cancel', 'done', 'import_transactions_mals_e'], 'disabled');

   // Dom.setStyle('sending_to_warehouse_waiting', 'display', '')
   // Dom.setStyle('sending_to_warehouse_msg', 'display', 'none')

    //
    Dom.get('send_to_warehouse_img_'+order_key).src = 'art/loading.gif'


    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
          //  alert(o.responseText);
            //return;
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
            
            	Dom.get('dispatch_state_'+r.order_key).innerHTML=r.dispatch_state;
                 	Dom.get('operations'+r.order_key).innerHTML=r.operations;
       	
            get_store_pending_orders_numbers(Dom.get('from').value,Dom.get('to').value)
            
            } else {
                Dom.get('send_to_warehouse_img').src = 'art/icons/cart_go.png'

              
            }


        },
        failure: function(o) {
            alert('EC21'+o.statusText);

        },
        scope: this
    }, request

    );
  
}

function create_delivery_note() {
    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=send_to_warehouse&order_key=' + Dom.get('order_key').value;

    Dom.addClass(['cancel', 'done', 'import_transactions_mals_e'], 'disabled');

    Dom.setStyle('sending_to_warehouse_waiting', 'display', '')
    Dom.setStyle('sending_to_warehouse_msg', 'display', 'none')

    //
    Dom.get('send_to_warehouse_img').src = 'art/loading.gif'


    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //alert(o.responseText);
            //return;
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                window.location = "order.php?id=" + Dom.get('order_key').value;
                //	if(Dom.get('referral').value=='store_pending_orders'){
                //	window.location="customers_pending_orders.php?store="+Dom.get('store_key').value;
                //	}else{
                //	window.location="customer.php?id="+Dom.get('customer_key').value;
                //	}
            } else {
                Dom.get('send_to_warehouse_img').src = 'art/icons/cart_go.png'

                Dom.removeClass(['cancel', 'done', 'import_transactions_mals_e'], 'disabled');
                if(r.number_items==0){
                    Dom.addClass(['done'], 'disabled');

                }
                dialog_sending_to_warehouse.show();
                Dom.setStyle('sending_to_warehouse_waiting', 'display', 'none')
                Dom.setStyle('sending_to_warehouse_msg', 'display', '')
                Dom.get('sending_to_warehouse_msg').innerHTML = r.msg;
            }


        },
        failure: function(o) {
            alert(o.statusText);

        },
        scope: this
    }, request

    );


}


function save_change_discount() {
    if (!Dom.hasClass('change_discount_save', 'disabled')) {

        Dom.setStyle('change_staff_discount_waiting', 'display', '')
        Dom.setStyle('change_staff_discount_buttons', 'display', 'none')

        percentage = Dom.get('change_discount_value').value;

        if (percentage == '') percentage = 0;

        var ar_file = 'ar_edit_orders.php';
        var request = 'tipo=update_percentage_discount&order_transaction_key=' + Dom.get('change_discount_transaction_key').value + '&percentage=' + percentage + '&order_key=' + Dom.get('order_key').value;

        //alert(request);
        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {
                //	alert(o.responseText);



                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {

                    datatable = tables['table0'];


                    record = datatable.getRecord(Dom.get("change_discount_record_key").value);

                    for (x in r.data) {

                        Dom.get(x).innerHTML = r.data[x];
                    }
                    Dom.get('ordered_products_number').value = r.data['ordered_products_number'];
                    if (r.discounts) {
                        Dom.get('tr_order_items_gross').style.display = '';
                        Dom.get('tr_order_items_discounts').style.display = '';


                    } else {
                        Dom.get('tr_order_items_gross').style.display = 'none';
                        Dom.get('tr_order_items_discounts').style.display = 'none';

                    }

                    if (r.charges) {
                        Dom.get('tr_order_items_charges').style.display = '';

                    } else {
                        Dom.get('tr_order_items_charges').style.display = 'none';

                    }


                    datatable.updateCell(record, 'quantity', r.quantity);
                    datatable.updateCell(record, 'to_charge', r.to_charge);
                    datatable.updateCell(record, 'description', r.description);
                    datatable.updateCell(record, 'discount_percentage', r.discount_percentage);



                    change_staff_discount.hide();
                    Dom.setStyle('change_staff_discount_waiting', 'display', 'none')
                    Dom.setStyle('change_staff_discount_buttons', 'display', '')
                    //Dom.get('change_discount_value').value=value;




                }

            },
            failure: function(o) {
                alert('EC22'+o.statusText);

            },
            scope: this
        }, request

        );


    }
}

function show_dialog_edit_credits() {
    dialog_edit_credits.show();
}


function show_dialog_edit_items_charges() {
    dialog_edit_items_charges.show();
    Dom.get('items_charges_amount').focus();
}

function submit_edit_items_charges(e) {
    var key;
    if (window.event) key = window.event.keyCode;
    else key = e.which;
    if (key == 13) save_set_items_charges();
};

function submit_edit_shipping(e) {
    var key;
    if (window.event) key = window.event.keyCode;
    else key = e.which;
    if (key == 13) save_set_shipping();
};


function show_dialog_edit_shipping() {
    dialog_edit_shipping.show();
    Dom.get('shipping_amount').focus();
}


function save(tipo) {
    //alert(tipo)
    switch (tipo) {
    case ('cancel'):

        if (Dom.hasClass('cancel_save', 'disabled')) {
            return;
        }
        Dom.setStyle('cancel_buttons', 'display', 'none')
        Dom.setStyle('cancel_wait', 'display', '')
        var value = encodeURIComponent(Dom.get("cancel_input").value);
        var ar_file = 'ar_edit_orders.php';
        var request = 'tipo=cancel&note=' + value+'&order_key='+Dom.get('order_key').value;
        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {
                //alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {

                    window.location.reload();
                } else {
                    alert('EC23'+r.msg)
                    Dom.setStyle('cancel_buttons', 'display', '')
                    Dom.setStyle('cancel_wait', 'display', 'none')
                }
            },
            failure: function(o) {
                alert(o.statusText);

            },
            scope: this
        }, request

        );


        break;
    }

}

function open_cancel_dialog() {


    Dom.get("cancel_input").value = '';
    Dom.addClass('cancel_save', 'disabled')

    dialog_cancel.show();
    Dom.get('cancel_input').focus();
    
    
    
    
    
    
    
}








function clear_lookup_family(){
Dom.get('lookup_family_query').value='';
lookup_family()
}

function lookup_family(){
var table = tables['table1'];
    var datasource = tables['dataSource1'];
 var request = '&display=products&lookup_family='+Dom.get('lookup_family_query').value;

if(Dom.get('lookup_family_query').value!=''){
Dom.setStyle('clear_lookup_family','display','')

}else{
Dom.setStyle('clear_lookup_family','display','none')

}

// hide_filter('', 0)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}


function change_block(){

 Dom.removeClass(['products','items'], 'selected')
 Dom.addClass(this, 'selected')
 Dom.setStyle(['products_block','items_block'], 'display','none')
 Dom.setStyle(this.id+'_block', 'display','')

Dom.get('products_display_type').value=this.id;




}


function change_block_delete(){

 Dom.removeClass(['products','items'], 'selected')
 Dom.addClass(this, 'selected')
 Dom.setStyle(['table_title_items','table_title_products'], 'display','none')
 Dom.setStyle('table_title_'+this.id, 'display','')

 var table = tables['table0'];
    var datasource = tables['dataSource0'];
   

if(this.id=='items'){
 var request = '&display=items&lookup_family=';
 
  Dom.setStyle('products_lookups', 'display','none')
Dom.get('lookup_family_query').value='';
Dom.setStyle('clear_lookup_family','display','none')

}else{

 var request = '&display=products';
  Dom.setStyle('products_lookups', 'display','')

   
}
 //hide_filter('', 0)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}



function show_edit_button(e, data) {
    Dom.setStyle('edit_button_' + data.name, 'visibility', 'visible')

}

function hide_edit_button(e, data) {

    if (data.name == 'shipping' && Dom.get('order_shipping_method').value == 'On Demand') {
        return
    }

    Dom.setStyle('edit_button_' + data.name, 'visibility', 'hidden')

}

function show_dialog_import_transactions_mals_e() {
    Dom.get('transactions_mals_e').value = '';

    region1 = Dom.getRegion('import_transactions_mals_e');
    region2 = Dom.getRegion('dialog_import_transactions_mals_e');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_import_transactions_mals_e', pos);

    dialog_import_transactions_mals_e.show();


}

function save_import_transactions_mals_e() {

    var values = new Object();
    values['data'] = Dom.get('transactions_mals_e').value;

    jsonificated_values = my_encodeURIComponent(YAHOO.lang.JSON.stringify(values));


    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=import_transactions_mals_e&order_key=' + Dom.get('order_key').value + '&values=' + jsonificated_values;
    //alert('R:'+request);
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                window.location.reload();
            }
        },
        failure: function(o) {
            //alert(o.statusText);

        },
        scope: this
    }, request

    );

}

var myonCellClick = function(oArgs) {


        var target = oArgs.target,
            column = this.getColumn(target),
            record = this.getRecord(target);



        datatable = this;
        var records = this.getRecordSet();
        //alert(records.getLength())

        //return;

        var recordIndex = this.getRecordIndex(record);


        switch (column.action) {
        case ('add_object'):
        case ('remove_object'):
            var data = record.getData();

            if (column.action == 'add_object') {
                if (record.getData("add") != '+') return;

                var new_qty = parseFloat(data['quantity']) + 1;
                datatable.updateCell(record, 'add', '<img style="width:12px;position:relative;left:-2px;top:2px" src="art/loading.gif">');

            } else {
                if (record.getData("remove") != '-') return;

                datatable.updateCell(record, 'remove', '<img style="width:12px;position:relative;left:-2px;top:2px" src="art/loading.gif">');

                qty = parseFloat(data['quantity'])
                if (qty == 0) {
                    return;
                }
                var new_qty = qty - 1;

            }

            var ar_file = 'ar_edit_orders.php';
            request = 'tipo=edit_new_order&id=' + Dom.get('order_key').value + '&key=quantity&newvalue=' + new_qty + '&oldvalue=' + data['quantity'] + '&pid=' + data['pid'];
           // alert(request)
            YAHOO.util.Connect.asyncRequest('POST', ar_file, {
                success: function(o) {
                    //alert(o.responseText);
                    datatable.updateCell(record, 'remove', '-');
                    datatable.updateCell(record, 'add', '+');


                    var r = YAHOO.lang.JSON.parse(o.responseText);
                    if (r.state == 200) {
                        for (x in r.data) {

                            Dom.get(x).innerHTML = r.data[x];
                        }
                        Dom.get('ordered_products_number').value = r.data['ordered_products_number'];
                       	
                       	if(r.data['ordered_products_number']>0){
                       		Dom.removeClass('done','disabled')
                       	}else{
                         		Dom.addClass('done','disabled')
                     	
                       	}
                       
                       
                        if (r.discounts) {
                            Dom.get('tr_order_items_gross').style.display = '';
                            Dom.get('tr_order_items_discounts').style.display = '';

                        } else {
                            Dom.get('tr_order_items_gross').style.display = 'none';
                            Dom.get('tr_order_items_discounts').style.display = 'none';

                        }

/*
							if(r.charges){
						    Dom.get('tr_order_items_charges').style.display='';

						}else{
						    Dom.get('tr_order_items_charges').style.display='none';

						}
						*/

                        datatable.updateCell(record, 'quantity', r.quantity);
                        datatable.updateCell(record, 'to_charge', r.to_charge);



                        for (var i = 0; i < records.getLength(); i++) {
                            var rec = records.getRecord(i);
                            if (r.discount_data[rec.getData('pid')] != undefined) {
                                datatable.updateCell(rec, 'to_charge', r.discount_data[rec.getData('pid')].to_charge);
                                datatable.updateCell(rec, 'description', r.discount_data[rec.getData('pid')].description);
                            }
                        }

                        if (r.quantity == 0) {

                            datatable.updateCell(record, 'description', r.description);

                            if (Dom.get('products_display_type').value == 'ordered_products') {

                                this.deleteRow(target);
                            }
                        }



										if(Dom.get('products_display_type').value=='products'){
				
				var table = tables['table0'];
    var datasource = tables['dataSource0'];
   
				}else{
				var table = tables['table1'];
    var datasource = tables['dataSource1'];
  
				
				}
                    
var request ='';
				 datasource.sendRequest(request, table.onDataReturnInitializeTable, table);




                    } else {
                        alert('EC24'+r.msg);
                        //	callback();
                    }
                },
                failure: function(o) {
                    alert('EC25'+o.statusText);
                    // callback();
                },
                scope: this
            }, request

            );

            break;

        case ('edit_object'):

            change_discount(this.getCell(target), record.getData('otf_key'), record.getId(), record.getData('discount_percentage'))

            break;
        default:

            this.onEventShowCellEditor(oArgs);
            break;
        }
    };

function change(e, o, tipo) {
    switch (tipo) {
    case ('cancel'):


        if (o.value != '') {
            enable_save(tipo);

            if (window.event) key = window.event.keyCode; //IE
            else key = e.which; //firefox     
            if (key == 13) save(tipo);


        } else disable_save(tipo);
        break;
    }
};

function enable_save(tipo) {
    switch (tipo) {
    case ('cancel'):

        Dom.removeClass(tipo + '_save', 'disabled')

        break;
    }
};

function disable_save(tipo) {
    switch (tipo) {
    case ('cancel'):
        Dom.addClass(tipo + '_save', 'disabled')
        break;
    }
};


function close_dialog(tipo) {
    switch (tipo) {


    case ('cancel'):


        dialog_cancel.hide();

        break;
    }
};

var CellEdit = function(callback, newValue) {



        var record = this.getRecord(),
            column = this.getColumn(),
            oldValue = this.value,
            datatable = this.getDataTable();
        var records = datatable.getRecordSet();
        var ar_file = 'ar_edit_orders.php';

        var request = 'tipo=edit_' + column.object + '&id=' + Dom.get('order_key').value + '&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue) + myBuildUrl(datatable, record);
        //alert('R:'+request);
        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {
                //   alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {


                        for (x in r.data) {

                            Dom.get(x).innerHTML = r.data[x];
                        }
                        Dom.get('ordered_products_number').value = r.data['ordered_products_number'];
                       	
                       	if(r.data['ordered_products_number']>0){
                       		Dom.removeClass('done','disabled')
                       	}else{
                         		Dom.addClass('done','disabled')
                     	
                       	}
                       
                       
                        if (r.discounts) {
                            Dom.get('tr_order_items_gross').style.display = '';
                            Dom.get('tr_order_items_discounts').style.display = '';

                        } else {
                            Dom.get('tr_order_items_gross').style.display = 'none';
                            Dom.get('tr_order_items_discounts').style.display = 'none';

                        }

/*
							if(r.charges){
						    Dom.get('tr_order_items_charges').style.display='';

						}else{
						    Dom.get('tr_order_items_charges').style.display='none';

						}
						*/

                        datatable.updateCell(record, 'quantity', r.quantity);
                        datatable.updateCell(record, 'to_charge', r.to_charge);



                        for (var i = 0; i < records.getLength(); i++) {
                            var rec = records.getRecord(i);
                            if (r.discount_data[rec.getData('pid')] != undefined) {
                                datatable.updateCell(rec, 'to_charge', r.discount_data[rec.getData('pid')].to_charge);
                                datatable.updateCell(rec, 'description', r.discount_data[rec.getData('pid')].description);
                            }
                        }

                        if (r.quantity == 0) {

                            datatable.updateCell(record, 'description', r.description);

                            if (Dom.get('products_display_type').value == 'ordered_products') {

                                this.deleteRow(target);
                            }
                        }

				if(Dom.get('products_display_type').value=='products'){
				
				var table = tables['table0'];
    var datasource = tables['dataSource0'];
   
				}else{
				var table = tables['table1'];
    var datasource = tables['dataSource1'];
  
				
				}
                    
var request ='';
				 datasource.sendRequest(request, table.onDataReturnInitializeTable, table);








                    callback(true, r.quantity);


                } else {
                    alert('EC26'+r.msg);
                    callback();
                }
            },
            failure: function(o) {
                //alert(o.statusText);
                callback();
            },
            scope: this
        }, request

        );
    };


var mygetTerms = function(query) {




        if (this.table_id == undefined) var table_id = 0;
        else var table_id = this.table_id;

        var Dom = YAHOO.util.Dom;
        var table = tables['table' + table_id];
        var datasource = tables['dataSource' + table_id];

        table.filter.value = Dom.get('f_input' + table_id).value;
        var request = '&tableid=' + table_id + '&sf=0&f_field=' + table.filter.key + '&f_value=' + table.filter.value + '&display=' + Dom.get('products_display_type').value;

        datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    };


function init_common_order_not_dispatched() {
    Event.addListener(['items','products'], "click", change_block);

    Event.addListener("tr_order_shipping", "mouseover", show_edit_button, {
        'name': 'shipping'
    });
    Event.addListener("tr_order_shipping", "mouseout", hide_edit_button, {
        'name': 'shipping'
    });
    Event.addListener("tr_order_items_charges", "mouseover", show_edit_button, {
        'name': 'items_charges'
    });
    Event.addListener("tr_order_items_charges", "mouseout", hide_edit_button, {
        'name': 'items_charges'
    });
    Event.addListener("tr_order_tax", "mouseover", show_edit_button, {
        'name': 'tax'
    });
    Event.addListener("tr_order_tax", "mouseout", hide_edit_button, {
        'name': 'tax'
    });
    Event.addListener("tr_order_credits", "mouseover", show_edit_button, {
        'name': 'credits'
    });
    Event.addListener("tr_order_credits", "mouseout", hide_edit_button, {
        'name': 'credits'
    });

    Event.addListener("use_calculate_shipping", "click", save_use_calculated_shipping);
    Event.addListener("use_calculate_items_charges", "click", save_use_calculated_items_charges);

    edit_delivery_address = new YAHOO.widget.Dialog("edit_delivery_address_splinter_dialog", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false

    });
    edit_delivery_address.render();
    YAHOO.util.Event.addListener("change_delivery_address", "click", change_delivery_address);

    change_staff_discount = new YAHOO.widget.Dialog("change_staff_discount", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    change_staff_discount.render();

    YAHOO.util.Event.addListener(["set_for_collection", "set_for_shipping"], "click", change_shipping_type);
    dialog_cancel = new YAHOO.widget.Dialog("dialog_cancel", {
       
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_cancel.render();
    
    if(Dom.get('cancel')!=undefined)
    YAHOO.util.Event.addListener("cancel", "click", open_cancel_dialog);


    dialog_edit_credits = new YAHOO.widget.Dialog("dialog_edit_credits", {
        context: ["edit_button_credits", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_edit_credits.render();
    Event.addListener("edit_button_credits", "click", show_dialog_edit_credits);



    dialog_edit_shipping = new YAHOO.widget.Dialog("dialog_edit_shipping", {
        context: ["edit_button_shipping", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_edit_shipping.render();
    Event.addListener("edit_button_shipping", "click", show_dialog_edit_shipping);
    Event.addListener("save_set_shipping", "click", save_set_shipping);
    Event.addListener("reset_set_shipping", "click", reset_set_shipping);
    Event.addListener("shipping_amount", "keydown", submit_edit_shipping);

    dialog_edit_items_charges = new YAHOO.widget.Dialog("dialog_edit_items_charges", {
        context: ["edit_button_items_charges", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_edit_items_charges.render();
    Event.addListener("edit_button_items_charges", "click", show_dialog_edit_items_charges);
    Event.addListener("save_set_items_charges", "click", save_set_items_charges);
    Event.addListener("reset_set_items_charges", "click", reset_set_items_charges);
    Event.addListener("items_charges_amount", "keydown", submit_edit_items_charges);

    dialog_sending_to_warehouse = new YAHOO.widget.Dialog("dialog_sending_to_warehouse", {
        context: ["done", "tr", "tr"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_sending_to_warehouse.render();

    Event.addListener("change_discount_save", "click", save_change_discount);
    Event.addListener("change_discount_cancel", "click", cancel_change_discount);

    dialog_import_transactions_mals_e = new YAHOO.widget.Dialog("dialog_import_transactions_mals_e", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_import_transactions_mals_e.render();
    Event.addListener("import_transactions_mals_e", "click", show_dialog_import_transactions_mals_e, true);

    Event.addListener("save_import_transactions_mals_e", "click", save_import_transactions_mals_e, true);

    dialog_add_credit = new YAHOO.widget.Dialog("dialog_add_credit", {
        context: ["add_credit", "tr", "tr"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_add_credit.render();
    Event.addListener("add_credit", "click", show_dialog_add_credit);

    Event.addListener("save_add_credit", "click", save_add_credit);

    Event.addListener("save_edit_credit", "click", save_edit_credit);
    Event.addListener("remove_credit", "click", remove_credit);


    dialog_edit_tax_category = new YAHOO.widget.Dialog("dialog_edit_tax_category", {
        context: ["edit_button_tax", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_edit_tax_category.render();
    YAHOO.util.Event.addListener("edit_button_tax", "click", show_dialog_edit_tax_category);

}

function show_dialog_edit_tax_category() {
    dialog_edit_tax_category.show()
}

function change_tax_category(o) {
    tax_category_code = value = o.getAttribute('tax_category')

    if (tax_category_code == Dom.get('original_tax_code').value) {
        dialog_edit_tax_category.hide()
        return
    }


    var ar_file = 'ar_edit_orders.php';
    request = 'tipo=edit_tax_category_order&order_key=' + Dom.get('order_key').value + '&tax_code=' + tax_category_code;
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                location.reload();

            } else {
                alert('EC27'+r.msg);
                //	callback();
            }
        },
        failure: function(o) {
            alert('EC28'+o.statusText);
            // callback();
        },
        scope: this
    }, request

    );


}


function remove_credit() {
    var ar_file = 'ar_edit_orders.php';
    request = 'tipo=remove_credit_from_order&order_key=' + Dom.get('order_key').value + '&transaction_key=' + Dom.get('credit_transaction_key').value;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                location.reload();

            } else {
                alert(r.msg);
                //	callback();
            }
        },
        failure: function(o) {
            //alert(o.statusText);
            // callback();
        },
        scope: this
    }, request

    );


}

function save_edit_credit() {

    credit = Dom.get('edit_credit_amount').value;
    description = Dom.get('edit_credit_description').value


    var ar_file = 'ar_edit_orders.php';
    request = 'tipo=edit_credit_to_order&order_key=' + Dom.get('order_key').value + '&transaction_key=' + Dom.get('credit_transaction_key').value + '&amount=' + credit + '&description=' + description + '&tax_code=' + Dom.get('edit_credit_tax_category').value;
    //alert(request);return;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                location.reload();

            } else {
                alert('EC29'+r.msg);
                //	callback();
            }
        },
        failure: function(o) {
            alert('EC30'+o.statusText);
            // callback();
        },
        scope: this
    }, request

    );


}




function close_dialog_edit_credit() {
    dialog_edit_credits.hide();
}

function change_tax_category_add_credit(o) {

    Dom.removeClass(Dom.getElementsByClassName('item', 'button', 'add_credit_tax_categories_options'), 'selected')
    Dom.addClass(o, 'selected')
    Dom.get('add_credit_tax_category').value = o.getAttribute('tax_category')
}

function change_tax_category_edit_credit(o) {

    Dom.removeClass(Dom.getElementsByClassName('item', 'button', 'edit_credit_tax_categories_options'), 'selected')
    Dom.addClass(o, 'selected')
    Dom.get('edit_credit_tax_category').value = o.getAttribute('tax_category')
}

function save_add_credit() {

    credit = Dom.get('add_credit_amount').value;
    description = Dom.get('add_credit_description').value


    var ar_file = 'ar_edit_orders.php';
    request = 'tipo=add_credit_to_order&order_key=' + Dom.get('order_key').value + '&amount=' + credit + '&description=' + description + '&tax_code=' + Dom.get('add_credit_tax_category').value;
    //alert(request);return;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                location.reload();

            } else {
                alert('EC31'+r.msg);
                //	callback();
            }
        },
        failure: function(o) {
            //alert(o.statusText);
            // callback();
        },
        scope: this
    }, request

    );


}

function close_dialog_add_credit() {
    dialog_add_credit.hide()
}

function show_dialog_add_credit() {
    Dom.get('add_credit_amount').value = '';
    Dom.get('add_credit_description').value = '';
    dialog_add_credit.show()
    Dom.get('add_credit_amount').focus();

}

YAHOO.util.Event.onDOMReady(init_common_order_not_dispatched);
