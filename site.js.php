<?php
include_once('common.php');


?>
 var Dom = YAHOO.util.Dom;
 var Event = YAHOO.util.Event;
 var tables;


 function change_block() {
    ids = ['details', 'pages', 'hits', 'visitors', 'reports', , 'search_queries', 'changelog', 'email_reminders', 'products','favorites'];
    block_ids = ['block_products', 'block_details', 'block_pages', 'block_hits', 'block_visitors', 'block_reports', 'block_search_queries', 'block_changelog', 'block_email_reminders','block_favorites'];
    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');

    if (this.id == 'hits') {
        Dom.setStyle('calendar_container', 'display', '')
    } else {
        Dom.setStyle('calendar_container', 'display', 'none')
    }


    Dom.get('block_view').value = this.id;
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=site-view&value=' + this.id, {});
}

 function change_favorites_block(){
     ids = ['favorites_products', 'favorites_customers'];
     block_ids = ['block_favorites_products', 'block_favorites_customers'];
     Dom.setStyle(block_ids, 'display', 'none');
     Dom.setStyle('block_' + this.id, 'display', '');
    
     Dom.removeClass(ids, 'selected');
     Dom.addClass(this, 'selected');
     YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=site-favorites_block&value=' + this.getAttribute('block_id'), {});

 
 }
  
    function change_email_reminders_block() {
   ids = ['email_reminders_requests', 'email_reminders_customers','email_reminders_products'];
     block_ids = ['block_email_reminders_requests', 'block_email_reminders_customers','block_email_reminders_products'];
     Dom.setStyle(block_ids, 'display', 'none');
     Dom.setStyle('block_' + this.id, 'display', '');
    
     Dom.removeClass(ids, 'selected');
     Dom.addClass(this, 'selected');

     YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=site-email_reminders_block&value=' + this.getAttribute('block_id'), {});
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

    function change_pages_block() {
   ids = ['pages_pages', 'pages_deleted_pages','pages_page_changelog','pages_product_changelog'];
     block_ids = ['block_pages_pages', 'block_pages_deleted_pages','block_pages_page_changelog','block_pages_product_changelog'];
     Dom.setStyle(block_ids, 'display', 'none');
     Dom.setStyle('block_' + this.id, 'display', '');
   
     Dom.removeClass(ids, 'selected');
     Dom.addClass(this, 'selected');

     YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=site-pages_block&value=' + this.getAttribute('block_id'), {});
 }  


function get_email_reminders_numbers(trigger, from, to) {
    var ar_file = 'ar_sites.php';
    var request = 'tipo=number_email_reminders_in_interval&trigger=' + trigger + '&parent=site&parent_key=' + Dom.get('site_key').value + '&from=' + from + '&to=' + to;

    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                for (i in r.elements_numbers) {
                    Dom.get('elements_' + r.trigger + '_email_reminders_' + i + '_number').innerHTML = r.elements_numbers[i]
                }
            }
        },
        failure: function(o) {},
        scope: this
    }, request
    );
}

function get_scopes_email_reminders_numbers(trigger, from, to) {
    var ar_file = 'ar_sites.php';
    var request = 'tipo=number_scopes_email_reminders_in_interval&trigger=' + trigger + '&parent=site&parent_key=' + Dom.get('site_key').value + '&from=' + from + '&to=' + to;

    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
           
            var r = YAHOO.lang.JSON.parse(o.responseText);
    
       if (r.state == 200) {
                for (i in r.customers_elements_numbers) {
                    Dom.get('customers_elements_' + r.trigger + '_email_reminders_' + i + '_number').innerHTML = r.customers_elements_numbers[i]

                }
                 for (i in r.products_elements_numbers) {
                    Dom.get('products_elements_' + r.trigger + '_email_reminders_' + i + '_number').innerHTML = r.products_elements_numbers[i]

                }
            }
        },
        failure: function(o) {},
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

     ids = ['page_section_elements_System', 'page_section_elements_Info', 'page_section_elements_Department', 'page_section_elements_Family', 'page_section_elements_Product', 'page_section_elements_ProductCategory', 'page_section_elements_FamilyCategory'];


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

     ids = ['page_section_elements_System', 'page_section_elements_Info', 'page_section_elements_Department', 'page_section_elements_Family', 'page_section_elements_Product', 'page_section_elements_ProductCategory', 'page_section_elements_FamilyCategory'];


    
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

    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


}

function change_pages_view(e) {

    var table = tables['table0'];

    if (this.id == 'page_visitors') {
        tipo = 'visitors'
    } else if (this.id == 'page_general') {
        tipo = 'general'

    } else if (this.id == 'page_products') {
        tipo = 'products'

    } else {
        return
    }




    table.hideColumn('type');
    table.hideColumn('title');
    table.hideColumn('users');
    table.hideColumn('visitors');
    table.hideColumn('sessions');
    table.hideColumn('requests');
    table.hideColumn('percentage_products_out_of_stock');
    table.hideColumn('products_out_of_stock');
    table.hideColumn('products');
 table.hideColumn('list_products');
    table.hideColumn('button_products');
    table.hideColumn('products_sold_out');
    table.hideColumn('link_title');



    if (tipo == 'visitors') {
        Dom.get('page_period_options').style.display = '';
        table.showColumn('users');
        table.showColumn('visitors');
        table.showColumn('sessions');
        table.showColumn('requests');
                    table.showColumn('link_title');

    } else if (tipo == 'general') {
        Dom.get('page_period_options').style.display = 'none';
        table.showColumn('title');
        table.showColumn('type');
            table.showColumn('link_title');


    }
    if (tipo == 'products') {
        Dom.get('page_period_options').style.display = 'none';

        table.showColumn('percentage_products_out_of_stock');
        table.showColumn('products_out_of_stock');
        table.showColumn('products');
           table.showColumn('list_products');
    table.showColumn('button_products');
    table.showColumn('products_sold_out');
    }


    Dom.removeClass(Dom.getElementsByClassName('table_option', 'button', this.parentNode), 'selected')
    Dom.addClass(this, "selected");



    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=site-pages-view&value=' + escape(tipo), {});

}






YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


  var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
	    					    									{key:"state", label:"", width:16,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}			

				    									,{key:"flag", label:"", width:16,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, action:'dialog',object:'flag'}			
	

	,{key:"id", label:"", width:120,sortable:false,hidden:true}

				    ,{key:"code", label:"<?php echo _('Code')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"type", label:"<?php echo _('Type')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='general'?'':'hidden:true,')?> width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"title", label:"<?php echo _('Header Title')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='general'?'':'hidden:true,')?> width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"link_title", label:"<?php echo _('Link Label')?>",<?php echo(($_SESSION['state']['site']['pages']['view']=='visitors' or $_SESSION['state']['site']['pages']['view']=='general')?'':'hidden:true,')?> width:280,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"users", label:"<?php echo _('Users')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='products'?'':'hidden:true,')?>width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"visitors", label:"<?php echo _('Visitors')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='visitors'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sessions", label:"<?php echo _('Sessions')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='visitors'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"requests", label:"<?php echo _('Requests')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='visitors'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"products", label:"<?php echo _('Products')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='products'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 
				 					,{key:"list_products", label:"<?php echo _('In list')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='products'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"button_products", label:"<?php echo _('Buttons')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='products'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				 ,{key:"products_out_of_stock", label:"<?php echo _('Out of Stock')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='products'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"percentage_products_out_of_stock", label:"%",<?php echo($_SESSION['state']['site']['pages']['view']=='products'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
		    				    ,{key:"products_sold_out", label:"<?php echo _('Sold Out')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='products'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

						    
		
			    
				    
				     ];

	
		request="ar_sites.php?tipo=pages&sf=0&parent=site&tableid=0&parent_key="+Dom.get('site_key').value;
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
			 'id','title','code','url','type','link_title','visitors','sessions','requests','users','products','products_out_of_stock','percentage_products_out_of_stock',
			 			 'list_products','button_products','products_sold_out','flag','id','state'

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
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
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
	      this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);
	    
	    
	    
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
	    request="ar_history.php?tipo=history&sf=0&type=site&tableid="+tableid+"&parent_key="+Dom.get('site_key').value;
	 
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
				,{key:"state", label:"<?php echo _('State')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"finish_date", label:"<?php echo _('Completed')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

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
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site']['email_reminders']['nr']?>,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site']['email_reminders']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['email_reminders']['order_dir']?>"
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
	    
	    this.table5.filter={key:'<?php echo$_SESSION['state']['site']['email_reminders']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['email_reminders']['f_value']?>'};


		var tableid=6; 
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				{key:"name", label:"<?php echo _('Name')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"products", label:"<?php echo _('Products')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"first_created", label:"<?php echo _('First Created')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"last_finish", label:"<?php echo _('Last Completed')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				     ];
		request="ar_sites.php?tipo=customers_email_reminder&sf=0&scope=back_in_stock&sf=0&tableid="+tableid+"&parent=site&parent_key="+Dom.get('site_key').value
	
	    this.dataSource6 = new YAHOO.util.DataSource(request);
	    this.dataSource6.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource6.connXhrMode = "queueRequests";
	    this.dataSource6.responseSchema = {
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
			 'name','products','first_created','last_finish'
						 ]};
	    
	    this.table6 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource6, {
							 //draggableColumns:true,
							   renderLoopSize: 60,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site']['email_reminders_customers']['nr']?>,containers : 'paginator6', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site']['email_reminders_customers']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['email_reminders_customers']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table6.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table6.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table6.doBeforePaginatorChange = mydoBeforePaginatorChange;
 		this.table6.request=request;
  		this.table6.table_id=tableid;
     	this.table6.subscribe("renderEvent", myrenderEvent);
		this.table6.getDataSource().sendRequest(null, {
		    success: function(request, response, payload) {
		        if (response.results.length == 0) {
		            get_scopes_email_reminders_numbers('back_in_stock','','')

		        } else {
		            // this.onDataReturnInitializeTable(request, response, payload);
		        }
		    },
		    scope: this.table6,
		    argument: this.table6.getState()
		});
	    
	    this.table6.filter={key:'<?php echo$_SESSION['state']['site']['email_reminders_customers']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['email_reminders_customers']['f_value']?>'};

		var tableid=7; 
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
			{key:"code", label:"<?php echo _('Code')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			,{key:"formated_web_configuration", label:"<?php echo _('Web State')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"customers", label:"<?php echo _('Customers')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"expected", label:"<?php echo _('Expected')?>", width:120,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"first_created", label:"<?php echo _('First Created')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"last_finish", label:"<?php echo _('Last Completed')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];
		request="ar_sites.php?tipo=products_email_reminder&scope=back_in_stock&sf=0&tableid="+tableid+"&parent=site&parent_key="+Dom.get('site_key').value
	
	    this.dataSource7 = new YAHOO.util.DataSource(request);
	    this.dataSource7.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource7.connXhrMode = "queueRequests";
	    this.dataSource7.responseSchema = {
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
			 'code','customers','first_created','last_finish','formated_web_configuration','expected'
						 ]};
	    
	    this.table7 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource7, {
							 //draggableColumns:true,
							   renderLoopSize: 70,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site']['email_reminders_products']['nr']?>,containers : 'paginator7', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info7'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site']['email_reminders_products']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['email_reminders_products']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table7.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table7.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table7.doBeforePaginatorChange = mydoBeforePaginatorChange;
 		this.table7.request=request;
  		this.table7.table_id=tableid;
     	this.table7.subscribe("renderEvent", myrenderEvent);
		this.table7.getDataSource().sendRequest(null, {
		    success: function(request, response, payload) {
		        if (response.results.length == 0) {
		            get_scopes_email_reminders_numbers('back_in_stock','','')

		        } else {
		            // this.onDataReturnInitializeTable(request, response, payload);
		        }
		    },
		    scope: this.table7,
		    argument: this.table7.getState()
		});
	    
	    this.table7.filter={key:'<?php echo$_SESSION['state']['site']['email_reminders_products']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['email_reminders_products']['f_value']?>'};


 var tableid=8; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"", width:120,sortable:false,hidden:true}

				    ,{key:"code", label:"<?php echo _('Code')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"type", label:"<?php echo _('Type')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 //   ,{key:"title", label:"<?php echo _('Header Title')?>", width:270,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"link_title", label:"<?php echo _('Link Label')?>", width:310,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  				    ,{key:"date", label:"<?php echo _('Deleted date')?>",width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 
							    
		
			    
				    
				     ];

	
		request="ar_sites.php?tipo=deleted_pages&sf=0&parent=site&tableid="+tableid+"&parent_key="+Dom.get('site_key').value;
	    this.dataSource8 = new YAHOO.util.DataSource(request);
	    this.dataSource8.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource8.connXhrMode = "queueRequests";
	    this.dataSource8.responseSchema = {
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
			 'id','title','code','url','type','link_title','date'

						 ]};
	    
	    this.table8 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource8, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder_page_thumbnails
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site']['deleted_pages']['nr']?>,containers : 'paginator8', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info8'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site']['deleted_pages']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['deleted_pages']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table8.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table8.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table8.doBeforePaginatorChange = mydoBeforePaginatorChange;
	      
	    
	    
	    
   this.table8.request=request;
  this.table8.table_id=tableid;
     this.table8.subscribe("renderEvent", myrenderEvent);
	    this.table8.filter={key:'<?php echo$_SESSION['state']['site']['deleted_pages']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['deleted_pages']['f_value']?>'};
			


var tableid=9; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 

				    {key:"code", label:"<?php echo _('Code')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   ,{key:"title_label", label:"<?php echo _('Label')?>", width:270,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"state", label:"<?php echo _('State')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"operation", label:"<?php echo _('Operation')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  				    ,{key:"date", label:"<?php echo _('Deleted date')?>",width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 
							    
		
			    
				    
				     ];

	
		request="ar_sites.php?tipo=pages_state_timeline&sf=0&parent=site&tableid="+tableid+"&parent_key="+Dom.get('site_key').value;
	   
	   this.dataSource9 = new YAHOO.util.DataSource(request);
	    this.dataSource9.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource9.connXhrMode = "queueRequests";
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
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'id','code','state','operation','title_label','date'

						 ]};
	    
	    this.table9 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource9, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder_page_thumbnails
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site']['page_changelog']['nr']?>,containers : 'paginator9', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [9,25,50,90,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info9'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site']['page_changelog']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['page_changelog']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table9.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table9.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table9.doBeforePaginatorChange = mydoBeforePaginatorChange;
	      
	    
	    
	    
   this.table9.request=request;
  this.table9.table_id=tableid;
     this.table9.subscribe("renderEvent", myrenderEvent);
	    this.table9.filter={key:'<?php echo$_SESSION['state']['site']['page_changelog']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['page_changelog']['f_value']?>'};
			



var tableid=10; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 

				    {key:"code", label:"<?php echo _('Code')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   ,{key:"description", label:"<?php echo _('Descrition')?>", width:270,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			
,{key:"availability", label:"<?php echo _('Availability')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

,{key:"web_state", label:"<?php echo _('Web State')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  	,{key:"date", label:"<?php echo _('Date')?>",width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 
							    
		
			    
				    
				     ];

	
		request="ar_assets.php?tipo=products_availability_timeline&sf=0&parent=site&tableid="+tableid+"&parent_key="+Dom.get('store_key').value;
	  
	   this.dataSource10 = new YAHOO.util.DataSource(request);
	    this.dataSource10.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource10.connXhrMode = "queueRequests";
	    this.dataSource10.responseSchema = {
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
			 'code','description','web_state','date','availability'

						 ]};
	    
	    this.table10 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource10, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder_page_thumbnails
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site']['product_changelog']['nr']?>,containers : 'paginator10', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info10'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site']['product_changelog']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['product_changelog']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table10.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table10.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table10.doBeforePaginatorChange = mydoBeforePaginatorChange;
	      
	    
	    
	    
   this.table10.request=request;
  this.table10.table_id=tableid;
     this.table10.subscribe("renderEvent", myrenderEvent);
	    this.table10.filter={key:'<?php echo$_SESSION['state']['site']['product_changelog']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['product_changelog']['f_value']?>'};
	


 var tableid=11; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 

				    {key:"customer", label:"<?php echo _('User')?>", width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"page", label:"<?php echo _('Page')?>", width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					,{key:"date", label:"<?php echo _('Date')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}		    

	//	,{key:"handle", label:"<?php echo _('Email')?>", width:150,hidden:true,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"previous_page", label:"<?php echo _('Previous Page')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			//	,{key:"ip", label:"<?php echo _('IP')?>", width:100,hidden:true,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    
				    
				     ];

request="ar_sites.php?tipo=requests&parent=site&tableid="+tableid+"&parent_key="+Dom.get('site_key').value+'&to='+Dom.get('to').value+'&from='+Dom.get('from').value
	//alert(request)
	this.dataSource11 = new YAHOO.util.DataSource(request);
	    this.dataSource11.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource11.connXhrMode = "queueRequests";
	    this.dataSource11.responseSchema = {
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
			 'customer','handle','ip','date','previous_page','page'
						 ]};
	    
	    this.table11 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource11, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        

									      rowsPerPage:<?php echo$_SESSION['state']['site']['requests']['nr']?>,containers : 'paginator11', 

 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info11'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {

									 key: "<?php echo$_SESSION['state']['site']['requests']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['requests']['order_dir']?>"

								     }
							   ,dynamicData : true

						     }
						     );
	    this.table11.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table11.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table11.doBeforePaginatorChange = mydoBeforePaginatorChange;
 this.table11.request=request;
  this.table11.table_id=tableid;
     this.table11.subscribe("renderEvent", myrenderEvent);

	    

	    this.table11.filter={key:'<?php echo$_SESSION['state']['site']['requests']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['requests']['f_value']?>'};


var tableid=12; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"name", label:"<?php echo _('Name')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"customers", label:"<?php echo _('Customers')?>", width:180,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					,{key:"last_favorited", label:"<?php echo _('Last Favorited')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}		    

				    
				     ];

request="ar_assets.php?tipo=favorite_products&parent=site&tableid="+tableid+"&parent_key="+Dom.get('site_key').value+'&to='+Dom.get('to').value+'&from='+Dom.get('from').value
	this.dataSource12 = new YAHOO.util.DataSource(request);
	    this.dataSource12.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource12.connXhrMode = "queueRequests";
	    this.dataSource12.responseSchema = {
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
			 'code','name','customers','last_favorited'
						 ]};
	    
	    this.table12 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource12, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        

									      rowsPerPage:<?php echo$_SESSION['state']['site']['favorites_products']['nr']?>,
									      containers : 'paginator12', 

 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info12'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {

									 key: "<?php echo$_SESSION['state']['site']['favorites_products']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['favorites_products']['order_dir']?>"

								     }
							   ,dynamicData : true

						     }
						     );
	    this.table12.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table12.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table12.doBeforePaginatorChange = mydoBeforePaginatorChange;
 this.table12.request=request;
  this.table12.table_id=tableid;
     this.table12.subscribe("renderEvent", myrenderEvent);

	    

	    this.table12.filter={key:'<?php echo$_SESSION['state']['site']['favorites_products']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['favorites_products']['f_value']?>'};





 var tableid=14; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 

				    {key:"name", label:"<?php echo _('Customer')?>", width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"products", label:"<?php echo _('Products')?>", width:180,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					,{key:"last_favorited", label:"<?php echo _('Last Favorited')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}		    

				    
				     ];

request="ar_contacts.php?tipo=customers_how_favorite_a_product&parent=site&tableid="+tableid+"&parent_key="+Dom.get('site_key').value+'&to='+Dom.get('to').value+'&from='+Dom.get('from').value
	//alert(request)
	this.dataSource14 = new YAHOO.util.DataSource(request);
	    this.dataSource14.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource14.connXhrMode = "queueRequests";
	    this.dataSource14.responseSchema = {
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
			 'name','products','last_favorited'
						 ]};
	    
	    this.table14 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource14, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        

									      rowsPerPage:<?php echo$_SESSION['state']['site']['favorites_customers']['nr']?>,
									      containers : 'paginator14', 

 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info14'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {

									 key: "<?php echo$_SESSION['state']['site']['favorites_customers']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['favorites_customers']['order_dir']?>"

								     }
							   ,dynamicData : true

						     }
						     );
	    this.table14.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table14.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table14.doBeforePaginatorChange = mydoBeforePaginatorChange;
 this.table14.request=request;
  this.table14.table_id=tableid;
     this.table14.subscribe("renderEvent", myrenderEvent);

	    

	    this.table14.filter={key:'<?php echo$_SESSION['state']['site']['favorites_customers']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['favorites_customers']['f_value']?>'};





	};
	get_page_thumbnails(0)
    });


function show_cell_dialog(datatable, oArgs) {

    var target = oArgs.target;
    var column = datatable.getColumn(target);
    var record = datatable.getRecord(target);
    var recordIndex = datatable.getRecordIndex(record);

    switch (column.object) {
    case 'flag':

       Dom.get('edit_flag_page_key').value = record.getData('id');
       Dom.get('edit_flag_table_record_index').value=recordIndex;
       Dom.removeClass(Dom.getElementsByClassName('buttons','button','site_flags'),'selected')
     
       Dom.addClass('flag_'+record.getData('flag_value'),'selected')
       
       
//        Dom.get('delete_from_list_category_code').innerHTML = record.getData('code');
        region1 = Dom.getRegion(target);
        region2 = Dom.getRegion('dialog_edit_flag');
        var pos = [region1.left+30 , region1.top]
        Dom.setXY('dialog_edit_flag', pos);
        dialog_edit_flag.show();
        break;
    }

}


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
    ids = ['elements_back_in_stock_email_reminders_Waiting', 'elements_back_in_stock_email_reminders_Ready', 'elements_back_in_stock_email_reminders_Sent', 'elements_back_in_stock_email_reminders_Cancelled'];
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
    ids = ['elements_back_in_stock_email_reminders_Waiting', 'elements_back_in_stock_email_reminders_Ready', 'elements_back_in_stock_email_reminders_Sent', 'elements_back_in_stock_email_reminders_Cancelled'];
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

var already_clicked_customers_email_reminders_elements_state_click=false;

function change_customers_email_reminders_elements(e, table_id) {
    var el = this
  
        if (already_clicked_customers_email_reminders_elements_state_click)
        {
            already_clicked_customers_email_reminders_elements_state_click=false; // reset
            clearTimeout(customers_email_reminders_alreadyclickedTimeout); // prevent this from happening
            change_customers_email_reminders_elements_state_dblclick(el, table_id)
        }
        else
        {
            already_clicked_customers_email_reminders_elements_state_click=true;
            customers_email_reminders_alreadyclickedTimeout=setTimeout(function(){
                already_clicked_customers_email_reminders_elements_state_click=false; // reset when it happens
                 change_customers_email_reminders_elements_state_click(el, table_id)
            },200); // <-- dblclick tolerance here
        }
        return false;
}

function change_customers_email_reminders_elements_state_click(el, table_id) {
    ids = ['customers_elements_back_in_stock_email_reminders_Done', 'customers_elements_back_in_stock_email_reminders_Pending'];
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

function change_customers_email_reminders_elements_state_dblclick(el, table_id) {
    ids = ['customers_elements_back_in_stock_email_reminders_Done', 'customers_elements_back_in_stock_email_reminders_Pending'];
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


function save_page_flag(key, value) {
    page_id = Dom.get('edit_flag_page_key').value;
    table_record_index = Dom.get('edit_flag_table_record_index').value;

    var request = 'ar_edit_sites.php?tipo=edit_page_flag&key=' + key + '&newvalue=' + value + '&id=' + page_id + '&okey=' + key + '&table_record_index=' + table_record_index
    	//alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);


            if (r.state == 200) {

                var table = tables['table0'];
                record = table.getRecord(r.record_index);

                var data = record.getData();
                data['flag'] = r.flag;
                data['flag_value'] = r.flag_value;

                table.updateRow(r.record_index, data);


                for (x in r.pages_flag_data) {
                    Dom.get('page_flags_elements_' + r.pages_flag_data[x].color + '_number').innerHTML = r.pages_flag_data[x].number;
                }

                // Dom.get('edit_flag_label').innerHTML = r.flag_label;
                //Dom.get('edit_flag_icon').src = 'art/icons/' + r.flag_icon;

                //Dom.removeClass(Dom.getElementsByClassName('flag'), 'selected')
                //Dom.addClass('flag_' + r.newvalue, 'selected')
                dialog_edit_flag.hide()


                //	window.page.reload()
            }

        }
    });

}



var already_clicked_page_flags_elements_click = false
function change_page_flags_elements(el, elements_type) {

  //  var el = this

    if (already_clicked_page_flags_elements_click) {
        already_clicked_page_flags_elements_click = false; // reset
        clearTimeout(alreadyclickedTimeout); // prevent this from happening
        change_page_flags_elements_dblclick(el, elements_type)
    } else {
        already_clicked_page_flags_elements_click = true;
        alreadyclickedTimeout = setTimeout(function() {
            already_clicked_page_flags_elements_click = false; // reset when it happens
            change_page_flags_elements_click(el, elements_type)
        }, 300); // <-- dblclick tolerance here
    }
    return false;
}

function change_page_flags_elements_click(el, elements_type) {

    ids = ['page_flags_elements_Yellow', 'page_flags_elements_Red', 'page_flags_elements_Purple', 'page_flags_elements_Pink', 'page_flags_elements_Orange', 'page_flags_elements_Green', 'page_flags_elements_Blue'];


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
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function change_page_flags_elements_dblclick(el, elements_type) {

    ids = ['page_flags_elements_Yellow', 'page_flags_elements_Red', 'page_flags_elements_Purple', 'page_flags_elements_Pink', 'page_flags_elements_Orange', 'page_flags_elements_Green', 'page_flags_elements_Blue'];

    Dom.removeClass(ids, 'selected')
    Dom.addClass(el, 'selected')




    var request = '';
    for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' + ids[i] + '=1'
        } else {
            request = request + '&' + ids[i] + '=0'

        }
    }
  

    table_id = 0;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
  
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}




var already_clicked_page_state_elements_click = false
function change_page_state_elements(el, elements_type) {

   var el = this

    if (already_clicked_page_state_elements_click) {
        already_clicked_page_state_elements_click = false; // reset
        clearTimeout(alreadyclickedTimeout); // prevent this from happening
        change_page_state_elements_dblclick(el, elements_type)
    } else {
        already_clicked_page_state_elements_click = true;
        alreadyclickedTimeout = setTimeout(function() {
            already_clicked_page_state_elements_click = false; // reset when it happens
            change_page_state_elements_click(el, elements_type)
        }, 300); // <-- dblclick tolerance here
    }
    return false;
}

function change_page_state_elements_click(el, elements_type) {



    ids = ['page_state_elements_Online', 'page_state_elements_Offline'];


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
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function change_page_state_elements_dblclick(el, elements_type) {

    ids = ['page_state_elements_Online', 'page_state_elements_Offline'];

    Dom.removeClass(ids, 'selected')
    Dom.addClass(el, 'selected')




    var request = '';
    for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' + ids[i] + '=1'
        } else {
            request = request + '&' + ids[i] + '=0'

        }
    }
  

    table_id = 0;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
  
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}






var already_clicked_products_email_reminders_elements_state_click=false;

function change_products_email_reminders_elements(e, table_id) {
    var el = this
  
        if (already_clicked_products_email_reminders_elements_state_click)
        {
            already_clicked_products_email_reminders_elements_state_click=false; // reset
            clearTimeout(products_email_reminders_alreadyclickedTimeout); // prevent this from happening
            change_products_email_reminders_elements_state_dblclick(el, table_id)
        }
        else
        {
            already_clicked_products_email_reminders_elements_state_click=true;
            products_email_reminders_alreadyclickedTimeout=setTimeout(function(){
                already_clicked_products_email_reminders_elements_state_click=false; // reset when it happens
                 change_products_email_reminders_elements_state_click(el, table_id)
            },200); // <-- dblclick tolerance here
        }
        return false;
}

