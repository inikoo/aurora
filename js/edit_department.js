var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;

function delete_department() {
    region1 = Dom.getRegion('delete');
    var pos = [region1.left, region1.bottom]
    Dom.setXY('dialog_delete_department', pos);

    Dom.setStyle(['save_delete_department', 'cancel_delete_department', 'delete_department_warning'], 'display', '');
    Dom.setStyle('delete_department', 'display', 'none');
}

function cancel_delete_department() {
    Dom.setStyle(['save_delete_department', 'cancel_delete_department', 'delete_department_warning'], 'display', 'none');
    Dom.setStyle('delete_department', 'display', '');
}

function save_delete_department() {


    var request = 'ar_edit_assets.php?tipo=delete_department&delete_type=delete&id=' + Dom.get('department_key').value

    Dom.setStyle('deleting', 'display', '');
    Dom.setStyle(['save_delete_department', 'cancel_delete_department'], 'display', 'none');
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            // alert(o.responseText);	
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                location.href = 'department.php?id=' + Dom.get('department_key').value;
            } else {
                Dom.setStyle('deleting', 'display', 'none');
                Dom.get('delete_department_msg').innerHTML = r.msg
            }
        }
    });


}

function show_history() {
    Dom.setStyle(['show_history', ''], 'display', 'none')
    Dom.setStyle(['hide_history', 'history_table'], 'display', '')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=site-show_history&value=1', {});
}

function hide_history() {
    Dom.setStyle(['show_history', ''], 'display', '')
    Dom.setStyle(['hide_history', 'history_table'], 'display', 'none')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=site-show_history&value=0', {});
}

function change_block(e) {

 Dom.setStyle(['d_families','d_details','d_web'],'display','none')
     Dom.setStyle('d_' + this.id,'display','')

   
    ids = ['families', 'details', 'web'];

    Dom.removeClass(ids, 'selected');

    Dom.addClass(this, 'selected');
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=department-editing&value=' + this.id, {});


}

var CellEdit = function(callback, newValue) {


        var record = this.getRecord(),
            column = this.getColumn(),
            oldValue = this.value,
            datatable = this.getDataTable(),
            recordIndex = datatable.getRecordIndex(record);

        //alert(	'tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ //myBuildUrl(datatable,record))
        if (column.object == 'department_page_properties' || column.object == 'family_page_properties') request_page = 'ar_edit_sites.php';

        else request_page = 'ar_edit_assets.php';




        YAHOO.util.Connect.asyncRequest('POST', request_page, {
            success: function(o) {
                //	alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {

                    if (column.key == 'price' || column.key == 'unit_price' || column.key == 'margin') {

                        datatable.updateCell(record, 'unit_price', r.newdata['Unit Price']);
                        datatable.updateCell(record, 'margin', r.newdata['Margin']);
                        datatable.updateCell(record, 'price', r.newdata['Price']);
                        datatable.updateCell(record, 'rrp_info', r.newdata['RRP Margin']);



                        //datatable.updateRow(recordIndex,data);
                        callback(true, r.newvalue);

                    } else if (column.key == 'unit_rrp') {
                        datatable.updateCell(record, 'unit_rrp', r.newdata['RRP Per Unit']);
                        datatable.updateCell(record, 'rrp_info', r.newdata['RRP Margin']);

                        callback(true, r.newvalue);

                    } else {

                        callback(true, r.newvalue);

                    }
                } else {
                    alert(r.msg);
                    callback();
                }
            },
            failure: function(o) {
                alert(o.statusText);
                callback();
            },
            scope: this
        }, 'tipo=edit_' + column.object + '&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue) + myBuildUrl(datatable, record)

        );
    };




function show_new_family_dialog() {
    Dom.setStyle('new_family_dialog', 'display', '');
    Dom.setStyle('cancel_new_family', 'visibility', 'visible');
    Dom.setStyle('save_new_family', 'visibility', 'visible');
    Dom.addClass('save_new_family', 'disabled');

    Dom.setStyle(['show_new_family_dialog_button', 'import_new_family'], 'display', 'none');
}

function save_new_family() {
    save_new_general('family')
}


function post_new_create_actions(branch, response) {

    cancel_new_family();
}

function cancel_new_family() {
    Dom.setStyle('new_family_dialog', 'display', 'none');
    Dom.setStyle('cancel_new_family', 'visibility', 'hidden');
    Dom.setStyle('save_new_family', 'visibility', 'hidden');
    Dom.addClass('save_new_family', 'disabled');

    Dom.setStyle(['show_new_family_dialog_button', 'import_new_family'], 'display', '');

    cancel_new_general('family');


}







YAHOO.util.Event.addListener(window, "load", function() {

    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;
    state = session_data.state;



    tables = new function() {




        var tableid = 0; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;
        var OrdersColumnDefs = [{
            key: "id",
            label: "",
            hidden: true,
            action: "none",
            isPrimaryKey: true
        }, {
            key: "go",
            label: "",
            width: 20,
            action: "none"
        }, {
            key: "code",
            label: labels.Code,
            width: 70,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            },
            editor: new YAHOO.widget.TextboxCellEditor({
                asyncSubmitter: CellEdit
            }),
            object: 'family'
        }, {
            key: "name",
            label: labels.Name,
            width: 250,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            },
            editor: new YAHOO.widget.TextboxCellEditor({
                asyncSubmitter: CellEdit
            }),
            object: 'family'
        }

        , {
            key: "sales_type",
            label: labels.Sale_Type,
            width: 150,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            },
            object: 'family',
            editor: new YAHOO.widget.RadioCellEditor({
                asyncSubmitter: CellEdit,
                radioOptions: [{
                    label: labels.Public_Sale,
                    value: 'Public Sale'
                }, {
                    label: labels.Private_Sale,
                    value: 'Private Sale'
                }, {
                    label: labels.Not_For_Sale,
                    value: 'Not For Sale'
                }],
                disableBtns: true
            })
        }

        ];
        request = "ar_edit_assets.php?tipo=edit_families&parent=department&sf=0&tableid=0&parent_key=" + Dom.get('department_key').value;
        
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

            fields: ["code", "name", 'sales_type', 'id', 'edit', 'go']
        };

        this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs, this.dataSource0, {
            //draggableColumns:true,
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.department.families.nr,
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
                key: state.department.families.order,
                dir: state.department.families.order_dir
            },
            dynamicData: true

        });
        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table0.table_id = tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);




        this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
        this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
        this.table0.subscribe("cellClickEvent", onCellClick);

        this.table0.filter = {
            key: state.department.families.f_field,
            value: state.department.families.f_value
        };



        var tableid = 1; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;

        var CustomersColumnDefs = [{
            key: "date",
            label: labels.Date,
            width: 200,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "author",
            label: labels.Author,
            width: 70,
            sortable: true,
            formatter: this.customer_name,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "abstract",
            label: labels.Description,
            width: 370,
            sortable: true,
            formatter: this.customer_name,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }];
        //?tipo=customers&tid=0"
        this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=department&tableid=1&sf=0");
        this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource1.connXhrMode = "queueRequests";
        this.dataSource1.responseSchema = {
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


            fields: ["id", "note", 'author', 'date', 'tipo', 'abstract', 'details']
        };

        this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs, this.dataSource1, {

            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.department.history.nr,
                containers: 'paginator1',
                alwaysVisible: false,
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
            })

            ,
            sortedBy: {
                key: state.department.history.order,
                dir: state.department.history.order_dir
            },
            dynamicData: true

        }

        );

        this.table1.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table1.table_id = tableid;
        this.table1.subscribe("renderEvent", myrenderEvent);


        this.table1.filter = {
            key: state.department.history.f_field,
            value: state.department.history.f_value
        };


        var tableid = 6; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;


        var CustomersColumnDefs = [{
            key: "id",
            label: "",
            hidden: true,
            action: "none",
            isPrimaryKey: true
        }, {
            key: "go",
            label: "",
            width: 20,
            action: "none"
        }, {
            key: "site",
            label: labels.website,
            width: 70,
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
            },
            editor: new YAHOO.widget.TextboxCellEditor({
                asyncSubmitter: CellEdit
            }),
            object: 'family_page_properties'
        }, {
            key: "store_title",
            label: labels.Header_Title,
            hidden: (state.department.edit_pages.view == 'page_header' ? false : true),
            width: 400,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            },
            editor: new YAHOO.widget.TextboxCellEditor({
                asyncSubmitter: CellEdit
            }),
            object: 'family_page_properties'
        }, {
            key: "link_title",
            label: labels.Link_Title,
            hidden: (state.department.edit_pages.view == 'page_properties' ? false : true),
            width: 250,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            },
            editor: new YAHOO.widget.TextboxCellEditor({
                asyncSubmitter: CellEdit
            }),
            object: 'family_page_properties'
        }, {
            key: "page_title",
            label: labels.Browser_Title,
            hidden: (state.department.edit_pages.view == 'page_html_head' ? false : true),
            width: 300,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            },
            editor: new YAHOO.widget.TextboxCellEditor({
                asyncSubmitter: CellEdit
            }),
            object: 'family_page_properties'
        }, {
            key: "page_description",
            label: labels.Description,
            hidden: (state.department.edit_pages.view == 'page_html_head' ? false : true),
            width: 270,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            },
            editor: new YAHOO.widget.TextboxCellEditor({
                asyncSubmitter: CellEdit
            }),
            object: 'family_page_properties'
        }, {
            key: "delete",
            label: "",
            width: 12,
            sortable: false,
            action: 'delete',
            object: 'page_store'
        }];




        //?tipo=customers&tid=0"
        this.dataSource6 = new YAHOO.util.DataSource("ar_edit_sites.php?tipo=pages&parent=department&sf=0&parent_key=" + Dom.get('department_key').value + "&tableid=6");

        //alert("ar_edit_sites.php?tipo=family_page_list&site_key="+Dom.get('site_key').value+"&parent=family&parent_key="+Dom.get('family_key').value+"&tableid=6")
        this.dataSource6.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource6.connXhrMode = "queueRequests";
        this.dataSource6.responseSchema = {
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


            fields: ["id", "go", "code", "store_title", "delete", "link_title", "url", "page_title", "page_keywords", "site"

            ]
        };

        this.table6 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs, this.dataSource6, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: state.department.edit_pages.nr,
                containers: 'paginator6',
                alpartysVisible: false,
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                template: "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
            })

            ,
            sortedBy: {
                key: state.department.edit_pages.order,
                dir: state.department.edit_pages.order_dir
            },
            dynamicData: true
        });

        this.table6.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table6.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table6.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table6.table_id = tableid;
        this.table6.subscribe("renderEvent", myrenderEvent);

        this.table6.subscribe("cellMouseoverEvent", highlightEditableCell);
        this.table6.subscribe("cellMouseoutEvent", unhighlightEditableCell);
        this.table6.subscribe("cellClickEvent", onCellClick);

        this.table6.filter = {
            key: state.department.edit_pages.f_field,
            value: state.department.edit_pages.f_value
        };




    };
});


function validate_code(query) {

    validate_general('department', 'code', unescape(query));
}

function validate_name(query) {
    validate_general('department', 'name', unescape(query));
}

function validate_family_code(query) {

    validate_general('family', 'code', unescape(query));
}

function validate_family_name(query) {
    validate_general('family', 'name', unescape(query));
}

function validate_family_special_char(query) {
    validate_general('family', 'special_char', unescape(query));
}





function reset_edit_department() {
    reset_edit_general('department');
}

function save_edit_department() {
    save_edit_general('departmenty');
}



function post_new_create_actions(branch, r) {

    var table_id = 0
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '&tableid=' + table_id + '&sf=0';
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

}

function post_item_updated_actions(branch, r) {
    key = r.key;
    newvalue = r.newvalue;
    if (key == 'name') {
        Dom.get('title_name').innerHTML = newvalue;
        Dom.get('title_name_bis').innerHTML = newvalue;

    } else if (key == 'code') {
        Dom.get('title_code').innerHTML = newvalue;
    }
    var table = tables.table1;
    var datasource = tables.dataSource1;
    var request = '';
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

}

function show_new_department_page_dialog() {

    var number_sites = Dom.get('number_sites').value;

    if (number_sites == 0) {
        return;
    } else if (number_sites == 1) {
        new_department_page(Dom.get('site_key').value);
    } else {
        alert("todo")
    }

}

