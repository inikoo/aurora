<?php
include_once('common.php');?>
  var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;
var dialog_qty;
var category_labels={'stock':'<?php echo _('Stock Keeping Units')?>','value':'<?php echo _('Stock value')?>'};



YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {
		
		    var tableid=0;
		    var tableDivEL="table"+tableid;

  var ColumnDefs = [
				      {key:"date", label:"<?php echo _('Date')?>", width:200,sortable:false,className:"aright"}
				      ,{key:"locations", label:"<?php echo _('Locations')?>", width:100,sortable:false,className:"aleft"}
				      ,{key:"quantity", label:"<?php echo _('Qty')?>", width:100,sortable:false,className:"aleft"}
				      ,{key:"value", label:"<?php echo _('Value')?>", width:60,sortable:false,className:"aleft"}
				      
				      ,{key:"sold_qty", label:"<?php echo _('Sold')?>", width:60,sortable:false,className:"aright"}
				      ,{key:"in_qty", label:"<?php echo _('In')?>", width:60,sortable:false,className:"aright"}
				      ,{key:"lost_qty", label:"<?php echo _('Lost')?>", width:60,sortable:false,className:"aright"}

				      ];

		 
		    
		    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=part_stock_history&parent=part&parent_key="+Dom.get('part_sku').value+"&sf=0&tableid="+tableid);
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
				 "date","locations","quantity","value","sold_qty","in_qty","lost_qty"

				 ]};

	    
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource0, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({alwaysVisible:false,
									 rowsPerPage:<?php echo$_SESSION['state']['part']['stock_history']['nr']?>,containers : 'paginator0', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								   key: "<?php echo$_SESSION['state']['part']['stock_history']['order']?>",
								    dir: "<?php echo$_SESSION['state']['part']['stock_history']['order_dir']?>"
								  }
								 ,dynamicData : true
								 
							     }
							     );


		    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;






		    var tableid=1;
		    var tableDivEL="table"+tableid;

   var ColumnDefs = [
				      {key:"date", label:"<?php echo _('Date')?>", width:150,sortable:false,className:"aright"}
				      
				      ,{key:"type", label:"<?php echo _('Type')?>", width:50,sortable:false,className:"aleft"}
				       ,{key:"user", label:"<?php echo _('User')?>", width:50,sortable:false,className:"aleft"}
				     ,{key:"location", label:"<?php echo _('Location')?>", width:60,sortable:false,className:"aleft"}

				      ,{key:"note", label:"<?php echo _('Note')?>", width:300,sortable:false,className:"aleft"}
				      ,{key:"change", label:"<?php echo _('Change')?>", width:60,sortable:false,className:"aright"}

				      ];
		 
		    
//alert("ar_assets.php?tipo=part_transactions&parent=part&parent_key="+Dom.get('part_sku').value+"&sf=0&tableid="+tableid)
		    this.dataSource1 = new YAHOO.util.DataSource("ar_assets.php?tipo=part_transactions&parent=part&parent_key="+Dom.get('part_sku').value+"&sf=0&tableid="+tableid);
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
				 "date","change","type","location","note","user"

				 ]};
	    
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource1, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
								   
									 rowsPerPage:<?php echo$_SESSION['state']['part']['transactions']['nr']?>,containers : 'paginator1', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								   key: "<?php echo$_SESSION['state']['part']['transactions']['order']?>",
								    dir: "<?php echo$_SESSION['state']['part']['transactions']['order_dir']?>"
								  }
								 ,dynamicData : true
								 
							     }
							     );


		    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

   this.table1.filter={key:'<?php echo$_SESSION['state']['part']['transactions']['f_field']?>',value:'<?php echo$_SESSION['state']['part']['transactions']['f_value']?>'};


		   

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
									       rowsPerPage    : <?php echo$_SESSION['state']['part']['delivery_notes']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['part']['delivery_notes']['order']?>",
									 dir: "<?php echo$_SESSION['state']['part']['delivery_notes']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'<?php echo$_SESSION['state']['part']['delivery_notes']['f_field']?>',value:'<?php echo$_SESSION['state']['part']['delivery_notes']['f_value']?>'};
	    
	    };
    });




var change_snapshot_granularity=function(e){
     var table=tables.table0;
     var datasource=tables.dataSource0;
     Dom.removeClass(Dom.getElementsByClassName('table_type','span' , 'stock_history_type'),'selected');;
     Dom.addClass(this,'selected');     
     var request='&type='+this.getAttribute('table_type');
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
 }
 var change_transaction_type=function(e){
     var table=tables.table1;
     var datasource=tables.dataSource1;
     Dom.removeClass(Dom.getElementsByClassName('transaction_type','span' , 'transaction_chooser'),'selected');;
     Dom.addClass(this,'selected');     
     var request='&view='+this.getAttribute('table_type');
  
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
 }

function change_block(){
ids=['description','sales','transactions','history','purchase_orders', 'delivery_notes'];
block_ids=['block_description','block_sales','block_transactions','block_history','block_purchase_orders','block_delivery_notes'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=part-view&value='+this.id ,{});
}

function change_sales_period(){
  tipo=this.id;
 
  ids=['parts_period_all','parts_period_three_year','parts_period_year','parts_period_six_month','parts_period_quarter','parts_period_month','parts_period_ten_day','parts_period_week','parts_period_yeartoday','parts_period_monthtoday','parts_period_weektoday','parts_period_today'];

 Dom.removeClass(ids,"selected")
 Dom.addClass(this,"selected")
   period=this.getAttribute('period');
 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=warehouse-parts-period&value='+period ,{});

Dom.setStyle(['info_all','info_three_year','info_year','info_six_month','info_quarter','info_month','info_ten_day','info_week','info_yeartoday','info_monthtoday','info_weektoday','info_today'],'display','none')


Dom.setStyle(['info2_all','info2_three_year','info2_year','info2_six_month','info2_quarter','info2_month','info2_ten_day','info2_week','info2_yeartoday','info2_monthtoday','info2_weektoday','info2_today'],'display','none')
Dom.setStyle(['info_'+period,'info2_'+period],'display','')

}


function hide_stock_history_chart(){
Dom.setStyle(['stock_history_plot','hide_stock_history_chart'],'display','none')
Dom.setStyle('show_stock_history_chart','display','')
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=part-show_stock_history_chart&value=0',{});
}

function show_stock_history_chart(){
Dom.setStyle(['hide_stock_history_chart','stock_history_plot'],'display','')
Dom.setStyle(['show_stock_history_chart'],'display','none')
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=part-show_stock_history_chart&value=1' ,{});
}

function show_qty(e, location_key, min ,max){
	region1 = Dom.getRegion(e); 
	region2 = Dom.getRegion('dialog_qty'); 

	var pos =[region1.right,region1.top]

	Dom.setXY('dialog_qty', pos);
	
	Dom.get('min_qty').value=min;
	Dom.get('max_qty').value=max;
	Dom.get('part_location').value=location_key;
	dialog_qty.show();
}

function save_qty(){
//alert(sku);
//alert(Dom.get('part_location').value + ':'+Dom.get('part_sku').value);//return;

//ar_edit_warehouse.php?tipo=edit_part_location&key=min&newvalue=4&oldvalue=null&location_key=&part_sku=7
    var request='ar_edit_warehouse.php?tipo=update_max_min&newvalue_min='+Dom.get('min_qty').value+'&newvalue_max='+Dom.get('max_qty').value+'&location_key='+Dom.get('part_location').value+'&part_sku='+Dom.get('part_sku').value
   //alert(request);  
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
				//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		   dialog_qty.hide();
		   window.location.reload();

		}else{
		  alert(r.msg);
	    }
	    }
	});    





}

function init(){

init_search('parts');
Event.addListener(['description','sales','transactions','history','purchase_orders', 'delivery_notes'], "click",change_block);


var ids =Array("restrictions_all_transactions","restrictions_oip_transactions","restrictions_out_transactions","restrictions_in_transactions","restrictions_audit_transactions","restrictions_move_transactions") ;
Event.addListener(ids, "click", change_transaction_type);
var ids =Array("stock_history_type_month","stock_history_type_week","stock_history_type_day") ;
Event.addListener(ids, "click", change_snapshot_granularity);
    
 YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
  YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);

 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.table_id=1;
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS);
 oAutoComp.minQueryLength = 0; 



 ids=['parts_period_all','parts_period_three_year','parts_period_year','parts_period_yeartoday','parts_period_six_month','parts_period_quarter','parts_period_month','parts_period_ten_day','parts_period_week','parts_period_monthtoday','parts_period_weektoday','parts_period_today'];
 YAHOO.util.Event.addListener(ids, "click",change_sales_period);


   YAHOO.util.Event.addListener('hide_stock_history_chart', "click",hide_stock_history_chart);
   YAHOO.util.Event.addListener('show_stock_history_chart', "click",show_stock_history_chart);


dialog_qty = new YAHOO.widget.Dialog("dialog_qty", {visible : false,close:true,underlay: "none",draggable:false});
dialog_qty.render();

Event.addListener('close_qty', "click", dialog_qty.hide,dialog_qty , true);

}
 YAHOO.util.Event.onDOMReady(init);
 

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });  

 YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });  
