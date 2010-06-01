<?php include_once('common.php');


?>


YAHOO.util.Event.onContentReady("table<?php print $_REQUEST['table_id']?>", function () {



	     //START OF THE TABLE=========================================================================================================================
<?php print "var tableid=".$_REQUEST['table_id'].";";?>

		
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
	    
				       {key:"public_id", label:"<?php echo _('Order')?>", width:185,sortable:false,className:"aleft"}
	    
				       ,{key:"customer", label:"<?php echo _('Customer Name')?>", width:185,sortable:false,className:"aleft"}

				      
				       ,{key:"date", label:"<?php echo _('Date')?>",width:70,sortable:false,className:"aright"}
				       ,{key:"value", label:"<?php echo _('Value')?>",sortable:false,className:"aright"}
				    
				       
				     	      
				      
				      
				      
				     
				       

					 ];
	    this.dataSourcetopcust = new YAHOO.util.DataSource("ar_reports.php?tipo=orders_in_process&tableid="+tableid);
	    this.dataSourcetopcust.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSourcetopcust.connXhrMode = "queueRequests";
	    this.dataSourcetopcust.responseSchema = {
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
			 "public_id","customer","date","value"
			
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSourcetopcust
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
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    

	    this.table1.filter={key:'',value:''};

	
	});

