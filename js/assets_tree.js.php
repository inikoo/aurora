<?
include_once('../common.php');
?>


    var sales_view=function(col){
	var table=tables['table0'];
	if(table.view=='general'){
	    table.hideColumn('active');
	    table.hideColumn('families');
	    table.showColumn('per_tsall');
	    table.showColumn('per_tsm');
// 	    var oColumn = table.getColumn(1);
// 	    table.removeColumn(oColumn); 
// 	    var oColumn = table.getColumn(1);
// 	    table.removeColumn(oColumn); 

// 	    table.insertColumn({key:"active", label:"%T Sales",hidden:false}, 2); 

	    //	    var oColumn = table.getColumn(2);
	    //table.removeColumn(oColumn); 
	    //var myRemovedColumn = table.removeColumn(oColumn); 
	}
    }



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    this.departmentLink=  function(el, oRecord, oColumn, oData) {
		var url="assets_department.php?id="+oRecord.getData("id");
		el.innerHTML = oData.link(url);
	    }
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"name", label:"<?=_('Name')?>", width:200,sortable:true,formatter:this.departmentLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"families", label:"<?=_('Families')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"active", label:"<?=_('Products')?>",  width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"tsall", label:"<?=_('T S')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"per_tsall", label:"<?=_('T %S')?>", width:70,sortable:true,className:"aright",hidden:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"tsm", label:"<?=_('30d S')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"per_tsm", label:"<?=_('30d %S')?>", width:70,sortable:true,className:"aright",hidden:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=index");
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
			 'families',
			 'active',"tsall","tsq","tsy","tsm","per_tsall","per_tsm"
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
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
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
	    this.table0.view='general';

		





	};
    });


 function init(){
 var Dom   = YAHOO.util.Dom;


 YAHOO.util.Event.addListener('sales_view', "click",sales_view)


 }

YAHOO.util.Event.onDOMReady(init);

