<?php include_once('common.php');

print "var top=".$_SESSION['state']['report']['products']['top'].";";
print "var criteria='".$_SESSION['state']['report']['products']['criteria']."';";

?>


YAHOO.util.Event.onContentReady("table<?php print $_REQUEST['table_id']?>", function () {



	     //START OF THE TABLE=========================================================================================================================

		<?php print "var tableid=".$_REQUEST['table_id'].";";?>

	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"position", label:"", width:2,sortable:false,className:"aleft"}
				       ,{key:"family", label:"<?php echo _('Fam')?>", width:25,sortable:false,className:"aleft"}
				      // ,{key:"code", label:"<?php echo _('Code')?>", width:45,sortable:false,className:"aleft"}
				       ,{key:"description", label:"<?php echo _('Product')?>", width:280,sortable:false,className:"aleft"}

				       ,{key:"net_sales", label:"<?php echo _('Sales')?>", width:65,sortable:false,className:"aright"}

				      
				     
				      
				      
				     
				       

					 ];
	    //?tipo=customers&tid=0"
	    this.dataSourcetopprod = new YAHOO.util.DataSource("ar_reports.php?tipo=products&nr=20&tableid="+tableid);
	  //  alert("ar_reports.php?tipo=customers&nr=20&tableid="+tableid)
	    this.dataSourcetopprod.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSourcetopprod.connXhrMode = "queueRequests";
	    this.dataSourcetopprod.responseSchema = {
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

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;


	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSourcetopprod
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : top,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								   //  ,sortedBy : {
									// key: criteria,
									// dir: 'desc'
								    // }
								     ,dynamicData : true

								  }
								   
								 );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	   

	    this.table1.filter={key:'',value:''};

	});