function change_products_email_reminders_elements_state_click(el, table_id) {
    ids = ['products_elements_back_in_stock_email_reminders_Done', 'products_elements_back_in_stock_email_reminders_Pending'];
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

function change_products_email_reminders_elements_state_dblclick(el, table_id) {
    ids = ['products_elements_back_in_stock_email_reminders_Done', 'products_elements_back_in_stock_email_reminders_Pending'];
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
function show_dialog_change_page_element_chooser() {
    region1 = Dom.getRegion('page_element_chooser_menu_button');
    region2 = Dom.getRegion('dialog_change_page_element_chooser');
    var pos = [region1.right - region2.width, region1.bottom + 3]
    Dom.setXY('dialog_change_page_element_chooser', pos);
    dialog_change_page_element_chooser.show()
}

function change_pages_element_chooser(elements_type) {

    Dom.setStyle(['page_section_chooser', 'page_flags_chooser', 'page_state_chooser'], 'display', 'none')
    Dom.setStyle('page_' + elements_type + '_chooser', 'display', '')
    Dom.removeClass(['pages_element_chooser_section', 'pages_element_chooser_flags', 'pages_element_chooser_state', ], 'selected')
    Dom.addClass('pages_element_chooser_' + elements_type, 'selected')
    dialog_change_page_element_chooser.hide()

    var table = tables.table0;
    var datasource = tables.dataSource0;
    var request = '&elements_type=' + elements_type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}


var already_clicked_requests_elements_click = false
function change_requests_elements() {
el=this;

var elements_type='';

    if (already_clicked_requests_elements_click) {
        already_clicked_requests_elements_click = false; // reset
        clearTimeout(alreadyclickedTimeout); // prevent this from happening
        change_requests_elements_dblclick(el, elements_type)
    } else {
        already_clicked_requests_elements_click = true;
        alreadyclickedTimeout = setTimeout(function() {
            already_clicked_requests_elements_click = false; // reset when it happens
        
            change_requests_elements_click(el, elements_type)
        }, 300); // <-- dblclick tolerance here
    }
    return false;
}

function change_requests_elements_click(el,elements_type) {


     ids = ['requests_elements_User', 'requests_elements_NoUser'];

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
  

    table_id = 11;
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

function change_requests_elements_dblclick(el,elements_type) {

     ids = ['requests_elements_User', 'requests_elements_NoUser'];


    
         Dom.removeClass(ids, 'selected')

     Dom.addClass(el, 'selected')

    table_id = 11;
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


function post_change_period_actions(r) {
    period = r.period;
    to = r.to;
    from = r.from;


    request = '&from=' + from + '&to=' + to+'&interval_period='+period;

    table_id = 11
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

    Dom.get('rtext11').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp11').innerHTML = '';


    get_requests_numbers(from, to)
   

}





function get_requests_numbers(from, to) {


    var ar_file = 'ar_sites.php';
    var request = 'tipo=get_interval_requests_elements_numbers&parent=site&parent_key=' + Dom.get('site_key').value + '&from=' + from + '&to=' + to;
    Dom.get('requests_elements_NoUser_number').innerHTML = '<img src="art/loading.gif" style="height:12.9px" />';
    Dom.get('requests_elements_User_number').innerHTML = '<img src="art/loading.gif" style="height:12.9px" />';
  
 //alert(ar_file+'?'+request)

    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
         //   alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                Dom.get('requests_elements_NoUser_number').innerHTML = r.elements_numbers.NoUser
                Dom.get('requests_elements_User_number').innerHTML = r.elements_numbers.User
                
            }
        },
        failure: function(o) {
        },
        scope: this
    }, request

    );
}



 function init() {

  from = Dom.get('from').value
    to = Dom.get('to').value

    get_requests_numbers(from, to)

     get_email_reminders_numbers('back_in_stock', '', '')
     get_scopes_email_reminders_numbers('back_in_stock', '', '')

     //'page_period_yeartoday'
     ids = ['page_period_all', 'page_period_year', 'page_period_quarter', 'page_period_month', 'page_period_week', 'page_period_three_year', 'page_period_six_month', 'page_period_ten_day', 'page_period_day', 'page_period_hour', 'page_period_yeartoday'];
     YAHOO.util.Event.addListener(ids, "click", change_table_period, {
         'table_id': 0,
         'subject': 'page'
     });


     init_search('site');
     ids = ['details', 'pages', 'hits', 'visitors', 'reports', 'search_queries', 'changelog', 'email_reminders', 'products','favorites'];
     Event.addListener(ids, "click", change_block);
     
     ids = ['search_queries_queries', 'search_queries_history'];
     Event.addListener(ids, "click", change_search_queries_block);
     
     ids = ['favorites_products', 'favorites_customers'];
     Event.addListener(ids, "click", change_favorites_block);
     

     ids = ['email_reminders_requests', 'email_reminders_customers', 'email_reminders_products'];
     Event.addListener(ids, "click", change_email_reminders_block);


     Event.addListener(['page_general', 'page_visitors', 'page_products'], "click", change_pages_view);
     Event.addListener(['pages_pages', 'pages_deleted_pages', 'pages_page_changelog', 'pages_product_changelog'], "click", change_pages_block);

     Event.addListener(['requests_elements_User', 'requests_elements_NoUser'], "click", change_requests_elements);



     ids = ['page_section_elements_System', 'page_section_elements_Info', 'page_section_elements_Department', 'page_section_elements_Family', 'page_section_elements_Product', 'page_section_elements_ProductCategory', 'page_section_elements_FamilyCategory'];
     Event.addListener(ids, "click", change_elements);

ids = ['page_state_elements_Online', 'page_state_elements_Offline'];
     Event.addListener(ids, "click", change_page_state_elements);


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

     YAHOO.util.Event.addListener('clean_table_filter_show5', "click", show_filter, 5);
     YAHOO.util.Event.addListener('clean_table_filter_hide5', "click", hide_filter, 5);
     YAHOO.util.Event.addListener('clean_table_filter_show6', "click", show_filter, 6);
     YAHOO.util.Event.addListener('clean_table_filter_hide6', "click", hide_filter, 6);
     YAHOO.util.Event.addListener('clean_table_filter_show7', "click", show_filter, 7);
     YAHOO.util.Event.addListener('clean_table_filter_hide7', "click", hide_filter, 7);
     
     YAHOO.util.Event.addListener('clean_table_filter_show8', "click", show_filter, 8);
     YAHOO.util.Event.addListener('clean_table_filter_hide8', "click", hide_filter, 8);
     
     YAHOO.util.Event.addListener('clean_table_filter_show10', "click", show_filter, 10);
     YAHOO.util.Event.addListener('clean_table_filter_hide10', "click", hide_filter, 10);
     
     YAHOO.util.Event.addListener('clean_table_filter_show10', "click", show_filter, 10);
     YAHOO.util.Event.addListener('clean_table_filter_hide10', "click", hide_filter, 10);
     
     YAHOO.util.Event.addListener('clean_table_filter_show11', "click", show_filter, 11);
     YAHOO.util.Event.addListener('clean_table_filter_hide11', "click", hide_filter, 11);

     YAHOO.util.Event.addListener('clean_table_filter_show12', "click", show_filter, 12);
     YAHOO.util.Event.addListener('clean_table_filter_hide12', "click", hide_filter, 12);


     YAHOO.util.Event.addListener('clean_table_filter_show14', "click", show_filter, 14);
     YAHOO.util.Event.addListener('clean_table_filter_hide14', "click", hide_filter, 14);


     
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

     var oACDS5 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS5.queryMatchContains = true;
     oACDS5.table_id = 5;
     var oAutoComp5 = new YAHOO.widget.AutoComplete("f_input5", "f_container5", oACDS5);
     oAutoComp5.minQueryLength = 0;

     var oACDS6 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS6.queryMatchContains = true;
     oACDS6.table_id = 6;
     var oAutoComp6 = new YAHOO.widget.AutoComplete("f_input6", "f_container6", oACDS6);
     oAutoComp6.minQueryLength = 0;

     var oACDS7 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS7.queryMatchContains = true;
     oACDS7.table_id = 7;
     var oAutoComp7 = new YAHOO.widget.AutoComplete("f_input7", "f_container7", oACDS7);
     oAutoComp7.minQueryLength = 0;


     var oACDS8 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS8.queryMatchContains = true;
     oACDS8.table_id = 8;
     var oAutoComp8 = new YAHOO.widget.AutoComplete("f_input8", "f_container8", oACDS8);
     oAutoComp8.minQueryLength = 0;


     var oACDS10 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS10.queryMatchContains = true;
     oACDS10.table_id = 10;
     var oAutoComp10 = new YAHOO.widget.AutoComplete("f_input10", "f_container10", oACDS10);
     oAutoComp10.minQueryLength = 0;


     var oACDS10 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS10.queryMatchContains = true;
     oACDS10.table_id = 10;
     var oAutoComp10 = new YAHOO.widget.AutoComplete("f_input10", "f_container10", oACDS10);
     oAutoComp10.minQueryLength = 0;

   var oACDS11 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS11.queryMatchContains = true;
     oACDS11.table_id = 11;
     var oAutoComp11 = new YAHOO.widget.AutoComplete("f_input11", "f_container11", oACDS11);
     oAutoComp11.minQueryLength = 0;

  var oACDS12 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS12.queryMatchContains = true;
     oACDS12.table_id = 12;
     var oAutoComp12 = new YAHOO.widget.AutoComplete("f_input12", "f_container12", oACDS12);
     oAutoComp12.minQueryLength = 0;



  var oACDS14 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS14.queryMatchContains = true;
     oACDS14.table_id = 14;
     var oAutoComp14 = new YAHOO.widget.AutoComplete("f_input14", "f_container14", oACDS14);
     oAutoComp14.minQueryLength = 0;



     dialog_change_pages_table_type = new YAHOO.widget.Dialog("change_pages_table_type_menu", {
         visible: false,
         close: true,
         underlay: "none",
         draggable: false
     });
     dialog_change_pages_table_type.render();
     YAHOO.util.Event.addListener("change_pages_table_type", "click", show_dialog_change_pages_table_type);


     ids = ['elements_back_in_stock_email_reminders_Waiting', 'elements_back_in_stock_email_reminders_Ready', 'elements_back_in_stock_email_reminders_Sent', 'elements_back_in_stock_email_reminders_Cancelled'];
     Event.addListener(ids, "click", change_email_reminders_elements, 5);

     ids = ['customers_elements_back_in_stock_email_reminders_Done', 'customers_elements_back_in_stock_email_reminders_Pending'];
     Event.addListener(ids, "click", change_customers_email_reminders_elements, 6);
     ids = ['products_elements_back_in_stock_email_reminders_Done', 'products_elements_back_in_stock_email_reminders_Pending'];
     Event.addListener(ids, "click", change_products_email_reminders_elements, 7);


     dialog_change_page_element_chooser = new YAHOO.widget.Dialog("dialog_change_page_element_chooser", {
         visible: false,
         close: true,
         underlay: "none",
         draggable: false
     });
     dialog_change_page_element_chooser.render();
     Event.addListener("page_element_chooser_menu_button", "click", show_dialog_change_page_element_chooser);


  dialog_edit_flag = new YAHOO.widget.Dialog("dialog_edit_flag", {
    
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_edit_flag.render();

 }


 YAHOO.util.Event.onDOMReady(init);
 YAHOO.util.Event.onContentReady("rppmenu7", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu7", {
         trigger: "rtext_rpp7"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu7", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu7", {
         trigger: "filter_name7"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
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
 
 YAHOO.util.Event.onContentReady("rppmenu5", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu5", {
         trigger: "rtext_rpp5"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu5", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu5", {
         trigger: "filter_name5"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 
 YAHOO.util.Event.onContentReady("rppmenu6", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu6", {
         trigger: "rtext_rpp6"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu6", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu6", {
         trigger: "filter_name6"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 

 YAHOO.util.Event.onContentReady("rppmenu8", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu8", {
         trigger: "rtext_rpp8"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu8", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu8", {
         trigger: "filter_name8"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 


 YAHOO.util.Event.onContentReady("rppmenu9", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu9", {
         trigger: "rtext_rpp9"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu9", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu9", {
         trigger: "filter_name9"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 


 YAHOO.util.Event.onContentReady("rppmenu10", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu10", {
         trigger: "rtext_rpp10"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu10", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu10", {
         trigger: "filter_name10"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 
  YAHOO.util.Event.onContentReady("rppmenu11", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu11", {
         trigger: "rtext_rpp11"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu11", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu11", {
         trigger: "filter_name11"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 
 
   YAHOO.util.Event.onContentReady("rppmenu12", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu12", {
         trigger: "rtext_rpp12"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu12", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu12", {
         trigger: "filter_name12"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 
 
 
   YAHOO.util.Event.onContentReady("rppmenu14", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu14", {
         trigger: "rtext_rpp14"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu14", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu14", {
         trigger: "filter_name14"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 




