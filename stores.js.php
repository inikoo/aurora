<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;
var Event  =YAHOO.util.Event;

var csvMenu;

var dialog_change_stores_display;
var dialog_change_departments_display;
var dialog_change_families_display;
var dialog_change_products_display;

function change_block(){
ids=['details','stores','departments','families','products','deals','sites'];
block_ids=['block_details','block_stores','block_departments','block_families','block_products','block_categories','block_sites'];

Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-block_view&value='+this.id ,{});
dialog_change_stores_display.hide();
}


var change_view=function(e){
	
	var table=tables['table0'];
	var tipo=this.id;
	//	alert(tipo)

	    table.hideColumn('active');
	    table.hideColumn('families');
	    table.hideColumn('departments');
	    table.hideColumn('todo');
	    table.hideColumn('discontinued');

	    table.hideColumn('sales');
	    table.hideColumn('profit');
	     table.hideColumn('new');
	    table.hideColumn('stock_error');
	    table.hideColumn('outofstock');
	    table.hideColumn('surplus');
	    table.hideColumn('optimal');
	    table.hideColumn('low');
	    table.hideColumn('critcal');
	    table.hideColumn('margin');

	    if(tipo=='sales'){
		Dom.get('stores_period_options').style.display='';
		Dom.get('stores_avg_options').style.display='';
		table.showColumn('sales');
		table.showColumn('profit');
		table.showColumn('margin');

	    }
	    if(tipo=='general'){
		Dom.get('stores_period_options').style.display='none';
		Dom.get('stores_avg_options').style.display='none';
		table.showColumn('active');
		table.showColumn('families');
		table.showColumn('departments');
		table.showColumn('todo');
		table.showColumn('discontinued');

	    }
	    if(tipo=='stock'){
		Dom.get('stores_period_options').style.display='none';
		Dom.get('stores_avg_options').style.display='none';
		
		table.showColumn('surplus');
		table.showColumn('optimal');
		table.showColumn('low');
		table.showColumn('critcal');
		table.showColumn('stock_error');
		table.showColumn('outofstock');
	    }

	      Dom.removeClass(Dom.getElementsByClassName('table_option','button' , this.parentNode),'selected')
    Dom.addClass(this,"selected");	

	
	//Dom.get(table.view).className="";
	//Dom.get(tipo).className="selected";
	//table.view=tipo
	    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-stores-view&value=' + escape(tipo),{} );
	
  }



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>", width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"departments", label:"<?php echo _('Departments')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"families", label:"<?php echo _('Families')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"active", label:"<?php echo _('Products')?>",  width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"discontinued", label:"<?php echo _('Discontinued')?>",  width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"new", label:"<?php echo _('New')?>",  width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"margin", label:"<?php echo _('Margin')?>", width:120,sortable:false,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"surplus", label:"<?php echo _('Surplus')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"optimal", label:"<?php echo _('OK')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"low", label:"<?php echo _('Low')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"critical", label:"<?php echo _('Critical')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"outofstock", label:"<?php echo _('Gone')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?php echo _('Unknown')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=stores");
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
			 'id',
			 "name",
			 'families','departments',
			 'active',"sales","stock_error","stock_value","outofstock","profit","surplus","optimal","low","critical","code","new","discontinued","margin"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['stores']['stores']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['stores']['stores']['order']?>",
									 dir: "<?php echo$_SESSION['state']['stores']['stores']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table0.doBeforeLoadData=mydoBeforeLoadData;
	    
	    this.table0.view='<?php echo$_SESSION['state']['stores']['stores']['view']?>';
	    this.table0.filter={key:'<?php echo$_SESSION['state']['stores']['stores']['f_field']?>',value:'<?php echo$_SESSION['state']['stores']['stores']['f_value']?>'};



	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
	    				    {key:"store", label:"<?php echo _('Store')?>", width:40,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"code", label:"<?php echo _('Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"families", label:"<?php echo _('Families')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"active", label:"<?php echo _('Products')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				     ,{key:"discontinued", label:"<?php echo _('Discontinued')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"sales", label:"<?php echo _('Sales')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"aws_p", label:"<?php echo _('Aw S/P')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"awp_p", label:"<?php echo _('Aw P/P')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"surplus", label:"<?php echo _('Surplus')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"optimal", label:"<?php echo _('OK')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"low", label:"<?php echo _('Low')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"critical", label:"<?php echo _('Critical')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
	
				    ,{key:"outofstock", label:"<?php echo _('Gone')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?php echo _('Unknown')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sales_type", label:"<?php echo _('Sales Type')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['departments']['view']=='web'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				     ];

	    this.dataSource1 = new YAHOO.util.DataSource("ar_assets.php?tipo=departments&parent=none&tableid=1");
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
			 'id',
			 "name","code","aws_p","awp_p","sales_type","store",
			 'families',
			 'active',"sales","stock_error","stock_value","outofstock","profit","surplus","optimal","low","critical","todo","discontinued"
			 ]};
	    

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo $_SESSION['state']['stores']['departments']['nr']+1?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['stores']['departments']['order']?>",
									 dir: "<?php echo$_SESSION['state']['stores']['departments']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table1.doBeforeLoadData=mydoBeforeLoadData;


	    
	    this.table1.view='<?php echo$_SESSION['state']['stores']['departments']['view']?>';

      this.table1.filter={key:'<?php echo$_SESSION['state']['stores']['departments']['f_field']?>',value:'<?php echo$_SESSION['state']['stores']['departments']['f_value']?>'};




	    var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
	    	    	{key:"store", label:"<?php echo _('Store')?>", width:40,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"code", label:"<?php echo _('Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"store", label:"<?php echo _('Store')?>",hidden:true, width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"department", label:"<?php echo _('Department')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"name", label:"<?php echo _('Name')?>",<?php echo($_SESSION['state']['stores']['families']['view']=='stock'?'hidden:true,':'')?> width:280,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"active", label:"<?php echo _('Products')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['families']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['families']['view']!='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['families']['view']!='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"surplus", label:"<?php echo _('Surplus')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"optimal", label:"<?php echo _('OK')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"low", label:"<?php echo _('Low')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"critical", label:"<?php echo _('Critical')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"outofstock", label:"<?php echo _('Gone')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?php echo _('Unknown')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['stores']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    
				    
				     ];

	    this.dataSource2 = new YAHOO.util.DataSource("ar_assets.php?tipo=families&parent=none&tableid=2");
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
			 'id',
			 "code","store",
			 "name",
			 'active',"stock_error","stock_value","outofstock","sales","profit","surplus","optimal","low","critical","store","department"
			 ]};
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource2, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['families']['table']['nr']+1?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['families']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['families']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table2.view='<?php echo$_SESSION['state']['stores']['families']['view']?>';
	    this.table2.filter={key:'<?php echo$_SESSION['state']['families']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['families']['table']['f_value']?>'};
		


	    var tableid=3;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
	              {key:"store", label:"<?php echo _('Store')?>", width:40,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"code", label:"<?php echo _('Code')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"name", label:"<?php echo _('Name')?>",width:400,<?php echo($_SESSION['state']['stores']['products']['view']!='general'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"smallname", label:"<?php echo _('Name')?>",width:150,<?php echo($_SESSION['state']['stores']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"state", label:"<?php echo _('State')?>",width:100,<?php echo(($_SESSION['state']['stores']['products']['view']!='general')?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"web", label:"<?php echo _('Web')?>",width:100,<?php echo(($_SESSION['state']['stores']['products']['view']!='general')?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      
			      ,{key:"sold", label:"<?php echo _('Sold')?>",width:100,<?php echo($_SESSION['state']['stores']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"sales", label:"<?php echo _('Sales')?>",width:100,<?php echo($_SESSION['state']['stores']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      ,{key:"profit", label:"<?php echo _('Profit')?>",width:100,<?php echo($_SESSION['state']['stores']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      ,{key:"margin", label:"<?php echo _('Margin')?>",width:100,<?php echo($_SESSION['state']['stores']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      ,{key:"stock", label:"<?php echo _('Available')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['stores']['products']['view']=='stock' or $_SESSION['state']['stores']['products']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      ,{key:"parts", label:"<?php echo _('Parts')?>",width:200,<?php echo($_SESSION['state']['stores']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"supplied", label:"<?php echo _('Supplied by')?>",width:200,<?php echo($_SESSION['state']['stores']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['stores']['products']['view']=='parts' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			      ,{key:"family", label:"<?php echo _('Family')?>",width:120,<?php echo($_SESSION['state']['stores']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"dept", label:"<?php echo _('Main Department')?>",width:300,<?php echo($_SESSION['state']['stores']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      ,{key:"expcode", label:"<?php echo _('TC(UK)')?>",width:200,<?php echo($_SESSION['state']['stores']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      

			       ];

	    this.dataSource3 = new YAHOO.util.DataSource("ar_assets.php?tipo=products&parent=none&parent_key=tableid=3");
		//alert("ar_assets.php?tipo=products&parent=none&tableid=3");
	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
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
			 'id'
			 ,"code","store"
			 ,"name","stock","stock_value"
			 ,'sales','profit','margin','sold',"parts","supplied","gmroi","family","dept","expcode","smallname","state","web"
			 ]};
	    
	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource3, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['stores']['products']['nr']+1?>,containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['stores']['products']['order']?>",
									 dir: "<?php echo$_SESSION['state']['stores']['products']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table3.view='<?php echo$_SESSION['state']['stores']['products']['view']?>';
	    this.table3.filter={key:'<?php echo$_SESSION['state']['stores']['products']['f_field']?>',value:'<?php echo$_SESSION['state']['stores']['products']['f_value']?>'};
		





	};
    });

function change_display_mode(parent,name,label){
    if(name=='percentage'){
	var request='&percentages=1';
    }if(name=='value'){
	var request='&percentages=0&show_default_currency=0';
    }if(name=='value_default_d2d'){
	var request='&percentages=0&show_default_currency=1';
    }

  Dom.get('change_'+parent+'_display_mode').innerHTML=label;
 if(parent=='products'){
   var table=tables['table3'];
    var datasource=tables.dataSource3;
    dialog_change_products_display.hide();

    }else if(parent=='families'){
      var table=tables['table2'];
    var datasource=tables.dataSource2;
    dialog_change_families_display.hide();

    }else if(parent=='departments'){
      var table=tables['table1'];
    var datasource=tables.dataSource1;
    dialog_change_departments_display.hide();

    }else if(parent=='store'){
      var table=tables['table0'];
    var datasource=tables.dataSource0;
    dialog_change_stores_display.hide();

    }else{
    return;
    }
    

  
   
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   

}


function change_family_elements(){

ids=['elements_family_discontinued','elements_family_discontinuing','elements_family_normal','elements_family_inprocess','elements_family_nosale'];


if(Dom.hasClass(this,'selected')){

var number_selected_elements=0;
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
number_selected_elements++;
}
}

if(number_selected_elements>1){
Dom.removeClass(this,'selected')

}

}else{
Dom.addClass(this,'selected')

}

table_id=2;
 var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
var request='';
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
request=request+'&'+ids[i]+'=1'
}else{
request=request+'&'+ids[i]+'=0'

}
}
  
 // alert(request)
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       


}


function show_dialog_change_stores_display(){
region1 = Dom.getRegion('change_stores_display_mode'); 
    region2 = Dom.getRegion('change_stores_display_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_stores_display_menu', pos);

dialog_change_stores_display.show();

}

function show_dialog_change_products_display(){
	region1 = Dom.getRegion('change_products_display_mode'); 
    region2 = Dom.getRegion('change_products_display_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_products_display_menu', pos);
	dialog_change_products_display.show();
}

function show_dialog_change_families_display(){
	region1 = Dom.getRegion('change_families_display_mode'); 
    region2 = Dom.getRegion('change_families_display_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_families_display_menu', pos);
	dialog_change_families_display.show();
}

function show_dialog_change_departments_display(){
	region1 = Dom.getRegion('change_departments_display_mode'); 
    region2 = Dom.getRegion('change_departments_display_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_departments_display_menu', pos);
	dialog_change_departments_display.show();
}

function init(){


dialog_change_stores_display = new YAHOO.widget.Dialog("change_stores_display_menu", {visible : false,close:true,underlay: "none",draggable:false});
dialog_change_stores_display.render();
	 YAHOO.util.Event.addListener("change_stores_display_mode", "click", show_dialog_change_stores_display);
 dialog_change_products_display = new YAHOO.widget.Dialog("change_products_display_menu", {visible : false,close:true,underlay: "none",draggable:false});
dialog_change_products_display.render();
	 YAHOO.util.Event.addListener("change_products_display_mode", "click", show_dialog_change_products_display);

dialog_change_families_display = new YAHOO.widget.Dialog("change_families_display_menu", {visible : false,close:true,underlay: "none",draggable:false});
dialog_change_families_display.render();
	 YAHOO.util.Event.addListener("change_families_display_mode", "click", show_dialog_change_families_display);

dialog_change_departments_display = new YAHOO.widget.Dialog("change_departments_display_menu", {visible : false,close:true,underlay: "none",draggable:false});
dialog_change_departments_display.render();
	 YAHOO.util.Event.addListener("change_departments_display_mode", "click", show_dialog_change_departments_display);


 Event.addListener(['elements_family_discontinued','elements_family_discontinuing','elements_family_normal','elements_family_inprocess','elements_family_nosale'], "click",change_family_elements);


    ids=['details','stores','departments','families','products','deals','sites'];
    Event.addListener(ids, "click",change_block);
    
    YAHOO.util.Event.addListener('export_csv0', "click",download_csv,'stores');
    YAHOO.util.Event.addListener('export_csv0_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table0',tipo:'stores'});
    csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {trigger:"export_csv0" });
	csvMenu.render();
	csvMenu.subscribe("show", csvMenu.focus);
    YAHOO.util.Event.addListener('export_csv0_close_dialog', "click",csvMenu.hide,csvMenu,true);
 
    init_search('products');
    
    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);oAutoComp.minQueryLength = 0; 
    YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
    YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);

    var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);oACDS1.queryMatchContains = true; oACDS1.table_id=1;
    var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);oAutoComp1.minQueryLength = 0; 
    YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
    YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
    
 var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);oACDS2.queryMatchContains = true; oACDS2.table_id=2;
    var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);oAutoComp2.minQueryLength = 0; 
    YAHOO.util.Event.addListener('clean_table_filter_show2', "click",show_filter,2);
    YAHOO.util.Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);
    
     var oACDS3 = new YAHOO.util.FunctionDataSource(mygetTerms);oACDS3.queryMatchContains = true; oACDS3.table_id=3;
    var oAutoComp3 = new YAHOO.widget.AutoComplete("f_input3","f_container3", oACDS3);oAutoComp3.minQueryLength = 0; 
    YAHOO.util.Event.addListener('clean_table_filter_show3', "click",show_filter,3);
    YAHOO.util.Event.addListener('clean_table_filter_hide3', "click",hide_filter,3);

    ids=['general','sales','stock'];
    YAHOO.util.Event.addListener(ids, "click",change_view)
    ids=['period_all','period_year','period_quarter','period_month','period_week','period_yeartoday','period_three_year','period_six_month','period_ten_day'];
    YAHOO.util.Event.addListener(ids, "click",change_period,{'table_id':0,'subject':'stores'});
    ids=['avg_totals','avg_month','avg_week',"avg_month_eff","avg_week_eff"];
    YAHOO.util.Event.addListener(ids, "click",change_avg,{'table_id':0,'subject':'stores'});
     YAHOO.util.Event.addListener('show_percentages', "click",show_percentages,'stores');

  
