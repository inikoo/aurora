<?php
include_once('common.php');
$static_list_id=$_REQUEST['id'];
?>
  var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;
var view='<?php echo$_SESSION['state']['hr']['view']?>'
var dialog_export;
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	     //START OF THE TABLE =========================================================================================================================

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	
		    var CustomersColumnDefs = [ 
				       {key:"id", label:"<?php echo _('Order ID')?>",width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
					   {key:"last_date", label:"<?php echo _('Last Updated')?>", <?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:115,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"customer",label:"<?php echo _('Customer')?>", <?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:240,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"state", label:"<?php echo _('Status')?>", <?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:205,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"total_amount", label:"<?php echo _('Total')?>", <?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:110,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"name", label:"<?php echo _('Customer Name')?>", width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?> width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //	,{key:"contact_since", label:"<?php echo _('Since')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:85,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"last_order", label:"<?php echo _('Last Order')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:85,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"orders", label:"<?php echo _('Orders')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"activity", label:"<?php echo _('Status')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"contact_name", label:"<?php echo _('Contact Name')?>",width:160,<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"email", label:"<?php echo _('Email')?>",width:210,<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"telephone", label:"<?php echo _('Telephone')?>", width:137,<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},className:"aright"}
				       //,{key:"address", label:"<?php echo _('Contact Address')?>", width:176,<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       //,{key:"billing_address", label:"<?php echo _('Billing Address')?>", width:170,<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				      // ,{key:"delivery_address", label:"<?php echo _('Delivery Address')?>", width:170,<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				      // ,{key:"total_payments", label:"<?php echo _('Payments')?>",width:99,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       //,{key:"total_refunds", label:"<?php echo _('Refunds')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"net_balance", label:"<?php echo _('Balance')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"balance", label:"<?php echo _('Outstanding')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"total_profit", label:"<?php echo _('Profit')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"top_orders", label:"<?php echo _('Rank Orders')?>",width:121,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"top_invoices", label:"<?php echo _('Rank Invoices')?>",width:121,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"top_balance", label:"<?php echo _('Rank Balance')?>",width:120,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"top_profits", label:"<?php echo _('Rank Profits')?>",width:120,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ];
	store_id=Dom.get('store_id').value;

	//alert("ar_contacts.php?tipo=customers&type=list&store_id="+store_id+"&where=&list_key=<?php echo $static_list_id;?>")
	    // alert("ar_contacts.php?tipo=customers&type=list&store_id="+store_id+"&where=&list_key=<?php echo $static_list_id;?>")
	    this.dataSource0 = new YAHOO.util.DataSource("ar_orders.php?tipo=orders&type=list&store_id="+Dom.get('store_id').value+"&where=&list_key=<?php echo $static_list_id;?>");
		alert("ar_orders.php?tipo=orders&type=list&store_id="+Dom.get('store_id').value+"&where=&list_key=<?php echo $static_list_id;?>");
	 //alert("ar_orders.php?tipo=orders&type=list&store_id="+Dom.get('store_id').value+"&where=&list_key=<?php echo $static_list_id;?>")
	  
	  this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		
		fields: [
			 'id',
			 'last_date',
			 'customer',
			 'total_amount',
			 "state"
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['table']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.view='<?php echo$_SESSION['state']['customers']['view']?>';

	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['table']['f_value']?>'};

	
	};
    });

function close_dialog(tipo){
    switch(tipo){
case('export'):
 dialog_export.hide();
 break;
    }
};


 function init(){
 
 
  init_search('customers_store');


  YAHOO.util.Event.addListener('export_csv0', "click",download_csv,'customers');
 YAHOO.util.Event.addListener('export_csv0_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table0',tipo:'customers'});
  csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {trigger:"export_csv0" });
	 csvMenu.render();
	 csvMenu.subscribe("show", csvMenu.focus);
   
 YAHOO.util.Event.addListener('export_csv0_close_dialog', "click",csvMenu.hide,csvMenu,true);
dialog_export = new YAHOO.widget.Dialog("dialog_export", {context:["export_data","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_export.render();
Event.addListener("export_data", "click", dialog_export.show,dialog_export , true);
 
 
 


 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 



 }

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });



