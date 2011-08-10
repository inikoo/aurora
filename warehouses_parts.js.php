<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;
 
    var period='period_<?php echo$_SESSION['state']['parts']['period']?>';
    var avg='avg_<?php echo$_SESSION['state']['parts']['avg']?>';

    var change_view=function(e){
	tipo=this.id;
	var table=tables['table0'];
	
	table.hideColumn('description');
	table.hideColumn('stock');
	table.hideColumn('stock_value');
	table.hideColumn('sold');
	//	table.hideColumn('given');
	table.hideColumn('money_in');
	//table.hideColumn('profit');
	table.hideColumn('profit_sold');
	table.hideColumn('available_for');
	table.hideColumn('margin');
	table.hideColumn('days_to_ns');
	table.hideColumn('supplied_by"');
	table.hideColumn('avg_stock');
	table.hideColumn('avg_stockvalue');
	table.hideColumn('keep_days');
	table.hideColumn('outstock_days');
	table.hideColumn('unknown_days');
	table.hideColumn('gmroi');


	if(tipo=='sales'){
	    table.showColumn('sold');
	    //table.showColumn('given');
	    table.showColumn('money_in');
	    //	    table.showColumn('profit');
	    table.showColumn('profit_sold');
	    table.showColumn('margin');
	Dom.get('period_options').style.display='';
		Dom.get('avg_options').style.display='';
	}else if(tipo=='general'){
	    table.showColumn('description');
	    table.showColumn('stock');
	    table.showColumn('stock_value');
	    	Dom.get('period_options').style.display='none';
		Dom.get('avg_options').style.display='none';

	}else if(tipo=='stock'){
	Dom.get('period_options').style.display='';
		Dom.get('avg_options').style.display='none';
	    table.showColumn('avg_stock');
	    table.showColumn('avg_stockvalue');
	    table.showColumn('keep_days');
	    table.showColumn('outstock_days');
	    table.showColumn('unknown_days');
	    table.showColumn('gmroi');

	}



	Dom.get(table.view).className="";
	Dom.get(tipo).className="selected";	
	table.view=tipo;
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=parts-view&value='+escape(tipo));
    }



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
				    {key:"sku", label:"<?php echo _('SKU')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    				    ,{key:"description", label:"<?php echo _('Description')?>",width:310,<?php echo($_SESSION['state']['parts']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"used_in", label:"<?php echo _('Used In')?>",width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"supplied_by", label:"<?php echo _('Supplied By')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"stock", label:"<?php echo _('Stock')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['parts']['view']=='forecast' or $_SESSION['state']['parts']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //  ,{key:"available_for", label:"<?php echo _('S Until')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['parts']['view']=='stock' or $_SESSION['state']['parts']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_value", label:"<?php echo _('Stk Value')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"avg_stock", label:"<?php echo _('AS')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"avg_stockvalue", label:"<?php echo _('ASV')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"keep_days", label:"<?php echo _('KD')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"outstock_days", label:"<?php echo _('OofS')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"unknown_days", label:"<?php echo _('?S')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts']['view']=='stock'   ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    ,{key:"sold", label:"<?php echo _('Sold(Given) Qty')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //   ,{key:"given", label:"<?php echo _('Given Qty')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts']['view']=='sale'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}} 
				    ,{key:"money_in", label:"<?php echo _('Sold')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    //    ,{key:"profit", label:"<?php echo _('Profit Out')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts']['view']=='sale'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit_sold", label:"<?php echo _('Profit (Inc Given)')?>", width:160,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"margin", label:"<?php echo _('Margin')?>", width:160,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=parts");
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
			 "sku"
			 ,"description"
			 ,"stock","available_for","stock_value","sold","given","money_in","profit","profit_sold","used_in","supplied_by","margin",'avg_stock','avg_stockvalue','keep_days','outstock_days','unknown_days','gmroi'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['parts']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<span id='paginator_info0'>{CurrentPageReport}</span>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['parts']['table']['order']?>",
									 dir: "<?php echo $_SESSION['state']['parts']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table0.view='<?php echo $_SESSION['state']['parts']['view']?>';
	    this.table0.filter={key:'<?php echo $_SESSION['state']['parts']['table']['f_field']?>',value:'<?php echo $_SESSION['state']['parts']['table']['f_value']?>'};
		





	};
    });

function change_period(e,table_id){
    tipo=this.id;
    Dom.get(period).className="";
    Dom.get(tipo).className="selected";	
    period=tipo;
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
    var request='&period=' + this.getAttribute('period');
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}
function change_avg(e,table_id){

    //  alert(avg);
    tipo=this.id;
    Dom.get(avg).className="";
    Dom.get(tipo).className="selected";	
    avg=tipo;
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
    var request='&avg=' + this.getAttribute('avg');
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}




 function init(){

// ------------------------------------------------------------------------
YAHOO.util.Event.addListener('export_csv0', "click",download_csv,'parts');
 YAHOO.util.Event.addListener('export_csv0_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table0',tipo:'parts'});
  csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {trigger:"export_csv0" });
	 csvMenu.render();
	 csvMenu.subscribe("show", csvMenu.focus);
   
 YAHOO.util.Event.addListener('export_csv0_close_dialog', "click",csvMenu.hide,csvMenu,true);
// -------------------------------------------------------------------------


 
  init_search('part');
 
 
 
 
 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 



 ids=['general','sales','stock','sales_outers'];
 YAHOO.util.Event.addListener(ids, "click",change_view,'parts');
 ids=['period_all','period_three_year','period_year','period_yeartoday','period_six_month','period_quarter','period_month','period_ten_day','period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_period,0);
 ids=['avg_totals','avg_month','avg_week',"avg_month_eff","avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,0);

 
 YAHOO.util.Event.addListener('show_details', "click",show_details,'parts')
     
 YAHOO.util.Event.addListener('submit_search', "click",submit_search);
 YAHOO.util.Event.addListener('prod_search', "keydown", submit_search_on_enter);

 Event.addListener('submit_search', "click",submit_search);
 Event.addListener('prod_search', "keydown", submit_search_on_enter);




 }

YAHOO.util.Event.onDOMReady(init);



YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 });
YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
