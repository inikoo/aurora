<?php include_once('common.php');



?>
  var Dom   = YAHOO.util.Dom;
     var Event = YAHOO.util.Event;
  
       
function change_block() {
    ids = ['details', 'sales', 'stock_transactions', 'stock_history', 'purchase_orders', 'notes']
    block_ids = ['block_details', 'block_sales', 'block_stock_transactions', 'block_stock_history', 'block_purchase_orders', 'block_notes'];

    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');

    if (this.id == 'details' || this.id == 'notes') {
        Dom.setStyle('calendar_container', 'display', 'none');
    } else {
        Dom.setStyle('calendar_container', 'display', '');
    }


    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier_product-block_view&value=' + this.id, {});
}
function change_sales_block(o) {
    Dom.removeClass(['plot_supplier_product_sales',  'supplier_product_sales_timeseries'], 'selected')
    Dom.addClass(this, 'selected')
    Dom.setStyle(['block_plot_supplier_product_sales',  'block_supplier_product_sales_timeseries'], 'display', 'none')
    Dom.setStyle('block_' + this.id, 'display', '')
    
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier_product-sales_block&value=' + this.id, {});
}

function change_stock_history_block(o) {
    Dom.removeClass(['stock_history_plot',  'stock_history_list'], 'selected')
    Dom.addClass(this, 'selected')
    Dom.setStyle(['block_stock_history_plot',  'block_stock_history_list'], 'display', 'none')
    Dom.setStyle('block_' + this.id, 'display', '')
    
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier_product-stock_history_block&value=' + this.id, {});
}

     
YAHOO.util.Event.addListener(window, "load", function() {

	tables = new function() {


    var tableid=0;
		    var tableDivEL="table"+tableid;

  var ColumnDefs = [
				      {key:"date", label:"<?php echo _('Date')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"locations", label:"<?php echo _('Locations')?>", width:100,sortable:false,className:"aleft"}
				      ,{key:"quantity", label:"<?php echo _('Qty')?>", width:80,sortable:false,className:"aright"}
				      ,{key:"value", label:"<?php echo _('Cost Value')?>", width:80,sortable:false,className:"aright"}
				      ,{key:"end_day_value", label:"<?php echo _('C Value (ED)')?>", width:80,sortable:false,className:"aright"}
				      ,{key:"commercial_value", label:"<?php echo _('Com Value')?>", width:80,sortable:false,className:"aright"}

				      ,{key:"sold_qty", label:"<?php echo _('Sold')?>", width:60,sortable:false,className:"aright"}
				      ,{key:"in_qty", label:"<?php echo _('In')?>", width:60,sortable:false,className:"aright"}
				      ,{key:"lost_qty", label:"<?php echo _('Lost')?>", width:60,sortable:false,className:"aright"}

				      ];

		 request="ar_parts.php?tipo=part_stock_history&parent=supplier_product&parent_key="+Dom.get('pid').value+"&sf=0&tableid="+tableid
		  // alert(request)
		    this.dataSource0 = new YAHOO.util.DataSource(request);
		    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
		    this.dataSource0.connXhrMode = "queueRequests";
		    this.dataSource0.responseSchema = {
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
				 "date","locations","quantity","value","sold_qty","in_qty","lost_qty","end_day_value","commercial_value"

				 ]};

	    
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource0, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({alwaysVisible:false,
									 rowsPerPage:<?php echo$_SESSION['state']['supplier_product']['stock_history']['nr']?>,containers : 'paginator0', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								   key: "<?php echo$_SESSION['state']['supplier_product']['stock_history']['order']?>",
								    dir: "<?php echo$_SESSION['state']['supplier_product']['stock_history']['order_dir']?>"
								  }
								 ,dynamicData : true
								 
							     }
							     );


		    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;






		    var tableid=1;
		    var tableDivEL="table"+tableid;

   var ColumnDefs = [
				      {key:"date", label:"<?php echo _('Date')?>", width:150,sortable:false,className:"aright"}
				      
				      ,{key:"type", label:"<?php echo _('Type')?>", width:80,sortable:false,className:"aleft"}
				       ,{key:"user", label:"<?php echo _('User')?>", width:45,sortable:false,className:"aleft"}
				     ,{key:"location", label:"<?php echo _('Location')?>", width:60,sortable:false,className:"aleft"}

				      ,{key:"note", label:"<?php echo _('Note')?>", width:230,sortable:false,className:"aleft"}
				      ,{key:"change", label:"<?php echo _('Change')?>", width:60,sortable:false,className:"aright"}
				     // ,{key:"stock", label:"<?php echo _('Stock')?>", width:60,sortable:false,className:"aright"}

				      ];
		 
		    
		    request="ar_parts.php?tipo=part_transactions&parent=supplier_product&parent_key="+Dom.get('pid').value+"&sf=0&tableid="+tableid
		    
		    this.dataSource1 = new YAHOO.util.DataSource(request);
		    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		    this.dataSource1.connXhrMode = "queueRequests";
		    this.dataSource1.responseSchema = {
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
				 "date","change","type","location","note","user","stock"

				 ]};
	    
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource1, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
								   
									 rowsPerPage:<?php echo$_SESSION['state']['supplier_product']['transactions']['nr']?>,containers : 'paginator1', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								   key: "<?php echo$_SESSION['state']['supplier_product']['transactions']['order']?>",
								    dir: "<?php echo$_SESSION['state']['supplier_product']['transactions']['order_dir']?>"
								  }
								 ,dynamicData : true
								 
							     }
							     );


		    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

   this.table1.filter={key:'<?php echo$_SESSION['state']['supplier_product']['transactions']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_product']['transactions']['f_value']?>'};


		   

var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
					       {key:"id", label:"<?php echo _('Number')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"date", label:"<?php echo _('Date')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"customer",label:"<?php echo _('Customer')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      				       ,{key:"type", label:"<?php echo _('Type')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				      ,{key:"state", label:"<?php echo _('Status')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       ,{key:"weight",label:"<?php echo _('Weight')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"parcels",label:"<?php echo _('Parcels')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       


					 ];

request="ar_suppliers.php?tipo=porders&parent=supplier_product&parent_key="+Dom.get('pid').value+"&tableid="+tableid

	    this.dataSource2 = new YAHOO.util.DataSource(request);
	    this.dataSource2.table_id=tableid;
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
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
			"id",
			 "type",
			 "customer",
			 "date",
			 "state","weight","parcels"
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource2, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['supplier_product']['porders']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['supplier_product']['porders']['order']?>",
									 dir: "<?php echo$_SESSION['state']['supplier_product']['porders']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'<?php echo$_SESSION['state']['supplier_product']['porders']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_product']['porders']['f_value']?>'};
	    
	    
	    
	    
		    var tableid=3; 
		    var tableDivEL="table"+tableid;  
		    
		    
		    var myRowFormatter = function(elTr, oRecord) {		   
				if (oRecord.getData('type') =='Orders') {
					Dom.addClass(elTr, 'store_history_orders');
				}else if (oRecord.getData('type') =='Notes') {
					Dom.addClass(elTr, 'store_history_notes');
				}else if (oRecord.getData('type') =='Changes') {
					Dom.addClass(elTr, 'store_history_changes');
				}
				return true;
			}; 
		    
		    
		this.prepare_note = function(elLiner, oRecord, oColumn, oData) {
          
            if(oRecord.getData("strikethrough")=="Yes") { 
            Dom.setStyle(elLiner,'text-decoration','line-through');
            Dom.setStyle(elLiner,'color','#777');

            }
            elLiner.innerHTML=oData
        };
        		    
		    var ColumnDefs = [
				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				      ,{key:"date", label:"<?php echo _('Date')?>",className:"aright",width:120,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:"<?php echo _('Time')?>",className:"aleft",width:50}
				      ,{key:"handle", label:"<?php echo _('Author')?>",className:"aleft",width:100,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"note", formatter:this.prepare_note,label:"<?php echo _('Notes')?>",className:"aleft",width:520}
                      ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'store_history'}
                      ,{key:"edit", label:"",width:12,sortable:false,action:'edit',object:'store_history'}

					   ];
		request="ar_history.php?tipo=supplier_history&parent=supplier_product&parent_key="+Dom.get('pid').value+"&sf=0&tableid="+tableid
	
		    this.dataSource3  = new YAHOO.util.DataSource(request);
		    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
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
                  fields: ["note","date","time","handle","delete","can_delete" ,"delete_type","key","edit","type","strikethrough"]};
		    this.table3 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource3
								 , {
								 formatRow: myRowFormatter,
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['supplier_product']['history']['nr']?>,containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['supplier_product']['history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['supplier_product']['history']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );

	    	this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	        this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	        this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table3.filter={key:'<?php echo$_SESSION['state']['supplier_product']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_product']['history']['f_value']?>'};
	        this.table3.subscribe("cellMouseoverEvent", highlightEditableCell);
	        this.table3.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table3.subscribe("cellClickEvent", onCellClick);            
			this.table3.table_id=tableid;
     		this.table3.subscribe("renderEvent", myrenderEvent);
     		
     		

	    	      
		    var tableid=4;
		    var tableDivEL="table"+tableid;

  			var ColumnDefs = [
				      {key:"date", label:"<?php echo _('Date')?>", width:200,sortable:false,className:"aright"}
				      //,{key:"invoices", label:"<?php echo _('Invoices')?>", width:100,sortable:false,className:"aright"}
				      //,{key:"customers", label:"<?php echo _('Customers')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"sales", label:"<?php echo _('Sales')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"qty", label:"<?php echo _('Sold')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"out_of_stock", label:"<?php echo _('Out of Stock')?>", width:100,sortable:false,className:"aright"}


					      ];

		 
		    request="ar_reports.php?tipo=inventory_assets_sales_history&sf=0&parent=supplier_product&parent_key="+Dom.get('pid').value+"&tableid="+tableid+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
	  // alert(request)
		  
		  this.dataSource4 = new YAHOO.util.DataSource(request);
	    this.dataSource4.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource4.connXhrMode = "queueRequests";
 
	    this.dataSource4.responseSchema = {
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
				 "date","invoices","customers","sales","qty","out_of_stock"

				 ]};

	  
	    this.table4 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource4, {
							 //draggableColumns:true,
							 formatRow: myRowFormatter,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo$_SESSION['state']['supplier_product']['sales_history']['nr']?>,containers : 'paginator4', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['supplier_product']['sales_history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['supplier_product']['sales_history']['order_dir']?>"
								     }
							   ,dynamicData : true  

						     }
						     );
	    this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table4.doBeforePaginator = mydoBeforePaginatorChange;
      	this.table4.request=request;
  		this.table4.table_id=tableid;
     	this.table4.subscribe("renderEvent", myrenderEvent);
		this.table4.filter={key:'<?php echo$_SESSION['state']['supplier_product']['sales_history']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_product']['sales_history']['f_value']?>'};





