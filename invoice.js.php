var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;

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
				     {key:"code", label:"<?php echo _('Code')?>",width:75,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo _('Description')?>",width:420,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     // ,{key:"tariff_code", label:"<?php echo _('Tariff Code')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"quantity",label:"<?php echo _('Qty')?>", width:45,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"gross",label:"<?php echo _('Gross')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"discount",label:"<?php echo _('Discounts')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"to_charge",label:"<?php echo _('Charge')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
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


function pay_all(){
Dom.setStyle(['amount_paid_total',,'show_other_amount_field'],'display','')
Dom.setStyle(['amount_paid','pay_all'],'display','none')
Dom.get('amount_paid').value=Dom.get('invoce_full_amount').value;
}

function show_other_amount_field(){

Dom.setStyle(['amount_paid_total',,'show_other_amount_field'],'display','none')
Dom.setStyle(['amount_paid','pay_all'],'display','')
}

function select_type_of_payment(){

Dom.addClass(['pay_by_creditcard','pay_by_bank_transfer','pay_by_paypal','pay_by_cash','pay_by_cheque','pay_by_other'],'disabled')
Dom.removeClass(['pay_by_creditcard','pay_by_bank_transfer','pay_by_paypal','pay_by_cash','pay_by_cheque','pay_by_other'],'selected')

Dom.removeClass(this,'disabled')
Dom.addClass(this,'selected')
Dom.get('payment_method').value=this.id;
Dom.removeClass('save_paid','disabled')


}

function save_paid(){
if(Dom.get('amount_paid').value=='' || Dom.get('amount_paid').value<0){
	return;
}

var invoice_key=Dom.get('invoice_key').value;
var reference=Dom.get('payment_reference').value;
var pay_amount=Dom.get('amount_paid').value;
var method=Dom.get('payment_method').value;

Dom.get('charge_img').src='art/loading.gif';
pay_dialog.hide();
    var request='ar_edit_orders.php?tipo=pay_invoice&invoice_key='+invoice_key+"&reference="+reference+"&pay_amount="+pay_amount+"&method="+method;

    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		//		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		        location.href='invoice.php?id='+r.invoice_key;
		  
		}else{
		alert(r.msg)
		  
	    }
	    }
	});    


}
function hide_dialog_pay_invoice(){
pay_dialog.hide();

}
function show_dialog_pay_invoice(){
pay_dialog.show();

}

function init(){
 init_search('orders_store');
 YAHOO.util.Event.addListener(['pay_by_creditcard','pay_by_bank_transfer','pay_by_paypal','pay_by_cash','pay_by_cheque','pay_by_other'], "click", select_type_of_payment);

	 YAHOO.util.Event.addListener('save_paid', "click", save_paid);
	pay_dialog = new YAHOO.widget.Dialog("dialog_pay_invoice", {context:["charge","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
	pay_dialog.render();

	 YAHOO.util.Event.addListener('charge', "click", show_dialog_pay_invoice);

}

YAHOO.util.Event.onDOMReady(init);
