<?php
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;
var dialog_new_list;
    


function close_dialog_new_list(){
dialog_new_list.hide();
}

function new_list(store_key){
    location.href='new_customers_list.php?store_key='+store_key;
}


function show_dialog_new_list(){
if(Dom.get('direct_store_key').value){
        location.href='new_customers_list.php?store_key='+Dom.get('direct_store_key').value;

}else{
    dialog_new_list.show();
}
}



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {





	     //START OF THE TABLE =========================================================================================================================

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
                                        {key:"customer_list_name", label:"<?php echo _('List Name')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                     ,{key:"customer_list_creation_date", label:"<?php echo _('List Created')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                       // ,{key:"id", label:"<?php echo _('Customer Id')?>",  width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					//,{key:"no_of_customer", label:"<?php echo _('No. Of Customer')?>",  width:180,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"customer_list_type", label:"<?php echo _('List Type')?>",  width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Customer Name')?>", width:190,sortable:true,hidden:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                       ,{key:"customer_list_key", label:"<?php echo _('Create Campaign')?>", width:155,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   ];
	    //?tipo=customers&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_customers_list.php?tipo=customers_lists");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",  rtext:"resultset.rtext",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		fields: ["customer_list_name","customer_list_key","customer_list_creation_date","no_of_customer","name","customer_list_type"]};
		//////fields: [ "id","name","departments","positions","customer_list_name","customer_list_key","customer_list_creation_date","no_of_customer"]};
             // fields: [ "id","name","departments","positions"]};


	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	  this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['list']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['list']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['list']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

	    
	    this.table0.subscribe("dataReturnEvent", data_returned);  


	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['list']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['list']['f_value']?>'};

	
	};
    });



function init(){

init_search('customers_store');
dialog_new_list = new YAHOO.widget.Dialog("dialog_new_list", {context:["new_customer_list","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_new_list.render();


Event.addListener("new_customer_list", "click", show_dialog_new_list);


}

YAHOO.util.Event.onDOMReady(init);



YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });



