<?php
include_once('common.php');

?>
 var Dom   = YAHOO.util.Dom;

var  families_period_ids=['families_period_all',
 'families_period_yesterday',
 'families_period_last_w',
 'families_period_last_m','families_period_three_year','families_period_year','families_period_yeartoday','families_period_six_month','families_period_quarter','families_period_month','families_period_ten_day','families_period_week','families_period_monthtoday','families_period_weektoday','families_period_today'];


function change_families_period(e, table_id) {

    tipo = this.id;

    Dom.removeClass(families_period_ids, "selected")
    Dom.addClass(this, "selected")

    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '&period=' + this.getAttribute('period');
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {




	    var tableid=2;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
				    {key:"sku", label:"<?php echo _('SKU')?>", width:40,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  	,{key:"reference", label:"<?php echo _('Reference')?>",width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  	,{key:"description", label:"<?php echo _('Description')?>",width:380,<?php echo($_SESSION['state']['warehouse']['parts']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   // ,{key:"description_small", label:"<?php echo _('Description')?>",width:320,<?php echo($_SESSION['state']['warehouse']['parts']['view']!='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"used_in", label:"<?php echo _('Used In')?>",width:350,<?php echo($_SESSION['state']['warehouse']['parts']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"supplied_by", label:"<?php echo _('Supplied By')?>",width:200,<?php echo($_SESSION['state']['warehouse']['parts']['view']=='supplier'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   	,{key:"locations", label:"<?php echo _('Locations')?>", width:200,sortable:false,className:"aleft",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='locations' )  ?'':'hidden:true')?>}
				    ,{key:"stock_value", label:"<?php echo _('Stk Value')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' or $_SESSION['state']['warehouse']['parts']['view']=='locations')?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   	,{key:"stock", label:"<?php echo _('Stock')?>", width:80,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' or $_SESSION['state']['warehouse']['parts']['view']=='locations' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_days", label:"<?php echo _('Stk Days')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' )?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_state", label:"<?php echo _('Stk State')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' )?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"next_shipment", label:"<?php echo _('Next Shipment')?>", width:120,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' )?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				//,{key:"avg_stock", label:"<?php echo _('AS')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   //,{key:"avg_stockvalue", label:"<?php echo _('ASV')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   //,{key:"keep_days", label:"<?php echo _('KD')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   //,{key:"outstock_days", label:"<?php echo _('OofS')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    // ,{key:"unknown_days", label:"<?php echo _('?S')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sold", label:"<?php echo _('Sold(Given) Qty')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"delta_sold", label:"<?php echo '&Delta;'._('Sold(Given) Qty')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"money_in", label:"<?php echo _('Sold')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"delta_money_in", label:"<?php echo '&Delta;'._('Sold')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //    ,{key:"profit", label:"<?php echo _('Profit Out')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sale'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit_sold", label:"<?php echo _('Profit (Inc Given)')?>", width:160,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='forecast'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"margin", label:"<?php echo _('Margin')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='forecast'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   	,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='forecast'   ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
		,{key:"package_type", label:"<?php echo _('Pkg Type')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"package_weight", label:"<?php echo _('Pkg Weight')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"package_dimension", label:"<?php echo _('Pkg Dim')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"package_volume", label:"<?php echo _('Pkg Vol')?>", width:110,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"unit_weight", label:"<?php echo _('Unit Weight')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"unit_dimension", label:"<?php echo _('Unit Dim')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				   ];
request="ar_parts.php?tipo=parts&parent=warehouse&parent_key="+Dom.get('warehouse_id').value+"&tableid=2&where=&sf=0";
	//alert(request)
	this.dataSource2 = new YAHOO.util.DataSource(request);
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
			 "sku","reference",
			 			 "package_type","package_weight","package_dimension","package_volume","unit_weight","unit_dimension",

			 "description","locations","description_small","delta_money_in","delta_sold","stock_days","stock_state","next_shipment",
			 "stock","available_for","stock_value","sold","given","money_in","profit","profit_sold","used_in","supplied_by","margin",'avg_stock','avg_stockvalue','keep_days','outstock_days','unknown_days','gmroi'
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
		this.table2.request=request;
  		this.table2.table_id=tableid;
     	this.table2.subscribe("renderEvent", part_myrenderEvent);
		this.table2.getDataSource().sendRequest(null, {
		    success: function(request, response, payload) {
		    
		  
		    
		        if (response.results.length == 0) {
		        //alert("caca")
		            get_part_elements_numbers()

		        } else {
		            // this.onDataReturnInitializeTable(request, response, payload);
		        }
		    },
		    scope: this.table2,
		    argument: this.table2.getState()
		});

	    
	    
	    this.table2.view='<?php echo $_SESSION['state']['warehouse']['parts']['view']?>';
	    this.table2.filter={key:'<?php echo $_SESSION['state']['warehouse']['parts']['f_field']?>',value:'<?php echo $_SESSION['state']['warehouse']['parts']['f_value']?>'};
		


	    var tableid=3; 
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				
				    {key:"code", label:"<?php echo _('Code')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"label", label:"<?php echo _('Label')?>", width:360,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"subjects", label:"<?php echo _('Parts')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"sales", label:"<?php echo _('Sales')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"delta_sales", label:"<?php echo '&Delta;'._('Sales')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				
				     ];
	  request="ar_parts.php?tipo=part_categories&sf=0&tableid="+tableid+"&parent=category&parent_key="+Dom.get('part_families_category_key').value+'&scope=part_families'
	 // alert(request)
	  this.dataSource3 = new YAHOO.util.DataSource(request);
	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
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
			"code","subjects","sold","sales","label","delta_sales"
			 ]};
	    
	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource3, {
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['warehouse']['families']['nr']?>,
									      containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['warehouse']['families']['order']?>",
									 dir: "<?php echo$_SESSION['state']['warehouse']['families']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table3.filter={key:'<?php echo$_SESSION['state']['warehouse']['families']['f_field']?>',value:'<?php echo$_SESSION['state']['warehouse']['families']['f_value']?>'};
		this.table3.table_id=tableid;
     	this.table3.subscribe("renderEvent", myrenderEvent);


	};
    });


function change_block() {

    if (this.id == 'history' || this.id == 'movements') {
        window.location = "inventory.php?warehouse_id=" + Dom.get('warehouse_key').value + "&block_view=" + this.id
    } else {
        ids = ['history', 'movements', 'parts', 'families']
        block_ids = ['block_history', 'block_movements', 'block_parts', 'block_families']
        Dom.setStyle(block_ids, 'display', 'none');
        Dom.setStyle('block_' + this.id, 'display', '');
        Dom.removeClass(ids, 'selected');
    
        Dom.addClass(this, 'selected');

        if (this.id == 'parts' || this.id == 'families') {
            Dom.setStyle('calendar_container', 'display', 'none');

        } else {
            Dom.setStyle('calendar_container', 'display', '');

        }

        YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=warehouse-parts_view&value=' + this.id, {});


    }


}



function init() {

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
        parent: 'warehouse',
        'parent_key': Dom.get('warehouse_key').value
    });
    Event.addListener("export_xls_parts", "click", export_table, {
        output: 'xls',
        table: 'parts',
        parent: 'warehouse',
        'parent_key': Dom.get('warehouse_key').value
    });

    Event.addListener("export_result_download_link_parts", "click", download_export_file,'parts');


    Event.addListener(['history', 'movements', 'parts','families'], "click", change_block);

    init_search('parts');
	

  Event.addListener('clean_table_filter_show3', "click", show_filter, 3);
    Event.addListener('clean_table_filter_hide3', "click", hide_filter, 3);
 


  var oACDS3 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS3.queryMatchContains = true;
    oACDS3.table_id = 3;
    var oAutoComp3 = new YAHOO.widget.AutoComplete("f_input3", "f_container3", oACDS3);
    oAutoComp3.minQueryLength = 0;

    YAHOO.util.Event.addListener(families_period_ids, "click", change_families_period, 3);




}

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu3", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu3", {trigger:"rtext_rpp3" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu3", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu3", {trigger:"filter_name3"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });


YAHOO.util.Event.onContentReady("rppmenu2", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {
        trigger: "rtext_rpp2"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});

YAHOO.util.Event.onContentReady("filtermenu2", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {
        trigger: "filter_name2"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});
