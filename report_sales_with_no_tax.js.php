<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;



  

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"<?php echo _('Public ID')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"date", label:"<?php echo _('Date')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"customer",label:"<?php echo _('Customer')?>", width:160,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"tax_number",label:"<?php echo _('Tax Number')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    
				    ,{key:"orders",label:"<?php echo _('Order')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},'hidden':true}
				    ,{key:"dns",label:"<?php echo _('Delivery Note')?>", width:100,sortable:false,className:"aleft",'hidden':true}
				    ,{key:"send_to",label:"<?php echo _('Send to')?>", width:80,sortable:false,className:"aleft"}
				    
				    ,{key:"total_amount", label:"<?php echo _('Total')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //  ,{key:"state", label:"<?php echo _('Status')?>", width:33,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    
				    
				    
				     ];
	    
	    this.dataSource0 = new YAHOO.util.DataSource("ar_reports.php?tipo=invoices_with_no_tax&tableid=0");
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
			 	 "id",
			 "state",
			 "customer",
			 "date",
			 "date",
				 "total_amount","orders","dns","send_to","tax_number"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['report_sales_with_no_tax']['invoices']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['report_sales_with_no_tax']['invoices']['order']?>",
									 dir: "<?php echo$_SESSION['state']['report_sales_with_no_tax']['invoices']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table0.doBeforeLoadData=mydoBeforeLoadData;


	    
	    this.table0.view='<?php echo$_SESSION['state']['stores']['view']?>';
	    this.table0.filter={key:'<?php echo$_SESSION['state']['report_sales_with_no_tax']['invoices']['f_field']?>',value:'<?php echo$_SESSION['state']['report_sales_with_no_tax']['invoices']['f_value']?>'};

		


	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				   
				    {key:"name",label:"<?php echo _('Customer')?>", width:160,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"tax_number",label:"<?php echo _('Tax Number')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    
				    ,{key:"send_to",label:"<?php echo _('Send to')?>", width:80,sortable:false,className:"aleft"}
				    ,{key:"num_invoices",label:"<?php echo _('Invoices')?>", width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"total_amount", label:"<?php echo _('Total')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //  ,{key:"state", label:"<?php echo _('Status')?>", width:33,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    
				    
				    
				     ];
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_reports.php?tipo=customers_with_no_tax&tableid=1");
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
			 "name","tax_number","num_invoices","send_to","total_amount"
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {
							 //draggableColumns:true,
							 renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage:<?php echo$_SESSION['state']['report_sales_with_no_tax']['customers']['nr']?>,containers : 'paginator1', 
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							     key: "<?php echo$_SESSION['state']['report_sales_with_no_tax']['customers']['order']?>",
							     dir: "<?php echo$_SESSION['state']['report_sales_with_no_tax']['customers']['order_dir']?>"
							 }
							 ,dynamicData : true
							 
						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.doBeforeLoadData=mydoBeforeLoadData;
	    
	    
	    
	    this.table1.view='<?php echo$_SESSION['state']['stores']['view']?>';
	    this.table1.filter={key:'<?php echo$_SESSION['state']['report_sales_with_no_tax']['customers']['f_field']?>',value:'<?php echo$_SESSION['state']['report_sales_with_no_tax']['customers']['f_value']?>'};





	};
    });




 function init(){
 
 

 YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);


 }

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu0", { context:["rtext_rpp0","tl", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp0", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu0", { context:["filter_name0","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });


YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu1", { context:["rtext_rpp1","tl", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp1", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu1", { context:["filter_name1","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name1", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu1", { context:["rtext_rpp1","tl", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp1", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu1", { context:["filter_name1","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name1", "click", oMenu.show, null, oMenu);
    });