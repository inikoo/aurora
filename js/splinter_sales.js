var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
sales_tables= new Object();
 var period_ids=['mtd','ytd','wtd','1w','10d','1m','1q','1y','3y','last_m','last_w','yesterday','today','6m'];

  function set_title(period){
  Dom.get('period_title').innerHTML=Dom.get('period_title_'+period).value;

  }


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



function sales_init(){

set_title(Dom.get('period').value)

        var tableid=0;
	    var tableDivEL="table"+tableid;
	    var ProductsColumnDefs = [
				       {key:"store", label:Dom.get('label_Store').value, width:150,sortable:false,className:"aleft"}
				       ,{key:"invoices", label:Dom.get('label_Invoices').value, width:90,sortable:false,className:"aright"}
				       ,{key:"invoices_share", label:Dom.get('label_Invoices_Share').value, width:90,sortable:false,className:"aright"}
				       
				       ,{key:"sales", label:Dom.get('label_Sales').value, hidden:(Dom.get('sales_currency').value=='store'?false:true),  width:110,sortable:false,className:"aright"}
				         ,{key:"dc_sales", label:Dom.get('label_Sales').value, hidden:(Dom.get('sales_currency').value=='store'?true:false),width:110,sortable:false,className:"aright"}
				        ,{key:"sales_share", label:Dom.get('label_Sales_Share').value, width:110,sortable:false,className:"aright", hidden:(Dom.get('sales_currency').value=='store'?false:true)}
				      	,{key:"dc_sales_share", label:Dom.get('label_Sales_Share').value, width:110,sortable:false,className:"aright", hidden:(Dom.get('sales_currency').value=='store'?true:false)}

				      ,{key:"invoices_delta", label:Dom.get('label_Invoices_Delta').value, width:110,sortable:false,className:"aright"}
				     ,{key:"sales_delta", label:Dom.get('label_Sales_Delta').value, width:110,sortable:false,className:"aright" ,hidden:(Dom.get('sales_currency').value=='store'?false:true)}
				       ,{key:"dc_sales_delta", label:Dom.get('label_Sales_Delta').value, width:110,sortable:false,className:"aright", hidden:(Dom.get('sales_currency').value=='store'?true:false)}

				        

					 ];
	    sales_tables.dataSourcetopprod = new YAHOO.util.DataSource("ar_splinters.php?tipo=sales&tableid="+tableid);
		//alert("ar_splinters.php?tipo=sales&tableid="+tableid);
	    sales_tables.dataSourcetopprod.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    sales_tables.dataSourcetopprod.connXhrMode = "queueRequests";
	    sales_tables.dataSourcetopprod.responseSchema = {
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
      sales_tables.table1.subscribe("renderEvent", myrenderEvent);
		    

		    sales_tables.table1.subscribe('renderEvent',function() { 
		  
		   if(Dom.get('block_key').value){
		    region=Dom.getRegion('block_table');
		    var elmHeight = region.bottom - region.top;
		 // alert( parent.window.document.getElementById("block_1"))
		  
		    parent.window.document.getElementById("block_"+Dom.get('block_key').value).height = elmHeight+10 ;
		    }
		    })
		 
	   

	    sales_tables.table1.filter={key:'',value:''};
	    
	     ids=['currency_corporate','currency_stores'];
 Event.addListener(ids, "click",change_currency);
 Event.addListener(period_ids, "click",change_period);


 ids=['type_stores','type_invoice_categories'];
 Event.addListener(ids, "click",change_type);


    
}
Event.onDOMReady(sales_init);


function change_currency() {
    if (this.id == 'currency_corporate') {
        Dom.removeClass('currency_stores', 'selected')
        Dom.addClass('currency_corporate', 'selected')
        tipo = 'corporate'

        sales_tables.table1.hideColumn('sales');
        sales_tables.table1.hideColumn('sales_share');
        sales_tables.table1.hideColumn('sales_delta');

        sales_tables.table1.showColumn('dc_sales');
        sales_tables.table1.showColumn('dc_sales_share');
        sales_tables.table1.showColumn('dc_sales_delta');
    } else {
        Dom.addClass('currency_stores', 'selected')
        Dom.removeClass('currency_corporate', 'selected')
        tipo = 'store';
        sales_tables.table1.hideColumn('dc_sales');
        sales_tables.table1.hideColumn('dc_sales_share');
        sales_tables.table1.hideColumn('dc_sales_delta');

        sales_tables.table1.showColumn('sales');
        sales_tables.table1.showColumn('sales_share');
        sales_tables.table1.showColumn('sales_delta');
    }
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=home-splinters-sales-currency&value=' + escape(tipo), {
        success: function(o) {}
    });

}

function change_period() {

    Dom.removeClass(period_ids, 'selected');
    Dom.addClass(this, 'selected');

    period = this.id;

    set_title(period)

    var table = sales_tables.table1;
    var datasource = sales_tables.dataSourcetopprod;
    var request = '&period=' + period;
    //alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function change_type() {

    Dom.removeClass(['type_stores', 'type_invoice_categories'], 'selected');
    Dom.addClass(this, 'selected');

    if (this.id == 'type_stores') type = 'stores'
    else type = 'invoice_categories';

    var table = sales_tables.table1;
    var datasource = sales_tables.dataSourcetopprod;
    var request = '&type=' + type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}



