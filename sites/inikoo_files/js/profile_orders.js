var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var LocationsColumnDefs = [
				       {key:"id", label:Dom.get("label_id").value, width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"date", label:Dom.get("label_date").value,width:150,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"state", label:Dom.get("label_state").value, width:380,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}				       
				       ,{key:"total", label:Dom.get("label_total").value,width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ];
	    
	    var request="ar_orders.php?tipo=list_orders&sf=0&nr=50&order=date"
	   
	    this.dataSource0 = new YAHOO.util.DataSource(request);
	    //alert("ar_orders.php?tipo=list_orders&customer_key="+Dom.get('customer_key').value);
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
			 ,"state"
			 ,'date'
			 ,'total'

			 ]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, LocationsColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : Dom.get('rrp').value,containers : 'paginator0', 
 									      pageReportTemplate : '(Page {currentPage} of {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: Dom.get('_order').value,
									 dir: Dom.get('_order_dir').value
								     },
								     dynamicData : true
								  }
								 );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    
	    this.table0.table_id=tableid;        
        this.table0.subscribe("renderEvent", myrenderEvent);
	    
	    //this.table0.filter={key:'<?php echo$_SESSION['state']['locations']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['locations']['table']['f_value']?>'};
   }  
});


function init(){


}
Event.onDOMReady(init);