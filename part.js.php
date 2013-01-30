<?php
include_once('common.php');?>

var link='part.php';

  var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;
var dialog_qty;
var dialog_move_qty;
var category_labels={'stock':'<?php echo _('Stock Keeping Units')?>','value':'<?php echo _('Stock value')?>'};
var change_plot_menu;


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

		 
		    
		    this.dataSource0 = new YAHOO.util.DataSource("ar_parts.php?tipo=part_stock_history&parent=part&parent_key="+Dom.get('part_sku').value+"&sf=0&tableid="+tableid);
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
									 rowsPerPage:<?php echo$_SESSION['state']['part']['stock_history']['nr']?>,containers : 'paginator0', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								   key: "<?php echo$_SESSION['state']['part']['stock_history']['order']?>",
								    dir: "<?php echo$_SESSION['state']['part']['stock_history']['order_dir']?>"
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
				      
				      ,{key:"type", label:"<?php echo _('Type')?>", width:50,sortable:false,className:"aleft"}
				       ,{key:"user", label:"<?php echo _('User')?>", width:50,sortable:false,className:"aleft"}
				     ,{key:"location", label:"<?php echo _('Location')?>", width:60,sortable:false,className:"aleft"}

				      ,{key:"note", label:"<?php echo _('Note')?>", width:300,sortable:false,className:"aleft"}
				      ,{key:"change", label:"<?php echo _('Change')?>", width:60,sortable:false,className:"aright"}

				      ];
		 
		    
		    this.dataSource1 = new YAHOO.util.DataSource("ar_parts.php?tipo=part_transactions&parent=part&parent_key="+Dom.get('part_sku').value+"&sf=0&tableid="+tableid);
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
				 "date","change","type","location","note","user"

				 ]};
	    
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource1, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
								   
									 rowsPerPage:<?php echo$_SESSION['state']['part']['transactions']['nr']?>,containers : 'paginator1', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								   key: "<?php echo$_SESSION['state']['part']['transactions']['order']?>",
								    dir: "<?php echo$_SESSION['state']['part']['transactions']['order_dir']?>"
								  }
								 ,dynamicData : true
								 
							     }
							     );


		    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

   this.table1.filter={key:'<?php echo$_SESSION['state']['part']['transactions']['f_field']?>',value:'<?php echo$_SESSION['state']['part']['transactions']['f_value']?>'};


		   

var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"id", label:"<?php echo _('Number')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"date", label:"<?php echo _('Date')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"type", label:"<?php echo _('Type')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"customer",label:"<?php echo _('Customer')?>", width:280,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"weight",label:"<?php echo _('Weight')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"parcels",label:"<?php echo _('Parcels')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       


					 ];

	    this.dataSource2 = new YAHOO.util.DataSource("ar_orders.php?tipo=dn&tableid=2");
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
			 "orders","invoices","weight","parcels"
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource2, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['part']['delivery_notes']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['part']['delivery_notes']['order']?>",
									 dir: "<?php echo$_SESSION['state']['part']['delivery_notes']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'<?php echo$_SESSION['state']['part']['delivery_notes']['f_field']?>',value:'<?php echo$_SESSION['state']['part']['delivery_notes']['f_value']?>'};
	    
	    
	    
	    
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
		request="ar_history.php?tipo=store_history&parent=part&parent_key="+Dom.get('part_sku').value+"&sf=0&tableid="+tableid
		//alert(request)
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
									      rowsPerPage    : <?php echo$_SESSION['state']['part']['history']['nr']?>,containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['part']['history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['part']['history']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );

	    	this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	        this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	        this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table3.filter={key:'<?php echo$_SESSION['state']['part']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['part']['history']['f_value']?>'};
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

		 
		    request="ar_parts.php?tipo=part_sales_history&parent=part&parent_key="+Dom.get('part_sku').value+"&tableid="+tableid+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
		//   alert(request)
		  
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
									       rowsPerPage:<?php echo$_SESSION['state']['part']['sales_history']['nr']?>,containers : 'paginator4', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['part']['sales_history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['part']['sales_history']['order_dir']?>"
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
		this.table4.filter={key:'<?php echo$_SESSION['state']['part']['sales_history']['f_field']?>',value:'<?php echo$_SESSION['state']['part']['sales_history']['f_value']?>'};


    var tableid=5;
		    var tableDivEL="table"+tableid;

  			var ColumnDefs = [
				      {key:"store", label:"<?php echo _('Store')?>", width:200,sortable:false,className:"aright"}
				      ,{key:"code", label:"<?php echo _('Code')?>", width:200,sortable:false,className:"aright"}
				      //,{key:"invoices", label:"<?php echo _('Invoices')?>", width:100,sortable:false,className:"aright"}
				      //,{key:"customers", label:"<?php echo _('Customers')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"sales", label:"<?php echo _('Sales')?>", width:100,sortable:false,className:"aright"}
				    //  ,{key:"qty", label:"<?php echo _('Sold')?>", width:100,sortable:false,className:"aright"}
				      //,{key:"out_of_stock", label:"<?php echo _('Out of Stock')?>", width:100,sortable:false,className:"aright"}


					      ];

		 
		    request="ar_parts.php?tipo=product_breakdown&part_sku="+Dom.get('part_sku').value+"&tableid="+tableid+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
		//  alert(request)
		  
		  this.dataSource5 = new YAHOO.util.DataSource(request);
	    this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource5.connXhrMode = "queueRequests";
 
	    this.dataSource5.responseSchema = {
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
				 "store","code","sales",

				 ]};

	  
	    this.table5 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource5, {
							 //draggableColumns:true,
							 formatRow: myRowFormatter,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo$_SESSION['state']['part']['product_breakdown']['nr']?>,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['part']['product_breakdown']['order']?>",
									 dir: "<?php echo$_SESSION['state']['part']['product_breakdown']['order_dir']?>"
								     }
							   ,dynamicData : true  

						     }
						     );
	    this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table5.doBeforePaginator = mydoBeforePaginatorChange;
      	this.table5.request=request;
  		this.table5.table_id=tableid;
     	this.table5.subscribe("renderEvent", myrenderEvent);
		this.table5.filter={key:'<?php echo$_SESSION['state']['part']['product_breakdown']['f_field']?>',value:'<?php echo$_SESSION['state']['part']['product_breakdown']['f_value']?>'};


	    
	    };
    });




var change_snapshot_granularity=function(e){
     var table=tables.table0;
     var datasource=tables.dataSource0;
     Dom.removeClass(Dom.getElementsByClassName('table_type','span' , 'stock_history_type'),'selected');;
     Dom.addClass(this,'selected');     
     var request='&type='+this.getAttribute('table_type');
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
 }
 var change_transaction_type=function(e){
     var table=tables.table1;
     var datasource=tables.dataSource1;
     Dom.removeClass(Dom.getElementsByClassName('transaction_type','span' , 'transaction_chooser'),'selected');;
     Dom.addClass(this,'selected');     
     var request='&view='+this.getAttribute('table_type');
  
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
 }

function change_block(){
ids=['description','sales','transactions','history','purchase_orders', 'delivery_notes','notes'];
block_ids=['block_description','block_sales','block_transactions','block_history','block_purchase_orders','block_delivery_notes','block_notes'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=part-view&value='+this.id ,{});
}


function change_timeseries_type(e, table_id) {

    ids = ['part_sales_history_type_year', 'part_sales_history_type_month', 'part_sales_history_type_week', 'part_sales_history_type_day'];
    Dom.removeClass(ids, 'selected')
    Dom.addClass(this, 'selected')

    type = this.getAttribute('tipo')


    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];

    var request = '&sf=0&type=' + type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
};




function change_sales_period(){
  tipo=this.id;
 
  ids=['parts_period_yesterday','parts_period_last_m','parts_period_last_w','parts_period_all','parts_period_three_year','parts_period_year','parts_period_six_month','parts_period_quarter','parts_period_month','parts_period_ten_day','parts_period_week','parts_period_yeartoday','parts_period_monthtoday','parts_period_weektoday','parts_period_today'];

 Dom.removeClass(ids,"selected")
 Dom.addClass(this,"selected")
   period=this.getAttribute('period');
 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=warehouse-parts-period&value='+period ,{});

Dom.setStyle(['info_yesterday','info_last_m','info_last_w','info_all','info_three_year','info_year','info_six_month','info_quarter','info_month','info_ten_day','info_week','info_yeartoday','info_monthtoday','info_weektoday','info_today'],'display','none')


Dom.setStyle(['info2_yesterday','info2_last_m','info2_last_w','info2_all','info2_three_year','info2_year','info2_six_month','info2_quarter','info2_month','info2_ten_day','info2_week','info2_yeartoday','info2_monthtoday','info2_weektoday','info2_today'],'display','none')
Dom.setStyle(['info_'+period,'info2_'+period],'display','')

}


function hide_stock_history_chart(){
Dom.setStyle(['stock_history_plot_subblock_part','hide_stock_history_chart'],'display','none')
Dom.setStyle('show_stock_history_chart','display','')
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=part-stock_history-show_chart&value=0',{});
}

function show_stock_history_chart(){
Dom.setStyle(['hide_stock_history_chart','stock_history_plot_subblock_part'],'display','')
Dom.setStyle(['show_stock_history_chart'],'display','none')
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=part-stock_history-show_chart&value=1' ,{});
}

function show_picking_limit_quantities(o){
		    Dom.setStyle('dialog_qty_msg','display','none')

if(!Dom.get('modify_stock').value)return;

	region1 = Dom.getRegion(o); 
	region2 = Dom.getRegion('dialog_qty'); 

	var pos =[region1.right-region2.width,region1.bottom]

	Dom.setXY('dialog_qty', pos);
	
	
	Dom.get('min_qty').value=(o.getAttribute('min_value')=='?'?'':o.getAttribute('min_value'));
	Dom.get('max_qty').value=(o.getAttribute('max_value')=='?'?'':o.getAttribute('max_value'));
	Dom.get('part_location').value=o.getAttribute('location_key');
	dialog_qty.show();
}

function show_move_quantities(o){

if(!Dom.get('modify_stock').value)return;

	region1 = Dom.getRegion(o); 
	region2 = Dom.getRegion('dialog_move_qty'); 

	var pos =[region1.right-region2.width,region1.bottom]

	Dom.setXY('dialog_move_qty', pos);
	
	Dom.get('move_qty_part').value=(o.getAttribute('move_qty')=='?'?'':o.getAttribute('move_qty'));
	Dom.get('move_qty_part_location').value=Dom.get(o).getAttribute('location_key');
	dialog_move_qty.show();
}



function save_move_qty(){
//alert(sku);
//alert(Dom.get('part_location').value + ':'+Dom.get('part_sku').value);//return;

//ar_edit_warehouse.php?tipo=edit_part_location&key=min&newvalue=4&oldvalue=null&location_key=&part_sku=7
    var request='ar_edit_warehouse.php?tipo=update_move_qty&move_qty='+Dom.get('move_qty_part').value+'&location_key='+Dom.get('move_qty_part_location').value+'&part_sku='+Dom.get('part_sku').value
  // alert(request);  
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
				//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		   dialog_move_qty.hide();
		   
		   if(r.action='updated'){
		   	o=Dom.get('store_limit_quantities_'+r.sku+'_'+r.location_key)
		   	o.setAttribute('move_qty',r.move_qty)
		   	Dom.get('store_limit_move_qty_'+r.sku+'_'+r.location_key).innerHTML=r.move_qty;


		   }
		   
		   
		   //window.location.reload();

		}else{
		  alert(r.msg);
	    }
	    }
	});    





}

function save_picking_quantity_limits(){
//alert(sku);
//alert(Dom.get('part_location').value + ':'+Dom.get('part_sku').value);//return;

//ar_edit_warehouse.php?tipo=edit_part_location&key=min&newvalue=4&oldvalue=null&location_key=&part_sku=7
    var request='ar_edit_warehouse.php?tipo=update_save_picking_location_quantity_limits&newvalue_min='+Dom.get('min_qty').value+'&newvalue_max='+Dom.get('max_qty').value+'&location_key='+Dom.get('part_location').value+'&part_sku='+Dom.get('part_sku').value
  // alert(request);  
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		//		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		   
		   
		    if(r.action='error'){
		    Dom.setStyle('dialog_qty_msg','display','')
		    	Dom.get('dialog_qty_msg_text').innerHTML=r.msg
		    
		    }else{
		   
		   dialog_qty.hide();
		   
		   if(r.action='updated'){
		   	o=Dom.get('picking_limit_quantities_'+r.sku+'_'+r.location_key)
		   	o.setAttribute('min_value',r.min_value)
		   	o.setAttribute('max_value',r.max_value)
		   	Dom.get('picking_limit_min_'+r.sku+'_'+r.location_key).innerHTML=r.min_value;
		   	Dom.get('picking_limit_max_'+r.sku+'_'+r.location_key).innerHTML=r.max_value;


		   }
		   }
		   
		   //window.location.reload();

		}else{
		  alert(r.msg);
	    }
	    }
	});    





}

function change_web_configuration(o,product_pid){
region1 = Dom.getRegion(o); 
    region2 = Dom.getRegion('dialog_edit_web_state'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('dialog_edit_web_state', pos);
Dom.get('product_pid').value=product_pid
dialog_edit_web_state.show()

}

function get_part_sales_data(from, to) {
    var request = 'ar_parts.php?tipo=get_part_sales_data&part_sku=' + Dom.get('part_sku').value + '&from=' + from + '&to=' + to
    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //	alert(o.responseText)
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

                Dom.get('required').innerHTML = r.required;
                Dom.get('out_of_stock').innerHTML = r.out_of_stock;
                Dom.get('not_found').innerHTML = r.not_found;



            }
        }
    });

}


function set_web_configuration(value) {
    var request = 'ar_edit_assets.php?tipo=edit_product&key=web_configuration&pid=' + Dom.get('product_pid').value + '&newvalue=' + value
    //   alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //	alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                dialog_edit_web_state.hide()
                Dom.get('product_web_state_' + r.newdata.pid).innerHTML = r.newdata.icon
                Dom.get('product_web_configuration_' + r.newdata.pid).innerHTML = r.newdata.formated_web_configuration_bis

            } else {
                alert(r.msg);
            }
        }
    });

}


function change_plot(type){
Dom.setStyle(['change_plot_label_stock','change_plot_label_value','change_plot_label_end_day_value','change_plot_label_commercial_value'],'display','none')
Dom.setStyle('change_plot_label_'+type,'display','')


change_plot_menu.hide()



reloadSettings("conf/plot_general_candlestick.xml.php?tipo=part_stock_history&output="+type+"&parent=part&parent_key="+Dom.get('part_sku').value);

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=part-stock_history-chart_output&value='+type ,{});

}
function show_dialog_change_plot(){
region1 = Dom.getRegion('change_plot'); 
    region2 = Dom.getRegion('change_plot_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_plot_menu', pos);

change_plot_menu.show()
}


