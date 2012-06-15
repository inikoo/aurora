top_products_tables= new Object();

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



function top_products_init(){
var tableid=1

        var tableid=Dom.get('top_products_index').value;
	    var tableDivEL="table"+tableid;
	    var ProductsColumnDefs = [
				       {key:"position", label:"", width:2,sortable:false,className:"aleft"}
				       ,{key:"family", label:Dom.get('label_Fam').value, width:25,sortable:false,className:"aleft"}
				       ,{key:"description", label:Dom.get('label_Product').value, width:280,sortable:false,className:"aleft"}
				       ,{key:"net_sales", label:Dom.get('label_Sales').value, width:65,sortable:false,className:"aright"}
					 ];
	  
	 // alert("ar_splinters.php?tipo=products&type="+Dom.get('top_products_type').value+"&tableid="+tableid)
	  top_products_tables.dataSourcetopprod = new YAHOO.util.DataSource("ar_splinters.php?tipo=products&type="+Dom.get('top_products_type').value+"&tableid="+tableid);
	    top_products_tables.dataSourcetopprod.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    top_products_tables.dataSourcetopprod.connXhrMode = "queueRequests";
	    top_products_tables.dataSourcetopprod.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
	 rowsPerPage:"resultset.records_perpage",
		    RecordOffset : "resultset.records_offset", 
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
			 'store','family','code','description','net_sales'
			 ]};
		    top_products_tables.table1 = new YAHOO.widget.DataTable(tableDivEL, ProductsColumnDefs,
								   top_products_tables.dataSourcetopprod
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : Dom.get('top_products_nr').value,containers : 'paginator'+tableid, 
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
								     }
								     ,dynamicData : true

								  }
								   
								 );
	    
	    top_products_tables.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    top_products_tables.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    top_products_tables.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
  top_products_tables.table1.table_id=tableid;
		    top_products_tables.table1.subscribe("renderEvent", myrenderEvent);
		    
	   

	    top_products_tables.table1.filter={key:'',value:''};

}



YAHOO.util.Event.onDOMReady(top_products_init);


function change_product_period(){
stores_keys=Dom.get('store_keys').value;
var period=this.getAttribute('period');
var tableid=Dom.get('top_products_index').value



var table=top_products_tables.table1;
    var datasource=top_products_tables.dataSourcetopprod;
       
    var request='&period=' + period;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);

ids=['top_products_all','top_products_1y','top_products_1m','top_products_1q'];
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
Dom.get('ampie').reloadData('plot_data.csv.php?tipo=top_families&store_keys='+stores_keys+'&period='+period); 
//alert ('plot_data.csv.php?tipo=top_families&store_keys='+stores_keys+'&period='+period)

}

function change_product_type(){
Dom.removeClass(['top_products_fam','top_products_products'],'selected');
Dom.addClass(this,'selected');

if(this.id=='top_products_fam')
type='families'
else
type='products';

var table=top_products_tables.table1;
    var datasource=top_products_tables.dataSourcetopprod;
    var request='&type=' + type;
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);
}

function change_product_number(){

var nr=this.getAttribute('nr');
var table=top_products_tables.table1;
    table.get('paginator').setRowsPerPage(nr)

ids=['top_products_50','top_products_10','top_products_20'];
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

}
function init(){


 ids=['top_products_50','top_products_10','top_products_20'];
 YAHOO.util.Event.addListener(ids, "click",change_product_number);
 
ids=['top_products_all','top_products_1y','top_products_1m','top_products_1q'];
 YAHOO.util.Event.addListener(ids, "click",change_product_period);
 
 
 ids=['top_products_fam','top_products_products'];
 YAHOO.util.Event.addListener(ids, "click",change_product_type);
 
 
}
YAHOO.util.Event.onDOMReady(init);
