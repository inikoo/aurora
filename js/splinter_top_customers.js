
top_customers_tables= new Object();


function top_customers_init(){

 ids=['top_customers_50','top_customers_10','top_customers_20'];
 YAHOO.util.Event.addListener(ids, "click",change_number);
 
ids=['top_customers_all','top_customers_1y','top_customers_1m','top_customers_1q'];
 YAHOO.util.Event.addListener(ids, "click",change_period);
 
 
		var tableid=Dom.get('top_customers_index').value;
	    var tableDivEL="table"+tableid;


	    var CustomersColumnDefs = [
				      {key:"position", label:"", width:2,sortable:false,className:"aleft"}
				      ,{key:"name", label:Dom.get('label_Customer_Name').value, width:175,sortable:false,className:"aleft"}
				       ,{key:"last_order", label:Dom.get('label_Last_Order').value,width:70,sortable:false,className:"aright"}
				       ,{key:"invoices", label:Dom.get('label_Invoices').value,sortable:false,className:"aright"}
					      ,{key:"net_balance", label:Dom.get('label_Balance').value,sortable:false,className:"aright"}
				      
				      
				      
				     
				       

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
									      rowsPerPage    : Dom.get('top_customers_nr').value,containers : 'paginator'+tableid, 
 									      pageReportTemplate : '(Page {currentPage} of {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info"+tableid+"'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: Dom.get('top_customers_order').value,
									 dir: 'desc'
								     },
								     dynamicData : true

								  }
								   
								 );
	   
	    top_customers_tables.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    top_customers_tables.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    top_customers_tables.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		  
	
	
	}
YAHOO.util.Event.onDOMReady(top_customers_init);


function change_period(){
var period=this.getAttribute('period');
var tableid=Dom.get('top_customers_index').value;

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
var table=top_customers_tables.table1;
    table.get('paginator').setRowsPerPage(nr)
ids=['top_customers_50','top_customers_10','top_customers_20'];
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');



}





