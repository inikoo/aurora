<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
if(!$user->can_view('orders'))
  exit();
?>

var Dom   = YAHOO.util.Dom;
var Event =YAHOO.util.Event;

var view='<?php echo$_SESSION['state']['orders']['view']?>'
var dispatch='<?php echo$_SESSION['state']['orders']['table']['dispatch']?>'
var paid='<?php echo$_SESSION['state']['orders']['table']['paid']?>'
var order_type='<?php echo$_SESSION['state']['orders']['table']['order_type']?>'

Event.addListener(window, "load", function() {
    tables = new function() {
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"id", label:"<?php echo _('Order ID')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"last_date", label:"<?php echo _('Last Updated')?>", width:115,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"customer",label:"<?php echo _('Customer')?>", width:240,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"state", label:"<?php echo _('Status')?>", width:205,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"total_amount", label:"<?php echo _('Total')?>", width:110,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_orders.php?tipo=orders");
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
			 "id",
			 "state",
			 "customer",
			 "date",
			 "last_date",
			 "total_amount"
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['orders']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['orders']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['orders']['table']['f_value']?>'};

	    

	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"id", label:"<?php echo _('Public ID')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"date", label:"<?php echo _('Date')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"customer",label:"<?php echo _('Customer')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"orders",label:"<?php echo _('Order')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"dns",label:"<?php echo _('Delivery Note')?>", width:100,sortable:false,className:"aleft"}
				       
				       ,{key:"total_amount", label:"<?php echo _('Total')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"state", label:"<?php echo _('Status')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					 ];

	    this.dataSource1 = new YAHOO.util.DataSource("ar_orders.php?tipo=invoices&tableid=1");
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
		
		fields: [
			 "id",
			 "state",
			 "customer",
			 "date",
			 "date",
			 "total_amount","orders","dns"
			 ]};

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['orders']['invoices']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders']['invoices']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders']['invoices']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['orders']['invoices']['f_field']?>',value:'<?php echo$_SESSION['state']['orders']['invoices']['f_value']?>'};

 var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"id", label:"<?php echo _('Number')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"date", label:"<?php echo _('Date')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"type", label:"<?php echo _('Type')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"customer",label:"<?php echo _('Customer')?>", width:280,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"weight",label:"<?php echo _('Weight')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"parcels",label:"<?php echo _('Parcels')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       


					 ];

	    this.dataSource2 = new YAHOO.util.DataSource("ar_orders.php?tipo=dn&tableid=2");
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
		
		fields: [
			 "id",
			 "type",
			 "customer",
			 "date",
			 "orders","invoices","weight","parcels"
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource2, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['orders']['dn']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders']['dn']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders']['dn']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'<?php echo$_SESSION['state']['orders']['dn']['f_field']?>',value:'<?php echo$_SESSION['state']['orders']['dn']['f_value']?>'};

	};
    });


function change_dn_view(o){

if(o.getAttribute('state')=='dn_state'){

dn_state='dn_type';
Dom.get("dn_view_state_chooser").style.display='none';
Dom.get("dn_view_type_chooser").style.display='';
}else{
dn_state='dn_state';
Dom.get("dn_view_state_chooser").style.display='';
Dom.get("dn_view_type_chooser").style.display='none';
}




o.setAttribute('state',dn_state);

 
     var table=tables.table2;
     var datasource=tables.dataSource2;
     Dom.removeClass(Dom.getElementsByClassName('dn_view','span' , 'dn_table_type'),'selected');;
     Dom.addClass('restrictions_dn_all','selected');     
     var request='&dn_state_type=all';
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-delivery_notes-view&value=' + escape(dn_state) ,{success:function(o) {}});

}

function init(){
// -------------------------Export(CSV) code for order list under order --------------------
  YAHOO.util.Event.addListener('export_csv0', "click",download_csv,'orders');
 YAHOO.util.Event.addListener('export_csv0_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table0',tipo:'orders'});
  csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {trigger:"export_csv0" });
	 csvMenu.render();
	 csvMenu.subscribe("show", csvMenu.focus);
   
 YAHOO.util.Event.addListener('export_csv0_close_dialog', "click",csvMenu.hide,csvMenu,true);
// -------------------------Export(CSV) code for invoice list under order --------------

YAHOO.util.Event.addListener('export_csv1', "click",download_csv,'invoices');
 YAHOO.util.Event.addListener('export_csv1_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table1',tipo:'invoices'});
  csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu1", {trigger:"export_csv1" });
         csvMenu.render();
         csvMenu.subscribe("show", csvMenu.focus);
   
 YAHOO.util.Event.addListener('export_csv1_close_dialog', "click",csvMenu.hide,csvMenu,true);
// ------------------------Export(CSV) code for delivery notes list under order ----------------
YAHOO.util.Event.addListener('export_csv2', "click",download_csv,'dn');
 YAHOO.util.Event.addListener('export_csv2_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table2',tipo:'dn'});
  csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu2", {trigger:"export_csv2" });
         csvMenu.render();
         csvMenu.subscribe("show", csvMenu.focus);
   
 YAHOO.util.Event.addListener('export_csv2_close_dialog', "click",csvMenu.hide,csvMenu,true);
// ------------------------------------------------------------------------------------


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


 cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
 
 cal2.update=updateCal;
 cal2.id='2';
 cal2.render();
 cal2.update();
 cal2.selectEvent.subscribe(handleSelect, cal2, true); 
 cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
 cal1.update=updateCal;
 cal1.id='1';
 cal1.render();
 cal1.update();
 cal1.selectEvent.subscribe(handleSelect, cal1, true); 
cal2i = new YAHOO.widget.Calendar("cal2i","cal2iContainer", { title:"<?php echo _('Choose a date')?>:", close:true } );
 cal2i.update=updateCal;
 cal2i.id='2i';
 cal2i.render();
 cal2i.update();
 cal2i.selectEvent.subscribe(handleSelect, cal2i, true); 
 cal1i = new YAHOO.widget.Calendar("cal1i","cal1iContainer", { title:"<?php echo _('Choose a date')?>:", close:true } );
 cal1i.update=updateCal;
 cal1i.id='1i';
 cal1i.render();
 cal1i.update();
 cal1i.selectEvent.subscribe(handleSelect, cal1i, true);  

cal2dn = new YAHOO.widget.Calendar("cal2dn","cal2dnContainer", { title:"<?php echo _('Choose a date')?>:", close:true } );
 cal2dn.update=updateCal;
 cal2dn.id='2dn';
 cal2dn.render();
 cal2dn.update();
 cal2dn.selectEvent.subscribe(handleSelect, cal2dn, true); 
 cal1dn = new YAHOO.widget.Calendar("cal1dn","cal1dnContainer", { title:"<?php echo _('Choose a date')?>:", close:true } );
 cal1dn.update=updateCal;
 cal1dn.id='1dn';
 cal1dn.render();
 cal1dn.update();
 cal1dn.selectEvent.subscribe(handleSelect, cal1dn, true);  

 

 
 Event.addListener("calpop1", "click", cal1.show, cal1, true);
 Event.addListener("calpop2", "click", cal2.show, cal2, true);
 Event.addListener("calpop1i", "click", cal1i.show, cal1i, true);
 Event.addListener("calpop2i", "click", cal2i.show, cal2i, true);
 Event.addListener("calpop1dn", "click", cal1dn.show, cal1dn, true);
 Event.addListener("calpop2dn", "click", cal2dn.show, cal2dn, true);
 

