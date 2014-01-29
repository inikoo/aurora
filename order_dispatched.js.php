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

		var tableid=0; 
	    var tableDivEL="table"+tableid;



	    var InvoiceColumnDefs = [
				     {key:"code", label:"<?php echo _('Code')?>",width:60,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo _('Description')?>",width:370,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    		//		     ,{key:"tariff_code",label:"<?php echo _('Tarrif Code')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

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
			 ,"invoiced","dispatched","tariff_code"
			 ]};
	    this.InvoiceDataTable = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
								   this.InvoiceDataSource, {
								       renderLoopSize: 50
								   }
								   
								   );
	


		
		var tableid=1; 
	    var tableDivEL="table"+tableid;



	    var InvoiceColumnDefs = [
				     {key:"code", label:"<?php echo _('Code')?>",width:60,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo _('Description')?>",width:275,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 //    ,{key:"tariff_code",label:"<?php echo _('Tarrif Code')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"dispatched",label:"<?php echo _('Dispatched')?>", width:65,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 				       ,{key:"quantity",label:"<?php echo _('Qty')?>", width:40,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 				       ,{key:"operation",label:"<?php echo _('Operation')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 				       ,{key:"reason",label:"<?php echo _('Reason')?>", width:90,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				 ,{key:"notes",label:"<?php echo _('Notes')?>", width:140,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];

		request="ar_orders.php?tipo=post_transactions&tableid=1&order_key="+Dom.get('order_key').value;
		//alert(request)
	    this.InvoiceDataSource1 = new YAHOO.util.DataSource(request);
	    this.InvoiceDataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.InvoiceDataSource1.connXhrMode = "queueRequests";
	    this.InvoiceDataSource1.responseSchema = {
		resultsList: "resultset.data", 
		fields: [
			 "code"
			 ,"description",'quantity','operation','tariff_code'
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


function get_dn_invoices_info(){

}

function init() {
    init_search('orders_store');
   	get_dn_invoices_info()


}

YAHOO.util.Event.onDOMReady(init);

