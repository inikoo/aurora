<?php
    include_once('common.php');?>
    
    
    var Dom   = YAHOO.util.Dom;
    var Event = YAHOO.util.Event;
    
    function change_block(){
ids=['suppliers','porders','sproducts','sinvoices','idn'];
block_ids=['block_suppliers','block_porders','block_sproducts','block_sinvoices','block_idn'];

Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=suppliers-block_view&value='+this.id ,{});
}

    
    
Event.addListener(window, "load", function() {
    tables = new function() {
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    

	    
	    var SuppliersColumnDefs = [
				       {key:"id", label:"<?php echo _('Id')?>", hidden:true, width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"code", label:"<?php echo _('Code')?>",width:80, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Name')?>",<?php echo(($_SESSION['state']['suppliers']['suppliers']['view']=='general' or $_SESSION['state']['suppliers']['suppliers']['view']=='contact' or $_SESSION['state']['suppliers']['suppliers']['view']=='products' or $_SESSION['state']['suppliers']['suppliers']['view']=='sales')?'':'hidden:true,')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"contact",<?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='contact'?'hidden:true,':'')?> label:"<?php echo _('Contact')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       ,{key:"email", label:"<?php echo _('Email')?>",<?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='contact'?'hidden:true,':'')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='general'?'hidden:true,':'')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"tel",<?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='contact'?'hidden:true,':'')?> label:"<?php echo _('Tel')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       ,{key:"pending_pos", <?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='general'?'hidden:true,':'')?> label:"<?php echo _('P POs')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"for_sale", <?php echo(($_SESSION['state']['suppliers']['suppliers']['view']=='products' or  $_SESSION['state']['suppliers']['suppliers']['view']=='general') ?'':'hidden:true,')?> label:"<?php echo _('Products')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"discontinued",<?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='products'?'hidden:true,':'')?>  label:"<?php echo _('Discontinued')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"stock_value",<?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='money'?'hidden:true,':'')?>  label:"<?php echo _('Stock Value')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					
					,{key:"high",<?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='stock'?'hidden:true,':'')?>  label:"<?php echo _('High')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"normal",<?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='stock'?'hidden:true,':'')?>  label:"<?php echo _('Normal')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"low", <?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='stock'?'hidden:true,':'')?> label:"<?php echo _('Low')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"critical", <?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='stock'?'hidden:true,':'')?> label:"<?php echo _('Critical')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"outofstock", <?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='stock'?'hidden:true,':'')?> label:"<?php echo _('Out of Stock')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					
					,{key:"sales", <?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='sales'?'hidden:true,':'')?> label:"<?php echo _('Sales')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			
			,{key:"profit", <?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='profit'?'hidden:true,':'')?> label:"<?php echo _('Profit')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       	 ,{key:"profit_after_storing", <?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='profit'?'hidden:true,':'')?> label:"<?php echo _('PaS')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"cost", <?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='profit'?'hidden:true,':'')?> label:"<?php echo _('Cost')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 					 ,{key:"margin", <?php echo($_SESSION['state']['suppliers']['suppliers']['view']!='profit'?'hidden:true,':'')?> label:"<?php echo _('Margin')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       ];

	      this.dataSource0 = new YAHOO.util.DataSource("ar_suppliers.php?tipo=suppliers");
		  //alert("ar_suppliers.php?tipo=suppliers");
  this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
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

table_paginator0=new YAHOO.widget.Paginator({
								       alwaysVisible:true,
									       rowsPerPage    : <?php echo$_SESSION['state']['suppliers']['suppliers']['nr']?>,
									       containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",
 									      rowsPerPageOptions : [10,25,50,100,250,500],
									      template : "{FirstPageLink}{PreviousPageLink} <strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  });

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : table_paginator0
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['suppliers']['suppliers']['order']?>",
									 dir: "<?php echo$_SESSION['state']['suppliers']['suppliers']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	   this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	  //table_paginator0.unsubscribe("changeRequest", this.table0.onPaginatorChangeRequest);
	  
	  
	  
	//  table_paginator0.subscribe("changeRequest", handlePagination, this.table0, true); 
	  
	  //  this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	   // this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['suppliers']['suppliers']['f_field']?>',value:'<?php echo$_SESSION['state']['suppliers']['suppliers']['f_value']?>'};
	    this.table0.view='<?php echo$_SESSION['state']['suppliers']['suppliers']['view']?>';
	    



	var tableid=1;
		var tableDivEL="table"+tableid;
		var ColumnDefs = [
				//  {key:"id", label:"<?php echo _('Id')?>",width:45,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  {key:"supplier", label:"<?php echo _('Supplier')?>",  width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	              ,{key:"code", label:"<?php echo _('Code')?>",  width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"description", label:"<?php echo _('Description')?>",<?php echo($_SESSION['state']['suppliers']['supplier_products']['view']=='general'?'':'hidden:true,')?>width:380, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  ,{key:"used_in", label:"<?php echo _('Used In')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"stock", label:"<?php echo _('Stock')?>",<?php echo($_SESSION['state']['suppliers']['supplier_products']['view']=='stock'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"weeks_until_out_of_stock", label:"<?php echo _('W Until OO')?>",<?php echo($_SESSION['state']['suppliers']['supplier_products']['view']=='stock'?'':'hidden:true,')?> width:75,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					  ,{key:"required", label:"<?php echo _('Required')?>",<?php echo($_SESSION['state']['suppliers']['supplier_products']['view']=='sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"dispatched", label:"<?php echo _('Dispatched')?>",<?php echo($_SESSION['state']['suppliers']['supplier_products']['view']=='sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 	,{key:"sold", label:"<?php echo _('Sold')?>",<?php echo($_SESSION['state']['suppliers']['supplier_products']['view']=='sales'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 	,{key:"sales", label:"<?php echo _('Sales')?>",<?php echo($_SESSION['state']['suppliers']['supplier_products']['view']=='sales'?'':'hidden:true,')?> width:75,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 ,{key:"profit", label:"<?php echo _('Profit')?>",<?php echo($_SESSION['state']['suppliers']['supplier_products']['view']=='profit'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 ,{key:"margin", label:"<?php echo _('Margin')?>",<?php echo($_SESSION['state']['suppliers']['supplier_products']['view']=='profit'?'':'hidden:true,')?> width:55,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				  ];

		this.dataSource1 = new YAHOO.util.DataSource("ar_suppliers.php?tipo=supplier_products&parent=none&tableid="+tableid);
		//alert("ar_suppliers.php?tipo=supplier_products&parent=none&tableid="+tableid);

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
				 "description","id","code","name","cost","used_in","profit","allcost","used","required","provided","lost","broken","supplier",
				 "dispatched","sold","sales","weeks_until_out_of_stock","stock","margin"
				 ]};
	    
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource1, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
									 rowsPerPage:<?php echo $_SESSION['state']['suppliers']['supplier_products']['nr']?>,containers : 'paginator1', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								    key: "<?php echo$_SESSION['state']['suppliers']['supplier_products']['order']?>",
								     dir: "<?php echo$_SESSION['state']['suppliers']['supplier_products']['order_dir']?>"
								 }
								 ,dynamicData : true
								 
							     }
							     );
		this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table1.filter={key:'<?php echo$_SESSION['state']['suppliers']['supplier_products']['f_field']?>',value:'<?php echo$_SESSION['state']['suppliers']['supplier_products']['f_value']?>'};
		this.table1.view='<?php echo$_SESSION['state']['suppliers']['supplier_products']['view']?>';


	};
    });




