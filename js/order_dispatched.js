var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
YAHOO.namespace("invoice");

function show_more_dn_operations(dn_key){
Dom.setStyle('more_dn_opertions_'+dn_key,'display','none');
Dom.setStyle('dn_operations_tr_'+dn_key,'display','');

}


function show_dispatched_post_transactions() {
    Dom.setStyle('dispatched_post_transactions', 'display', '');
    Dom.setStyle('msg_dispatched_post_transactions', 'display', 'none');

}


function show_set_tax_number_dialog_from_details() {
    region1 = Dom.getRegion('update_order_tax_number');
    region2 = Dom.getRegion('dialog_set_tax_number');
    var pos = [region1.right - region2.width, region1.top]
    Dom.setXY('dialog_set_tax_number', pos);

    if (Dom.get('tax_number') != undefined) tax_number = Dom.get('tax_number').innerHTML


    dialog_set_tax_number.show();

}


function create_invoice() {

    Dom.get('create_invoice_img').src = 'art/loading.gif'
    var order_key = Dom.get('order_key').value;


    var request = 'ar_edit_orders.php?tipo=create_invoice_order&order_key=' + escape(order_key);
    // alert(request); return;
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
        //	alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                //location.href='invoice.php?id='+r.invoice_key;
                post_create_invoice_actions(r.invoice_key);

                //location.reload(); 
            } else {
                alert(r.msg)

            }
        }
    });

}

function post_create_invoice_actions(invoice_key) {

    //var request='ar_edit_orders.php?tipo=categorize_invoice&invoice_key='+escape(invoice_key);
    //YAHOO.util.Connect.asyncRequest('POST',request ,{});    
    location.reload();
}


YAHOO.util.Event.addListener(window, "load", function() {

    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;
    state = session_data.state;

   tables = new function() {

        var tableid = 0;
        var tableDivEL = "table" + tableid;



        var InvoiceColumnDefs = [{
            key: "code",
            label: labels.Code,
            width: 60,
            sortable: false,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "description",
            label: labels.Description,
            width: 370,
            sortable: false,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }

        , {
            key: "ordered",
            label: labels.Ordered,
            width: 100,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "dispatched",
            label: labels.Dispatched,
            width: 70,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "invoiced",
            label: labels.Amount,
            width: 70,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }];

        request = "ar_orders.php?tipo=transactions_dispatched&tableid=0&sf=0&parent=order&parent_key=" + Dom.get('order_key').value
        //alert(request)
        this.dataSource0 = new YAHOO.util.DataSource(request);
        this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource0.connXhrMode = "queueRequests";
        this.dataSource0.responseSchema = {
            resultsList: "resultset.data",
            metaFields: {
                rowsPerPage: "resultset.records_perpage",
                RecordOffset: "resultset.records_offset",
                rtext: "resultset.rtext",
                rtext_rpp: "resultset.rtext_rpp",
                sort_key: "resultset.sort_key",
                sort_dir: "resultset.sort_dir",
                tableid: "resultset.tableid",
                filter_msg: "resultset.filter_msg",
                totalRecords: "resultset.total_records"
            },
            fields: ["code", "description", "ordered", "invoiced", "dispatched", "tariff_code"]
        };

        this.table0 = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs, this.dataSource0, {
            //draggableColumns:true,
            renderLoopSize: 50,
            generateRequest: myRequestBuilderwithTotals,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.order.items.nr,
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
                key: state.order.items.order,
                dir: state.order.items.order_dir
            },
            dynamicData: true

        });
        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table0.doBeforeLoadData = mydoBeforeLoadData;


        this.table0.table_id = tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);

        this.table0.filter = {
            key: state.order.items.order,
            dir: state.order.items.order_dir
        };



        var tableid = 1;
        var tableDivEL = "table" + tableid;



        var InvoiceColumnDefs = [{
            key: "code",
            label: labels.Code,
            width: 60,
            sortable: false,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "description",
            label: labels.Description,
            width: 275,
            sortable: false,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "dispatched",
            label: labels.Original_Qty,
            width: 65,
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
            }
        }, {
            key: "operation",
            label: labels.Operation,
            width: 70,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "reason",
            label: labels.Reason,
            width: 90,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }

        , {
            key: "notes",
            label: labels.Notes,
            width: 140,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }];

        request = "ar_orders.php?tipo=post_transactions&sf=0&tableid=" + tableid + "&parent=order&parent_key=" + Dom.get('order_key').value;
        //alert(request)
        this.dataSource1 = new YAHOO.util.DataSource(request);
        this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource1.connXhrMode = "queueRequests";




        this.dataSource1.responseSchema = {
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
            fields: ["code", "description", 'quantity', 'operation', 'tariff_code', "notes", "dn", "dispatched", 'reason']
        };


        this.InvoiceDataTable1 = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs, this.dataSource1, {
            renderLoopSize: 50
        }

        );




        var tableid = 2;
        var tableDivEL = "table" + tableid;


        var myRowFormatter = function(elTr, oRecord) {
                if (oRecord.getData('type') == 'Orders') {
                    Dom.addClass(elTr, 'customer_history_orders');
                } else if (oRecord.getData('type') == 'Notes') {
                    Dom.addClass(elTr, 'customer_history_notes');
                } else if (oRecord.getData('type') == 'Changes') {
                    Dom.addClass(elTr, 'customer_history_changes');
                }
                return true;
            };


        this.prepare_note = function(elLiner, oRecord, oColumn, oData) {

            if (oRecord.getData("strikethrough") == "Yes") {
                Dom.setStyle(elLiner, 'text-decoration', 'line-through');
                Dom.setStyle(elLiner, 'color', '#777');

            }
            elLiner.innerHTML = oData
        };

        var ColumnDefs = [{
            key: "key",
            label: "",
            width: 20,
            sortable: false,
            isPrimaryKey: true,
            hidden: true
        }, {
            key: "type",
            label: "",
            width: 0,
            sortable: false,
            hidden: true
        }, {
            key: "date",
            label: labels.Date,
            className: "aright",
            width: 120,
            sortable: true,
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "time",
            label: labels.Time,
            className: "aleft",
            width: 70
        }, {
            key: "handle",
            label: labels.Author,
            className: "aleft",
            width: 100,
            sortable: true,
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "note",
            formatter: this.prepare_note,
            label: labels.Notes,
            className: "aleft",
            width: 420
        }

        ];
        request = "ar_history.php?tipo=customer_history&parent=order_customer&parent_key=" + Dom.get('customer_key').value + "&sf=0&tableid=" + tableid

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
            fields: ["note", "date", "time", "handle", "delete", "can_delete", "delete_type", "key", "edit", "type", "strikethrough"]
        };

        this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource2, {
            formatRow: myRowFormatter,
            renderLoopSize: 5,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.order.customer_history.nr,

                containers: 'paginator2',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                alwaysVisible: false,
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



            })

            ,
            sortedBy: {
                key: state.order.customer_history.order,
                dir: state.order.customer_history.order_dir
            },
            dynamicData: true

        }

        );
        this.table2.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table2.filter = {
            key: state.order.customer_history.f_field,
            value: state.order.customer_history.f_value
        };

        this.table2.subscribe("cellMouseoverEvent", highlightEditableCell);
        this.table2.subscribe("cellMouseoutEvent", unhighlightEditableCell);



        this.table2.subscribe("cellClickEvent", onCellClick);
        this.table2.table_id = tableid;
        this.table2.subscribe("renderEvent", myrenderEvent);


        this.table2.getDataSource().sendRequest(null, {
            success: function(request, response, payload) {



                if (response.results.length == 0) {
                    //alert("caca")
                    get_history_numbers();

                } else {
                    // this.onDataReturnInitializeTable(request, response, payload);
                }
            },
            scope: this.table2,
            argument: this.table2.getState()
        });





      var tableid = 3;
        var tableDivEL = "table" + tableid;


        var myRowFormatter = function(elTr, oRecord) {
                if (oRecord.getData('type') == 'Orders') {
                    Dom.addClass(elTr, 'store_history_orders');
                } else if (oRecord.getData('type') == 'Notes') {
                    Dom.addClass(elTr, 'store_history_notes');
                } else if (oRecord.getData('type') == 'Changes') {
                    Dom.addClass(elTr, 'store_history_changes');
                }
                return true;
            };

        this.prepare_note = function(elLiner, oRecord, oColumn, oData) {

            if (oRecord.getData("strikethrough") == "Yes") {
                Dom.setStyle(elLiner, 'text-decoration', 'line-through');
                Dom.setStyle(elLiner, 'color', '#777');

            }
            elLiner.innerHTML = oData
        };

        var ColumnDefs = [{
            key: "key",
            label: "",
            width: 20,
            sortable: false,
            isPrimaryKey: true,
            hidden: true
        }, {
            key: "date",
            label: labels.Date,
            className: "aright",
            width: 120,
            sortable: true,
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "time",
            label: labels.Time,
            className: "aleft",
            width: 70
        }, {
            key: "handle",
            label: labels.Author,
            className: "aleft",
            width: 120,
            sortable: true,
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "note",
            formatter: this.prepare_note,
            label: labels.Notes,
            className: "aleft",
            width: 420
        }, {
            key: "delete",
            label: "",
            width: 12,
            sortable: false,
            action: 'dialog',
            object: 'delete_note'
        }, {
            key: "edit",
            label: "",
            width: 12,
            sortable: false,
            action: 'edit',
            object: 'supplier_product_history'
        }

        ];
        request = "ar_history.php?tipo=order_history&parent=order&parent_key=" + Dom.get('order_key').value + "&sf=0&tableid=" + tableid
        
        this.dataSource3 = new YAHOO.util.DataSource(request);
        this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource3.connXhrMode = "queueRequests";
        this.dataSource3.responseSchema = {
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
            fields: ["note", "date", "time", "handle", "delete", "can_delete", "delete_type", "key", "edit", "type", "strikethrough"]
        };
        this.table3 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource3, {
            formatRow: myRowFormatter,
            renderLoopSize: 5,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.order.history.nr,
                containers: 'paginator3',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                alwaysVisible: false,
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



            })

            ,
            sortedBy: {
                key: state.order.history.order,
                dir: state.order.history.order_dir
            },
            dynamicData: true

        }

        );

        this.table3.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;

        this.table3.filter = {
            key: state.order.history.f_field,
            value: state.order.history.f_value
        };
        this.table3.subscribe("cellMouseoverEvent", highlightEditableCell);
        this.table3.subscribe("cellMouseoutEvent", unhighlightEditableCell);
        this.table3.subscribe("cellClickEvent", onNotesCellClick);
        this.table3.table_id = tableid;
        this.table3.subscribe("renderEvent", myrenderEvent);



    };
});


function get_dn_invoices_info() {

}

function show_order_details() {
    Dom.setStyle('order_details_panel', 'display', '')
    Dom.setStyle('show_order_details', 'display', 'none')

}



function close_quick_edit_tax_number() {
    dialog_set_tax_number.hide();
}

function save_quick_edit_tax_number() {
    save_edit_general_bulk('order');
}

function init() {
    init_search('orders_store');
    get_dn_invoices_info()
    Event.addListener("create_invoice", "click", create_invoice);
 



    validate_scope_data = {
        'order': {
            'tax_number': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'Order_Tax_Number',
                'validation': [{
                    'regexp': "[a-z0-9]+",
                    'invalid_msg': Dom.get('invalid_tax_number_label').value
                }]
            }

        }
    };



    validate_scope_metadata = {
        'order': {
            'type': 'edit',
            'ar_file': 'ar_edit_orders.php',
            'key_name': 'order_key',
            'key': Dom.get('order_key').value
        }
    };


    var customer_tax_number_oACDS = new YAHOO.util.FunctionDataSource(validate_customer_tax_number);
    customer_tax_number_oACDS.queryMatchContains = true;
    var customer_tax_number_oAutoComp = new YAHOO.widget.AutoComplete("Order_Tax_Number", "Order_Tax_Number_Container", customer_tax_number_oACDS);
    customer_tax_number_oAutoComp.minQueryLength = 0;
    customer_tax_number_oAutoComp.queryDelay = 0.1;


    dialog_set_tax_number = new YAHOO.widget.Dialog("dialog_set_tax_number", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false

    });
    dialog_set_tax_number.render();

}

YAHOO.util.Event.onDOMReady(init);
