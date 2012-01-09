var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;




YAHOO.namespace ("invoice"); 


YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.invoice.XHR_JSON = new function() {


		
	    //START OF THE TABLE=========================================================================================================================
		
		var tableid=0; 
	    // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var InvoiceColumnDefs = [
				     {key:"code", label:"Code",width:75,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"Description",width:420,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     // ,{key:"tariff_code", label:"<?php echo _('Tariff Code')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"quantity",label:"Qty", width:45,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"gross",label:"Gross", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"discount",label:"Discounts", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"to_charge",label:"Charge", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];

		//alert("ar_orders.php?tipo=transactions_invoice&tid=0&id="+Dom.get('invoice_key').value);
	    this.InvoiceDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=transactions_invoice&tid=0&id="+Dom.get('invoice_key').value);
	    this.InvoiceDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.InvoiceDataSource.connXhrMode = "queueRequests";
	    this.InvoiceDataSource.responseSchema = {
		resultsList: "resultset.data", 
		fields: [
			 "code"
			 ,"description"
			 ,"quantity"
			 ,"discount"
			 ,"to_charge","gross","tariff_code"
			 // "promotion_id",
			 ]};
	    this.InvoiceDataTable = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
								   this.InvoiceDataSource, {
								       renderLoopSize: 50
								   }
								   
								   );
	


	


    
    };
  });




function init(){


}

YAHOO.util.Event.onDOMReady(init);
