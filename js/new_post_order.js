var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_cancel, dialog_edit_shipping;
var dialog_mark_all_for_refund;
YAHOO.namespace("invoice");
var panel2;

var edit_delivery_address;


function post_change_main_delivery_address() {

    var ar_file = 'ar_edit_orders.php';
    request = 'tipo=update_ship_to_key&order_key=' + Dom.get('order_key').value + '&ship_to_key=0';

    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                if (r.result == 'updated') {
                    edit_delivery_address.hide()
                    Dom.get('delivery_address').innerHTML = r.new_value;

                }
            } else {
                alert(r.msg);
                //	callback();
            }
        },
        failure: function(o) {
            alert(o.statusText);
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
    //alert(request);return;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
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


                }


            } else {
                alert(r.msg);
                //	callback();
            }
        },
        failure: function(o) {
            alert(o.statusText);
            // callback();
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
        //alert(datatable)
        var recordIndex = this.getRecordIndex(record);
        var data = record.getData();



        if (!(data['state'] == 'In Process' || data['state'] == '')) {

            return;
        }

        switch (column.action) {
        case ('add_object'):
        case ('remove_object'):



            if (data['quantity'] == '') {
                data['quantity'] = 0;
            }
            if (column.action == 'add_object') {



                var new_qty = parseFloat(data['quantity']) + 1;
                if (new_qty > data['max_resend']) new_qty = data['max_resend'];


            } else {
                qty = parseFloat(data['quantity'])
                if (qty == 0) {
                    return;
                }
                var new_qty = qty - 1;
                
                if(new_qty<0){
                new_qty=0;
                }

            }


            if (new_qty == data['quantity']) return;



            var ar_file = 'ar_edit_orders.php';
            request = 'tipo=edit_new_post_order&order_key=' + data['order_key'] + '&key=quantity&new_value=' + new_qty + '&otf_key=' + data['otf_key'];
            //alert(request);
            YAHOO.util.Connect.asyncRequest('POST', ar_file, {
                success: function(o) {
                    //   alert(o.responseText);
                    var r = YAHOO.lang.JSON.parse(o.responseText);
                    if (r.state == 200) {
                        if (r.result == 'updated') {
                            datatable.updateCell(record, 'quantity', r.quantity);
                            datatable.updateCell(record, 'notes', r.notes);
                            datatable.updateCell(record, 'operation', r.operation);
                            datatable.updateCell(record, 'reason', r.reason);
                            datatable.updateCell(record, 'to_be_returned', r.to_be_returned);
                            if (r.data != undefined) {
                                for (x in r.data) {
                                    for (y in r.data[x]) {
                                        if (Dom.get(x + '_' + y) != null) Dom.get(x + '_' + y).innerHTML = r.data[x][y];
                                    }

                                }




                                if (r.data.Refund.In_Process_Products == 0) {
                                    Dom.setStyle('refund_marked_items_tr', 'display', 'none')
                                    Dom.setStyle('show_mark_all_for_refund', 'display', '')



                                } else {
                                    Dom.setStyle('refund_marked_items_tr', 'display', '')
                                    Dom.setStyle('show_mark_all_for_refund', 'display', 'none')

                                }

                                Dom.get('refund_items_value').value = r.data.Refund.Other_Items_Amount
                                Dom.get('refund_marked_items_value').value = r.data.Refund.Net_Amount
                                Dom.get('refund_marked_items_tax_value').value = r.data.Refund.Tax_Amount
                                Dom.get('refund_marked_items_original_formated_value').innerHTML = r.data.Refund.Formated_Net_Amount
                                Dom.get('refund_marked_items_formated_value').innerHTML = r.data.Refund.Formated_Net_Amount
                                Dom.get('refund_items_original_formated_value').innerHTML = r.data.Refund.Formated_Other_Items_Amount
                                Dom.get('refund_items_formated_value').innerHTML = r.data.Refund.Formated_Other_Items_Amount

                                recalcuate_refund_total()


                                if (r.data['Refund']['In_Process_Products'] == 0) {
                                    Dom.setStyle('refund', 'display', 'none');
                                } else {
                                    Dom.setStyle('refund', 'display', '');
                                }


                                if (r.data['Resend']['Distinct_Products'] == 0) {
                                    Dom.setStyle(['resend', 'shipping_block', 'send'], 'display', 'none');
                                } else {
                                    Dom.setStyle(['resend', 'shipping_block', 'send'], 'display', '');
                                }



                                if (r.data['Resend']['In_Process_Products'] == 0) {
                                    Dom.setStyle(['send'], 'display', 'none');
                                } else {
                                    Dom.setStyle(['send'], 'display', '');
                                }







                            }
                        }

                    } else {
                        alert(r.msg);
                        //	callback();
                    }
                },
                failure: function(o) {
                    alert(o.statusText);
                    // callback();
                },
                scope: this
            }, request

            );

            break;


        default:

            this.onEventShowCellEditor(oArgs);
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

        var data = record.getData();

        request = 'tipo=edit_new_post_order&order_key=' + data['order_key'] + '&key=' + column.object + '&new_value=' + encodeURIComponent(newValue) + '&otf_key=' + data['otf_key'];


        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {
                // alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {


                    if (r.result == 'nochange') {
                        callback(true, this.value);
                        return;
                    }

                    if (r.result == 'updated') {
                        datatable.updateCell(record, 'quantity', r.quantity);
                        datatable.updateCell(record, 'notes', r.notes);
                        datatable.updateCell(record, 'operation', r.operation);
                        datatable.updateCell(record, 'reason', r.reason);
                        datatable.updateCell(record, 'to_be_returned', r.to_be_returned);
                        if (r.data != undefined) {
                            for (x in r.data) {
                                for (y in r.data[x]) {
                                    if (Dom.get(x + '_' + y) != null) Dom.get(x + '_' + y).innerHTML = r.data[x][y];
                                }

                            }

							
                                if (r.data.Refund.In_Process_Products == 0) {
                                    Dom.setStyle('refund_marked_items_tr', 'display', 'none')
                                    Dom.setStyle('show_mark_all_for_refund', 'display', '')



                                } else {
                                    Dom.setStyle('refund_marked_items_tr', 'display', '')
                                    Dom.setStyle('show_mark_all_for_refund', 'display', 'none')

                                }

                                Dom.get('refund_items_value').value = r.data.Refund.Other_Items_Amount
                                Dom.get('refund_marked_items_value').value = r.data.Refund.Net_Amount
                                Dom.get('refund_marked_items_tax_value').value = r.data.Refund.Tax_Amount
                                Dom.get('refund_marked_items_original_formated_value').innerHTML = r.data.Refund.Formated_Net_Amount
                                Dom.get('refund_marked_items_formated_value').innerHTML = r.data.Refund.Formated_Net_Amount
                                Dom.get('refund_items_original_formated_value').innerHTML = r.data.Refund.Formated_Other_Items_Amount
                                Dom.get('refund_items_formated_value').innerHTML = r.data.Refund.Formated_Other_Items_Amount

                                recalcuate_refund_total()


                                if (r.data['Refund']['In_Process_Products'] == 0) {
                                    Dom.setStyle('refund', 'display', 'none');
                                } else {
                                    Dom.setStyle('refund', 'display', '');
                                }


                                if (r.data['Resend']['Distinct_Products'] == 0) {
                                    Dom.setStyle(['resend', 'shipping_block', 'send'], 'display', 'none');
                                } else {
                                    Dom.setStyle(['resend', 'shipping_block', 'send'], 'display', '');
                                }



                                if (r.data['Resend']['In_Process_Products'] == 0) {
                                    Dom.setStyle(['send'], 'display', 'none');
                                } else {
                                    Dom.setStyle(['send'], 'display', '');
                                }



                           

                        }
                    }


                    callback(true, r.new_value);




                } else {
                    //alert('msg:'+r.msg);
                    callback();
                }
            },
            failure: function(o) {
                // alert(o.statusText);
                callback();
            },
            scope: this
        }, request

        );
    };

function recalcuate_refund_total() {


    refund = 0;
    refund_tax = 0;




    if (Dom.get('refund_marked_items_switch').getAttribute('valor') == 'Yes') {
        refund = parseFloat(Dom.get('refund_marked_items_value').value) + refund;
        refund_tax = parseFloat(Dom.get('refund_marked_items_tax_value').value) + refund_tax;

        Dom.get('refund_marked_items_formated_value').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat(Dom.get('refund_marked_items_value').value).toFixed(2)


    } else {
        Dom.get('refund_marked_items_formated_value').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat('0').toFixed(2)

    }



    if (Dom.get('refund_items_switch').getAttribute('valor') == 'Yes') {
        refund = parseFloat(Dom.get('refund_items_value').value) + refund;
        refund_tax = parseFloat(Dom.get('refund_items_tax_value').value) + refund_tax;

        Dom.get('refund_items_formated_value').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat(Dom.get('refund_items_value').value).toFixed(2)


        if ((Dom.get('refund_reason').value == '')) {

        } else {
            Dom.setStyle('show_other_items_options', 'display', '')

        }

    } else {
        Dom.get('refund_items_formated_value').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat('0').toFixed(2)

    }


    if (Dom.get('refund_shipping_switch').getAttribute('valor') == 'Yes') {
        refund = parseFloat(Dom.get('refund_shipping_value').value) + refund;
        refund_tax = parseFloat(Dom.get('refund_shipping_tax_value').value) + refund_tax;

        Dom.get('shipping').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat(Dom.get('refund_shipping_value').value).toFixed(2)

    } else {
        Dom.get('shipping').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat('0').toFixed(2)

    }
    if (Dom.get('refund_charges_switch').getAttribute('valor') == 'Yes') {
        refund = parseFloat(Dom.get('refund_charges_value').value) + refund;
        Dom.get('charges').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat(Dom.get('refund_charges_value').value).toFixed(2)

    } else {
        Dom.get('charges').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat('0').toFixed(2)

    }


    if (Dom.get('refund_insurance_switch').getAttribute('valor') == 'Yes') {
        refund = parseFloat(Dom.get('refund_insurance_value').value) + refund;
        Dom.get('insurance').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat(Dom.get('refund_insurance_value').value).toFixed(2)

    } else {
        Dom.get('insurance').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat('0').toFixed(2)

    }



    if (Dom.get('refund_net_adjusts_switch').getAttribute('valor') == 'Yes') {
        refund = parseFloat(Dom.get('refund_net_adjusts_value').value) + refund;

        Dom.get('net_adjusts').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat(Dom.get('refund_net_adjusts_value').value).toFixed(2)

    } else {
        Dom.get('net_adjusts').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat('0').toFixed(2)

    }


    if (Dom.get('refund_tax_adjusts_switch').getAttribute('valor') == 'Yes') {

        refund_tax = parseFloat(Dom.get('refund_tax_adjusts_value').value) + refund;
        Dom.get('tax_adjusts').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat(Dom.get('refund_tax_adjusts_value').value).toFixed(2)

    } else {
        Dom.get('tax_adjusts').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat('0').toFixed(2)

    }




    if (Dom.get('refund_total_net_switch').getAttribute('valor') == 'Yes') {
        net_refund = refund;
    } else {
        net_refund = 0;
        refund = 0;
    }






    if (Dom.get('refund_tax_switch').getAttribute('valor') == 'Yes') {
        refund = parseFloat(refund_tax) + refund;
        Dom.get('tax').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat(refund_tax).toFixed(2)

    } else {
        Dom.get('tax').innerHTML = Dom.get('refund_currency_symbol').value + parseFloat('0').toFixed(2)

    }





    Dom.get('refund_net_total').innerHTML = Dom.get('refund_currency_symbol').value + net_refund.toFixed(2)


    Dom.get('refund_total').innerHTML = Dom.get('refund_currency_symbol').value + refund.toFixed(2)
    Dom.get('Refund_Formated_Amount').innerHTML = Dom.get('refund_currency_symbol').value + refund.toFixed(2)



    validate_full_refund()

}





