<?php
include_once('common.php');

print "var store_key=".$_REQUEST['store_key'].";"

?>
   var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;
     
        var customer_views_ids = ['general', 'contact', 'address', 'ship_to_address', 'balance', 'rank', 'weblog'];

     
var dialog_export;
var category_labels={'total':'<?php echo _('Number')?>','growth':'<?php echo _('Growth')?>'};
var period_labels={'m':'<?php echo _('Montly')?>','y':'<?php echo _('Yearly')?>','w':'<?php echo _('Weekly')?>','q':'<?php echo _('Quarterly')?>'};
var pie_period_labels={'m':'<?php echo _('Month')?>','y':'<?php echo _('Year')?>','w':'<?php echo _('Week')?>','q':'<?php echo _('Quarter')?>'};


function  new_customer_from_file(){
//location.href='import_data.php?tipo=customers_store';
//import_csv.php?subject=customers_store&subject_key=3
location.href='import_csv.php?subject=customers_store&subject_key='+store_key;

}

function close_dialog(tipo){
	dialog_new_customer.hide();
}

function new_customer(tipo){
    location.href='new_customer.php?store='+store_key;
    dialog_new_customer.hide();
}

function close_dialog(tipo){
    switch(tipo){
case('export'):
 dialog_export.hide();
 break;
    }
};




YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {



	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [ 
				       {key:"id", label:"<?php echo$customers_ids[0]?>",width:45,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Customer Name')?>", width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['customers']['customers']['view']=='general'?'':'hidden:true,')?> width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       	,{key:"contact_since", label:"<?php echo _('Since')?>",<?php echo($_SESSION['state']['customers']['customers']['view']=='general'?'':'hidden:true,')?>width:85,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"last_order", label:"<?php echo _('Last Order')?>",<?php echo($_SESSION['state']['customers']['customers']['view']=='general'?'':'hidden:true,')?>width:85,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"orders", label:"<?php echo _('Orders')?>",<?php echo($_SESSION['state']['customers']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"activity", label:"<?php echo _('Status')?>",<?php echo($_SESSION['state']['customers']['customers']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"contact_name", label:"<?php echo _('Contact Name')?>",width:160,<?php echo($_SESSION['state']['customers']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"email", label:"<?php echo _('Email')?>",width:210,<?php echo($_SESSION['state']['customers']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"telephone", label:"<?php echo _('Telephone')?>", width:137,<?php echo($_SESSION['state']['customers']['customers']['view']=='contact'?'':'hidden:true,')?>sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},className:"aright"}
				       ,{key:"address", label:"<?php echo _('Contact Address')?>", width:176,<?php echo($_SESSION['state']['customers']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       ,{key:"billing_address", label:"<?php echo _('Billing Address')?>", width:170,<?php echo($_SESSION['state']['customers']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       ,{key:"delivery_address", label:"<?php echo _('Delivery Address')?>", width:170,<?php echo($_SESSION['state']['customers']['customers']['view']=='address'?'':'hidden:true,')?>sortable:true,className:"aleft"}
				       ,{key:"total_payments", label:"<?php echo _('Payments')?>",width:99,<?php echo($_SESSION['state']['customers']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_refunds", label:"<?php echo _('Refunds')?>",width:90,<?php echo($_SESSION['state']['customers']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"net_balance", label:"<?php echo _('Balance')?>",width:90,<?php echo($_SESSION['state']['customers']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"balance", label:"<?php echo _('Outstanding')?>",width:90,<?php echo($_SESSION['state']['customers']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_profit", label:"<?php echo _('Profit')?>",width:90,<?php echo($_SESSION['state']['customers']['customers']['view']=='balance'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_orders", label:"<?php echo _('Rank Orders')?>",width:121,<?php echo($_SESSION['state']['customers']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_invoices", label:"<?php echo _('Rank Invoices')?>",width:121,<?php echo($_SESSION['state']['customers']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_balance", label:"<?php echo _('Rank Balance')?>",width:120,<?php echo($_SESSION['state']['customers']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_profits", label:"<?php echo _('Rank Profits')?>",width:120,<?php echo($_SESSION['state']['customers']['customers']['view']=='rank'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"logins", label:"<?php echo _('Logins')?>",width:120,<?php echo($_SESSION['state']['customers']['customers']['view']=='weblog'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"failed_logins", label:"<?php echo _('Failed Logis')?>",width:120,<?php echo($_SESSION['state']['customers']['customers']['view']=='weblog'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"requests", label:"<?php echo _('Viewed Pages')?>",width:120,<?php echo($_SESSION['state']['customers']['customers']['view']=='weblog'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				
				
				
				];
				
		request="ar_contacts.php?tipo=customers&sf=0&where=&parent=store&parent_key="+Dom.get('store_id').value;		
			//alert(request)
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
			 ,"top_orders","top_invoices","top_balance","top_profits","logins","failed_logins","requests"
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['customers']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['customers']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['customers']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	  
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	  
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	      
		this.table0.table_id=tableid;
		
      
       this.table0.subscribe("renderEvent", customers_myrenderEvent);
		this.table0.getDataSource().sendRequest(null, {
    		success:function(request, response, payload) {
        		if(response.results.length == 0) {
            		get_elements_numbers()
            	} else {
            		//this.onDataReturnInitializeTable(request, response, payload);
        		}
    		},
    		scope:this.table0,
    		argument:this.table0.getState()
		});
	  
		    
		    
	    this.table0.view='<?php echo$_SESSION['state']['customers']['customers']['view']?>';

	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['customers']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['customers']['f_value']?>'};


   var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"public_id", label:"<?php echo _('Order ID')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				   
				   {key:"date", label:"<?php echo _('Order Date')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				     {key:"status",label:"<?php echo _('Status')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       //{key:"weight", label:"<?php echo _('Weight')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       //{key:"picks", label:"<?php echo _('Picks')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				      
				      
				      {key:"operations", label:"<?php echo _('Actions')?>", width:170,hidden:false,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				      // {key:"see_link", label:"",sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},

				];

	    this.dataSource1 = new YAHOO.util.DataSource("ar_edit_orders.php?tipo=store_pending_orders&tableid=1&parent_key="+Dom.get('store_key').value);
		//alert("ar_edit_orders.php?tipo=ready_to_pick_orders");
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
			 "id","public_id",
			 "weight","picks",
			 "customer",
			 "date","picker","packer","status","operations","see_link","status"
			
			 ]};

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['customers']['pending_orders']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['pending_orders']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['pending_orders']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['customers']['pending_orders']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['pending_orders']['f_value']?>'};

	    
	    this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);

	
	
	
	    var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	
		    var CustomersColumnDefs = [ 
					{key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				       ,{key:"id", label:"<?php echo _('Id')?>",width:45,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"name", label:"<?php echo _('Customer Name')?>", width:210,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       
				      
				       ,{key:"contact_name", label:"<?php echo _('Contact Name')?>",width:160,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"address", label:"<?php echo _('Contact Address')?>", width:176,sortable:true,className:"aleft"}
				      						  ,{key:"delete", label:"",sortable:false,width:16,sortable:false,action:'delete',object:'post_to_send'}
				       ,{key:"mark_as_send", label:"",sortable:false, width:16,sortable:false,action:'edit',object:'post_to_send'}
					];
	
request="ar_contacts.php?tipo=pending_post&parent=store&sf=0&tableid=2&parent_key="+Dom.get('store_key').value
	    this.dataSource2 = new YAHOO.util.DataSource(request);
	  
//alert(request)
	  
	  this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
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
			 'id','key',
			 'name',
			 'location',
			 'orders',
			 'email',
			 'telephone',
			 'last_order','activity',
			 'total_payments','contact_name'
			 ,"address"
			 ,"billing_address","delivery_address"
			 ,"total_paymants","total_refunds","net_balance","total_profit","balance","contact_since","delete","mark_as_send"
			 ,"top_orders","top_invoices","top_balance","top_profits"
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customers']['pending_post']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['pending_post']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['pending_post']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	this.table2.subscribe("cellClickEvent", onCellClick);  
		    
		    
	    this.table2.view='<?php echo$_SESSION['state']['customers']['pending_post']['view']?>';

	    this.table2.filter={key:'<?php echo$_SESSION['state']['customers']['pending_post']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['pending_post']['f_value']?>'};

	
	
	
	};
    });

function change_block_view() {

    ids = ['contacts', 'dashboard', 'pending_orders', 'pending_post']
    block_ids = ['contacts_block', 'dashboard_block', 'pending_orders_block', 'pending_post_block'];

    Dom.setStyle(block_ids, 'display', 'none');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');
    Dom.setStyle(this.id + '_block', 'display', '');

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=customers-block_view&value=' + this.id, {});
}


function show_export_dialog(e, tag) {

  //  Dom.get('export_xls').onclick = function() {
   //     window.location = 'export.php?ar_file=ar_contacts&tipo=customers&parent=store&parent_key=' + Dom.get('store_key').value + '&output=xls'
  //  };
  //  Dom.get('export_csv').onclick = function() {
  //      window.location = 'export.php?ar_file=ar_contacts&tipo=customers&parent=store&parent_key=' + Dom.get('store_key').value + '&output=csv'
   // };

	Dom.setStyle('dialog_export_'+tag,'display','');
	
    region1 = Dom.getRegion('export_' + tag);
    region2 = Dom.getRegion('dialog_export');

    var pos = [region1.right - 20, region1.bottom]
    Dom.setXY('dialog_export_'+tag, pos);
		   
    dialog_export.show()

}


function map_field_changed() {
    //Dom.getElementsByClassName('')
}

function update_map_field(o) {
    if (o.getAttribute('checked') == 1) {
        o.src = 'art/icons/checkbox_unchecked.png';
        o.setAttribute('checked', 0)
    } else {
        o.src = 'art/icons/checkbox_checked.png';
        o.setAttribute('checked', 1)

    }

}

function export_customers_table(e, data) {

    request = 'ar_export.php?ar_file=ar_contacts&tipo=customers&parent=store&parent_key=' + Dom.get('store_key').value + '&output='+data.output
alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
			alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == '200') {


            } else {

                Dom.get('send_reset_password_msg').innerHTML = r.msg;
            }


        }
    });

}

function init() {

    YAHOO.util.Event.addListener(customer_views_ids, "click", change_view_customers, 0);
    dialog_export = new YAHOO.widget.Dialog("dialog_export_customers", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_export.render();

    Event.addListener("export_customers", "click", show_export_dialog, 'customers');
    Event.addListener("export_csv_customers", "click", export_customers_table, {
        output: 'csv'
    });
    Event.addListener("export_xls_customers", "click", export_customers_table, {
        output: 'xls'
    });



    init_search('customers_store');

    YAHOO.util.Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    YAHOO.util.Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;

    ids = ['contacts', 'dashboard', 'pending_orders', 'pending_post']
    YAHOO.util.Event.addListener(ids, "click", change_block_view);

    //YAHOO.util.Event.addListener('submit_advanced_search', "click",submit_advanced_search);
    //var search_data={tipo:'customer_name',container:'customer'};
    dialog_new_customer = new YAHOO.widget.Dialog("dialog_new_customer", {
        context: ["new_customer", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_new_customer.render();
    Event.addListener("new_customer", "click", dialog_new_customer.show, dialog_new_customer, true);
    Event.addListener("close_dialog_new_customer", "click", dialog_new_customer.hide, dialog_new_customer, true);


}

YAHOO.util.Event.onDOMReady(init);


YAHOO.util.Event.onContentReady("filtermenu0", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
        trigger: "filter_name0"
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
