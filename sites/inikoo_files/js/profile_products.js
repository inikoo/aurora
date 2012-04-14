var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var LocationsColumnDefs = [
			      {key:"subject", label:Dom.get('label_subject').value,className:"aleft",width:60,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   				      ,{key:"description", label:Dom.get('label_description').value,className:"aleft",width:270,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				   ,{key:"orders", label:Dom.get('label_orders').value, formatter: "number",className:"aright",width:60,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"dispatched", label:Dom.get('label_dispatched').value, formatter: "number", className:"aright",width:60,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ];
	
	    
	    
	//   alert("ar_orders.php?tipo=assets_dispatched_to_customer&customer_key="+Dom.get('customer_key').value) 
	    this.dataSource0 = new YAHOO.util.DataSource("ar_orders.php?tipo=assets_dispatched_to_customer&customer_key="+Dom.get('customer_key').value);
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
		fields: ["subject","ordered","dispatched","orders","description" ]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, LocationsColumnDefs,
								   this.dataSource0
								 , {   renderLoopSize: 50}
								 );
	   // this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    //this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    
	    //this.table0.table_id=tableid;        
        //this.table0.subscribe("renderEvent", myrenderEvent);
	    
	    //this.table0.filter={key:'<?php echo$_SESSION['state']['locations']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['locations']['table']['f_value']?>'};
   }  
});


function init(){


}
Event.onDOMReady(init);