function save_full_refund() {

    Dom.setStyle('waiting_save_full_refund', 'display', '')
    Dom.setStyle(['save_full_refund', 'cancel_full_refund'], 'display', 'none')


    var data_to_update = new Object;
    data_to_update = {
        'items_to_be_returned': Dom.get('refund_return_items').value,
        'reason': Dom.get('refund_reason').value,

        'refund_marked_items': Dom.get('refund_marked_items_switch').getAttribute('valor'),
        'refund_items': Dom.get('refund_items_switch').getAttribute('valor'),
        'refund_shipping': Dom.get('refund_shipping_switch').getAttribute('valor'),
        'refund_charges': Dom.get('refund_charges_switch').getAttribute('valor'),
        'refund_insurance': Dom.get('refund_insurance_switch').getAttribute('valor'),
        'refund_net': Dom.get('refund_total_net_switch').getAttribute('valor'),

        'refund_net_adjusts': Dom.get('refund_net_adjusts_switch').getAttribute('valor'),
        'refund_tax': Dom.get('refund_tax_switch').getAttribute('valor'),
        'refund_tax_adjusts': Dom.get('refund_tax_adjusts_switch').getAttribute('valor')
        //   ,'refund_type': Dom.get('refund_action').value,
    }

    jsonificated_values = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data_to_update));


    var request = 'ar_edit_orders.php?tipo=refund_order&values=' + jsonificated_values + "&order_key=" + Dom.get('order_key').value
    // alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //  alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);


            if (r.state == 200) {
                //alert('ok');
                location.reload()
                // post_comment_update(r);
            } else {
                Dom.setStyle('waiting_save_full_refund', 'display', 'none')
                Dom.setStyle(['save_full_refund', 'cancel_full_refund'], 'display', '')
                alert('error, call Inikoo for support')

            }
            //alert('not');
            //post_comment_update(r);
        }
    });



}



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
        Dom.get(tipo + '_save').style.visibility = 'visible';
        break;
    }
};

function disable_save(tipo) {
    switch (tipo) {
    case ('cancel'):
        Dom.get(tipo + '_save').style.visibility = 'hidden';
        break;
    }
};


function close_dialog(tipo) {
    switch (tipo) {


    case ('cancel'):

        Dom.get(tipo + "_input").value = '';
        Dom.get(tipo + '_save').style.visibility = 'hidden';
        dialog_cancel.hide();

        break;
    }
};


YAHOO.util.Event.addListener(window, "load", function() {

    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;
    state = session_data.state;


    tables = new function() {



        var tableid = 0;
        var tableDivEL = "table" + tableid;



        var ColumnDefs = [{
            key: "order_key",
            label: "",
            width: 20,
            sortable: false,
            hidden: true
        }, {
            key: "otf_key",
            label: "",
            width: 20,
            sortable: false,
            isPrimaryKey: true,
            hidden: true
        }, {
            key: "max_resend",
            label: "",
            width: 20,
            sortable: false,
            hidden: true
        }, {
            key: "code",
            label: labels.Code,
            width: 60,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "description",
            label: labels.Description,
            width: 300,
            sortable: false,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "stock",
            label: labels.Stock,
            hidden: true,
            width: 80,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "ordered",
            label: labels.Ordered,
            width: 70,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "quantity",
            label: labels.Qty,
            width: 40,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            editor: new YAHOO.widget.TextboxCellEditor({
                asyncSubmitter: CellEdit
            }),
            object: 'quantity'
        }, {
            key: "add",
            label: "",
            width: 3,
            sortable: false,
            action: 'add_object',
            object: 'new_order'
        }, {
            key: "remove",
            label: "",
            width: 3,
            sortable: false,
            action: 'remove_object',
            object: 'new_order'
        }, {
            key: "operation",
            label: labels.Operation,
            width: 70,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            },
            object: 'operation',
            editor: new YAHOO.widget.RadioCellEditor({
                asyncSubmitter: CellEdit,
                radioOptions: [{
                    label: labels.Refund + '<br>',
                    value: 'Refund'
                }, {
                    label: labels.Resend + '<br>',
                    value: 'Resend'
                }],
                disableBtns: true
            })
        }, {
            key: "reason",
            label: labels.Reason,
            width: 60,
            sortable: false,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            },
            object: 'reason',
            editor: new YAHOO.widget.RadioCellEditor({
                asyncSubmitter: CellEdit,
                radioOptions: [{
                    label: labels.Damaged + '<br>',
                    value: 'Damaged'
                }, {
                    label: labels.Not_Received + '<br>',
                    value: 'Missing'
                }, {
                    label: labels.Dontlikeit + '<br>',
                    value: 'Do not Like'
                }, {
                    label: labels.Other + '<br>',
                    value: 'Other'
                }],
                disableBtns: true
            })
        }, {
            key: "to_be_returned",
            label: labels.Rtn,
            width: 25,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            },
            object: 'to_be_returned',
            editor: new YAHOO.widget.RadioCellEditor({
                asyncSubmitter: CellEdit,
                radioOptions: [{
                    label: labels.Yes + '<br>',
                    value: 'Yes'
                }, {
                    label: labels.No,
                    value: 'No'
                }],
                disableBtns: true
            })
        }, {
            key: "notes",
            label: labels.Notes,
            className: "aright",
            width: 100,
            hidden: false,
            sortable: false
        }

        ];

        request = "ar_edit_orders.php?tipo=post_transactions_to_process&tableid=" + tableid + '&parent_key=' + Dom.get('order_key').value

        this.dataSource0 = new YAHOO.util.DataSource(request);
        this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource0.connXhrMode = "queueRequests";
        this.dataSource0.responseSchema = {
            resultsList: "resultset.data",
            metaFields: {
                rtext: "resultset.rtext",
                rtext_rpp: "resultset.rtext_rpp",
                rowsPerPage: "resultset.records_perpage",
                sort_key: "resultset.sort_key",
                sort_dir: "resultset.sort_dir",
                tableid: "resultset.tableid",
                filter_msg: "resultset.filter_msg",
                totalRecords: "resultset.total_records"
            },
            fields: ["code", "state", "description", "quantity", "discount", "to_charge", "gross", "tariff_code", "stock", "add", "remove", "pid", "ordered", "operation", "order_key", "otf_key", "reason", "to_be_returned", "max_resend", "max_refund", "notes"]
        };







        this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource0, {

            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.post_transactions.nr,
                containers: 'paginator0',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                alwaysVisible: false,
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
            })

            ,
            sortedBy: {
                key: state.post_transactions.order,
                dir: state.post_transactions.order_dir
            },
            dynamicData: true

        });





        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table0.doBeforePaginator = mydoBeforePaginatorChange;
        this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
        this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
        this.table0.subscribe("cellClickEvent", myonCellClick);


        this.table0.table_id = tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);
        this.table0.filter = {
            key: state.post_transactions.f_field,
            value: state.post_transactions.f_value
        };


    };
});

