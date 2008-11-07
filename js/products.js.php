<?
include_once('../common.php');
?>

    var change_view=function(e){
	tipo=this.id;
	var table=tables['table0'];
	
	table.hideColumn('description');
	table.hideColumn('stock');
	table.hideColumn('stock_value');
	table.hideColumn('awtdm');
	table.hideColumn('tsall');
	table.hideColumn('tsy');
	table.hideColumn('tsm');
	table.hideColumn('tsw');
	table.hideColumn('awtsall');
	table.hideColumn('awtsy');
	table.hideColumn('awtsm');
	table.hideColumn('tsoall');
	table.hideColumn('tsoy');
	table.hideColumn('tsom');
	table.hideColumn('tsow');
	table.hideColumn('awtsoall');
	table.hideColumn('awtsoy');
	table.hideColumn('awtsom');

	if(tipo=='sales'){
	    table.showColumn('tsall');
	    table.showColumn('tsy');
	    table.showColumn('tsm');
	    table.showColumn('tsw');
	    table.showColumn('awtsall');
	    table.showColumn('awtsy');
	    table.showColumn('awtsm');
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
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=products-view&value='+escape(tipo));
    }



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    this.productLink=  function(el, oRecord, oColumn, oData) {
		var url="product.php?id="+oRecord.getData("id");
		el.innerHTML = oData.link(url);
	    }
	    var tableid=0;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
				    {key:"code", label:"<?=_('Code')?>", width:70,sortable:true,formatter:this.productLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"description", label:"<?=_('Description')?>",width:280,<?=($_SESSION['state']['products']['view']!='general'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"stock", label:"<?=_('Stock')?>", width:70,sortable:true,className:"aright",<?=(($_SESSION['state']['products']['view']=='stock' or $_SESSION['state']['products']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"days_to_ns", label:"<?=_('S Until')?>", width:70,sortable:true,className:"aright",<?=(($_SESSION['state']['products']['view']=='stock' or $_SESSION['state']['products']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"stock_value", label:"<?=_('Stk Value')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['products']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"awtdm", label:"<?=_('30d awO')?>", width:90,sortable:true,className:"aright",<?=(($_SESSION['state']['products']['view']=='stock'  ) ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    ,{key:"tsall", label:"<?=_('T S')?>", width:90,sortable:true,className:"aright",<?=(($_SESSION['state']['products']['view']=='sales'  or $_SESSION['state']['products']['view']=='general') ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"tsy", label:"<?=_('1y S')?>", width:90,sortable:true,className:"aright",<?=($_SESSION['state']['products']['view']!='sales'?'hidden:true,':'')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"tsm", label:"<?=_('30d S')?>", width:90,sortable:true,className:"aright",<?=($_SESSION['state']['products']['view']!='sales'?'hidden:true,':'')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}} 
				    ,{key:"tsw", label:"<?=_('1w S')?>", width:90,sortable:true,className:"aright",<?=($_SESSION['state']['products']['view']!='sales'?'hidden:true,':'')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"awtsall", label:"<?=_('T awS')?>", width:90,sortable:true,className:"aright",<?=(($_SESSION['state']['products']['view']=='sales'  or $_SESSION['state']['products']['view']=='general') ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"awtsy", label:"<?=_('1y awS')?>", width:90,sortable:true,className:"aright",<?=(($_SESSION['state']['products']['view']=='sales'  ) ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"awtsm", label:"<?=_('30d awS')?>", width:90,sortable:true,className:"aright",<?=(($_SESSION['state']['products']['view']=='sales'  ) ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"tsoall", label:"<?=_('T OS')?>", width:90,sortable:true,className:"aright",<?=(($_SESSION['state']['products']['view']=='sales_outers' ) ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"tsoy", label:"<?=_('1y OS')?>", width:90,sortable:true,className:"aright",<?=($_SESSION['state']['products']['view']!='sales_outers'?'hidden:true,':'')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"tsom", label:"<?=_('30d OS')?>", width:90,sortable:true,className:"aright",<?=($_SESSION['state']['products']['view']!='sales_outers'?'hidden:true,':'')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}} 
				    ,{key:"tsow", label:"<?=_('1w OS')?>", width:90,sortable:true,className:"aright",<?=($_SESSION['state']['products']['view']!='sales_outers'?'hidden:true,':'')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"awtsoall", label:"<?=_('T awOS')?>", width:90,sortable:true,className:"aright",<?=(($_SESSION['state']['products']['view']=='sales_outers'  or $_SESSION['state']['products']['view']=='general') ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"awtsoy", label:"<?=_('1y awOS')?>", width:90,sortable:true,className:"aright",<?=(($_SESSION['state']['products']['view']=='sales_outers'  ) ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"awtsom", label:"<?=_('30d awOS')?>", width:90,sortable:true,className:"aright",<?=(($_SESSION['state']['products']['view']=='sales_outers'  ) ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    

				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=products");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'id'
			 ,"code"
			 ,"description","stock"
			 ,"tsall","tsq","tsy","tsm","stock_value","tsy","tsw","awtsall","awtsy","awtsm","awtdm","tsoall","tsoq","tsoy","tsom","tsoy","tsow","awtsoall","awtsoy","awtsom","days_to_ns"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?=$_SESSION['state']['products']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?=$_SESSION['state']['products']['table']['order']?>",
									 dir: "<?=$_SESSION['state']['products']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table0.view='<?=$_SESSION['state']['products']['view']?>';

		





	};
    });


 function init(){
 var Dom   = YAHOO.util.Dom;



 ids=['general','sales','stock','sales_outers'];
 YAHOO.util.Event.addListener(ids, "click",change_view);
 
 YAHOO.util.Event.addListener('show_details', "click",show_details,'products')
     
 YAHOO.util.Event.addListener('submit_search', "click",submit_search);
 YAHOO.util.Event.addListener('prod_search', "keydown", submit_search_on_enter);



 }

YAHOO.util.Event.onDOMReady(init);



YAHOO.util.Event.onContentReady("rppmenu", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu", { context:["paginator0","tr", "bl"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("paginator_info0", "click", oMenu.show, null, oMenu);
    });