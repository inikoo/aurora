<?php
include_once('common.php');
?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;


function change_block(e){
     var ids = ["stores","campaigns","offers"]; 
    var block_ids = ["block_stores","block_campaigns","block_offers"]; 

	Dom.setStyle(block_ids,'display','none');
	Dom.setStyle('block_'+this.id,'display','');
	Dom.removeClass(ids,'selected');
	Dom.addClass(this, 'selected');
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-marketing_block_view&value='+this.id ,{});
}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {




		var tableid=0; 
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"code", label:"<?php echo _('Code')?>",width:70,sortable:true,<?php echo($_SESSION['state']['stores']['marketing']['view']=='general'?'':'hidden:true,')?>className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Store Name')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"ecampaigns", label:"<?php echo _('Marketing Emails')?>", sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},className:'aright'}
				    ,{key:"newsletters", label:"<?php echo _('Newsletters')?>", sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},className:'aright'}
				    ,{key:"reminders", label:"<?php echo _('Reminders')?>", sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},className:'aright'}
				    ,{key:"campaigns", label:"<?php echo _('Campaigns')?>", sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},className:'aright'}
				    ,{key:"deals", label:"<?php echo _('Deals')?>", sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},className:'aright'}

		
					 ];
	    this.dataSource0 = new YAHOO.util.DataSource("ar_deals.php?tipo=marketing_per_store&tableid="+tableid);
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
		    totalRecords: "resultset.total_records"		},
		
		
		fields: [
			 'code','name','ecampaigns','newsletters','reminders','campaigns','deals'
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['stores']['marketing']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['stores']['marketing']['order']?>",
									 dir: "<?php echo$_SESSION['state']['stores']['marketing']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.view='<?php echo$_SESSION['state']['stores']['marketing']['view']?>';

	    this.table0.filter={key:'<?php echo$_SESSION['state']['stores']['marketing']['f_field']?>',value:'<?php echo$_SESSION['state']['stores']['marketing']['f_value']?>'};



	    var tableid=10; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
	    				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
                   	,{key:"store", label:"<?php echo _('Store')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

                                        ,{key:"code", label:"<?php echo _('Code')?>", width:110,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                     ,{key:"description", label:"<?php echo _('Description')?>", width:310,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"duration", label:"<?php echo _('Duration')?>",  width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
 					,{key:"orders", label:"<?php echo _('Orders')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"customers", label:"<?php echo _('Customers')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				 
				 ];
	    //?tipo=products&tid=0"
	    
	    request="ar_deals.php?tipo=deals&parent=stores&parent_key=&tableid=10&referrer=marketing"
	   // alert(request);
	    this.dataSource10 = new YAHOO.util.DataSource(request);
	    this.dataSource10.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource10.connXhrMode = "queueRequests";
	    this.dataSource10.responseSchema = {
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
		
		fields: ["name","key","description","duration","orders","code","customers","store"]};
		

	  this.table10 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs,
								   this.dataSource10
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo $_SESSION['state']['stores']['offers']['nr']?>,containers : 'paginator10', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info10'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['stores']['offers']['order']?>",
									 dir: "<?php echo $_SESSION['state']['stores']['offers']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
		this.table10.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table10.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table10.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table10.request=request;
  		this.table10.table_id=tableid;
     	this.table10.subscribe("renderEvent", offers_myrenderEvent);
		this.table10.getDataSource().sendRequest(null, {
		    success: function(request, response, payload) {
		        if (response.results.length == 0) {
		            offers_myrenderEvent()

		        } else {
		             this.onDataReturnInitializeTable(request, response, payload);
		        }
		    },
		    scope: this.table10,
		    argument: this.table10.getState()
		});
	  
	    this.table10.filter={key:'<?php echo $_SESSION['state']['stores']['offers']['f_field']?>',value:'<?php echo $_SESSION['state']['stores']['offers']['f_value']?>'};
	    
	    
	    
	    var tableid=11; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
	    			{key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
                   	,{key:"store", label:"<?php echo _('Store')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

   ,{key:"code", label:"<?php echo _('Code')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	    			
					,{key:"name", label:"<?php echo _('Name')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"duration", label:"<?php echo _('Duration')?>",  width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"orders", label:"<?php echo _('Orders')?>",  width:180,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"customers", label:"<?php echo _('Customers')?>",  width:180,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				 
				 ];
	    //?tipo=products&tid=0"
	    request='ar_deals.php?tipo=campaigns&parent=stores&parent_key=&tableid='+tableid+'&referrer=marketing'
	 
	    this.dataSource11 = new YAHOO.util.DataSource(request);
	    this.dataSource11.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource11.connXhrMode = "queueRequests";
	    this.dataSource11.responseSchema = {
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
		
		fields: ["name","key","code","duration","orders","customers","store"]};
		

	  this.table11 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs,
								   this.dataSource11
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo $_SESSION['state']['stores']['campaigns']['nr']?>,containers : 'paginator11', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info11'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['stores']['campaigns']['order']?>",
									 dir: "<?php echo $_SESSION['state']['stores']['campaigns']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
		this.table11.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table11.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table11.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table11.request=request;
  		this.table11.table_id=tableid;
     	this.table11.subscribe("renderEvent", campaigns_myrenderEvent);
		this.table11.getDataSource().sendRequest(null, {
		    success: function(request, response, payload) {
		        if (response.results.length == 0) {
		            campaigns_myrenderEvent()

		        } else {
		             this.onDataReturnInitializeTable(request, response, payload);
		        }
		    },
		    scope: this.table11,
		    argument: this.table11.getState()
		});	  
	    this.table11.filter={key:'<?php echo $_SESSION['state']['stores']['campaigns']['f_field']?>',value:'<?php echo $_SESSION['state']['stores']['campaigns']['f_value']?>'};

	    


	
	};
    });




 function init(){

   init_search('marketing');
 YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 

 

 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
 oAutoComp.minQueryLength = 0; 

     var ids = ["stores","campaigns","offers"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);





 
}

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
    
