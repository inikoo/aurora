<?
include_once('../common.php');
?>
var Dom   = YAHOO.util.Dom;

 var period='period_<?=$_SESSION['state']['stores']['period']?>';
    var avg='avg_<?=$_SESSION['state']['stores']['avg']?>';

    var change_view=function(e){
	
	var table=tables['table0'];
	var tipo=this.id;
	//	alert(table.view+' '+tipo)
	if(table.view!=tipo){
	    table.hideColumn('active');
	    table.hideColumn('families');
	    table.hideColumn('departments');

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
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-view&value=' + escape(tipo) );
	}
  }



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?=_('Code')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"name", label:"<?=_('Name')?>", width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				
				    ,{key:"departments", label:"<?=_('Departments')?>", width:100,sortable:true,className:"aright",<?=($_SESSION['state']['stores']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    ,{key:"families", label:"<?=_('Families')?>", width:100,sortable:true,className:"aright",<?=($_SESSION['state']['stores']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"active", label:"<?=_('Products')?>",  width:100,sortable:true,className:"aright",<?=($_SESSION['state']['stores']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sales", label:"<?=_('Sales')?>", width:90,sortable:true,className:"aright",<?=($_SESSION['state']['stores']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?=_('Profit')?>", width:90,sortable:true,className:"aright",<?=($_SESSION['state']['stores']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}



				    ,{key:"surplus", label:"<?=_('Surplus')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"optimal", label:"<?=_('OK')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"low", label:"<?=_('Low')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}




				    ,{key:"critical", label:"<?=_('Critical')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    



				    ,{key:"outofstock", label:"<?=_('Gone')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?=_('Unknown')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['stores']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=stores");
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
			 'id',
			 "name",
			 'families','departments',
			 'active',"sales","stock_error","stock_value","outofstock","profit","surplus","optimal","low","critical","code"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?=$_SESSION['state']['stores']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?=$_SESSION['state']['stores']['table']['order']?>",
									 dir: "<?=$_SESSION['state']['stores']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table0.view='<?=$_SESSION['state']['stores']['view']?>';

		





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

 YAHOO.util.Event.addListener('show_details', "click",show_details,'stores');
 YAHOO.util.Event.addListener('show_percentages', "click",show_percentages,'stores');


 YAHOO.util.Event.addListener('product_submit_search', "click",submit_search,'product');
 YAHOO.util.Event.addListener('product_search', "keydown", submit_search_on_enter,'product');




 }

YAHOO.util.Event.onDOMReady(init);

