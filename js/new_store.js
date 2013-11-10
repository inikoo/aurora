var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {



        var tableid = 2; // Change if you have more the 1 table
        var tableDivEL = "table" + tableid;
        var ColumnDefs = [
            {
            key: "flag",
            label: "",
            width: 10,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "code",
            label: "<?php echo _('Code')?>",
            width: 25,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "name",
            label: "<?php echo _('Name')?>",
            width: 100,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
            , {
            key: "wregion",
            label: "<?php echo _('Region')?>",
            width: 120,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }

        ];

        this.dataSource2 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=country_list&tableid=2&nr=20&sf=0");
        this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource2.connXhrMode = "queueRequests";
        this.dataSource2.table_id = tableid;

        this.dataSource2.responseSchema = {
            resultsList: "resultset.data",
            metaFields: {
                rtext: "resultset.rtext",
                rtext_rpp: "resultset.rtext_rpp",
                rowsPerPage: "resultset.records_perpage",
                sort_key: "resultset.sort_key",
                sort_dir: "resultset.sort_dir",
                tableid: "resultset.tableid",
                filter_msg: "resultset.filter_msg",
                totalRecords: "resultset.total_records" // Access to value in the server response
            },


            fields: [
                "name", "flag", 'code', 'wregion'
                ]
        };

        this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource2, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: 20,
                containers: 'paginator2',
                pageReportTemplate: '(' + Dom.get('label_Pages').value + ' {currentPage} ' + Dom.get('label_of').value + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                alwaysVisible: false,
                template: "{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}"
            })

            ,
            sortedBy: {
                key: "code",
                dir: ""
            },
            dynamicData: true

        }

        );

        this.table2.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
        //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);
        this.table2.subscribe("rowMouseoverEvent", this.table2.onEventHighlightRow);
        this.table2.subscribe("rowMouseoutEvent", this.table2.onEventUnhighlightRow);
        this.table2.subscribe("rowClickEvent", select_country);



        this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table2.filter = {
            key: 'code',
            value: ''
        };




    };

});


function save_new_store() {
    save_new_general('store');


}

function post_new_create_actions(branch, response) {
    window.location = "store.php?view=details&id=" + response.store_key


}

function cancel_add_store() {
    window.location = "edit_stores.php"
}


function validate_code(query) {
    validate_general('store', 'code', unescape(query));

}


function validate_name(query) {
    validate_general('store', 'name', unescape(query));

}


function change_locate(o) {

    ids = Dom.getElementsByClassName('radio', 'button', 'locale_container')
    Dom.removeClass(ids, 'selected');
    Dom.addClass(o, 'selected');

    Dom.get('locale').value = o.getAttribute('radio_value')



}

function select_country(oArgs) {

    geo_constraints = tables.table2.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
    Dom.get('Country').value = geo_constraints;
    dialog_country_list.hide();
    hide_filter(true, 2)
    
    validate_scope_data['store']['country']['changed']=true;
        validate_scope_data['store']['country']['validated']=true;

    validate_scope('store')
}

function show_dialog_country_list() {
    region1 = Dom.getRegion('Country');
    region2 = Dom.getRegion('dialog_country_list');
    var pos = [region1.left, region1.bottom]
    Dom.setXY('dialog_country_list', pos);
    dialog_country_list.show()
}

function init() {

    dialog_country_list = new YAHOO.widget.Dialog("dialog_country_list", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_country_list.render();
    Event.addListener("country_button", "click", show_dialog_country_list);

    var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS2.queryMatchContains = true;
    oACDS2.table_id = 2;
    var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2", "f_container2", oACDS2);
    oAutoComp2.minQueryLength = 0;
    YAHOO.util.Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
    YAHOO.util.Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);

    validate_scope_data = {

        'store': {
            'code': {
                'changed': false,
                'validated': false,
                'required': true,
                'dbname': 'Store Code',
                'group': 1,
                'type': 'item',
                'name': 'Code',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('invalid_store_code').value
                }],
                'ar': 'find',
                'ar_request': 'ar_assets.php?tipo=is_store_code&query='
            },
            'name': {
                'changed': false,
                'validated': false,
                'required': true,
                'dbname': 'Store Name',
                'group': 1,
                'type': 'item',
                'name': 'Name',

                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('invalid_store_name').value
                }],
                'ar': false
            },
            'country': {
                'changed': false,
                'validated': false,
                'required': true,
                'dbname': 'Country Code',
                'group': 1,
                'type': 'item',
                'name': 'country',

                'validation': false,
                'ar': false
            },
            'locale': {
                'changed': true,
                'validated': true,
                'required': true,
                'dbname': 'Store Locale',
                'group': 1,
                'type': 'item',
                'name': 'locale',

                'validation': false,
                'ar': false
            }



        }



    };




    validate_scope_metadata = {
        'store': {
            'type': 'new',
            'ar_file': 'ar_edit_assets.php',
            'key_name': 'store_key',
            'key': ''
        }


    };




    init_search('products');



    YAHOO.util.Event.addListener('save_new_store', "click", save_new_store);
    YAHOO.util.Event.addListener('close_add_store', "click", cancel_add_store);


    var code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    code_oACDS.queryMatchContains = true;
    var code_oAutoComp = new YAHOO.widget.AutoComplete("Code", "Code_Container", code_oACDS);
    code_oAutoComp.minQueryLength = 0;
    code_oAutoComp.queryDelay = 0.1;
    var name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    name_oACDS.queryMatchContains = true;
    var name_oAutoComp = new YAHOO.widget.AutoComplete("Name", "Name_Container", name_oACDS);
    name_oAutoComp.minQueryLength = 0;
    name_oAutoComp.queryDelay = 0.1;


}





YAHOO.util.Event.onDOMReady(init);
