<?
include_once('../common.php');
?>

    var change_view=function(e){
	tipo=this.id;
	var table=tables['table0'];
	
	table.hideColumn('description');
	table.hideColumn('stock');
	table.hideColumn('stock_value');
	table.hideColumn('sold');
	table.hideColumn('given');
	table.hideColumn('money_in');
	table.hideColumn('profit');
	table.hideColumn('profit_sold');
	table.hideColumn('available_for');
	table.hideColumn('awtsy');
	table.hideColumn('awtsm');
	table.hideColumn('tsoall');
	table.hideColumn('tsoy');
	table.hideColumn('tsom');
	table.hideColumn('tsow');
	table.hideColumn('awtsoall');
	table.hideColumn('awtsoy');
	table.hideColumn('awtsom');
	table.hideColumn('days_to_ns');
	if(tipo=='sales'){
	    table.showColumn('sold');
	    table.showColumn('given');
	    table.showColumn('money_in');
	    table.showColumn('profit');
	    table.showColumn('profit_sold');

	}if(tipo=='sales_outers'){
	    table.showColumn('tsoall');
	    table.showColumn('tsoy');
	    table.showColumn('tsom');
	    table.showColumn('tsow');
	    table.showColumn('awtsoall');
	    table.showColumn('awtsoy');
	    table.showColumn('awtsom');
	}

	else if(tipo=='general'){
	    table.showColumn('description');
	    table.showColumn('tsall');
	    table.showColumn('awtsoall');
	    table.showColumn('awtsall');
	    table.showColumn('stock');
	    table.showColumn('days_to_ns');
	}else if(tipo=='stock'){
	    table.showColumn('stock');
	    table.showColumn('stock_value');
	    table.showColumn('awtdm');
	    table.showColumn('days_to_ns');
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
				    {key:"sku", label:"<?=_('SKU')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    
				    ,{key:"used_in", label:"<?=_('Used In')?>",width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"description", label:"<?=_('Description')?>",width:280,<?=($_SESSION['state']['parts']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"stock", label:"<?=_('Stock')?>", width:70,sortable:true,className:"aright",<?=(($_SESSION['state']['parts']['view']=='stock' or $_SESSION['state']['parts']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"available_for", label:"<?=_('S Until')?>", width:70,sortable:true,className:"aright",<?=(($_SESSION['state']['parts']['view']=='stock' or $_SESSION['state']['parts']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"stock_value", label:"<?=_('Stk Value')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sold", label:"<?=_('Sold(Given) Qty')?>", width:100,sortable:true,className:"aright",<?=($_SESSION['state']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //   ,{key:"given", label:"<?=_('Given Qty')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['parts']['view']=='sale'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}} 
				    ,{key:"money_in", label:"<?=_('Sold')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    //    ,{key:"profit", label:"<?=_('Profit Out')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['parts']['view']=='sale'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit_sold", label:"<?=_('Profit (Inc Given)')?>", width:160,sortable:true,className:"aright",<?=($_SESSION['state']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"margin", label:"<?=_('Margin')?>", width:160,sortable:true,className:"aright",<?=($_SESSION['state']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=parts");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
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
			 ,"stock","available_for","stock_value","sold","given","money_in","profit","profit_sold","used_in","supplied_by"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?=$_SESSION['state']['parts']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?=$_SESSION['state']['parts']['table']['order']?>",
									 dir: "<?=$_SESSION['state']['parts']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table0.view='<?=$_SESSION['state']['parts']['view']?>';
	    this.table0.filter={key:'<?=$_SESSION['state']['parts']['table']['f_field']?>',value:'<?=$_SESSION['state']['parts']['table']['f_value']?>'};
		





	};
    });


 function init(){
 var Dom   = YAHOO.util.Dom;
 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 



 ids=['general','sales','stock','sales_outers'];
 YAHOO.util.Event.addListener(ids, "click",change_view,'parts');
 
 YAHOO.util.Event.addListener('show_details', "click",show_details,'parts')
     
 YAHOO.util.Event.addListener('submit_search', "click",submit_search);
 YAHOO.util.Event.addListener('prod_search', "keydown", submit_search_on_enter);

 Event.addListener('submit_search', "click",submit_search);
 Event.addListener('prod_search', "keydown", submit_search_on_enter);

 }

YAHOO.util.Event.onDOMReady(init);



YAHOO.util.Event.onContentReady("rppmenu", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu", { context:["paginator0","tr", "bl"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("paginator_info0", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("filtermenu", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu", { context:["filter_name0","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });
