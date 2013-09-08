<?php
include_once('common.php');

?>
  var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;

var dialog_export;
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
				    {key:"sku", label:"<?php echo _('SKU')?>", width:40,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  	,{key:"reference", label:"<?php echo _('Reference')?>",width:90,<?php echo($_SESSION['state']['parts_list']['parts']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  	,{key:"description", label:"<?php echo _('Description')?>",width:380,<?php echo($_SESSION['state']['parts_list']['parts']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"description_small", label:"<?php echo _('Description')?>",width:320,<?php echo($_SESSION['state']['parts_list']['parts']['view']!='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"used_in", label:"<?php echo _('Used In')?>",width:350,<?php echo($_SESSION['state']['parts_list']['parts']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"supplied_by", label:"<?php echo _('Supplied By')?>",width:200,<?php echo($_SESSION['state']['parts_list']['parts']['view']=='supplier'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   	,{key:"locations", label:"<?php echo _('Locations')?>", width:200,sortable:false,className:"aleft",<?php echo(($_SESSION['state']['parts_list']['parts']['view']=='locations' )  ?'':'hidden:true')?>}
				    ,{key:"stock_value", label:"<?php echo _('Stk Value')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['parts_list']['parts']['view']=='stock' or $_SESSION['state']['parts_list']['parts']['view']=='locations')?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   	,{key:"stock", label:"<?php echo _('Stock')?>", width:80,sortable:true,className:"aright",<?php echo(($_SESSION['state']['parts_list']['parts']['view']=='stock' or $_SESSION['state']['parts_list']['parts']['view']=='locations' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_days", label:"<?php echo _('Stk Days')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['parts_list']['parts']['view']=='stock' )?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_state", label:"<?php echo _('Stk State')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['parts_list']['parts']['view']=='stock' )?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					//,{key:"avg_stock", label:"<?php echo _('AS')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts_list']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   //,{key:"avg_stockvalue", label:"<?php echo _('ASV')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts_list']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   //,{key:"keep_days", label:"<?php echo _('KD')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts_list']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   //,{key:"outstock_days", label:"<?php echo _('OofS')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts_list']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    // ,{key:"unknown_days", label:"<?php echo _('?S')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts_list']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sold", label:"<?php echo _('Sold(Given) Qty')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts_list']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"delta_sold", label:"<?php echo '&Delta;'._('Sold(Given) Qty')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts_list']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"money_in", label:"<?php echo _('Sold')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts_list']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"delta_money_in", label:"<?php echo '&Delta;'._('Sold')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts_list']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //    ,{key:"profit", label:"<?php echo _('Profit Out')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts_list']['parts']['view']=='sale'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit_sold", label:"<?php echo _('Profit (Inc Given)')?>", width:160,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts_list']['parts']['view']=='forecast'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"margin", label:"<?php echo _('Margin')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts_list']['parts']['view']=='forecast'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   	,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['parts_list']['parts']['view']=='forecast'   ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];
request="ar_parts.php?tipo=parts&parent=list&parent_key="+Dom.get('list_key').value+"&tableid=0&where=&sf=0";
//alert(request)
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
			 "sku","reference",
			 "description","locations","description_small","delta_money_in","delta_sold","stock_days","stock_state",
			 "stock","available_for","stock_value","sold","given","money_in","profit","profit_sold","used_in","supplied_by","margin",'avg_stock','avg_stockvalue','keep_days','outstock_days','unknown_days','gmroi'
			 ]};

	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['parts_list']['parts']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<span id='paginator_info0'>{CurrentPageReport}</span>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['parts_list']['parts']['order']?>",
									 dir: "<?php echo $_SESSION['state']['parts_list']['parts']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table0.request=request;
  		this.table0.table_id=tableid;
     	this.table0.subscribe("renderEvent", part_myrenderEvent);
		this.table0.getDataSource().sendRequest(null, {
      		success:function(request, response, payload) {
        if(response.results.length == 0) {
            get_part_elements_numbers()
            
        } else {
           // this.onDataReturnInitializeTable(request, response, payload);
        }
    },
    scope:this.table0,
    argument:this.table0.getState()
});
	    
	    this.table0.view='<?php echo $_SESSION['state']['parts_list']['parts']['view']?>';
	    this.table0.filter={key:'<?php echo $_SESSION['state']['parts_list']['parts']['f_field']?>',value:'<?php echo $_SESSION['state']['parts_list']['parts']['f_value']?>'};
		
	
	};
    });

function change_parts_view_save(tipo) {
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=parts_list-parts-view&value=' + escape(tipo), {});

}


 function init(){
 
 
    dialog_export['parts'] = new YAHOO.widget.Dialog("dialog_export_parts", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_export['parts'].render();
    Event.addListener("export_parts", "click", show_export_dialog, 'parts');
    Event.addListener("export_csv_parts", "click", export_table, {
        output: 'csv',
        table: 'parts',
        parent: 'list',
        'parent_key': Dom.get('parent_key').value
    });
    Event.addListener("export_xls_parts", "click", export_table, {
        output: 'xls',
        table: 'parts',
        parent: 'list',
        'parent_key': Dom.get('parent_key').value
    });

    Event.addListener("export_result_download_link_parts", "click", download_export_file, 'parts');

 
 /*
 var ids=['parts_general','parts_stock','parts_sales','parts_forecast','parts_locations'];
YAHOO.util.Event.addListener(ids, "click",change_parts_view,0);
 ids=['parts_period_all','parts_period_three_year','parts_period_year','parts_period_yeartoday','parts_period_six_month','parts_period_quarter','parts_period_month','parts_period_ten_day','parts_period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_parts_period,0);
 ids=['parts_avg_totals','parts_avg_month','parts_avg_week',"parts_avg_month_eff","parts_avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_parts_avg,0);
 */
 
  init_search('parts');



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



