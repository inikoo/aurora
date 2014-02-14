<?php
include_once('common.php');


?>
 var Dom = YAHOO.util.Dom;
 var Event = YAHOO.util.Event;
 var tables;


 function change_block() {
     ids = ['details', 'pages', 'hits', 'visitors', 'reports', , 'search_queries','changelog','email_reminders'];
     block_ids = ['block_details', 'block_pages', 'block_hits', 'block_visitors', 'block_reports', 'block_search_queries','block_changelog','block_email_reminders'];
     Dom.setStyle(block_ids, 'display', 'none');
     Dom.setStyle('block_' + this.id, 'display', '');
     Dom.removeClass(ids, 'selected');
     Dom.addClass(this, 'selected');
     Dom.get('block_view').value=this.id;
     YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=site-view&value=' + this.id, {});
 }

 function change_search_queries_block() {
     ids = ['search_queries_queries', 'search_queries_history'];
     block_ids = ['block_search_queries_queries', 'block_search_queries_history'];
     Dom.setStyle(block_ids, 'display', 'none');
     Dom.setStyle('block_' + this.id, 'display', '');
    
     Dom.removeClass(ids, 'selected');
     Dom.addClass(this, 'selected');

     YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=site-search_queries_block&value=' + this.getAttribute('block_id'), {});
 }

function get_email_reminders_numbers(trigger, from, to) {
    var ar_file = 'ar_sites.php';
    var request = 'tipo=number_email_reminders_in_interval&trigger='+trigger+'&parent=site&parent_key=' + Dom.get('site_key').value + '&from=' + from + '&to=' + to;

   YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

         
   
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
        
                for (i in r.elements_numbers) {
    
    Dom.get('elements_' + r.trigger + '_email_reminders_' + i + '_number').innerHTML = r.elements_numbers[i]
                }
            }
        },
        failure: function(o) {
           // alert(o.statusText);
        },
        scope: this
    }, request

    );
}



var already_clicked_elements_click = false
function change_elements() {
el=this;
var elements_type='';
    if (already_clicked_elements_click) {
        already_clicked_elements_click = false; // reset
        clearTimeout(alreadyclickedTimeout); // prevent this from happening
        change_elements_dblclick(el, elements_type)
    } else {
        already_clicked_elements_click = true;
        alreadyclickedTimeout = setTimeout(function() {
            already_clicked_elements_click = false; // reset when it happens
            change_elements_click(el, elements_type)
        }, 300); // <-- dblclick tolerance here
    }
    return false;
}

function change_elements_click(el,elements_type) {

     ids = ['elements_System', 'elements_Info', 'elements_Department', 'elements_Family','elements_Product', 'elements_ProductCategory', 'elements_FamilyCategory'];


    if (Dom.hasClass(el, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(el, 'selected')

        }

    } else {
        Dom.addClass(el, 'selected')

    }

    table_id = 0;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';
    for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' + ids[i] + '=1'
        } else {
            request = request + '&' + ids[i] + '=0'

        }
    }

    // alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


}

function change_elements_dblclick(el,elements_type) {

     ids = ['elements_System', 'elements_Info', 'elements_Department', 'elements_Family','elements_Product', 'elements_ProductCategory', 'elements_FamilyCategory'];


    
         Dom.removeClass(ids, 'selected')

     Dom.addClass(el, 'selected')

    table_id = 0;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';
    for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' + ids[i] + '=1'
        } else {
            request = request + '&' + ids[i] + '=0'

        }
    }

    // alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


}

var change_view=function(e){
	
	var table=tables['table0'];

if(this.id=='page_visitors'){
tipo='visitors'
}else if(this.id=='page_general'){
tipo='general'

}else{
return
}




	    table.hideColumn('type');
	    table.hideColumn('title');
	    table.hideColumn('users');
	    table.hideColumn('visitors');
	    table.hideColumn('sessions');
	    table.hideColumn('requests');


	    if(tipo=='visitors'){
		Dom.get('page_period_options').style.display='';
		  table.showColumn('users');
	    table.showColumn('visitors');
	    table.showColumn('sessions');
	    table.showColumn('requests');
	    }
	    if(tipo=='general'){
		Dom.get('page_period_options').style.display='none';
		table.showColumn('title');
		table.showColumn('type');
	

	    }
	 

	      Dom.removeClass(Dom.getElementsByClassName('table_option','button' , this.parentNode),'selected')
    Dom.addClass(this,"selected");	

	
	
	    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=site-pages-view&value=' + escape(tipo),{} );
	
  }




YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


  var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"type", label:"<?php echo _('Type')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='general'?'':'hidden:true,')?> width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"title", label:"<?php echo _('Header Title')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='general'?'':'hidden:true,')?> width:270,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"link_title", label:"<?php echo _('Link Label')?>", width:330,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"users", label:"<?php echo _('Users')?>",<?php echo($_SESSION['state']['site']['pages']['view']!='general'?'':'hidden:true,')?>width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"visitors", label:"<?php echo _('Visitors')?>",<?php echo($_SESSION['state']['site']['pages']['view']!='general'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sessions", label:"<?php echo _('Sessions')?>",<?php echo($_SESSION['state']['site']['pages']['view']!='general'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"requests", label:"<?php echo _('Requests')?>",<?php echo($_SESSION['state']['site']['pages']['view']!='general'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

						    
		
			    
				    
				     ];

	
		request="ar_sites.php?tipo=pages&parent=site&tableid=0&parent_key="+Dom.get('site_key').value;

	    this.dataSource0 = new YAHOO.util.DataSource(request);
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
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'id','title','code','url','type','link_title','visitors','sessions','requests','users'
						 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder_page_thumbnails
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site']['pages']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site']['pages']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['pages']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
   this.table0.request=request;
  this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);


	    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['site']['pages']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['pages']['f_value']?>'};
			



var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 

					    {key:"customer", label:"<?php echo _('Customer')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"handle", label:"<?php echo _('Handle')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
									,{key:"logins", label:"<?php echo _('Logins')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

						,{key:"requests", label:"<?php echo _('Pageviews')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				,{key:"last_visit", label:"<?php echo _('Last Visit')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    
				    
				    
				     ];
request="ar_sites.php?tipo=users_in_site&sf=0&tableid=1&parent_key="+Dom.get('site_key').value
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
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'customer','handle','requests','logins','last_visit'
						 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site']['users']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site']['users']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['users']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
 		this.table1.request=request;
 		this.table1.table_id=tableid;
     	this.table1.subscribe("renderEvent", myrenderEvent);

	    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['site']['users']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['users']['f_value']?>'};



		

		var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 

				{key:"query", label:"<?php echo _('Query')?>", width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"results", label:"<?php echo _('Results Shown')?>", width:50,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"date", label:"<?php echo _('Last date')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				,{key:"users", label:"<?php echo _('Register Users')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"no_users", label:"<?php echo _('No Register Users')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				,{key:"multiplicity", label:"<?php echo _('Searched Times')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					    
				    
				     ];
		request="ar_sites.php?tipo=queries&sf=0&tableid=2&parent_key="+Dom.get('site_key').value
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
			 'query','date','multiplicity','users','no_users','results'
						 ]};
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource2, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site']['queries']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site']['queries']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['queries']['order_dir']?>"
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

	    
	    this.table2.filter={key:'<?php echo$_SESSION['state']['site']['queries']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['queries']['f_value']?>'};


		var tableid=3; 
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				{key:"query", label:"<?php echo _('Query')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"date", label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"customer", label:"<?php echo _('Customer')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"handle", label:"<?php echo _('Handle')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ];
		request="ar_sites.php?tipo=query_history&sf=0&tableid="+tableid+"&parent_key="+Dom.get('site_key').value
		//alert(request)
	    this.dataSource3 = new YAHOO.util.DataSource(request);
	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
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
			 'customer','handle','date','query'
						 ]};
	    
	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource3, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site']['query_history']['nr']?>,containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site']['query_history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['query_history']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
 		this.table3.request=request;
  		this.table3.table_id=tableid;
     	this.table3.subscribe("renderEvent", myrenderEvent);

	    
	    this.table3.filter={key:'<?php echo$_SESSION['state']['site']['query_history']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['query_history']['f_value']?>'};



 var tableid=4; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:100,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"abstract", label:"<?php echo _('Description')?>", width:340,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    request="ar_history.php?tipo=history&type=site&tableid="+tableid+"&parent_key="+Dom.get('site_key').value;
	  
	    this.dataSource4 = new YAHOO.util.DataSource(request);
	    this.dataSource4.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource4.connXhrMode = "queueRequests";
	    this.dataSource4.responseSchema = {
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
			 "id"
			 ,"note"
			 ,'author','date','tipo','abstract','details'
			 ]};
	    
	    this.table4 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource4
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['site']['history']['nr']?>,containers : 'paginator4', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['site']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['site']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;

		       this.table4.table_id=tableid;
     this.table4.subscribe("renderEvent", myrenderEvent);

		    
	    this.table4.filter={key:'<?php echo$_SESSION['state']['site']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['history']['f_value']?>'};


