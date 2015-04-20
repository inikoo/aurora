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
// alert(request); return;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
		//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		        //location.href='invoice.php?id='+r.invoice_key;
		       
		       post_create_invoice_actions(r.invoice_key);
		       
		       //location.reload(); 
		  
		}else{
		alert(r.msg)
		  
	    }
	    }
	});    

}

function post_create_invoice_actions(invoice_key){

    //var request='ar_edit_orders.php?tipo=categorize_invoice&invoice_key='+escape(invoice_key);
    //YAHOO.util.Connect.asyncRequest('POST',request ,{});    
    location.reload(); 
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

request="ar_orders.php?tipo=transactions_dispatched&tableid=0&sf=0&parent=order&parent_key="+Dom.get('order_key').value

	    this.dataSource0 = new YAHOO.util.DataSource(request);
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		       rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		fields: [
			 "code"
			 ,"description"
			 ,"ordered"
			 ,"invoiced","dispatched","tariff_code"
			 ]};
			 
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['order']['items']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['order']['items']['order']?>",
									 dir: "<?php echo$_SESSION['state']['order']['items']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table0.doBeforeLoadData=mydoBeforeLoadData;
       
       
       this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);
	    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['order']['items']['f_field']?>',value:'<?php echo$_SESSION['state']['order']['items']['f_value']?>'};



		
		var tableid=1; 
	    var tableDivEL="table"+tableid;



	    var InvoiceColumnDefs = [
				     {key:"code", label:"<?php echo _('Code')?>",width:60,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo _('Description')?>",width:275,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 //    ,{key:"tariff_code",label:"<?php echo _('Tarrif Code')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"dispatched",label:"<?php echo _('Original Qty')?>", width:65,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 				       ,{key:"quantity",label:"<?php echo _('Qty')?>", width:40,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 				       ,{key:"operation",label:"<?php echo _('Operation')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 				       ,{key:"reason",label:"<?php echo _('Reason')?>", width:90,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				 ,{key:"notes",label:"<?php echo _('Notes')?>", width:140,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];

		request="ar_orders.php?tipo=post_transactions&sf=0&tableid="+tableid+"&parent=order&parent_key="+Dom.get('order_key').value;
	//alert(request)
	    this.dataSource1 = new YAHOO.util.DataSource(request);
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    
	 
	    
	    
	    this.dataSource1.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		       rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		fields: [
			 "code"
			 ,"description",'quantity','operation','tariff_code'
			 ,"notes"
			 ,"dn","dispatched",'reason'
			 ]};
			 
			 
	    this.InvoiceDataTable1 = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
								   this.dataSource1, {
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

