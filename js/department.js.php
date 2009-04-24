<?
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../common.php');
?>
var Dom   = YAHOO.util.Dom;
 var period='period_<?=$_SESSION['state']['families']['period']?>';
    var avg='avg_<?=$_SESSION['state']['families']['avg']?>';

    var change_view=function(e){

	var table=tables['table0'];
	var tipo=this.id;
	//	alert(table.view+' '+tipo)
	if(table.view!=tipo){
	    table.hideColumn('stock_value');
	     table.hideColumn('stock_error');
	     table.hideColumn('outofstock');
	     table.hideColumn('active'); table.hideColumn('discontinued');
	     table.hideColumn('sales');
	     table.hideColumn('profit');
	     table.hideColumn('todo');

	    if(tipo=='sales'){
		table.showColumn('profit');
		table.showColumn('sales');
			Dom.get('period_options').style.display='';
		Dom.get('avg_options').style.display='';

	    }else if(tipo=='general'){
		table.showColumn('active');
		table.showColumn('todo');	table.showColumn('discontinued');
		Dom.get('period_options').style.display='none';
		Dom.get('avg_options').style.display='none';
	    }else if(tipo=='stock'){
		    table.showColumn('stock_value');
		    table.showColumn('stock_error');
		    table.showColumn('outofstock');
		    	Dom.get('period_options').style.display='';
		Dom.get('avg_options').style.display='none';
	    }
	    

	    Dom.get(table.view).className="";
	    Dom.get(tipo).className="selected";
	    table.view=tipo
		YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=families-view&value=' + escape(tipo) );
	}
    }



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?=_('Code')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?=_('Name')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"active", label:"<?=_('Products')?>",  width:100,sortable:true,className:"aright",<?=($_SESSION['state']['families']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"discontinued", label:"<?=_('Discontinued')?>",  width:100,sortable:true,className:"aright",<?=($_SESSION['state']['families']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"todo", label:"<?=_('To do')?>",  width:100,sortable:true,className:"aright",<?=($_SESSION['state']['families']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    ,{key:"sales", label:"<?=_('Sales')?>", width:90,sortable:true,className:"aright",<?=($_SESSION['state']['families']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?=_('Profit')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['families']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_value", label:"<?=_('Stk Value')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"outofstock", label:"<?=_('Out of Stk ')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?=_('Stk Error')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=families&parent=department");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
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
			 "code",
			 "name",
			 'active',"stock_error","stock_value","outofstock","sales","profit","todo","discontinued","notforsale"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?=$_SESSION['state']['families']['table']['nr']+1?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									  key: "<?=$_SESSION['state']['families']['table']['order']?>",
									  dir: "<?=$_SESSION['state']['families']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table0.view='<?=$_SESSION['state']['families']['view']?>';

		





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
    // alert(request);
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
 var Dom   = YAHOO.util.Dom;


 ids=['general','sales','stock'];
 YAHOO.util.Event.addListener(ids, "click",change_view)
 ids=['period_all','period_year','period_quarter','period_month','period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_period,0);
 ids=['avg_totals','avg_month','avg_week',"avg_month_eff","avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,0);

     YAHOO.util.Event.addListener('show_details', "click",show_details,'department');

 YAHOO.util.Event.addListener('product_submit_search', "click",submit_search,"product");
 YAHOO.util.Event.addListener('product_search', "keydown", submit_search_on_enter,"product");

 YAHOO.util.Event.addListener('show_percentages', "click",show_percentages,'department');



 }

YAHOO.util.Event.onDOMReady(init);

