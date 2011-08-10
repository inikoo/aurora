<?php
include_once('common.php');
?>

      YAHOO.util.Event.addListener(window, "load", function() {
	      tables = new function() {
		   
		      var tableid=2;
		      var tableDivEL="table"+tableid;
		      var ColumnDefs = [
					{key:"id", label:"<?php echo _('Product ID')?>", width:90,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"store", label:"<?php echo _('Store')?>", width:30,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
										,{key:"parts", label:"<?php echo _('Parts')?>",width:100,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					,{key:"description", label:"<?php echo _('Description')?>", sortable:true, width:400,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					];
		      
		      
		      this.dataSource2 = new YAHOO.util.DataSource("ar_assets.php?tipo=product_server&tableid="+tableid);
		      this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
		      this.dataSource2.connXhrMode = "queueRequests";
		      this.dataSource2.responseSchema = {
			  resultsList: "resultset.data", 
			  metaFields: {
			      rowsPerPage:"resultset.records_perpage",
			      rtext:"resultset.rtext",
			      sort_key:"resultset.sort_key",
			      sort_dir:"resultset.sort_dir",
			      tableid:"resultset.tableid",
			      filter_msg:"resultset.filter_msg",
			      totalRecords: "resultset.total_records"
			  },
			
			  fields: [
				   "id","store","description","parts"
				   ]};
		      
		      this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							       this.dataSource2, {
								   //draggableColumns:true,
								   renderLoopSize: 50,generateRequest : myRequestBuilder
								   ,paginator : new YAHOO.widget.Paginator({
									 rowsPerPage:<?php echo $_SESSION['state']['product']['server']['nr']?>,containers : 'paginator2', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
									 firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								       })
								   
								   ,sortedBy : {
								      key: "<?php echo $_SESSION['state']['product']['server']['order']?>",
								       dir: "<?php echo $_SESSION['state']['product']['server']['order_dir']?>"
								   }
								   ,dynamicData : true
								   
							     }
							       );
		      this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
		      this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
		      this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;

	    
	    };
    });


function init(){
 init_search('products');


    
    
}

YAHOO.util.Event.onDOMReady(init);