function show_only_ordered_products() {

    Dom.removeClass('show_all_products', 'selected')
    Dom.addClass('show_only_ordered_products', 'selected')
    Dom.removeClass("showing_only_family", "selected");
    var table = tables['table0'];
    var datasource = tables['dataSource0'];
    var request = '&show_all=no';

    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function show_all_products() {


    Dom.addClass('show_all_products', 'selected')
    Dom.removeClass('show_only_ordered_products', 'selected')
    Dom.removeClass("showing_only_family", "selected");
    var table = tables['table0'];
    var datasource = tables['dataSource0'];
    var request = '&show_all=yes';

    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function save(tipo) {
    //alert(tipo)
    switch (tipo) {
    case ('cancel'):
        var value = encodeURIComponent(Dom.get(tipo + "_input").value);
        var ar_file = 'ar_edit_orders.php';
        var request = 'tipo=cancel&note=' + value;
        //alert('R:'+request);
        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {
                alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {
                    window.location.reload();
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



function create_delivery_note() {

    Dom.get('send_to_warehouse_img').src = 'art/loading.gif'

    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=send_post_order_to_warehouse&order_key=' + Dom.get('order_key').value;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //  alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                window.location = 'new_post_order.php?id=' + r.order_key;
            }
        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    }, request

    );

}


function cancel() {
    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=cancel_post_transactions&order_key=' + Dom.get('order_key').value;


    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                window.location.reload(true);

            }
        },
        failure: function(o) {
            alert(o.statusText);

        },
        scope: this
    }, request

    );



}



function close_edit_delivery_address_dialog() {
    edit_delivery_address.hide();
}

function change_delivery_address() {

    var y = (Dom.getY('control_panel'))
    var x = (Dom.getX('control_panel'))
    Dom.setX('edit_delivery_address_dialog', x);
    Dom.setY('edit_delivery_address_dialog', y + 0);
    edit_delivery_address.show();
}

function init() {
    init_search('orders_store');

    edit_delivery_address = new YAHOO.widget.Dialog("edit_delivery_address_dialog", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false

    });
    edit_delivery_address.render();



    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);

    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;
    //
    YAHOO.util.Event.addListener(["set_for_collection", "set_for_shipping"], "click", change_shipping_type);

    YAHOO.util.Event.addListener("change_delivery_address", "click", change_delivery_address);


    // YAHOO.util.Event.addListener('done', "click",create_delivery_note);
    dialog_mark_all_for_refund = new YAHOO.widget.Dialog("dialog_mark_all_for_refund", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    dialog_mark_all_for_refund.render();
    Event.addListener("show_mark_all_for_refund", "click", show_dialog_mark_all_for_refund);
    YAHOO.util.Event.addListener("cancel", "click", cancel);
    YAHOO.util.Event.addListener("send", "click", create_delivery_note);


    recalcuate_refund_total()

    //  YAHOO.util.Event.addListener("save_refund", "click", create_refund);
    // alert("xx")
    //  YAHOO.util.Event.addListener("save_credit", "click", save_credit);
    //  YAHOO.util.Event.addListener("cancel_saved_credit", "click", cancel_saved_credit);
}






YAHOO.util.Event.onDOMReady(init);




function switch_refund_element(o) {

    if (o.getAttribute('valor') == 'Yes') {
        o.src = 'art/icons/accept_bw.png';

        o.setAttribute('valor', 'No')
    } else {
        o.setAttribute('valor', 'Yes')
        o.src = 'art/icons/accept.png';

    }

    recalcuate_refund_total();


}


function change_refund_reason(value, o) {

    buttons = Dom.getElementsByClassName('reason_button', 'button', 'change_refund_reason_buttons')
    Dom.removeClass(buttons, 'selected')
    Dom.addClass(o, 'selected')
    Dom.get('refund_reason').value = value

    Dom.setStyle('hide_other_items_options', 'display', '')

    validate_full_refund()

}

/*
function change_refund_action(value, o) {

    buttons = Dom.getElementsByClassName('action_button', 'button', 'change_refund_action_buttons')
    Dom.removeClass(buttons, 'selected')
    Dom.addClass(o, 'selected')
    Dom.get('refund_action').value = value

    validate_full_refund()

}
*/

function hide_bulk_items_info() {

    Dom.setStyle('show_other_items_options', 'display', '')
    Dom.setStyle('bulk_items_info', 'display', 'none')


}

function show_bulk_items_info() {

    Dom.setStyle('show_other_items_options', 'display', 'none')
    Dom.setStyle('bulk_items_info', 'display', '')


}



function validate_full_refund() {

/*
    if (Dom.get('refund_action').value == '') {
        Dom.addClass("save_full_refund", "disabled")
        return;
    }
*/

    if (Dom.get('refund_items_switch').getAttribute('valor') == 'Yes') {
        if ((Dom.get('refund_reason').value != '')) {
            Dom.removeClass("save_full_refund", "disabled")
        } else {
            Dom.addClass("save_full_refund", "disabled")
        }

    } else {

        Dom.removeClass("save_full_refund", "disabled")
    }




}


function mark_all_for_refund_return(value) {

    if (value == 'Yes') {
        Dom.addClass('mark_all_for_refund_return_yes', 'selected')
        Dom.removeClass('mark_all_for_refund_return_no', 'selected')
        Dom.get('refund_return_items').value = 'Yes'
    } else {
        Dom.addClass('mark_all_for_refund_return_no', 'selected')
        Dom.removeClass('mark_all_for_refund_return_yes', 'selected')
        Dom.get('refund_return_items').value = 'No'

    }
}


function cancel_full_refund() {

    dialog_mark_all_for_refund.hide()

}

function show_dialog_merked_for_refund() {

    region1 = Dom.getRegion('totals');
    region2 = Dom.getRegion('dialog_mark_all_for_refund');
    region3 = Dom.getRegion('order_items_net_label');

    var pos = [region3.left - region2.width, region1.top]
    Dom.setXY('dialog_mark_all_for_refund', pos);
    dialog_mark_all_for_refund.show()


    Dom.setStyle('refund_marked_items_tr', 'display', '')
    Dom.setStyle('refund_items_tr', 'display', 'none')

    Dom.get('refund_marked_items_switch').setAttribute('valor', 'Yes')
    Dom.get('refund_items_switch').setAttribute('valor', 'No')



    recalcuate_refund_total()


}



function show_dialog_mark_all_for_refund() {

    region1 = Dom.getRegion('totals');
    region2 = Dom.getRegion('dialog_mark_all_for_refund');
    region3 = Dom.getRegion('order_items_net_label');

    var pos = [region3.left - region2.width, region1.top]
    Dom.setXY('dialog_mark_all_for_refund', pos);
    dialog_mark_all_for_refund.show()

    Dom.setStyle('refund_marked_items_tr', 'display', 'none')
    Dom.setStyle(['refund_items_tr', 'bulk_items_info'], 'display', '')

    Dom.get('refund_marked_items_switch').setAttribute('valor', 'No')
    Dom.get('refund_items_switch').setAttribute('valor', 'Yes')
    Dom.get('refund_shipping_switch').setAttribute('valor', 'Yes')
    Dom.get('refund_charges_switch').setAttribute('valor', 'Yes')
    Dom.get('refund_insurance_switch').setAttribute('valor', 'Yes')
    Dom.get('refund_marked_items_switch').src = 'art/icons/accept_bw.png';
    Dom.get('refund_items_switch').src = 'art/icons/accept.png';
    Dom.get('refund_shipping_switch').src = 'art/icons/accept.png';
    Dom.get('refund_charges_switch').src = 'art/icons/accept.png';
    Dom.get('refund_insurance_switch').src = 'art/icons/accept.png';
    recalcuate_refund_total()

}

function show_dialog_marked_for_refund() {

    region1 = Dom.getRegion('totals');
    region2 = Dom.getRegion('dialog_mark_all_for_refund');
    region3 = Dom.getRegion('order_items_net_label');

    var pos = [region3.left - region2.width, region1.top]
    Dom.setXY('dialog_mark_all_for_refund', pos);
    dialog_mark_all_for_refund.show()

    Dom.setStyle('refund_marked_items_tr', 'display', '')
    Dom.setStyle(['refund_items_tr', 'bulk_items_info'], 'display', 'none')





    Dom.get('refund_marked_items_switch').setAttribute('valor', 'Yes')
    Dom.get('refund_items_switch').setAttribute('valor', 'No')
    Dom.get('refund_shipping_switch').setAttribute('valor', 'No')
    Dom.get('refund_charges_switch').setAttribute('valor', 'No')
    Dom.get('refund_insurance_switch').setAttribute('valor', 'No')
    Dom.get('refund_marked_items_switch').src = 'art/icons/accept.png';
    Dom.get('refund_items_switch').src = 'art/icons/accept_bw.png';
    Dom.get('refund_shipping_switch').src = 'art/icons/accept_bw.png';
    Dom.get('refund_charges_switch').src = 'art/icons/accept_bw.png';
    Dom.get('refund_insurance_switch').src = 'art/icons/accept_bw.png';
    recalcuate_refund_total()

}


function cancel_saved_credit() {
    ar_file = 'ar_edit_orders.php';
    request = ar_file + '?tipo=cancel_saved_credit&order_key=' + Dom.get('order_key').value;
    //  alert(request)
    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                //window.location='dn.php?id='+Dom.get('dn_key').value;
                location.reload();
            }

        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });

}

function save_credit() {
    ar_file = 'ar_edit_orders.php';
    request = ar_file + '?tipo=save_credit&order_key=' + Dom.get('order_key').value;
    //  alert(request)
    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            //	alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                //window.location='dn.php?id='+Dom.get('dn_key').value;
                location.reload();
            }

        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });

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
