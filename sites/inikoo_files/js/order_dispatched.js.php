
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
		
		var tableid=1; 
	    // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var InvoiceColumnDefs = [
				     {key:"code", label:"<?php echo _('Code')?>",width:60,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo _('Description')?>",width:370,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"ordered",label:"<?php echo _('Ordered')?>", width:100,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"dispatched",label:"<?php echo _('Dispatched')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"invoiced",label:"<?php echo _('Amount')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];

		//alert("ar_orders.php?tipo=transactions_dipatched&tid=1&id="+Dom.get('order_key').value);
	    this.InvoiceDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=transactions_dipatched&tid=1&id="+Dom.get('order_key').value);
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
