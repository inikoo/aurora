<?php
include_once('common.php')?>
var Dom   = YAHOO.util.Dom;

var link='report_geo_sales.php'

      
    YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [ 
				       {key:"id", label:"<?php echo$customers_ids[0]?>",width:45,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Customer Name')?>", width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?> width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       	,{key:"contact_since", label:"<?php echo _('Since')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:85,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"last_order", label:"<?php echo _('Last Order')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>width:85,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"orders", label:"<?php echo _('Orders')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"activity", label:"<?php echo _('Status')?>",<?php echo($_SESSION['state']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"contact_name", label:"<?php echo _('Contact Name')?>",width:160,<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"email", label:"<?php echo _('Email')?>",width:210,<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"telephone", label:"<?php echo _('Telephone')?>", width:137,<?php echo($_SESSION['state']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},className:"aright"}
				       ,{key:"address", label:"<?php echo _('Contact Address')?>", width:176,<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       ,{key:"billing_address", label:"<?php echo _('Billing Address')?>", width:170,<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       ,{key:"delivery_address", label:"<?php echo _('Delivery Address')?>", width:170,<?php echo($_SESSION['state']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       ,{key:"total_payments", label:"<?php echo _('Payments')?>",width:99,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_refunds", label:"<?php echo _('Refunds')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"net_balance", label:"<?php echo _('Balance')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"balance", label:"<?php echo _('Outstanding')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_profit", label:"<?php echo _('Profit')?>",width:90,<?php echo($_SESSION['state']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_orders", label:"<?php echo _('Rank Orders')?>",width:121,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_invoices", label:"<?php echo _('Rank Invoices')?>",width:121,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_balance", label:"<?php echo _('Rank Balance')?>",width:120,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_profits", label:"<?php echo _('Rank Profits')?>",width:120,<?php echo($_SESSION['state']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ];


	    var where_data=new Object();
	    
	    where_data={geo_constraints:'<?php echo $_SESSION['state']['country']['code']?>'};
	    var where=YAHOO.lang.JSON.stringify(where_data);
	    
	    // alert("ar_contacts.php?tipo=customers&sf=0&where="+where);
	    this.dataSource0 = new YAHOO.util.DataSource("ar_contacts.php?tipo=customers&sf=0&all_stores=1&where="+where);
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
		
		
		fields: [
			 'id',
			 'name',
			 'location',
			 'orders',
			 'email',
			 'telephone',
			 'last_order','activity',
			 'total_payments','contact_name'
			 ,"address"
			 ,"billing_address","delivery_address"
			 ,"total_paymants","total_refunds","net_balance","total_profit","balance","contact_since"
			 ,"top_orders","top_invoices","top_balance","top_profits"
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['table']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.view='<?php echo$_SESSION['state']['customers']['view']?>';

	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['table']['f_value']?>'};



var tableid=1;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
		      		{key:"country_code", label:"<?php echo _('Code')?>",width:80, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"country_name", label:"<?php echo _('Country')?>",width:320, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			     ,{key:"invoices_formated", label:"<?php echo _('Invoices')?>",width:200 ,sortable:true,className:"aright",sortOptions:{field: "invoices",defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			        ,{key:"sales_formated", label:"<?php echo _('Sales')?>",width:100 ,sortable:true,className:"aright",sortOptions:{field: "sales",defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			     
					];
		    
		      
		      this.dataSource1 = new YAHOO.util.DataSource("ar_reports.php?tipo=country_sales&tableid=1");
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
			  
			  fields: [
				  "country_name","country_code","population","gnp",'sales','sales_formated',"_name","_code","invoices",'invoices_formated'
				   ]};
		      
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['report_geo_sales']['countries']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								   
								   ,sortedBy : {
								      key: "<?php echo $_SESSION['state']['report_geo_sales']['countries']['order']?>",
								       dir: "<?php echo $_SESSION['state']['report_geo_sales']['countries']['order_dir']?>"
								   }
								  // ,dynamicData : true
								 
							       }
							       );
		      this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		     // this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		    //  this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
               //    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['report_geo_sales']['countries']['f_field']?>',value:'<?php echo$_SESSION['state']['report_geo_sales']['countries']['f_value']?>'};

	
	};
    });


function change_block(){
    ids=['overview','customers','invoices','details'];
    block_ids=['block_overview','block_customers','block_invoices','block_details'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=report_geo_sales-country-view&value='+this.id ,{});
}


function change_map_link(){
ids=['map_links_countries','map_links_wregions'];
map_ids=['map_countries','map_continents','map_wregions'];

Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected')
var the_id=this.id.replace("map_links_", "");
Dom.setStyle(map_ids,'display','none');
Dom.setStyle('map_'+the_id,'display','');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=report_geo_sales-continent-map_links&value='+escape(      the_id     ),{});
}

 function init(){

     ids=['overview','customers','invoices','details'];
YAHOO.util.Event.addListener(ids, "click",change_block);

 var ids=['map_links_countries','map_l-inks_wregions'];
YAHOO.util.Event.addListener(ids, "click",change_map_link);

 
YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
  YAHOO.util.Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 YAHOO.util.Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);

 //init_search('country_list');
 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 

var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS);
 oAutoComp1.minQueryLength = 0; 

var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS);
 oAutoCom2p.minQueryLength = 0; 
//init_search('world_region');
  alert("x")

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
 
YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
    
YAHOO.util.Event.onContentReady("rppmenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {trigger:"rtext_rpp2" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {  trigger: "filter_name2"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });    