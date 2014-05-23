

var customer_key=<?php echo $_REQUEST['customer_key']  ?>;
var customer_type="<?php echo $_REQUEST['customer_type']  ?>";

Event.addListener(window, "load", function() {
	tables = new function() {

			var tableid=2; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;  
		   
		    var ColumnDefs =  [
				       {key:"public_id", label:"<?php echo _('Order ID')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"last_update", label:"<?php echo _('Last Updated')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"current_state",label:"<?php echo _('Current State')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
                                        {key:"order_date", label:"<?php echo _('Order Date')?>", width:120,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				      {key:"total_amount", label:"<?php echo _('Total')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 
					
					 ];
		
		    this.dataSource2  = new YAHOO.util.DataSource("ar_contacts.php?tipo=site_user_view_orders&customer_key="+customer_key+"&sf=0&tid="+tableid);
			//alert("ar_contacts.php?tipo=site_user_view_orders&customer_key="+customer_key+"&sf=0&tid="+tableid);
		    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.table_id=tableid;
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
				totalRecords: "resultset.total_records" // Access to value in the server response
			},
		
			fields: ["public_id","last_update","current_state","order_date","total_amount"]};
			  
		    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : 25,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })

								     ,sortedBy : {
									 key: "last_update<?php //echo $_SESSION['state']['customer']['orders']['order']?>",
									 dir: "desc<?php //echo $_SESSION['state']['customer']['orders']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
				this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
				this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
				this.table2.filter={key:'public_id<?php //echo$_SESSION['state']['customer']['orders']['f_field']?>',value:'<?php //echo$_SESSION['state']['customer']['orders']['f_value']?>'};
		};
	}
);	

function init(){
	
}

YAHOO.util.Event.onDOMReady(init);		
