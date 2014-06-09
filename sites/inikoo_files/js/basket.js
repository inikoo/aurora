var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

var dialog_confirm_cancel;
var dialog_set_tax;
var dialog_check_tax_number;

function close_edit_delivery_address_dialog() {
    edit_delivery_address.hide();
}

function close_edit_billing_address_dialog() {
    edit_billing_address.hide();
}

function change_delivery_address() {


    region1 = Dom.getRegion('control_panel');
    region2 = Dom.getRegion('edit_delivery_address_splinter_dialog');
    var pos = [region1.left, region1.top]
    Dom.setXY('edit_delivery_address_splinter_dialog', pos);

    Dom.setStyle('close_edit_delivery_address_dialog', 'display', '')

    edit_delivery_address.show();
}

function change_billing_address() {

    region1 = Dom.getRegion('control_panel');
    region2 = Dom.getRegion('edit_billing_address_splinter_dialog');
    var pos = [region1.left, region1.top]
    Dom.setXY('edit_billing_address_splinter_dialog', pos);
    Dom.setStyle('close_edit_billing_address_dialog', 'display', '')

    edit_billing_address.show();

}


function post_change_main_delivery_address() {}

function post_create_delivery_address_function(r) {

    hide_new_delivery_address();
    if (r.address_key) use_this_delivery_address_in_order(r.address_key, false)

}

function post_create_billing_address_function(r) {

    hide_new_billing_address();
    if (r.address_key) use_this_billing_address_in_order(r.address_key, false)

}

function use_this_delivery_address_in_order(address_key, hide_edit_delivery_address) {

    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=update_ship_to_key_from_address&order_key=' + Dom.get('order_key').value + '&address_key=' + address_key;
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //   alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                Dom.get('delivery_address').innerHTML = r.ship_to;
                Dom.setStyle('tr_order_shipping', 'display', '');
                Dom.setStyle('shipping_address', 'display', '');
                Dom.setStyle('for_collection', 'display', 'none');

                for (x in r.data) {
                    if (Dom.get(x) != undefined) Dom.get(x).innerHTML = r.data[x];
                }

                Dom.get('tax_info').innerHTML = r.tax_info


                if (hide_edit_delivery_address) {
                    edit_delivery_address.hide()
                }
            } else {
                alert('EC19' + r.msg);
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

function use_this_billing_address_in_order(address_key, hide_edit_billing_address) {

    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=update_billing_to_key_from_address&order_key=' + Dom.get('order_key').value + '&address_key=' + address_key;
    //alert(ar_file+'?'+request)
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {




                Dom.get('billing_address').innerHTML = r.billing_to;

                for (x in r.data) {
                    if (Dom.get(x) != undefined) Dom.get(x).innerHTML = r.data[x];
                }

                Dom.get('tax_info').innerHTML = r.tax_info



                if (hide_edit_billing_address) {
                    edit_billing_address.hide()
                }
            } else {
                alert('EC19' + r.msg);
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

function change_shipping_type(new_value) {

    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=edit_new_order_shipping_type&id=' + Dom.get('order_key').value + '&key=collection&newvalue=' + new_value;
    // alert(request);
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                if (r.result == 'updated') {

                    if (r.new_value == 'Yes') {
                        Dom.setStyle('tr_order_shipping', 'display', 'none');
                        Dom.setStyle(['shipping_address', 'title_delivery_address'], 'display', 'none');
                        Dom.setStyle(['for_collection', 'title_for_collection'], 'display', '');
                    } else {
                        Dom.setStyle('tr_order_shipping', 'display', '');
                        Dom.setStyle(['shipping_address', 'title_delivery_address'], 'display', '');
                        Dom.setStyle(['for_collection', 'title_for_collection'], 'display', 'none');

                    }

                    for (x in r.data) {
                        if (Dom.get(x) != undefined) Dom.get(x).innerHTML = r.data[x];
                    }

                    Dom.get('tax_info').innerHTML = r.tax_info


                    Dom.get('shipping_amount').value = r.shipping_amount
                    Dom.get('delivery_address').innerHTML = r.ship_to;

                }


            } else {
                alert('EC20' + r.msg);
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

function change_item_quantity(oArgs) {


    var target = oArgs.target,
        column = this.getColumn(target),
        record = this.getRecord(target);



    datatable = this;
    var records = this.getRecordSet();


    var recordIndex = this.getRecordIndex(record);


    switch (column.action) {


    default:

        this.onEventShowCellEditor(oArgs);
        break;
    }
};

function CellEdit(callback, newValue) {



    var record = this.getRecord(),
        column = this.getColumn(),
        oldValue = this.value,
        datatable = this.getDataTable();
    var records = datatable.getRecordSet();
    var ar_file = 'ar_edit_orders.php';

    var request = 'tipo=edit_' + column.object + '&id=' + Dom.get('order_key').value + '&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue) + myBuildUrl(datatable, record);
    // alert(ar_file+'?'+request);
    //return;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
         //   alert('c'+o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {


                for (x in r.data) {
                    if (Dom.get(x) != undefined) Dom.get(x).innerHTML = r.data[x];
                }
                Dom.get('ordered_products_number').value = r.data['ordered_products_number'];

                if (r.data['ordered_products_number'] > 0) {
                    Dom.removeClass('done', 'disabled')
                } else {
                    Dom.addClass('done', 'disabled')

                }
                if(Dom.get('charges_deal_info_span')!=undefined)
				Dom.get('charges_deal_info_span').innerHTML=r.charges_deal_info;
			
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

/*
                            // problem if we delete row, we have to change items number so beter jus reloaf table
                            if (Dom.get('products_display_type').value == 'ordered_products') {

                                datatable.deleteRow(record);
                            }
                            */

                    var table = tables['table' + Dom.get('items_table_index').value];
                    var datasource = tables['dataSource' + Dom.get('items_table_index').value];
                    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


                }
                if (Dom.get('products_display_type').value == 'products') {

                    var table = tables['table1'];
                    var datasource = tables['dataSource1'];

                } else {
                    var table = tables['table0'];
                    var datasource = tables['dataSource0'];


                }

                var request = '';
                // datasource.sendRequest(request, table.onDataReturnInitializeTable, table);






                callback(true, r.quantity);


            } else {
                alert('EC26' + r.msg);
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


Event.addListener(window, "load", function() {
    tables = new function() {

        var tableid = 0; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;
        var OrdersColumnDefs = [

            {
            key: "pid",
            label: "Product ID",
            width: 20,
            sortable: false,
            isPrimaryKey: true,
            hidden: true
        }

            , {
            key: "code",
            label: Dom.get('label_code').value,
            width: 70,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "description",
            label: Dom.get('label_description').value,
            width: 500,
            sortable: false,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }


                , {
            key: "price_per_outer",
            label: Dom.get('label_price_per_outer').value,
            width: 100,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }


                    , {
            key: "quantity",
            label: Dom.get('label_quantity').value,
            width: 70,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            editor: new YAHOO.widget.TextboxCellEditor({
                asyncSubmitter: CellEdit
            }),
            object: 'new_order'
        }
            //  ,{key:"gross",label:Dom.get('label_gross').value,  width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
                            // ,{key:"discount",label:Dom.get('label_discount').value,  width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
                            ,
        {
            key: "to_charge",
            label: Dom.get('label_net').value,
            width: 85,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }
        ];

        var request = "ar_orders.php?tipo=transactions&parent=order_in_process_by_customer&parent_key=" + Dom.get('order_key').value + "&tableid=0"
        // alert(request)
        this.dataSource0 = new YAHOO.util.DataSource(request);
        this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource0.connXhrMode = "queueRequests";
        this.dataSource0.responseSchema = {
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
                "code"
                , "description"
                , "quantity"
                , "discount"
                , "to_charge", "gross", "tariff_code", "created", "last_updated", "pid", "price_per_outer"
                // "promotion_id",
                                        ]
        };

        this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs, this.dataSource0, {
            draggableColumns: true,
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: 500,
                containers: 'paginator0',
                pageReportTemplate: '(Page {currentPage} of {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
            })

            ,
            sortedBy: {
                key: "code",
                dir: ""
            },
            dynamicData: true

        });
        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

        this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
        this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
        this.table0.subscribe("cellClickEvent", change_item_quantity);

        this.table0.request = request;
        this.table0.table_id = tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);


        this.table0.filter = {
            key: '',
            value: ''
        };





    };
});

function cancel_order() {

    Dom.setStyle('wait_cancel', 'display', '')
    Dom.setStyle(['cancel_order', 'close_cancel_order_dialog'], 'display', 'none')


    Event.removeListener('cancel_order', "mouseout", hide_cancel_order_info);
    Dom.get('cancel_order_img').src = 'art/loading.gif';
    var value = encodeURIComponent('Cancelled by customer');
    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=cancel&note=' + value + '&order_key=' + Dom.get('order_key').value;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
                   alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                location.href = 'basket.php?cancelled=1';

            } else {
                alert('EC23' + r.msg)
                Dom.setStyle('wait_cancel', 'display', 'none')
                Dom.setStyle(['cancel_order', 'close_cancel_order_dialog'], 'display', '')
            }
        },
        failure: function(o) {
            alert(o.statusText);

        },
        scope: this
    }, request

    );


}

function show_cancel_order_info() {
    Dom.setStyle('cancel_order_info', 'display', '')
}

function hide_cancel_order_info() {
    Dom.setStyle('cancel_order_info', 'display', 'none')

}


function show_edit_button(e, data) {

}

function hide_edit_button(e, data) {


}

function get_tax_info() {

    var ar_file = 'ar_orders.php';
    var request = 'tipo=get_tax_info&order_key=' + Dom.get('order_key').value;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //  alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {



                Dom.get('tax_info').innerHTML = r.tax_info
                for (x in r.data) {
                    if (Dom.get(x) != undefined) Dom.get(x).innerHTML = r.data[x];
                }


            } else {
                alert(r.state)
            }
        },
        failure: function(o) {


        },
        scope: this
    }, request

    );


}


function post_item_updated_actions(branch, r) {

    if (r.key == 'tax_number') {
        dialog_set_tax.hide();
        show_dialog_check_tax_number(r.newvalue);
        get_tax_info();


    }

}


function back_to_shop() {
    location.href = "page.php?id=" + Dom.get('last_basket_page_key').value

}

function validate_customer_tax_number(query) {
    validate_general('customer_quick', 'tax_number', unescape(query));
}


function close_quick_edit_tax_number() {
    dialog_set_tax.hide();
}

function save_quick_edit_tax_number() {
    save_edit_general_bulk('customer_quick');
}

function show_set_tax_number_dialog() {

    region1 = Dom.getRegion('set_tax_number');
    region2 = Dom.getRegion('dialog_set_tax');
    var pos = [region1.right - region2.width, region1.top]
    Dom.setXY('dialog_set_tax', pos);
    dialog_set_tax.show();

}

function show_cancel_order_dialog() {

    region1 = Dom.getRegion('show_cancel_order_dialog');
    region2 = Dom.getRegion('dialog_confirm_cancel');
    var pos = [region1.right - region2.width - 5, region1.top]


    Dom.setXY('dialog_confirm_cancel', pos);


    dialog_confirm_cancel.show();

}



function close_cancel_order_dialog() {
    dialog_confirm_cancel.hide()
}

function special_intructions_changed() {
    Dom.setStyle('special_instructions_saved', 'display', 'none');

}

function save_special_intructions() {
    Dom.setStyle('special_instructions_wait', 'display', '');
    Dom.setStyle('special_instructions_saved', 'display', 'none');
    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=update_order_special_intructions&order_key=' + Dom.get('order_key').value + '&value=' + Dom.get('special_instructions').value;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //    alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                Dom.setStyle('special_instructions_wait', 'display', 'none');
                Dom.setStyle('special_instructions_saved', 'display', '');
                Dom.get('special_instructions').value = r.value
            } else {
                //  alert(r.state)
            }
        },
        failure: function(o) {


        },
        scope: this
    }, request

    );


}

function add_insurance(o) {

    var insurance_key = o.getAttribute('insurance_key')

    Dom.setStyle(['insurance_checked_' + insurance_key, 'insurance_unchecked_' + insurance_key], 'display', 'none')
    Dom.setStyle('insurance_wait_' + insurance_key, 'display', '')

    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=add_insurance&insurance_key=' + insurance_key + '&order_key=' + Dom.get('order_key').value;
    //alert(ar_file+'?'+request)  
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //  alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                for (x in r.data) {
                    if (Dom.get(x) != undefined) Dom.get(x).innerHTML = r.data[x];
                }

                Dom.get('tax_info').innerHTML = r.tax_info
                if (r.order_insurance_amount == 0) {
                    Dom.setStyle('tr_order_insurance', 'display', 'none')
                } else {
                    Dom.setStyle('tr_order_insurance', 'display', '')

                }

                if (r.onptf_key) {
                    Dom.setStyle('insurance_wait_' + insurance_key, 'display', 'none')

                    Dom.setStyle('insurance_checked_' + insurance_key, 'display', '')
                    Dom.get('insurance_checked_' + insurance_key).setAttribute('onptf_key', r.onptf_key)
                } else {

                }

            } else {
                Dom.setStyle('insurance_wait_' + insurance_key, 'display', 'none')

                Dom.setStyle('insurance_unchecked_' + insurance_key, 'display', '')

            }
        },
        failure: function(o) {


        },
        scope: this
    }, request

    );


}

function remove_insurance(o) {
    var onptf_key = o.getAttribute('onptf_key')
    var insurance_key = o.getAttribute('insurance_key')

    Dom.setStyle(['insurance_checked_' + insurance_key, 'insurance_unchecked_' + insurance_key], 'display', 'none')
    Dom.setStyle('insurance_wait_' + insurance_key, 'display', '')

    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=remove_insurance&onptf_key=' + onptf_key + '&order_key=' + Dom.get('order_key').value;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //   alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {


                for (x in r.data) {
                    if (Dom.get(x) != undefined) Dom.get(x).innerHTML = r.data[x];
                }

                Dom.get('tax_info').innerHTML = r.tax_info
                if (r.order_insurance_amount == 0) {
                    Dom.setStyle('tr_order_insurance', 'display', 'none')
                } else {
                    Dom.setStyle('tr_order_insurance', 'display', '')

                }

                Dom.setStyle('insurance_wait_' + insurance_key, 'display', 'none')

                Dom.setStyle('insurance_unchecked_' + insurance_key, 'display', '')


            } else {

            }
        },
        failure: function(o) {


        },
        scope: this
    }, request

    );


}

function show_dialog_check_tax_number(tax_number) {

    region1 = Dom.getRegion('check_tax_number');
    region2 = Dom.getRegion('dialog_check_tax_number');
    var pos = [region1.right - region2.width, region1.top]
    Dom.setXY('dialog_check_tax_number', pos);


    Dom.get('tax_number_to_check').innerHTML = tax_number


    Dom.get('check_tax_number_result').innerHTML = '';
    Dom.setStyle('check_tax_number_result_tr', 'display', 'none');
    Dom.setStyle('check_tax_number_buttons', 'display', 'none');
    Dom.setStyle('check_tax_number_wait', 'display', '');


    if (Dom.get('save_tax_details_not_match') != undefined) Dom.setStyle('save_tax_details_not_match', 'display', 'none')
    if (Dom.get('save_tax_details_match') != undefined) Dom.setStyle('save_tax_details_match', 'display', 'none')

    Dom.setStyle('close_check_tax_number', 'display', 'none')
    Dom.setStyle('check_tax_number_name_tr', 'display', 'none')
    Dom.setStyle('check_tax_number_address_tr', 'display', 'none')


    dialog_check_tax_number.show()

    Dom.get('check_tax_number_result').innerHTML = '';

    var request = 'ar_edit_contacts.php?tipo=check_tax_number&customer_key=' + Dom.get('customer_key').value



    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            Dom.setStyle(['submit_register', 'cancel_register'], 'visibility', 'visible');

            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            Dom.get('check_tax_number_result').innerHTML = r.msg;
            Dom.setStyle('check_tax_number_result_tr', 'display', '');
            Dom.setStyle('check_tax_number_buttons', 'display', '');
            Dom.setStyle('check_tax_number_wait', 'display', 'none');
            if (r.state == '200') {
                if (Dom.get('customer_tax_number_valid') != undefined) Dom.get('customer_tax_number_valid').innerHTML = r.tax_number_valid
                if (r.result.valid) {
                    Dom.get('check_tax_number').src = 'art/icons/taxation_green.png';


                } else {
                    Dom.get('check_tax_number').src = 'art/icons/taxation_error.png';

                }



                if ((r.result.name != undefined || r.result.address != undefined) && r.result.valid) {

                    if (r.result.name != undefined) {
                        Dom.setStyle('check_tax_number_name_tr', 'display', '')
                        Dom.get('check_tax_number_name').innerHTML = r.result.name

                    }
                    if (r.result.address != undefined) {
                        Dom.setStyle('check_tax_number_address_tr', 'display', '')
                        Dom.get('check_tax_number_address').innerHTML = r.result.address

                    }
                    if (Dom.get('save_tax_details_not_match') != undefined) Dom.setStyle('save_tax_details_not_match', 'display', '')
                    if (Dom.get('save_tax_details_match') != undefined) {
                        Dom.setStyle('save_tax_details_match', 'display', '')
                    } else {
                        Dom.setStyle('close_check_tax_number', 'display', '')
                    }

                } else {

                    Dom.setStyle('close_check_tax_number', 'display', '')
                }



            } else {

                Dom.setStyle('close_check_tax_number', 'display', '')
            }



        },
        failure: function(o) {

        }

    });


}


function init_basket() {



    validate_scope_data = {
        'customer_quick': {
            'tax_number': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'Customer_Tax_Number',
                'validation': [{
                    'regexp': "[a-z\\d]+",
                    'invalid_msg': Dom.get('invalid_tax_number_label').value
                }]
            }

        }
    };



    validate_scope_metadata = {
        'customer_quick': {
            'type': 'edit',
            'ar_file': 'ar_edit_contacts.php',
            'key_name': 'customer_key',
            'key': Dom.get('customer_key').value
        }
    };


    var customer_tax_number_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_tax_number);
    customer_tax_number_oACDS.queryMatchContains = true;
    var customer_tax_number_oAutoComp = new YAHOO.widget.AutoComplete("Customer_Tax_Number", "Customer_Tax_Number_Container", customer_tax_number_oACDS);
    customer_tax_number_oAutoComp.minQueryLength = 0;
    customer_tax_number_oAutoComp.queryDelay = 0.1;


    Event.addListener('cancel_order', "click", cancel_order);
    Event.addListener('show_cancel_order_dialog', "click", show_cancel_order_dialog);
    Event.addListener('close_cancel_order_dialog', "click", close_cancel_order_dialog);

    Event.addListener('show_cancel_order_dialog', "mouseover", show_cancel_order_info);
    Event.addListener('show_cancel_order_dialog', "mouseout", hide_cancel_order_info);

    dialog_confirm_cancel = new YAHOO.widget.Dialog("dialog_confirm_cancel", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false

    });
    dialog_confirm_cancel.render();


    dialog_set_tax = new YAHOO.widget.Dialog("dialog_set_tax", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false

    });
    dialog_set_tax.render();

    Event.addListener('special_instructions', "keydown", special_intructions_changed);

    var special_intructions_oACDS = new YAHOO.util.FunctionDataSource(save_special_intructions);
    special_intructions_oACDS.queryMatchContains = true;
    var special_intructions_oAutoComp = new YAHOO.widget.AutoComplete("special_instructions", "special_instructions_container", special_intructions_oACDS);
    special_intructions_oAutoComp.minQueryLength = 0;
    special_intructions_oAutoComp.queryDelay = 0.3;


    edit_delivery_address = new YAHOO.widget.Dialog("edit_delivery_address_splinter_dialog", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false

    });
    edit_delivery_address.render();
    YAHOO.util.Event.addListener("change_delivery_address", "click", change_delivery_address);

    edit_billing_address = new YAHOO.widget.Dialog("edit_billing_address_splinter_dialog", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false

    });
    edit_billing_address.render();
    YAHOO.util.Event.addListener("change_billing_address", "click", change_billing_address);

   if (Dom.get("dialog_check_tax_number") != undefined) {
        dialog_check_tax_number = new YAHOO.widget.Dialog("dialog_check_tax_number", {
            visible: false,
            close: true,
            underlay: "none",
            draggable: false
        });
        dialog_check_tax_number.render();
    }




    //   Event.addListener("check_tax_number", "click", show_dialog_check_tax_number);
    Event.addListener(["close_check_tax_number"], "click", close_dialog_check_tax_number);


}

YAHOO.util.Event.onDOMReady(init_basket);
