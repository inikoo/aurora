//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012 LW
var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;


var customer_views_ids = ['customers_general', 'customers_contact', 'customers_address', 'customers_ship_to_address', 'customers_balance', 'customers_rank', 'customers_weblog', 'customers_other_value'];





var subcategories_period_ids = ['subcategories_period_all', 'subcategories_period_yesterday', 'subcategories_period_last_w', 'subcategories_period_last_m', 'subcategories_period_three_year', 'subcategories_period_year', 'subcategories_period_yeartoday', 'subcategories_period_six_month', 'subcategories_period_quarter', 'subcategories_period_month', 'subcategories_period_ten_day', 'subcategories_period_week', 'subcategories_period_monthtoday', 'subcategories_period_weektoday', 'subcategories_period_today'];




function change_history_elements(e, table_id) {
    ids = ['elements_Changes', 'elements_Assign'];
    if (Dom.hasClass(this, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(this, 'selected')

        }

    } else {
        Dom.addClass(this, 'selected')

    }
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';
    for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' + ids[i] + '=1'
        } else {
            request = request + '&' + ids[i] + '=0'

        }
    }
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}


function change_customers_view_save(tipo) {
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=customer_categories-customers-view&value=' + escape(tipo), {});

}



function change_block() {
    ids = ['subcategories', 'subjects', 'overview', 'history', 'sales', 'no_assigned', 'deals'];
    block_ids = ['block_subcategories', 'block_subjects', 'block_overview', 'block_history', 'block_sales', 'block_no_assigned', 'block_deals'];
    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');
    //alert('ar_sessions.php?tipo=update&keys=customer_categories-'+Dom.get('state_type').value+'_block_view&value='+this.id )
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=customer_categories-' + Dom.get('state_type').value + '_block_view&value=' + this.id, {
        success: function(o) {

        }

    });
}


YAHOO.util.Event.addListener(window, "load", function() {

    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;
    state = session_data.state;

    tables = new function() {

        if (Dom.get('show_subjects').value) {
            var tableid = 0;
            var tableDivEL = "table" + tableid;
            var ColumnDefs = [{
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
                hidden: (Dom.get('customers_view').value == 'general' || Dom.get('customers_view').value == 'other_value' ? false : true),
                width: 200,
                sortable: true,
                className: "aleft",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_ASC
                }
            }, {
                key: "contact_since",
                label: labels.Since,
                hidden: (Dom.get('customers_view').value.value == 'general' ? false : true),
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
                hidden: (Dom.get('customers_view').value.value == 'general' ? false : true),
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
                hidden: (Dom.get('customers_view').value.value == 'general' ? false : true),
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "activity",
                label: labels.Status,
                hidden: (Dom.get('customers_view').value.value == 'general' ? false : true),
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
                hidden: (Dom.get('customers_view').value == 'contact' ? false : true),
                sortable: true,
                className: "aleft",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "email",
                label: labels.Email,
                width: 210,
                hidden: (Dom.get('customers_view').value == 'contact' ? false : true),
                sortable: true,
                className: "aleft",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "telephone",
                label: labels.Telephone,
                width: 137,
                hidden: (Dom.get('customers_view').value == 'contact' ? false : true),
                sortable: true,
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                },
                className: "aright"
            }, {
                key: "address",
                label: labels.Contact_Address,
                width: 176,
                hidden: (Dom.get('customers_view').value == 'address' ? false : true),
                sortable: true,
                className: "aleft"
            }, {
                key: "billing_address",
                label: labels.Billing_Address,
                width: 170,
                hidden: (Dom.get('customers_view').value == 'address' ? false : true),
                sortable: true,
                className: "aleft"
            }, {
                key: "delivery_address",
                label: labels.Delivery_Address,
                width: 170,
                hidden: (Dom.get('customers_view').value == 'address' ? false : true),
                sortable: true,
                className: "aleft"
            }, {
                key: "total_payments",
                label: labels.Payments,
                width: 99,
                hidden: (Dom.get('customers_view').value == 'balance' ? false : true),
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "total_refunds",
                label: labels.Refunds,
                width: 90,
                hidden: (Dom.get('customers_view').value == 'balance' ? false : true),
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "net_balance",
                label: labels.Balance,
                width: 90,
                hidden: (Dom.get('customers_view').value == 'balance' ? false : true),
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "balance",
                label: labels.Outstanding,
                width: 90,
                hidden: (Dom.get('customers_view').value == 'balance' ? false : true),
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "total_profit",
                label: labels.Profit,
                width: 90,
                hidden: (Dom.get('customers_view').value == 'balance' ? false : true),
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "top_orders",
                label: labels.Orders_Rank,
                width: 121,
                hidden: (Dom.get('customers_view').value == 'rank' ? false : true),
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "top_invoices",
                label: labels.Invoices_Rank,
                width: 121,
                hidden: (Dom.get('customers_view').value == 'rank' ? false : true),
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "top_balance",
                label: labels.Balance_Rank,
                width: 120,
                hidden: (Dom.get('customers_view').value == 'rank' ? false : true),
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "top_profits",
                label: labels.Profits_Rank,
                width: 120,
                hidden: (Dom.get('customers_view').value == 'rank' ? false : true),
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "logins",
                label: labels.Logins,
                width: 120,
                hidden: (Dom.get('customers_view').value == 'weblog' ? false : true),
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "failed_logins",
                label: labels.Failed_Logis,
                width: 120,
                hidden: (Dom.get('customers_view').value == 'weblog' ? false : true),
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "requests",
                label: labels.Viewed_Pages,
                width: 120,
                hidden: (Dom.get('customers_view').value == 'weblog' ? false : true),
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }, {
                key: "other_value",
                label: labels.Category_Other_Value,
                width: 300,
                hidden: (Dom.get('customers_view').value.value == 'other_value' ? false : true),
                sortable: true,
                className: "aleft",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }

            ];

            request = "ar_contacts.php?tipo=customers&tableid=0&where=&parent=category&sf=0&parent_key=" + Dom.get('category_key').value
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

                fields: ['id', 'other_value', 'name', 'location', 'orders', 'email', 'telephone', 'last_order', 'activity', 'total_payments', 'contact_name', "address", "billing_address", "delivery_address", "total_paymants", "total_refunds", "net_balance", "total_profit", "balance", "contact_since", "top_orders", "top_invoices", "top_balance", "top_profits", "logins", "failed_logins", "requests"]
            };

            this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource0, {
                //draggableColumns:true,
                renderLoopSize: 50,
                generateRequest: myRequestBuilder,
                paginator: new YAHOO.widget.Paginator({
                    rowsPerPage: state.customer_categories.customers.nr,
                    containers: 'paginator0',
                    pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                    previousPageLinkLabel: "<",
                    nextPageLinkLabel: ">",
                    firstPageLinkLabel: "<<",
                    lastPageLinkLabel: ">>",
                    rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                    alwaysVisible: false,
                    template: "{PreviousPageLink}<span id='paginator_info0'>{CurrentPageReport}</span>{NextPageLink}"
                })

                ,
                sortedBy: {
                    key: state.customer_categories.customers.order,
                    dir: state.customer_categories.customers.order_dir
                },
                dynamicData: true

            });
            this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
            this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
            this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
            this.table0.table_id = tableid;
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
                key: state.customer_categories.customers.f_field,
                value: state.customer_categories.customers.f_value
            };





        }

        if (Dom.get('show_subcategories').value) {
            var tableid = 1; // Change if you have more the 1 table
            var tableDivEL = "table" + tableid;
            var OrdersColumnDefs = [

            {
                key: "code",
                label: labels.Code,
                width: 190,
                sortable: true,
                className: "aleft",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_ASC
                }
            }, {
                key: "label",
                label: labels.Label,
                width: 360,
                sortable: true,
                className: "aleft",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_ASC
                }
            }, {
                key: "subjects",
                label: labels.Customers,
                width: 100,
                sortable: true,
                className: "aright",
                sortOptions: {
                    defaultDir: YAHOO.widget.DataTable.CLASS_DESC
                }
            }


            ];
            request = "ar_contacts.php?tipo=customer_categories&sf=0&tableid=1&parent=category&parent_key=" + Dom.get('category_key').value
            //  alert(request)
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

                fields: ["code", "subjects", "sold", "sales", "label", "delta_sales"]
            };

            this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs, this.dataSource1, {
                renderLoopSize: 50,
                generateRequest: myRequestBuilder,
                paginator: new YAHOO.widget.Paginator({
                    rowsPerPage: state.customer_categories.subcategories.nr,
                    containers: 'paginator1',
                    pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                    previousPageLinkLabel: "<",
                    nextPageLinkLabel: ">",
                    firstPageLinkLabel: "<<",
                    lastPageLinkLabel: ">>",
                    rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                    alwaysVisible: false,
                    template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
                })

                ,
                sortedBy: {
                    key: state.customer_categories.subcategories.order,
                    dir: state.customer_categories.subcategories.order_dir
                },
                dynamicData: true

            });
            this.table1.handleDataReturnPayload = myhandleDataReturnPayload;
            this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
            this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;




            this.table1.filter = {
                key: state.customer_categories.subcategories.f_field,
                value: state.customer_categories.subcategories.f_value
            };


            this.table1.table_id = tableid;
            this.table1.subscribe("renderEvent", myrenderEvent);

        }
        var tableid = 2; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;

        var CustomersColumnDefs = [{
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
            width: 50
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
            width: 520
        }];
        request = "ar_history.php?tipo=customer_categories&parent=category&parent_key=" + Dom.get('category_key').value + "&tableid=2";

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


            fields: ["key", "date", 'time', 'handle', 'note']
        };

        this.table2 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs, this.dataSource2, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.customer_categories.history.nr,
                containers: 'paginator2',
                alwaysVisible: false,
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
            })

            ,
            sortedBy: {
                key: state.customer_categories.history.order,
                dir: state.customer_categories.history.order_dir
            },
            dynamicData: true

        }

        );

        this.table2.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;

        this.table2.table_id = tableid;
        this.table2.subscribe("renderEvent", myrenderEvent);




        this.table2.filter = {
            key: state.customer_categories.history.f_field,
            value: state.customer_categories.history.f_value
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
        request = "ar_deals.php?tipo=deals&parent=customer_categories&sf=0&parent_key=" + Dom.get('category_key').value + '&tableid=' + tableid + '&referrer=customer_category'
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
                rowsPerPage: state.customer_categories.offers.nr,
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
                key: state.customer_categories.offers.order,
                dir: state.customer_categories.offers.order_dir
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
            key: state.customer_categories.offers.f_field,
            value: state.customer_categories.offers.f_value
        };





    };
});

