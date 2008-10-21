<?
include_once('../common.php');
?>
var Dom   = YAHOO.util.Dom;

    var change_view=function(e){

	var table=tables['table0'];
	var tipo=this.id;
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
	table.view=tipo
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-view&value=' + escape(tipo) );
    }



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    this.familyLink=  function(el, oRecord, oColumn, oData) {
		var url="family.php?id="+oRecord.getData("id");
		el.innerHTML = oData.link(url);
	    }
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"name", label:"<?=_('Code')?>", width:50,sortable:true,formatter:this.familyLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"description", label:"<?=_('Description')?>",width:240, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"active", label:"<?=_('Products')?>",  width:100,sortable:true,className:"aright",<?=($_SESSION['state']['department']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"tsall", label:"<?=_('T S')?>", width:90,sortable:true,className:"aright",<?=($_SESSION['state']['department']['view']!='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"per_tsall", label:"<?=_('T %S')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['department']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"tsm", label:"<?=_('30d S')?>", width:90,sortable:true,className:"aright",<?=($_SESSION['state']['department']['view']!='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"per_tsm", label:"<?=_('30d %S')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['department']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_value", label:"<?=_('Stk Value')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['department']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"outofstock", label:"<?=_('Out of Stk ')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['department']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?=_('Stk Error')?>", width:70,sortable:true,className:"aright",<?=($_SESSION['state']['department']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=department");
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
			 "name","description",
			 'active',"tsall","tsq","tsy","tsm","per_tsall","per_tsm","stock_error","stock_value","outofstock"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?=$_SESSION['tables']['departments_list'][2]?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?=$_SESSION['tables']['departments_list'][0]?>",
									 dir: "<?=$_SESSION['tables']['departments_list'][1]?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table0.view='<?=$_SESSION['state']['department']['view']?>';

		





	};
    });


 function init(){
 var Dom   = YAHOO.util.Dom;


 ids=['general','sales','stock'];
 YAHOO.util.Event.addListener(ids, "click",change_view)


     YAHOO.util.Event.addListener('show_details', "click",show_details,'department');





 }

YAHOO.util.Event.onDOMReady(init);

