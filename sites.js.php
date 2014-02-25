<?php
include_once('common.php');


?>
 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;
var tables;

function change_block(){
ids=['sites','pages'];
block_ids=['block_sites','block_pages'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=sites-block_view&value='+this.id ,{});
}

function change_sites_view(e, table_id) {

    var tipo = this.id;

    if (tipo == 'sites_products') tipo = 'products';
    else if (tipo == 'sites_general') tipo = 'general';
    else if (tipo == 'sites_users') tipo = 'users';
    else if (tipo == 'sites_email_reminders') tipo = 'email_reminders';

    var table = tables['table' + table_id];




    Dom.removeClass(['sites_general', 'sites_products', 'sites_users', 'sites_email_reminders'], 'selected')
    Dom.addClass(this, 'selected')




    table.hideColumn('name');
    table.hideColumn('url');
    table.hideColumn('users');
    table.hideColumn('pages');

    table.hideColumn('products');
    table.hideColumn('out_of_stock');
    table.hideColumn('out_of_stock_percentage');


    table.hideColumn('pages_products');
    table.hideColumn('pages_out_of_stock');
    table.hideColumn('pages_out_of_stock_percentage');
    table.hideColumn('email_reminders_customers');

    table.hideColumn('email_reminders_products');
    table.hideColumn('email_reminders_waiting');
    table.hideColumn('email_reminders_ready');
    table.hideColumn('email_reminders_sent');
    table.hideColumn('email_reminders_cancelled');



    if (tipo == 'general') {
        table.showColumn('name');
        table.showColumn('url');
        table.showColumn('users');
        table.showColumn('pages');


    } else if (tipo == 'products') {
        table.showColumn('products');
        table.showColumn('out_of_stock');

        table.showColumn('out_of_stock_percentage');
        table.showColumn('pages_products');
        table.showColumn('pages_out_of_stock');
        table.showColumn('pages_out_of_stock_percentage');

    } else if (tipo == 'email_reminders') {
        table.showColumn('email_reminders_customers');

        table.showColumn('email_reminders_products');
        table.showColumn('email_reminders_waiting');
        table.showColumn('email_reminders_ready');
        table.showColumn('email_reminders_sent');
        table.showColumn('email_reminders_cancelled');


    }


    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=sites-sites-view&value=' + tipo, {});


}






YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


  var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
	    			 {key:"site", label:"<?php echo _('Site')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"code", label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"type", label:"<?php echo _('Type')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"title", label:"<?php echo _('Title')?>", width:380,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   // ,{key:"url", label:"<?php echo _('URL')?>", width:280,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

						    
				    
				    
				     ];
request="ar_sites.php?tipo=pages&parent=none&tableid=0&parent_key="
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
			 'id','title','code','url','type','site'
						 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
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
 this.table0.request=request;
  this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);

	    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['site']['pages']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['pages']['f_value']?>'};
		
 var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 

				    {key:"code", label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>", width:120,<?php echo($_SESSION['state']['sites']['sites']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"url", label:"<?php echo _('URL')?>", width:280,<?php echo($_SESSION['state']['sites']['sites']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"users", label:"<?php echo _('Users')?>", width:100,<?php echo($_SESSION['state']['sites']['sites']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"pages", label:"<?php echo _('Pages')?>", width:100,<?php echo($_SESSION['state']['sites']['sites']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",<?php echo($_SESSION['state']['sites']['sites']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"products", label:"<?php echo _('Products')?>", width:100,<?php echo(($_SESSION['state']['sites']['sites']['view']=='products' or  $_SESSION['state']['sites']['sites']['view']=='general')?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"out_of_stock", label:"<?php echo _('OoS')?>", width:100,<?php echo($_SESSION['state']['sites']['sites']['view']=='products'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"out_of_stock_percentage", label:"%", width:100,<?php echo($_SESSION['state']['sites']['sites']['view']=='products'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"pages_products", label:"<?php echo _('Pages w Prods')?>", width:100,<?php echo($_SESSION['state']['sites']['sites']['view']=='products'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"pages_out_of_stock", label:"<?php echo _('Pages w OoS')?>", width:100,<?php echo($_SESSION['state']['sites']['sites']['view']=='products'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"pages_out_of_stock_percentage", label:"%", width:100,<?php echo($_SESSION['state']['sites']['sites']['view']=='products'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"email_reminders_customers", label:"<?php echo _('Customers')?>", width:100,<?php echo(($_SESSION['state']['sites']['sites']['view']=='email_reminders' )?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"email_reminders_products", label:"<?php echo _('Products')?>", width:100,<?php echo(($_SESSION['state']['sites']['sites']['view']=='email_reminders' )?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"email_reminders_waiting", label:"<?php echo _('Waiting')?>", width:100,<?php echo(($_SESSION['state']['sites']['sites']['view']=='email_reminders' )?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"email_reminders_ready", label:"<?php echo _('Ready')?>", width:100,<?php echo(($_SESSION['state']['sites']['sites']['view']=='email_reminders' )?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"email_reminders_sent", label:"<?php echo _('Sent')?>", width:100,<?php echo(($_SESSION['state']['sites']['sites']['view']=='email_reminders' )?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"email_reminders_cancelled", label:"<?php echo _('Cancelled')?>", width:100,<?php echo(($_SESSION['state']['sites']['sites']['view']=='email_reminders' )?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

						    
				    
				    
				     ];
request="ar_sites.php?tipo=sites&parent=none&tableid=1&parent_key="
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
			 'id','title','code','url','type','site','name','users','pages','pages_products','pages_out_of_stock','pages_out_of_stock_percentage','products','out_of_stock','out_of_stock_percentage',
			 'email_reminders_customers','email_reminders_products','email_reminders_waiting','email_reminders_ready','email_reminders_sent','email_reminders_cancelled'
						 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['sites']['sites']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['sites']['sites']['order']?>",
									 dir: "<?php echo$_SESSION['state']['sites']['sites']['order_dir']?>"
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

	    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['sites']['sites']['f_field']?>',value:'<?php echo$_SESSION['state']['sites']['sites']['f_value']?>'};
		





	};get_page_thumbnails(0)
    });

function show_dialog_change_pages_table_type(){
	region1 = Dom.getRegion('change_pages_table_type'); 
    region2 = Dom.getRegion('change_pages_table_type_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_pages_table_type_menu', pos);
	dialog_change_pages_table_type.show();
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
 	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=sites-'+parent+'-table_type&value='+escape(tipo),{});
 	dialog_change_pages_table_type.hide();

   
}

 function init() {

     init_search('sites');
     Event.addListener(['sites', 'pages'], "click", change_block);

     Event.addListener(['sites_general', 'sites_products','sites_users','sites_email_reminders'], "click", change_sites_view,1);




     YAHOO.util.Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
     YAHOO.util.Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);

     var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS.queryMatchContains = true;
     var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
     oAutoComp.minQueryLength = 0;

	     dialog_change_pages_table_type = new YAHOO.widget.Dialog("change_pages_table_type_menu", {
         visible: false,
         close: true,
         underlay: "none",
         draggable: false
     });
     dialog_change_pages_table_type.render();
     YAHOO.util.Event.addListener("change_pages_table_type", "click", show_dialog_change_pages_table_type);


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
