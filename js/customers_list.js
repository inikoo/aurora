var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;

var customer_views_ids = ['general', 'contact', 'address', 'ship_to_address', 'balance', 'rank', 'weblog'];

function change_block() {
    ids = ['customers', 'deals'];
    block_ids = ['block_customers', 'block_deals'];
    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=customers_list-block_view&value=' + this.id, {
        success: function(o) {

        }

    });
}


YAHOO.util.Event.addListener(window, "load", function() {

    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;
    state = session_data.state;
    tables = new function() {

        var tableid = 0; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;
        var CustomersColumnDefs = [{
            key: "id",
            label: labels.Id,
            width: 45,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "name",
            label: labels.Customer_Name,
            width: 260,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "location",
            label: labels.Location,
            hidden: (state.customers_list.customers.view == 'general' || state.customers_list.customers.view == 'other_value' ? false : true),
            width: 200,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "contact_since",
            label: labels.Since,
            hidden: (state.customers_list.customers.view == 'general' ? false : true),
            sortable: true,
            className: "aright",
            width: 85,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "last_order",
            label: labels.Last_Order,
            hidden: (state.customers_list.customers.view == 'general' ? false : true),
            sortable: true,
            className: "aright",
            width: 85,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "orders",
            label: labels.Orders,
            hidden: (state.customers_list.customers.view == 'general' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "activity",
            label: labels.Status,
            hidden: (state.customers_list.customers.view == 'general' ? false : true),
            sortable: true,
            className: "aright",
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "contact_name",
            label: labels.Contact_Name,
            width: 160,
            hidden: (state.customers_list.customers.view == 'contact' ? false : true),
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "email",
            label: labels.Email,
            width: 210,
            hidden: (state.customers_list.customers.view == 'contact' ? false : true),
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "telephone",
            label: labels.Telephone,
            width: 137,
            hidden: (state.customers_list.customers.view == 'contact' ? false : true),
            sortable: true,
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            className: "aright"
        }, {
            key: "address",
            label: labels.Contact_Address,
            width: 176,
            hidden: (state.customers_list.customers.view == 'address' ? false : true),
            sortable: true,
            className: "aleft"
        }, {
            key: "billing_address",
            label: labels.Billing_Address,
            width: 170,
            hidden: (state.customers_list.customers.view == 'address' ? false : true),
            sortable: true,
            className: "aleft"
        }, {
            key: "delivery_address",
            label: labels.Delivery_Address,
            width: 170,
            hidden: (state.customers_list.customers.view == 'address' ? false : true),
            sortable: true,
            className: "aleft"
        }, {
            key: "total_payments",
            label: labels.Payments,
            width: 99,
            hidden: (state.customers_list.customers.view == 'balance' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "total_refunds",
            label: labels.Refunds,
            width: 90,
            hidden: (state.customers_list.customers.view == 'balance' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "net_balance",
            label: labels.Balance,
            width: 90,
            hidden: (state.customers_list.customers.view == 'balance' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "balance",
            label: labels.Outstanding,
            width: 90,
            hidden: (state.customers_list.customers.view == 'balance' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "total_profit",
            label: labels.Profit,
            width: 90,
            hidden: (state.customers_list.customers.view == 'balance' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "top_orders",
            label: labels.Orders_Rank,
            width: 121,
            hidden: (state.customers_list.customers.view == 'rank' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "top_invoices",
            label: labels.Invoices_Rank,
            width: 121,
            hidden: (state.customers_list.customers.view == 'rank' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "top_balance",
            label: labels.Balance_Rank,
            width: 120,
            hidden: (state.customers_list.customers.view == 'rank' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "top_profits",
            label: labels.Profits_Rank,
            width: 120,
            hidden: (state.customers_list.customers.view == 'rank' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "logins",
            label: labels.Logins,
            width: 120,
            hidden: (state.customers_list.customers.view == 'weblog' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "failed_logins",
            label: labels.Failed_Logis,
            width: 120,
            hidden: (state.customers_list.customers.view == 'weblog' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "requests",
            label: labels.Viewed_Pages,
            width: 120,
            hidden: (state.customers_list.customers.view == 'weblog' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "other_value",
            label: labels.Category_Other_Value,
            width: 300,
            hidden: (state.customers_list.customers.view == 'other_value' ? false : true),
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }


        ];

        request = "ar_contacts.php?tipo=customers&parent=list&sf=0&where=&parent_key=" + Dom.get('customer_list_key').value
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


            fields: ['id', 'logins', 'failed_logins', 'requests', 'name', 'location', 'orders', 'email', 'telephone', 'last_order', 'activity', 'total_payments', 'contact_name', "address", "billing_address", "delivery_address", "total_paymants", "total_refunds", "net_balance", "total_profit", "balance", "contact_since", "top_orders", "top_invoices", "top_balance", "top_profits"]
        };
        //__You shouls not change anything from here
        this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs, this.dataSource0, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.customers_list.customers.nr,
                containers: 'paginator0',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



            })

            ,
            sortedBy: {
                key: state.customers_list.customers.order,
                dir: state.customers_list.customers.order_dir
            },
            dynamicData: true

        }

        );

        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;


        this.table0.subscribe("renderEvent", customers_myrenderEvent);
        this.table0.getDataSource().sendRequest(null, {
            success: function(request, response, payload) {
                if (response.results.length == 0) {

                    get_elements_numbers()
                } else {
                    //this.onDataReturnInitializeTable(request, response, payload);
                }
            },
            scope: this.table0,
            argument: this.table0.getState()
        });

        this.table0.filter = {
            key: state.customers_list.customers.f_field,
            value: state.customers_list.customers.f_value
        };



        var tableid = 4;
        var tableDivEL = "table" + tableid;
        var productsColumnDefs = [

        {
            key: "key",
            label: "",
            width: 20,
            sortable: false,
            isPrimaryKey: true,
            hidden: true
        }

        , {
            key: "code",
            label: labels.Code,
            width: 110,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "description",
            label: labels.Description,
            width: 350,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "duration",
            label: labels.Duration,
            width: 150,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }

        , {
            key: "orders",
            label: labels.Orders,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "customers",
            label: labels.Customers,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }


        ];
        //?tipo=products&tid=0"
        request = "ar_deals.php?tipo=deals&parent=customers_list&sf=0&parent_key=" + Dom.get('customer_list_key').value + '&tableid=' + tableid + '&referrer=customers_list'
        // alert(request);
        this.dataSource4 = new YAHOO.util.DataSource(request);
        this.dataSource4.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource4.connXhrMode = "queueRequests";
        this.dataSource4.responseSchema = {
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

            fields: ["name", "key", "description", "duration", "orders", "code", "customers"]
        };


        this.table4 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs, this.dataSource4, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder
            //,initialLoad:false
            ,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.customers_list.offers.nr,
                containers: 'paginator4',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



            })

            ,
            sortedBy: {
                key: state.customers_list.offers.order,
                dir: state.customers_list.offers.order_dir
            },
            dynamicData: true

        }

        );

        this.table4.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table4.request = request;
        this.table4.table_id = tableid;
        this.table4.subscribe("renderEvent", myrenderEvent);
        this.table4.getDataSource().sendRequest(null, {
            success: function(request, response, payload) {

                this.onDataReturnInitializeTable(request, response, payload);

            },
            scope: this.table4,
            argument: this.table4.getState()
        });

        this.table4.filter = {
            key: state.customers_list.offers.f_field,
            value: state.customers_list.offers.f_value
        };



    };
});

function change_customers_view_save(tipo) {
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=customers_list-customers-view&value=' + escape(tipo), {});

}

function print_labels() {
    window.location = 'customers_address_label.pdf.php?label=l7159&scope=list&id=' + Dom.get('customer_list_key').value
}

function new_deal() {
    location.href = "new_deal.php?parent=customers_list&parent_key=" + Dom.get('customer_list_key').value;
}


function init() {
init_search('customers_store');


    dialog_export['customers'] = new YAHOO.widget.Dialog("dialog_export_customers", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_export['customers'].render();
    Event.addListener("export_customers", "click", show_export_dialog, 'customers');
    Event.addListener("export_csv_customers", "click", export_table, {
        output: 'csv',
        table: 'customers',
        parent: 'list',
        'parent_key': Dom.get('customer_list_key').value
    });
    Event.addListener("export_xls_customers", "click", export_table, {
        output: 'xls',
        table: 'customers',
        parent: 'list',
        'parent_key': Dom.get('customer_list_key').value
    });

    Event.addListener("export_result_download_link_customers", "click", download_export_file, 'customers');


    ids = ['customers', 'deals'];
    Event.addListener(ids, "click", change_block);

    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    oACDS.table_id = 0;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;

    var oACDS4 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS4.queryMatchContains = true;
    oACDS4.table_id = 4;
    var oAutoComp4 = new YAHOO.widget.AutoComplete("f_input4", "f_container4", oACDS4);
    oAutoComp4.minQueryLength = 0;


    Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    Event.addListener('clean_table_filter_show4', "click", show_filter, 4);
    Event.addListener('clean_table_filter_hide4', "click", hide_filter, 4);

    YAHOO.util.Event.addListener(customer_views_ids, "click", change_view_customers, 0);




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
    rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {
        trigger: "rtext_rpp0"
    });
    rppmenu.render();
    rppmenu.subscribe("show", rppmenu.focus);
});

YAHOO.util.Event.onContentReady("rppmenu4", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu4", {
        trigger: "rtext_rpp4"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});

YAHOO.util.Event.onContentReady("filtermenu4", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu4", {
        trigger: "filter_name4"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});