ids=['department_general','department_sales','department_stock'];
 YAHOO.util.Event.addListener(ids, "click",change_department_view,{'table_id':1,'parent':'stores'})
 ids=['department_period_all','department_period_three_year','department_period_year','department_period_yeartoday','department_period_six_month','department_period_quarter','department_period_month','department_period_ten_day','department_period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_period,{'table_id':1,'subject':'department'});
 ids=['department_avg_totals','department_avg_month','department_avg_week',"department_avg_month_eff","department_avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,{'table_id':1,'subject':'department'});

ids=['family_general','family_sales','family_stock'];
 YAHOO.util.Event.addListener(ids, "click",change_family_view,{'table_id':2,'parent':'stores'})
 ids=['family_period_all','family_period_three_year','family_period_year','family_period_yeartoday','family_period_six_month','family_period_quarter','family_period_month','family_period_ten_day','family_period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_period,{'table_id':2,'subject':'family'});
 ids=['family_avg_totals','family_avg_month','family_avg_week',"family_avg_month_eff","family_avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,{'table_id':2,'subject':'family'});

 

ids=['product_general','product_sales','product_stock','product_parts','product_cats'];
 YAHOO.util.Event.addListener(ids, "click",change_product_view,{'table_id':3,'parent':'stores'})
 ids=['product_period_all','product_period_three_year','product_period_year','product_period_yeartoday','product_period_six_month','product_period_quarter','product_period_month','product_period_ten_day','product_period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_period,{'table_id':3,'subject':'product'});
 ids=['product_avg_totals','product_avg_month','product_avg_week',"product_avg_month_eff","product_avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,{'table_id':3,'subject':'product'});


 }

YAHOO.util.Event.onDOMReady(init);

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
    
    
    
YAHOO.util.Event.onContentReady("rppmenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {trigger:"rtext_rpp2" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {  trigger: "filter_name2"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("rppmenu3", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu3", {trigger:"rtext_rpp3" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu3", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu3", {  trigger: "filter_name3"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });    
    
    

  
  
