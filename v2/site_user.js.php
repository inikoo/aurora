<?php
include_once('common.php');

?>
var Dom   = YAHOO.util.Dom;

var dialog_change_password;
var dialog_set_password;
var  group_name=new Object;


    YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
			      // {key:"user", label:"<?php echo _('User')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      {key:"ip", label:"<?php echo _('IP Address')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    ,{key:"login_date", label:"<?php echo _('Login Date')?>",width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			,{key:"logout_date", label:"<?php echo _('Logout Date')?>",width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}	
			];
			       
	    this.dataSource0= new YAHOO.util.DataSource("ar_users.php?tipo=customer_user_login_history&tableid=0&customer_user=1&sf=0&user_key="+Dom.get('user_key').value);
		//alert("ar_users.php?tipo=customer_user_login_history&tableid=0&user_key="+Dom.get('user_key').value)
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
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
		
		
		fields: ["user","ip","login_date","logout_date"]};


	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['site_user']['login_history']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['site_user']['login_history']['order']?>",
									 dir: "<?php echo $_SESSION['state']['site_user']['login_history']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.subscribe("cellClickEvent", this.table0.onEventShowCellEditor);


	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['site_user']['login_history']['f_field']?>',value:'<?php echo$_SESSION['state']['site_user']['login_history']['f_value']?>'};
	   
	   
	   
	

	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
	    			    {key:"date", label:"<?php echo _('Date')?>",width:160,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			      // {key:"user", label:"<?php echo _('User')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"page_code", label:"<?php echo _('Page')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   , {key:"page_title", label:"<?php echo _('Description')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			     , {key:"ip", label:"<?php echo _('IP Address')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			     , {key:"os", label:"<?php echo _('OS')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			     , {key:"browser", label:"<?php echo _('Browser')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			];
			  
request="ar_sites.php?tipo=page_stats&group_by=hits&parent=user&tableid=1&sf=0&parent_key="+Dom.get('user_key').value

		//alert(request)
	    this.dataSource1 = new YAHOO.util.DataSource(request);
	 
	 this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
		
		
		fields: ["page_code","page","ip","date","user_agent","page_title","os","browser"]};

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['site_user']['request_history']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site_user']['request_history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site_user']['request_history']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.subscribe("cellClickEvent", this.table0.onEventShowCellEditor);


	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['site_user']['request_history']['f_field']?>',value:'<?php echo$_SESSION['state']['site_user']['request_history']['f_value']?>'};


 var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 

				    {key:"code", label:"<?php echo _('Page Key')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   , {key:"page", label:"<?php echo _('Page')?>", width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"total_visits", label:"<?php echo _('Total Visits')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	    
				    
				    
				     ];
request="ar_sites.php?tipo=page_stats&group_by=pages&parent=user&tableid=2&sf=0&parent_key="+Dom.get('user_key').value
//alert(request)
	    this.dataSource2 = new YAHOO.util.DataSource(request);
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: { 
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'id','title','code','url','type','site','name', 'email', 'visits','ip','date','previous_page', 'page', 'total_visits'
						 ]};
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource2, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site_user']['visit_pages']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site_user']['visit_pages']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site_user']['visit_pages']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
 this.table2.request=request;
  this.table2.table_id=tableid;
     this.table2.subscribe("renderEvent", myrenderEvent);

	    
	    this.table2.filter={key:'<?php echo$_SESSION['state']['site_user']['visit_pages']['f_field']?>',value:'<?php echo$_SESSION['state']['site_user']['visit_pages']['f_value']?>'};


	};
    });


function change_block(){
ids=['login_history','request_history','pages_visit'];
block_ids=['block_login_history','block_request_history', 'block_pages_visit'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=site_user-block_view&value='+this.id ,{});
}





 function init(){

ids=['login_history','request_history','pages_visit'];
 YAHOO.util.Event.addListener(ids, "click",change_block)

 init_search('users');
 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 

dialog_change_password = new YAHOO.widget.Dialog("dialog_change_password", { visible : false,close:true,underlay: "none",draggable:false});
dialog_change_password.render();
Event.addListener("show_dialog_change_password", "click", show_dialog_change_password);
Event.addListener("close_dialog_change_password", "click", dialog_change_password.hide,dialog_change_password , true);
Event.addListener('send_reset_password', "click",send_reset_password);

 
 dialog_set_password = new YAHOO.widget.Dialog("dialog_set_password", { visible : false,close:true,underlay: "none",draggable:false});
dialog_set_password.render();
 
 Event.addListener('change_password', "click",show_dialog_set_password);
Event.addListener("cancel_change_password", "click", dialog_set_password.hide,dialog_set_password , true);
 Event.addListener('submit_change_password', "click",submit_change_password);
Event.addListener(['change_password_password1','change_password_password2'], "keyup",change_password_changed);
Event.addListener(['change_password_password1','change_password_password2'], "keydown",change_password_changed);

 
 }

 YAHOO.util.Event.onDOMReady(init);
YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