function init(){

    init_search('supplier_products');

ids=['suppliers','porders','sproducts','sinvoices','idn'];
    Event.addListener(ids, "click",change_block);
    
      ids=['suppliers_general','suppliers_sales','suppliers_stock','suppliers_products','suppliers_contact','suppliers_profit'];
 YAHOO.util.Event.addListener(ids, "click",change_suppliers_view,{'table_id':0,'parent':'suppliers'})

 ids=['suppliers_period_all','suppliers_period_year','suppliers_period_quarter','suppliers_period_month','suppliers_period_week',
 'suppliers_period_six_month','suppliers_period_three_year','suppliers_period_ten_day','suppliers_period_month','suppliers_period_week',
 'suppliers_period_yeartoday','suppliers_period_monthtoday','suppliers_period_weektoday'
 
 ];
 YAHOO.util.Event.addListener(ids, "click",change_period,{'table_id':0,'subject':'suppliers'});


 ids=['suppliers_avg_totals','suppliers_avg_month','suppliers_avg_week'];
 YAHOO.util.Event.addListener(ids, "click",change_avg,{'table_id':0,'subject':'suppliers'});

 
   
    ids=['supplier_products_general','supplier_products_sales','supplier_products_stock','supplier_products_profit'];
 YAHOO.util.Event.addListener(ids, "click",change_supplier_products_view,{'table_id':1,'parent':'suppliers'})
 ids=['supplier_products_period_all','supplier_products_period_year','supplier_products_period_quarter','supplier_products_period_month','supplier_products_period_week',
 'supplier_products_period_six_month','supplier_products_period_three_year','supplier_products_period_ten_day','supplier_products_period_month','supplier_products_period_week',
 'supplier_products_period_yeartoday','supplier_products_period_monthtoday','supplier_products_period_weektoday'
 
 ];

 YAHOO.util.Event.addListener(ids, "click",change_period,{'table_id':1,'subject':'supplier_products'});
 ids=['supplier_products_avg_totals','supplier_products_avg_month','supplier_products_avg_week'];
 YAHOO.util.Event.addListener(ids, "click",change_avg,{'table_id':1,'subject':'suppliers'});

    
    
    
 //Event.addListener('export_csv0', "click",download_csv,'suppliers');
 //Event.addListener('export_csv0_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table0',tipo:'suppliers'});
//csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {trigger:"export_csv0" });
//	 csvMenu.render();
	// csvMenu.subscribe("show", csvMenu.focus);
    //Event.addListener('export_csv0_close_dialog', "click",csvMenu.hide,csvMenu,true);
 
 

    
   // ids=['suppliers_general','suppliers_sales','suppliers_stock','suppliers_products'];
  //  Event.addListener(ids, "click",supplier_change_view)


  

   Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
 
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
    });

Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });
Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

