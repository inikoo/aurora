sales_tables= new Object();
 var period_ids=['mtd','ytd','wtd','1w','10d','1m','1q','1y','3y','last_m','last_w','yesterday','today'];
function sales_init(){

        var tableid=Dom.get('sales_index').value;
	    var tableDivEL="table"+tableid;
	    var ProductsColumnDefs = [
				       {key:"store", label:Dom.get('label_Store').value, width:100,sortable:false,className:"aleft"}
				       ,{key:"invoices", label:Dom.get('label_Invoices').value, width:65,sortable:false,className:"aright"}
				       ,{key:"invoices_share", label:Dom.get('label_Invoices_Share').value, width:65,sortable:false,className:"aright"}
				        ,{key:"sales", label:Dom.get('label_Sales').value, hidden:(Dom.get('sales_currency').value=='store'?false:true),  width:100,sortable:false,className:"aright"}
				         ,{key:"dc_sales", label:Dom.get('label_Sales').value, hidden:(Dom.get('sales_currency').value=='store'?true:false),width:100,sortable:false,className:"aright"}
				        ,{key:"sales_share", label:Dom.get('label_Sales_Share').value, width:65,sortable:false,className:"aright", hidden:(Dom.get('sales_currency').value=='store'?false:true)}
				      	,{key:"dc_sales_share", label:Dom.get('label_Sales_Share').value, width:65,sortable:false,className:"aright", hidden:(Dom.get('sales_currency').value=='store'?true:false)}

				      ,{key:"invoices_delta", label:Dom.get('label_Invoices_Delta').value, width:100,sortable:false,className:"aright"}
				     ,{key:"sales_delta", label:Dom.get('label_Sales_Delta').value, width:100,sortable:false,className:"aright" ,hidden:(Dom.get('sales_currency').value=='store'?false:true)}
				       ,{key:"dc_sales_delta", label:Dom.get('label_Sales_Delta').value, width:100,sortable:false,className:"aright", hidden:(Dom.get('sales_currency').value=='store'?true:false)}

				        

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
			
			 'store','invoices','invoices_share','sales','sales_share','invoices_delta','sales_delta','dc_sales','dc_sales_share','dc_sales_delta'
			 ]};
			 
		
	 
	
		//sales_tables.dataSourcetopprod.setInterval(5000, null, polling_datatable2); 	
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
	    
	     ids=['currency_corporate','currency_stores'];
 YAHOO.util.Event.addListener(ids, "click",change_currency);
 YAHOO.util.Event.addListener(period_ids, "click",change_period);

  
}



YAHOO.util.Event.onDOMReady(sales_init);


function change_currency(){
if(this.id=='currency_corporate'){
Dom.removeClass('currency_stores','selected')
Dom.addClass('currency_corporate','selected')
tipo='corporate'

 sales_tables.table1.hideColumn('sales');
 sales_tables.table1.hideColumn('sales_share');
  sales_tables.table1.hideColumn('sales_delta');

  sales_tables.table1.showColumn('dc_sales');
  sales_tables.table1.showColumn('dc_sales_share');
  sales_tables.table1.showColumn('dc_sales_delta');
}else{
Dom.addClass('currency_stores','selected')
Dom.removeClass('currency_corporate','selected')
tipo='store';
 sales_tables.table1.hideColumn('dc_sales');
 sales_tables.table1.hideColumn('dc_sales_share');
  sales_tables.table1.hideColumn('dc_sales_delta');

  sales_tables.table1.showColumn('sales');
  sales_tables.table1.showColumn('sales_share');
  sales_tables.table1.showColumn('sales_delta');
}
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=home-splinters-sales-currency&value=' + escape(tipo) ,{success:function(o) {}});

}

function change_period(){

Dom.removeClass(period_ids,'selected');
Dom.addClass(this,'selected');

period=this.id;



var table=sales_tables.table1;
    var datasource=sales_tables.dataSourcetopprod;
       
    var request='&period=' + period;
  
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);


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
//YAHOO.util.Event.onDOMReady(init);
