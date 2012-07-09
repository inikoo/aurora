<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2011 LW
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;
var Event   = YAHOO.util.Event;

var period='period_<?php echo $_SESSION['state']['invoice_categories']['period']?>';
var avg='avg_<?php echo $_SESSION['state']['invoice_categories']['avg']?>';

var dialog_new_category;


function change_block(){
ids=['subcategories','subjects','subcategories_charts','history','sales'];
block_ids=['block_subcategories','block_subjects','block_subcategories_charts','block_history','block_sales'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=part_categories-block_view&value='+this.id ,{});
}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
			       {key:"id", label:"<?php echo _('Public ID')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"date", label:"<?php echo _('Date')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"customer",label:"<?php echo _('Customer')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"orders",label:"<?php echo _('Order')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"dns",label:"<?php echo _('Delivery Note')?>", width:100,sortable:false,className:"aleft"}
				       
				       ,{key:"total_amount", label:"<?php echo _('Total')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"state", label:"<?php echo _('Status')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ];

request="ar_orders.php?tipo=invoices&tableid=0&where=&parent=category&parent_key="+Dom.get('category_key').value;
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
		 			 "id",
			 "state",
			 "customer",
			 "date",
			 "total_amount","orders","dns"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['orders']['invoices']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<span id='paginator_info0'>{CurrentPageReport}</span>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['orders']['invoices']['order']?>",
									 dir: "<?php echo $_SESSION['state']['orders']['invoices']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table0.filter={key:'<?php echo $_SESSION['state']['orders']['invoices']['f_field']?>',value:'<?php echo $_SESSION['state']['orders']['invoices']['f_value']?>'};
		


	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				
				    {key:"name", label:"<?php echo _('Name')?>", width:360,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"subjects", label:"<?php echo _('Parts')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"sold", label:"<?php echo _('Outers')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"sales", label:"<?php echo _('Sales')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				
				     ];

	    this.dataSource1 = new YAHOO.util.DataSource("ar_orders.php?tipo=invoice_categories&sf=0&tableid=1");
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
			"name","subjects","sold","sales"
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['invoice_categories']['subcategories']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['invoice_categories']['subcategories']['order']?>",
									 dir: "<?php echo$_SESSION['state']['invoice_categories']['subcategories']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table1.view='<?php echo$_SESSION['state']['invoice_categories']['view']?>';
	    this.table1.filter={key:'<?php echo$_SESSION['state']['invoice_categories']['subcategories']['f_field']?>',value:'<?php echo$_SESSION['state']['invoice_categories']['subcategories']['f_value']?>'};
this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);
		
 var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var PartsColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.part_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //     ,{key:"tipo", label:"<?php echo _('Type')?>", width:90,sortable:true,formatter:this.part_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"diff_qty",label:"<?php echo _('Qty')?>", width:90,sortable:true,formatter:this.part_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"abstract", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.part_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
				       
				       
				       
	    this.dataSource2 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=part_categories&id="+Dom.get('category_key').value+"&tableid=2");
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		  
		 rowsPerPage:"resultset.records_perpage",
		    RecordOffset : "resultset.records_offset", 
		       rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		
		},
		
		
		fields: [
			 "id"
			 ,"note"
			 ,'author','date','tipo','abstract','details'
			 ]};
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, PartsColumnDefs,
						     this.dataSource2
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['invoice_categories']['history']['nr']?>,containers : 'paginator2', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['invoice_categories']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['invoice_categories']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;

		       this.table2.table_id=tableid;
     this.table2.subscribe("renderEvent", myrenderEvent);

		    
	    this.table2.filter={key:'<?php echo$_SESSION['state']['invoice_categories']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['invoice_categories']['history']['f_value']?>'};





	};
    });




