sales_tables= new Object();
YAHOO.util.Event.onContentReady("table<?php print $_REQUEST['table_id']?>", function () {

 

	     //START OF THE TABLE=========================================================================================================================

		<?php print "var tableid=".$_REQUEST['table_id'].";";?>

	    var tableDivEL="table"+tableid;


	    var SalesColumnDefs = [
				       {key:"position", label:"", width:2,sortable:false,className:"aleft"}
				       ,{key:"family", label:"<?php echo _('Fam')?>", width:25,sortable:false,className:"aleft"}
				      // ,{key:"code", label:"<?php echo _('Code')?>", width:45,sortable:false,className:"aleft"}
				       ,{key:"description", label:"<?php echo _('Product')?>", width:280,sortable:false,className:"aleft"}

				       ,{key:"net_sales", label:"<?php echo _('Sales')?>", width:65,sortable:false,className:"aright"}

				      
				     
				      
				      
				     
				       

					 ];
	    sales_tables.dataSourcetopprod = new YAHOO.util.DataSource("ar_splinters.php?tipo=products&tableid="+tableid);
	    sales_tables.dataSourcetopprod.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    sales_tables.dataSourcetopprod.connXhrMode = "queueRequests";
	    sales_tables.dataSourcetopprod.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    rtext:"resultset.rtext",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 'position',
			 'store','family','code','description','net_sales'
			 ]};
	    //__You shouls not change anything from here

	    //sales_tables.dataSource.doBeforeCallback = mydoBeforeCallback;


	    sales_tables.table1 = new YAHOO.widget.DataTable(tableDivEL, SalesColumnDefs,
								   sales_tables.dataSourcetopprod
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : product_nr,containers : 'paginator', 
 									      pageReportTemplate : '(Page {currentPage} of {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								    ,sortedBy : {
									 key: 'position',
									 dir: 'desc'
								     }
								     ,dynamicData : true

								  }
								   
								 );
	    
	    sales_tables.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    sales_tables.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    sales_tables.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	   

	    sales_tables.table1.filter={key:'',value:''};

	});
