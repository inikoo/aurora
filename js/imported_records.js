var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var state_records;
var gettext_strings;

function records_myrenderEvent() {

    ostate = this.getState();
    paginator = ostate.pagination

    if (paginator.totalRecords <= paginator.rowsPerPage) {
        Dom.setStyle('paginator' + this.table_id, 'display', 'none')
    }
    get_records_elements()
}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


        var tableid = 0; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;
        var CustomersColumnDefs = [

        
                     {
            key: "index",
            label: "Index",
            width: 80,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "status",
            label: "Status",
            width: 100,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "note",
            label: "Note",
            width: 550,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }

          


        ];

        request = "ar_import.php?tipo=list_records&parent_key=" + Dom.get('imported_records_key').value;

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

            fields: ["index", "note", "status"]
        };


        this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs, this.dataSource0, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder
            //,initialLoad:false
            ,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state_records.nr,
                containers: 'paginator0',
                pageReportTemplate: '(Page {currentPage} of {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



            })

            ,
            sortedBy: {
                key: state_records.order,
                dir: state_records.order_dir
            },
            dynamicData: true

        }

        );

        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table0.request = request;
        this.table0.table_id = tableid;
        this.table0.subscribe("renderEvent", records_myrenderEvent);
        this.table0.getDataSource().sendRequest(null, {
            success: function(request, response, payload) {
                if (response.results.length == 0) {
                    get_records_elements()
                } else {
                    //this.onDataReturnInitializeTable(request, response, payload);
                }
            },
            scope: this.table0,
            argument: this.table0.getState()
        });

        this.table0.filter = {
            key: state_records.f_field,
            value: state_records.f_value
        };


    };
});

function get_records_elements() {
    var ar_file = 'ar_import.php';
    var request = 'tipo=get_records_elements&imported_records_key=' + Dom.get('imported_records_key').value

    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {


            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {

                for (i in r.elements_numbers) {
                    Dom.get('elements_' + i + '_number').innerHTML = r.elements_numbers[i]
                }
            }
        },
        failure: function(o) {
            // alert(o.statusText);
        },
        scope: this
    }, request

    );
}

function change_block() {
    ids = ['overview', 'records'];
    block_ids = ['block_overview', 'block_records'];

    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=imported_records-' + Dom.get('subject').value + '-view&value=' + this.id, {});
    dialog_change_stores_display.hide();
}


function insert_data() {

    //  return;
    var ar_file = 'ar_import_csv.php';
    var request = ar_file + '?tipo=insert_data';

    alert(request);


    YAHOO.util.Connect.asyncRequest('POST', request, {});
}



function read_results() {
    var request = 'ar_import_csv.php?tipo=import_customer_csv_status';



    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {


        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('records_todo').innerHTML = r.data.todo.number;
                Dom.get('records_imported').innerHTML = r.data.done.number;
                Dom.get('records_error').innerHTML = r.data.error.number;
                Dom.get('records_ignored').innerHTML = r.data.ignored.number;

                Dom.get('records_todo_comments').innerHTML = r.data.todo.comments;
                Dom.get('records_imported_comments').innerHTML = r.data.done.comments;
                Dom.get('records_error_comments').innerHTML = r.data.error.comments;
                Dom.get('records_ignored_comments').innerHTML = r.data.ignored.comments;
                if (r.data.todo.number != 0) {

                    setTimeout("read_results()", 100);
                }
            } else {
                //Dom.get('message_error').innerHTML=r.msg;
            }
        }

    });


}

function init() {
    init_search(Dom.get('search_type').value);
    ids = ['overview', 'records'];
    YAHOO.util.Event.addListener(ids, "click", change_block);
   
        state_records = YAHOO.lang.JSON.parse(base64_decode(Dom.get('state_records').value))
        gettext_strings = YAHOO.lang.JSON.parse(base64_decode(Dom.get('gettext_strings').value))

    //read_results();
    //insert_data();
}

YAHOO.util.Event.onDOMReady(init);
