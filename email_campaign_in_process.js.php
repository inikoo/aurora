<?php
include_once('common.php');


?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var dialog_add_email_address;
var dialog_add_email_address_from_list;
var validate_scope_data;
var validate_scope_metadata;
var dialog_preview_text_email;
var dialog_send_email_campaign;
var dialog_department_list;
var dialog_edit_color;
var dialog_upload_header_image;
var dialog_upload_postcard;
var dialog_change_email_type;
var dialog_edit_objective;

function select_department(oArgs){
    parent_key=tables.table5.getRecord(oArgs.target).getData('key')
    var request='ar_edit_marketing.php?tipo=add_email_campaign_objective&email_campaign_key='+Dom.get('email_campaign_key').value+'&parent=Department&parent_key='+parent_key;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
                table_id=9
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);       
                dialog_department_list.hide();
                hide_filter(true,5)
		    }else{
		   
	        }
	    }
    });
}

function select_family(oArgs){
    parent_key=tables.table6.getRecord(oArgs.target).getData('key')
    var request='ar_edit_marketing.php?tipo=add_email_campaign_objective&email_campaign_key='+Dom.get('email_campaign_key').value+'&parent=Family&parent_key='+parent_key;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
                table_id=9
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);       
                dialog_family_list.hide();
                hide_filter(true,6)
		    }else{
		   
	        }
	    }
    });
}
function select_product(oArgs){
    parent_key=tables.table7.getRecord(oArgs.target).getData('pid')
    var request='ar_edit_marketing.php?tipo=add_email_campaign_objective&email_campaign_key='+Dom.get('email_campaign_key').value+'&parent=Product&parent_key='+parent_key;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
                table_id=9
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);       
                dialog_product_list.hide();
                hide_filter(true,7)
		    }else{
		   
	        }
	    }
    }); 
}
function select_offer(oArgs){
    parent_key=tables.table8.getRecord(oArgs.target).getData('pid')
    var request='ar_edit_marketing.php?tipo=add_email_campaign_objective&email_campaign_key='+Dom.get('email_campaign_key').value+'&parent=Deal&parent_key='+parent_key;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
                table_id=9
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);       
                dialog_offer_list.hide();
                hide_filter(true,8)
		    }else{
		   
	        }
	    }
    }); 
}



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	     //START OF THE TABLE =========================================================================================================================
		var store_key=Dom.get('store_id').value;




	 var tableid=9; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
	    				       {key:"id", label:"",hidden:true,action:"none",isPrimaryKey:true}
	    	,{key:"term", label:"",hidden:true}
	    	,{key:"metadata", label:"",hidden:true}
	    	,{key:"temporal_metadata", label:"",hidden:true}
	    		    	,{key:"temporal_formated_metadata", label:"",hidden:true}

	    	,{key:"valid_terms", label:"",hidden:true}

			,{key:"type",label:"", width:12,className:"aleft"}
	         ,{key:"parent", label:"<?php echo _('Type')?>",width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
             ,{key:"name", label:"<?php echo _('Name')?>",width:270,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			   , {key:"objective", label:"<?php echo _('objective')?>",width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},action:'dialog',object:'email_campaign_objective'}
			,{key:"delete",label:"", width:20,className:"aleft",action:'delete',object:'email_campaign_objective'}
			
			
			  			];
			       
		this.dataSource9 = new YAHOO.util.DataSource("ar_edit_marketing.php?tipo=email_campaign_objectives&email_campaign_key="+Dom.get('email_campaign_key').value+"&tableid="+tableid+"&sf=0");
	 this.dataSource9.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource9.connXhrMode = "queueRequests";
	    	    this.dataSource9.table_id=tableid;

	    this.dataSource9.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "description","name","id","type","delete","objective",'link','id','parent','term','metadata','temporal_metadata','valid_terms','temporal_formated_metadata'
			 ]};

	    this.table9 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource9
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator9', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info9'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "name",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table9.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table9.doBeforeSortColumn = mydoBeforeSortColumn;
	  this.table9.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table9.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table9.subscribe("cellClickEvent", onCellClick);      	    
      this.table9.table_id=tableid;
     this.table9.subscribe("renderEvent", myrenderEvent);


	    this.table9.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table9.filter={key:'code',value:''};


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var CustomersColumnDefs = [
                                        {key:"name", label:"<?php echo _('List Name')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                     ,{key:"creation_date", label:"<?php echo _('List Created')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					//,{key:"no_of_customer", label:"<?php echo _('No. Of Customer')?>",  width:180,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"customer_list_type", label:"<?php echo _('List Type')?>",  width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						,{key:"emails_valid_to_send", label:"<?php echo _('Emails')?>", width:50,sortable:false,className:"right"}

			,{key:"add_to_email_campaign_action", label:"", width:100,sortable:false,className:"right"}
                  //                     ,{key:"customer_list_key", label:"<?php echo _('Create Campaign')?>", width:155,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   ];
	    //?tipo=customers&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_contacts.php?tipo=customers_lists&sf=0&store_id="+Dom.get('store_id').value);
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
		
		fields: ["name","customer_list_key","creation_date","customers","customer_list_type","add_to_email_campaign_action","emails_valid_to_send"]};
		

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
									      ,template : "{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}"



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
 this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);
	    
	   // this.table0.subscribe("dataReturnEvent", data_returned);  


	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['list']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['list']['f_value']?>'};

	
	
	
	 var tableid=5; 
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
			
                    {key:"code", label:"<?php echo _('Code')?>",width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				];
			       
	    this.dataSource5 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=department_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource5.connXhrMode = "queueRequests";
	    	    this.dataSource5.table_id=tableid;

	    this.dataSource5.responseSchema = {
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
			 "code","name",'key'
			 ]};

	    this.table5 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource5
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);





 this.table5.subscribe("rowMouseoverEvent", this.table5.onEventHighlightRow);
       this.table5.subscribe("rowMouseoutEvent", this.table5.onEventUnhighlightRow);
      this.table5.subscribe("rowClickEvent", select_department);
           
           this.table5.table_id=tableid;
           this.table5.subscribe("renderEvent", myrenderEvent);



	    this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table5.filter={key:'code',value:''};
   var tableid=6; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
			
                    {key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						
			];
		this.dataSource6 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=family_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource6.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource6.connXhrMode = "queueRequests";
	    	    this.dataSource6.table_id=tableid;

	    this.dataSource6.responseSchema = {
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
			 "code",'name','key'
			 ]};

	    this.table6 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource6
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator6', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table6.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table6.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table6.subscribe("rowMouseoverEvent", this.table6.onEventHighlightRow);
       this.table6.subscribe("rowMouseoutEvent", this.table6.onEventUnhighlightRow);
      this.table6.subscribe("rowClickEvent", select_family);
        this.table6.table_id=tableid;
           this.table6.subscribe("renderEvent", myrenderEvent);


	    this.table6.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table6.filter={key:'code',value:''};


   var tableid=7; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
                    {key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			  			];
			      
		this.dataSource7 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=product_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource7.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource7.connXhrMode = "queueRequests";
	    	    this.dataSource7.table_id=tableid;

	    this.dataSource7.responseSchema = {
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
			 "code","name","pid"
			 ]};

	    this.table7 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource7
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator7', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info7'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table7.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table7.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table7.subscribe("rowMouseoverEvent", this.table7.onEventHighlightRow);
       this.table7.subscribe("rowMouseoutEvent", this.table7.onEventUnhighlightRow);
      this.table7.subscribe("rowClickEvent", select_product);
     
 this.table7.table_id=tableid;
     this.table7.subscribe("renderEvent", myrenderEvent);

	    this.table7.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table7.filter={key:'code',value:''};


	 var tableid=8; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
                    {key:"name", label:"<?php echo _('Name')?>",width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                  // ,{key:"description", label:"<?php echo _('Description')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			  			];
			       
		this.dataSource8 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=deal_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource8.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource8.connXhrMode = "queueRequests";
	    	    this.dataSource8.table_id=tableid;

	    this.dataSource8.responseSchema = {
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
			 "description","name","id"
			 ]};

	    this.table8 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource8
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator8', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info8'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "name",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table8.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table8.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table8.subscribe("rowMouseoverEvent", this.table8.onEventHighlightRow);
       this.table8.subscribe("rowMouseoutEvent", this.table8.onEventUnhighlightRow);
      this.table8.subscribe("rowClickEvent", select_offer);
     
 this.table8.table_id=tableid;
     this.table8.subscribe("renderEvent", myrenderEvent);

	    this.table8.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table8.filter={key:'code',value:''};


	