function get_part_transaction_numbers(from,to){


var ar_file='ar_parts.php'; 
    	var request='tipo=number_part_transactions_in_interval&part_sku='+Dom.get('part_sku').value+'&from='+from+'&to='+to;
			
			
			Dom.setStyle(['transactions_all_transactions_wait','transactions_in_transactions_wait','transactions_out_transactions_wait','transactions_audit_transactions_wait','transactions_oip_transactions_wait','transactions_move_transactions_wait'],'display','');


Dom.get('transactions_all_transactions').innerHTML='';
						Dom.get('transactions_in_transactions').innerHTML='';
						Dom.get('transactions_out_transactions').innerHTML='';
						Dom.get('transactions_audit_transactions').innerHTML='';
						Dom.get('transactions_oip_transactions').innerHTML='';
						Dom.get('transactions_move_transactions').innerHTML='';


	YAHOO.util.Connect.asyncRequest(
					'POST',
					ar_file, {
					    success:function(o) {
						//alert(o.responseText);
						var r = YAHOO.lang.JSON.parse(o.responseText);
						if (r.state == 200) {

						Dom.setStyle(['transactions_all_transactions_wait','transactions_in_transactions_wait','transactions_out_transactions_wait','transactions_audit_transactions_wait','transactions_oip_transactions_wait','transactions_move_transactions_wait'],'display','none');

						Dom.get('transactions_all_transactions').innerHTML=r.transactions.all_transactions
						Dom.get('transactions_in_transactions').innerHTML=r.transactions.in_transactions
						Dom.get('transactions_out_transactions').innerHTML=r.transactions.out_transactions
						Dom.get('transactions_audit_transactions').innerHTML=r.transactions.audit_transactions
						Dom.get('transactions_oip_transactions').innerHTML=r.transactions.oip_transactions
						Dom.get('transactions_move_transactions').innerHTML=r.transactions.move_transactions

						
						
						}
					    },
					failure:function(o) {
					    alert(o.statusText);
					    
					},
					scope:this
				    },
				    request
				    
				    );  
}



function change_interval(e,suffix){
  
     from=Dom.get("v_calpop1"+suffix).value;
     to=Dom.get("v_calpop2"+suffix).value;

     if(from=='' && to==''){
	 Dom.get('clear_interval'+suffix).style.display='none';

     }else{
	 Dom.get('clear_interval'+suffix).style.display='';

 }


   
   
       Dom.get("v_calpop2"+suffix).value=to;
     Dom.get("v_calpop1"+suffix).value=from;
      var request='&sf=0&from=' +from+'&to='+to;
     if(suffix=='t'){
    get_part_transaction_numbers(from,to)
tables.dataSource1.sendRequest(request,tables.table1.onDataReturnInitializeTable, tables.table1);  
     }else{
     
      tables.dataSource0.sendRequest(request,tables.table0.onDataReturnInitializeTable, tables.table0);  

     }
     
 
   
     
     
 }

function change_sales_sub_block(o) {
    Dom.removeClass(['plot_part_sales',  'part_sales_timeseries','product_breakdown_sales'], 'selected')
    Dom.addClass(o, 'selected')
    Dom.setStyle(['sub_block_plot_part_sales',  'sub_block_part_sales_timeseries','sub_block_product_breakdown_sales'], 'display', 'none')
    Dom.setStyle('sub_block_' + o.id, 'display', '')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=part-sales_sub_block_tipo&value=' + o.id, {});
}



function clear_interval(e,suffix){
 
    var request='&sf=0&from=&to=';
   if(suffix=='t'){
      Dom.get("v_calpop1t").value='';
     Dom.get("v_calpop2t").value='';
             Dom.get('clear_intervalt').style.display='none';
 get_part_transaction_numbers('','')
     tables.dataSource1.sendRequest(request,tables.table1.onDataReturnInitializeTable, tables.table1);       

   }else{
   
   Dom.get("v_calpop1").value='';
     Dom.get("v_calpop2").value='';
           Dom.get('clear_interval').style.display='none';
       tables.dataSource0.sendRequest(request,tables.table0.onDataReturnInitializeTable, tables.table0);       
}
   
 }




function init(){

ids=['part_sales_history_type_year','part_sales_history_type_month','part_sales_history_type_week','part_sales_history_type_day'];
	YAHOO.util.Event.addListener(ids, "click", change_timeseries_type,4);

get_part_sales_data(Dom.get('from').value,Dom.get('to').value)

change_plot_menu = new YAHOO.widget.Dialog("change_plot_menu", {visible : false,close:true,underlay: "none",draggable:false});
change_plot_menu.render();
Event.addListener("change_plot", "click", show_dialog_change_plot);

dialog_edit_web_state = new YAHOO.widget.Dialog("dialog_edit_web_state", {visible : false,close:true,underlay: "none",draggable:false});
dialog_edit_web_state.render();

image_region=Dom.getRegion('main_image')
if(image_region.height>160){
Dom.setStyle('main_image','height','160px')
Dom.setStyle('main_image','width','')
}


cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
 
 cal2.update=updateCal;
 cal2.id='2';
 cal2.render();
 cal2.update();
 cal2.selectEvent.subscribe(handleSelect, cal2, true); 
 cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
 cal1.update=updateCal;
 cal1.id='1';
 cal1.render();
 cal1.update();
 cal1.selectEvent.subscribe(handleSelect, cal1, true); 


cal2t = new YAHOO.widget.Calendar("cal2t","cal2tContainer", { title:"<?php echo _('Choose a date')?>:", close:true } );
 cal2t.update=updateCal;
 cal2t.id='2t';
 cal2t.render();
 cal2t.update();
 cal2t.selectEvent.subscribe(handleSelect, cal2t, true); 
 cal1t = new YAHOO.widget.Calendar("cal1t","cal1tContainer", { title:"<?php echo _('Choose a date')?>:", close:true } );
 cal1t.update=updateCal;
 cal1t.id='1t';
 cal1t.render();
 cal1t.update();
 cal1t.selectEvent.subscribe(handleSelect, cal1t, true);  




 
 Event.addListener("calpop1", "click", cal1.show, cal1, true);
 Event.addListener("calpop2", "click", cal2.show, cal2, true);
Event.addListener("calpop1t", "click", cal1t.show, cal1t, true);
 Event.addListener("calpop2t", "click", cal2t.show, cal2t, true);

 Event.addListener("submit_interval", "click", change_interval,'');
 Event.addListener("clear_interval", "click", clear_interval,'');
Event.addListener("submit_intervalt", "click", change_interval,'t');
 Event.addListener("clear_intervalt", "click", clear_interval,'t');

init_search('parts');
Event.addListener(['description','sales','transactions','history','purchase_orders', 'delivery_notes','notes'], "click",change_block);


var ids =Array("restrictions_all_transactions","restrictions_oip_transactions","restrictions_out_transactions","restrictions_in_transactions","restrictions_audit_transactions","restrictions_move_transactions") ;
Event.addListener(ids, "click", change_transaction_type);
var ids =Array("stock_history_type_month","stock_history_type_week","stock_history_type_day") ;
Event.addListener(ids, "click", change_snapshot_granularity);
    
 YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
  YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);

 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.table_id=1;
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS);
 oAutoComp.minQueryLength = 0; 



 ids=['parts_period_yesterday','parts_period_last_m','parts_period_last_w','parts_period_all','parts_period_three_year','parts_period_year','parts_period_yeartoday','parts_period_six_month','parts_period_quarter','parts_period_month','parts_period_ten_day','parts_period_week','parts_period_monthtoday','parts_period_weektoday','parts_period_today'];
 YAHOO.util.Event.addListener(ids, "click",change_sales_period);


   YAHOO.util.Event.addListener('hide_stock_history_chart', "click",hide_stock_history_chart);
   YAHOO.util.Event.addListener('show_stock_history_chart', "click",show_stock_history_chart);


dialog_qty = new YAHOO.widget.Dialog("dialog_qty", {visible : false,close:true,underlay: "none",draggable:false});
dialog_qty.render();

dialog_move_qty = new YAHOO.widget.Dialog("dialog_move_qty", {visible : false,close:true,underlay: "none",draggable:false});
dialog_move_qty.render();

Event.addListener('close_qty', "click", dialog_qty.hide,dialog_qty , true);

Event.addListener('close_move_qty', "click", dialog_move_qty.hide,dialog_move_qty , true);

get_part_transaction_numbers(Dom.get('v_calpop1t').value,Dom.get('v_calpop2t').value)


}
 YAHOO.util.Event.onDOMReady(init);
 

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });  

 YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });  
