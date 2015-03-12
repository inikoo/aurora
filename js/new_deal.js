var Dom   = YAHOO.util.Dom;
var session_data,labels;
var asset_select_scope;

YAHOO.util.Event.addListener(window, "load", function() {

	session_data=YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
	labels=session_data.label;

	tables = new function() {

		this.remove_links = function(elLiner, oRecord, oColumn, oData) {
			elLiner.innerHTML = oData;
			elLiner.innerHTML=  oData.replace(/<.*?>/g, '');

		};

		YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;


		var tableid=100;
		var tableDivEL="table"+tableid;
		
		var ColumnDefs = [
		{key:"key", label:"",hidden:true},
		{key:"code",formatter:"remove_links", label:labels.Code,width:30,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
		{key:"name", formatter:"remove_links",label:labels.Name,width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
		];
		request="ar_quick_tables.php?tipo=campaign_list&store_key="+Dom.get('store_key').value+"&tableid="+tableid+"&nr=20&sf=0";
		this.dataSource100 = new YAHOO.util.DataSource(request);
		this.dataSource100.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.dataSource100.connXhrMode = "queueRequests";
		this.dataSource100.table_id=tableid;

		this.dataSource100.responseSchema = {
			resultsList: "resultset.data",
			metaFields: {
				rowsPerPage:"resultset.records_perpage",
				rtext:"resultset.rtext",
				rtext_rpp:"resultset.rtext_rpp",
				sort_key:"resultset.sort_key",
				sort_dir:"resultset.sort_dir",
				tableid:"resultset.tableid",
				filter_msg:"resultset.filter_msg",
				totalRecords: "resultset.total_records"
			},
			fields: [
			"name",'code','id'
			]
		};


		this.table100 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
			this.dataSource100, {
				renderLoopSize: 50,generateRequest : myRequestBuilder,
				paginator : new YAHOO.widget.Paginator({
					rowsPerPage:20,containers : 'paginator100', 
					pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
					previousPageLinkLabel : "<",
					nextPageLinkLabel : ">",
					firstPageLinkLabel :"<<",
					lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false,
					template : "{PreviousPageLink}<strong id='paginator_info100'>{CurrentPageReport}</strong>{NextPageLink}"
				})

				,sortedBy : {
					key: 'code',
					dir: ''
				},
				dynamicData : true

			}

			);

		this.table100.handleDataReturnPayload =myhandleDataReturnPayload;
		this.table100.doBeforeSortColumn = mydoBeforeSortColumn;
        //this.table100.subscribe("cellClickEvent", this.table100.onEventShowCellEditor);
        this.table100.prefix='';
        this.table100.subscribe("rowMouseoverEvent", this.table100.onEventHighlightRow);
        this.table100.subscribe("rowMouseoutEvent", this.table100.onEventUnhighlightRow);
        this.table100.subscribe("rowClickEvent", select_campaign_from_list);



        this.table100.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table100.filter={key:'code',value:''};
        
        var tableid=101;
        var tableDivEL="table"+tableid;

        var ColumnDefs = [
        {key:"key", label:"",hidden:true},
        {key:"code",formatter:"remove_links", label:labels.Code,width:30,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
        {key:"name", formatter:"remove_links",label:labels.Name,width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
        ];
        request="ar_quick_tables.php?tipo=department_list&store_key="+Dom.get('store_key').value+"&tableid="+tableid+"&nr=20&sf=0";
        this.dataSource101 = new YAHOO.util.DataSource(request);
        this.dataSource101.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource101.connXhrMode = "queueRequests";
        this.dataSource101.table_id=tableid;

        this.dataSource101.responseSchema = {
        	resultsList: "resultset.data",
        	metaFields: {
        		rowsPerPage:"resultset.records_perpage",
        		rtext:"resultset.rtext",
        		rtext_rpp:"resultset.rtext_rpp",
        		sort_key:"resultset.sort_key",
        		sort_dir:"resultset.sort_dir",
        		tableid:"resultset.tableid",
        		filter_msg:"resultset.filter_msg",
        		totalRecords: "resultset.total_records"
        	},
        	fields: [
        	"name",'code','key'
        	]
        };


        this.table101 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
        	this.dataSource101, {
        		renderLoopSize: 50,generateRequest : myRequestBuilder,
        		paginator : new YAHOO.widget.Paginator({
        			rowsPerPage:20,containers : 'paginator101', 
        			pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
        			previousPageLinkLabel : "<",
        			nextPageLinkLabel : ">",
        			firstPageLinkLabel :"<<",
        			lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false,
        			template : "{PreviousPageLink}<strong id='paginator_info101'>{CurrentPageReport}</strong>{NextPageLink}"
        		})

        		,sortedBy : {
        			key: 'code',
        			dir: ''
        		},
        		dynamicData : true

        	}

        	);

        this.table101.handleDataReturnPayload =myhandleDataReturnPayload;
        this.table101.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table101.prefix='';
        this.table101.subscribe("rowMouseoverEvent", this.table101.onEventHighlightRow);
        this.table101.subscribe("rowMouseoutEvent", this.table101.onEventUnhighlightRow);
        this.table101.subscribe("rowClickEvent", select_department_from_list);
        this.table101.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table101.filter={key:'code',value:''};

        var tableid=102;
        var tableDivEL="table"+tableid;

        var ColumnDefs = [
        {key:"key", label:"",hidden:true},
        {key:"code",formatter:"remove_links", label:labels.Code,width:30,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
        {key:"name", formatter:"remove_links",label:labels.Name,width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
        ];
        request="ar_quick_tables.php?tipo=family_list&store_key="+Dom.get('store_key').value+"&tableid="+tableid+"&nr=20&sf=0";
        this.dataSource102 = new YAHOO.util.DataSource(request);
        this.dataSource102.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource102.connXhrMode = "queueRequests";
        this.dataSource102.table_id=tableid;

        this.dataSource102.responseSchema = {
        	resultsList: "resultset.data",
        	metaFields: {
        		rowsPerPage:"resultset.records_perpage",
        		rtext:"resultset.rtext",
        		rtext_rpp:"resultset.rtext_rpp",
        		sort_key:"resultset.sort_key",
        		sort_dir:"resultset.sort_dir",
        		tableid:"resultset.tableid",
        		filter_msg:"resultset.filter_msg",
        		totalRecords: "resultset.total_records"
        	},
        	fields: [
        	"name",'code','id'
        	]
        };


        this.table102 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
        	this.dataSource102, {
        		renderLoopSize: 50,generateRequest : myRequestBuilder,
        		paginator : new YAHOO.widget.Paginator({
        			rowsPerPage:20,containers : 'paginator102', 
        			pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
        			previousPageLinkLabel : "<",
        			nextPageLinkLabel : ">",
        			firstPageLinkLabel :"<<",
        			lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false,
        			template : "{PreviousPageLink}<strong id='paginator_info102'>{CurrentPageReport}</strong>{NextPageLink}"
        		})

        		,sortedBy : {
        			key: 'code',
        			dir: ''
        		},
        		dynamicData : true

        	}

        	);

        this.table102.handleDataReturnPayload =myhandleDataReturnPayload;
        this.table102.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table102.prefix='';
        this.table102.subscribe("rowMouseoverEvent", this.table102.onEventHighlightRow);
        this.table102.subscribe("rowMouseoutEvent", this.table102.onEventUnhighlightRow);
        this.table102.subscribe("rowClickEvent", select_family_from_list);
        this.table102.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table102.filter={key:'code',value:''};



        var tableid=103;
        var tableDivEL="table"+tableid;

        var ColumnDefs = [
        {key:"key", label:"",hidden:true},
        {key:"code",formatter:"remove_links", label:labels.Code,width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
        {key:"name", formatter:"remove_links",label:labels.Name,width:160,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
        ];
        request="ar_quick_tables.php?tipo=product_list&store_key="+Dom.get('store_key').value+"&tableid="+tableid+"&nr=20&sf=0";
        this.dataSource103 = new YAHOO.util.DataSource(request);
        this.dataSource103.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource103.connXhrMode = "queueRequests";
        this.dataSource103.table_id=tableid;

        this.dataSource103.responseSchema = {
        	resultsList: "resultset.data",
        	metaFields: {
        		rowsPerPage:"resultset.records_perpage",
        		rtext:"resultset.rtext",
        		rtext_rpp:"resultset.rtext_rpp",
        		sort_key:"resultset.sort_key",
        		sort_dir:"resultset.sort_dir",
        		tableid:"resultset.tableid",
        		filter_msg:"resultset.filter_msg",
        		totalRecords: "resultset.total_records"
        	},
        	fields: [
        	"name",'code','id'
        	]
        };


        this.table103 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
        	this.dataSource103, {
        		renderLoopSize: 50,generateRequest : myRequestBuilder,
        		paginator : new YAHOO.widget.Paginator({
        			rowsPerPage:20,containers : 'paginator103', 
        			pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
        			previousPageLinkLabel : "<",
        			nextPageLinkLabel : ">",
        			firstPageLinkLabel :"<<",
        			lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false,
        			template : "{PreviousPageLink}<strong id='paginator_info103'>{CurrentPageReport}</strong>{NextPageLink}"
        		})

        		,sortedBy : {
        			key: 'code',
        			dir: ''
        		},
        		dynamicData : true

        	}

        	);

        this.table103.handleDataReturnPayload =myhandleDataReturnPayload;
        this.table103.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table103.prefix='';
        this.table103.subscribe("rowMouseoverEvent", this.table103.onEventHighlightRow);
        this.table103.subscribe("rowMouseoutEvent", this.table103.onEventUnhighlightRow);
        this.table103.subscribe("rowClickEvent", select_product_from_list);
        this.table103.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table103.filter={key:'code',value:''};

        var tableid=104;
        var tableDivEL="table"+tableid;
        
        var ColumnDefs = [
        {key:"key", label:"",hidden:true},
        {key:"formated_id",formatter:"remove_links", label:labels.ID,width:30,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
        {key:"name", formatter:"remove_links",label:labels.Name,width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
        ];
        request="ar_quick_tables.php?tipo=customer_list&store_key="+Dom.get('store_key').value+"&tableid="+tableid+"&nr=20&sf=0";
        this.dataSource104 = new YAHOO.util.DataSource(request);
        this.dataSource104.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource104.connXhrMode = "queueRequests";
        this.dataSource104.table_id=tableid;

        this.dataSource104.responseSchema = {
        	resultsList: "resultset.data",
        	metaFields: {
        		rowsPerPage:"resultset.records_perpage",
        		rtext:"resultset.rtext",
        		rtext_rpp:"resultset.rtext_rpp",
        		sort_key:"resultset.sort_key",
        		sort_dir:"resultset.sort_dir",
        		tableid:"resultset.tableid",
        		filter_msg:"resultset.filter_msg",
        		totalRecords: "resultset.total_records"
        	},
        	fields: [
        	"name",'key','formated_id'
        	]
        };


        this.table104 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
        	this.dataSource104, {
        		renderLoopSize: 50,generateRequest : myRequestBuilder,
        		paginator : new YAHOO.widget.Paginator({
        			rowsPerPage:20,containers : 'paginator104', 
        			pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
        			previousPageLinkLabel : "<",
        			nextPageLinkLabel : ">",
        			firstPageLinkLabel :"<<",
        			lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false,
        			template : "{PreviousPageLink}<strong id='paginator_info104'>{CurrentPageReport}</strong>{NextPageLink}"
        		})

        		,sortedBy : {
        			key: 'key',
        			dir: ''
        		},
        		dynamicData : true

        	}

        	);

        this.table104.handleDataReturnPayload =myhandleDataReturnPayload;
        this.table104.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table104.prefix='';
        this.table104.subscribe("rowMouseoverEvent", this.table104.onEventHighlightRow);
        this.table104.subscribe("rowMouseoutEvent", this.table104.onEventUnhighlightRow);
        this.table104.subscribe("rowClickEvent", select_customer_from_list);
        this.table104.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table104.filter={key:'name',value:''};


        var tableid=105;
		var tableDivEL="table"+tableid;
		
		var ColumnDefs = [
		{key:"key", label:"",hidden:true},
		{key:"code",formatter:"remove_links", label:labels.Code,width:30,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
		{key:"name", formatter:"remove_links",label:labels.Name,width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
		];
		request="ar_quick_tables.php?tipo=deal_list&store_key="+Dom.get('store_key').value+"&tableid="+tableid+"&nr=20&sf=0";

		this.dataSource105 = new YAHOO.util.DataSource(request);
		this.dataSource105.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.dataSource105.connXhrMode = "queueRequests";
		this.dataSource105.table_id=tableid;

		this.dataSource105.responseSchema = {
			resultsList: "resultset.data",
			metaFields: {
				rowsPerPage:"resultset.records_perpage",
				rtext:"resultset.rtext",
				rtext_rpp:"resultset.rtext_rpp",
				sort_key:"resultset.sort_key",
				sort_dir:"resultset.sort_dir",
				tableid:"resultset.tableid",
				filter_msg:"resultset.filter_msg",
				totalRecords: "resultset.total_records"
			},
			fields: [
			"name",'code','id'
			]
		};


		this.table105 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
			this.dataSource105, {
				renderLoopSize: 50,generateRequest : myRequestBuilder,
				paginator : new YAHOO.widget.Paginator({
					rowsPerPage:20,containers : 'paginator105', 
					pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
					previousPageLinkLabel : "<",
					nextPageLinkLabel : ">",
					firstPageLinkLabel :"<<",
					lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false,
					template : "{PreviousPageLink}<strong id='paginator_info105'>{CurrentPageReport}</strong>{NextPageLink}"
				})

				,sortedBy : {
					key: 'code',
					dir: ''
				},
				dynamicData : true

			}

			);

		this.table105.handleDataReturnPayload =myhandleDataReturnPayload;
		this.table105.doBeforeSortColumn = mydoBeforeSortColumn;
        //this.table105.subscribe("cellClickEvent", this.table105.onEventShowCellEditor);
        this.table105.prefix='';
        this.table105.subscribe("rowMouseoverEvent", this.table105.onEventHighlightRow);
        this.table105.subscribe("rowMouseoutEvent", this.table105.onEventUnhighlightRow);
        this.table105.subscribe("rowClickEvent", select_deal_from_list);



        this.table105.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table105.filter={key:'code',value:''};
        

    };});

function trigger_changed (value) {
	Dom.setStyle(['trigger_department_options','trigger_family_options','trigger_product_options','trigger_customer_options'],'display','none')
	Dom.setStyle(['department_terms_select','family_terms_select','product_terms_select','customer_terms_select','customer_terms_select'],'display','none')

	switch(value){
		case 'Department':
		show_dialog_departments_list('trigger');
		Dom.setStyle(['trigger_department_options','department_terms_select'],'display','')
		terms_changed('Department Quantity Ordered')
		break;
		case 'Family':
		show_dialog_families_list('trigger');
		Dom.setStyle(['trigger_family_options','family_terms_select'],'display','')
		terms_changed('Department Quantity Ordered')
		break;
		case 'Product':
		show_dialog_products_list('trigger');
		Dom.setStyle(['trigger_product_options','product_terms_select'],'display','')

		terms_changed('Department Quantity Ordered')
		break;
		case 'Customer':
		show_dialog_customers_list();
		break;		
		case 'Order':
		show_dialog_customers_list();
		break;		
	}
}


function show_dialog_campaigns_list () {
	region1 = Dom.getRegion('select_campaign');
	region2 = Dom.getRegion('dialog_campaigns_list');
	var pos =[region1.left,region1.top]
	Dom.setXY('dialog_campaigns_list', pos);
	dialog_campaigns_list.show();
}

function show_dialog_departments_list (scope) {

	if(scope=='trigger'){
		var scope_element='trigger'
		asset_select_scope='trigger'
	}else if(scope=='target_bis'){
		var scope_element='allowances_select'
		asset_select_scope='target_bis'
	}else{
		var scope_element='customer_terms_select'
		asset_select_scope='target'
	}

	region1 = Dom.getRegion(scope_element);
	region2 = Dom.getRegion('dialog_departments_list');
	var pos =[region1.left-2,region1.top+22]
	Dom.setXY('dialog_departments_list', pos);
	dialog_departments_list.show();

}

function show_dialog_families_list (scope) {

	if(scope=='trigger'){
		var scope_element='trigger'
		asset_select_scope='trigger'
	}else if(scope=='target_bis'){
		var scope_element='allowances_select'
		asset_select_scope='target_bis'
	}else{
		var scope_element='customer_terms_select'
		asset_select_scope='target'
	}
	region1 = Dom.getRegion(scope_element);
	region2 = Dom.getRegion('dialog_families_list');
	var pos =[region1.left-2,region1.top+22]
	Dom.setXY('dialog_families_list', pos);
	dialog_families_list.show();
}

function show_dialog_products_list (scope) {

	if(scope=='trigger'){
		var scope_element='trigger'
		asset_select_scope='trigger'
	}else if(scope=='target_bis'){
		var scope_element='allowances_select'
		asset_select_scope='target_bis'
	}else{
		var scope_element='customer_terms_select'
		asset_select_scope='target'
	}

	region1 = Dom.getRegion(scope_element);
	region2 = Dom.getRegion('dialog_products_list');
	var pos =[region1.left-2,region1.top+22]
	Dom.setXY('dialog_products_list', pos);
	dialog_products_list.show();
}

function show_dialog_customers_list () {
	region1 = Dom.getRegion('tigger');
	var pos =[region1.left-2,region1.top+22]
	var pos =[region1.left,region1.top]
	Dom.setXY('dialog_customers_list', pos);
	dialog_customers_list.show();
}

function show_dialog_deals_list () {
	region1 = Dom.getRegion('allowances_select');
	region2 = Dom.getRegion('dialog_deals_list');
	var pos =[region1.left,region1.top]
	Dom.setXY('dialog_deals_list', pos);
	dialog_deals_list.show();
}




function select_campaign_from_list(oArgs) {

	Dom.setStyle('new_campaign_fields','display','none')

	record = tables.table100.getRecord(oArgs.target);
	
	Dom.removeClass(['select_campaign','new_campaign'],'selected');
	Dom.get('campaign_formated').value= record.getData('key');
	Dom.get('campaign_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
	Dom.get('select_campaign').innerHTML=Dom.get('select_campaign').getAttribute('alt_label');

	dialog_campaigns_list.hide();
}

function select_department_from_list(oArgs) {

	record = tables.table101.getRecord(oArgs.target);
	if(asset_select_scope=='trigger'){
		Dom.get('trigger').value= 'Department';
		Dom.get('trigger_key').value= record.getData('key');
		Dom.get('department_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
		Dom.setStyle('trigger_department_options_options','display','')

	}else if(asset_select_scope=='target_bis'){

		Dom.get('target').value= 'Department';
		Dom.get('target_key').value= record.getData('key');
		Dom.get('target_bis_department_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
	}else{
		Dom.get('target').value= 'Department';
		Dom.get('target_key').value= record.getData('key');
		Dom.get('target_department_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
	}
	dialog_departments_list.hide();

}

function select_family_from_list(oArgs) {


	record = tables.table102.getRecord(oArgs.target);
	if(asset_select_scope=='trigger'){
		Dom.get('trigger').value= 'Family';
		Dom.get('trigger_key').value= record.getData('key');
		Dom.get('family_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
		Dom.setStyle('trigger_family_options','display','');
	}else if(asset_select_scope=='target_bis'){

		Dom.get('target').value= 'Family';
		Dom.get('target_key').value= record.getData('key');
		Dom.get('target_bis_family_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
	}else{
		Dom.get('target').value= 'Family';
		Dom.get('target_key').value= record.getData('key');
		Dom.get('target_family_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";

	}

	dialog_families_list.hide();
}

function select_product_from_list(oArgs) {

	

	record = tables.table103.getRecord(oArgs.target);
	if(asset_select_scope=='trigger'){
		Dom.get('trigger').value= 'Product';
		Dom.get('trigger_key').value= record.getData('key');
		Dom.get('product_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
		Dom.setStyle('trigger_product_options','display','')
	}else if(asset_select_scope=='target_bis'){

		Dom.get('target').value= 'Product';
		Dom.get('target_key').value= record.getData('key');
		Dom.get('target_bis_product_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
	}else{
		Dom.get('target').value= 'Product';
		Dom.get('target_key').value= record.getData('key');
		Dom.get('target_product_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";

	}

	dialog_products_list.hide();
}

function select_customer_from_list(oArgs) {

	Dom.setStyle('trigger_customer_options','display','')

	record = tables.table104.getRecord(oArgs.target);
	Dom.get('trigger').value= 'Customer';
	Dom.get('trigger_key').value= record.getData('key');
	Dom.get('customer_formated').innerHTML = record.getData('formated_id') + " (" + record.getData('name') + ") ";
	dialog_customers_list.hide();
}

function select_deal_from_list(oArgs) {

	record = tables.table105.getRecord(oArgs.target);
	Dom.get('clone_deal_key').value=record.getData('key');
	Dom.get('clone_deal_formated').innerHTML = record.getData('code') + " (" + record.getData('name') + ") ";
	dialog_deals_list.hide();
}

function new_campaign () {
	Dom.removeClass(['select_campaign','new_campaign'],'selected');
	Dom.addClass('new_campaign','selected');
	Dom.get('campaign_key').value=0;
	Dom.setStyle('new_campaign_fields','display','');
	Dom.get('select_campaign').innerHTML=Dom.get('select_campaign').getAttribute('label');
	Dom.get('campaign_formated').innerHTML ='';
}

function terms_changed(value){
	Dom.setStyle(['amount_options','voucher_options','if_order_more_tr','order_interval_tr','order_number_tr','for_every_ordered_tr','target_department_options','target_family_options','target_product_options'],'display','none');
	Dom.setStyle(['order_more_than_allowances_select','for_every_allowances_select','voucher_allowances_select','every_order_allowances_select','next_order_allowances_select','amount_allowances_select','order_interval_allowances_select','order_number_allowances_select'],'display','none');


	Dom.get('terms').value=value;
	switch(value){
		case 'Customer Department Quantity Ordered':
		Dom.setStyle(['if_order_more_tr','target_department_options'],'display','');
		Dom.setStyle('order_more_than_allowances_select','display','');
		
		show_dialog_departments_list('target')	
		allowances_changed('Percentage Off')
		Dom.get('terms').value='Department Quantity Ordered';
		break;
		case 'Customer Family Quantity Ordered':

		Dom.setStyle(['if_order_more_tr','target_family_options'],'display','');
		Dom.setStyle('order_more_than_allowances_select','display','');

		show_dialog_families_list('target')	

		allowances_changed('Percentage Off');
		Dom.get('terms').value='Family Quantity Ordered';

		break;
		case 'Customer Product Quantity Ordered':
		Dom.setStyle(['if_order_more_tr','target_product_options'],'display','');
		Dom.setStyle('order_more_than_allowances_select','display','');
		show_dialog_products_list('target')	

		allowances_changed('Percentage Off');
		Dom.get('terms').value='Product Quantity Ordered';
		break;
		case 'Department Quantity Ordered':
		Dom.setStyle('if_order_more_tr','display','');
		Dom.setStyle('order_more_than_allowances_select','display','');

		allowances_changed('Percentage Off');
		break;
		case 'Family Quantity Ordered':
		Dom.setStyle('if_order_more_tr','display','');
		Dom.setStyle('order_more_than_allowances_select','display','');

		allowances_changed('Percentage Off');
		break;
		case 'Product Quantity Ordered':
		Dom.setStyle('if_order_more_tr','display','');
		Dom.setStyle('order_more_than_allowances_select','display','');

		allowances_changed('Percentage Off');
		break;
		case 'Department For Every Quantity Ordered':
		Dom.setStyle('for_every_ordered_tr','display','');
		Dom.setStyle('for_every_allowances_select','display','');
		allowances_changed('Get Same Free');
		break;
		case 'Family For Every Quantity Ordered':
		Dom.setStyle('for_every_ordered_tr','display','');
		Dom.setStyle('for_every_allowances_select','display','');
		allowances_changed('Get Same Free');

		break;
		case 'Product For Every Quantity Ordered':
		Dom.setStyle('for_every_ordered_tr','display','');
		Dom.setStyle('for_every_allowances_select','display','');
		allowances_changed('Get Same Free');

		break;
		case 'Voucher':
		Dom.setStyle('voucher_options','display','');
		Dom.setStyle('voucher_allowances_select','display','');
		allowances_changed('Percentage Off');

		break;
		case 'Amount':
		Dom.setStyle('amount_options','display','');
		Dom.get('amount').focus();
		Dom.setStyle('amount_allowances_select','display','');
		allowances_changed('Percentage Off')
		break;
		case 'Order Interval':
		Dom.setStyle('order_interval_tr','display','');
		Dom.get('order_interval').focus();
		Dom.setStyle('order_interval_allowances_select','display','');
		allowances_changed('Percentage Off');

		break;
		case 'Order Number':
		Dom.setStyle('order_number_tr','display','');
		Dom.get('order_number').focus();
		Dom.setStyle('order_number_allowances_select','display','');
		allowances_changed('Percentage Off');
		
		break;
		case 'Voucher AND Amount':
		Dom.setStyle('voucher_options','display','');
		Dom.setStyle('amount_options','display','');
		Dom.get('amount').focus();
		
		break;
		case 'Voucher AND Order Number':
		Dom.setStyle('voucher_options','display','');
		Dom.setStyle('order_number_tr','display','');
		Dom.get('order_number').focus();
		
		break;
		case 'Voucher AND Order Interval':
		Dom.setStyle('voucher_options','display','');
		Dom.setStyle('order_interval_tr','display','');
		Dom.get('order_interval').focus();
		
		break;
		case 'Amount AND Order Number':
		Dom.setStyle('amount_options','display','');
		Dom.setStyle('order_number_tr','display','');
		Dom.get('amount').focus();
		break;
		case 'Amount AND Order Interval':
		Dom.setStyle('amount_options','display','');
		Dom.setStyle('order_interval_tr','display','');
		Dom.get('amount').focus();
		break;
		case 'Every Order':
		Dom.setStyle('every_order_allowances_select','display','');

		allowances_changed('Percentage Off')
		break;
		case 'Next Order':
		Dom.setStyle('next_order_allowances_select','display','');

		allowances_changed('Percentage Off')
		break;
		default:


	}

}

function allowances_changed (value) {
	Dom.setStyle(['percentage_off_tr','get_same_free_tr','target_bis_department_options','target_bis_family_options','target_bis_product_options','clone_deal_options'],'display','none')
	switch(value){
		case 'Department Percentage Off':
		Dom.setStyle(['percentage_off_tr','target_bis_department_options'],'display','');
		show_dialog_departments_list('target_bis')

		break;
		case 'Family Percentage Off':
		Dom.setStyle(['percentage_off_tr','target_bis_family_options'],'display','');
		show_dialog_families_list('target_bis')
		break;
		case 'Product Percentage Off':
		Dom.setStyle(['percentage_off_tr','target_bis_product_options'],'display','');
		show_dialog_products_list('target_bis')
		break;
		case 'Percentage Off':
		Dom.setStyle('percentage_off_tr','display','');
		break;
		case 'Get Same Free':
		Dom.setStyle('get_same_free_tr','display','');
		break;

		case 'Clone':
		Dom.setStyle('clone_deal_options','display','');
		show_dialog_deals_list()
		break;
	



	}


}

function select_voucher_code_type () {

	if(this.id=='voucher_code_random'){
		Dom.removeClass('voucher_code_custome','selected');
		Dom.addClass('voucher_code_random','selected');
		Dom.setStyle('voucher_code_tr','display','none')
		Dom.get('voucher_code').value='';
		Dom.get('voucher_code_type').value='Random';

	}else{
		Dom.addClass('voucher_code_custome','selected');
		Dom.removeClass('voucher_code_random','selected');
		Dom.setStyle('voucher_code_tr','display','');
		Dom.get('voucher_code').focus();
		Dom.get('voucher_code_type').value='Custome';
	}
}

function select_voucher_type () {

	if(this.id=='voucher_type_public'){
		Dom.addClass('voucher_type_public','selected');
		Dom.removeClass('voucher_type_private','selected');
		Dom.get('voucher_type').value='Public';

	}else{
		Dom.removeClass('voucher_type_public','selected');
		Dom.addClass('voucher_type_private','selected');
		Dom.get('voucher_type').value='Private';
	}
}

function date_changed() {

    if (this.id == 'v_calpop1') {

        validate_general('campaign', 'from', this.value);
    } else if (this.id == 'v_calpop2') {
        validate_general('campaign', 'to', this.value);

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
        validate_general('campaign', 'from', txtDate1.value);
    } else if (this.id == 2) {
        validate_general('campaign', 'to', txtDate1.value);
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
        Dom.get("v_calpop1").value=date
       
        validate_general('campaign', 'from', date);
    }
}

function permanent_campaign() {

    if (Dom.hasClass("to_permanent", "selected")) {
        Dom.removeClass("to_permanent", "selected")
        Dom.setStyle(['v_calpop2', 'calpop2'], 'display', '');
        validate_scope_data.campaign.to.validated = false;
        validate_scope('campaign');
    } else {
        Dom.addClass("to_permanent", "selected")
        Dom.setStyle(['v_calpop2', 'calpop2'], 'display', 'none');

        validate_scope_data.campaign.to.validated = true;
        validate_scope_data.campaign.to.changed = true;

        Dom.get("v_calpop2").value = '';
        validate_scope('campaign');

    }
}

function init(){


	switch(Dom.get('trigger').value){
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

	session_data=YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
	labels=session_data.label;

	init_search('products_store');

	validate_scope_data = {

		'deal': {
			'code': {
				'changed': false,
				'validated': false,
				'required': true,
				'dbname': 'Deal Code',
				'group': 1,
				'type': 'item',
				'name': 'Code',
				'validation': [{
					'regexp': "[a-z\\d]+",
					'invalid_msg': labels.Invalid_code
				}],
				'ar': 'find',
				'ar_request': 'ar_deals.php?tipo=is_deal_code&query='
			},
			'name': {
				'changed': false,
				'validated': false,
				'required': true,
				'dbname': 'Deal Name',
				'group': 1,
				'type': 'item',
				'name': 'Name',

				'validation': [{
					'regexp': "[a-z\\d]+",
					'invalid_msg': labels.Invalid_name
				}],
				'ar': false
			},
			'country': {
				'changed': true,
				'validated': true,
				'required': true,
				'dbname': 'Country Code',
				'group': 1,
				'type': 'item',
				'name': 'Country',

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
	Event.addListener('new_campaign', "click",new_campaign);

	dialog_departments_list = new YAHOO.widget.Dialog("dialog_departments_list", {
		visible: false,
		close: true,
		underlay: "none",
		draggable: false
	});
	dialog_departments_list.render();
	Event.addListener("update_department", "click", show_dialog_departments_list,'trigger');
	Event.addListener("target_update_department", "click", show_dialog_departments_list,'target');



	dialog_families_list = new YAHOO.widget.Dialog("dialog_families_list", {
		visible: false,
		close: true,
		underlay: "none",
		draggable: false
	});
	dialog_families_list.render();
	Event.addListener("update_family", "click", show_dialog_families_list,"trigger");
	Event.addListener("target_update_family", "click", show_dialog_families_list,"target");

	dialog_products_list = new YAHOO.widget.Dialog("dialog_products_list", {
		visible: false,
		close: true,
		underlay: "none",
		draggable: false
	});
	dialog_products_list.render();
	Event.addListener("update_product", "click", show_dialog_products_list,"trigger");
	Event.addListener("target_update_product", "click", show_dialog_products_list,"target");

	dialog_products_list = new YAHOO.widget.Dialog("dialog_products_list", {
		visible: false,
		close: true,
		underlay: "none",
		draggable: false
	});
	dialog_products_list.render();
	Event.addListener("update_product", "click", show_dialog_products_list);

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
	Event.addListener(['voucher_code_random','voucher_code_custome'], "click", select_voucher_code_type);
	Event.addListener(['voucher_type_public','voucher_type_private'], "click", select_voucher_type);




}

YAHOO.util.Event.onDOMReady(init);






