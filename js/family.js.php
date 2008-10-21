<?
include_once('../common.php');
?>

    var change_view=function(e){
	tipo=this.id;
	var table=tables['table0'];
	
	//	alert(table.view+' '+tipo)
	if(table.view!=tipo){
	    if(tipo=='sales'){
		table.showColumn('per_tsall');
		table.showColumn('per_tsm');
		
		if(table.view=='general'){
		    table.hideColumn('active');

		}
		if(table.view=='stock'){
		    table.hideColumn('stock_value');
		    table.hideColumn('stock_error');
		    table.hideColumn('outofstock');
		    table.showColumn('tsall');
		    table.showColumn('tsm');
		}
		

	    }
	    if(tipo=='general'){
		  table.showColumn('active');

		if(table.view=='sales'){
		    table.hideColumn('per_tsall');
		    table.hideColumn('per_tsm');
		}
		if(table.view=='stock'){
		    table.hideColumn('stock_value');
		    table.hideColumn('stock_error');
		    table.hideColumn('outofstock');
		    table.showColumn('tsall');
		    table.showColumn('tsm');
		    table.showColumn('active');
		}

	    }
	    if(tipo=='stock'){
		if(table.view=='general'){
		    table.hideColumn('tsall');
		    table.hideColumn('tsm');
		    table.hideColumn('active');
		    table.showColumn('stock_value');
		    table.showColumn('stock_error');
		    table.showColumn('outofstock');
		}
		if(table.view=='sales'){
		    table.hideColumn('tsall');
		    table.hideColumn('tsm');
		    table.hideColumn('per_tsall');
		    table.hideColumn('per_tsm');
		    table.showColumn('stock_value');
		    table.showColumn('stock_error');
		    table.showColumn('outofstock');
		}

	    }

	}
		Dom.get(table.view).className="";
		Dom.get(tipo).className="selected";	
		table.view=tipo;
		YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=family-view&value='+escape(tipo));
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
				    {key:"code", label:"<?=_('Code')?>", width:50,sortable:true,formatter:this.productLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"description", label:"<?=_('Description')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"stock", label:"<?=_('Stoke')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"tsall", label:"<?=_('T S')?>", width:90,sortable:true,className:"aright",<?=($_SESSION['state']['family']['view']!='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"per_tsall", label:"<?=_('T %S')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['family']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"tsm", label:"<?=_('30d S')?>", width:90,sortable:true,className:"aright",<?=($_SESSION['state']['family']['view']!='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"per_tsm", label:"<?=_('30d %S')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['family']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_value", label:"<?=_('Stk Value')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['family']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=family");
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
			 ,"tsall","tsq","tsy","tsm","per_tsall","per_tsm","stock_value"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?=$_SESSION['state']['family']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?=$_SESSION['state']['family']['table']['order']?>",
									 dir: "<?=$_SESSION['state']['family']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table0.view='<?=$_SESSION['state']['family']['view']?>';

		





	};
    });


 function init(){
 var Dom   = YAHOO.util.Dom;



ids=['general','sales','stock'];
 YAHOO.util.Event.addListener(ids, "click",change_view)

     YAHOO.util.Event.addListener('show_details', "click",show_details,'family')




 }

YAHOO.util.Event.onDOMReady(init);