
var period_ids=['top_customers_all','top_customers_ytd','top_customers_mtd','top_customers_wtd','top_customers_today','top_customers_yesterday','top_customers_last_w','top_customers_last_m','top_customers_3y','top_customers_1y','top_customers_6m','top_customers_1q','top_customers_1m','top_customers_10d','top_customers_1w'];
var  number_records_ids=['top_customers_50','top_customers_10','top_customers_20','top_customers_100'];

top_customers_tables= new Object();


function getDocHeight() {
    var D = document;
    return Math.max(
        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
    );
}


function myrenderEvent(){

parent.Dom.setStyle('block_'+Dom.get('block_key').value,'height',getDocHeight()+'px')

}


function set_title(period){



  Dom.get('period_title').innerHTML=Dom.get('period_title_'+period).value;

  }


function top_customers_init(){
set_title(Dom.get('period').value)

 YAHOO.util.Event.addListener(number_records_ids, "click",change_top_customer_number);
 

 YAHOO.util.Event.addListener(period_ids,"click",change_top_customer_period);


		var tableid=Dom.get('top_customers_index').value;
	    var tableDivEL="table"+tableid;


	    var CustomersColumnDefs = [
				      {key:"position", label:"", width:15,sortable:false,className:"aleft"}
				      	,{key:"name", label:Dom.get('label_Customer_Name').value, width:240,sortable:false,className:"aleft"}
				      	,{key:"location", label:Dom.get('label_Location').value, width:160,sortable:false,className:"aleft"}
				    	,{key:"invoices", label:Dom.get('label_Invoices').value,sortable:false,className:"aright"}
					    ,{key:"net_balance", label:Dom.get('label_Balance').value,sortable:false,className:"aright"}
					   // ,{key:"top_balance", label:'xxx',sortable:false,className:"aright"}
					    ,{key:"last_order", label:Dom.get('label_Last_Order').value,width:80,sortable:false,className:"aright"}
					    ,{key:"status", label:Dom.get('label_Status').value,width:60,sortable:false,className:"aright"}
				      
				      
				     
				       

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
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		
		fields: [
			 'position',
			 'id',
			 'name',
			 'location',
			 'orders',
			 'email',
			 'telephone',
			 'last_order','status',
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
									 key: 'position',
									 dir: 'desc'
								     },
								     dynamicData : true

								  }
								   
								 );
	   
	    top_customers_tables.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    top_customers_tables.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    top_customers_tables.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		     top_customers_tables.table1.table_id=tableid;
		   top_customers_tables.table1.subscribe("renderEvent", myrenderEvent);
		
	
	
	}
YAHOO.util.Event.onDOMReady(top_customers_init);


function change_top_customer_period(){

var period=this.getAttribute('period');
  set_title(period)

var tableid=Dom.get('top_customers_index').value;

var table=top_customers_tables.table1;
    var datasource=top_customers_tables.dataSourcetopcust;
    var request='&period=' + period;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);

Dom.removeClass(period_ids,'selected');
Dom.addClass(this,'selected');

}
function change_top_customer_number(){

var nr=this.getAttribute('nr');
var table=top_customers_tables.table1;
    table.get('paginator').setRowsPerPage(nr)
Dom.removeClass(number_records_ids,'selected');
Dom.addClass(this,'selected');



}





