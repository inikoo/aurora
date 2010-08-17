<?php
include_once('common.php');?>

YAHOO.namespace ("invoice"); 


YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.invoice.XHR_JSON = new function() {


		
	    //START OF THE TABLE=========================================================================================================================
		
		var tableid=0; 
	    // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var InvoiceColumnDefs = [
				     {key:"sku", label:"<?php echo _('Part')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"used_in", label:"<?php echo _('Sold as')?>",width:120,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description",label:"<?php echo _('Description')?>", width:300,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"location",label:"<?php echo _('Location')?>", width:70,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"quantity",label:"<?php echo _('Qty')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];


	    this.InvoiceDataSource = new YAHOO.util.DataSource("ar_edit_orders.php?tipo=picking_aid_sheet&tid=0");
	    this.InvoiceDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.InvoiceDataSource.connXhrMode = "queueRequests";
	    this.InvoiceDataSource.responseSchema = {
		resultsList: "resultset.data", 
		fields: [
			 "sku"
			 ,"used_in"
			 ,"description"
			 ,"location"
			 ,"quantity"
			
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
