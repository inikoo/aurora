sales_tables= new Object();

function sales_init(){

        var tableid=Dom.get('sales_index').value;
	    var tableDivEL="table"+tableid;
	    var ProductsColumnDefs = [
				       {key:"store", label:Dom.get('label_Store').value, width:100,sortable:false,className:"aleft"}
				       ,{key:"invoices", label:Dom.get('label_Invoices').value, width:65,sortable:false,className:"aright"}
				       ,{key:"invoices_share", label:Dom.get('label_Invoices_Share').value, width:65,sortable:false,className:"aright"}
				        ,{key:"sales", label:Dom.get('label_Sales').value, width:65,sortable:false,className:"aright"}
				        ,{key:"sales_share", label:Dom.get('label_Sales_Share').value, width:65,sortable:false,className:"aright"}
				       ,{key:"invoices_delta", label:Dom.get('label_Invoices_Delta').value, width:100,sortable:false,className:"aright"}
				        ,{key:"sales_delta", label:Dom.get('label_Sales_Delta').value, width:100,sortable:false,className:"aright"}
				        
				        

					 ];
	    sales_tables.dataSourcetopprod = new YAHOO.util.DataSource("ar_splinters.php?tipo=sales&tableid="+tableid);
	    sales_tables.dataSourcetopprod.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    sales_tables.dataSourcetopprod.connXhrMode = "queueRequests";
	    sales_tables.dataSourcetopprod.responseSchema = {
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
			
			 'store','invoices','invoices_share','sales','sales_share','invoices_delta','sales_delta'
			 ]};
		    sales_tables.table1 = new YAHOO.widget.DataTable(tableDivEL, ProductsColumnDefs,
								   sales_tables.dataSourcetopprod
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      
								     
								    ,sortedBy : {
									 key: 'store',
									 dir: 'desc'
								     }
								     ,dynamicData : true

								  }
								   
								 );
	    
	    sales_tables.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    sales_tables.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    sales_tables.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	   

	    sales_tables.table1.filter={key:'',value:''};

}



YAHOO.util.Event.onDOMReady(sales_init);


function change_product_period(){
stores_keys=Dom.get('store_keys').value;
var period=this.getAttribute('period');
var tableid=Dom.get('sales_index').value



var table=sales_tables.table1;
    var datasource=sales_tables.dataSourcetopprod;
       
    var request='&period=' + period;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);

ids=['sales_all','sales_1y','sales_1m','sales_1q'];
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
Dom.get('ampie').reloadData('plot_data.csv.php?tipo=top_families&store_keys='+stores_keys+'&period='+period); 


}
function change_product_number(){

var nr=this.getAttribute('nr');
var table=sales_tables.table1;
    table.get('paginator').setRowsPerPage(nr)

ids=['sales_50','sales_10','sales_20'];
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

}
function init(){
 ids=['sales_50','sales_10','sales_20'];
 YAHOO.util.Event.addListener(ids, "click",change_product_number);
 
ids=['sales_all','sales_1y','sales_1m','sales_1q'];
 YAHOO.util.Event.addListener(ids, "click",change_product_period);
}
YAHOO.util.Event.onDOMReady(init);
