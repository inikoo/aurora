var Dom = YAHOO.util.Dom;

YAHOO.util.Event.addListener(window, "load", function() {

    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;


    tables = new function() {

        this.remove_links = function(elLiner, oRecord, oColumn, oData) {
            elLiner.innerHTML = '';
            if (oData != undefined) {
                elLiner.innerHTML = oData.replace(/<.*?>/g, '');
            }
        };

        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;



        var tableid = 0;
        var tableDivEL = "table" + tableid;

        var ColumnDefs = [{
            key: "key",
            label: "",
            hidden: true
        }, {
            key: "code",
            formatter: "remove_links",
            label: labels.Code,
            width: 50,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "label",
            formatter: "remove_links",
            label: labels.Name,
            width: 200,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "subjects",
            label: labels.Parts,
            width: 60,
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }];
        request = 'ar_quick_tables.php?tipo=category_list&parent_key=' + Dom.get('part_families_root_category_key').value + '&branch_type=Head' + "&tableid=" + tableid + "&nr=20&sf=0";

        this.dataSource0 = new YAHOO.util.DataSource(request);
        this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource0.connXhrMode = "queueRequests";
        this.dataSource0.table_id = tableid;

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
            fields: ["label", 'code', 'key', 'subjects']
        };


        this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource0, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: 20,
                containers: 'paginator0',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                alwaysVisible: false,
                template: "{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}"
            })

            ,
            sortedBy: {
                key: 'code',
                dir: ''
            },
            dynamicData: true

        }

        );

        this.table0.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
        //this.table0.subscribe("cellClickEvent", this.table0.onEventShowCellEditor);
        this.table0.prefix = '';
        this.table0.subscribe("rowMouseoverEvent", this.table0.onEventHighlightRow);
        this.table0.subscribe("rowMouseoutEvent", this.table0.onEventUnhighlightRow);
        this.table0.subscribe("rowClickEvent", select_part_family_from_list);

        this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table0.filter = {
            key: 'code',
            value: ''
        };








    };
});

function select_part_family_from_list(oArgs) {

    record = tables.table0.getRecord(oArgs.target);


    Dom.get('part_family_key').value = record.getData('key');

    Dom.get('part_family').innerHTML = record.getData('code')
    Dom.get('part_family').title = record.getData('label')
    dialog_part_families_list.hide();

    validate_scope_data.part.part_family_key.validated = true;
   

    validate_scope('part')


}

function validate_part_description(query) {
    validate_general('part', 'part_description', unescape(query));
}

function validate_part_reference(query) {
    validate_general('part', 'part_reference', unescape(query));
}





function reset_new_employee() {
    reset_edit_general('staff')
}



function save_new_part() {
    save_new_general('part');
}

function post_action(branch, r) {
    window.location.href = 'part.php?sku=' + r.object_key;
}


function show_part_families_dialog() {

    region1 = Dom.getRegion('show_part_families_dialog');
    region2 = Dom.getRegion('dialog_part_families_list');
    var pos = [region1.left - region2.width, region1.top]
    Dom.setXY('dialog_part_families_list', pos);

    dialog_part_families_list.show()


}

function init() {

    switch (Dom.get('parent').value) {
    case 'suppliers':
        init_search('supplier_products_supplier');
        break;
    case 'parts':
        init_search('parts');
        break;
    default:

    }

    // init_search('parts');
    validate_scope_data = {

        'part': {
            'part_reference': {
                'changed': true,
                'validated': false,
                'required': false,
                'dbname': 'Part Reference',
                'group': 1,
                'type': 'item',
                'name': 'part_reference',
                'ar': 'find',
                'ar_request': 'ar_parts.php?tipo=is_part_reference&query=',

            },
            'part_description': {
                'changed': true,
                'validated': false,
                'required': true,
                'dbname': 'Part Unit Description',
                'group': 1,
                'type': 'item',
                'name': 'part_description',
                'ar': false,
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': 'Invalid Part Description'
                }]
            },
            'sp_pid': {
                'changed': false,
                'validated': (Dom.get('parent').value == 'suppliers' ? true : false),
                'required': true,
                'group': 1,
                'type': 'item',
                'name': 'sp_pid',
                'dbname': 'Supplier Product ID',
                'validation': [{
                    'numeric': 'positive',
                    'invalid_msg': ''
                }]
            },
            'part_family_key': {
                'changed': false,
                'validated': (Dom.get('parent').value == 'parts' ? true : false),
                'required': true,
                'group': 1,
                'type': 'item',
                'name': 'part_family_key',
                'dbname': 'Part Family Key',
                'validation': [{
                    'numeric': 'positive',
                    'invalid_msg': ''
                }]
            },


        }
    };




    validate_scope_metadata = {
        'part': {
            'type': 'new',
            'ar_file': 'ar_edit_parts.php',
            'key_name': 'sp_pid',
            'key': Dom.get('sp_pid').value
        }


    };




    var part_description_oACDS = new YAHOO.util.FunctionDataSource(validate_part_description);
    part_description_oACDS.queryMatchContains = true;
    var part_description_oAutoComp = new YAHOO.widget.AutoComplete("part_description", "part_description_Container", part_description_oACDS);
    part_description_oAutoComp.minQueryLength = 0;
    part_description_oAutoComp.queryDelay = 0.1;

    var part_reference_oACDS = new YAHOO.util.FunctionDataSource(validate_part_reference);
    part_reference_oACDS.queryMatchContains = true;
    var part_reference_oAutoComp = new YAHOO.widget.AutoComplete("part_reference", "part_reference_Container", part_reference_oACDS);
    part_reference_oAutoComp.minQueryLength = 0;
    part_reference_oAutoComp.queryDelay = 0.1;

    //  YAHOO.util.Event.addListener('reset_new_part', "click",reset_new_part)
    YAHOO.util.Event.addListener('save_new_part', "click", save_new_part)


    dialog_part_families_list = new YAHOO.widget.Dialog("dialog_part_families_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_part_families_list.render();

	if(Dom.get('part_reference').value!=''){
	validate_part_reference(Dom.get('part_reference').value)
	}
	
	if(Dom.get('part_description').value!=''){
	
	validate_part_description(Dom.get('part_description').value)
	}
	
	
	Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
	Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);

  
    var oACDS0 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS0.queryMatchContains = true;
    oACDS0.table_id = 0;
    var oAutoComp0 = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS0);
    oAutoComp0.minQueryLength = 0;


}


YAHOO.util.Event.onDOMReady(init);
