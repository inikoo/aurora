var Dom = YAHOO.util.Dom;
var session_data, labels;
var asset_select_scope;
var validate_scope_data;

YAHOO.util.Event.addListener(window, "load", function() {

    session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;

    tables = new function() {

        this.remove_links = function(elLiner, oRecord, oColumn, oData) {
            elLiner.innerHTML = oData;
            elLiner.innerHTML = oData.replace(/<.*?>/g, '');

        };

        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;


        var tableid = 100;
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
        request = "ar_quick_tables.php?tipo=campaign_list&store_key=" + Dom.get('store_key').value + "&tableid=" + tableid + "&nr=20&sf=0";
        this.dataSource100 = new YAHOO.util.DataSource(request);
        this.dataSource100.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource100.connXhrMode = "queueRequests";
        this.dataSource100.table_id = tableid;

        this.dataSource100.responseSchema = {
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


        this.table100 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource100, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: 20,
                containers: 'paginator100',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                alwaysVisible: false,
                template: "{PreviousPageLink}<strong id='paginator_info100'>{CurrentPageReport}</strong>{NextPageLink}"
            })

            ,
            sortedBy: {
                key: 'code',
                dir: ''
            },
            dynamicData: true

        }

        );

        this.table100.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table100.doBeforeSortColumn = mydoBeforeSortColumn;
        //this.table100.subscribe("cellClickEvent", this.table100.onEventShowCellEditor);
        this.table100.prefix = '';
        this.table100.subscribe("rowMouseoverEvent", this.table100.onEventHighlightRow);
        this.table100.subscribe("rowMouseoutEvent", this.table100.onEventUnhighlightRow);
        this.table100.subscribe("rowClickEvent", select_campaign_from_list);



        this.table100.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table100.filter = {
            key: 'code',
            value: ''
        };

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
        request = "ar_quick_tables.php?tipo=product_list&store_key=" + Dom.get('store_key').value + "&tableid=" + tableid + "&nr=20&sf=0";

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

        var tableid = 104;
        var tableDivEL = "table" + tableid;

        var ColumnDefs = [{
            key: "key",
            label: "",
            hidden: true
        }, {
            key: "formated_id",
            formatter: "remove_links",
            label: labels.ID,
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
        request = "ar_quick_tables.php?tipo=customer_list&store_key=" + Dom.get('store_key').value + "&tableid=" + tableid + "&nr=20&sf=0";
        this.dataSource104 = new YAHOO.util.DataSource(request);
        this.dataSource104.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource104.connXhrMode = "queueRequests";
        this.dataSource104.table_id = tableid;

        this.dataSource104.responseSchema = {
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
            fields: ["name", 'key', 'formated_id']
        };


        this.table104 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs, this.dataSource104, {
            renderLoopSize: 50,
            generateRequest: myRequestBuilder,
            paginator: new YAHOO.widget.Paginator({
                rowsPerPage: 20,
                containers: 'paginator104',
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
                previousPageLinkLabel: "<",
                nextPageLinkLabel: ">",
                firstPageLinkLabel: "<<",
                lastPageLinkLabel: ">>",
                rowsPerPageOptions: [10, 25, 50, 100, 250, 500],
                alwaysVisible: false,
                template: "{PreviousPageLink}<strong id='paginator_info104'>{CurrentPageReport}</strong>{NextPageLink}"
            })

            ,
            sortedBy: {
                key: 'key',
                dir: ''
            },
            dynamicData: true

        }

        );

        this.table104.handleDataReturnPayload = myhandleDataReturnPayload;
        this.table104.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table104.prefix = '';
        this.table104.subscribe("rowMouseoverEvent", this.table104.onEventHighlightRow);
        this.table104.subscribe("rowMouseoutEvent", this.table104.onEventUnhighlightRow);
        this.table104.subscribe("rowClickEvent", select_customer_from_list);
        this.table104.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table104.filter = {
            key: 'name',
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
    Dom.setStyle(['trigger_department_options', 'trigger_family_options', 'trigger_product_options', 'trigger_customer_options'], 'display', 'none')
    Dom.setStyle(['department_terms_select', 'family_terms_select', 'product_terms_select', 'customer_terms_select', 'customer_terms_select'], 'display', 'none')

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
        terms_changed('Department Quantity Ordered')
        break;
    case 'Product':
        show_dialog_products_list('trigger');
        Dom.setStyle(['trigger_product_options', 'product_terms_select'], 'display', '')

        terms_changed('Department Quantity Ordered')
        break;
    case 'Customer':
        Dom.setStyle(['voucher_tr'], 'display', 'none')
        show_dialog_customers_list();
        break;
    case 'Order':
        show_dialog_customers_list();
        break;
    }
}


function show_dialog_campaigns_list() {
    region1 = Dom.getRegion('select_campaign');
    region2 = Dom.getRegion('dialog_campaigns_list');
    var pos = [region1.left, region1.top]
    Dom.setXY('dialog_campaigns_list', pos);
    dialog_campaigns_list.show();
}

function show_dialog_departments_list(scope) {

    if (scope == 'trigger') {
        var scope_element = 'trigger'
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
    var pos = [region1.left - 2, region1.top + 22]
    Dom.setXY('dialog_departments_list', pos);
    dialog_departments_list.show();

}

function show_dialog_families_list(scope) {

    if (scope == 'trigger') {
        var scope_element = 'trigger'
        asset_select_scope = 'trigger'
    } else if (scope == 'target_bis') {
        var scope_element = 'allowances_select'
        asset_select_scope = 'target_bis'
    } else {
        var scope_element = 'customer_terms_select'
        asset_select_scope = 'target'
    }
    region1 = Dom.getRegion(scope_element);
    region2 = Dom.getRegion('dialog_families_list');
    var pos = [region1.left - 2, region1.top + 22]
    Dom.setXY('dialog_families_list', pos);
    dialog_families_list.show();
}

function show_dialog_products_list(scope) {

    if (scope == 'trigger') {
        var scope_element = 'trigger'
        asset_select_scope = 'trigger'
    } else if (scope == 'target_bis') {
        var scope_element = 'allowances_select'
        asset_select_scope = 'target_bis'
    } else {
        var scope_element = 'customer_terms_select'
        asset_select_scope = 'target'
    }

    region1 = Dom.getRegion(scope_element);
    region2 = Dom.getRegion('dialog_products_list');
    var pos = [region1.left - 2, region1.top + 22]
    Dom.setXY('dialog_products_list', pos);
    dialog_products_list.show();
}

function show_dialog_customers_list() {
    region1 = Dom.getRegion('tigger');
    var pos = [region1.left - 2, region1.top + 22]
    var pos = [region1.left, region1.top]
    Dom.setXY('dialog_customers_list', pos);
    dialog_customers_list.show();
}

function show_dialog_deals_list() {
    region1 = Dom.getRegion('allowances_select');
    region2 = Dom.getRegion('dialog_deals_list');
    var pos = [region1.left, region1.top]
    Dom.setXY('dialog_deals_list', pos);
    dialog_deals_list.show();
}




function select_campaign_from_list(oArgs) {

    Dom.setStyle('new_campaign_fields', 'display', 'none')

    record = tables.table100.getRecord(oArgs.target);

    Dom.removeClass(['select_campaign', 'new_campaign'], 'selected');
    Dom.get('campaign_key').value = record.getData('key');

    Dom.get('campaign_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
    Dom.get('select_campaign').innerHTML = Dom.get('select_campaign').getAttribute('alt_label');

    dialog_campaigns_list.hide();
    validate_scope_data.deal.campaign_key.required = true;
    validate_scope_data.deal.campaign_code.required = false;
    validate_scope_data.deal.campaign_name.required = false;
    validate_scope_data.deal.campaign_name.required = false;
    validate_scope_data.deal.from.required = false;
    validate_scope_data.deal.to.required = false;

    validate_scope_data.deal.campaign_key.validated = true;
    validate_scope('deal')

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
    } else {
        Dom.get('target').value = 'Product';
        Dom.get('target_key').value = record.getData('key');
        Dom.get('target_product_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";

    }

    dialog_products_list.hide();
}

function select_customer_from_list(oArgs) {

    Dom.setStyle('trigger_customer_options', 'display', '')

    record = tables.table104.getRecord(oArgs.target);
    Dom.get('trigger').value = 'Customer';
    Dom.get('trigger_key').value = record.getData('key');
    Dom.get('customer_formated').innerHTML = record.getData('formated_id') + " (" + record.getData('name') + ") ";
    dialog_customers_list.hide();
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
    Dom.setStyle(['order_more_than_allowances_select', 'for_every_allowances_select', 'voucher_allowances_select', 'every_order_allowances_select', 'next_order_allowances_select', 'amount_allowances_select', 'order_interval_allowances_select', 'order_number_allowances_select'], 'display', 'none');

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
    Dom.setStyle(['percentage_off_tr', 'get_same_free_tr', 'target_bis_department_options', 'target_bis_family_options', 'target_bis_product_options', 'clone_deal_options'], 'display', 'none')

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

    case 'Clone':
        Dom.setStyle('clone_deal_options', 'display', '');
        show_dialog_deals_list()
        break;




    }
    validate_scope('deal')

}

function select_voucher_code_type() {

    if (this.id == 'voucher_code_random') {
        Dom.removeClass('voucher_code_custome', 'selected');
        Dom.addClass('voucher_code_random', 'selected');
        Dom.setStyle('voucher_code_tr', 'display', 'none')
        Dom.get('voucher_code').value = '';
        Dom.get('voucher_code_type').value = 'Random';
        validate_scope_data.deal.voucher_code.required = false;


    } else {
        Dom.addClass('voucher_code_custome', 'selected');
        Dom.removeClass('voucher_code_random', 'selected');
        Dom.setStyle('voucher_code_tr', 'display', '');
        Dom.get('voucher_code').focus();
        Dom.get('voucher_code_type').value = 'Custome';
        validate_scope_data.deal.voucher_code.required = true;

    }

    validate_scope('deal')
}

function select_voucher_type() {

    if (this.id == 'voucher_type_public') {
        Dom.addClass('voucher_type_public', 'selected');
        Dom.removeClass('voucher_type_private', 'selected');
        Dom.get('voucher_type').value = 'Public';

    } else {
        Dom.removeClass('voucher_type_public', 'selected');
        Dom.addClass('voucher_type_private', 'selected');
        Dom.get('voucher_type').value = 'Private';
    }
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



function date_changed() {

    if (this.id == 'v_calpop1') {

        validate_general('deal', 'from', this.value);
    } else if (this.id == 'v_calpop2') {
        validate_general('deal', 'to', this.value);

    }
}

function handleSelect(type, args, obj) {

    var dates = args[0];
    var date = dates[0];
    var year = date[0],
        month = date[1],
        day = date[2];


    if (month < 10) month = '0' + month;
    if (day < 10) day = '0' + day;
    var txtDate1 = document.getElementById("v_calpop" + this.id);
    txtDate1.value = day + "-" + month + "-" + year;
    this.hide();

    if (this.id == 1) {
        validate_general('deal', 'from', txtDate1.value);
    } else if (this.id == 2) {
        validate_general('deal', 'to', txtDate1.value);
    }
}

function start_now() {

    if (Dom.hasClass("start_now", "selected")) {
        Dom.removeClass("start_now", "selected")
        Dom.setStyle(['v_calpop1', 'calpop1'], 'display', '');
    } else {
        Dom.addClass("start_now", "selected")
        Dom.setStyle(['v_calpop1', 'calpop1'], 'display', 'none');
        var d = new Date()
        year = d.getFullYear(),
            month = d.getMonth(),
            day = d.getDate();
        if (month < 10) month = '0' + month;
        if (day < 10) day = '0' + day;
        var date = day + "-" + month + "-" + year;
        Dom.get("v_calpop1").value = date

        validate_general('deal', 'from', date);
    }
}

function permanent_campaign() {

    if (Dom.hasClass("to_permanent", "selected")) {
        Dom.removeClass("to_permanent", "selected")
        Dom.setStyle(['v_calpop2', 'calpop2'], 'display', '');
        validate_scope_data.deal.to.validated = false;
        validate_scope('deal');
    } else {
        Dom.addClass("to_permanent", "selected")
        Dom.setStyle(['v_calpop2', 'calpop2'], 'display', 'none');

        validate_scope_data.deal.to.validated = true;
        validate_scope_data.deal.to.changed = true;

        Dom.get("v_calpop2").value = '';
        validate_scope('deal');

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
            'code': {
                'changed': false,
                'validated': false,
                'required': true,
                'dbname': 'Deal Code',
                'group': 1,
                'type': 'item',
                'name': 'deal_code',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_code
                }],
                'ar': 'find',
                'ar_request': 'ar_deals.php?tipo=is_deal_code_in_store&store_key=' + Dom.get('store_key').value + '&query=',
            },
            'name': {
                'changed': false,
                'validated': false,
                'required': true,
                'dbname': 'Deal Name',
                'group': 1,
                'type': 'item',
                'name': 'deal_name',

                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_name
                }],
                'ar': false
            },
            'description': {
                'changed': false,
                'validated': true,
                'required': false,
                'dbname': 'Deal Description',
                'group': 1,
                'type': 'item',
                'name': 'deal_description',

                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_description
                }],
                'ar': false
            },
            'terms': {
                'changed': false,
                'validated': false,
                'required': false,
                'dbname': 'Deal Terms Type',
                'name': 'terms',

                'validation': false,
                'ar': false
            },
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
            'voucher_code': {
                'changed': false,
                'validated': false,
                'required': false,
                'dbname': 'voucher_code',
                'name': 'voucher_code',

                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_code
                }],
                'ar': 'find',
                'ar_request': 'ar_deals.php?tipo=is_voucher_code_in_store&store_key=' + Dom.get('store_key').value + '&query='
            },
            'voucher_code_type': {
                'changed': false,
                'validated': false,
                'required': false,
                'dbname': 'voucher_code_type',
                'name': 'voucher_code_type',

                'validation': false,
                'ar': false
            },
            'voucher_type': {
                'changed': false,
                'validated': false,
                'required': false,
                'dbname': 'voucher_type',
                'name': 'voucher_type',

                'validation': false,
                'ar': false
            },
            'amount': {
                'changed': false,
                'validated': false,
                'required': false,
                'name': 'amount',
                'dbname': 'amount',
                'validation': [{
                    'numeric': "money",
                    'invalid_msg': labels.Invalid_amount
                }],
                'ar': false
            },
            'amount_type': {
                'changed': false,
                'validated': false,
                'required': false,
                'name': 'amount_type',
                'dbname': 'amount_type',
                'validation': false,
                'ar': false
            },
            'if_order_more': {
                'changed': false,
                'validated': false,
                'required': false,
                'name': 'if_order_more',
                'dbname': 'if_order_more',
                'validation': [{
                    'numeric': "positive integer",
                    'invalid_msg': labels.Invalid_number
                }],
                'ar': false
            },
            'for_every_ordered': {
                'changed': false,
                'validated': false,
                'required': false,
                'name': 'for_every_ordered',
                'dbname': 'for_every_ordered',
                'validation': [{
                    'numeric': "positive integer",
                    'invalid_msg': labels.Invalid_number
                }],
                'ar': false
            },
            'order_interval': {
                'changed': false,
                'validated': false,
                'required': false,
                'name': 'order_interval',
                'dbname': 'order_interval',
                'validation': [{
                    'numeric': "positive integer",
                    'invalid_msg': labels.Invalid_number
                }],
                'ar': false
            },
            'order_number': {
                'changed': false,
                'validated': false,
                'required': false,
                'name': 'order_number',
                'dbname': 'order_number',
                'validation': [{
                    'numeric': "positive integer",
                    'invalid_msg': labels.Invalid_number
                }],
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
            'trigger': {
                'changed': false,
                'validated': true,
                'required': true,
                'name': 'trigger',
                'dbname': 'Deal Trigger',
            },
            'trigger': {
                'changed': false,
                'validated': true,
                'required': true,
                'name': 'trigger',
                'dbname': 'Deal Trigger',
            },
            'trigger_key': {
                'changed': false,
                'validated': true,
                'required': true,
                'name': 'trigger_key',
                'dbname': 'Deal Trigger Key',
            },
            'campaign_key': {
                'changed': false,
                'validated': (Dom.get('scope_subject').value == 'Campaign' ? true : false),
                'required': true,
                'group': 1,
                'type': 'item',
                'name': 'campaign_key',
                'dbname': 'Deal Campaign Key',
                'validation': [{
                    'numeric': 'positive',
                    'invalid_msg': ''
                }]
            },
            'campaign_code': {
                'changed': false,
                'validated': false,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'campaign_code',
                'ar': 'find',
                'ar_request': 'ar_deals.php?tipo=is_campaign_code_in_store&store_key=' + Dom.get('store_key').value + '&query=',
                'dbname': 'Deal Campaign Code'
            },
            'campaign_name': {
                'changed': false,
                'validated': false,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'campaign_name',
                'ar': false,
                'dbname': 'Deal Campaign Name',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_name
                }]
            },
            'campaign_description': {
                'changed': false,
                'validated': false,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'campaign_description',
                'ar': false,
                'dbname': 'Deal Campaign Description',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': labels.Invalid_description
                }]
            },
            'from': {
                'changed': false,
                'validated': false,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'v_calpop1',
                'ar': false,
                'dbname': 'Deal Campaign Valid From',
                'validation': [{
                    'regexp': "\d{2}-\d{2}-\d{4}",
                    'invalid_msg': labels.Invalid_date
                }]
            },
            'to': {
                'changed': false,
                'validated': false,
                'required': false,
                'group': 1,
                'type': 'item',
                'name': 'v_calpop2',
                'ar': false,
                'dbname': 'Deal Campaign Valid To',
                'validation': [{
                    'regexp': "\d{2}-\d{2}-\d{4}",
                    'invalid_msg': labels.Invalid_date
                }]
            }


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
        terms_changed('Voucher');

        break;
    case 'Order':
        terms_changed('Voucher');
        break;
    }

    switch (Dom.get('scope_subject').value) {
    case 'Campaign':
        init_search('marketing_store');

        break;
    case 'Customer':
        init_search('customers_store');
        break;
    default:
        init_search('products_store');
    }

    cal1 = new YAHOO.widget.Calendar("calpop1", "campaign_from_Container", {
        title: "Start:",
        mindate: new Date(),
        close: true
    });
    cal1.update = updateCal;
    cal1.id = '1';
    cal1.render();
    cal1.update();
    cal1.selectEvent.subscribe(handleSelect, cal1, true);

    YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);


    cal2 = new YAHOO.widget.Calendar("calpop2", "campaign_to_Container", {
        title: "Until:",
        mindate: new Date(),
        close: true
    });
    cal2.update = updateCal;
    cal2.id = '2';
    cal2.render();
    cal2.update();
    cal2.selectEvent.subscribe(handleSelect, cal2, true);

    YAHOO.util.Event.addListener("calpop2", "click", cal2.show, cal2, true);

    Event.addListener(['v_calpop1', 'v_calpop2'], "keyup", date_changed);

    YAHOO.util.Event.addListener('to_permanent', "click", permanent_campaign)
    YAHOO.util.Event.addListener('start_now', "click", start_now)

    dialog_campaigns_list = new YAHOO.widget.Dialog("dialog_campaigns_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_campaigns_list.render();
    Event.addListener("select_campaign", "click", show_dialog_campaigns_list);
    Event.addListener('new_campaign', "click", new_campaign);

    dialog_departments_list = new YAHOO.widget.Dialog("dialog_departments_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_departments_list.render();
    Event.addListener("update_department", "click", show_dialog_departments_list, 'trigger');
    Event.addListener("target_update_department", "click", show_dialog_departments_list, 'target');
    Event.addListener("target_bis_update_department", "click", show_dialog_departments_list, 'target');

    dialog_families_list = new YAHOO.widget.Dialog("dialog_families_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });

    dialog_families_list.render();
    Event.addListener("update_family", "click", show_dialog_families_list, "trigger");
    Event.addListener("target_update_family", "click", show_dialog_families_list, "target");
    Event.addListener("target_bis_update_family", "click", show_dialog_families_list, "target");

    dialog_products_list = new YAHOO.widget.Dialog("dialog_products_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_products_list.render();
    Event.addListener("update_product", "click", show_dialog_products_list, "trigger");
    Event.addListener("target_update_product", "click", show_dialog_products_list, "target");
    Event.addListener("target_bis_update_product", "click", show_dialog_products_list, "target");

    dialog_customers_list = new YAHOO.widget.Dialog("dialog_customers_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_customers_list.render();
    Event.addListener("update_customer", "click", show_dialog_customers_list);

    dialog_deals_list = new YAHOO.widget.Dialog("dialog_deals_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_deals_list.render();
    Event.addListener("update_clone_deal", "click", show_dialog_deals_list);
    Event.addListener(['voucher_code_random', 'voucher_code_custome'], "click", select_voucher_code_type);
    Event.addListener(['voucher_type_public', 'voucher_type_private'], "click", select_voucher_type);
    Event.addListener(['amount_type_total', 'amount_type_net', 'amount_type_items'], "click", select_amount_type);

    var campaign_code_oACDS = new YAHOO.util.FunctionDataSource(validate_campaign_code);
    campaign_code_oACDS.queryMatchContains = true;
    var campaign_code_oAutoComp = new YAHOO.widget.AutoComplete("campaign_code", "campaign_code_Container", campaign_code_oACDS);
    campaign_code_oAutoComp.minQueryLength = 0;
    campaign_code_oAutoComp.queryDelay = 0.1;

    var campaign_name_oACDS = new YAHOO.util.FunctionDataSource(validate_campaign_name);
    campaign_name_oACDS.queryMatchContains = true;
    var campaign_name_oAutoComp = new YAHOO.widget.AutoComplete("campaign_name", "campaign_name_Container", campaign_name_oACDS);
    campaign_name_oAutoComp.minQueryLength = 0;
    campaign_name_oAutoComp.queryDelay = 0.1;

    var campaign_description_oACDS = new YAHOO.util.FunctionDataSource(validate_campaign_description);
    campaign_description_oACDS.queryMatchContains = true;
    var campaign_description_oAutoComp = new YAHOO.widget.AutoComplete("campaign_description", "campaign_description_Container", campaign_description_oACDS);
    campaign_description_oAutoComp.minQueryLength = 0;
    campaign_description_oAutoComp.queryDelay = 0.1;

    var deal_code_oACDS = new YAHOO.util.FunctionDataSource(validate_deal_code);
    deal_code_oACDS.queryMatchContains = true;
    var deal_code_oAutoComp = new YAHOO.widget.AutoComplete("deal_code", "deal_code_Container", deal_code_oACDS);
    deal_code_oAutoComp.minQueryLength = 0;
    deal_code_oAutoComp.queryDelay = 0.1;

    var deal_name_oACDS = new YAHOO.util.FunctionDataSource(validate_deal_name);
    deal_name_oACDS.queryMatchContains = true;
    var deal_name_oAutoComp = new YAHOO.widget.AutoComplete("deal_name", "deal_name_Container", deal_name_oACDS);
    deal_name_oAutoComp.minQueryLength = 0;
    deal_name_oAutoComp.queryDelay = 0.1;

    var deal_description_oACDS = new YAHOO.util.FunctionDataSource(validate_deal_description);
    deal_description_oACDS.queryMatchContains = true;
    var deal_description_oAutoComp = new YAHOO.widget.AutoComplete("deal_description", "deal_description_Container", deal_description_oACDS);
    deal_description_oAutoComp.minQueryLength = 0;
    deal_description_oAutoComp.queryDelay = 0.1;

    var voucher_code_oACDS = new YAHOO.util.FunctionDataSource(validate_voucher_code);
    voucher_code_oACDS.queryMatchContains = true;
    var voucher_code_oAutoComp = new YAHOO.widget.AutoComplete("voucher_code", "voucher_code_Container", voucher_code_oACDS);
    voucher_code_oAutoComp.minQueryLength = 0;
    voucher_code_oAutoComp.queryDelay = 0.1;

    var amount_oACDS = new YAHOO.util.FunctionDataSource(validate_amount);
    amount_oACDS.queryMatchContains = true;
    var amount_oAutoComp = new YAHOO.widget.AutoComplete("amount", "amount_Container", amount_oACDS);
    amount_oAutoComp.minQueryLength = 0;
    amount_oAutoComp.queryDelay = 0.1;
    var if_order_more_oACDS = new YAHOO.util.FunctionDataSource(validate_if_order_more);
    if_order_more_oACDS.queryMatchContains = true;
    var if_order_more_oAutoComp = new YAHOO.widget.AutoComplete("if_order_more", "if_order_more_Container", if_order_more_oACDS);
    if_order_more_oAutoComp.minQueryLength = 0;
    if_order_more_oAutoComp.queryDelay = 0.1;

    var for_every_ordered_oACDS = new YAHOO.util.FunctionDataSource(validate_for_every_ordered);
    for_every_ordered_oACDS.queryMatchContains = true;
    var for_every_ordered_oAutoComp = new YAHOO.widget.AutoComplete("for_every_ordered", "for_every_ordered_Container", for_every_ordered_oACDS);
    for_every_ordered_oAutoComp.minQueryLength = 0;
    for_every_ordered_oAutoComp.queryDelay = 0.1;

    var order_interval_oACDS = new YAHOO.util.FunctionDataSource(validate_order_interval);
    order_interval_oACDS.queryMatchContains = true;
    var order_interval_oAutoComp = new YAHOO.widget.AutoComplete("order_interval", "order_interval_Container", order_interval_oACDS);
    order_interval_oAutoComp.minQueryLength = 0;
    order_interval_oAutoComp.queryDelay = 0.1;

    var order_number_oACDS = new YAHOO.util.FunctionDataSource(validate_order_number);
    order_number_oACDS.queryMatchContains = true;
    var order_number_oAutoComp = new YAHOO.widget.AutoComplete("order_number", "order_number_Container", order_number_oACDS);
    order_number_oAutoComp.minQueryLength = 0;
    order_number_oAutoComp.queryDelay = 0.1;

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

    Event.addListener('clean_table_filter_show100', "click", show_filter, 100);
    Event.addListener('clean_table_filter_hide100', "click", hide_filter, 100);
    Event.addListener('clean_table_filter_show101', "click", show_filter, 101);
    Event.addListener('clean_table_filter_hide101', "click", hide_filter, 101);
    Event.addListener('clean_table_filter_show102', "click", show_filter, 102);
    Event.addListener('clean_table_filter_hide102', "click", hide_filter, 102);
    Event.addListener('clean_table_filter_show103', "click", show_filter, 103);
    Event.addListener('clean_table_filter_hide103', "click", hide_filter, 103);
    Event.addListener('clean_table_filter_show104', "click", show_filter, 104);
    Event.addListener('clean_table_filter_hide104', "click", hide_filter, 104);
    Event.addListener('clean_table_filter_show105', "click", show_filter, 105);
    Event.addListener('clean_table_filter_hide105', "click", hide_filter, 105);


    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    oACDS.table_id = 100;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input100", "f_container100", oACDS);
    oAutoComp.minQueryLength = 100;

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

    var oACDS104 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS104.queryMatchContains = true;
    oACDS104.table_id = 104;
    var oAutoComp104 = new YAHOO.widget.AutoComplete("f_input104", "f_container104", oACDS104);
    oAutoComp104.minQueryLength = 0;

    var oACDS105 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS105.queryMatchContains = true;
    oACDS105.table_id = 105;
    var oAutoComp105 = new YAHOO.widget.AutoComplete("f_input105", "f_container105", oACDS105);
    oAutoComp105.minQueryLength = 0;


    YAHOO.util.Event.addListener(['go_to_new', 'create_other_deal'], "click", after_actions_changed);



}

YAHOO.util.Event.onDOMReady(init);
