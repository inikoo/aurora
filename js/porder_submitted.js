var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

var receivers = new Object;
var checkers = new Object;

var active_editor = '';
var receiver_list;
var checker_list;
var submit_dialog;
var staff_dialog;
var cancel_dialog;
var dn_dialog;
var invoice_dialog;






function mark_as_confirmed_save() {

    var request = 'ar_edit_porders.php?tipo=mark_as_confirmed&agreed_date=' + escape(Dom.get('v_calpop_agreed_delivery_date').value) + '&id=' + escape(Dom.get('po_key').value);


    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                Dom.setStyle(['confirmed_date_tr', 'agreed_date_tr', 'invoice_po', 'dn_po', 'sdns_info'], 'display', '')
                Dom.setStyle(['submitted_date_tr', 'confirm'], 'display', 'none')
                Dom.get('po_state').innerHTML = r.po_state
                Dom.get('confirmed_date').innerHTML = r.confirmed_date
                Dom.get('agreed_date').innerHTML = r.agreed_date
                Dom.get('estimated_delivery').innerHTML = r.estimated_delivery

                confirm_dialog.hide()

                table_id = Dom.get('history_table_id').value
                var table = tables['table' + table_id];
                var datasource = tables['dataSource' + table_id];
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);


            } else {
                Dom.get('confirm_msg').innerHTML = r.msg
            }
        }
    });
}



function cancel_order_save() {
    var note = Dom.get('cancel_note').value;

    var request = 'ar_edit_porders.php?tipo=cancel&note=' + escape(note) + '&id=' + escape(Dom.get('po_key').value);
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                location.href = 'porder.php?id=' + Dom.get('po_key').value;
            } else alert(r.msg);
        }
    });
}



function back_to_in_process() {

    Dom.get('back_to_in_process_icon').src = "art/loading.gif"


    var request = 'ar_edit_porders.php?tipo=back_to_process&id=' + escape(Dom.get('po_key').value);

    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                location.href = 'porder.php?id=' + Dom.get('po_key').value;

            } else alert(r.msg);
        }
    });
}






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
            }

        },


        {
            key: "amount",
            label: labels.Net_Cost,
            width: 100,
            className: "aright"
        }

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




        this.table0.filter = {
            key: state.porder.products.f_field,
            value: state.porder.products.f_value
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




    }
});



function submit_date_manually() {
    Dom.get('tr_manual_submit_date').style.display = "none";
    Dom.get('tbody_manual_submit_date').style.display = "";
    Dom.get('date_type').value = 'manual';
}




function match_to_dn_save() {
    var number = Dom.get('dn_number').value;
    var input_sdn = Dom.get('input_sdn').value;
        var receive = Dom.get('mark_as_received').value;

     var receiver_key = Dom.get('received_by').value;
    var location_key = Dom.get('location_key').value;

    
    if (number == '') {
        Dom.get('dn_dialog_msg').innerHTML = labels.SDN_number_required;
        return;
    } else {
        Dom.get('dn_dialog_msg').innerHTML = '';
    }


    Dom.setStyle('wait_match_to_dn', 'display', '');
    Dom.setStyle('match_to_dn_save', 'display', 'none');


  
   




    var request = 'ar_edit_porders.php?tipo=create_sdn_from_po&number=' + encodeURIComponent(number) 
    + '&input=' + escape(input_sdn) 
     + '&receive=' + escape(receive) 
       + '&receiver_key=' + escape(receiver_key) 
         + '&location_key=' + escape(location_key) 
    + '&po_key=' + escape(Dom.get('po_key').value);

    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
           // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {


                location.href = r.redirect

            } else alert(r.msg);
        }
    });

}



function show_agreed_estimated_delivery_date_dialog() {
    region1 = Dom.getRegion('edit_agreed_estimated_delivery_date');
    region2 = Dom.getRegion('edit_agreed_estimated_delivery_date_dialog');
    var pos = [region1.left, region1.bottom + 5]
    Dom.setXY('edit_agreed_estimated_delivery_date_dialog', pos);
    edit_agreed_estimated_delivery_date_dialog.show()
}

function show_agreed_delivery_date_dialog_calendar() {
    Dom.get('confirm_msg').innerHTML = '';

    cal3.show()
    Dom.setStyle('agreed_delivery_date_Container', 'z-index', 10000)
    region1 = Dom.getRegion('v_calpop_agreed_delivery_date');
    region2 = Dom.getRegion('agreed_delivery_date_Container');

    var pos = [region1.left - region2.width, region1.top]
    Dom.setXY('agreed_delivery_date_Container', pos);

}


function show_confirm_dialog() {
    region1 = Dom.getRegion('confirm');
    region2 = Dom.getRegion('confirm_dialog');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('confirm_dialog', pos);
    Dom.get('confirm_msg').innerHTML = '';
    confirm_dialog.show()
}

