<?php
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var LocationsColumnDefs = [
	    			{key:"score", hidden:true,label:"<?php echo _('Score')?>", width:60,sortable:false,className:"aleft"},
	    			{key:"store", label:"<?php echo _('Store')?>", width:60,sortable:false,className:"aleft"},
				    {key:"subject", label:"<?php echo _('Type')?>", width:100,sortable:false,className:"aleft"},
				 //   {key:"result", label:"<?php echo _('Subject')?>", width:100,sortable:false,className:"aleft"},
				    {key:"description", label:"<?php echo _('Description')?>", width:552,sortable:false,className:"aleft"}
				      
				];
	    //?tipo=locations&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_search.php?tipo=search&q="+Dom.get('all_search').value);
	  
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    rtext:"resultset.rtext",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		fields: [
			 "subject","description","score","store","result"
			 ]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, LocationsColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['search']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['search']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['search']['table']['order_dir']?>"
								     },
								     dynamicData : true
								  }
								 );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['search']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['search']['table']['f_value']?>'};
	    
	
	

	};
    });





function submit_search(e){



    var table=tables.table0;
    var datasource=tables.dataSource0;

    var request='&sf=0&q=' +Dom.get('all_search').value;
    

   
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);     

}
function submit_search_on_enter(e){
     var key;     
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox     

     if (key == 13)
	 submit_search(e);
};

function init(){




YAHOO.util.Event.addListener('submit_search', "click",submit_search);

YAHOO.util.Event.addListener('all_search', "keyup",submit_search_on_enter);


}

YAHOO.util.Event.onDOMReady(init);
