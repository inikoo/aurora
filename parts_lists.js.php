<?php
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;
var dialog_new_list;
    


function close_dialog_new_list(){
dialog_new_list.hide();
}

function new_list(store_key){
    location.href='new_parts_list.php?store_key='+store_key;
}


function show_dialog_new_list(){

    dialog_new_list.show();

}



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {





	     //START OF THE TABLE =========================================================================================================================

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
	    		    				    {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
                            ,{key:"name", label:"<?php echo _('List Name')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                        	,{key:"creation_date", label:"<?php echo _('List Created')?>", width:220,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
							,{key:"list_type", label:"<?php echo _('Type')?>",  width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 	       	,{key:"items", label:"<?php echo _('Parts')?>", width:120,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						 	,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'customer_list'}


				 
				 ];

	    request="ar_parts.php?tipo=parts_lists&sf=0";
	   // alert(request)
	    this.dataSource0 = new YAHOO.util.DataSource(request);
		
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
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
		
		fields: ["name","key","creation_date","items","list_type","delete"]};
		

	  this.table0 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs,
								   this.dataSource0
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['warehouse']['parts_lists']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['warehouse']['parts_lists']['order']?>",
									 dir: "<?php echo$_SESSION['state']['warehouse']['parts_lists']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);
     
	       this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	        this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table0.subscribe("cellClickEvent", onCellClick);      
	   // this.table0.subscribe("dataReturnEvent", data_returned);  


	    this.table0.filter={key:'<?php echo$_SESSION['state']['warehouse']['parts_lists']['f_field']?>',value:'<?php echo$_SESSION['state']['warehouse']['parts_lists']['f_value']?>'};

	
	};
    });



function init(){

  init_search('parts');
dialog_new_list = new YAHOO.widget.Dialog("dialog_new_list", {context:["new_parts_list","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_new_list.render();


Event.addListener("new_parts_list", "click", show_dialog_new_list);


}

YAHOO.util.Event.onDOMReady(init);



YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });



