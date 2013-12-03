var state_data=new Object();

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
    
  
    
      state_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('state_data').value))

        var tableid=0;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
	    				{key:"store_key", label:'store key', width:0,hidden:true,sortable:false,className:"aleft"}
				    	,{key:"name", label:Dom.get('label_Store').value, width:150,sortable:false,className:"aleft"}
				    	,{key:"invoices", label:Dom.get('label_Invoices').value, width:90,sortable:false,className:"aright"}
				    	,{key:"invoices_share", label:Dom.get('label_Invoices_Share').value, width:90,sortable:false,className:"aright"}
				    	,{key:"sales", label:Dom.get('label_Sales').value, hidden:(Dom.get('sales_currency').value=='store'?false:true),  width:110,sortable:false,className:"aright"}
				    	,{key:"dc_sales", label:Dom.get('label_Sales').value, hidden:(Dom.get('sales_currency').value=='store'?true:false),width:110,sortable:false,className:"aright"}
				    	,{key:"sales_share", label:Dom.get('label_Sales_Share').value, width:110,sortable:false,className:"aright", hidden:(Dom.get('sales_currency').value=='store'?false:true)}
				    	,{key:"dc_sales_share", label:Dom.get('label_Sales_Share').value, width:110,sortable:false,className:"aright", hidden:(Dom.get('sales_currency').value=='store'?true:false)}
				    	,{key:"invoices_delta", label:Dom.get('label_Invoices_Delta').value, width:110,sortable:false,className:"aright"}
				    	,{key:"sales_delta", label:Dom.get('label_Sales_Delta').value, width:110,sortable:false,className:"aright" ,hidden:(Dom.get('sales_currency').value=='store'?false:true)}
				    	,{key:"dc_sales_delta", label:Dom.get('label_Sales_Delta').value, width:110,sortable:false,className:"aright", hidden:(Dom.get('sales_currency').value=='store'?true:false)}
					 ];
					 
					request="ar_reports.php?tipo=sales_per_store&tableid="+tableid+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value	 
		// alert(request)
					 
	    this.dataSource0 = new YAHOO.util.DataSource(request);
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
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
			 'store_key','name','invoices','invoices_share','sales','sales_share','invoices_delta','sales_delta','dc_sales','dc_sales_share','dc_sales_delta'
			 ]};
			 
 	
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 20,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
								        alwaysVisible:true,
									      rowsPerPage:state_data.stores.stores.nr+1,
									      totalRecords:YAHOO.widget.Paginator.VALUE_UNLIMITED,
									      containers : 'paginator0', 
 									      pageReportTemplate : '('+Dom.get('label_paginator_Page').value+' {currentPage} '+Dom.get('label_paginator_of').value+' {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",
 									      rowsPerPageOptions : [10,25,50,100,250,500],
 									     
									      template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: state_data.stores.stores.order,
									 dir: state_data.stores.stores.order_dir
								     }
							   ,dynamicData : true

						     }
						     );
	 
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.table_id=tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);
      	this.table0.filter={key:state_data.stores.stores.f_field,value:state_data.stores.stores.f_value};
      	
      	
      	var tableid=1;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
	    				{key:"category_key", label:'category key', width:0,hidden:true,sortable:false,className:"aleft"}
				    	,{key:"store", label:Dom.get('label_Store').value, width:50,sortable:false,className:"aleft"}

				    	,{key:"name", label:Dom.get('label_Category').value, width:100,sortable:false,className:"aleft"}
				    	,{key:"invoices", label:Dom.get('label_Invoices').value, width:90,sortable:false,className:"aright"}
				    	,{key:"invoices_share", label:Dom.get('label_Invoices_Share').value, width:90,sortable:false,className:"aright"}
				    	,{key:"sales", label:Dom.get('label_Sales').value, hidden:(Dom.get('sales_currency').value=='store'?false:true),  width:110,sortable:false,className:"aright"}
				    	,{key:"dc_sales", label:Dom.get('label_Sales').value, hidden:(Dom.get('sales_currency').value=='store'?true:false),width:110,sortable:false,className:"aright"}
				    	,{key:"sales_share", label:Dom.get('label_Sales_Share').value, width:110,sortable:false,className:"aright", hidden:(Dom.get('sales_currency').value=='store'?false:true)}
				    	,{key:"dc_sales_share", label:Dom.get('label_Sales_Share').value, width:110,sortable:false,className:"aright", hidden:(Dom.get('sales_currency').value=='store'?true:false)}
				    	,{key:"invoices_delta", label:Dom.get('label_Invoices_Delta').value, width:110,sortable:false,className:"aright"}
				    	,{key:"sales_delta", label:Dom.get('label_Sales_Delta').value, width:110,sortable:false,className:"aright" ,hidden:(Dom.get('sales_currency').value=='store'?false:true)}
				    	,{key:"dc_sales_delta", label:Dom.get('label_Sales_Delta').value, width:110,sortable:false,className:"aright", hidden:(Dom.get('sales_currency').value=='store'?true:false)}
					 ];
					 
		request="ar_reports.php?tipo=sales_per_invoice_category&tableid="+tableid+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value	 
	    this.dataSource1 = new YAHOO.util.DataSource(request);
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
			 'store','category_key','name','invoices','invoices_share','sales','sales_share','invoices_delta','sales_delta','dc_sales','dc_sales_share','dc_sales_delta'
			 ]};
			 
 	
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource1, {
							 //draggableColumns:true,
							   renderLoopSize: 20,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
								        alwaysVisible:true,
									      rowsPerPage:state_data.categories.categories.nr+1,
									      totalRecords:YAHOO.widget.Paginator.VALUE_UNLIMITED,
									      containers : 'paginator1', 
 									      pageReportTemplate : '('+Dom.get('label_paginator_Page').value+' {currentPage} '+Dom.get('label_paginator_of').value+' {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",
 									      rowsPerPageOptions : [10,25,50,100,250,500],
 									     
									      template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: state_data.categories.categories.order,
									 dir: state_data.categories.categories.order_dir
								     }
							   ,dynamicData : true

						     }
						     );
	 
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.table_id=tableid;
        this.table1.subscribe("renderEvent", myrenderEvent);
      	this.table1.filter={key:state_data.categories.categories.f_field,value:state_data.categories.categories.f_value};
      	
      	
      		
		    var tableid=2;
		    var tableDivEL="table"+tableid;

  			var ColumnDefs = [
				      {key:"date", label:Dom.get('label_Date').value, width:200,sortable:false,className:"aright"}
				      ,{key:"invoices", label:Dom.get('label_Invoices').value, width:100,sortable:false,className:"aright"}
				      ,{key:"customers", label:Dom.get('label_Customers').value, width:100,sortable:false,className:"aright"}
				      ,{key:"sales", label:Dom.get('label_Sales').value, width:100,sortable:false,className:"aright"}
					];

		 
		    request="ar_reports.php?tipo=assets_sales_history&scope=report_sales&parent=stores&parent_key=&tableid="+tableid+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
	//alert(request)
		this.dataSource2 = new YAHOO.util.DataSource(request);
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
 
	    this.dataSource2.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
			
		

	fields: [
				 "date","invoices","customers","sales"

				 ]};
			
		    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource2, {
							 //draggableColumns:true,
							   renderLoopSize: 20,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        alwaysVisible:true,
									      rowsPerPage:state_data.categories.categories.nr,
									      totalRecords:YAHOO.widget.Paginator.VALUE_UNLIMITED,
									      containers : 'paginator2', 
 									      pageReportTemplate : '('+Dom.get('label_paginator_Page').value+' {currentPage} '+Dom.get('label_paginator_of').value+' {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",
 									      rowsPerPageOptions : [10,25,50,100,250,500],
 									     
									      template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: state_data.stores.sales_history.order,
									 dir: state_data.stores.sales_history.order_dir
								     }
							   ,dynamicData : true

						     }
						     );
	 
						     
						    
						     
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginator = mydoBeforePaginatorChange;
      this.table2.request=request;
  this.table2.table_id=tableid;
     this.table2.subscribe("renderEvent", myrenderEvent);

      	this.table2.filter={key:state_data.stores.sales_history.f_field,value:state_data.stores.sales_history.f_value};
      	
    };
  });

function change_block() {

    block = this.id
    Dom.removeClass(['stores', 'categories','history'], 'selected')
    Dom.addClass(this, 'selected')
    Dom.setStyle(['block_stores', 'block_categories','block_history'], 'display', 'none')
    Dom.setStyle(Dom.get('block_' + block), 'display', '')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=report_sales-block&value='+block, {
        success: function(o) {}
    });
}
function stores_subblock() {

    block = this.getAttribute('block_id')
    Dom.removeClass(['stores_subblock_sales', 'stores_subblock_overview'], 'selected')
    Dom.addClass(this, 'selected')
    Dom.setStyle(['subblock_stores_sales','subblock_stores_overview'], 'display', 'none')
    Dom.setStyle(Dom.get('subblock_stores_' + block), 'display', '')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=report_sales-stores_subblock&value='+block, {
        success: function(o) {}
    });
}

function categories_subblock() {

    block = this.getAttribute('block_id')
    Dom.removeClass(['categories_subblock_sales',  'categories_subblock_overview'], 'selected')
    Dom.addClass(this, 'selected')
    Dom.setStyle(['subblock_categories_sales', 'subblock_categories_overview'], 'display', 'none')
    Dom.setStyle(Dom.get('subblock_categories_' + block), 'display', '')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=report_sales-categories_subblock&value='+block, {
        success: function(o) {}
    });
}

function history_subblock() {

    block = this.getAttribute('block_id')
    Dom.removeClass(['history_subblock_list',  'history_subblock_stores_plot','history_subblock_categories_plot'], 'selected')
    Dom.addClass(this, 'selected')
    Dom.setStyle(['subblock_history_list',  'subblock_history_stores_plot','subblock_history_categories_plot'], 'display', 'none')
    Dom.setStyle(Dom.get('subblock_history_' + block), 'display', '')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=report_sales-history_subblock&value='+block, {
        success: function(o) {}
    });
}

function post_change_period_actions(period, from, to) {

    request = '&from=' + from + '&to=' + to;
alert(request)
    table_id = 0
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
  
    Dom.get('rtext0').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp0').innerHTML = '';
  
  	 table_id = 1
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
  
    Dom.get('rtext'+table_id).innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp'+table_id).innerHTML = '';

	 table_id = 2
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
  
    Dom.get('rtext'+table_id).innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp'+table_id).innerHTML = '';


}

function change_timeline_group(table_id, subject, mode, label) {

    Dom.removeClass(Dom.getElementsByClassName('timeline_group', 'button', subject + '_timeline_group_options'), 'selected');;
    Dom.addClass(subject + '_timeline_group_' + mode, 'selected');
    var request = '&timeline_group=' + mode;
    dialog_sales_history_timeline_group.hide();
    
    Dom.get('change_' + subject + '_timeline_group').innerHTML = ' &#x21b6 ' + label;
    var request = '&timeline_group=' + mode;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function show_dialog_sales_history_timeline_group() {
    region1 = Dom.getRegion('change_sales_history_timeline_group');
    region2 = Dom.getRegion('dialog_sales_history_timeline_group');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_sales_history_timeline_group', pos);
    dialog_sales_history_timeline_group.show();
}


function init_report_sales_main() {



    YAHOO.util.Event.addListener(['stores', 'categories','history'], "click", change_block);
    YAHOO.util.Event.addListener(['stores_subblock_sales', 'stores_subblock_overview'], "click", stores_subblock);
    YAHOO.util.Event.addListener(['categories_subblock_sales',  'categories_subblock_overview'], "click", categories_subblock);
    YAHOO.util.Event.addListener(['history_subblock_list',  'history_subblock_stores_plot','history_subblock_categories_plot'], "click", history_subblock);



  dialog_sales_history_timeline_group = new YAHOO.widget.Dialog("dialog_sales_history_timeline_group", {visible : false,close:true,underlay: "none",draggable:false});
dialog_sales_history_timeline_group.render();
YAHOO.util.Event.addListener("change_sales_history_timeline_group", "click", show_dialog_sales_history_timeline_group);



}

YAHOO.util.Event.onDOMReady(init_report_sales_main);