function change_sales_period(){
  tipo=this.id;
 
  ids=['category_period_yesterday','category_period_last_m','category_period_last_w','category_period_all','category_period_three_year','category_period_year','category_period_six_month','category_period_quarter','category_period_month','category_period_ten_day','category_period_week','category_period_yeartoday','category_period_monthtoday','category_period_weektoday','category_period_today'];

 Dom.removeClass(ids,"selected")
 Dom.addClass(this,"selected")
   period=this.getAttribute('period');
 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=part_categories-period&value='+period ,{});



Dom.setStyle(['info_yesterday','info_last_m','info_last_w','info_all','info_three_year','info_year','info_six_month','info_quarter','info_month','info_ten_day','info_week','info_yeartoday','info_monthtoday','info_weektoday','info_today'],'display','none')


Dom.setStyle(['info2_yesterday','info2_last_m','info2_last_w','info2_all','info2_three_year','info2_year','info2_six_month','info2_quarter','info2_month','info2_ten_day','info2_week','info2_yeartoday','info2_monthtoday','info2_weektoday','info2_today'],'display','none')
Dom.setStyle(['info_'+period,'info2_'+period],'display','')

}



function change_period(e,table_id){ }
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


function change_display_mode(name,label){
    if(name=='percentage'){
	var request='&percentages=1';
    }if(name=='value'){
	var request='&percentages=0&show_default_currency=0';
    }if(name=='value_default_d2d'){
	var request='&percentages=0&show_default_currency=1';
    }

    Dom.get('change_display_mode').innerHTML=label;
    var table=tables['table0'];
    var datasource=tables.dataSource0;
    
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   

}

 function change_invoice_type(e){
     var table=tables.table0;
     var datasource=tables.dataSource0;
     Dom.removeClass(Dom.getElementsByClassName('invoice_type','span' , 'invoice_chooser'),'selected');;
     Dom.addClass(this,'selected');     
     var request='&invoice_type='+this.getAttribute('table_type');
	 
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
 }

 function init(){

var ids =Array("restrictions_paid","restrictions_to_pay","restrictions_refunds","restrictions_invoices","restrictions_all_invoices") ;
Event.addListener(ids, "click", change_invoice_type);

 ids=['subcategories','subjects','subcategories_charts','history','sales'];
 Event.addListener(ids, "click",change_block);
  
 init_search('parts');
 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 
 
 ids=['elements_Keeping','elements_NotKeeping','elements_Discontinued','elements_LastStock'];
 Event.addListener(ids, "click",change_parts_elements,0);
 var ids=['parts_general','parts_stock','parts_sales','parts_forecast','parts_locations'];
 YAHOO.util.Event.addListener(ids, "click",change_parts_view,0);
 ids=['parts_period_all','parts_period_three_year','parts_period_year','parts_period_yeartoday','parts_period_six_month','parts_period_quarter','parts_period_month','parts_period_ten_day','parts_period_week', 'parts_period_monthtoday','parts_period_weektoday','parts_period_today'];
 YAHOO.util.Event.addListener(ids, "click",change_parts_period,0);
 ids=['parts_avg_totals','parts_avg_month','parts_avg_week',"parts_avg_month_eff","parts_avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_parts_avg,0);

 ids=['subcategories_period_all','subcategories_period_three_year','subcategories_period_year','subcategories_period_yeartoday','subcategories_period_six_month','subcategories_period_quarter','subcategories_period_month','subcategories_period_ten_day','subcategories_period_week','subcategories_period_monthtoday','subcategories_period_weektoday','subcategories_period_today'];
 YAHOO.util.Event.addListener(ids, "click",change_period,1);
 ids=['category_period_all','category_period_three_year','category_period_year','category_period_yeartoday','category_period_six_month','category_period_quarter','category_period_month','category_period_ten_day','category_period_week','category_period_monthtoday','category_period_weektoday','category_period_today','category_period_yesterday','category_period_last_m','category_period_last_w'];
 YAHOO.util.Event.addListener(ids, "click",change_sales_period);

 }

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });

YAHOO.util.Event.onContentReady("change_display_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("change_display_menu", { context:["change_display_mode","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("change_display_mode", "click", oMenu.show, null, oMenu);
  
    });