function update_page_preview_snapshot(page_key) {
    YAHOO.util.Connect.asyncRequest('POST', 'ar_edit_sites.php?tipo=update_page_preview_snapshot&id=' + page_key, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
        }
    });
}

function new_department_page(site_key) {


    var request = 'tipo=new_page&parent=department&parent_key=' + Dom.get('department_key').value + '&site_key=' + site_key

    YAHOO.util.Connect.asyncRequest('POST', 'ar_edit_sites.php', {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                window.location = 'edit_page.php?view=setup&id=' + r.page_key
            } else {

                alert(r.msg)

            }

        },
        failure: function(o) {
            alert(o.statusText);
            callback();
        },
        scope: this
    }, request

    );



}

function change_details_sublock(e) {

    ids = [ 'details_subtab_code', 'details_subtab_info', 'details_subtab_discounts', 'details_subtab_pictures']

    block_ids = [ 'd_details_subtab_code', 'd_details_subtab_info', 'd_details_subtab_discounts', 'd_details_subtab_pictures']

    sub_block = Dom.get(this).getAttribute('block_id')

    Dom.setStyle(block_ids, 'display', 'none');

    Dom.get('d_details_subtab_' + sub_block).style.display = '';
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=department-edit_details_subtab&value=' + sub_block, {});

}

function general_description_editor_changed() {
	validate_scope_data['department_general_description']['Department_Description']['changed'] = true;
   	validate_scope('department_general_description')
 }
 
 function validate_department_description(query){

 validate_general('department_general_description','Department_Description',unescape(query));
}
function reset_edit_department_general_description() {
	reset_edit_general('department_general_description')
	
    //GeneralDescriptionEditor.setEditorHTML(Dom.get('Department_Description').value);
}

function save_edit_department_general_description() {
//GeneralDescriptionEditor.saveHTML();
	save_edit_general('department_general_description');
}



