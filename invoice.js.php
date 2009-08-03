<?phpinclude_once('../common.php');?>

YAHOO.namespace ("invoice"); 


YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.invoice.XHR_JSON = new function() {


		
	    //START OF THE TABLE=========================================================================================================================
		
		var tableid=0; 
	    // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var InvoiceColumnDefs = [
				     {key:"code", label:"<?php echo_('Code')?>",width:75,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo_('Description')?>",width:380,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     // ,{key:"tariff_code", label:"<?php echo_('Tariff Code')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"quantity",label:"<?php echo_('Qty')?>", width:45,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"gross",label:"<?php echo_('Gross')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"discount",label:"<?php echo_('Discounts')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"to_charge",label:"<?php echo_('Charge')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];


	    this.InvoiceDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=transactions_invoice&tid=0");
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
