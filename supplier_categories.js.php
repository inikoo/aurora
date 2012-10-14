<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2011 LW
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;
var Event   = YAHOO.util.Event;

var period='period_<?php echo $_SESSION['state']['supplier_categories']['period']?>';
var avg='avg_<?php echo $_SESSION['state']['supplier_categories']['avg']?>';

var dialog_new_category;

function post_create_actions(){
table_id=0;
							var table=tables['table'+table_id];
    						var datasource=tables['dataSource'+table_id];
    						var request='&table_id='+table_id;
    						datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    

}


function change_category(){
    if(Dom.hasClass(this,'selected'))
    	return;
    //alert(this)
     Dom.removeClass(Dom.getElementsByClassName('selected','span','cat_chooser'),'selected');
    Dom.addClass(this,'selected');
    var table_id=0
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
    var request='&category=' + this.getAttribute('cat_id');
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   


    
}
var change_view=function(e){
	
	var table=tables['table0'];
	var tipo=this.id;
	//	alert(table.view+' '+tipo)
	if(table.view!=tipo){
	    table.hideColumn('active');
	    table.hideColumn('families');
	    table.hideColumn('departments');
	    table.hideColumn('todo');
	    table.hideColumn('discontinued');

	    table.hideColumn('sales');
	    table.hideColumn('profit');
	    //    table.hideColumn('stock_value');
	    table.hideColumn('stock_error');
	    table.hideColumn('outofstock');
	    table.hideColumn('surplus');
	    table.hideColumn('optimal');
	    table.hideColumn('low');
	    table.hideColumn('critcal');

	    if(tipo=='sales'){
		Dom.get('period_options').style.display='';
		Dom.get('avg_options').style.display='';
		table.showColumn('sales');
		table.showColumn('profit');
	    }
	    if(tipo=='general'){
		Dom.get('period_options').style.display='none';
		Dom.get('avg_options').style.display='none';
		table.showColumn('active');
		table.showColumn('families');
		table.showColumn('departments');
		table.showColumn('todo');
		table.showColumn('discontinued');

	    }
	    if(tipo=='stock'){
		Dom.get('period_options').style.display='none';
		Dom.get('avg_options').style.display='none';
		
		table.showColumn('surplus');
		table.showColumn('optimal');
		table.showColumn('low');
		table.showColumn('critcal');
		table.showColumn('stock_error');
		table.showColumn('outofstock');
	    }

	    

	
	Dom.get(table.view).className="";
	Dom.get(tipo).className="selected";
	table.view=tipo
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customer_categories-view&value=' + escape(tipo) );
	}
  }


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    
	    
	    if(Dom.get('category_key').value){
	        hide_sales=false
	    }else{
	    hide_sales=true
	    }
	    
	    
	    var OrdersColumnDefs = [ 
				 //  {key:"go",label:'',width:20,},
				    {key:"name", label:"<?php echo _('Name')?>", width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"subjects", label:"<?php echo _('Suppliers')?>", width:260,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"sales", label:"<?php echo _('Sales')?>", hidden:hide_sales, width:260,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				/*    ,{key:"active", label:"<?php echo _('Products')?>",  width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['product_categories']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"discontinued", label:"<?php echo _('Discontinued')?>",  width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['product_categories']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['product_categories']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['product_categories']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"surplus", label:"<?php echo _('Surplus')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['product_categories']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"optimal", label:"<?php echo _('OK')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['product_categories']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"low", label:"<?php echo _('Low')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['product_categories']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"critical", label:"<?php echo _('Critical')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['product_categories']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"outofstock", label:"<?php echo _('Gone')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['product_categories']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?php echo _('Unknown')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['product_categories']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
		               */
				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_suppliers.php?tipo=supplier_categories&sf=0&category_key="+Dom.get('category_key').value);
	    //alert("ar_suppliers.php?tipo=supplier_categories&sf=0&category_key="+Dom.get('category_key').value)
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
		        "name","subjects","sales"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['supplier_categories']['subcategories']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['supplier_categories']['subcategories']['order']?>",
									 dir: "<?php echo$_SESSION['state']['supplier_categories']['subcategories']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

 this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);
	    
	    this.table0.view='<?php echo$_SESSION['state']['supplier_categories']['view']?>';
	    this.table0.filter={key:'<?php echo$_SESSION['state']['supplier_categories']['subcategories']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_categories']['subcategories']['f_value']?>'};

		

    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    

	    
	    var SuppliersColumnDefs = [
				       {key:"id", label:"<?php echo _('Id')?>", hidden:true, width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"code", label:"<?php echo _('Code')?>",width:80, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Name')?>",<?php echo(($_SESSION['state']['supplier_categories']['suppliers']['view']=='general' or $_SESSION['state']['supplier_categories']['suppliers']['view']=='contact' or $_SESSION['state']['supplier_categories']['suppliers']['view']=='products' or $_SESSION['state']['supplier_categories']['suppliers']['view']=='sales')?'':'hidden:true,')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"contact",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='contact'?'hidden:true,':'')?> label:"<?php echo _('Contact')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       ,{key:"email", label:"<?php echo _('Email')?>",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='contact'?'hidden:true,':'')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='general'?'hidden:true,':'')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"tel",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='contact'?'hidden:true,':'')?> label:"<?php echo _('Tel')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       ,{key:"pending_pos", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='general'?'hidden:true,':'')?> label:"<?php echo _('P POs')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"for_sale", <?php echo(($_SESSION['state']['supplier_categories']['suppliers']['view']=='products' or  $_SESSION['state']['supplier_categories']['suppliers']['view']=='general') ?'':'hidden:true,')?> label:"<?php echo _('Products')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"discontinued",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='products'?'hidden:true,':'')?>  label:"<?php echo _('Discontinued')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"stock_value",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='money'?'hidden:true,':'')?>  label:"<?php echo _('Stock Value')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					
					,{key:"high",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='stock'?'hidden:true,':'')?>  label:"<?php echo _('High')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"normal",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='stock'?'hidden:true,':'')?>  label:"<?php echo _('Normal')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"low", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='stock'?'hidden:true,':'')?> label:"<?php echo _('Low')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"critical", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='stock'?'hidden:true,':'')?> label:"<?php echo _('Critical')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"outofstock", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='stock'?'hidden:true,':'')?> label:"<?php echo _('Out of Stock')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					
					,{key:"sales", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='sales'?'hidden:true,':'')?> label:"<?php echo _('Sales')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			
			,{key:"profit", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='profit'?'hidden:true,':'')?> label:"<?php echo _('Profit')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       	 ,{key:"profit_after_storing", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='profit'?'hidden:true,':'')?> label:"<?php echo _('PaS')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"cost", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='profit'?'hidden:true,':'')?> label:"<?php echo _('Cost')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 					 ,{key:"margin", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='profit'?'hidden:true,':'')?> label:"<?php echo _('Margin')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       ];

	      this.dataSource1 = new YAHOO.util.DataSource("ar_suppliers.php?tipo=suppliers&parent=category&tableid=1&parent_key="+Dom.get('category_key').value);
	//	 alert("ar_suppliers.php?tipo=suppliers&parent=category&parent_key="+Dom.get('category_key').value);
  this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",

		    rowsPerPage:"resultset.records_perpage",
		    recordsOffset:"resultset.records_offset",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 "id"
			 ,"name"
			 ,"code"
			 ,"for_sale"
			 ,"outofstock"
			 ,"low","location","email","profit",'profit_after_storing','cost',"pending_pos","sales","contact","critical","margin"
	 ]};

table_paginator1=new YAHOO.widget.Paginator({
								       alwaysVisible:true,
									       rowsPerPage    : <?php echo$_SESSION['state']['supplier_categories']['suppliers']['nr']?>,
									       containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",
 									      rowsPerPageOptions : [10,25,50,100,250,500],
									      template : "{FirstPageLink}{PreviousPageLink} <strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  });

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs,
						     this.dataSource1, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : table_paginator1
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['supplier_categories']['suppliers']['order']?>",
									 dir: "<?php echo$_SESSION['state']['supplier_categories']['suppliers']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	   this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	  //table_paginator0.unsubscribe("changeRequest", this.table1.onPaginatorChangeRequest);
	  
	  
	  
	//  table_paginator0.subscribe("changeRequest", handlePagination, this.table1, true); 
	  
	  //  this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	   // this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['supplier_categories']['suppliers']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_categories']['suppliers']['f_value']?>'};
	    this.table1.view='<?php echo$_SESSION['state']['supplier_categories']['suppliers']['view']?>';
	    




	};
    });





 function init(){
 
    init_search('supplier_products');

 
var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 


    ids=['general'];
 YAHOO.util.Event.addListener(ids, "click",change_view,{'table_id':0,'parent':'supplier_categories'})

 ids=['period_all','period_year','period_quarter','period_month','period_week',
 'period_six_month','period_three_year','period_ten_day','period_month','period_week',
 'period_yeartoday','period_monthtoday','period_weektoday'
 
 ];
 YAHOO.util.Event.addListener(ids, "click",change_period,{'table_id':0,'subject':'supplier_categories'});


     ids=['suppliers_general','suppliers_sales','suppliers_stock','suppliers_products','suppliers_contact','suppliers_profit'];
 YAHOO.util.Event.addListener(ids, "click",change_suppliers_view,{'table_id':0,'parent':'suppliers'})

 ids=['suppliers_period_all','suppliers_period_year','suppliers_period_quarter','suppliers_period_month','suppliers_period_week',
 'suppliers_period_six_month','suppliers_period_three_year','suppliers_period_ten_day','suppliers_period_month','suppliers_period_week',
 'suppliers_period_yeartoday','suppliers_period_monthtoday','suppliers_period_weektoday'
 
 ];
 YAHOO.util.Event.addListener(ids, "click",change_period,{'table_id':0,'subject':'suppliers'});


 ids=['suppliers_avg_totals','suppliers_avg_month','suppliers_avg_week'];
 YAHOO.util.Event.addListener(ids, "click",change_avg,{'table_id':0,'subject':'suppliers'});

 


 

 var elements = Dom.getElementsByClassName('cat_chooser','span','cat_chooser');

     YAHOO.util.Event.addListener(elements, "click",change_category);

 


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