var tableid=5; 
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				{key:"subject_name", label:"<?php echo _('Name')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"product", label:"<?php echo _('Product')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"date", label:"<?php echo _('Created')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"state", label:"<?php echo _('State')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"finish_date", label:"<?php echo _('Completed')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				     ];
		request="ar_sites.php?tipo=email_reminder&scope=back_in_stock&sf=0&tableid="+tableid+"&parent=site&parent_key="+Dom.get('site_key').value
	
	    this.dataSource5 = new YAHOO.util.DataSource(request);
	    this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource5.connXhrMode = "queueRequests";
	    this.dataSource5.responseSchema = {
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
			 'subject_name','date','product','state','finish_date'
						 ]};
	    
	    this.table5 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource5, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site']['query_history']['nr']?>,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site']['query_history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['query_history']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
 		this.table5.request=request;
  		this.table5.table_id=tableid;
     	this.table5.subscribe("renderEvent", myrenderEvent);
this.table5.getDataSource().sendRequest(null, {
		    success: function(request, response, payload) {
		        if (response.results.length == 0) {
		            get_email_reminders_numbers('back_in_stock','','')

		        } else {
		            // this.onDataReturnInitializeTable(request, response, payload);
		        }
		    },
		    scope: this.table5,
		    argument: this.table5.getState()
		});
	    
	    this.table5.filter={key:'<?php echo$_SESSION['state']['site']['query_history']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['query_history']['f_value']?>'};



	};
	get_page_thumbnails(0)
    });





function go_edit(){
request='edit_site.php?id='+Dom.get('site_key').value;

if(     Dom.get('block_view').value=='pages'){
	request+='&block_view=pages'
}

window.location=request

}


function change_table_type(parent,tipo,label){

	if(parent=='pages'){
		table_id=0
	}
	
	Dom.get('change_pages_table_type').innerHTML=label;
	
	if(tipo=='list'){
		Dom.setStyle('thumbnails'+table_id,'display','none')
		Dom.setStyle(['table'+table_id,'list_options'+table_id,'table_view_menu'+table_id],'display','')
 	}else{
		Dom.setStyle('thumbnails'+table_id,'display','')
		Dom.setStyle(['table'+table_id,'list_options'+table_id,'table_view_menu'+table_id],'display','none')
 	}
 	
 	
 	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=site-'+parent+'-table_type&value='+escape(tipo),{});
 	dialog_change_pages_table_type.hide();

   
}

function show_dialog_change_pages_table_type(){
	region1 = Dom.getRegion('change_pages_table_type'); 
    region2 = Dom.getRegion('change_pages_table_type_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_pages_table_type_menu', pos);
	dialog_change_pages_table_type.show();
}

function update_sitemap(){

   var request = 'ar_edit_sites.php?tipo=update_sitemap&site_key=' + Dom.get('site_key').value
    //alert(request)
    Dom.setStyle("update_sitemap_wait",'display','')
        Dom.setStyle("update_sitemap",'display','none')

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
           // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
Dom.setStyle("update_sitemap_wait",'display','none')
        Dom.setStyle("update_sitemap",'display','')
             Dom.get('sitemap_last_update').innerHTML=r.sitemap_last_update;
             
        Dom.setStyle("sitemap_link",'display','')

            } else {
                alert(r.msg)
            }
        }


    });
	
}

var already_clicked_email_reminders_elements_state_click=false;

function change_email_reminders_elements(e, table_id) {
    var el = this
  
        if (already_clicked_email_reminders_elements_state_click)
        {
            already_clicked_email_reminders_elements_state_click=false; // reset
            clearTimeout(alreadyclickedTimeout); // prevent this from happening
            change_email_reminders_elements_state_dblclick(el, table_id)
        }
        else
        {
            already_clicked_email_reminders_elements_state_click=true;
            alreadyclickedTimeout=setTimeout(function(){
                already_clicked_email_reminders_elements_state_click=false; // reset when it happens
                 change_email_reminders_elements_state_click(el, table_id)
            },200); // <-- dblclick tolerance here
        }
        return false;
}

