var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

var receivers = new Object;
var checkers = new Object;

var active_editor = '';
var receiver_list;
var checker_list;
var submit_dialog;
var staff_dialog;
var delete_dialog;
var dialog_incoterm_list;

function change_submit_method(o) {

    Dom.removeClass(Dom.getElementsByClassName('radio', 'button', 'submit_method_container'), 'selected')
    Dom.addClass(o, 'selected')


    Dom.get('submit_method').value = o.getAttribute('radio_value')

}



var myCellEdit = function(callback, newValue) {
        var record = this.getRecord(),
            column = this.getColumn(),
            oldValue = this.value,
            datatable = this.getDataTable();
        recordIndex = datatable.getRecordIndex(record);

        ar_file = 'ar_edit_porders.php';

        var request = 'tipo=edit_' + column.object + '&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue) + myBuildUrl(datatable, record) + '&po_key=' + Dom.get('po_key').value;


        // alert(ar_file+'?'+request);
        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {
                //alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {

                    Dom.get('ordered_products_number').innerHTML = r.data.distinct_products

                    if (r.data.distinct_products == 0) Dom.addClass('submit_po', 'disabled')
                    else Dom.removeClass('submit_po', 'disabled')

                    for (x in r.data) {
                        if (Dom.get(x) != undefined) {
                            Dom.get(x).innerHTML = r.data[x];
                        }
                    }

                    datatable.updateCell(record, 'amount', r.to_charge);

                    if (r.quantity == 0 && Dom.get('products_display_type').value == 'ordered_products') {
                        datatable.deleteRow(record);
                    }

                    callback(true, r.quantity);
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




var myonCellClick = function(oArgs) {


        var target = oArgs.target,
            column = this.getColumn(target),
            record = this.getRecord(target);



        datatable = this;
        var records = this.getRecordSet();
        //alert(records.getLength())
        // alert(column.action);
        //return;
        //alert(datatable)
        var recordIndex = this.getRecordIndex(record);


        switch (column.action) {

        case ('add_object'):
        case ('remove_object'):
            var data = record.getData();

            if (column.action == 'add_object') var new_qty = parseFloat(data['quantity']) + 1;
            else var new_qty = parseFloat(data['quantity']) - 1;

            var ar_file = 'ar_edit_porders.php';
            request = 'tipo=edit_new_porder&key=quantity&newvalue=' + new_qty + '&oldvalue=' + data['quantity'] + '&pid=' + data['pid'] + '&key=' + data['key'] + '&po_key=' + Dom.get('po_key').value;


            //alert(ar_file+'?'+request)
            YAHOO.util.Connect.asyncRequest('POST', ar_file, {
                success: function(o) {
                    //	alert(o.responseText);
                    var r = YAHOO.lang.JSON.parse(o.responseText);
                    if (r.state == 200) {
                        for (x in r.data) {
                            if (Dom.get(x) != undefined) {
                                Dom.get(x).innerHTML = r.data[x];
                            }
                        }

                        Dom.get('ordered_products_number').innerHTML = r.data.distinct_products

                        if (r.data.distinct_products == 0) Dom.addClass('submit_po', 'disabled')
                        else Dom.removeClass('submit_po', 'disabled')


                        datatable.updateCell(record, 'quantity', r.quantity);
                        if (r.quantity == 0) r.to_charge = '';
                        datatable.updateCell(record, 'amount', r.to_charge);

                        if (r.quantity == 0 && Dom.get('products_display_type').value == 'ordered_products') {
                            this.deleteRow(target);
                        }


                        //	callback(true, r.newvalue);
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



function close_dialog_bis(tipo) {
    switch (tipo) {
    case ('submit'):
        submit_dialog.hide();
        Dom.get('tr_manual_submit_date').style.display = "";
        Dom.get('tbody_manual_submit_date').style.display = "none";
        Dom.get('date_type').value = 'auto';

        break;
    case ('staff'):
        staff_dialog.hide();

        break;
    case ('delete'):
        delete_dialog.hide();

        break;
    }

}




function delete_order() {

    Dom.setStyle('waiting_delete_order', 'display', '')
    Dom.setStyle('delete_order', 'display', 'none')

    var request = 'ar_edit_porders.php?tipo=delete_po&id=' + Dom.get('po_key').value;

    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                location.href = 'supplier.php?id=' + r.supplier_key;
            } else {
                Dom.setStyle('waiting_delete_order', 'display', 'none')
                Dom.setStyle('delete_order', 'display', '')
                Dom.get('delete_dialog_msg').innerHTML = r.msg;
            }
        }
    });

}


function show_only_ordered_products() {



    Dom.removeClass('all_products', 'selected')
    Dom.addClass('ordered_products', 'selected')

    var table = tables['table0'];
    var datasource = tables['dataSource0'];
    var request = '&display=ordered_products';
    Dom.get('products_display_type').value = 'ordered_products';
    hide_filter('', 0)


    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function show_all_products() {
    Dom.removeClass('ordered_products', 'selected')
    Dom.addClass('all_products', 'selected')

    var table = tables['table0'];
    var datasource = tables['dataSource0'];
    var request = '&display=all_products';
    Dom.get('products_display_type').value = 'all_products';

    hide_filter('', 0)

    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}



var select_staff = function(o, e) {

        var staff_id = o.getAttribute('staff_id');
        var staff_name = o.innerHTML;
        o.className = 'selected';

        Dom.get('submitted_by').value = staff_id;
        Dom.get('submited_by_alias').innerHTML = staff_name;

        close_dialog_bis('staff');
    }


function submit_order_save(o) {

    var submit_date = Dom.get('v_calpop1').value;
    //	var submit_time=Dom.get('v_time').value;
    var estimated_date = ''
    //	var date_type=Dom.get('date_type').value;
    var submit_method = Dom.get('submit_method').value;
    var staff_key = Dom.get('submitted_by').value;


    Dom.setStyle('submit_order_wait', 'display', '')
    Dom.setStyle('submit_order_button', 'display', 'none')

    var request = 'ar_edit_porders.php?tipo=submit&submit_method=' + escape(submit_method) + '&staff_key=' + escape(staff_key) + '&submit_date=' + escape(submit_date) + '&id=' + escape(Dom.get('po_key').value);

    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                location.href = 'porder.php?id=' + Dom.get('po_key').value;

            } else alert(r.msg);
        }
    });
}






var swap_show_all_products = function(o) {

        var status = o.getAttribute('status');
        //alert(status)
        if (status == 0) {
            o.className = 'selected but';
            Dom.get('show_items').className = 'but';
            var table = tables['table0'];
            var datasource = tables['dataSource0'];
            var request = '&all_products=0&all_products_supplier=1';

            Dom.get("clean_table_controls0").style.visibility = 'visible';
            Dom.get("clean_table_filter0").style.visibility = 'visible';
            datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
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
            key: "key",
            label: '',
            width: 20,
            sortable: false,
            isPrimaryKey: true,
            hidden: true
        }, {
            key: "pid",
            label: '',
            width: 20,
            sortable: false,
            isPrimaryKey: true,
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
            key: "parts_info",
            label: labels.Parts_Info,
            width: 200,
            sortable: false,
            className: "aleft"
        }

        , {
            key: "description",
            label: labels.Description,
            width: 300,
            sortable: false,
            className: "aleft"
        }

        , {
            key: "quantity",
            label: labels.Qty,
            width: 40,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            editor: new YAHOO.widget.TextboxCellEditor({
                asyncSubmitter: myCellEdit
            }),
            object: 'new_porder',
            'action': 'change_qty'
        }
        // ,{key:"stock", label:labels.Stock,width:90,className:"aright"}
        // ,{key:"stock_time", label:labels.Stock_Time,width:75,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
        // ,{key:"expected_qty_edit", label:labels.Qty,width:70,className:"aright"}
        // ,{key:"expected_qty", label:labels.Qty,width:100,className:"aright"}
        ,
        {
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
        },

        //    , {
        //       key: "unit_type",
        //      label: labels.Unit,
        //      width: 30,
        //      className: "aleft"
        //  }, 
        {
            key: "amount",
            label: labels.Net_Cost,
            width: 100,
            className: "aright"
        }
        // ,{key:"qty_edit", label:labels.Qty,width:50,className:"aright",hidden:true}
        // ,{key:"diff", label:labels.delta:40,className:"aright",hidden:true}
        //,{key:"damaged_edit", label:labels.Damaged,width:60,className:"aright",hidden:true}
        //,{key:"damaged", label:labels.Damaged,width:60,className:"aright"}
        //,{key:"usable", label:Unusable,width:55,className:"aright"}
        ];
        request = "ar_edit_porders.php?tipo=po_transactions_to_process&sf=0&tableid=" + tableid + '&id=' + Dom.get('po_key').value + '&supplier_key=' + Dom.get('supplier_key').value
        // alert(request)
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

            fields: ["key", "pid", "code", "description", "quantity", "amount", "unit_type", "add", "remove", "parts_info"]
        };

        this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource0, {
            //draggableColumns:true,
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.porder.products.nr,

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

                key: state.porder.products.order,
                dir: state.porder.products.order_dir
            },
            dynamicData: true

        });
        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table0.doBeforeLoadData = mydoBeforeLoadData;
        this.table0.table_id = tableid;
        this.table0.request = request;
        this.table0.subscribe("renderEvent", myrenderEvent);


        this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
        this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
        this.table0.subscribe("cellClickEvent", myonCellClick);



        this.table0.filter = {
            key: state.porder.products.f_field,
            value: state.porder.products.f_value
        };


        var tableid = 2;
        var tableDivEL = "table" + tableid;


        var ColumnDefs = [{
            key: "key",
            label: "",
            width: 100,
            hidden: true
        }, {
            key: "code",
            label: labels.Alias,
            width: 100,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "name",
            label: labels.Name,
            width: 250,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }

        ];
        this.dataSource2 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=active_staff_list&active=Yes&tableid=" + tableid + "&nr=20&sf=0");
        //alert("ar_quick_tables.php?tipo=active_staff_list&active=Yes&tableid="+tableid+"&nr=20&sf=0");
        this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource2.connXhrMode = "queueRequests";
        this.dataSource2.table_id = tableid;

        this.dataSource2.responseSchema = {
            resultsList: "resultset.data",
            metaFields: {
                rtext: "resultset.rtext",
                rtext_rpp: "resultset.rtext_rpp",
                rowsPerPage: "resultset.records_perpage",
                sort_key: "resultset.sort_key",
                sort_dir: "resultset.sort_dir",
                tableid: "resultset.tableid",
                filter_msg: "resultset.filter_msg",
                totalRecords: "resultset.total_records" // Access to value in the server response
            },


            fields: ["code", 'name', 'key']
        };

        this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource2, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: 20,
                containers: 'paginator2',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                alwaysVisible: false,
                template: "{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}"
            })

            ,
            sortedBy: {
                key: "code",
                dir: ""
            },
            dynamicData: true

        }

        );

        this.table2.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
        //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);
        this.table2.subscribe("rowMouseoverEvent", this.table2.onEventHighlightRow);
        this.table2.subscribe("rowMouseoutEvent", this.table2.onEventUnhighlightRow);
        this.table2.subscribe("rowClickEvent", select_staff_from_list);
        this.table2.table_id = tableid;
        this.table2.subscribe("renderEvent", myrenderEvent);


        this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table2.filter = {
            key: 'code',
            value: ''
        };




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
        request = "ar_history.php?tipo=purchase_order_history&parent=porder&parent_key=" + Dom.get('po_key').value + "&sf=0&tableid=" + tableid

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
                rowsPerPage: state.porder.history.nr,
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
                key: state.porder.history.order,
                dir: state.porder.history.order_dir
            },
            dynamicData: true

        }

        );

        this.table3.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;

        this.table3.filter = {
            key: state.porder.history.f_field,
            value: state.porder.history.f_value
        };
        this.table3.subscribe("cellMouseoverEvent", highlightEditableCell);
        this.table3.subscribe("cellMouseoutEvent", unhighlightEditableCell);
        this.table3.subscribe("cellClickEvent", onCellClick);
        this.table3.table_id = tableid;
        this.table3.subscribe("renderEvent", myrenderEvent);




        var tableid = 6;
        var tableDivEL = "table" + tableid;
        var ColumnDefs = [{
            key: "code",
            label: labels.Code,
            width: 25,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "name",
            label: labels.Name,
            width: 150,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "transport_method",
            label: labels.Transport_type,
            width: 100,
            sortable: false
        }

        ];
        request = "ar_quick_tables.php?tipo=incoterm_list&tableid=" + tableid + "&nr=20&sf=0"

        this.dataSource6 = new YAHOO.util.DataSource(request);
        this.dataSource6.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource6.connXhrMode = "queueRequests";
        this.dataSource6.table_id = tableid;

        this.dataSource6.responseSchema = {
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


            fields: ["name", 'code', 'transport_method']
        };

        this.table6 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource6, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: 20,
                containers: 'paginator6',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                alwaysVisible: false,
                template: "{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}"
            })

            ,
            sortedBy: {
                key: "code",
                dir: ""
            },
            dynamicData: true

        }

        );

        this.table6.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table6.doBeforeSortColumn = mydoBeforeSortColumn;
        //this.table6.subscribe("cellClickEvent", this.table6.onEventShowCellEditor);
        this.table6.subscribe("rowMouseoverEvent", this.table6.onEventHighlightRow);
        this.table6.subscribe("rowMouseoutEvent", this.table6.onEventUnhighlightRow);
        this.table6.subscribe("rowClickEvent", change_incoterm);



        this.table6.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table6.filter = {
            key: 'code',
            value: ''
        };



    }
});

function show_other_staff() {

    region1 = Dom.getRegion('submit_dialog');
    region2 = Dom.getRegion('dialog_other_staff');
    region3 = Dom.getRegion('get_submiter');
    var pos = [region1.right - region2.width, region3.bottom]

    Dom.setXY('dialog_other_staff', pos);

    dialog_other_staff.show();



}

function select_staff_from_list(oArgs) {
    var staff_name = tables.table2.getRecord(oArgs.target).getData('name');
    var staff_key = tables.table2.getRecord(oArgs.target).getData('key');

    Dom.get('submitted_by').value = staff_key
    Dom.get('submited_by_alias').innerHTML = staff_name
    staff_dialog.hide();
    dialog_other_staff.hide();

}


function show_staff_dialog() {

    if (Dom.get('number_buyers').value > 0) {

        region1 = Dom.getRegion('submit_dialog');
        region2 = Dom.getRegion('staff_dialog');
        region3 = Dom.getRegion('get_submiter');
        var pos = [region1.right - region2.width, region3.bottom]

        Dom.setXY('staff_dialog', pos);

        staff_dialog.show();
    } else {
        show_other_staff()
    }
}


function show_submit_dialog() {

    if (Dom.hasClass('submit_po', 'disabled')) {
        return;
    }

    region1 = Dom.getRegion('submit_po');

    region2 = Dom.getRegion('submit_dialog');
    var pos = [region1.right - region2.width, region1.top]

    Dom.setXY('submit_dialog', pos);
    submit_dialog.show()

}

function show_edit_incoterm_dialog() {
    region1 = Dom.getRegion('edit_incoterm');

    region2 = Dom.getRegion('edit_incoterm_dialog');
    var pos = [region1.left - region2.width, region1.top - 4]

    Dom.setXY('edit_incoterm_dialog', pos);
    edit_incoterm_dialog.show()

}


function highlight_edit_incoterm() {
    Dom.addClass('incoterm_data', 'highlight_edit_area')
}

function unhighlight_edit_incoterm() {
    Dom.removeClass('incoterm_data', 'highlight_edit_area')
}

function show_order_details() {
    Dom.setStyle('show_order_details', 'display', 'none')
    Dom.setStyle('order_details_panel', 'display', '')

}






function show_sticky_note_for_supplier(o) {
    region1 = Dom.getRegion(o);
    region2 = Dom.getRegion('dialog_sticky_note_for_supplier');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_sticky_note_for_supplier', pos);
    dialog_sticky_note_for_supplier.show()

    var potfk = o.getAttribute('potfk')

    Dom.get('sticky_note_for_supplier_potfk').value = potfk;

    if (Dom.get('note_' + potfk) != undefined) {
        Dom.get('sticky_note_for_supplier_input').value = Dom.get('note_' + potfk).innerHTML

    } else {
        Dom.get('sticky_note_for_supplier_input').value = '';
    }
    Dom.get('sticky_note_for_supplier_input').focus();

}

function close_dialog_sticky_note_for_supplier() {
    dialog_sticky_note_for_supplier.hide();
    //Dom.get('sticky_note_for_supplier_input').value = Dom.get('sticky_note_for_supplier_content').innerHTML;
}


function change_note_lock(o, value) {
    var potfk = o.getAttribute('potfk')
    var request = 'ar_edit_porders.php?tipo=edit_sticky_note_for_supplier_lock&parent=potf&parent_key=' + potfk + '&value=' + value
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);



            if (r.state == 200) {

                if (r.newvalue == 'open') {
                    Dom.setStyle(['note_locked_' + r.potfk], 'display', 'none')
                    Dom.setStyle(['note_open_' + r.potfk, ], 'display', '')

                    if (Dom.get('note_' + r.potfk).innerHTML == '') {
                        Dom.setStyle('note_to_supplier_' + r.potfk, 'display', 'none')
                        Dom.setStyle('add_note_to_supplier_' + r.potfk, 'display', '')
                    }

                } else {
                    Dom.setStyle(['note_locked_' + r.potfk], 'display', '')
                    Dom.setStyle(['note_open_' + r.potfk, ], 'display', 'none')
                }




                close_dialog_sticky_note_for_supplier();




            } else {
                alert(r.msg);
            }
        }
    });

}

function save_sticky_note_for_supplier() {

    var potfk = Dom.get('sticky_note_for_supplier_potfk').value
    var request = 'ar_edit_porders.php?tipo=edit_sticky_note_for_supplier&parent=potf&parent_key=' + potfk + '&note=' + my_encodeURIComponent(Dom.get('sticky_note_for_supplier_input').value)

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);



            if (r.state == 200) {

                Dom.get('note_' + r.potfk).innerHTML = r.newvalue;

                if (r.locked == 'Yes') {

                    Dom.setStyle(['note_locked_' + r.potfk, 'note_to_supplier_' + r.potfk], 'display', '')
                    Dom.setStyle(['note_open_' + r.potfk, 'add_note_to_supplier_' + r.potfk], 'display', 'none')
                } else {

                    Dom.setStyle(['note_locked_' + r.potfk], 'display', 'none')
                    Dom.setStyle(['note_open_' + r.potfk, ], 'display', '')

                    if (r.newvalue == '') {
                        Dom.setStyle('note_to_supplier_' + r.potfk, 'display', 'none')
                        Dom.setStyle('add_note_to_supplier_' + r.potfk, 'display', '')
                    }

                }




                close_dialog_sticky_note_for_supplier();




            } else Dom.get(tipo + '_msg').innerHTML = r.msg;

        }
    });
}


