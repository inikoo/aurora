var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var state_records;
var gettext_strings;
var table_interval;

function get_wait_info(fork_key, tag, imported_record_key) {
    request = 'ar_fork.php?tipo=get_wait_info&fork_key=' + fork_key + '&tag=' + tag + '&extra_key=' + imported_record_key
    //    alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                if (r.fork_state == 'Queued') {
                    setTimeout(function() {
                        get_wait_info(r.fork_key, r.tag, imported_record_key)
                    }, 1000);
                    Dom.get('progress').innerHTML = r.msg;

                }
                else if (r.fork_state == 'In Process') {
                    // alert(r.msg)
                    //Dom.get('dialog_edit_subjects_wait_done').innerHTML = r.msg
                    Dom.get('progress').innerHTML = r.progress;
                    Dom.get('records_todo').innerHTML = r.todo;
                    Dom.get('records_done').innerHTML = r.done;
                    Dom.get('records_error').innerHTML = r.errors;
                    Dom.get('records_ignored').innerHTML = r.no_changed;

                    Dom.setStyle(['start_date_tr', 'info_wait_tbody'], 'display', '')


                    //alert("caca")
                    setTimeout(function() {
                        get_wait_info(r.fork_key, r.tag, imported_record_key)
                    }, 1000);

                }
                else if (r.fork_state == 'Finished' || r.fork_state == 'Cancelled') {

                    Dom.setStyle(['info_wait', 'branch_wait', 'title_wait', 'cancelling_import','cancel_import'], 'display', 'none')
                    Dom.setStyle(['info_finished', 'branch_finished', 'title_finished'], 'display', '')

                    Dom.addClass('title_container', 'no_buttons')

                    Dom.get('finished_date').innerHTML = r.result_extra_data['finished_date'];
                    Dom.get('finished_list_link').innerHTML = r.result_extra_data.finished_list_link;
                    Dom.get('finished_records_done').innerHTML = r.result_extra_data.finished_records_done;
                    Dom.get('finished_records_ignored').innerHTML = r.result_extra_data.finished_records_ignored;
                    Dom.get('finished_records_error').innerHTML = r.result_extra_data.finished_records_error;

                    if (r.fork_state == 'Cancelled') {

                        Dom.setStyle(['title_cancelled', 'finished_records_cancelled_tr', 'dates_cancelled'], 'display', '')
                    Dom.get('finished_records_cancelled').innerHTML = r.result_extra_data.finished_records_cancelled;
                    Dom.get('start_date').innerHTML = r.result_extra_data['start_date'];
                    Dom.get('cancelled_date').innerHTML = r.result_extra_data['cancelled_date'];


                    } else {
                    
                        Dom.setStyle(['dates_finished'], 'display', '')
                        Dom.setStyle(['title_cancelled', 'finished_records_cancelled_tr', 'dates_cancelled'], 'display', 'none')

                    }


                    clearInterval(table_interval)


                    var table = tables.table0;
                    var datasource = tables.dataSource0;
                    Dom.addClass(this, 'selected');
                    var request = '';
                    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

                }
				else{
					
				
				}

            }
        }

    });

}



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
            width: 50,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "status",
            label: "Status",
            width: 80,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "data",
            label: "Data",
            width: 480,
            sortable: false,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }

            , {
            key: "note",
            label: "Impored record",
            width: 220,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }




        ];

        request = "ar_import.php?tipo=list_records&sf=0&parent_key=" + Dom.get('imported_records_key').value;

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

            fields: ["index", "note", "status", "data"]
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


        var interval_callback = {
            success: this.table0.onDataReturnInitializeTable,
            failure: function() {
                YAHOO.log("Polling failure", "error");
            },
            scope: this.table0
        };

        table_interval = this.dataSource0.setInterval(5000, null, interval_callback);


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



function cancel_import() {
    var ar_file = 'ar_import.php';
    var request = 'tipo=cancel_import&imported_records_key=' + Dom.get('imported_records_key').value
    Dom.setStyle('cancel_import', 'display', 'none')
    Dom.setStyle('cancelling_import', 'display', '')

    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {


            }
        },
        failure: function(o) {
            // alert(o.statusText);
        },
        scope: this
    }, request

    );

}



function init() {
    init_search(Dom.get('search_type').value);
    ids = ['overview', 'records'];
    YAHOO.util.Event.addListener(ids, "click", change_block);

    state_records = YAHOO.lang.JSON.parse(base64_decode(Dom.get('state_records').value))
    gettext_strings = YAHOO.lang.JSON.parse(base64_decode(Dom.get('gettext_strings').value))



    YAHOO.util.Event.addListener('cancel_import', "click", cancel_import);


    if (!(Dom.get('imported_record_state').value == 'Finished' || Dom.get('imported_record_state').value == 'Cancelled')) {
        get_wait_info(Dom.get('fork_key').value, Dom.get('subject').value, Dom.get('imported_records_key').value);
    } else {

    }
}

YAHOO.util.Event.onDOMReady(init);
