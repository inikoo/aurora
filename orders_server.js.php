<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
if(!$user->can_view('orders'))
  exit();
?>
var view='<?php echo$_SESSION['state']['stores']['orders_view']?>'


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"code", label:"<?php echo _('Code')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"name", label:"<?php echo _('Store Name')?>", width:110,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"orders",label:"<?php echo _('Orders')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"cancelled",label:"<?php echo _('Cancelled')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"unknown",label:"<?php echo _('Unknown')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"pending", label:"<?php echo _('Pending')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"dispatched",label:"<?php echo _('Dispatched')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},

					 ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=orders_per_store&tableid="+tableid);
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
			 "code",
			 "name",
			 "orders",
			 "cancelled",
			 "unknown",
			 "paid","pending","dispatched"
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
								       alwaysVisible: false,
									       rowsPerPage    : <?php echo $_SESSION['state']['stores']['orders']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['stores']['orders']['order']?>",
									 dir: "<?php echo$_SESSION['state']['stores']['orders']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['stores']['orders']['f_field']?>',value:'<?php echo$_SESSION['state']['stores']['orders']['f_value']?>'};

		    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"code", label:"<?php echo _('Code')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"name", label:"<?php echo _('Store Name')?>", width:110,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"invoices",label:"<?php echo _('Invoices')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       	{key:"invoices_paid",label:"<?php echo _('To be Paid')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"invoices_to_be_paid",label:"<?php echo _('Paid')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"refunds",label:"<?php echo _('Refunds')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"refunds_paid",label:"<?php echo _('To be Paid')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"refunds_to_be_paid",label:"<?php echo _('Paid')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					 ];

	    this.dataSource1 = new YAHOO.util.DataSource("ar_assets.php?tipo=invoices_per_store&tableid="+tableid);
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
			 "code",
			 "name",
			 "invoices","refunds","invoices_paid","invoices_to_be_paid","refunds_paid","refunds_to_be_paid"
			 ]};

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
								       alwaysVisible: false,
									       rowsPerPage    : <?php echo $_SESSION['state']['stores']['invoices']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['stores']['invoices']['order']?>",
									 dir: "<?php echo$_SESSION['state']['stores']['invoices']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['stores']['invoices']['f_field']?>',value:'<?php echo$_SESSION['state']['stores']['invoices']['f_value']?>'};    

		var tableid=2; 
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"code", label:"<?php echo _('Code')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"name", label:"<?php echo _('Store Name')?>", width:110,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"dn",label:"<?php echo _('Total')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       	{key:"dn_ready_to_pick",label:"<?php echo _('To Pick')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"dn_picking",label:"<?php echo _('Picking')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"dn_packing",label:"<?php echo _('Packing')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"dn_ready",label:"<?php echo _('Ready')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"dn_send",label:"<?php echo _('Send')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"dn_returned",label:"<?php echo _('Return')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"dn_order",label:"<?php echo _('Orders')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"dn_samples",label:"<?php echo _('Samples')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"dn_donations",label:"<?php echo _('Donations')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"dn_replacements",label:"<?php echo _('Replacements')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"dn_shortages",label:"<?php echo _('Shortages')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					 ];

	    this.dataSource2 = new YAHOO.util.DataSource("ar_assets.php?tipo=delivery_notes_per_store&tableid="+tableid);
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
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
			 "code",
			 "name",
			 "dn","dn_ready_to_pick","dn_picking","dn_packing","dn_ready","dn_send","dn_returned"
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource2, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
								       alwaysVisible: false,
									       rowsPerPage    : <?php echo $_SESSION['state']['stores']['delivery_notes']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['stores']['delivery_notes']['order']?>",
									 dir: "<?php echo$_SESSION['state']['stores']['delivery_notes']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'<?php echo$_SESSION['state']['stores']['delivery_notes']['f_field']?>',value:'<?php echo$_SESSION['state']['stores']['delivery_notes']['f_value']?>'};    


	};
    });


 var change_view = function (e){
	    new_view=this.id
	    if(new_view!=view){
		YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-orders_view&value='+escape(new_view),{});
		this.className='selected';
		Dom.get(view).className='';
		Dom.get(view+'_table').style.display='none';
		Dom.get(new_view+'_table').style.display='';
		view=new_view;
	    }
	 }



function init(){
 init_search('orders');

var ids=['orders','invoices','dn'];
	YAHOO.util.Event.addListener(ids, "click", change_view);
	
    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS);
    oAutoComp.minQueryLength = 0; 
   
    
    
}

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });
YAHOO.util.Event.onContentReady("rppmenu0", function () {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("rtext_rpp0", "click",oMenu.show , null, oMenu);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });
YAHOO.util.Event.onContentReady("rppmenu1", function () {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("rtext_rpp1", "click",oMenu.show , null, oMenu);
    });
    
 YAHOO.util.Event.onContentReady("filtermenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {  trigger: "filter_name2"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });
YAHOO.util.Event.onContentReady("rppmenu2", function () {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {trigger:"rtext_rpp2" });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("rtext_rpp2", "click",oMenu.show , null, oMenu);
    });   
    