function init_in_process() {


    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;

    init_search('supplier_products_supplier');

    submit_dialog = new YAHOO.widget.Dialog("submit_dialog", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    submit_dialog.render();
    staff_dialog = new YAHOO.widget.Dialog("staff_dialog", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    staff_dialog.render();

    dialog_other_staff = new YAHOO.widget.Dialog("dialog_other_staff", {
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    dialog_other_staff.render();

    delete_dialog = new YAHOO.widget.Dialog("delete_dialog", {
        context: ["delete_po", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    delete_dialog.render();



    Event.addListener("submit_po", "click", show_submit_dialog);
    Event.addListener("get_submiter", "click", show_staff_dialog);
    Event.addListener("delete_po", "click", delete_dialog.show, delete_dialog, true);

    var ids = Dom.getElementsByClassName('radio', 'span', 'submit_method_container');
    YAHOO.util.Event.addListener(ids, "click", swap_radio, 'submit_method');

    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;


    cal1 = new YAHOO.widget.Calendar("cal1", "cal1Container", {
        title: labels.Choose_a_date,
        close: true
    });
    cal1.update = updateCal;
    cal1.id = '1';
    cal1.render();
    cal1.update();
    cal1.selectEvent.subscribe(handleSelect, cal1, true);



    YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);



    Event.addListener("ordered_products", "click", show_only_ordered_products);
    Event.addListener("all_products", "click", show_all_products);



    dialog_sticky_note_for_supplier = new YAHOO.widget.Dialog("dialog_sticky_note_for_supplier", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_sticky_note_for_supplier.render();



}

function validate_port_export(query) {
    validate_general('incoterm', 'port_export', unescape(query));
}

function validate_port_import(query) {
    validate_general('incoterm', 'port_import', unescape(query));
}

function validate_tc(query) {
    validate_general('terms_and_conditions', 'terms_and_conditions', unescape(query));
}

function save_edit_incoterm() {
    save_edit_general_bulk('incoterm');
}

function save_edit_tc() {
    save_edit_general_bulk('terms_and_conditions');
}

function reset_edit_incoterm() {
    reset_edit_general('incoterm')
    incoterm = Dom.get('Purchase_Order_Incoterm').getAttribute('ovalue')
    incoterm_formated = Dom.get('Purchase_Order_Incoterm').getAttribute('ovalue_formated')

    Dom.get('Purchase_Order_Incoterm_formated').innerHTML = incoterm_formated

    if (incoterm == '') {

        Dom.setStyle(['update_Purchase_Order_Incoterm', 'delete_Purchase_Order_Incoterm'], 'display', 'none')
        Dom.setStyle('set_Purchase_Order_Incoterm', 'display', '')



    } else {
        Dom.setStyle(['update_Purchase_Order_Incoterm', 'delete_Purchase_Order_Incoterm'], 'display', '')
        Dom.setStyle('set_Purchase_Order_Incoterm', 'display', 'none')
    }

    edit_incoterm_dialog.hide();

}


function save_edit_terms_and_conditions() {
    save_edit_general_bulk('terms_and_conditions');
}

function reset_edit_terms_and_conditions() {
    reset_edit_general('terms_and_conditions');
    Dom.setStyle('edit_tc', 'display', 'none')
    Dom.setStyle('terms_and_conditions_tr', 'display', '')

}

function show_edit_tc() {
    Dom.removeClass('reset_edit_terms_and_conditions', 'disabled')
    Dom.setStyle('edit_tc', 'display', '')
    Dom.setStyle('terms_and_conditions_tr', 'display', 'none')
}

function delete_incoterm() {

    Dom.get('Purchase_Order_Incoterm_formated').innerHTML = ''
    Dom.setStyle(['update_Purchase_Order_Incoterm', 'delete_Purchase_Order_Incoterm'], 'display', 'none')
    Dom.setStyle('set_Purchase_Order_Incoterm', 'display', '')

    value = '';

    validate_scope_data['incoterm']['incoterm']['value'] = value;

    Dom.get('Purchase_Order_Incoterm').value = value
    ovalue = Dom.get('Purchase_Order_Incoterm').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['incoterm']['incoterm']['changed'] = true;
    } else {
        validate_scope_data['incoterm']['incoterm']['changed'] = false;
    }
    validate_scope('incoterm')

}

function show_dialog_incoterm_list(e) {

    region1 = Dom.getRegion(this);
    region2 = Dom.getRegion('dialog_incoterm_list');
    var pos = [region1.right + 5, region1.top - 120]
    Dom.setXY('dialog_incoterm_list', pos);
    dialog_incoterm_list.show()

}

function post_bulk_save_actions(branch) {

    if (branch == 'incoterm') {
        setTimeout(function() {
            edit_incoterm_dialog.hide()
        }, 400);
    } else if (branch == 'terms_and_conditions') {
        Dom.setStyle('edit_tc', 'display', 'none')
        Dom.setStyle('terms_and_conditions_tr', 'display', '')
    }
}

function post_item_updated_actions(branch, r) {

    if (branch == 'incoterm') {

        switch (r.key) {
        case 'incoterm':
            Dom.get('incoterm').innerHTML = r.newvalue
            break;
        case 'port_export':
            Dom.get('export_port').innerHTML = r.newvalue
            break;
        case 'port_import':
            Dom.get('import_port').innerHTML = r.newvalue

            break;
        default:

        }

    } else if (branch == 'terms_and_conditions') {
        switch (r.key) {
        case 'terms_and_conditions':
            Dom.get('terms_and_conditions_formated').innerHTML = r.newvalue
            break;

        default:
        }
    }
}

function change_incoterm(oArgs) {
    var incoterm = tables.table6.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');

    Dom.get('Purchase_Order_Incoterm_formated').innerHTML = incoterm
    Dom.setStyle(['update_Purchase_Order_Incoterm', 'delete_Purchase_Order_Incoterm'], 'display', '')
    Dom.setStyle('set_Purchase_Order_Incoterm', 'display', 'none')

    value = incoterm;
    validate_scope_data['incoterm']['incoterm']['value'] = value;



    Dom.get('Purchase_Order_Incoterm').value = value
    ovalue = Dom.get('Purchase_Order_Incoterm').getAttribute('ovalue');
    if (ovalue != value) {
        validate_scope_data['incoterm']['incoterm']['changed'] = true;
    } else {
        validate_scope_data['incoterm']['incoterm']['changed'] = false;
    }

    validate_scope('incoterm')

    dialog_incoterm_list.hide();

}

function init_edit_po() {

    validate_scope_data = {

        'incoterm': {

            'incoterm': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'dbname': 'Purchase Order Incoterm',
                'name': 'Purchase_Order_Incoterm',
                'ar': false,
                'validation': false

            },

            'port_import': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'dbname': 'Purchase Order Port of Import',
                'name': 'Purchase_Order_Port_of_Import',
                'validation': [{
                    'regexp': "[a-z\d]*",
                    'invalid_msg': ''
                }]
            },
            'port_export': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'dbname': 'Purchase Order Port of Export',
                'name': 'Purchase_Order_Port_of_Export',
                'validation': [{
                    'regexp': "[a-z\d]*",
                    'invalid_msg': ''
                }]
            }

        },
        'terms_and_conditions': {
            'terms_and_conditions': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'dbname': 'Purchase Order Terms and Conditions',
                'name': 'terms_and_conditions',
                'validation': [{
                    'regexp': "[a-z\d]*",
                    'invalid_msg': ''
                }]
            }
        }

    };


    validate_scope_metadata = {
        'incoterm': {
            'type': 'edit',
            'ar_file': 'ar_edit_porders.php',
            'key_name': 'po_key',
            'key': Dom.get('po_key').value
        },
        'terms_and_conditions': {
            'type': 'edit',
            'ar_file': 'ar_edit_porders.php',
            'key_name': 'po_key',
            'key': Dom.get('po_key').value,
            'dont_disable_reset_if_no_change': true
        }
    };

    Event.addListener("incoterm_data", "mouseenter", highlight_edit_incoterm);
    Event.addListener("incoterm_data", "mouseleave", unhighlight_edit_incoterm);

    edit_incoterm_dialog = new YAHOO.widget.Dialog("edit_incoterm_dialog", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    edit_incoterm_dialog.render();

    dialog_incoterm_list = new YAHOO.widget.Dialog("dialog_incoterm_list", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_incoterm_list.render();

    Event.addListener("set_Purchase_Order_Incoterm", "click", show_dialog_incoterm_list);
    Event.addListener("update_Purchase_Order_Incoterm", "click", show_dialog_incoterm_list);


    var supplier_port_export_oACDS = new YAHOO.util.FunctionDataSource(validate_port_export);
    supplier_port_export_oACDS.queryMatchContains = true;
    var supplier_port_export_oAutoComp = new YAHOO.widget.AutoComplete("Purchase_Order_Port_of_Export", "Purchase_Order_Port_of_Export_Container", supplier_port_export_oACDS);
    supplier_port_export_oAutoComp.minQueryLength = 0;
    supplier_port_export_oAutoComp.queryDelay = 0.1;

    var supplier_port_import_oACDS = new YAHOO.util.FunctionDataSource(validate_port_import);
    supplier_port_import_oACDS.queryMatchContains = true;
    var supplier_port_import_oAutoComp = new YAHOO.widget.AutoComplete("Purchase_Order_Port_of_Import", "Purchase_Order_Port_of_Import_Container", supplier_port_import_oACDS);
    supplier_port_import_oAutoComp.minQueryLength = 0;
    supplier_port_import_oAutoComp.queryDelay = 0.1;


    var po_tc_oACDS = new YAHOO.util.FunctionDataSource(validate_tc);
    po_tc_oACDS.queryMatchContains = true;
    var po_tc_oAutoComp = new YAHOO.widget.AutoComplete("terms_and_conditions", "terms_and_conditions_Container", po_tc_oACDS);
    po_tc_oAutoComp.minQueryLength = 0;
    po_tc_oAutoComp.queryDelay = 0.1;


    Event.addListener('save_edit_incoterm', "click", save_edit_incoterm);
    Event.addListener('reset_edit_incoterm', "click", reset_edit_incoterm);

    Event.addListener('save_edit_terms_and_conditions', "click", save_edit_terms_and_conditions);
    Event.addListener('reset_edit_terms_and_conditions', "click", reset_edit_terms_and_conditions);


    Event.addListener('clean_table_filter_show6', "click", show_filter, 6);
    Event.addListener('clean_table_filter_hide6', "click", hide_filter, 6);


    Event.addListener('edit_incoterm', "click", show_edit_incoterm_dialog);



}

YAHOO.util.Event.onDOMReady(init_edit_po);

YAHOO.util.Event.onDOMReady(init_in_process);
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
