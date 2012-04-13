
var Dom   = YAHOO.util.Dom;
var Event =YAHOO.util.Event;
YAHOO.namespace ("invoice"); 

function show_dispatched_post_transactions(){
Dom.setStyle('dispatched_post_transactions','display','');
Dom.setStyle('msg_dispatched_post_transactions','display','none');

}

YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.invoice.XHR_JSON = new function() {


		
	    //START OF THE TABLE=========================================================================================================================
		
		var tableid=0; 
	    // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var InvoiceColumnDefs = [
				     {key:"code", label:Dom.get('label_code').value,width:60,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:Dom.get('label_description').value,width:370,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"ordered",label:Dom.get('label_ordered').value, width:100,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"dispatched",label:Dom.get('label_dispatched').value, width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"invoiced",label:Dom.get('label_amount').value, width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];


	    this.InvoiceDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=transactions_dipatched&tid=0");
	    this.InvoiceDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.InvoiceDataSource.connXhrMode = "queueRequests";
	    this.InvoiceDataSource.responseSchema = {
		resultsList: "resultset.data", 
		fields: [
			 "code"
			 ,"description"
			 ,"ordered"
			 ,"invoiced","dispatched"
			 ]};
	    this.InvoiceDataTable = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
								   this.InvoiceDataSource, {
								       renderLoopSize: 50
								   }
								   
								   );
	


		    //START OF THE TABLE=========================================================================================================================
		
		var tableid=1; 
	    // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var InvoiceColumnDefs = [
				     {key:"code", label:Dom.get('label_code').value,width:60,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:Dom.get('label_description').value,width:370,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			//	     ,{key:"dn",label:Dom.get('label_dn').value, width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"dispatched",label:Dom.get('label_qty').value, width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"notes",label:Dom.get('label_notes').value, width:250,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];


	    this.InvoiceDataSource1 = new YAHOO.util.DataSource("ar_orders.php?tipo=post_transactions&tid=0&id="+Dom.get('order_key').value);
	    this.InvoiceDataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.InvoiceDataSource1.connXhrMode = "queueRequests";
	    this.InvoiceDataSource1.responseSchema = {
		resultsList: "resultset.data", 
		fields: [
			 "code"
			 ,"description"
			 ,"notes"
			 ,"dn","dispatched"
			 ]};
	    this.InvoiceDataTable1 = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
								   this.InvoiceDataSource1, {
								       renderLoopSize: 50
								   }
								   
								   );


    
    };
  });




function init(){
init_search('orders_store');
    var change_view = function (e) {
        window.location = "orders.php?view="+this.id;
    }
    var ids=['orders','invoices','dn'];
    Event.addListener(ids, "click", change_view);


}

YAHOO.util.Event.onDOMReady(init);
