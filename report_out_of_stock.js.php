<?php include_once('common.php');




?>
var link='report_customers.php';
YAHOO.util.Event.addListener(window, "load", function() {

 var Dom   = YAHOO.util.Dom;

    tables = new function() {



	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				    
				    {key:"sku", label:"<?php echo _('SKU')?>", width:87,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					    ,{key:"used_in", label:"<?php echo _('Products')?>",width:390, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

	,{key:"date", label:"<?php echo _('Date')?>",width:120, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"reporter", label:"<?php echo _('Reporter')?>",width:100, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
								    ,{key:"dn", label:"<?php echo _('Order')?>",width:100, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}


					 ];
	   
	    this.dataSource0 = new YAHOO.util.DataSource("ar_reports.php?tipo=transactions_parts_marked_as_out_of_stock");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    rtext:"resultset.rtext",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			'sku','used_in','date','reporter','dn'
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : top,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									key: "<?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['order']?>",
									 dir: "<?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.view='<?php echo $_SESSION['state']['report_part_out_of_stock']['transactions']['view']?>';

	    this.table0.filter={key:'<?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['f_field']?>',value:'<?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['f_value']?>'};

	    //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	};
    });


function change_criteria(){
 var table=tables['table0'];
      var datasource=tables.dataSource0;
      var request='&o='+this.id;
      var ids=['net_balance','invoices'];
        Dom.removeClass(ids,'selected');
      Dom.addClass(this,'selected');
      datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   

}
function change_top(){
var table=tables['table0'];
      var datasource=tables.dataSource0;
      var request='&nr='+this.getAttribute('top');
      var ids=['top10','top25','top100'];
       Dom.removeClass(ids,'selected');
      Dom.addClass(this,'selected');
      datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   
}

function export_data(){
    o=Dom.get('export');
    output=o.getAttribute('output');
    location.href='export.php?ar_file=ar_reports&tipo=customers&output='+output;
    
}


function init(){
    var ids=['net_balance','invoices'];
    YAHOO.util.Event.addListener(ids, "click", change_criteria);
    var ids=['top10','top25','top100','top200'];
 
   YAHOO.util.Event.addListener(ids, "click", change_top);

   YAHOO.util.Event.addListener('export', "click", export_data);
   //YAHOO.util.Event.addListener('export', "contextmenu", change_export_type,'export');



  var oContextMenu = new YAHOO.widget.ContextMenu("export_menu", {
        trigger: 'export'
    });
 
  
    oContextMenu.render(document.body);





}

YAHOO.util.Event.onDOMReady(init);