//----------------------------------------------------------------------------------------------------------------




        var tableid=10; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
	    				    {key:"id", label:"",hidden:true,action:"none",isPrimaryKey:true}
	    				    ,{key:"name", label:"<?php echo _('Name')?>",width:170,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                        ,{key:"palette", label:"<?php echo _('Palette')?>",width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                        ,{key:"used",label:"<?php echo _('In Use')?>", width:40,className:"aright"}

			                ,{key:"delete",label:"", width:20,className:"aleft",action:'delete',object:'color_scheme'}
			  			];
			       
		request="ar_edit_marketing.php?tipo=color_schemes&store_key="+Dom.get('store_key').value+"&tableid="+tableid+"&sf=0&email_content_key="+Dom.get('email_content_key').value	       
		this.dataSource10 = new YAHOO.util.DataSource(request);
	 this.dataSource10.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource10.connXhrMode = "queueRequests";
	    	    this.dataSource10.table_id=tableid;

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
		
		
		fields: [
			 "id","name","palette","delete","used"
			 ]};

	    this.table10 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource10
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator10', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info10'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "name",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table10.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table10.doBeforeSortColumn = mydoBeforeSortColumn;
	  this.table10.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table10.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table10.subscribe("cellClickEvent", onCellClick);      	    
     
 this.table10.table_id=tableid;
     this.table10.subscribe("renderEvent", myrenderEvent);

	    this.table10.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table10.filter={key:'name',value:''};



  var tableid=11; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
	    				    {key:"id", label:"",hidden:true,action:"none",isPrimaryKey:true}
	    				    ,{key:"name", label:"<?php echo _('Name')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                        ,{key:"image", label:"<?php echo _('Image')?>",width:610,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                        ,{key:"used",label:"<?php echo _('In Use')?>", width:40,className:"aright"}

			                ,{key:"delete",label:"", width:20,className:"aleft",action:'delete',object:'template_header_image'}
			  			];
			
