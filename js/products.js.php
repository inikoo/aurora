<?
include_once('../common.php');
?>
 var period='period_<?=$_SESSION['state']['products']['period']?>';
    var avg='avg_<?=$_SESSION['state']['products']['avg']?>';

    var change_view=function(e){
	tipo=this.id;
	var table=tables['table0'];
	
	table.hideColumn('name');
	table.hideColumn('stock');
	table.hideColumn('stock_value');
	table.hideColumn('sales');
	table.hideColumn('profit');
	if(tipo=='sales'){
	    table.showColumn('sales');
	    table.showColumn('profit');
	}if(tipo=='sales_outers'){
	    table.showColumn('stock');
	    table.showColumn('stock_value');
	}

	else if(tipo=='general'){
	    table.showColumn('name');

	}else if(tipo=='stock'){
	    table.showColumn('stock');
	    table.showColumn('stock_value');

	}



	Dom.get(table.view).className="";
	Dom.get(tipo).className="selected";	
	table.view=tipo;
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=products-view&value='+escape(tipo));
    }



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
				    {key:"code", label:"<?=_('Code')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?=_('Name')?>",width:400,<?=($_SESSION['state']['products']['view']!='general'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:"<?=_('Sales')?>",width:100,<?=($_SESSION['state']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"profit", label:"<?=_('Profit')?>",width:100,<?=($_SESSION['state']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"stock", label:"<?=_('Available')?>", width:70,sortable:true,className:"aright",<?=(($_SESSION['state']['products']['view']=='stock' or $_SESSION['state']['products']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    

				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=products");
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
			 'id'
			 ,"code"
			 ,"name","stock","stock_value"
			 ,'sales','profit'
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
	    this.table0.filter={key:'<?=$_SESSION['state']['products']['table']['f_field']?>',value:'<?=$_SESSION['state']['products']['table']['f_value']?>'};
		





	};
    });


 function init(){
 var Dom   = YAHOO.util.Dom;
 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 



 ids=['general','sales','stock','sales_outers'];
 YAHOO.util.Event.addListener(ids, "click",change_view);
 ids=['period_all','period_year','period_quarter','period_month','period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_period,0);
 ids=['avg_totals','avg_month','avg_week',"avg_month_eff","avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,0);
 
 YAHOO.util.Event.addListener('show_details', "click",show_details,'products')
     
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
