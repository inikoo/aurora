<?php include_once('common.php');

print "var nr=parseInt(".$_SESSION['state']['home']['splinters']['top_customers']['nr'].");";
print "var criteria='".$_SESSION['state']['home']['splinters']['top_customers']['order']."';";
?>

top_customers_tables= new Object();

YAHOO.util.Event.onContentReady("table<?php print $_REQUEST['table_id']?>", function () {



	     //START OF THE TABLE=========================================================================================================================
<?php print "var tableid=".$_REQUEST['table_id'].";";?>

		
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"position", label:"", width:2,sortable:false,className:"aleft"}
				       ,{key:"name", label:"<?php echo _('Customer Name')?>", width:185,sortable:false,className:"aleft"}

				      
				       ,{key:"last_order", label:"<?php echo _('Last Order')?>",width:70,sortable:false,className:"aright"}
				       ,{key:"invoices", label:"<?php echo _('Invoices')?>",sortable:false,className:"aright"}
				    
				       
				     	      
				      ,{key:"net_balance", label:"<?php echo _('Balance')?>",sortable:false,className:"aright"}
				      
				      
				      
				     
				       

					 ];
	    //?tipo=customers&tid=0"
	    top_customers_tables.dataSourcetopcust = new YAHOO.util.DataSource("ar_splinters.php?tipo=customers&tableid="+tableid);
	  //  alert("ar_reports.php?tipo=customers&nr=20&tableid="+tableid)
	    top_customers_tables.dataSourcetopcust.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    top_customers_tables.dataSourcetopcust.connXhrMode = "queueRequests";
	    top_customers_tables.dataSourcetopcust.responseSchema = {
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
			 'id',
			 'name',
			 'location',
			 'orders',
			 'email',
			 'telephone',
			 'last_order','activity',
			 'total_payments','contact_name'
			 ,"address","town","postcode","region","country"
			 ,"ship_address","ship_town","ship_postcode","ship_region","ship_country"
			 ,"total_paymants","total_refunds","net_balance","total_profit","balance"
			 ,"top_orders","top_invoices","top_balance","top_profits","invoices","store"
			 ]};
	    //__You shouls not change anything from here

	    //top_customers_tables.dataSource.doBeforeCallback = mydoBeforeCallback;



	    top_customers_tables.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   top_customers_tables.dataSourcetopcust
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
								     
								     ,sortedBy : {
									 key: criteria,
									 dir: 'desc'
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    top_customers_tables.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    top_customers_tables.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    top_customers_tables.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    top_customers_tables.table1.view='<?php echo$_SESSION['state']['customers']['view']?>';

	    top_customers_tables.table1.filter={key:'<?php echo$_SESSION['state']['customers']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['table']['f_value']?>'};

	    //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:top_customers_tables.table1,datasource:top_customers_tables.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	});


function change_period(){
var period=this.getAttribute('period');
var tableid=<?php print $_REQUEST['table_id']?>;

var table=top_customers_tables.table1;
    var datasource=top_customers_tables.dataSourcetopcust;
    var request='&period=' + period;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);
ids=['top_customers_all','top_customers_1y','top_customers_1m','top_customers_1q'];
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

}
function change_number(){
var nr=this.getAttribute('nr');
var tableid=<?php print $_REQUEST['table_id']?>;

var table=top_customers_tables.table1;
    var datasource=top_customers_tables.dataSourcetopcust;
    var request='&nr=' + nr;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);
ids=['top_customers_50','top_customers_10','top_customers_20'];
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

}
function init(){
 ids=['top_customers_50','top_customers_10','top_customers_20'];
 YAHOO.util.Event.addListener(ids, "click",change_number);
 
ids=['top_customers_all','top_customers_1y','top_customers_1m','top_customers_1q'];
 YAHOO.util.Event.addListener(ids, "click",change_period);
}
YAHOO.util.Event.onDOMReady(init);