function init() {


    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;


    //YAHOO.util.Event.addListener('delete_department', "click", delete_department);
    YAHOO.util.Event.addListener('cancel_delete_department', "click", cancel_delete_department);
    YAHOO.util.Event.addListener('save_delete_department', "click", save_delete_department);

    ids = ['page_properties', 'page_html_head', 'page_header'];
    YAHOO.util.Event.addListener(ids, "click", change_edit_pages_view, {
        'table_id': 6,
        'parent': 'page'
    })


    init_search('products_store');
    validate_scope_metadata = {
        'department': {
            'type': 'edit',
            'ar_file': 'ar_edit_assets.php',
            'key_name': 'id',
            'key': Dom.get('department_key').value
        },
         'department_general_description': {
            'type': 'edit',
            'ar_file': 'ar_edit_assets.php',
            'key_name': 'id',
            'key': Dom.get('department_key').value
        },
        'family': {
            'type': 'new',
            'ar_file': 'ar_edit_assets.php',
            'key_name': 'department_id',
            'key': Dom.get('department_key').value
        }
    };
    


    validate_scope_data = {
        'department': {
            'name': {
                'changed': false,
                'validated': true,
                'required': true,
                'group': 1,
                'type': 'item',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_Name
                }],

                'name': 'name',
                'ar': 'find',
                'ar_request': 'ar_assets.php?tipo=is_department_name&store_key=' + Dom.get('store_key').value + '&query='
            },
            'code': {
                'changed': false,
                'validated': true,
                'required': false,
                'group': 1,
                'type': 'item',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_Code
                }],
                'name': 'code',
                'ar': 'find',
                'ar_request': 'ar_assets.php?tipo=is_department_code&store_key=' + Dom.get('store_key').value + '&query='
            }
        },
        'family': {
            'name': {
                'changed': false,
                'validated': false,
                'required': true,
                'group': 1,
                'type': 'item',
                'dbname': 'Product Family Name',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_Name
                }],
                'name': 'family_name',
                'ar': 'find',
                'ar_request': 'ar_assets.php?tipo=is_family_name&store_key=' + Dom.get('store_key').value + '&query='
            },
            'code': {
                'changed': false,
                'validated': false,
                'required': true,
                'group': 1,
                'type': 'item',
                'dbname': 'Product Family Code',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_Code
                }],
                'name': 'family_code',
                'ar': 'find',
                'ar_request': 'ar_assets.php?tipo=is_family_code&store_key=' + Dom.get('store_key').value + '&query='
            },
            'special_char': {
                'changed': false,
                'validated': false,
                'required': false,
                'group': 1,
                'type': 'item',
                'dbname': 'Product Family Special Characteristic',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_String
                }],
                'name': 'family_special_char',
                'ar': false,
                'ar_request': false
            },
            'description': {
                'changed': false,
                'validated': false,
                'required': false,
                'group': 1,
                'type': 'textarea',
                'dbname': 'Product Family Description',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_Description
                }],
                'name': 'family_description',
                'ar': false,
                'ar_request': false
            }
        },
         'department_general_description':{
		'Department_Description': {
			'changed': false,
			'validated': true,
			'required': false,
			'group': 2,
			'type': 'item',
			'dbname': 'Product Department Description',
			'name': 'Department_Description',
			'ar': false,
			'validation': false
		}
	}  
    };

    //   var ids = ["checkbox_thumbnails","checkbox_list","checkbox_slideshow","checkbox_manual"]; 
    //   YAHOO.util.Event.addListener(ids, "click", select_layout);
    //YAHOO.util.Event.on('uploadButton', 'click', onUploadButtonClick);
    YAHOO.util.Event.addListener('new_department_page', "click", show_new_department_page_dialog);

    YAHOO.util.Event.addListener('cancel_new_family', "click", cancel_new_family);
    YAHOO.util.Event.addListener('save_new_family', "click", save_new_family);

    YAHOO.util.Event.on('uploadButton', 'click', upload_image);



    var department_code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    department_code_oACDS.queryMatchContains = true;
    var department_code_oAutoComp = new YAHOO.widget.AutoComplete("code", "code_Container", department_code_oACDS);
    department_code_oAutoComp.minQueryLength = 0;
    department_code_oAutoComp.queryDelay = 0.1;

    var department_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    department_name_oACDS.queryMatchContains = true;
    var department_name_oAutoComp = new YAHOO.widget.AutoComplete("name", "name_Container", department_name_oACDS);
    department_name_oAutoComp.minQueryLength = 0;
    department_name_oAutoComp.queryDelay = 0.1;

    var family_code_oACDS = new YAHOO.util.FunctionDataSource(validate_family_code);
    family_code_oACDS.queryMatchContains = true;
    var family_code_oAutoComp = new YAHOO.widget.AutoComplete("family_code", "family_code_Container", family_code_oACDS);
    family_code_oAutoComp.minQueryLength = 0;
    family_code_oAutoComp.queryDelay = 0.1;

    var family_name_oACDS = new YAHOO.util.FunctionDataSource(validate_family_name);
    family_name_oACDS.queryMatchContains = true;
    var family_name_oAutoComp = new YAHOO.widget.AutoComplete("family_name", "family_name_Container", family_name_oACDS);
    family_name_oAutoComp.minQueryLength = 0;
    family_name_oAutoComp.queryDelay = 0.1;


    var family_special_char_oACDS = new YAHOO.util.FunctionDataSource(validate_family_special_char);
    family_special_char_oACDS.queryMatchContains = true;
    var family_special_char_oAutoComp = new YAHOO.widget.AutoComplete("family_special_char", "family_special_char_Container", family_special_char_oACDS);
    family_special_char_oAutoComp.minQueryLength = 0;
    family_special_char_oAutoComp.queryDelay = 0.1;


  var department_name_oACDS = new YAHOO.util.FunctionDataSource(validate_department_description);
	department_name_oACDS.queryMatchContains = true;
	var department_name_oAutoComp = new YAHOO.widget.AutoComplete("Department_Description","Department_Description_Container", department_name_oACDS);
	department_name_oAutoComp.minQueryLength = 0; 
	department_name_oAutoComp.queryDelay = 0.1;
  




    function mygetTerms(query) {
        multireload();
    };
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0;

    var ids = ["details", "families", "web"];
    YAHOO.util.Event.addListener(ids, "click", change_block);
    
     ids=['details_subtab_code','details_subtab_info','details_subtab_discounts','details_subtab_pictures']
    YAHOO.util.Event.addListener(ids, "click", change_details_sublock);
 
       Event.addListener('save_edit_department_general_description', "click", save_edit_department_general_description);
    Event.addListener('reset_edit_department_general_description', "click", reset_edit_department_general_description);

 
 /*
 var myConfig = {
       
         height: '300px',
        width: '935px',
        animate: true,
        dompath: true,
        focusAtStart: true,
         autoHeight: true
    };


  var state = 'off';
    GeneralDescriptionEditor = new YAHOO.widget.Editor('Department_Description', myConfig);
   
    GeneralDescriptionEditor.on('toolbarLoaded', function() {
    
     var codeConfig = {
            type: 'push', label: 'Edit HTML Code', value: 'editcode'
        };
        this.toolbar.addButtonToGroup(codeConfig, 'insertitem');
        
         this.toolbar.on('editcodeClick', function() {
        

        
            var ta = this.get('element'),iframe = this.get('iframe').get('element');

            if (state == 'on') {
                state = 'off';
                this.toolbar.set('disabled', false);
                          this.setEditorHTML(ta.value);
                if (!this.browser.ie) {
                    this._setDesignMode('on');
                }

                Dom.removeClass(iframe, 'editor-hidden');
                Dom.addClass(ta, 'editor-hidden');
                this.show();
                this._focusWindow();
            } else {
                state = 'on';
                
                this.cleanHTML();
               
                Dom.addClass(iframe, 'editor-hidden');
                Dom.removeClass(ta, 'editor-hidden');
                this.toolbar.set('disabled', true);
                this.toolbar.getButtonByValue('editcode').set('disabled', false);
                this.toolbar.selectButton('editcode');
                this.dompath.innerHTML = 'Editing HTML Code';
                this.hide();
            
            }
            return false;
        }, this, true);

        this.on('cleanHTML', function(ev) {
            this.get('element').value = ev.html;
        }, this, true);
        
        
         this.on('afterRender', function() {
            var wrapper = this.get('editor_wrapper');
            wrapper.appendChild(this.get('element'));
            this.setStyle('width', '100%');
            this.setStyle('height', '100%');
            this.setStyle('visibility', '');
            this.setStyle('top', '');
            this.setStyle('left', '');
            this.setStyle('position', '');

            this.addClass('editor-hidden');
        }, this, true);
    
    this.on('cleanHTML', function(ev) {
            this.get('element').value = ev.html;
        }, this, true);
        
        
         this.on('editorKeyUp', general_description_editor_changed, this, true);
                this.on('editorDoubleClick', general_description_editor_changed, this, true);
                this.on('editorMouseDown', general_description_editor_changed, this, true);
                this.on('buttonClick', general_description_editor_changed, this, true);
    }, GeneralDescriptionEditor, true);
   
   
   
    yuiImgUploader(GeneralDescriptionEditor, 'Department_Description', 'ar_upload_file_from_editor.php','image');
   
   GeneralDescriptionEditor.render();
 */
 
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
