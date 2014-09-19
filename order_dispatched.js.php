<?php
include_once('common.php');?>
var Dom   = YAHOO.util.Dom;
var Event =YAHOO.util.Event;
YAHOO.namespace ("invoice"); 

function show_dispatched_post_transactions(){
Dom.setStyle('dispatched_post_transactions','display','');
Dom.setStyle('msg_dispatched_post_transactions','display','none');

}

function create_invoice(){

Dom.get('create_invoice_img').src='art/loading.gif'
var order_key=Dom.get('order_key').value;


    var request='ar_edit_orders.php?tipo=create_invoice_order&order_key='+escape(order_key);
//  alert(request); //return;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
			//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		        //location.href='invoice.php?id='+r.invoice_key;
		        location.reload(); 
		  
		}else{
		alert(r.msg)
		  
	    }
	    }
	});    

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

function show_order_details(){
Dom.setStyle('order_details_panel','display','')
Dom.setStyle('show_order_details','display','none')

}

function hide_order_details(){
Dom.setStyle('order_details_panel','display','none')
Dom.setStyle('show_order_details','display','')
}


function init() {
    init_search('orders_store');
   	get_dn_invoices_info()
    Event.addListener("create_invoice", "click", create_invoice);
 YAHOO.util.Event.addListener('hide_order_details', "click", hide_order_details)
 YAHOO.util.Event.addListener('show_order_details', "click", show_order_details)



}

YAHOO.util.Event.onDOMReady(init);

