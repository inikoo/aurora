<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
if(!$user->can_view('orders'))
  exit();
?>

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
				       {key:"dispached",label:"<?php echo _('Dispached')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"paid", label:"<?php echo _('Paid')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},

					 ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=orders_per_store");
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
			 "paid","pending","dispached"
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
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

	    


	};
    });




function init(){
 init_search('orders');


    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
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

