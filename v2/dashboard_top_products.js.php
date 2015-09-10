<?php include_once('common.php');

print "var nr=parseInt(".$_SESSION['state']['home']['splinters']['top_products']['nr'].");";
print "var criteria='".$_SESSION['state']['home']['splinters']['top_products']['order']."';";

?>
top_products_tables= new Object();

YAHOO.util.Event.onContentReady("table<?php print $_REQUEST['table_id']?>", function () {

 

	     //START OF THE TABLE=========================================================================================================================

		<?php print "var tableid=".$_REQUEST['table_id'].";";?>

	    var tableDivEL="table"+tableid;


	    var CustomersColumnDefs = [
				      // {key:"position", label:"", width:2,sortable:false,className:"aleft"} ,
				       {key:"family", label:"<?php echo _('Fam')?>", width:25,sortable:false,className:"aleft"}
				      // ,{key:"code", label:"<?php echo _('Code')?>", width:45,sortable:false,className:"aleft"}
				       ,{key:"description", label:"<?php echo _('Product')?>", width:280,sortable:false,className:"aleft"}

				       //,{key:"net_sales", label:"<?php echo _('Sales')?>", width:65,sortable:false,className:"aright"}

				      
				     
				      
				      
				     
				       

					 ];
	    //?tipo=customers&tid=0"
	    top_products_tables.dataSourcetopprod = new YAHOO.util.DataSource("ar_splinters.php?tipo=products&tableid="+tableid);
	  //  alert("ar_reports.php?tipo=customers&nr=20&tableid="+tableid)
	    top_products_tables.dataSourcetopprod.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    top_products_tables.dataSourcetopprod.connXhrMode = "queueRequests";
	    top_products_tables.dataSourcetopprod.responseSchema = {
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
			 'position',
			 'store','family','code','description','net_sales'
			 ]};
	    //__You shouls not change anything from here

	    //top_products_tables.dataSource.doBeforeCallback = mydoBeforeCallback;


	    top_products_tables.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   top_products_tables.dataSourcetopprod
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : nr,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								   //  ,sortedBy : {
									// key: criteria,
									// dir: 'desc'
								    // }
								     ,dynamicData : true

								  }
								   
								 );
	    
	    top_products_tables.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    top_products_tables.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    top_products_tables.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	   

	    top_products_tables.table1.filter={key:'',value:''};

	});

function change_period(){
var period=this.getAttribute('period');
var tableid=<?php print $_REQUEST['table_id']?>;

var table=top_products_tables.table1;
    var datasource=top_products_tables.dataSourcetopprod;
    var request='&period=' + period;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);
ids=['top_products_all','top_products_1y','top_products_1m','top_products_1q'];
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

}
function change_number(){
var nr=this.getAttribute('nr');
var tableid=<?php print $_REQUEST['table_id']?>;

var table=top_products_tables.table1;
    var datasource=top_products_tables.dataSourcetopprod;
    var request='&nr=' + nr;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);
ids=['top_products_50','top_products_10','top_products_20'];
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

}
function init(){
 ids=['top_products_50','top_products_10','top_products_20'];
 YAHOO.util.Event.addListener(ids, "click",change_number);
 
ids=['top_products_all','top_products_1y','top_products_1m','top_products_1q'];
 YAHOO.util.Event.addListener(ids, "click",change_period);
}
YAHOO.util.Event.onDOMReady(init);
