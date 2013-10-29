<?php
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;
var dialog_new_list;
    
function change_block() {
    ids = ['user_created', 'imported_records'];
    block_ids = ['block_user_created', 'block_imported_records'];

    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=customers-list-block_view&value=' + this.id, {});
}

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



	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
	    
	    				    {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
                            ,{key:"name", label:"<?php echo _('List Name')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                        	,{key:"creation_date", label:"<?php echo _('List Created')?>", width:220,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
							,{key:"customer_list_type", label:"<?php echo _('Type')?>",  width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 	       	,{key:"items", label:"<?php echo _('Customers')?>", width:120,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						 	,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'customer_list'}

				 
				 ];
	    //?tipo=customers&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_contacts.php?tipo=customers_lists&store_id="+Dom.get('store_id').value);
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
		
		fields: ["name","key","creation_date","customers","customer_list_type","items","delete"]};
		

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

	       this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	        this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table0.subscribe("cellClickEvent", onCellClick);      
	   // this.table0.subscribe("dataReturnEvent", data_returned);  

  this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);
	   
	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['list']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['list']['f_value']?>'};


    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
	    
	    	{key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
	    	,{key:"filename", label:"<?php echo _('Import Records')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			,{key:"status", label:"<?php echo _('State')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			,{key:"name", label:"<?php echo _('List Name')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
            ,{key:"user", label:"<?php echo _('User')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			,{key:"date", label:"<?php echo _('Date')?>", width:220,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			,{key:"imported", label:"<?php echo _('Imported Customers')?>", width:120,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 ];

	    request="ar_import.php?tipo=list_subject_imported_records&subject=customers&parent=store&parent_key="+Dom.get('store_id').value+'&tableid='+tableid
	   //alert(request)
	    
	    this.dataSource1 = new YAHOO.util.DataSource(request);
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
		
		fields: ["name","key","date","imported","ignored","errors","filename","user","status"]};
		

	  this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource1
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['list']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['list']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['list']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

	     

  this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);
	   
	    this.table1.filter={key:'<?php echo$_SESSION['state']['customers']['list']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['list']['f_value']?>'};


	
	};
    });



function init(){

    ids = ['user_created', 'imported_records'];
    YAHOO.util.Event.addListener(ids, "click", change_block);

init_search('customers_store');
dialog_new_list = new YAHOO.widget.Dialog("dialog_new_list", {context:["new_customer_list","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_new_list.render();


Event.addListener("new_customer_list", "click", show_dialog_new_list);
    Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    
 Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
    Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);
       
    
  var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;
    
    
     var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACD1S.queryMatchContains = true;
    var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS1);
     oACDS1.table_id = 1;
    oAutoComp1.minQueryLength = 0;

}

YAHOO.util.Event.onDOMReady(init);



YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });

Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });
    
   YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });

Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {trigger:"filter_name1"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    }); 

