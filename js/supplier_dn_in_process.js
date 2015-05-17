var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

var receivers = new Object;
var checkers = new Object;

var active_editor = '';
var receiver_list;
var checker_list;
var received_dialog;
var staff_dialog;
var delete_dialog;





function myCellEdit(callback, newValue) {
    var record = this.getRecord(),
        column = this.getColumn(),
        oldValue = this.value,
        datatable = this.getDataTable();
    recordIndex = datatable.getRecordIndex(record);

    ar_file = 'ar_edit_porders.php';




    var request = 'tipo=edit_' + column.object + '&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + myBuildUrl(datatable, record) + '&supplier_delivery_note_key=' + Dom.get('supplier_delivery_note_key').value;


    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //   alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {


                for (x in r.data) {
                    if (Dom.get(x) != undefined) Dom.get(x).innerHTML = r.data[x];
                }



                //if(r.quantity==0 && !show_all){
                //    datatable.deleteRow(record);
                //}
                callback(true, r.quantity);
            } else {
                //alert(r.msg);
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




function myonCellClick(oArgs) {


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
    case ('match_po'):

        var data = record.getData();

        if (column.action == 'match_po') {
            var new_qty = parseFloat(data['quantity']);
        } else if (column.action == 'add_object') {
            var new_qty = parseFloat(data['dn_quantity']) + 1;
        } else {
            if (data['dn_quantity'] == 0) {
                return;
            }

            var new_qty = parseFloat(data['dn_quantity']) - 1;
        }
        var ar_file = 'ar_edit_porders.php';
        request = 'tipo=edit_new_supplier_dn&key=quantity&newvalue=' + new_qty + '&oldvalue=' + data['quantity'] + '&id=' + data['id'] + '&supplier_delivery_note_key=' + Dom.get('supplier_delivery_note_key').value + '&sp_key=' + data["sp_key"];
        //alert(request)
        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {
                //alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {
                    for (x in r.data) {


                        if (Dom.get(x) != undefined) Dom.get(x).innerHTML = r.data[x];
                    }



                    datatable.updateCell(record, 'dn_quantity', r.quantity);


                    //if(r.quantity==0 && !show_all){
                    //	this.deleteRow(target);
                    // }
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






function delete_supplier_dn() {
    var request = 'ar_edit_porders.php?tipo=delete_dn&id=' + Dom.get('supplier_delivery_note_key').value;
    // alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                location.href = 'supplier.php?id=' + Dom.get('supplier_key').value;
            } else {
                Dom.get('delete_dialog_msg').innerHTML = r.msg;
            }
        }
    });

}







var input_order_save = function(o) {

        Dom.get('save_inputted_dn_icon').src = 'art/loading.gif'

        var request = 'ar_edit_porders.php?tipo=input_dn&id=' + escape(Dom.get('supplier_delivery_note_key').value);

        YAHOO.util.Connect.asyncRequest('POST', request, {

            success: function(o) {
                //alert(o.responseText)
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {

                    location.href = 'supplier_dn.php?id=' + Dom.get('supplier_delivery_note_key').value;



                } else alert(r.msg);
            }
        });
    };



function received_order_save(o) {

    var received_date = Dom.get('v_calpop1').value;
    var received_time = Dom.get('v_time').value;
    var staff_key = Dom.get('received_by').value;
    var date_type = Dom.get('date_type').value;
    var location_key = Dom.get('location_key').value;


    var request = 'ar_edit_porders.php?tipo=receive_dn&id=' + escape(Dom.get('supplier_delivery_note_key').value) + '&date_type=' + escape(date_type) + '&staff_key=' + escape(staff_key) + '&received_date=' + escape(received_date) + '&received_time=' + escape(received_time) + '&location_key=' + escape(location_key);

    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //alert(o.responseText);

            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                location.href = 'supplier_dn.php?id=' + Dom.get('supplier_delivery_note_key').value;



            } else alert(r.msg);
        }
    });
};





