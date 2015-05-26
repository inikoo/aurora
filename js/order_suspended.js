


Event.addListener(window, "load", function() {

    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;
    state = session_data.state;

    var itemsRowFormatter = function(elTr, oRecord) {
            if (oRecord.getData('class') == 'first') {

                Dom.addClass(elTr, 'first');
            }
            return true;
        };

    tables = new function() {






        var tableid = 0; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;
        var OrdersColumnDefs = [{
            key: "code",
            label: labels.Code,
            width: 70,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "description",
            label: labels.Description,
            sortable: false,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "quantity",
            label: labels.Qty,
            width: 45,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "gross",
            label: labels.Gross,
            width: 60,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "discount",
            label: labels.Discounts,
            width: 60,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "net",
            label: labels.Net,
            width: 60,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "tax",
            label: labels.Tax,
            width: 60,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "total",
            label: labels.Amount,
            width: 60,
            sortable: false,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }

        }];
        request = "ar_orders.php?tipo=transactions&parent=order_cancelled&parent_key=" + Dom.get('order_key').value + "&tableid=0"
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

            fields: ["code", "description", "quantity", "discount", "to_charge", "gross", "tariff_code", "created", "last_updated", 'tax', 'net', 'class', 'total'
            // "promotion_id",
            ]
        };

        this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs, this.dataSource0, {
            formatRow: itemsRowFormatter,
            draggableColumns: true,
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.order_cancelled.items.nr,
                containers: 'paginator0',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
            })

            ,
            sortedBy: {
                key: state.order_cancelled.items.order,
                dir: state.order_cancelled.items.order_dir
            },
            dynamicData: true

        });
        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

        this.table0.request = request;
        this.table0.table_id = tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);



        this.table0.filter = {
            key: state.order_cancelled.items.order,
            dir: state.order_cancelled.items.order_dir
        };



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
        //alert('R:'+request);
        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {
                //alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {

                    window.location.reload();
                } else {
                    alert(r.msg)
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

function init(){

init_search('orders_store');
   dialog_cancel = new YAHOO.widget.Dialog("dialog_cancel", {
        context: ["cancel", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_cancel.render();
    YAHOO.util.Event.addListener("cancel", "click", open_cancel_dialog);
    

   

}

YAHOO.util.Event.onDOMReady(init);
