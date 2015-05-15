var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

function change_block() {
    ids = ['suppliers', 'porders', 'sproducts', 'sinvoices', 'idn'];
    block_ids = ['block_suppliers', 'block_porders', 'block_sproducts', 'block_sinvoices', 'block_idn'];

    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=suppliers-block_view&value=' + this.id, {});
}



Event.addListener(window, "load", function() {
    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;
    state = session_data.state;


    tables = new function() {
        var tableid = 0; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;



        var SuppliersColumnDefs = [{
            key: "id",
            label: labels.Id,
            hidden: true,
            width: 60,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "code",
            label: labels.Code,
            width: 80,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "name",
            label: labels.Name,
            hidden: (state.suppliers.view == 'general' || state.suppliers.view == 'contact' || state.suppliers.view == 'products' || state.suppliers.view == 'sales' ? false : true),
            width: 190,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "contact",
            label: labels.Contact,
            hidden: (state.suppliers.view == 'contact' ? false : true),
            width: 190,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "email",
            label: labels.Email,
            hidden: (state.suppliers.view == 'contact' ? false : true),
            width: 190,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "origin",
            label: labels.Products_Origin,
            hidden: (state.suppliers.view == 'general' ? false : true),
            width: 190,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "tel",
            hidden: (state.suppliers.view == 'contact' ? false : true),
            label: labels.Tel,
            width: 190,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "pending_pos",
            hidden: (state.suppliers.view == 'general' ? false : true),
            label: labels.P_POs,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "active_sp",
            hidden: (state.suppliers.view == 'general' || state.suppliers.view == 'products' ? false : true),
            label: labels.Products,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "no_active_sp",
            hidden: (state.suppliers.view == 'products' ? false : true),
            label: labels.Discontinued,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "stock_value",
            hidden: (state.suppliers.view == 'money' ? false : true),
            label: labels.Stock_Value,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "delivery_time",
            hidden: (state.suppliers.view == 'stock' ? false : true),
            label: labels.Delivery_Time,
            width: 150,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }

        , {
            key: "high",
            hidden: (state.suppliers.view == 'stock' ? false : true),
            label: labels.High,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "normal",
            hidden: (state.suppliers.view == 'stock' ? false : true),
            label: labels.Normal,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "low",
            hidden: (state.suppliers.view == 'stock' ? false : true),
            label: labels.Low,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "critical",
            hidden: (state.suppliers.view == 'stock' ? false : true),
            label: labels.Critical,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "outofstock",
            hidden: (state.suppliers.view == 'stock' ? false : true),
            label: labels.Out_of_Stock,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "required",
            hidden: (state.suppliers.view == 'sales' ? false : true),
            label: labels.Required,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "sold",
            hidden: (state.suppliers.view == 'sales' ? false : true),
            label: labels.Sold,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "sales",
            hidden: (state.suppliers.view == 'sales' ? false : true),
            label: labels.Sales,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "delta_sales",
            hidden: (state.suppliers.view == 'sales' ? false : true),
            label: labels.y + '&Delta; ' + labels.Sales,
            width: 100,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "profit",
            hidden: (state.suppliers.view == 'profit' ? false : true),
            label: labels.Profit,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "profit_after_storing",
            hidden: (state.suppliers.view == 'profit' ? false : true),
            label: labels.PaS,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "cost",
            hidden: (state.suppliers.view == 'profit' ? false : true),
            label: labels.Cost,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "margin",
            hidden: (state.suppliers.view == 'profit' ? false : true),
            label: labels.Margin,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "sales_year0",
            hidden: (state.suppliers.view == 'sales_year' ? false : true),
            label: labels.Year0,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "sales_year1",
            hidden: (state.suppliers.view == 'sales_year' ? false : true),
            label: labels.Year1,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "sales_year2",
            hidden: (state.suppliers.view == 'sales_year' ? false : true),
            label: labels.Year2,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "sales_year3",
            hidden: (state.suppliers.view == 'sales_year' ? false : true),
            label: labels.Year3,
            width: 90,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "delta_sales_year0",
            hidden: (state.suppliers.view == 'sales_year' ? false : true),
            label: '&Delta;' + labels.year1,
            width: 70,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "delta_sales_year1",
            hidden: (state.suppliers.view == 'sales_year' ? false : true),
            label: '&Delta;' + labels.year1 + '/' + labels.year2,
            width: 70,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "delta_sales_year2",
            hidden: (state.suppliers.view == 'sales_year' ? false : true),
            label: '&Delta;' + labels.year2 + '/' + labels.year3,
            width: 70,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "delta_sales_year3",
            hidden: (state.suppliers.view == 'sales_year' ? false : true),
            label: '&Delta;' + labels.year3 + '/' + labels.year4,
            width: 70,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }

        ];
        request = "ar_suppliers.php?tipo=suppliers&parent=none&parent_key=0&sf=0"

        this.dataSource0 = new YAHOO.util.DataSource(request);
        this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource0.connXhrMode = "queueRequests";
        this.dataSource0.responseSchema = {
            resultsList: "resultset.data",
            metaFields: {
                rtext: "resultset.rtext",
                rtext_rpp: "resultset.rtext_rpp",

                rowsPerPage: "resultset.records_perpage",
                recordsOffset: "resultset.records_offset",
                sort_key: "resultset.sort_key",
                sort_dir: "resultset.sort_dir",
                tableid: "resultset.tableid",
                filter_msg: "resultset.filter_msg",
                totalRecords: "resultset.total_records"
            },

            fields: ["id", "name", "code", "products", "high", "normal", "critical", "outofstock", "required", "sold", "delta_sales", "outofstock", "for_sale", "discontinued", "delivery_time", "active_sp", "no_active_sp", "delivery_time", "low", "location", "email", "profit", 'profit_after_storing', 'cost', "pending_pos", "sales", "contact", "critical", "margin", "sales_year0", "delta_sales_year0", "sales_year1", "delta_sales_year1", "sales_year2", "delta_sales_year2", "sales_year3", "delta_sales_year3", "origin"]
        };

        table_paginator0 = new YAHOO.widget.Paginator({
            alwaysVisible: true,
            rowsPerPage: state.suppliers.nr,
            containers: 'paginator0',
            pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
            previousPageLinkLabel: "<",
            nextPageLinkLabel: ">",
            firstPageLinkLabel: "<<",
            lastPageLinkLabel: ">>",
            rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
            template: "{FirstPageLink}{PreviousPageLink} <strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
        });

        this.table0 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs, this.dataSource0, {
            draggableColumns: true,
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: table_paginator0

            ,
            sortedBy: {
                key: state.suppliers.order,
                dir: state.suppliers.order_dir
            },
            dynamicData: true

        });
        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;

        this.table0.table_id = tableid;
        this.table0.request = request;
        this.table0.subscribe("renderEvent", myrenderEvent);
        this.table0.filter = {
            key: state.suppliers.f_field,
            value: state.suppliers.f_value
        };
        this.table0.view = state.suppliers.view;




        var tableid = 1;
        var tableDivEL = "table" + tableid;
        var ColumnDefs = [
        //  {key:"id", label:labels.Id,width:45,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
        {
            key: "supplier",
            label: labels.Supplier,
            width: 60,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
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
            key: "description",
            label: labels.Description,
            hidden: (state.supplier_products.view == 'general' ? false : true),
            width: 380,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "used_in",
            label: labels.Used_In,
            width: 250,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "stock",
            label: labels.Stock,
            hidden: (state.supplier_products.view == 'stock' ? false : true),
            width: 55,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "weeks_until_out_of_stock",
            label: labels.W_Until_OO,
            hidden: (state.supplier_products.view == 'stock' ? false : true),
            width: 75,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "required",
            label: labels.Required,
            hidden: (state.supplier_products.view == 'sales' ? false : true),
            width: 55,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "dispatched",
            label: labels.Dispatched,
            hidden: (state.supplier_products.view == 'sales' ? false : true),
            width: 55,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "sold",
            label: labels.Sold,
            hidden: (state.supplier_products.view == 'sales' ? false : true),
            width: 55,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "sales",
            label: labels.Sales,
            hidden: (state.supplier_products.view == 'sales' ? false : true),
            width: 75,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "profit",
            label: labels.Profit,
            hidden: (state.supplier_products.view == 'profit' ? false : true),
            width: 55,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "margin",
            label: labels.Margin,
            hidden: (state.supplier_products.view == 'profit' ? false : true),
            width: 55,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }

        ];

        request = "ar_suppliers.php?tipo=supplier_products&sf=0&parent=none&parent_key=&tableid=" + tableid;
        this.dataSource1 = new YAHOO.util.DataSource(request);
        //alert("ar_suppliers.php?tipo=supplier_products&parent=none&tableid="+tableid);
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

            fields: ["description", "id", "code", "name", "cost", "used_in", "profit", "allcost", "used", "required", "provided", "lost", "broken", "supplier", "dispatched", "sold", "sales", "weeks_until_out_of_stock", "stock", "margin"]
        };

        this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource1, {
            //draggableColumns:true,
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.supplier_products.nr,
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
                key: state.supplier_products.order,
                dir: state.supplier_products.order_dir
            },
            dynamicData: true

        });
        this.table1.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;


        this.table1.table_id = tableid;
        this.table1.request = request;
        this.table1.subscribe("renderEvent", myrenderEvent);


        this.table1.filter = {
            key: state.suppliers.f_field,
            value: state.supplier_products.f_value
        };
        this.table1.view = state.supplier_products.view;



        var tableid = 2; 
        var tableDivEL = "table" + tableid;
        var OrdersColumnDefs = [{
            key: "public_id",
            label: labels.Purchase_Order_ID,
            width: 120,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        },{
            key: "date",
            label: labels.Last_Updated,
            width: 145,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "status",
            label: labels.Status,
            width: 70,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "buyer_name",
            label: labels.Buyer,
            width: 170,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },
         {
            key: "items",
            label: labels.Items,
            width: 70,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        },
         {
            key: "total",
            label: labels.Amount,
            width: 110,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, 
        
        
         
        
        ];
        request = "ar_porders.php?tipo=purchase_orders&sf=0&parent=none&parent_key=&tableid=" + tableid;

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

            fields: ["public_id", "state", "customer", "date", "last_date", "buyer_name", "status","total","items"]
        };

        this.table2 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs, this.dataSource2, {
            draggableColumns: true,
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.porders.nr,
                containers: 'paginator2',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
            })

            ,
            sortedBy: {
                key: state.porders.order,
                dir: state.porders.order_dir
            },
            dynamicData: true

        });
        this.table2.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table2.table_id = tableid;
        this.table2.request = request;
        this.table2.subscribe("renderEvent", myrenderEvent);

        this.table2.filter = {
            key: state.porders.f_field,
            value: state.porders.f_value
        };

        var tableid = 3; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;
        var OrdersColumnDefs = [{
            key: "public_id",
            label: labels.Purchase_Order_ID,
            width: 120,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "date",
            label: labels.Last_Updated,
            width: 145,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "buyer_name",
            label: labels.Buyer_Name,
            width: 170,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "customer",
            label: labels.Total,
            width: 110,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "state",
            label: labels.Items,
            width: 70,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "status",
            label: labels.Status,
            width: 70,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }];
        request = "ar_porders.php?tipo=invoices&sf=0&parent=none&parent_key=&tableid=" + tableid;

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

            fields: ["public_id", "state", "customer", "date", "last_date", "buyer_name", "status"]
        };

        this.table3 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs, this.dataSource3, {
            draggableColumns: true,
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.supplier_invoices.nr,
                containers: 'paginator3',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
            })

            ,
            sortedBy: {
                key: state.supplier_invoices.order,
                dir: state.supplier_invoices.order_dir
            },
            dynamicData: true

        });
        this.table3.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table3.table_id = tableid;
        this.table3.request = request;
        this.table3.subscribe("renderEvent", myrenderEvent);

        this.table3.filter = {
            key: state.supplier_invoices.f_field,
            value: state.supplier_invoices.f_value
        };

        var tableid = 4; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;
        var OrdersColumnDefs = [{
            key: "id",
            label: labels.Delivery_ID,
            width: 120,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "date",
            label: labels.Last_Updated,
            width: 145,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "supplier_name",
            label: labels.Supplier,
            width: 170,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "items",
            label: labels.Items,
            width: 110,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "status",
            label: labels.Status,
            width: 70,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }];
        request = "ar_porders.php?tipo=delivery_notes&sf=0&parent=none&parent_key=&tableid=" + tableid;
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

            fields: ["public_id", "state", "customer", "date", "last_date", "supplier_name", "status", "items", "id"]
        };

        this.table4 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs, this.dataSource4, {
            draggableColumns: true,
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.supplier_dns.nr,
                containers: 'paginator4',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
            })

            ,
            sortedBy: {
                key: state.supplier_dns.order,
                dir: state.supplier_dns.order_dir
            },
            dynamicData: true

        });
        this.table4.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table4.table_id = tableid;
        this.table4.request = request;
        this.table4.subscribe("renderEvent", myrenderEvent);

        this.table4.filter = {
            key: state.supplier_dns.f_field,
            value: state.supplier_dns.f_value
        };



    };
});




function init() {

    init_search('supplier_products');

    ids = ['suppliers', 'porders', 'sproducts', 'sinvoices', 'idn'];
    Event.addListener(ids, "click", change_block);

    ids = ['suppliers_general', 'suppliers_sales', 'suppliers_sales_year', 'suppliers_stock', 'suppliers_products', 'suppliers_contact', 'suppliers_profit'];
    YAHOO.util.Event.addListener(ids, "click", change_suppliers_view, {
        'table_id': 0,
        'parent': 'suppliers'
    })
    YAHOO.util.Event.addListener(suppliers_period_ids, "click", change_table_period, {
        'table_id': 0,
        'subject': 'suppliers'
    });


    // ids=['suppliers_avg_totals','suppliers_avg_month','suppliers_avg_week'];
    // YAHOO.util.Event.addListener(ids, "click",change_avg,{'table_id':0,'subject':'suppliers'});


    ids = ['supplier_products_general', 'supplier_products_sales', 'supplier_products_stock', 'supplier_products_profit'];

    YAHOO.util.Event.addListener(ids, "click", change_supplier_products_view, {
        'table_id': 1,
        'parent': 'suppliers'
    })


    YAHOO.util.Event.addListener(supplier_products_period_ids, "click", change_table_period, {
        'table_id': 1,
        'subject': 'supplier_products'
    });
    //ids=['supplier_products_avg_totals','supplier_products_avg_month','supplier_products_avg_week'];
    //YAHOO.util.Event.addListener(ids, "click",change_avg,{'table_id':1,'subject':'suppliers'});



    //Event.addListener('export_csv0', "click",download_csv,'suppliers');
    //Event.addListener('export_csv0_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table0',tipo:'suppliers'});
    //csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {trigger:"export_csv0" });
    //	 csvMenu.render();
    // csvMenu.subscribe("show", csvMenu.focus);
    //Event.addListener('export_csv0_close_dialog', "click",csvMenu.hide,csvMenu,true);



    // ids=['suppliers_general','suppliers_sales','suppliers_stock','suppliers_products'];
    //  Event.addListener(ids, "click",supplier_change_view)



    Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
    Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);

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


}

Event.onDOMReady(init);
Event.onContentReady("filtermenu0", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
        trigger: "filter_name0"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});
Event.onContentReady("rppmenu0", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {
        trigger: "rtext_rpp0"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});

Event.onContentReady("filtermenu1", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
        trigger: "filter_name1"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});
Event.onContentReady("rppmenu1", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {
        trigger: "rtext_rpp1"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});