var clear_interval = function(e,suffix){

     Dom.get("v_calpop1").value='';
     Dom.get("v_calpop2").value='';
     Dom.get("v_calpop1i").value='';
     Dom.get("v_calpop2i").value='';
     Dom.get("v_calpop1dn").value='';
     Dom.get("v_calpop2dn").value='';
          Dom.get('clear_interval').style.display='none';
          Dom.get('clear_intervali').style.display='none';
          Dom.get('clear_intervaldn').style.display='none';

     
  //   if(suffix==''){
   //  var table=tables.table0;
    // var datasource=tables.dataSource0;
    //  }else if(suffix=='i'){
   //  var table=tables.table1;
   //  var datasource=tables.dataSource1;
   //  }else if(suffix=='dn'){
   //   var table=tables.table2;
   //  var datasource=tables.dataSource2;
   //  }
    var table=tables.table0;
    var datasource=tables.dataSource0;
     var request='&sf=0&from=&to=';
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
  var table=tables.table1;
    var datasource=tables.dataSource1;
     var request='&sf=0&from=&to=';
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
     var table=tables.table2;
    var datasource=tables.dataSource2;
     var request='&sf=0&from=&to=';
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
  
  
 }

 var change_interval = function(e,suffix){
 

 
     from=Dom.get("v_calpop1"+suffix).value;
     to=Dom.get("v_calpop2"+suffix).value;




     if(from=='' && to=='')
	 Dom.get('clear_interval'+suffix).style.display='none';
     else
	 Dom.get('clear_interval'+suffix).style.display='';
 


 if(suffix==''){
 var table=tables.table0;
     var datasource=tables.dataSource0;
     Dom.get("v_calpop2i").value=to;
     Dom.get("v_calpop1i").value=from;
     Dom.get("v_calpop2dn").value=to;
     Dom.get("v_calpop1dn").value=from;
     
     }else if(suffix=='i'){
     var table=tables.table1;
     var datasource=tables.dataSource1;
       Dom.get("v_calpop2").value=to;
     Dom.get("v_calpop1").value=from;
     Dom.get("v_calpop2dn").value=to;
     Dom.get("v_calpop1dn").value=from;
     
     }else if(suffix=='dn'){
    
       Dom.get("v_calpop2i").value=to;
     Dom.get("v_calpop1i").value=from;
     Dom.get("v_calpop2").value=to;
     Dom.get("v_calpop1").value=from;
     
     }
            var table=tables.table0;
     var datasource=tables.dataSource0;
     var request='&sf=0&from=' +from+'&to='+to;
alert(request);
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table);  
       var table=tables.table1;
     var datasource=tables.dataSource1;
     var request='&sf=0&from=' +from+'&to='+to;
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table);  
 
        var table=tables.table2;
     var datasource=tables.dataSource2;
     var request='&sf=0&from=' +from+'&to='+to;
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table);  
    
     
 }
 
 var change_order_dispatch_type=function(e){
   
     var table=tables.table0;
     var datasource=tables.dataSource0;
     Dom.removeClass(Dom.getElementsByClassName('dispatch','span' , 'dispatch_chooser'),'selected');;
     Dom.addClass(this,'selected');     
     var request='&dispatch='+this.getAttribute('table_type');
	 //alert(request);
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
 }
 var change_invoice_type=function(e){
     var table=tables.table1;
     var datasource=tables.dataSource1;
     Dom.removeClass(Dom.getElementsByClassName('invoice_type','span' , 'invoice_chooser'),'selected');;
     Dom.addClass(this,'selected');     
     var request='&invoice_type='+this.getAttribute('table_type');
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
 }
 var change_dn_type=function(e){
     var new_dispatch=this.id;
     var table=tables.table2;
     var datasource=tables.dataSource2;
     Dom.removeClass(Dom.getElementsByClassName('dn_view','span' , 'dn_table_type'),'selected');;
     Dom.addClass(this,'selected');     
     var request='&dn_state_type='+this.getAttribute('table_type');
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
 } 
 

 Event.addListener("submit_interval", "click", change_interval,'');
 Event.addListener("clear_interval", "click", clear_interval,'');
 Event.addListener("submit_intervali", "click", change_interval,'i');
 Event.addListener("clear_intervali", "click", clear_interval,'i');
Event.addListener("submit_intervaldn", "click", change_interval,'dn');
 Event.addListener("clear_intervaldn", "click", clear_interval,'dn'); 

var ids =Array("restrictions_orders_cancelled","restrictions_orders_suspended","restrictions_orders_unknown","restrictions_orders_dispatched","restrictions_orders_in_process","restrictions_all_orders") ;
Event.addListener(ids, "click", change_order_dispatch_type);
var ids =Array("restrictions_paid","restrictions_to_pay","restrictions_refunds","restrictions_invoices","restrictions_all_invoices") ;
Event.addListener(ids, "click", change_invoice_type);
var ids =Array("restrictions_dn_all","restrictions_dn_returned","restrictions_dn_send","restrictions_dn_ready","restrictions_dn_packing","restrictions_dn_picking","restrictions_dn_ready_to_pick","restrictions_dn_shortages","restrictions_dn_replacements","restrictions_dn_donations","restrictions_dn_samples","restrictions_dn_orders") ;
Event.addListener(ids, "click", change_dn_type);


 var change_block_view = function (e){
	   
	   ids=['orders','invoices','dn'];
block_ids=['block_orders','block_invoices','block_dn'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=orders-view&value='+this.id ,{});
	   
	}   


	var ids=['orders','invoices','dn'];
	Event.addListener(ids, "click", change_block_view);
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