request="ar_edit_marketing.php?tipo=email_template_header_images&store_key="+Dom.get('store_key').value+"&tableid="+tableid+"&sf=0&email_content_key="+Dom.get('email_content_key').value

		this.dataSource11 = new YAHOO.util.DataSource(request);
	 this.dataSource11.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource11.connXhrMode = "queueRequests";
	    	    this.dataSource11.table_id=tableid;

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
		
		
		fields: [
			 "id","name","image","delete","used"
			 ]};

	    this.table11 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource11
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator11', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info11'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "name",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table11.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table11.doBeforeSortColumn = mydoBeforeSortColumn;
	  this.table11.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table11.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table11.subscribe("cellClickEvent", onCellClick);      	    
     

 this.table11.table_id=tableid;
     this.table11.subscribe("renderEvent", myrenderEvent);
	    this.table11.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table11.filter={key:'name',value:''};





  var tableid=12; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
	    				    {key:"id", label:"",hidden:true,action:"none",isPrimaryKey:true}
	    				    ,{key:"name", label:"<?php echo _('Name')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                        ,{key:"image", label:"<?php echo _('Image')?>",width:610,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                        ,{key:"used",label:"<?php echo _('In Use')?>", width:40,className:"aright"}

			                ,{key:"delete",label:"", width:20,className:"aleft",action:'delete',object:'template_postcard'}
			  			];
			       
		this.dataSource12 = new YAHOO.util.DataSource("ar_edit_marketing.php?tipo=email_template_postcards&store_key="+Dom.get('store_key').value+"&tableid="+tableid+"&sf=0&email_content_key="+Dom.get('email_content_key').value);
	 this.dataSource12.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource12.connXhrMode = "queueRequests";
	    	    this.dataSource12.table_id=tableid;

	    this.dataSource12.responseSchema = {
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
			 "id","name","image","delete","used"
			 ]};

	    this.table12 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource12
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator12', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info12'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "name",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table12.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table12.doBeforeSortColumn = mydoBeforeSortColumn;
	  this.table12.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table12.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table12.subscribe("cellClickEvent", onCellClick);      	    
     
 this.table12.table_id=tableid;
     this.table12.subscribe("renderEvent", myrenderEvent);


	    this.table12.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table12.filter={key:'name',value:''};



	
	
	};
    });

function cancel_edit_email_campaign(){
location.href='marketing.php';
}

function validate_email_campaign_name(query){
 validate_general('email_campaign','name',unescape(query));
}

function validate_email_campaign_objective(query){
 validate_general('email_campaign','objective',unescape(query));
}

function validate_email_campaign_scope(query){
 validate_general('email_campaign','scope',unescape(query));
}

function validate_email_campaign_subject(query){
validate_scope_metadata['email_campaign']['secondary_key']=Dom.get('current_email_contact_key');
 validate_general('email_campaign','subject',unescape(query));
}

function validate_email_campaign_content_text(query){

 validate_general('email_content_text','content_text',unescape(query));
}

function validate_add_email_address_manually(query){
 validate_general('add_email_address_manually','email_address',unescape(query));
}

function save_add_email_address_manually(){
save_new_general('add_email_address_manually');
}

function post_new_create_actions(branch,r){
switch ( branch ) {
	case 'add_email_address_manually':
		Dom.get('recipients_preview').innerHTML=r.recipients_preview;
		Dom.get('email_campaign_number_recipients').value=r.number_recipients;
		validate_general('full_email_campaign','email_recipients',r.number_recipients);
		
		if(r.ready_to_send){
		Dom.removeClass('preview_email_campaign','disabled');
				Dom.removeClass('send_email_campaign','disabled');

		}else{
			Dom.addClass('preview_email_campaign','disabled');
				Dom.addClass('send_email_campaign','disabled');
		
		}
		
		
		//check_if_ready_to_send();
		close_dialog_add_email_address();
		break;
	
	
	
	default:
		
}
}

function close_dialog_add_email_address(){
cancel_new_general('add_email_address_manually')
dialog_add_email_address.hide();
}

function add_to_email_campaign(list_key){
var email_campaign_key=Dom.get('email_campaign_key').value;

var request='ar_edit_marketing.php?tipo=add_emails_from_list&email_campaign_key='+encodeURIComponent(email_campaign_key)+'&list_key='+encodeURIComponent(list_key);
//alert(request);
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
Dom.get('recipients_preview').innerHTML=r.recipients_preview;
Dom.get('email_campaign_number_recipients').value=r.number_recipients;
		validate_general('full_email_campaign','email_recipients',r.number_recipients);
		check_if_ready_to_send();
		Dom.setStyle('recipients_preview_msg','visibility','visible')
		Dom.get('recipients_preview_msg').innerHTML=r.msg;
		
		   dialog_add_email_address_from_list.hide();
		}else{
		    if(r.msg!=undefined)
		        Dom.addClass('delete_email_campaign','error')
		        Dom.get('delete_email_campaign').innerHTML='<span class="error">'+r.msg+'</span>';
	      
	    }
	    }
	    });






}



function send_email_campaign(){

validate_scope('email_campaign');


Dom.setStyle('dialog_send_email_campaign_choose_when1','display','');
//Dom.setStyle('other_time_form','display','none');

dialog_send_email_campaign.show();



}

function delete_email_campaign(){
var email_campaign_key=Dom.get('email_campaign_key').value;

var request='ar_edit_marketing.php?tipo=delete_email_campaign&email_campaign_key='+email_campaign_key;
//alert(request);
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){

            location.href="marketing.php";
		}else{
		    if(r.msg!=undefined)
		        Dom.get('add_email_address_from_customer_list_msg').innerHTML='<span class="error">'+r.msg+'</span>';
	      
	    }
	    }
	    });
}

function reset_edit_email_campaign(){
reset_edit_general('email_campaign');
}



function send_now(){
start_send(0)
}

function choose_time(){

Dom.setStyle('dialog_send_email_campaign_choose_when1','display','none');
Dom.setStyle('other_time_form','display','');

}

function send_other_time(){
user_input=Dom.get('end_email_campaign_datetime').value;
alert(user_input)

lag_seconds=Date.create(user_input).secondsFromNow();



return;

if(isNaN(lag_seconds)){
	lag_seconds='Not Identified';
	Dom.get('time_tag').innerHTML=lag_seconds
	Dom.setStyle('time_tag','display','');
}
else{
	display_date=Date.create(user_input).format(Date.RFC1123);
	Dom.get('time_tag').innerHTML=display_date;
	//Dom.setStyle('time_tag','display','none');
	start_send(lag_seconds);
}
//alert(lag_seconds);return;


}

function start_send(lap_seconds){
var email_campaign_key=Dom.get('email_campaign_key').value;

var request='ar_edit_marketing.php?tipo=set_email_campaign_as_ready&email_campaign_key='+email_campaign_key+'&start_sending_in='+lap_seconds;
//alert(request);
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
//alert("email_campaign.php?id="+Dom.get('email_campaign_key').value);return;
            location.href="email_campaign.php?id="+Dom.get('email_campaign_key').value;
		}else{
		alert(r.msg)
		    //if(r.msg!=undefined)
		     //   Dom.get('add_email_address_from_customer_list_msg').innerHTML='<span class="error">'+r.msg+'</span>';
	      
	    }
	    }
	    });

}



function save_edit_email_campaign(){
save_edit_general('email_campaign');
}

function post_item_updated_actions(branch,r){
key=r.key;
newvalue=r.newvalue;

switch ( branch ) {
	case 'email_campaign':
		switch ( key ) {
			case 'name':
				Dom.get('h1_email_campaign_name').innerHTML=newvalue;
				break;
			case('subject'):
			    
			    break;
			case 'content_html_text':
				table_id=9
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);       
				break;
			
		};
		
		if(r.ready_to_send){
		    Dom.removeClass(['preview_email_campaign','send_email_campaign'],'disabled');
		    
		}else{
		Dom.addClass(['preview_email_campaign','send_email_campaign'],'disabled');
		}
		
		break;
	
	
	
	
}

}





function init(){

  init_search('marketing_store');

//changeHeight(Dom.get('template_email_iframe'))
//resizeFrame()

 validate_scope_data={
 'email_campaign':{
	

	  'subject':{
	            'dbname':'Email Campaign Subject',
	            'changed':false,
	            'validated':true,
	            'required':false,
	            'group':1,
	            'type':'item',
	            'name':'email_campaign_subject',
	            'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_subjects').innerHTML}]
	            }         
	    
	            
	   	 //           'validation':[{'regexp':"^(((d|f|c)\\()?[a-z0-9\\-\\)]+,?)+$",'invalid_msg':Dom.get('invalid_email_campaign_scope').innerHTML}]
         
	            
   },
   
    'email_content_text':{
	

	           
	     'content_text':{
	            'dbname':'Email Campaign Content Text',
	            'changed':false,
	            'validated':true,
	            'required':false,
	            'group':1,
	            'type':'item',
	            'name':'email_campaign_content_text',
	            'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_contents').innerHTML}]
	            }
         
	            
   },
     'email_content_html':{
	
   
	         'content_html':{
	            'dbname':'Email Campaign Content HTML',
	            'changed':false,
	            'validated':true,
	            'required':false,
	            'group':1,
	            'type':'item',
	            'name':'html_email_editor',
	            'validation':false
	            }            
         
	            
   },
   
 'add_email_address_manually':{
  	'email_address':{'dbname':'Email Address','changed':false,'validated':false,'required':true,'group':1,'type':'item','name':'add_email_address','ar':false,'validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]},
  
  'email_contact_name':{'dbname':'Email Contact Name','changed':false,'validated':false,'required':false,'group':1,'type':'item','name':'add_email_contact_name','ar':false,'validation':[{'regexp':regexp_valid_email,'invalid_msg':'<?php echo _('Invalid Email')?>'}]}
   },
   
  'full_email_campaign':{

 	   	'email_recipients':{
 	   	'changed':false,'validated':Dom.get('email_campaign_number_recipients').value>0?true:false,'required':true,'name':'email_campaign_number_recipients','validation':[{'numeric':"positive integer",'invalid_msg':Dom.get('invalid_email_campaign_recipients').innerHTML}]
 	   	},
 	   	'email_subjects':{
 	   	'changed':false,'validated':Dom.get('email_campaign_subjects').value!=''?true:false,'required':true,'name':'email_campaign_subjects','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_subjects').innerHTML}]
 	   	},
	'email_contents':{
 	   	'changed':false,'validated':Dom.get('email_campaign_contents').value!=''?true:false,'required':true,'name':'email_campaign_contents','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_contents').innerHTML}]
 	   	},
 },
 'preview_email_campaign':{
 	'email_subjects':{
 	   	'changed':false,'validated':Dom.get('email_campaign_subjects').value!=''?true:false,'required':true,'name':'email_campaign_subjects','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_subjects').innerHTML}]
 	   	},
	'email_contents':{
 	   	'changed':false,'validated':Dom.get('email_campaign_contents').value!=''?true:false,'required':true,'name':'email_campaign_contents','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('invalid_email_campaign_contents').innerHTML}]
 	   	}
 }
  
  
  }
validate_scope_metadata={
'email_campaign':{'type':'edit','ar_file':'ar_edit_marketing.php','key_name':'email_campaign_key','key':Dom.get('email_campaign_key').value,'dynamic_second_key':'current_email_contact_key','second_key_name':'email_content_key'}
,'email_content_text':{'type':'edit','ar_file':'ar_edit_marketing.php','key_name':'email_campaign_key','key':Dom.get('email_campaign_key').value,'dynamic_second_key':'current_email_contact_key','second_key_name':'email_content_key'}
,'email_content_html':{'type':'edit','ar_file':'ar_edit_marketing.php','key_name':'email_campaign_key','key':Dom.get('email_campaign_key').value,'dynamic_second_key':'current_email_contact_key','second_key_name':'email_content_key'}

,'add_email_address_manually':{'type':'new','ar_file':'ar_edit_marketing.php','key_name':'email_campaign_key','key':Dom.get('email_campaign_key').value}
,'full_email_campaign':{'type':'edit','ar_file':'ar_edit_marketing.php','key_name':'email_campaign_key','key':Dom.get('email_campaign_key').value}
,'preview_email_campaign':{'type':'edit','ar_file':'ar_edit_marketing.php','key_name':'email_campaign_key','key':Dom.get('email_campaign_key').value}

};

    dialog_add_email_address = new YAHOO.widget.Dialog("dialog_add_email_address", {context:["add_email_address_manually","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_add_email_address.render();
    Event.addListener("add_email_address_manually", "click", dialog_add_email_address.show,dialog_add_email_address , true);

  dialog_add_email_address_from_list = new YAHOO.widget.Dialog("dialog_add_email_address_from_list", {context:["add_email_address_from_customer_list","tr","br"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_add_email_address_from_list.render();
    Event.addListener("add_email_address_from_customer_list", "click", dialog_add_email_address_from_list.show,dialog_add_email_address_from_list , true);

  dialog_preview_text_email = new YAHOO.widget.Dialog("dialog_preview_text_email", {context:["preview_email_campaign","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_preview_text_email.render();
    Event.addListener("preview_email_campaign", "click", preview_email_campaign);

  
   dialog_send_email_campaign = new YAHOO.widget.Dialog("dialog_send_email_campaign", {context:["send_email_campaign","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_send_email_campaign.render();
    Event.addListener("preview_email_campaign", "click", preview_email_campaign);
  
   /*
    var email_campaign_name_oACDS = new YAHOO.util.FunctionDataSource(validate_email_campaign_name);
    email_campaign_name_oACDS.queryMatchContains = true;
    var email_campaign_name_oAutoComp = new YAHOO.widget.AutoComplete("email_campaign_name","email_campaign_name_Container", email_campaign_name_oACDS);
    email_campaign_name_oAutoComp.minQueryLength = 0; 
    email_campaign_name_oAutoComp.queryDelay = 0.1;
    
   
    
   
    var email_campaign_scope_oACDS = new YAHOO.util.FunctionDataSource(validate_email_campaign_scope);
    email_campaign_scope_oACDS.queryMatchContains = true;
    var email_campaign_scope_oAutoComp = new YAHOO.widget.AutoComplete("email_campaign_scope","email_campaign_scope_Container", email_campaign_scope_oACDS);
    email_campaign_scope_oAutoComp.minQueryLength = 0; 
    email_campaign_scope_oAutoComp.queryDelay = 0.1;
    */
    var add_email_address_oACDS = new YAHOO.util.FunctionDataSource(validate_add_email_address_manually);
    add_email_address_oACDS.queryMatchContains = true;
    var add_email_address_oAutoComp = new YAHOO.widget.AutoComplete("add_email_address","add_email_address_Container", add_email_address_oACDS);
    add_email_address_oAutoComp.minQueryLength = 0; 
    add_email_address_oAutoComp.queryDelay = 0.1;
    
    var email_campaign_subject_oACDS = new YAHOO.util.FunctionDataSource(validate_email_campaign_subject);
    email_campaign_subject_oACDS.queryMatchContains = true;
    var email_campaign_subject_oAutoComp = new YAHOO.widget.AutoComplete("email_campaign_subject","email_campaign_subject_Container", email_campaign_subject_oACDS);
    email_campaign_subject_oAutoComp.minQueryLength = 0; 
    email_campaign_subject_oAutoComp.queryDelay = 0.1;
    
     var email_campaign_content_text_oACDS = new YAHOO.util.FunctionDataSource(validate_email_campaign_content_text);
    email_campaign_content_text_oACDS.queryMatchContains = true;
    var email_campaign_content_text_oAutoComp = new YAHOO.widget.AutoComplete("email_campaign_content_text","email_campaign_content_text_Container", email_campaign_content_text_oACDS);
    email_campaign_content_text_oAutoComp.minQueryLength = 0; 
    email_campaign_content_text_oAutoComp.queryDelay = 0.1;
   
    Event.addListener("save_new_add_email_address_manually", "click", save_add_email_address_manually);
    Event.addListener("cancel_new_add_email_address_manually", "click", close_dialog_add_email_address);
    Event.addListener("delete_email_campaign", "click", delete_email_campaign);
    
    
   


    Event.addListener("send_email_campaign", "click", send_email_campaign);
 

    Event.addListener('reset_edit_email_campaign', "click", reset_edit_email_campaign);
    Event.addListener('save_edit_email_campaign', "click", save_edit_email_campaign);
    
   
 
     dialog_department_list = new YAHOO.widget.Dialog("dialog_department_list", { visible : false,close:true,underlay: "none",draggable:false});
    dialog_department_list.render();
    Event.addListener("department", "click", show_dialog_department_list);

    dialog_family_list = new YAHOO.widget.Dialog("dialog_family_list", {context:["family","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_family_list.render();
    Event.addListener("family", "click", dialog_family_list.show,dialog_family_list , true);

    dialog_product_list = new YAHOO.widget.Dialog("dialog_product_list", {context:["product","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_product_list.render();
    Event.addListener("product", "click", dialog_product_list.show,dialog_product_list , true);
    
     dialog_offer_list = new YAHOO.widget.Dialog("dialog_offer_list", {context:["offer","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_offer_list.render();
    Event.addListener("offer", "click", dialog_offer_list.show,dialog_offer_list , true);
    
   

  dialog_edit_objective = new YAHOO.widget.Dialog("dialog_edit_objective", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_edit_objective.render();


                  Event.addListener("objective_term_Order", "click", change_objective_term,'Order');
                  Event.addListener("objective_term_Buy", "click", change_objective_term,'Buy');
                  Event.addListener("objective_term_Visit", "click", change_objective_term,'Visit');
                  Event.addListener("objective_term_Use", "click", change_objective_term,'Use');


            Event.addListener("objective_time_limit", "keyup", validate_change_objective_interval);

            Event.addListener("save_edit_objective", "click", save_edit_objective);
      Event.addListener("show_add_object_manually", "click", show_add_object_manually);

}

function save_edit_objective(){

alert("xxx")

if(Dom.hasClass('save_edit_objective','disabled')){

return;
}





var objective_key=Dom.get('objective_key').value;
var objective_term=Dom.get('objective_term').value;
var objective_time_limit_in_seconds=Dom.get('objective_time_limit_in_seconds').value;
	
var request='ar_edit_marketing.php?tipo=update_objective&objective_key='+objective_key+'&objective_term='+objective_term+'&objective_time_limit_in_seconds='+objective_time_limit_in_seconds;
alert(request)
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){

	
		}else{
		  
	    }
	    }
	    });


}

function validate_change_objective_interval(){

date=parse_time_interval(this.value)
if(!date){
Dom.setStyle('objective_time_parsed_interval_tr','display','none')
Dom.setStyle('objective_time_wrong_interval_tr','display','')

Dom.addClass('save_edit_objective','disabled')
}else{
//alert(date)
Dom.setStyle('objective_time_parsed_interval_tr','display','')
Dom.setStyle('objective_time_parsed_interval_tr','visibility','visible')

Dom.setStyle('objective_time_wrong_interval_tr','display','none')
Dom.removeClass('save_edit_objective','disabled')

//date.advance({ seconds: 1 });
//parsed_interval=date.relative()
//parsed_interval=parsed_interval.replace(/ from now/,'')

if(date.daysSince()<0){

    if(date.hoursSince())
    parsed_interval=date.hoursSince()+' <?php echo _('hours')?>';
    else
        parsed_interval=date.secondsSince()+' <?php echo _('seconds')?>';


}else{
parsed_interval=date.daysSince()+' <?php echo _('days')?>';
}
Dom.get('objective_time_parsed_interval').innerHTML=parsed_interval;

}


}




function post_delete_actions(column){
if(column=='template_header_image' || column=='color_scheme'       || column=='template_postcard'){

Dom.get('template_email_iframe').contentDocument.location.reload(true);
}

}





function  show_dialog_department_list(){

 var pos = Dom.getXY('department');
 pos[0]=pos[0]-300
 Dom.setXY('dialog_department_list', pos);
 dialog_department_list.show();

}

function show_add_object_manually(){

Dom.setStyle(['objectives_second_label','show_add_object_manually'],'visibility','hidden')
Dom.get('email_campaign_scope').value='';

Dom.setStyle('add_objective_tr','display','')

}

function hide_add_object_manually(){

Dom.setStyle('objectives_second_label','visibility','hidden')
}


function update_objects_table() {


    table_id = 9;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest('', table.onDataReturnInitializeTable, table);
}


function show_cell_dialog(datatable, oArgs) {

    var target = oArgs.target;
    var column = datatable.getColumn(target);
    var record = datatable.getRecord(target);

    var recordIndex = datatable.getRecordIndex(record);

    switch (column.object) {
    case 'email_campaign_objective':

        var term_buttons = Dom.getElementsByClassName('objective_term', 'button', 'objective_terms');
        Dom.removeClass(term_buttons, 'selected');
        Dom.setStyle(term_buttons, 'display', 'none');
        var valid_terms = record.getData('valid_terms');
        for (x in valid_terms) {
            Dom.setStyle("objective_term_" + valid_terms[x], 'display', '')
        }

        Dom.get('objective_term').value = record.getData('term');
        Dom.addClass('objective_term_' + record.getData('term'), 'selected');
        Dom.get('objective_time_limit').value = record.getData('temporal_formated_metadata');

        Dom.get('objective_key').value = record.getData('id');

        Dom.get('objective_time_limit_in_seconds').value = record.getData('temporal_metadata');
        var pos = Dom.getXY(target);
        pos[0] = pos[0] - 320 + 100
        Dom.setXY('dialog_edit_objective', pos);

        dialog_edit_objective.show();

        break;


    }

}

function change_objective_term(e, term) {
    var term_buttons = Dom.getElementsByClassName('objective_term', 'button', 'objective_terms');
    Dom.removeClass(term_buttons, 'selected');
    Dom.addClass('objective_term_' + term, 'selected');


    Dom.get('objective_term').value = term;

}

Event.onDOMReady(init);
