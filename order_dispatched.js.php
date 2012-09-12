<?php
include_once('common.php');?>
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
				     {key:"code", label:"<?php echo _('Code')?>",width:60,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo _('Description')?>",width:370,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"ordered",label:"<?php echo _('Ordered')?>", width:100,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"dispatched",label:"<?php echo _('Dispatched')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"invoiced",label:"<?php echo _('Amount')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
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
				     {key:"code", label:"<?php echo _('Code')?>",width:60,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo _('Description')?>",width:200,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			//	     ,{key:"dn",label:"<?php echo _('DN')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"dispatched",label:"<?php echo _('Dispatched')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 				       ,{key:"quantity",label:"<?php echo _('Qty')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 				       ,{key:"operation",label:"<?php echo _('Operation')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 				       ,{key:"reason",label:"<?php echo _('Reason')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				 ,{key:"notes",label:"<?php echo _('Notes')?>", width:200,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];


	    this.InvoiceDataSource1 = new YAHOO.util.DataSource("ar_orders.php?tipo=post_transactions&tableid=1&order_key="+Dom.get('order_key').value);
//alert("ar_orders.php?tipo=post_transactions&tableid=1&order_key="+Dom.get('order_key').value)
	    this.InvoiceDataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.InvoiceDataSource1.connXhrMode = "queueRequests";
	    this.InvoiceDataSource1.responseSchema = {
		resultsList: "resultset.data", 
		fields: [
			 "code"
			 ,"description",'quantity','operation'
			 ,"notes"
			 ,"dn","dispatched",'reason'
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