function show_dn_dialog() {
    region1 = Dom.getRegion('dn_po');
    region2 = Dom.getRegion('dn_dialog');
    var pos = [region1.left, region1.bottom]
    Dom.setXY('dn_dialog', pos);

    dn_dialog.show()
    Dom.get('dn_number').focus()
}

function change_input_sdn() {
    if (this.id == 'input_sdn_No') {
    if( Dom.get('mark_as_received').value == 'Yes')
    return;
        Dom.setStyle('input_sdn_No', 'display', 'none')
        Dom.setStyle('input_sdn_Yes', 'display', '')
        Dom.get('input_sdn').value = 'No';
    } else {
        Dom.setStyle('input_sdn_No', 'display', '')
        Dom.setStyle('input_sdn_Yes', 'display', 'none')
        Dom.get('input_sdn').value = 'Yes';
    }

}
function change_mark_as_received(){

  if (this.id == 'mark_as_received_No') {
  
  
        Dom.setStyle(['mark_as_received_No','receiving_fields'], 'display', 'none')
        Dom.setStyle('mark_as_received_Yes', 'display', '')
        Dom.get('mark_as_received').value = 'No';
    } else {
    
      Dom.setStyle('input_sdn_No', 'display', '')
        Dom.setStyle('input_sdn_Yes', 'display', 'none')
        Dom.get('input_sdn').value = 'Yes';
    
        Dom.setStyle(['mark_as_received_No','receiving_fields'], 'display', '')
        Dom.setStyle('mark_as_received_Yes', 'display', 'none')
        Dom.get('mark_as_received').value = 'Yes';
    }

}



function show_location_dialog() {

    region1 = Dom.getRegion('get_location');
    region2 = Dom.getRegion('received_dialog');
    var pos = [region1.left - region2.width, region1.top]
    Dom.setXY('location_dialog', pos);

    location_dialog.show()

}

function show_staff_dialog(e, parent) {

    Dom.get('staff_list_parent_dialog').value = parent

    if (parent == 'receiver') {
        region1 = Dom.getRegion('get_receiver');

    } else if (parent == 'submitter') {
        region1 = Dom.getRegion('get_receiver');

    }


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


function init() {



    init_search('supplier_products_supplier');



    cancel_dialog = new YAHOO.widget.Dialog("cancel_dialog", {
        context: ["cancel_po", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    cancel_dialog.render();
    Event.addListener("cancel_po", "click", cancel_dialog.show, cancel_dialog, true);
    //alert('x');
    //YAHOO.util.Event.addListener('show_all', "click",change_show_all);
    submit_dialog = new YAHOO.widget.Dialog("submit_dialog", {
        context: ["submit_po", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    submit_dialog.render();

    staff_dialog = new YAHOO.widget.Dialog("staff_dialog", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    staff_dialog.render();

    dn_dialog = new YAHOO.widget.Dialog("dn_dialog", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dn_dialog.render();

    confirm_dialog = new YAHOO.widget.Dialog("confirm_dialog", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    confirm_dialog.render();
    
    
    location_dialog = new YAHOO.widget.Dialog("location_dialog", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    location_dialog.render();
    

    Event.addListener("dn_po", "click", show_dn_dialog);

    Event.addListener("confirm", "click", show_confirm_dialog);





    Event.addListener("get_canceller", "click", staff_dialog.show, staff_dialog, true);
    //  alert('x');
    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;

    cal1 = new YAHOO.widget.Calendar("cal1", "cal1Container", {
        title: "",
        close: true
    });
    cal1.update = updateCal;
    cal1.id = '1';
    cal1.render();
    cal1.update();
    cal1.selectEvent.subscribe(handleSelect, cal1, true);
    Event.addListener("calpop1", "click", cal1.show, cal1, true);


    cal3 = new YAHOO.widget.Calendar("cal3", "agreed_delivery_date_Container", {
        close: true
    });

    cal3.update = updateCal;
    cal3.id = '_agreed_delivery_date';
    cal3.render();
    cal3.update();
    cal3.selectEvent.subscribe(handleSelect, cal3, true);
    YAHOO.util.Event.addListener("agreed_delivery_date_pop", "click", show_agreed_delivery_date_dialog_calendar);


    Event.addListener("back_to_in_process", "click", back_to_in_process);

    Event.addListener(['input_sdn_No', 'input_sdn_Yes'], "click", change_input_sdn);
    Event.addListener(['mark_as_received_Yes', 'mark_as_received_No'], "click", change_mark_as_received);




    Event.addListener(['get_receiver','received_by_alias'], "click", show_staff_dialog, 'receiver');
    Event.addListener(['get_location','location_code'], "click", show_location_dialog);


}



YAHOO.util.Event.onDOMReady(init);
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
