var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;




YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
    
        field_labels = YAHOO.lang.JSON.parse(base64_decode(Dom.get('field_labels').value))
    state_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('state_data').value))


        var tableid = 0; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;
        var CustomersColumnDefs = [
            {
            key: "code",
            label: field_labels.code,
            width: 70,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "name",
            label: field_labels.name,
            width: 150,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "contacts",
            label: field_labels.contacts,
            hidden: (state_data.stores.customers.type=='all_contacts'?false:true),
            sortable : true,
            className : "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            className: 'aright'
        }
            , {
            key: "new_contacts",
            label: field_labels.new_contacts,
            hidden: (state_data.stores.customers.type=='all_contacts'?false:true), sortable : true,
            className : "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            className: 'aright'
        }
                , {
            key: "active_contacts",
            label: field_labels.active_contacts,
            hidden: (state_data.stores.customers.type=='all_contacts'?false:true), sortable : true,
            className : "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            className: 'aright'
        }
                , {
            key: "losing_contacts",
            label: field_labels.losing_contacts,
            hidden: (state_data.stores.customers.type=='all_contacts'?false:true), sortable : true,
            className : "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            className: 'aright'
        }
                , {
            key: "lost_contacts",
            label: field_labels.lost_contacts,
           hidden: (state_data.stores.customers.type=='all_contacts'?false:true), sortable : true,
            className : "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            className: 'aright'
        }

            , {
            key: "contacts_with_orders",
            label: field_labels.contacts_with_orders,
            hidden: (state_data.stores.customers.type=='contacts_with_orders'?false:true),sortable : true,
            className : "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            className: 'aright'
        }
            , {
            key: "new_contacts_with_orders",
            label: field_labels.new_contacts_with_orders,
            hidden: (state_data.stores.customers.type=='contacts_with_orders'?false:true), sortable : true,
            className : "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            className: 'aright'
        }
                , {
            key: "active_contacts_with_orders",
            label: field_labels.active_contacts_with_orders,
            hidden: (state_data.stores.customers.type=='contacts_with_orders'?false:true),sortable : true,
            className : "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            className: 'aright'
        }
                , {
            key: "losing_contacts_with_orders",
            label: field_labels.losing_contacts_with_orders,
            hidden: (state_data.stores.customers.type=='contacts_with_orders'?false:true), sortable : true,
            className : "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            className: 'aright'
        }
                , {
            key: "lost_contacts_with_orders",
            label: field_labels.lost_contacts_with_orders,
            hidden: (state_data.stores.customers.type=='contacts_with_orders'?false:true), sortable : true,
            className : "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            },
            className: 'aright'
        }


        ];
        //?tipo=customers&tid=0"
        this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=customers_per_store&tableid=" + tableid);
        //alert("ar_assets.php?tipo=customers_per_store&tableid="+tableid);
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
                'code', 'name', 'contacts', 'active_contacts', 'new_contacts', 'lost_contacts', 'losing_contacts',
                'contacts_with_orders', 'active_contacts_with_orders', 'new_contacts_with_orders', 'lost_contacts_with_orders', 'losing_contacts_with_orders', 'users'
                ]
        };
        //__You shouls not change anything from here
        //this.dataSource.doBeforeCallback = mydoBeforeCallback;


        this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs, this.dataSource0, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state_data.stores.customers.nr ,
                containers : 'paginator',
                pageReportTemplate : 'Page {currentPage} of {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



            })

            ,
            sortedBy: {
                key: state_data.stores.customers.order ,
                dir: state_data.stores.customers.order_dir
            },
            dynamicData: true

        }

        );

        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table0.table_id = tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);


        this.table0.view =state_data.stores.customers.view ;

        this.table0.filter = {
            key: state_data.stores.customers.f_filter,
            value: state_data.stores.customers.f_value
        };

        //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})

        //	    var Dom   = YAHOO.util.Dom;
        //alert(Dom.get('f_input'));
        YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click", myRowsPerPageDropdown)

    };
});


function change_table_type() {

    if (this.id == 'all_contacts') {
        Dom.removeClass('contacts_with_orders', 'selected')
        Dom.addClass('all_contacts', 'selected')
        tipo = 'all_contacts'
        tables.table0.hideColumn('contacts_with_orders');
        tables.table0.hideColumn('new_contacts_with_orders');
        tables.table0.hideColumn('active_contacts_with_orders');
        tables.table0.hideColumn('losing_contacts_with_orders');
        tables.table0.hideColumn('lost_contacts_with_orders');

        tables.table0.showColumn('contacts');
        tables.table0.showColumn('new_contacts');
        tables.table0.showColumn('active_contacts');
        tables.table0.showColumn('losing_contacts');
        tables.table0.showColumn('lost_contacts');




    } else {
        Dom.addClass('contacts_with_orders', 'selected')
        Dom.removeClass('all_contacts', 'selected')
        tipo = 'contacts_with_orders';

        tables.table0.hideColumn('contacts');
        tables.table0.hideColumn('new_contacts');
        tables.table0.hideColumn('active_contacts');
        tables.table0.hideColumn('losing_contacts');
        tables.table0.hideColumn('lost_contacts');

        tables.table0.showColumn('contacts_with_orders');
        tables.table0.showColumn('new_contacts_with_orders');
        tables.table0.showColumn('active_contacts_with_orders');
        tables.table0.showColumn('losing_contacts_with_orders');
        tables.table0.showColumn('lost_contacts_with_orders');

    }
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=stores-customers-type&value=' + escape(tipo), {
        success: function(o) {}
    });









}

function init() {





    YAHOO.util.Event.addListener(['contacts_with_orders', 'all_contacts'], "click", change_table_type);


    YAHOO.util.Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    YAHOO.util.Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);

    init_search('customers');


    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container", oACDS);
    oAutoComp.minQueryLength = 0;









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