YAHOO.util.Event.addListener(window, "load", function() {

    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;
    state = session_data.state;


    tables = new function() {

        var tableid = 0;
        var tableDivEL = "table" + tableid;
        var ColumnDefs = [{
            key: "id",
            label: "",
            width: 20,
            sortable: false,
            isPrimaryKey: true,
            hidden: true
        }, {
            key: "sp_key",
            label: "",
            width: 20,
            sortable: false,
            isPrimaryKey: true,
            hidden: true
        }

        , {
            key: "code",
            label: labels.Code,
            width: 60,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }

        , {
            key: "description",
            label: labels.Description,
            width: 400,
            sortable: false,
            className: "aleft"
        }, {
            key: "parts",
            hidden: true,
            label: labels.Parts,
            width: 200,
            sortable: false,
            className: "aleft"
        }

        , {
            key: "quantity",
            label: labels.PO_Qty,
            width: 60,
            sortable: false,
            className: "aright",
            action: 'match_po',
            object: 'new_order'
        }
        // ,{key:"stock", label:labels.Stock O(U),width:90,className:"aright"}
        // ,{key:"stock_time", label:labels.Stock Time,width:75,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
        // ,{key:"expected_qty_edit", label:labels.Qty O[U],width:70,className:"aright"}
        // ,{key:"expected_qty", label:labels.Qty O[U],width:100,className:"aright"}
        //,{            key: "unit_type", label: labels.PO_U,width: 30,className: "aleft"}
        ,
        {
            key: "dn_quantity",
            label: labels.DN_Qty,
            width: 60,
            sortable: false,
            className: "aright",
            editor: new YAHOO.widget.TextboxCellEditor({
                asyncSubmitter: myCellEdit
            }),
            object: 'new_supplier_dn',
            'action': 'change_qty'
        }
        //  , { key: "dn_unit_type",label: labels.DN_U,width: 30,className: "aleft"}
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
        }


        // ,{key:"amount", label:labels.Net Cost,width:50,className:"aright"}
        // ,{key:"qty_edit", label:labels.Qty [U],width:50,className:"aright",hidden:true}
        // ,{key:"diff", label:labels.&Delta;U,width:40,className:"aright",hidden:true}
        //,{key:"damaged_edit", label:labels.Damaged,width:60,className:"aright",hidden:true}
        //,{key:"damaged", label:labels.Damaged,width:60,className:"aright"}
        //,{key:"usable", label:labels.In O[U],width:55,className:"aright"}
        ];
        request = "ar_edit_porders.php?tipo=dn_transactions_to_process&supplier_dn_key=" + Dom.get('supplier_delivery_note_key').value + "&tableid=" + tableid + '&pos=' + Dom.get('po_keys').value
        //alert(request)
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

            fields: ["id", "code", "description", "quantity", "amount", "unit_type", "add", "remove", "used_in", "dn_quantity", "dn_unit_type", "sp_key"]
        };

        this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource0, {
            //draggableColumns:true,
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.supplier_dn.products.nr,
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
                key: state.supplier_dn.products.order,
                dir: state.supplier_dn.products.order_dir
            },
            dynamicData: true

        });
        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
        this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
        this.table0.subscribe("cellClickEvent", myonCellClick);




        this.table0.filter = {
            key: state.supplier_dn.products.f_field,
            value: state.supplier_dn.products.f_value
        };


     var tableid = 1;
        var tableDivEL = "table" + tableid;


        var ColumnDefs = [{
            key: "key",
            label: "",
            width: 100,
            hidden: true
        }, {
            key: "code",
            label: labels.Code,
            width: 100,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
           
           }, {
            key: "used_for",
            label: labels.Used_for,
            width: 300,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            } 
            
        }

        ];
        
        request="ar_quick_tables.php?tipo=location_list&warehouse_key="+Dom.get('warehouse_key').value+"&tableid=" + tableid + "&nr=20&sf=0"
       // alert(request)
        this.dataSource1 = new YAHOO.util.DataSource(request);
        //alert("ar_quick_tables.php?tipo=active_staff_list&active=Yes&tableid="+tableid+"&nr=20&sf=0");
        this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource1.connXhrMode = "queueRequests";
        this.dataSource1.table_id = tableid;

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
                totalRecords: "resultset.total_records" // Access to value in the server response
            },


            fields: ["code", 'used_for', 'key']
        };

        this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource1, {
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

        this.table1.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
        //this.table1.subscribe("cellClickEvent", this.table1.onEventShowCellEditor);
        this.table1.subscribe("rowMouseoverEvent", this.table1.onEventHighlightRow);
        this.table1.subscribe("rowMouseoutEvent", this.table1.onEventUnhighlightRow);
        this.table1.subscribe("rowClickEvent", select_location_from_list);
        this.table1.table_id = tableid;
        this.table1.subscribe("renderEvent", myrenderEvent);


        this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table1.filter = {
            key: 'code',
            value: ''
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
        request = "ar_history.php?tipo=supplier_dn_history&parent=supplier_dn&parent_key=" + Dom.get('supplier_delivery_note_key').value + "&sf=0&tableid=" + tableid

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
                rowsPerPage: state.supplier_dn.history.nr,
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
                key: state.supplier_dn.history.order,
                dir: state.supplier_dn.history.order_dir
            },
            dynamicData: true

        }

        );

        this.table3.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;

        this.table3.filter = {
            key: state.supplier_dn.history.f_field,
            value: state.supplier_dn.history.f_value
        };
        this.table3.subscribe("cellMouseoverEvent", highlightEditableCell);
        this.table3.subscribe("cellMouseoutEvent", unhighlightEditableCell);
        this.table3.subscribe("cellClickEvent", onCellClick);
        this.table3.table_id = tableid;
        this.table3.subscribe("renderEvent", myrenderEvent);





    }
});





function take_values_from_pos() {

    var ar_file = 'ar_edit_porders.php';
    request = 'tipo=take_values_from_pos&dn_key=' + Dom.get('supplier_delivery_note_key').value;
    //	alert(ar_file+'?'+request)
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                var tableid = 0;
                var table = tables['table' + tableid];

                var datasource = tables['dataSource' + tableid];
                table.filter.value = Dom.get('f_input' + tableid).value;
                var request = '&show_all=no';
                datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

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


function show_mark_as_received() {
    region1 = Dom.getRegion('mark_as_received');
    region2 = Dom.getRegion('received_dialog');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('received_dialog', pos);
    received_dialog.show()
}

function show_location_dialog() {

    region1 = Dom.getRegion('get_location');
    region2 = Dom.getRegion('received_dialog');
    var pos = [region1.left - region2.width, region1.top]
    Dom.setXY('location_dialog', pos);

    location_dialog.show()

}

function show_staff_dialog() {

    region1 = Dom.getRegion('get_receiver');
    region2 = Dom.getRegion('received_dialog');
    var pos = [region1.left - region2.width, region1.top]
    Dom.setXY('staff_dialog', pos);

    staff_dialog.show()

}


function select_staff_from_list(oArgs) {
    var staff_name = tables.table2.getRecord(oArgs.target).getData('name');
    var staff_key = tables.table2.getRecord(oArgs.target).getData('key');
   select_staff(staff_key, staff_name)
}

function select_staff_from_button(o) {
    var staff_key = o.getAttribute('staff_key')
    var staff_name = o.innerHTML
    select_staff(staff_key, staff_name)
}

function select_staff(staff_key, staff_name) {
    Dom.get('received_by_alias').innerHTML = staff_name
    Dom.get('received_by').value = staff_key
    staff_dialog.hide()
}

function select_location_from_list(oArgs) {
    var location_code = tables.table1.getRecord(oArgs.target).getData('code');
    var location_key = tables.table1.getRecord(oArgs.target).getData('key');
   select_location(location_key, location_code)
}

function select_location_from_button(o) {
    var location_key = o.getAttribute('location_key')
    var location_code = o.innerHTML
    select_location(location_key, location_code)
}

function select_location(location_key, location_code) {
    Dom.get('location_code').innerHTML = location_code
    Dom.get('location_key').value = location_key
    location_dialog.hide()
}


function init_in_process() {

    //take_values_from_pos();
    // Event.addListener("take_values_from_pos", "click", take_values_from_pos);
    Event.addListener("save_inputted_dn", "click", input_order_save);


    Event.addListener("ordered_products", "click", show_only_ordered_products);
    Event.addListener("all_products", "click", show_all_products);


    delete_dialog = new YAHOO.widget.Dialog("delete_dialog", {
        context: ["delete_dn", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    delete_dialog.render();

    Event.addListener("delete_dn", "click", delete_dialog.show, delete_dialog, true);


    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;


    Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);


    received_dialog = new YAHOO.widget.Dialog("received_dialog", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    received_dialog.render();
    staff_dialog = new YAHOO.widget.Dialog("staff_dialog", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    staff_dialog.render();

    location_dialog = new YAHOO.widget.Dialog("location_dialog", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    location_dialog.render();

    Event.addListener("mark_as_received", "click", show_mark_as_received);

    Event.addListener("get_receiver", "click", show_staff_dialog);
    Event.addListener("get_location", "click", show_location_dialog);



}



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
