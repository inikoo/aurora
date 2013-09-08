<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
if(!$user->can_view('orders'))
  exit();
?>

var Dom   = YAHOO.util.Dom;
var Event =YAHOO.util.Event;



Event.addListener(window, "load", function() {
    tables = new function() {
    
    
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				    	       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
                                ,{key:"name", label:"<?php echo _('List Name')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                ,{key:"creation_date", label:"<?php echo _('List Created')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"list_type", label:"<?php echo _('List Type')?>",  width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 	 ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'order_list'}
					 ];

request="ar_orders.php?tipo=orders_lists&store="+Dom.get('store_id').value+'&block_view=orders&sf=0'
	    this.dataSource0 = new YAHOO.util.DataSource(request);
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
		
		fields: ["name","key","creation_date","items","list_type","delete"]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['orders_lists']['orders']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders_lists']['orders']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders_lists']['orders']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
		this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
		this.table0.subscribe("cellClickEvent", onCellClick);   
	    this.table0.filter={key:'<?php echo$_SESSION['state']['orders_lists']['orders']['f_field']?>',value:'<?php echo$_SESSION['state']['orders_lists']['orders']['f_value']?>'};

	    

	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				     {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
                                ,{key:"name", label:"<?php echo _('List Name')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                ,{key:"creation_date", label:"<?php echo _('List Created')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"list_type", label:"<?php echo _('List Type')?>",  width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 	 ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'invoice_list'}
					 ];

	    	    this.dataSource1 = new YAHOO.util.DataSource("ar_orders.php?tipo=invoices_lists&store="+Dom.get('store_id').value+'&block_view=invoices&tableid=1');

	     this.dataSource1.table_id=tableid;
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
		
			fields: ["name","key","creation_date","items","list_type","delete"]};


	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['orders_lists']['invoices']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders_lists']['invoices']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders_lists']['invoices']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table1.subscribe("cellMouseoverEvent", highlightEditableCell);
		this.table1.subscribe("cellMouseoutEvent", unhighlightEditableCell);
		this.table1.subscribe("cellClickEvent", onCellClick);  		
	    this.table1.filter={key:'<?php echo$_SESSION['state']['orders_lists']['invoices']['f_field']?>',value:'<?php echo$_SESSION['state']['orders_lists']['invoices']['f_value']?>'};

 var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
			  {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
                                ,{key:"name", label:"<?php echo _('List Name')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                ,{key:"creation_date", label:"<?php echo _('List Created')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"list_type", label:"<?php echo _('List Type')?>",  width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 	 ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'dn_list'}


					 ];

	    	    	    this.dataSource2 = new YAHOO.util.DataSource("ar_orders.php?tipo=dn_lists&store="+Dom.get('store_id').value+'&block_view=dn&tableid=2');

	    this.dataSource2.table_id=tableid;
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
		
			fields: ["name","key","creation_date","items","list_type","delete"]};


	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource2, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['orders_lists']['dn']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders_lists']['dn']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders_lists']['dn']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table2.subscribe("cellMouseoverEvent", highlightEditableCell);
		this.table2.subscribe("cellMouseoutEvent", unhighlightEditableCell);
		this.table2.subscribe("cellClickEvent", onCellClick);  
	    this.table2.filter={key:'<?php echo$_SESSION['state']['orders_lists']['dn']['f_field']?>',value:'<?php echo$_SESSION['state']['orders_lists']['dn']['f_value']?>'};

	};
    });




function init(){





init_search('orders_store');

 
    Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);





 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
  oACDS.table_id=0;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 
 
 var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
  oACDS1.table_id=1;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 

 var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
  oACDS2.table_id=2;
 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);
 oAutoComp2.minQueryLength = 0; 



 var change_block_view = function (e){
	   
	   ids=['orders','invoices','dn'];
block_ids=['block_orders','block_invoices','block_dn'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
Dom.get('block_view').value=this.id;

switch (this.id ) {
	case 'invoices':
		Dom.get('new_list_button').innerHTML='<?php echo _('New List').' ('._('Invoices').')'?>';
		break;
	
	case 'dn':
		Dom.get('new_list_button').innerHTML='<?php echo _('New List').' ('._('Delivery Notes').')'?>';
		break;
	default:
		Dom.get('new_list_button').innerHTML='<?php echo _('New List').' ('._('Orders').')'?>';
}


YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=orders_lists-view&value='+this.id ,{});
	   
	}   


	var ids=['orders','invoices','dn'];
	Event.addListener(ids, "click", change_block_view);
	
	
	var new_list = function (e){
	   
switch ( Dom.get('block_view').value ) {
	case 'invoices':
		location.href='new_invoices_list.php?store='+Dom.get('store_id').value
		break;
	
	case 'dn':
	location.href='new_delivery_notes_list.php?store='+Dom.get('store_id').value
	break;
	default:
	location.href='new_orders_list.php?store='+Dom.get('store_id').value
}
	   

	   
	}   
		Event.addListener('new_list_button', "click", new_list);

}

Event.onDOMReady(init);

Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });



Event.onContentReady("rppmenu0", function () {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	Event.addListener("rtext_rpp0", "click",oMenu.show , null, oMenu);
});

Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 Event.addListener("filter_name1", "click", oMenu.show, null, oMenu);
    });



Event.onContentReady("rppmenu1", function () {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	Event.addListener("rtext_rpp1", "click",oMenu.show , null, oMenu);

    });

Event.onContentReady("filtermenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {  trigger: "filter_name2"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 Event.addListener("filter_name2", "click", oMenu.show, null, oMenu);
    });



Event.onContentReady("rppmenu2", function () {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {trigger:"rtext_rpp2" });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	Event.addListener("rtext_rpp2", "click",oMenu.show , null, oMenu);

    });