function change_email_reminders_elements_state_click(el, table_id) {
    ids = ['elements_back_in_stock_email_reminders_Waiting', 'elements_back_in_stock_email_reminders_Ready', 'elements_back_in_stock_email_reminders_Send', 'elements_back_in_stock_email_reminders_Cancelled'];
    if (Dom.hasClass(el, 'selected')) {
        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }
        if (number_selected_elements > 1) {
            Dom.removeClass(el, 'selected')
        }
    } else {
        Dom.addClass(el, 'selected')
    }
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';
    for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' + ids[i] + '=1'
        } else {
            request = request + '&' + ids[i] + '=0'
        }
    }
    
  
    
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function change_email_reminders_elements_state_dblclick(el, table_id) {
    ids = ['elements_back_in_stock_email_reminders_Waiting', 'elements_back_in_stock_email_reminders_Ready', 'elements_back_in_stock_email_reminders_Send', 'elements_back_in_stock_email_reminders_Cancelled'];
      Dom.removeClass(ids, 'selected')
      
        Dom.addClass(el, 'selected')
      

    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';
    for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' + ids[i] + '=1'
        } else {
            request = request + '&' + ids[i] + '=0'
        }
    }
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}





 function init() {
 
  get_email_reminders_numbers('back_in_stock','','')
 
     //'page_period_yeartoday'
     ids = ['page_period_all', 'page_period_year', 'page_period_quarter', 'page_period_month', 'page_period_week', 'page_period_three_year', 'page_period_six_month', 'page_period_ten_day', 'page_period_day', 'page_period_hour', 'page_period_yeartoday'];
     YAHOO.util.Event.addListener(ids, "click", change_table_period, {
         'table_id': 0,
         'subject': 'page'
     });


     init_search('site');
     ids = ['details', 'pages', 'hits', 'visitors', 'reports','search_queries','changelog','email_reminders'];
     Event.addListener(ids, "click", change_block);
       ids = ['search_queries_queries', 'search_queries_history'];
          Event.addListener(ids, "click", change_search_queries_block);
     
       
     
     Event.addListener(['page_general', 'page_visitors'], "click", change_view);
     
     
     
     
    
   

     

     ids = ['elements_System', 'elements_Info', 'elements_Department', 'elements_Family','elements_Product', 'elements_ProductCategory', 'elements_FamilyCategory'];
     Event.addListener(ids, "click", change_elements);



     YAHOO.util.Event.addListener('update_sitemap', "click", update_sitemap);


     YAHOO.util.Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
     YAHOO.util.Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
        YAHOO.util.Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
     YAHOO.util.Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);
          YAHOO.util.Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
     YAHOO.util.Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);
          YAHOO.util.Event.addListener('clean_table_filter_show3', "click", show_filter, 3);
     YAHOO.util.Event.addListener('clean_table_filter_hide3', "click", hide_filter, 3);
       YAHOO.util.Event.addListener('clean_table_filter_show4', "click", show_filter, 4);
     YAHOO.util.Event.addListener('clean_table_filter_hide4', "click", hide_filter, 4);
     

   var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    oACDS.table_id = 0;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;

    var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS1.queryMatchContains = true;
    oACDS1.table_id = 1;
    var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS1);
    oAutoComp1.minQueryLength = 0;

    var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS2.queryMatchContains = true;
    oACDS2.table_id = 2;
    var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2", "f_container2", oACDS2);
    oAutoComp2.minQueryLength = 0;
    
       var oACDS3 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS3.queryMatchContains = true;
    oACDS3.table_id = 3;
    var oAutoComp3 = new YAHOO.widget.AutoComplete("f_input3", "f_container3", oACDS3);
    oAutoComp3.minQueryLength = 0;


  var oACDS4 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS4.queryMatchContains = true;
    oACDS4.table_id = 4;
    var oAutoComp4 = new YAHOO.widget.AutoComplete("f_input4", "f_container4", oACDS4);
    oAutoComp4.minQueryLength = 0;


     dialog_change_pages_table_type = new YAHOO.widget.Dialog("change_pages_table_type_menu", {
         visible: false,
         close: true,
         underlay: "none",
         draggable: false
     });
     dialog_change_pages_table_type.render();
     YAHOO.util.Event.addListener("change_pages_table_type", "click", show_dialog_change_pages_table_type);


    ids = ['elements_back_in_stock_email_reminders_Waiting', 'elements_back_in_stock_email_reminders_Ready', 'elements_back_in_stock_email_reminders_Send', 'elements_back_in_stock_email_reminders_Cancelled'];
       Event.addListener(ids, "click", change_email_reminders_elements, 5);
  
 }

 YAHOO.util.Event.onDOMReady(init);

 YAHOO.util.Event.onContentReady("rppmenu0", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {
         trigger: "rtext_rpp0"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu0", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
         trigger: "filter_name0"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });


 YAHOO.util.Event.onContentReady("rppmenu1", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {
         trigger: "rtext_rpp1"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu1", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
         trigger: "filter_name1"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });


 YAHOO.util.Event.onContentReady("rppmenu2", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {
         trigger: "rtext_rpp2"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu2", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {
         trigger: "filter_name2"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });


 YAHOO.util.Event.onContentReady("rppmenu3", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu3", {
         trigger: "rtext_rpp3"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu3", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu3", {
         trigger: "filter_name3"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });

YAHOO.util.Event.onContentReady("rppmenu4", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu4", {
         trigger: "rtext_rpp4"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu4", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu4", {
         trigger: "filter_name4"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });


