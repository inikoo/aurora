<?php
include_once('common.php');

?>
  var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;


  
function create_new_po(){
    var request='ar_orders.php?tipo=create_po';
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		//			alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    window.location.href='porder.php?id='+r.id;
		}
	    }
	});    
    
};
    

YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {
		
		
		    
		var tableid=0;
		var tableDivEL="table"+tableid;
		var ColumnDefs = [
				  
			  {key:"supplier", label:"<?php echo _('Supplier')?>", hidden:true, width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	              ,{key:"code", label:"<?php echo _('Code')?>",  width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"description", label:"<?php echo _('Description')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='general'?'':'hidden:true,')?>width:380, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"used_in", label:"<?php echo _('Used In')?>", width:310,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"stock", label:"<?php echo _('Stock')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='stock'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"weeks_until_out_of_stock", label:"<?php echo _('W Until OO')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='stock'?'':'hidden:true,')?> width:75,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					  ,{key:"required", label:"<?php echo _('Required')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"dispatched", label:"<?php echo _('Dispatched')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 	,{key:"sold", label:"<?php echo _('Sold')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 	,{key:"sales", label:"<?php echo _('Sales')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='sales'?'':'hidden:true,')?> width:75,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 ,{key:"profit", label:"<?php echo _('Profit')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='profit'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 ,{key:"margin", label:"<?php echo _('Margin')?>",<?php echo($_SESSION['state']['supplier']['supplier_products']['view']=='profit'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				  ];
		
		request="ar_suppliers.php?tipo=supplier_products&parent=supplier&parent_key="+Dom.get('supplier_key').value+"&tableid="+tableid
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
			      "description","id","code","name","cost","used_in","profit","allcost","used","required","provided","lost","broken","supplier",
				 "dispatched","sold","sales","weeks_until_out_of_stock","stock","margin"
			     ]};
		
		this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource0, {
							     //draggableColumns:true,
							     renderLoopSize: 50,generateRequest : myRequestBuilder
							     ,paginator : new YAHOO.widget.Paginator({
								     rowsPerPage:<?php echo$_SESSION['state']['supplier']['supplier_products']['nr']?>,containers : 'paginator0', 
								     pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								     previousPageLinkLabel : "<",
								     nextPageLinkLabel : ">",
								     firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
								     ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								 })
							     
							     ,sortedBy : {
								 key: "<?php echo$_SESSION['state']['supplier']['supplier_products']['order']?>",
								 dir: "<?php echo$_SESSION['state']['supplier']['supplier_products']['order_dir']?>"
							     }
							     ,dynamicData : true
							     
							 }
							 );
		this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		
		this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);
		
		this.table0.filter={key:'<?php echo$_SESSION['state']['supplier']['supplier_products']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier']['supplier_products']['f_value']?>'};
		this.table0.view='<?php echo$_SESSION['state']['supplier']['supplier_products']['view']?>';
		
		


	





		
		
		
		
		var tableid=1; // Change if you have more the 1 table
		var tableDivEL="table"+tableid;
		var SuppliersColumnDefs = [
					   {key:"id", label:"<?php echo _('Id')?>",  width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"date", label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   ,{key:"status", label:"<?php echo _('Type')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"items", label:"<?php echo _('Items')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   ,{key:"total", label:"<?php echo _('Total')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ];
		
		this.dataSource1 = new YAHOO.util.DataSource("ar_porders.php?tipo=purchase_orders&parent=supplier&parent_key="+supplier_key+"&tableid=1");
		//	alert("ar_porders.php?tipo=purchase_orders&parent=supplier&parent_key="+supplier_key+"tableid=1")
	this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.dataSource1.connXhrMode = "queueRequests";
		this.dataSource1.responseSchema = {
		    resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rowsPerPage:"resultset.records_perpage",
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
			 ,"items"
			 ,"total"

	 ]};

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs,
						     this.dataSource1, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['porders']['table']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['porders']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['porders']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.table_id=tableid;
     	this.table1.subscribe("renderEvent", myrenderEvent);
	    
	    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['porders']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['porders']['table']['f_value']?>'};
	
	
	
	
		    var tableid=2;
		    var tableDivEL="table"+tableid;

  var ColumnDefs = [
				      {key:"date", label:"<?php echo _('Date')?>", width:200,sortable:false,className:"aright"}
				      ,{key:"locations", label:"<?php echo _('Locations')?>", width:100,sortable:false,className:"aleft"}
				      ,{key:"quantity", label:"<?php echo _('Qty')?>", width:100,sortable:false,className:"aleft"}
				      ,{key:"value", label:"<?php echo _('Value')?>", width:60,sortable:false,className:"aleft"}
				      
				      ,{key:"sold_qty", label:"<?php echo _('Sold')?>", width:60,sortable:false,className:"aright"}
				      ,{key:"in_qty", label:"<?php echo _('In')?>", width:60,sortable:false,className:"aright"}
				      ,{key:"lost_qty", label:"<?php echo _('Lost')?>", width:60,sortable:false,className:"aright"}

				      ];

		 
		    
		    this.dataSource2 = new YAHOO.util.DataSource("ar_assets.php?tipo=part_stock_history&parent=supplier&parent_key="+Dom.get('supplier_key').value+"&sf=0&tableid="+tableid);
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
				 "date","locations","quantity","value","sold_qty","in_qty","lost_qty"

				 ]};

	    
		    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource2, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({alwaysVisible:false,
									 rowsPerPage:<?php echo$_SESSION['state']['part']['stock_history']['nr']?>,containers : 'paginator2', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								   key: "<?php echo$_SESSION['state']['part']['stock_history']['order']?>",
								    dir: "<?php echo$_SESSION['state']['part']['stock_history']['order_dir']?>"
								  }
								 ,dynamicData : true
								 
							     }
							     );


		    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
		    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
		    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;


	
	
	
	
	var tableid=3; // Change if you have more the 1 table
		var tableDivEL="table"+tableid;
		var SuppliersColumnDefs = [
					   {key:"id", label:"<?php echo _('Id')?>",  width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"date", label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   ,{key:"status", label:"<?php echo _('Type')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"items", label:"<?php echo _('Items')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   //,{key:"total", label:"<?php echo _('Total')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ];
		
		this.dataSource3 = new YAHOO.util.DataSource("ar_porders.php?tipo=delivery_notes&parent=supplier&parent_key="+supplier_key+"&tableid=3");
	this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.dataSource3.connXhrMode = "queueRequests";
		this.dataSource3.responseSchema = {
		    resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rowsPerPage:"resultset.records_perpage",
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
			 ,"items"

	 ]};

	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs,
						     this.dataSource3, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['supplier_dns']['table']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['supplier_dns']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['supplier_dns']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table3.table_id=tableid;
     this.table3.subscribe("renderEvent", myrenderEvent);
	    
	    
	    this.table3.filter={key:'<?php echo$_SESSION['state']['supplier_dns']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_dns']['table']['f_value']?>'};


				    var tableid=4; 
		    var tableDivEL="table"+tableid;  
		    
		    
		    var myRowFormatter = function(elTr, oRecord) {		   
				if (oRecord.getData('type') =='Orders') {
					Dom.addClass(elTr, 'customer_history_orders');
				}else if (oRecord.getData('type') =='Notes') {
					Dom.addClass(elTr, 'customer_history_notes');
				}else if (oRecord.getData('type') =='Changes') {
					Dom.addClass(elTr, 'customer_history_changes');
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
                      ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'supplier_history'}
                      ,{key:"edit", label:"",width:12,sortable:false,action:'edit',object:'supplier_history'}

					   ];
		request="ar_history.php?tipo=supplier_history&parent=supplier&parent_key="+Dom.get('supplier_key').value+"&sf=0&tableid="+tableid
		//alert(request)
		    this.dataSource4  = new YAHOO.util.DataSource(request);
		    this.dataSource4.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource4.connXhrMode = "queueRequests";
	    this.dataSource4.responseSchema = {
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
		    this.table4 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource4
								 , {
								 formatRow: myRowFormatter,
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['supplier']['history']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['supplier']['history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['supplier']['history']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	        this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
	        this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table4.filter={key:'<?php echo$_SESSION['state']['supplier']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier']['history']['f_value']?>'};

	        this.table4.subscribe("cellMouseoverEvent", highlightEditableCell);
	        this.table4.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table4.subscribe("cellClickEvent", onCellClick);            
			this.table4.table_id=tableid;
     		this.table4.subscribe("renderEvent", myrenderEvent);



  var tableid=100; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

 this.remove_links = function(elLiner, oRecord, oColumn, oData) {
  elLiner.innerHTML = oData;
         //   if(oRecord.getData("field3") > 100) {
       elLiner.innerHTML=  oData.replace(/<.*?>/g, '')

        };
        
        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;

	   
	    var ColumnDefs = [
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"code",formatter:"remove_links", label:"<?php echo _('Code')?>",width:30,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", formatter:"remove_links",label:"<?php echo _('Name')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			];
			       
	    this.dataSource100 = new YAHOO.util.DataSource("ar_regions.php?tipo=country_list&tableid="+tableid+"&nr=20&sf=0");
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
			 "name","flag",'code','population','gnp','wregion','code3a','code2a','plain_name','postal_regex','postcode_help'
			 ]};


	    this.table100 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource100
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['world']['countries']['nr']?>,containers : 'paginator100', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info100'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['world']['countries']['order']?>",
									 dir: "<?php echo$_SESSION['state']['world']['countries']['order_dir']?>"
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
      this.table100.subscribe("rowClickEvent", select_country_from_list);
     
this.table100.table_id=tableid;
     this.table100.subscribe("renderEvent", myrenderEvent);

	    this.table100.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table100.filter={key:'<?php echo$_SESSION['state']['world']['countries']['f_field']?>',value:'<?php echo$_SESSION['state']['world']['countries']['f_value']?>'};
	    //



	    }});
	    
	    
	function change_sales_sub_block(o) {
        Dom.removeClass(['plot_family_sales', 'product_sales', 'family_sales_timeseries'], 'selected')
        Dom.addClass(o, 'selected')
        Dom.setStyle(['sub_block_plot_family_sales', 'sub_block_product_sales', 'sub_block_family_sales_timeseries'], 'display', 'none')
        Dom.setStyle('sub_block_' + o.id, 'display', '')
        YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=family-sales_sub_block_tipo&value=' + o.id, {});
    }


    function orders_change_view(e) {

        var tipo = this.id;
        switch (tipo) {
        case ('dns'):
            Dom.get('block_pos').style.display = 'none';
            Dom.get('block_invoices').style.display = 'none';
            Dom.get('block_dns').style.display = '';
            Dom.removeClass('pos', "selected");
            Dom.removeClass('invoices', "selected");
            Dom.addClass('dns', "selected");
            break;
        case ('pos'):
            Dom.get('block_pos').style.display = '';
            Dom.get('block_invoices').style.display = 'none';
            Dom.get('block_dns').style.display = 'none';
            Dom.removeClass('dns', "selected");
            Dom.removeClass('invoices', "selected");
            Dom.addClass('pos', "selected");
            break;
        case ('invoices'):
            Dom.get('block_pos').style.display = 'none';
            Dom.get('block_invoices').style.display = '';
            Dom.get('block_dns').style.display = 'none';
            Dom.removeClass('pos', "selected");
            Dom.removeClass('dns', "selected");
            Dom.addClass('invoices', "selected");
            break;
        }


        YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier-orders_view&value=' + escape(tipo), {}, null);


    }
    var product_change_view = function(e) {

            var table = tables['table0'];
            var tipo = this.id;

            if (table.view != tipo) {
                table.hideColumn('cost');
                table.hideColumn('required');
                table.hideColumn('provided');
                table.hideColumn('profit');
                table.hideColumn('name');
                table.hideColumn('tuos');
                table.hideColumn('usld');
                table.hideColumn('stock');
                table.hideColumn('sales');



                if (tipo == 'product_sales') {
                    table.showColumn('cost');
                    table.showColumn('provided');
                    table.showColumn('required');
                    table.showColumn('profit');
                    table.showColumn('sales');


                } else if (tipo == 'product_general') {
                    table.showColumn('name');

                } else if (tipo == 'product_stock') {
                    table.showColumn('usld');
                    table.showColumn('stock');
                    table.showColumn('name');

                } else if (tipo == 'product_forecast') {
                    table.showColumn('tuos');
                    table.showColumn('usld');

                }




                Dom.get(table.view).className = "";
                Dom.get(tipo).className = "selected";

                table.view = tipo;
                YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier-products-view&value=' + escape(tipo), {});

            }
        }

    function change_block() {
        ids = ["details", "products", "purchase_orders", "purchases", "sales", "history"];
        block_ids = ["block_details", "block_products", "block_purchase_orders", "block_purchases", "block_sales", "block_history"];

        Dom.setStyle(block_ids, 'display', 'none');
        Dom.setStyle('block_' + this.id, 'display', '');
        Dom.removeClass(ids, 'selected');
        Dom.addClass(this, 'selected');

        YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier-block_view&value=' + this.id, {});
    }


    function get_supplier_sales_data(from, to) {
        var request = 'ar_suppliers.php?tipo=get_supplier_sales_data&supplier_key=' + Dom.get('supplier_key').value + '&from=' + from + '&to=' + to
        //alert(request);
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

                    Dom.get('required').innerHTML = r.required;
                    Dom.get('out_of_stock').innerHTML = r.out_of_stock;
                    Dom.get('not_found').innerHTML = r.not_found;



                }


            }
        });

    }


    function init() {

        get_supplier_sales_data(Dom.get('from').value, Dom.get('to').value)
        init_search('supplier_products_supplier');

		/*
        YAHOO.util.Event.addListener('export_csv0', "click", download_csv, 'supplier');
        YAHOO.util.Event.addListener('export_csv0_in_dialog', "click", download_csv_from_dialog, {
            table: 'export_csv_table0',
            tipo: 'supplier'
        });
        csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {
            trigger: "export_csv0"
        });
        csvMenu.render();
        csvMenu.subscribe("show", csvMenu.focus);

        YAHOO.util.Event.addListener('export_csv0_close_dialog', "click", csvMenu.hide, csvMenu, true);
		*/
		
		
        var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms, {
            table_id: 0
        });
        oACDS.queryMatchContains = true;
        var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
        oAutoComp.minQueryLength = 0;

        ids = ['pos', 'dns', 'invoices'];
        YAHOO.util.Event.addListener(ids, "click", orders_change_view)

        ids = ['product_general', 'product_sales', 'product_stock', 'product_forecast'];
        YAHOO.util.Event.addListener(ids, "click", product_change_view)

        ids = ["details", "products", "purchase_orders", "purchases", "sales", "history"];
        Event.addListener(ids, "click", change_block);


        ids = ['supplier_products_general', 'supplier_products_sales', 'supplier_products_stock', 'supplier_products_profit'];
        YAHOO.util.Event.addListener(ids, "click", change_supplier_products_view, {
            'table_id': 0,
            'parent': 'supplier'
        })


        ids = ['supplier_products_period_all', 'supplier_products_period_year', 'supplier_products_period_quarter', 'supplier_products_period_month', 'supplier_products_period_week',
                 'supplier_products_period_six_month', 'supplier_products_period_three_year', 'supplier_products_period_ten_day', 'supplier_products_period_month', 'supplier_products_period_week',
                 'supplier_products_period_yeartoday', 'supplier_products_period_monthtoday', 'supplier_products_period_weektoday'

                 ];

        YAHOO.util.Event.addListener(ids, "click", change_period, {
            'table_id': 0,
            'subject': 'supplier_products'
        });
        
        
        
        
    };
    YAHOO.util.Event.onDOMReady(init);

    YAHOO.util.Event.onContentReady("filtermenu0", function() {
        var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
            trigger: "filter_name0"
        });
        oMenu.render();
        oMenu.subscribe("show", oMenu.focus);

    });


    YAHOO.util.Event.onContentReady("rppmenu0", function() {
        var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {
            trigger: "rtext_rpp0"
        });
        oMenu.render();
        oMenu.subscribe("show", oMenu.focus);
    });

    YAHOO.util.Event.onContentReady("filtermenu1", function() {
        var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
            trigger: "filter_name1"
        });
        oMenu.render();
        oMenu.subscribe("show", oMenu.focus);
        YAHOO.util.Event.addListener("filter_name1", "click", oMenu.show, null, oMenu);
    });


    YAHOO.util.Event.onContentReady("rppmenu1", function() {
        var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {
            trigger: "rtext_rpp1"
        });
        oMenu.render();
        oMenu.subscribe("show", oMenu.focus);
        YAHOO.util.Event.addListener("rtext_rpp1", "click", oMenu.show, null, oMenu);
    });

    YAHOO.util.Event.onContentReady("filtermenu2", function() {
        var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {
            trigger: "filter_name2"
        });
        oMenu.render();
        oMenu.subscribe("show", oMenu.focus);
        YAHOO.util.Event.addListener("filter_name2", "click", oMenu.show, null, oMenu);
    });


    YAHOO.util.Event.onContentReady("rppmenu2", function() {
        var oMenu = new YAHOO.widget.Menu("rppmenu2", {
            context: ["filter_name2", "tr", "bl"]
        });
        oMenu.render();
        oMenu.subscribe("show", oMenu.focus);
        YAHOO.util.Event.addListener("paginator_info2", "click", oMenu.show, null, oMenu);
    });
