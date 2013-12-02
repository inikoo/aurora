<?php include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {




		var tableid=0; 
	    var tableDivEL="table"+tableid;


       var CustomersColumnDefs = [
				    
				    {key:"sku", label:"<?php echo _('SKU')?>", width:50,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"product", label:"<?php echo _('Product')?>",width:70, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                ,{key:"date", label:"<?php echo _('Date')?>",width:150, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                ,{key:"order", label:"<?php echo _('Order')?>",width:150, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"picker", label:"<?php echo _('Picker')?>",width:50, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				                ,{key:"qty", label:"<?php echo _('Quantity')?>",width:50, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				                ,{key:"lost_revenue", label:"<?php echo _('Lost Revenue')?>",width:150, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			//	,{key:"note", label:"<?php echo _('Notes')?>",width:265, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					 ];
	   
	   request="ar_reports.php?tipo=transactions_parts_marked_as_out_of_stock&parent=warehouses&parent_key=&tableid="+tableid;
	 //  alert(request)
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
		'sku','used_in','date','picker','note','product','order','qty','lost_revenue'
			 ]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['order']?>",
									 dir: "<?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['f_field']?>',value:'<?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['f_value']?>'};
	  //  YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	
	 //START OF THE TABLE=========================================================================================================================

		var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;


        var CustomersColumnDefs1 = [
				    
				    {key:"sku", label:"<?php echo _('SKU')?>", width:60,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"reference", label:"<?php echo _('Reference')?>",width:100, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					//,{key:"used_in", label:"<?php echo _('Products')?>",width:180, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                ,{key:"date", label:"<?php echo _('Last Date')?>",width:170, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"orders", label:"<?php echo _('Orders')?>",width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"customers", label:"<?php echo _('Customers')?>",width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"qty", label:"<?php echo _('Quantity')?>",width:100, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					,{key:"lost_revenue", label:"<?php echo _('Lost Revenue')?>",width:100, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					 ];
	   
	    			    this.dataSource1 = new YAHOO.util.DataSource("ar_reports.php?tipo=parts_marked_as_out_of_stock&parent=warehouses&parent_key=&tableid="+tableid);

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
		'sku','used_in','date','orders','customers','reference','lost_revenue','qty'
			 ]};
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs1,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['report_part_out_of_stock']['parts']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['report_part_out_of_stock']['parts']['order']?>",
									 dir: "<?php echo$_SESSION['state']['report_part_out_of_stock']['parts']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['report_part_out_of_stock']['parts']['f_field']?>',value:'<?php echo$_SESSION['state']['report_part_out_of_stock']['parts']['f_value']?>'};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	
	
	
		var tableid=2; 
	    var tableDivEL="table"+tableid;


       var CustomersColumnDefs = [
				    
				    {key:"customer", label:"<?php echo _('Customer')?>", width:200,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					//,{key:"products", label:"<?php echo _('Products')?>",width:200, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                ,{key:"date", label:"<?php echo _('Last Date')?>",width:150, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  //  ,{key:"reporter", label:"<?php echo _('Reporter')?>",width:50, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"orders", label:"<?php echo _('Affected Orders')?>",width:100, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"orders_percentage", label:"<?php echo '% '._('Orders')?>",width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					,{key:"lost_revenue", label:"<?php echo _('Lost Revenue')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"lost_revenue_percentage", label:"<?php echo '% '._('Lost Revenue')?>",width:100, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					 ];
	   
	   request="ar_reports.php?tipo=customers_affected_by_out_of_stock&tableid="+tableid
	 //  alert(request)
	    this.dataSource2 = new YAHOO.util.DataSource(request);

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
		'customer','products','date','orders','lost_revenue','orders_percentage','lost_amount_percentage','lost_revenue_percentage'
			 ]};
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['report_part_out_of_stock']['customers']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['report_part_out_of_stock']['customers']['order']?>",
									 dir: "<?php echo$_SESSION['state']['report_part_out_of_stock']['customers']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'<?php echo$_SESSION['state']['report_part_out_of_stock']['customers']['f_field']?>',value:'<?php echo$_SESSION['state']['report_part_out_of_stock']['customers']['f_value']?>'};

	
		var tableid=3; 
	    var tableDivEL="table"+tableid;


       var CustomersColumnDefs = [
				    
				    {key:"public_id", label:"<?php echo _('Order')?>", width:40,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"customer", label:"<?php echo _('Customer')?>",width:220, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                ,{key:"date", label:"<?php echo _('Dispatch Date')?>",width:150, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"lost_revenue", label:"<?php echo _('Lost Revenue')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"lost_revenue_percentage", label:"<?php echo '% '._('Lost Revenue')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					 ];
	   
	   request="ar_reports.php?tipo=orders_affected_by_out_of_stock&tableid="+tableid
	    this.dataSource3 = new YAHOO.util.DataSource(request);

	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
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
		'customer','public_id','date','lost_revenue','lost_revenue_percentage'
			 ]};
	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource3
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['report_part_out_of_stock']['orders']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['report_part_out_of_stock']['orders']['order']?>",
									 dir: "<?php echo$_SESSION['state']['report_part_out_of_stock']['orders']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table3.filter={key:'<?php echo$_SESSION['state']['report_part_out_of_stock']['orders']['f_field']?>',value:'<?php echo$_SESSION['state']['report_part_out_of_stock']['orders']['f_value']?>'};

	
	
	
	};
    });
   
    


function change_block(tipo) {

    Dom.setStyle(['transactions', 'parts','customers','orders'], 'display', 'none');
    Dom.setStyle(tipo, 'display', '');
    Dom.removeClass(['transactions_tab', 'parts_tab', 'customers_tab','orders_tab'], 'selected')
    Dom.addClass(tipo + '_tab', 'selected')
    //alert(tipo) 
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=report_part_out_of_stock-block&value=' + escape(tipo), {
        success: function(o) {
       
        }
    });

    //alert('ar_sessions.php?tipo=update&keys=report_part_out_of_stock-view=&value=' + escape(tipo) )
}







function get_out_of_stock_data(from, to) {

Dom.get('number_out_of_stock_parts').innerHTML='<img src="art/loading.gif" style="height:14px">';
Dom.get('number_out_of_stock_transactions').innerHTML='<img src="art/loading.gif" style="height:14px">';

    var request = 'ar_reports.php?tipo=out_of_stock_data&from=' +from + '&to=' +to
    //alert(request)	 
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText);	
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('number_out_of_stock_parts').innerHTML = r.number_out_of_stock_parts;
                Dom.get('number_out_of_stock_transactions').innerHTML = r.number_out_of_stock_transactions;
            } else {

            }
        }
    });


}


function get_out_of_stock_customer_data(from, to) {
Dom.get('number_out_of_stock_customers').innerHTML='<img src="art/loading.gif" style="height:14px">';

    var request = 'ar_reports.php?tipo=out_of_stock_customer_data&from=' +from + '&to=' +to
    
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText);	
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('number_out_of_stock_customers').innerHTML = r.number_out_of_stock_customers;
            } else {

            }
        }
    });
}

function get_out_of_stock_order_data(from, to) {
Dom.get('number_out_of_stock_orders').innerHTML='<img src="art/loading.gif" style="height:14px">';

    var request = 'ar_reports.php?tipo=out_of_stock_order_data&from=' +from + '&to=' +to
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
           // alert(o.responseText);	
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('number_out_of_stock_orders').innerHTML = r.number_out_of_stock_orders;
            } else {

            }
        }
    });
}


function get_out_of_stock_lost_revenue_data(from, to) {
Dom.get('lost_revenue').innerHTML='<img src="art/loading.gif" style="height:14px">';

    var request = 'ar_reports.php?tipo=out_of_stock_lost_revenue_data&from=' +from + '&to=' +to
    // alert(request)	 
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
           // alert(o.responseText);	
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('lost_revenue').innerHTML = r.lost_revenue;
            } else {

            }
        }
    });


}

function post_change_period_actions(period, from, to) {

    request = '&from=' + from + '&to=' + to;

    table_id = 0
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    table_id = 1
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    table_id = 2
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
       table_id = 3
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

    Dom.get('rtext0').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp0').innerHTML = '';
    Dom.get('rtext1').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/>  <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp1').innerHTML = '';
    Dom.get('rtext2').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/>  <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp2').innerHTML = '';
    Dom.get('rtext3').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/>  <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp3').innerHTML = '';



    
     get_out_of_stock_data(from, to)
    get_out_of_stock_customer_data(from, to);
    get_out_of_stock_lost_revenue_data(from, to);
    get_out_of_stock_order_data(from, to);
    

}

function init() {



    get_out_of_stock_data(Dom.get('from').value,Dom.get('to').value)
    get_out_of_stock_customer_data(Dom.get('from').value,Dom.get('to').value);
    get_out_of_stock_lost_revenue_data(Dom.get('from').value,Dom.get('to').value);
    get_out_of_stock_order_data(Dom.get('from').value,Dom.get('to').value);
    
   
    
    
    
    Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
    Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);
    Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
    Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);
    Event.addListener('clean_table_filter_show3', "click", show_filter, 3);
    Event.addListener('clean_table_filter_hide3', "click", hide_filter, 3);
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

YAHOO.util.Event.onContentReady("filtermenu1", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
        trigger: "filter_name1"
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

YAHOO.util.Event.onContentReady("filtermenu2", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {
        trigger: "filter_name2"
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


YAHOO.util.Event.onContentReady("filtermenu3", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu3", {
        trigger: "filter_name3"
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
