<?php include_once('common.php');


?>


YAHOO.util.Event.onContentReady("table<?php print $_REQUEST['table_id']?>", function () {



	     //START OF THE TABLE=========================================================================================================================
<?php print "var tableid=".$_REQUEST['table_id'].";";?>

		
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
	    
				       {key:"order", label:"<?php echo _('Order')?>", width:80,sortable:false,className:"aleft"}
	    
				       ,{key:"customer", label:"<?php echo _('Customer Name')?>", width:185,sortable:false,className:"aleft"}

				      
				       ,{key:"date", label:"<?php echo _('Date')?>",width:70,sortable:false,className:"aright"}
				       ,{key:"value", label:"<?php echo _('Value')?>",sortable:false,className:"aright"}
				    
				       
				     	      
				      
				      
				      
				     
				       

					 ];
	    this.dataSource0 = new YAHOO.util.DataSource("ar_splinters.php?tipo=orders_in_process&tableid="+tableid);
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
			 "order","customer","date","value"
			
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : top,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: 'date',
									 dir: 'desc'
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    

	    this.table0.filter={key:'',value:''};

	
	});