function update_customer_category_history_elements() {

    var ar_file = 'ar_history.php';
    var request = 'tipo=get_customer_category_history_elements&parent=category&parent_key=' + Dom.get('category_key').value;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            for (key in r.elements_number) {
                Dom.get('elements_' + key + '_number').innerHTML = r.elements_number[key]
            }
        },
        failure: function(o) {},
        scope: this
    }, request

    );
}

function new_deal() {
    location.href = "new_deal.php?parent=customer_categories&parent_key=" + Dom.get('category_key').value;
}


function init() {
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
        parent: 'category',
        'parent_key': Dom.get('category_key').value
    });
    Event.addListener("export_xls_customers", "click", export_table, {
        output: 'xls',
        table: 'customers',
        parent: 'category',
        'parent_key': Dom.get('category_key').value
    });

    Event.addListener("export_result_download_link_customers", "click", download_export_file, 'customers');





    ids = ['subcategories', 'subjects', 'overview', 'history', 'sales', 'no_assigned', 'deals'];
    Event.addListener(ids, "click", change_block);

    init_search('customers_store');

    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    oACDS.table_id = 0;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;

    var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS1.queryMatchContains = true;
    oACDS1.table_id = 1;
    var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS1);
    oAutoComp1.minQueryLength = 0;


    var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS2.queryMatchContains = true;
    oACDS2.table_id = 2;
    var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2", "f_container2", oACDS2);
    oAutoComp2.minQueryLength = 0;

    var oACDS4 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS4.queryMatchContains = true;
    oACDS4.table_id = 4;
    var oAutoComp4 = new YAHOO.widget.AutoComplete("f_input4", "f_container4", oACDS4);
    oAutoComp4.minQueryLength = 0;

    Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
    Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);
    Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
    Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);
    Event.addListener('clean_table_filter_show4', "click", show_filter, 4);
    Event.addListener('clean_table_filter_hide4', "click", hide_filter, 4);

    YAHOO.util.Event.addListener(customer_views_ids, "click", change_view_customers, 0);


    //ids=['elements_all_contacts_lost','label_all_contacts_losing','elements_all_contacts_active'];
    //Event.addListener(ids, "click",change_customers_elements,0);
    ids = ['elements_Changes', 'elements_Assign'];
    Event.addListener(ids, "click", change_history_elements, 2);


    YAHOO.util.Event.addListener(customers_period_ids, "click", change_customers_period, 0);


    ids = ['customers_avg_totals', 'customers_avg_month', 'customers_avg_week', "customers_avg_month_eff", "customers_avg_week_eff"];
    YAHOO.util.Event.addListener(ids, "click", change_customers_avg, 0);

    YAHOO.util.Event.addListener(subcategories_period_ids, "click", change_subcategories_period, 1);
    ids = ['category_period_all', 'category_period_three_year', 'category_period_year', 'category_period_yeartoday', 'category_period_six_month', 'category_period_quarter', 'category_period_month', 'category_period_ten_day', 'category_period_week', 'category_period_monthtoday', 'category_period_weektoday', 'category_period_today', 'category_period_yesterday', 'category_period_last_m', 'category_period_last_w'];
    YAHOO.util.Event.addListener(ids, "click", change_sales_period);





}

YAHOO.util.Event.onDOMReady(init);

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

YAHOO.util.Event.onContentReady("rppmenu1", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {
        trigger: "rtext_rpp1"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});

YAHOO.util.Event.onContentReady("filtermenu1", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
        trigger: "filter_name1"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});
YAHOO.util.Event.onContentReady("rppmenu2", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {
        trigger: "rtext_rpp2"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});

YAHOO.util.Event.onContentReady("filtermenu2", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {
        trigger: "filter_name2"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

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