/*
		var tableid=0; // Change if you have more the 1 table
		var tableDivEL="table"+tableid;
		var SuppliersColumnDefs = [
					   {key:"id", label:"<?php echo _('Id')?>",  width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"date", label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   ,{key:"status", label:"<?php echo _('PO State')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"qty", label:"<?php echo _('Qty')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   ,{key:"amount", label:"<?php echo _('Amount')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ];
	
	request="ar_porders.php?tipo=purchase_orders_with_product&pid="+Dom.get('pid').value+"&tableid="+tableid
	//alert(request)
	
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
			 "id"
			 ,"status"
			 ,"date"
			 ,"qty"
			 ,"amount"

	 ]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['supplier_product']['porders']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['supplier_product']['porders']['order']?>",
									 dir: "<?php echo$_SESSION['state']['supplier_product']['porders']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['supplier_product']['porders']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_product']['porders']['f_value']?>'};
	
	this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);
	
	
	*/
	
	
 }});

var change_snapshot_granularity=function(e){
     var table=tables.table1;
     var datasource=tables.dataSource1;
     Dom.removeClass(Dom.getElementsByClassName('table_type','span' , 'stock_history_type'),'selected');;
     Dom.addClass(this,'selected');     
     var request='&type='+this.getAttribute('table_type');
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
 }

function hide_stock_history_chart(){
Dom.setStyle(['stock_history_plot','hide_stock_history_chart'],'display','none')
Dom.setStyle('show_stock_history_chart','display','')
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier_product-show_stock_history_chart&value=0',{});
}

function show_stock_history_chart(){
Dom.setStyle(['hide_stock_history_chart','stock_history_plot'],'display','')
Dom.setStyle(['show_stock_history_chart'],'display','none')
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier_product-show_stock_history_chart&value=1' ,{});
}

function get_supplier_product_sales_data(from, to) {
    var request = 'ar_parts.php?tipo=get_inventory_assets_sales_data&subject=supplier_product&subject_key=' + Dom.get('pid').value + '&from=' + from + '&to=' + to

    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('sold').innerHTML = r.sold;
                Dom.get('sales_amount').innerHTML = r.sales;
                Dom.get('profits').innerHTML = r.profits;
                Dom.get('margin').innerHTML = r.margin;
                Dom.get('gmroi').innerHTML = r.gmroi;
                if (r.no_supplied == 0) {
                    Dom.setStyle('no_supplied_tbody', 'display', 'none')
                } else {
                    Dom.setStyle('no_supplied_tbody', 'display', '')

                }

                if (r.given == 0) {
                    Dom.setStyle('given_tr', 'display', 'none')
                    Dom.setStyle('dispatched_tr', 'display', 'none')

                } else {
                    Dom.setStyle('given_tr', 'display', '')
                    Dom.setStyle('dispatched_tr', 'display', '')


                }

                Dom.get('given').innerHTML = r.given;
                Dom.get('dispatched').innerHTML = r.dispatched;

                Dom.get('required').innerHTML = r.required;
                Dom.get('out_of_stock').innerHTML = r.out_of_stock;
                Dom.get('not_found').innerHTML = r.not_found;



            }
        }
    });

}



function get_supplier_product_transaction_numbers(from, to) {


    var ar_file = 'ar_suppliers.php';
    var request = 'tipo=number_supplier_product_transactions_in_interval&supplier_product_pid=' + Dom.get('pid').value + '&from=' + from + '&to=' + to;

   
    Dom.setStyle(['transactions_all_transactions_wait', 'transactions_in_transactions_wait', 'transactions_out_transactions_wait', 'transactions_audit_transactions_wait', 'transactions_oip_transactions_wait', 'transactions_move_transactions_wait'], 'display', '');

    Dom.get('transactions_all_transactions').innerHTML = '';
    Dom.get('transactions_in_transactions').innerHTML = '';
    Dom.get('transactions_out_transactions').innerHTML = '';
    Dom.get('transactions_audit_transactions').innerHTML = '';
    Dom.get('transactions_oip_transactions').innerHTML = '';
    Dom.get('transactions_move_transactions').innerHTML = '';


    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
           
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.setStyle(['transactions_all_transactions_wait', 'transactions_in_transactions_wait', 'transactions_out_transactions_wait', 'transactions_audit_transactions_wait', 'transactions_oip_transactions_wait', 'transactions_move_transactions_wait'], 'display', 'none');
                Dom.get('transactions_all_transactions').innerHTML = r.transactions.all_transactions
                Dom.get('transactions_in_transactions').innerHTML = r.transactions.in_transactions
                Dom.get('transactions_out_transactions').innerHTML = r.transactions.out_transactions
                Dom.get('transactions_audit_transactions').innerHTML = r.transactions.audit_transactions
                Dom.get('transactions_oip_transactions').innerHTML = r.transactions.oip_transactions
                Dom.get('transactions_move_transactions').innerHTML = r.transactions.move_transactions
               
            }
        },
        failure: function(o) {
        },
        scope: this
    }, request

    );
}



function init() {


    get_supplier_product_transaction_numbers(Dom.get('from').value, Dom.get('to').value)
    get_supplier_product_sales_data(Dom.get('from').value, Dom.get('to').value)

    ids = ['details', 'sales', 'stock_transactions', 'stock_history', 'purchase_orders', 'notes']

    Event.addListener(ids, "click", change_block);



    Event.addListener(['plot_supplier_product_sales',  'supplier_product_sales_timeseries'], "click", change_sales_block);
    Event.addListener(['stock_history_plot',  'stock_history_list'], "click", change_stock_history_block);





    init_search('supplier_products_supplier');

    YAHOO.util.Event.addListener('hide_stock_history_chart', "click", hide_stock_history_chart);
    YAHOO.util.Event.addListener('show_stock_history_chart', "click", show_stock_history_chart);

    var ids = Array("stock_history_type_month", "stock_history_type_week", "stock_history_type_day");
    Event.addListener(ids, "click", change_snapshot_granularity);
}





 YAHOO.util.Event.onDOMReady(init);
 YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });