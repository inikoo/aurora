var Dom = YAHOO.util.Dom;
var session_data, labels;
var asset_select_scope;
var validate_scope_data;

YAHOO.util.Event.addListener(window, "load", function() {

    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;


    tables = new function() {

        this.remove_links = function(elLiner, oRecord, oColumn, oData) {
            elLiner.innerHTML = '';
            if(oData!= undefined){
            elLiner.innerHTML = oData.replace(/<.*?>/g, '');
}
        };

        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;



        var tableid = 101;
        var tableDivEL = "table" + tableid;

        var ColumnDefs = [{
            key: "key",
            label: "",
            hidden: true
        }, {
            key: "code",
            formatter: "remove_links",
            label: labels.Code,
            width: 30,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "name",
            formatter: "remove_links",
            label: labels.Name,
            width: 200,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }];
        request = "ar_quick_tables.php?tipo=department_list&store_key=" + Dom.get('store_key').value + "&tableid=" + tableid + "&nr=20&sf=0";
        this.dataSource101 = new YAHOO.util.DataSource(request);
        this.dataSource101.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource101.connXhrMode = "queueRequests";
        this.dataSource101.table_id = tableid;

        this.dataSource101.responseSchema = {
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
            fields: ["name", 'code', 'key']
        };


        this.table101 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource101, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: 20,
                containers: 'paginator101',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                alwaysVisible: false,
                template: "{PreviousPageLink}<strong id='paginator_info101'>{CurrentPageReport}</strong>{NextPageLink}"
            })

            ,
            sortedBy: {
                key: 'code',
                dir: ''
            },
            dynamicData: true

        }

        );

        this.table101.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table101.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table101.prefix = '';
        this.table101.subscribe("rowMouseoverEvent", this.table101.onEventHighlightRow);
        this.table101.subscribe("rowMouseoutEvent", this.table101.onEventUnhighlightRow);
        this.table101.subscribe("rowClickEvent", select_department_from_list);
        this.table101.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table101.filter = {
            key: 'code',
            value: ''
        };

        var tableid = 102;
        var tableDivEL = "table" + tableid;

        var ColumnDefs = [{
            key: "key",
            label: "",
            hidden: true
        }, {
            key: "code",
            formatter: "remove_links",
            label: labels.Code,
            width: 30,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "name",
            formatter: "remove_links",
            label: labels.Name,
            width: 200,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }];
        request = "ar_quick_tables.php?tipo=family_list&store_key=" + Dom.get('store_key').value + "&tableid=" + tableid + "&nr=20&sf=0";
        this.dataSource102 = new YAHOO.util.DataSource(request);
        this.dataSource102.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource102.connXhrMode = "queueRequests";
        this.dataSource102.table_id = tableid;

        this.dataSource102.responseSchema = {
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
            fields: ["name", 'code', 'key']
        };


        this.table102 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource102, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: 20,
                containers: 'paginator102',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                alwaysVisible: false,
                template: "{PreviousPageLink}<strong id='paginator_info102'>{CurrentPageReport}</strong>{NextPageLink}"
            })

            ,
            sortedBy: {
                key: 'code',
                dir: ''
            },
            dynamicData: true

        }

        );

        this.table102.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table102.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table102.prefix = '';
        this.table102.subscribe("rowMouseoverEvent", this.table102.onEventHighlightRow);
        this.table102.subscribe("rowMouseoutEvent", this.table102.onEventUnhighlightRow);
        this.table102.subscribe("rowClickEvent", select_family_from_list);
        this.table102.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table102.filter = {
            key: 'code',
            value: ''
        };



        var tableid = 103;
        var tableDivEL = "table" + tableid;

        var ColumnDefs = [{
            key: "key",
            label: "",
            hidden: true
        }, {
            key: "code",
            formatter: "remove_links",
            label: labels.Code,
            width: 70,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "name",
            formatter: "remove_links",
            label: labels.Name,
            width: 160,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }];
        request = "ar_quick_tables.php?tipo=product_list&parent=store&parent_key=" + Dom.get('store_key').value + "&tableid=" + tableid + "&nr=20&sf=0";

        this.dataSource103 = new YAHOO.util.DataSource(request);
        this.dataSource103.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource103.connXhrMode = "queueRequests";
        this.dataSource103.table_id = tableid;

        this.dataSource103.responseSchema = {
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
            fields: ["name", 'code', 'id', 'key']
        };


        this.table103 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource103, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: 20,
                containers: 'paginator103',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                alwaysVisible: false,
                template: "{PreviousPageLink}<strong id='paginator_info103'>{CurrentPageReport}</strong>{NextPageLink}"
            })

            ,
            sortedBy: {
                key: 'code',
                dir: ''
            },
            dynamicData: true

        }

        );

        this.table103.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table103.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table103.prefix = '';
        this.table103.subscribe("rowMouseoverEvent", this.table103.onEventHighlightRow);
        this.table103.subscribe("rowMouseoutEvent", this.table103.onEventUnhighlightRow);
        this.table103.subscribe("rowClickEvent", select_product_from_list);
        this.table103.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table103.filter = {
            key: 'code',
            value: ''
        };

        
        var tableid = 105;
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
            key: "name",
            formatter: "remove_links",
            label: labels.Name,
            width: 180,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }];
        request = "ar_quick_tables.php?tipo=deal_list&store_key=" + Dom.get('store_key').value + "&tableid=" + tableid + "&nr=20&sf=0";

        this.dataSource105 = new YAHOO.util.DataSource(request);
        this.dataSource105.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource105.connXhrMode = "queueRequests";
        this.dataSource105.table_id = tableid;

        this.dataSource105.responseSchema = {
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
            fields: ["name", 'code', 'key']
        };


        this.table105 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource105, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: 20,
                containers: 'paginator105',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                alwaysVisible: false,
                template: "{PreviousPageLink}<strong id='paginator_info105'>{CurrentPageReport}</strong>{NextPageLink}"
            })

            ,
            sortedBy: {
                key: 'code',
                dir: ''
            },
            dynamicData: true

        }

        );

        this.table105.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table105.doBeforeSortColumn = mydoBeforeSortColumn;
        //this.table105.subscribe("cellClickEvent", this.table105.onEventShowCellEditor);
        this.table105.prefix = '';
        this.table105.subscribe("rowMouseoverEvent", this.table105.onEventHighlightRow);
        this.table105.subscribe("rowMouseoutEvent", this.table105.onEventUnhighlightRow);
        this.table105.subscribe("rowClickEvent", select_deal_from_list);

        this.table105.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table105.filter = {
            key: 'code',
            value: ''
        };

       





    };
});

function trigger_changed(value) {
    Dom.setStyle(['trigger_department_options', 'trigger_family_options', 'trigger_product_options', 'trigger_customer_options', 'trigger_customer_category_options', 'trigger_customer_list_options'], 'display', 'none')
    Dom.setStyle(['department_terms_select', 'family_terms_select', 'product_terms_select', 'customer_terms_select'], 'display', 'none')

    Dom.setStyle(['voucher_tr'], 'display', '')

    switch (value) {
    case 'Department':
        show_dialog_departments_list('trigger');
        Dom.setStyle(['trigger_department_options', 'department_terms_select'], 'display', '')
        terms_changed('Department Quantity Ordered')
        break;
    case 'Family':
        show_dialog_families_list('trigger');
        Dom.setStyle(['trigger_family_options', 'family_terms_select'], 'display', '')
        terms_changed('Family Quantity Ordered')
        break;
    case 'Product':
        show_dialog_products_list('trigger');
        Dom.setStyle(['trigger_product_options', 'product_terms_select'], 'display', '')

        terms_changed('Family Quantity Ordered')
        break;
    case 'Customer':
        show_dialog_customers_list();
        terms_changed('Voucher')
        break;
    case 'Customer Category':
        show_dialog_customer_categories_list();
        terms_changed('Voucher')
        break;
    case 'Customer List':
        show_dialog_customer_lists_list();
        terms_changed('Voucher')
        break;

    case 'Order':
        break;
    }
}



function show_dialog_departments_list(scope) {


    if (scope == 'trigger') {
        var scope_element = 'trigger_select'
        asset_select_scope = 'trigger'
    } else if (scope == 'target_bis') {
        var scope_element = 'allowances_select'
        asset_select_scope = 'target_bis'
    } else {
        var scope_element = 'customer_terms_select'
        asset_select_scope = 'target'
    }

    region1 = Dom.getRegion(scope_element);

    region2 = Dom.getRegion('dialog_departments_list');
    var pos = [region1.left - 2, region1.top + 1]
    Dom.setXY('dialog_departments_list', pos);
    dialog_departments_list.show();

}

function show_dialog_families_list(scope) {

    if (scope == 'trigger') {
        var scope_element = 'trigger_select'
        asset_select_scope = 'trigger'
    } else if (scope == 'target_bis') {
        var scope_element = 'allowances_select'
        asset_select_scope = 'target_bis'
    } else if (scope == 'free_product_from_family') {
        var scope_element = 'allowances_select'
        asset_select_scope = 'free_product_from_family'
    } else {
        var scope_element = 'customer_terms_select'
        asset_select_scope = 'target'
    }
    region1 = Dom.getRegion(scope_element);
    region2 = Dom.getRegion('dialog_families_list');
    var pos = [region1.left - 2, region1.top + 1]
    Dom.setXY('dialog_families_list', pos);
    dialog_families_list.show();
}

function show_dialog_products_list(scope) {

    if (scope == 'trigger') {
        var scope_element = 'trigger_select'
        asset_select_scope = 'trigger'
    } else if (scope == 'target_bis') {
        var scope_element = 'allowances_select'
        asset_select_scope = 'target_bis'
    } else if (scope == 'default_free_product_from_family') {
        var scope_element = 'allowances_select'
        asset_select_scope = 'default_free_product_from_family'
    } else {
        var scope_element = 'customer_terms_select'
        asset_select_scope = 'target'
    }


    if (scope != 'default_free_product_from_family') {
        table_id = 103;
        var table = tables['table' + table_id];
        var datasource = tables['dataSource' + table_id];
        request = "ar_quick_tables.php?tipo=product_list&parent=store&parent_key=" + Dom.get('store_key').value + "&tableid=103&nr=20&sf=0";
        datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    }


    region1 = Dom.getRegion(scope_element);
    region2 = Dom.getRegion('dialog_products_list');
    var pos = [region1.left - 2, region1.top + 1]
    Dom.setXY('dialog_products_list', pos);
    dialog_products_list.show();
}


function show_dialog_deals_list() {
    region1 = Dom.getRegion('allowances_select');
    region2 = Dom.getRegion('dialog_deals_list');
    var pos = [region1.left, region1.top]
    Dom.setXY('dialog_deals_list', pos);
    dialog_deals_list.show();
}



function select_department_from_list(oArgs) {

    record = tables.table101.getRecord(oArgs.target);
    if (asset_select_scope == 'trigger') {
        Dom.get('trigger').value = 'Department';
        Dom.get('trigger_key').value = record.getData('key');
        Dom.get('department_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
        Dom.setStyle('trigger_department_options_options', 'display', '')

    } else if (asset_select_scope == 'target_bis') {

        Dom.get('target').value = 'Department';
        Dom.get('target_key').value = record.getData('key');
        Dom.get('target_bis_department_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
    } else {
        Dom.get('target').value = 'Department';
        Dom.get('target_key').value = record.getData('key');
        Dom.get('target_department_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
    }
    dialog_departments_list.hide();

}

function select_family_from_list(oArgs) {

    //alert(asset_select_scope)
    record = tables.table102.getRecord(oArgs.target);
    if (asset_select_scope == 'trigger') {
        Dom.get('trigger').value = 'Family';
        Dom.get('trigger_key').value = record.getData('key');
        Dom.get('family_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
        Dom.setStyle('trigger_family_options', 'display', '');
    } else if (asset_select_scope == 'target_bis') {

        Dom.get('target').value = 'Family';
        Dom.get('target_key').value = record.getData('key');
        Dom.get('target_bis_family_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
    } else if (asset_select_scope == 'free_product_from_family') {

        Dom.get('target').value = 'Family';
        Dom.get('target_key').value = record.getData('key');
        Dom.get('target_bis_family_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";


        table_id = 103;
        var table = tables['table' + table_id];
        var datasource = tables['dataSource' + table_id];
        request = "ar_quick_tables.php?tipo=product_list&parent=family&parent_key=" + record.getData('key') + "&tableid=103&nr=20&sf=0";
        datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

        Dom.setStyle(['default_free_product_from_family_options'], 'display', '');
        show_dialog_products_list('default_free_product_from_family');

    } else {
        Dom.get('target').value = 'Family';
        Dom.get('target_key').value = record.getData('key');
        Dom.get('target_family_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";

    }

    dialog_families_list.hide();
}

function select_product_from_list(oArgs) {



    record = tables.table103.getRecord(oArgs.target);
    if (asset_select_scope == 'trigger') {
        Dom.get('trigger').value = 'Product';
        Dom.get('trigger_key').value = record.getData('key');
        Dom.get('product_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
        Dom.setStyle('trigger_product_options', 'display', '')
    } else if (asset_select_scope == 'target_bis') {

        Dom.get('target').value = 'Product';
        Dom.get('target_key').value = record.getData('key');
        Dom.get('target_bis_product_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
    } else if (asset_select_scope == 'default_free_product_from_family') {

        Dom.get('default_free_product_from_family').value = record.getData('code');
        Dom.get('default_free_product_from_family_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
    } else {
        Dom.get('target').value = 'Product';
        Dom.get('target_key').value = record.getData('key');
        Dom.get('target_product_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";

    }

    dialog_products_list.hide();
}


function select_deal_from_list(oArgs) {

    record = tables.table105.getRecord(oArgs.target);
    Dom.get('target').value = 'Deal';
    Dom.get('target_key').value = record.getData('key');
    Dom.get('clone_deal_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
    dialog_deals_list.hide();
}

function new_campaign() {
    Dom.removeClass(['select_campaign', 'new_campaign'], 'selected');
    Dom.addClass('new_campaign', 'selected');
    Dom.get('campaign_key').value = 0;
    Dom.setStyle('new_campaign_fields', 'display', '');
    Dom.get('select_campaign').innerHTML = Dom.get('select_campaign').getAttribute('label');
    Dom.get('campaign_formated').innerHTML = '';

    validate_scope_data.deal.campaign_key.required = false;
    validate_scope_data.deal.campaign_code.required = true;
    validate_scope_data.deal.campaign_name.required = true;
    validate_scope_data.deal.campaign_name.required = true;
    validate_scope_data.deal.from.required = true;
    validate_scope_data.deal.to.required = true;

    validate_scope('deal')




}

function terms_changed(value) {

    Dom.setStyle(['amount_options', 'voucher_options', 'if_order_more_tr', 'order_interval_tr', 'order_number_tr', 'for_every_ordered_tr', 'target_department_options', 'target_family_options', 'target_product_options'], 'display', 'none');
    Dom.setStyle(['order_more_than_allowances_select', 'for_every_allowances_select', 'voucher_allowances_select', 'every_order_allowances_select', 'next_order_allowances_select', 'amount_allowances_select', 'order_interval_allowances_select', 'order_number_allowances_select', 'for_every_any_product_allowances_select'], 'display', 'none');

    validate_scope_data.deal.voucher_code.required = false;
    validate_scope_data.deal.amount.required = false;
    validate_scope_data.deal.if_order_more.required = false;
    validate_scope_data.deal.for_every_ordered.required = false;
    validate_scope_data.deal.order_interval.required = false;
    validate_scope_data.deal.order_number.required = false;


    Dom.get('terms').value = value;

    switch (value) {
    case 'Customer Department Quantity Ordered':
        Dom.setStyle(['if_order_more_tr', 'target_department_options'], 'display', '');
        Dom.setStyle('order_more_than_allowances_select', 'display', '');

        show_dialog_departments_list('target')
        allowances_changed('Percentage Off')
        Dom.get('terms').value = 'Department Quantity Ordered';
        break;
    case 'Customer Family Quantity Ordered':

        Dom.setStyle(['if_order_more_tr', 'target_family_options'], 'display', '');
        Dom.setStyle('order_more_than_allowances_select', 'display', '');

        show_dialog_families_list('target')

        allowances_changed('Percentage Off');
        Dom.get('terms').value = 'Family Quantity Ordered';

        break;
    case 'Customer Product Quantity Ordered':
        Dom.setStyle(['if_order_more_tr', 'target_product_options'], 'display', '');
        Dom.setStyle('order_more_than_allowances_select', 'display', '');
        show_dialog_products_list('target')

        allowances_changed('Percentage Off');
        Dom.get('terms').value = 'Product Quantity Ordered';
        break;
    case 'Department Quantity Ordered':
        Dom.setStyle('if_order_more_tr', 'display', '');
        Dom.setStyle('order_more_than_allowances_select', 'display', '');

        allowances_changed('Percentage Off');
        break;
    case 'Family Quantity Ordered':
        Dom.setStyle('if_order_more_tr', 'display', '');
        Dom.setStyle('order_more_than_allowances_select', 'display', '');

        allowances_changed('Percentage Off');
        break;
    case 'Product Quantity Ordered':
        Dom.setStyle('if_order_more_tr', 'display', '');
        Dom.setStyle('order_more_than_allowances_select', 'display', '');

        allowances_changed('Percentage Off');
        break;
    case 'Department For Every Quantity Any Product Ordered':
    
        Dom.setStyle('for_every_ordered_tr', 'display', '');
        Dom.setStyle('for_every_any_product_allowances_select', 'display', '');
        allowances_changed('Get Cheapest Free');
        break;
    case 'Family For Every Quantity Any Product Ordered':
        Dom.setStyle('for_every_ordered_tr', 'display', '');
        Dom.setStyle('for_every_any_product_allowances_select', 'display', '');
        allowances_changed('Get Cheapest Free');

        break;
    case 'Department For Every Quantity Ordered':
        Dom.setStyle('for_every_ordered_tr', 'display', '');
        Dom.setStyle('for_every_allowances_select', 'display', '');
        allowances_changed('Get Same Free');
        break;
    case 'Family For Every Quantity Ordered':
        Dom.setStyle('for_every_ordered_tr', 'display', '');
        Dom.setStyle('for_every_allowances_select', 'display', '');
        allowances_changed('Get Same Free');

        break;
    case 'Product For Every Quantity Ordered':
        Dom.setStyle('for_every_ordered_tr', 'display', '');
        Dom.setStyle('for_every_allowances_select', 'display', '');
        allowances_changed('Get Same Free');

        break;



    case 'Voucher':
        Dom.setStyle('voucher_options', 'display', '');
        Dom.setStyle('voucher_allowances_select', 'display', '');
        allowances_changed('Percentage Off');

        break;
    case 'Amount':
        Dom.setStyle('amount_options', 'display', '');
        Dom.get('amount').focus();
        Dom.setStyle('amount_allowances_select', 'display', '');
        allowances_changed('Percentage Off');
        break;
    case 'Order Interval':
        Dom.setStyle('order_interval_tr', 'display', '');
        Dom.get('order_interval').focus();
        Dom.setStyle('order_interval_allowances_select', 'display', '');
        allowances_changed('Percentage Off');

        break;
    case 'Order Number':
        Dom.setStyle('order_number_tr', 'display', '');
        Dom.get('order_number').focus();
        Dom.setStyle('order_number_allowances_select', 'display', '');
        allowances_changed('Percentage Off');

        break;
    case 'Voucher AND Amount':

        Dom.setStyle('voucher_options', 'display', '');
        Dom.setStyle('amount_options', 'display', '');
        Dom.setStyle('voucher_allowances_select', 'display', '');

        Dom.get('amount').focus();
        allowances_changed('Percentage Off');

        break;
    case 'Voucher AND Order Number':
        Dom.setStyle('voucher_options', 'display', '');
        Dom.setStyle('order_number_tr', 'display', '');
        Dom.setStyle('voucher_allowances_select', 'display', '');

        Dom.get('order_number').focus();
        allowances_changed('Percentage Off');

        break;
    case 'Voucher AND Order Interval':
        Dom.setStyle('voucher_options', 'display', '');
        Dom.setStyle('order_interval_tr', 'display', '');
        Dom.setStyle('voucher_allowances_select', 'display', '');

        Dom.get('order_interval').focus();
        allowances_changed('Percentage Off');

        break;
    case 'Amount AND Order Number':
        Dom.setStyle('amount_options', 'display', '');
        Dom.setStyle('order_number_tr', 'display', '');
        Dom.setStyle('voucher_allowances_select', 'display', '');

        Dom.get('amount').focus();
        allowances_changed('Percentage Off');
        break;
    case 'Amount AND Order Interval':
        Dom.setStyle('amount_options', 'display', '');
        Dom.setStyle('order_interval_tr', 'display', '');
        Dom.setStyle('order_interval_allowances_select', 'display', '');

        Dom.get('amount').focus();
        allowances_changed('Percentage Off');
        break;
    case 'Every Order':
        Dom.setStyle('every_order_allowances_select', 'display', '');

        allowances_changed('Percentage Off')
        break;
    case 'Next Order':
        Dom.setStyle('next_order_allowances_select', 'display', '');

        allowances_changed('Percentage Off')
        break;
    default:


    }

    validate_scope('deal')

}

function allowances_changed(value) {
    Dom.setStyle(['percentage_off_tr', 'get_same_free_tr', 'target_bis_department_options', 'target_bis_family_options', 'target_bis_product_options', 'default_free_product_from_family_options', 'clone_deal_options'], 'display', 'none')

    validate_scope_data.deal.percentage_off.required = false;
    validate_scope_data.deal.get_same_free.required = false;
    Dom.get('allowances').value = value;
    switch (value) {
    case 'Department Percentage Off':
        Dom.setStyle(['percentage_off_tr', 'target_bis_department_options'], 'display', '');
        show_dialog_departments_list('target_bis')
        validate_scope_data.deal.percentage_off.required = true;

        break;
    case 'Family Percentage Off':
        Dom.setStyle(['percentage_off_tr', 'target_bis_family_options'], 'display', '');
        show_dialog_families_list('target_bis');
        validate_scope_data.deal.percentage_off.required = true;

        break;
    case 'Product Percentage Off':
        Dom.setStyle(['percentage_off_tr', 'target_bis_product_options'], 'display', '');
        show_dialog_products_list('target_bis');
        validate_scope_data.deal.percentage_off.required = true;

        break;
    case 'Percentage Off':
        Dom.setStyle('percentage_off_tr', 'display', '');
        validate_scope_data.deal.percentage_off.required = true;
        break;
    case 'Amount Off':
        Dom.setStyle('amount_off_tr', 'display', '');
        validate_scope_data.deal.amount_off.required = true;
        break;
    case 'Get Same Free':
        Dom.setStyle('get_same_free_tr', 'display', '');
        validate_scope_data.deal.get_same_free.required = true;
        break;
    case 'Get Cheapest Free':
        Dom.setStyle('get_same_free_tr', 'display', '');
        validate_scope_data.deal.get_same_free.required = true;
        break;
    case 'Clone':
        Dom.setStyle('clone_deal_options', 'display', '');
        show_dialog_deals_list()
        break;

    case 'Bonus Product From Family':
        Dom.setStyle(['target_bis_family_options'], 'display', '');

        show_dialog_families_list('free_product_from_family');
        break;

    case 'Bonus Product':
        Dom.setStyle(['target_bis_product_options'], 'display', '');
        show_dialog_products_list('target_bis');
        break;
    }
    validate_scope('deal')

}


function select_amount_type() {

    Dom.removeClass(['amount_type_total', 'amount_type_net', 'amount_type_items'], 'selected');


    if (this.id == 'amount_type_total') {
        Dom.addClass('amount_type_total', 'selected');
        Dom.get('amount_type').value = 'Order Total Amount';

    } else if (this.id == 'amount_type_net') {
        Dom.addClass('amount_type_net', 'selected');
        Dom.get('amount_type').value = 'Order Total Net Amount';

    } else {
        Dom.addClass('amount_type_items', 'selected');
        Dom.get('amount_type').value = 'Order Items Net Amount';
    }
}



function validate_campaign_code(query) {
    validate_general('deal', 'campaign_code', query);
}

function validate_campaign_name(query) {

    validate_general('deal', 'campaign_name', query);
}

function validate_campaign_description(query) {
    validate_general('deal', 'campaign_description', query);
}

function validate_deal_code(query) {
    validate_general('deal', 'code', query);
}

function validate_deal_name(query) {

    validate_general('deal', 'name', query);
}

function validate_deal_description(query) {
    validate_general('deal', 'description', query);
}

function validate_voucher_code(query) {
    validate_general('deal', 'voucher_code', query);
}

function validate_amount(query) {
    validate_general('deal', 'amount', query);
}

function validate_if_order_more(query) {
    validate_general('deal', 'if_order_more', query);
}

function validate_for_every_ordered(query) {
    validate_general('deal', 'for_every_ordered', query);
}

function validate_order_interval(query) {
    validate_general('deal', 'order_interval', query);
}

function validate_order_number(query) {
    validate_general('deal', 'order_number', query);
}

function validate_percentage_off(query) {
    validate_general('deal', 'percentage_off', query);
}

function validate_amount_off(query) {
    validate_general('deal', 'amount_off', query);
}

function validate_get_same_free(query) {
    validate_general('deal', 'get_same_free', query);
}

function save_new_deal() {
    save_new_general('deal');
}

function post_new_create_actions(branch, response) {
    if (Dom.get('post_create_action').value == 'go_to_new') {
        window.location = "deal.php?id=" + response.deal_key
    } else {
        Dom.get('new_deal_msg').innerHTML = response.message
        Dom.get('deal_code').value = '';
        validate_general('deal', 'code', '');

    }
}

function after_actions_changed() {

    Dom.get('go_to_new').src = 'art/icons/checkbox_unchecked.png'
    Dom.get('create_other_deal').src = 'art/icons/checkbox_unchecked.png'
    this.src = 'art/icons/checkbox_checked.png'

    Dom.get('post_create_action').value = this.id

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=deal-post_create_action&value=' + this.id, {});

}

function init() {
    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;


    validate_scope_data = {

        'deal': {
          
            'allowances': {
                'changed': false,
                'validated': false,
                'required': false,
                'dbname': 'Deal Component Allowance Type',
                'name': 'allowances',

                'validation': false,
                'ar': false
            },
            'target': {
                'changed': true,
                'validated': false,
                'required': false,
                'dbname': 'Deal Component Allowance Target',
                'name': 'target',

                'validation': false,
                'ar': false
            },
            'target_key': {
                'changed': true,
                'validated': false,
                'required': false,
                'dbname': 'Deal Component Allowance Target Key',
                'name': 'target_key',

                'validation': false,
                'ar': false
            },

            'default_free_product_from_family': {
                'changed': true,
                'validated': false,
                'required': false,
                'dbname': 'default_free_product_from_family',
                'name': 'default_free_product_from_family',

                'validation': false,
                'ar': false
            },


            'percentage_off': {
                'changed': false,
                'validated': false,
                'required': false,
                'name': 'percentage_off',
                'dbname': 'percentage_off',
                'validation': [{
                    'numeric': "percentage",
                    'invalid_msg': labels.Invalid_percentage
                }],
                'ar': false
            },
            'amount_off': {
                'changed': false,
                'validated': false,
                'required': false,
                'name': 'amount_off',
                'dbname': 'amount_off',
                'validation': [{
                    'numeric': "money",
                    'invalid_msg': labels.Invalid_amount
                }],
                'ar': false
            },
            'get_same_free': {
                'changed': false,
                'validated': false,
                'required': false,
                'name': 'get_same_free',
                'dbname': 'get_same_free',
                'validation': [{
                    'numeric': "positive integer",
                    'invalid_msg': labels.Invalid_number
                }],
                'ar': false
            },
          
          

        }
    };


    validate_scope_metadata = {
        'deal': {
            'type': 'new',
            'ar_file': 'ar_edit_deals.php',
            'key_name': 'store_key',
            'key': Dom.get('store_key').value
        }
    };

/*
    switch (Dom.get('trigger').value) {
    case 'Department':
        terms_changed('Department Quantity Ordered');
        break;
    case 'Family':
        terms_changed('Family Quantity Ordered');
        break;
    case 'Product':
        terms_changed('Product Quantity Ordered');
        break;
    case 'Customer':
    case 'Customer Category':
    case 'Customer List':
        terms_changed('Voucher');

        break;
    case 'Order':
        terms_changed('Voucher');
        break;
    }
*/
   
        init_search('marketing_store');

  
  
  
  
    dialog_departments_list = new YAHOO.widget.Dialog("dialog_departments_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_departments_list.render();
  
    dialog_families_list = new YAHOO.widget.Dialog("dialog_families_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });

    dialog_families_list.render();

    dialog_products_list = new YAHOO.widget.Dialog("dialog_products_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_products_list.render();
 

    dialog_deals_list = new YAHOO.widget.Dialog("dialog_deals_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_deals_list.render();
    Event.addListener("update_clone_deal", "click", show_dialog_deals_list);

  

    var percentage_off_oACDS = new YAHOO.util.FunctionDataSource(validate_percentage_off);
    percentage_off_oACDS.queryMatchContains = true;
    var percentage_off_oAutoComp = new YAHOO.widget.AutoComplete("percentage_off", "percentage_off_Container", percentage_off_oACDS);
    percentage_off_oAutoComp.minQueryLength = 0;
    percentage_off_oAutoComp.queryDelay = 0.1;

    var amount_off_oACDS = new YAHOO.util.FunctionDataSource(validate_amount_off);
    amount_off_oACDS.queryMatchContains = true;
    var amount_off_oAutoComp = new YAHOO.widget.AutoComplete("amount_off", "amount_off_Container", amount_off_oACDS);
    amount_off_oAutoComp.minQueryLength = 0;
    amount_off_oAutoComp.queryDelay = 0.1;

    var get_same_free_oACDS = new YAHOO.util.FunctionDataSource(validate_get_same_free);
    get_same_free_oACDS.queryMatchContains = true;
    var get_same_free_oAutoComp = new YAHOO.widget.AutoComplete("get_same_free", "get_same_free_Container", get_same_free_oACDS);
    get_same_free_oAutoComp.minQueryLength = 0;
    get_same_free_oAutoComp.queryDelay = 0.1;

    YAHOO.util.Event.addListener('save_new_deal', "click", save_new_deal);

    Event.addListener('clean_table_filter_show101', "click", show_filter, 101);
    Event.addListener('clean_table_filter_hide101', "click", hide_filter, 101);
    Event.addListener('clean_table_filter_show102', "click", show_filter, 102);
    Event.addListener('clean_table_filter_hide102', "click", hide_filter, 102);
    Event.addListener('clean_table_filter_show103', "click", show_filter, 103);
    Event.addListener('clean_table_filter_hide103', "click", hide_filter, 103);
    Event.addListener('clean_table_filter_show105', "click", show_filter, 105);
    Event.addListener('clean_table_filter_hide105', "click", hide_filter, 105);
    
    
    var oACDS101 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS101.queryMatchContains = true;
    oACDS101.table_id = 101;
    var oAutoComp101 = new YAHOO.widget.AutoComplete("f_input101", "f_container101", oACDS101);
    oAutoComp101.minQueryLength = 0;

    var oACDS102 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS102.queryMatchContains = true;
    oACDS102.table_id = 102;
    var oAutoComp102 = new YAHOO.widget.AutoComplete("f_input102", "f_container102", oACDS102);
    oAutoComp102.minQueryLength = 0;

    var oACDS103 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS103.queryMatchContains = true;
    oACDS103.table_id = 103;
    var oAutoComp103 = new YAHOO.widget.AutoComplete("f_input103", "f_container103", oACDS103);
    oAutoComp103.minQueryLength = 0;

  
    var oACDS105 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS105.queryMatchContains = true;
    oACDS105.table_id = 105;
    var oAutoComp105 = new YAHOO.widget.AutoComplete("f_input105", "f_container105", oACDS105);
    oAutoComp105.minQueryLength = 0;

 
    YAHOO.util.Event.addListener(['go_to_new', 'create_other_deal'], "click", after_actions_changed);

}

YAHOO.util.Event.onDOMReady(init);
