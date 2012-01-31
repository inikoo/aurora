<?php
include_once('common.php');

?>
 var Dom   = YAHOO.util.Dom;




YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	
	    var tableid=2;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
				    {key:"sku", label:"<?php echo _('SKU')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"description", label:"<?php echo _('Description')?>",width:290,<?php echo($_SESSION['state']['warehouse']['parts']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"used_in", label:"<?php echo _('Used In')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"supplied_by", label:"<?php echo _('Supplied By')?>",width:200,<?php echo($_SESSION['state']['warehouse']['parts']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   
				   	,{key:"locations", label:"<?php echo _('Locations')?>", width:200,sortable:false,className:"aleft",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='locations' )  ?'':'hidden:true')?>}
				    ,{key:"stock", label:"<?php echo _('Stock')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' or $_SESSION['state']['warehouse']['parts']['view']=='locations' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //  ,{key:"available_for", label:"<?php echo _('S Until')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' or $_SESSION['state']['warehouse']['parts']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_value", label:"<?php echo _('Stk Value')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' or $_SESSION['state']['warehouse']['parts']['view']=='locations')?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"avg_stock", label:"<?php echo _('AS')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"avg_stockvalue", label:"<?php echo _('ASV')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"keep_days", label:"<?php echo _('KD')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"outstock_days", label:"<?php echo _('OofS')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"unknown_days", label:"<?php echo _('?S')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'   ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    ,{key:"sold", label:"<?php echo _('Sold(Given) Qty')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //   ,{key:"given", label:"<?php echo _('Given Qty')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sale'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}} 
				    ,{key:"money_in", label:"<?php echo _('Sold')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    //    ,{key:"profit", label:"<?php echo _('Profit Out')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sale'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit_sold", label:"<?php echo _('Profit (Inc Given)')?>", width:160,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"margin", label:"<?php echo _('Margin')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];

	    this.dataSource2 = new YAHOO.util.DataSource("ar_assets.php?tipo=parts&parent=warehouse&parent_key="+Dom.get('warehouse_id').value+"&tableid=2&where=");
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
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
			 "sku"
			 ,"description","locations"
			 ,"stock","available_for","stock_value","sold","given","money_in","profit","profit_sold","used_in","supplied_by","margin",'avg_stock','avg_stockvalue','keep_days','outstock_days','unknown_days','gmroi'
			 ]};
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource2, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['warehouse']['parts']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<span id='paginator_info2'>{CurrentPageReport}</span>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['warehouse']['parts']['order']?>",
									 dir: "<?php echo $_SESSION['state']['warehouse']['parts']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table2.view='<?php echo $_SESSION['state']['warehouse']['parts']['view']?>';
	    this.table2.filter={key:'<?php echo $_SESSION['state']['warehouse']['parts']['f_field']?>',value:'<?php echo $_SESSION['state']['warehouse']['parts']['f_value']?>'};
		



	};
    });


function change_block(){
ids=['stats','movements','parts']
block_ids=['block_stats','block_movements','block_parts']
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=warehouse-parts_view&value='+this.id ,{});
}




 function init(){
  init_search('parts');
  
  ids=['elements_Keeping','elements_NotKeeping','elements_Discontinued','elements_LastStock'];
  Event.addListener(ids, "click",change_parts_elements,2);
var ids=['parts_general','parts_stock','parts_sales','parts_forecast','parts_locations'];
YAHOO.util.Event.addListener(ids, "click",change_parts_view,2);
 ids=['parts_period_all','parts_period_three_year','parts_period_year','parts_period_yeartoday','parts_period_six_month','parts_period_quarter','parts_period_month','parts_period_ten_day','parts_period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_parts_period,2);
 ids=['parts_avg_totals','parts_avg_month','parts_avg_week',"parts_avg_month_eff","parts_avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_parts_avg,2);
 
  
  
Event.addListener(['stats','movements','parts'], "click",change_block);

 
  





Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);

 var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
  oACDS2.table_id=2;
 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);
 oAutoComp2.minQueryLength = 0; 



 }

YAHOO.util.Event.onDOMReady(init);


YAHOO.util.Event.onContentReady("rppmenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {trigger:"rtext_rpp2" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {trigger:"filter_name2"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
