var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var state_imported_records;
var gettext_strings;

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


        var tableid = 0; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;
        var CustomersColumnDefs = [

            {
            key: "key",
            label: "",
            width: 20,
            sortable: false,
            isPrimaryKey: true,
            hidden: true
        }
                    , {
            key: "filename",
            label: "Filename",
            width: 80,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "date",
            label: "Date",
            width: 220,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "user",
            label: "User",
            width: 150,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }

            , {
            key: "status",
            label: "Status",
            width: 220,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
                , {
            key: "records",
            label: "records",
            width: 60,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }


        ];

        request = "ar_import.php?tipo=imported_records&subject=" + Dom.get('subject').value + "&parent=" + Dom.get('parent').value + "&parent_key=" + Dom.get('parent_key').value;

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

            fields: ["key", "date", "status", "filename", "records", "user"]
        };


        this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs, this.dataSource0, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder
            //,initialLoad:false
            ,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state_imported_records.imported_records.nr,
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
                key: state_imported_records.imported_records.order,
                dir: state_imported_records.imported_records.order_dir
            },
            dynamicData: true

        }

        );

        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table0.request = request;
        this.table0.table_id = tableid;
        this.table0.subscribe("renderEvent", imported_records_myrenderEvent);
        this.table0.getDataSource().sendRequest(null, {
            success: function(request, response, payload) {
                if (response.results.length == 0) {
                    get_imported_records_elements()
                } else {
                    //this.onDataReturnInitializeTable(request, response, payload);
                }
            },
            scope: this.table0,
            argument: this.table0.getState()
        });

        this.table0.filter = {
            key: state_imported_records.imported_records.f_field,
            value: state_imported_records.imported_records.f_value
        };


    };
});


function save_upload() {


    if (Dom.get('upload_import_file').value == '') {
        return;
    }

    YAHOO.util.Connect.setForm('upload_form', true, true);
    var request = 'ar_import.php?tipo=upload_file&subject=' + Dom.get('subject').value + '&parent=' + Dom.get('parent').value + "&parent_key=" + Dom.get('parent_key').value
    //   alert(request)
    var uploadHandler = {
        upload: function(o) {
            //   alert(o.responseText)
            // alert(base64_decode(o.responseText))
            var r = YAHOO.lang.JSON.parse(base64_decode(o.responseText));

            if (r.state == 200) {

                if (r.action == 'uploaded' || r.action == 'found_same_user') {
                    window.location.href = "import_review.php?id=" + r.imported_records_key + '&reference=subject';

                }


            } else {

                //dialog_attach.show();
                Dom.get('upload_msg').innerHTML = r.msg;
            }
        },
        failure: function(o) {


        }
    };

    YAHOO.util.Connect.asyncRequest('POST', request, uploadHandler);

}

function check_if_file_selected() {

    if (Dom.get('upload_import_file').value == '') {
        Dom.addClass('save_upload_button', 'disabled')
    } else {
        Dom.removeClass('save_upload_button', 'disabled')
    }

}


function change_block() {
    ids = ['upload_file', 'import_history']
    block_ids = ['block_upload_file', 'block_import_history']
    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=imported_records-view&value=' + this.id, {});

}

function imported_records_myrenderEvent() {

    ostate = this.getState();
    paginator = ostate.pagination

    if (paginator.totalRecords <= paginator.rowsPerPage) {
        Dom.setStyle('paginator' + this.table_id, 'display', 'none')
    }
    get_imported_records_elements()
}

    function get_imported_records_elements() {
        var ar_file = 'ar_import.php';
        var request = 'tipo=get_imported_records_elements&subject=' + Dom.get('subject').value + '&parent=' + Dom.get('parent').value + '&parent_key=' + Dom.get('parent_key').value
         //alert(request)
        //Dom.get(['elements_Error_number','elements_Excess_number','elements_Normal_number','elements_Low_number','elements_VeryLow_number','elements_OutofStock_number']).innerHTML='<img src="art/loading.gif" style="height:12.9px" />';
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

    function init() {
        Event.addListener(['upload_file', 'import_history'], "click", change_block);


        Event.addListener("upload_import_file", "change", check_if_file_selected);
        Event.addListener("save_upload_button", "click", save_upload);

        init_search(Dom.get('search_type').value);
        state_imported_records = YAHOO.lang.JSON.parse(base64_decode(Dom.get('state_imported_records').value))
        gettext_strings = YAHOO.lang.JSON.parse(base64_decode(Dom.get('gettext_strings').value))


    }

    YAHOO.util.Event.onDOMReady(init);
