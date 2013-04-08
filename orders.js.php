<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
if(!$user->can_view('orders'))
  exit();
?>
var  link='orders.php';
var Dom   = YAHOO.util.Dom;
var Event =YAHOO.util.Event;

Event.addListener(window, "load", function() {
    tables = new function() {
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"id", label:"<?php echo _('ID')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"date", label:"<?php echo _('Date')?>", width:155,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"customer",label:"<?php echo _('Customer')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"state", label:"<?php echo _('Status')?>", width:205,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"total_amount", label:"<?php echo _('Total Balance')?>", width:110,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ];
		//alert("ar_orders.php?tipo=orders&where=");
	    this.dataSource0 = new YAHOO.util.DataSource("ar_orders.php?tipo=orders&where=&parent=store&parent_key="+Dom.get('store_key').value);
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
			 "id",
			 "state",
			 "customer",
			 "date",
			 "last_date",
			 "total_amount"
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['orders']['orders']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders']['orders']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders']['orders']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['orders']['orders']['f_field']?>',value:'<?php echo$_SESSION['state']['orders']['orders']['f_value']?>'};

	    

	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"id", label:"<?php echo _('ID')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"date", label:"<?php echo _('Date')?>", width:155,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"customer",label:"<?php echo _('Customer')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     
				     				       ,{key:"type", label:"<?php echo _('Type')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				     ,{key:"state", label:"<?php echo _('Status')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"method", label:"<?php echo _('Payment')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				      
				      // ,{key:"orders",label:"<?php echo _('Order')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"dns",label:"<?php echo _('Delivery Note')?>", width:150,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       
				       ,{key:"total_amount", label:"<?php echo _('Total')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ];

request="ar_orders.php?tipo=invoices&tableid=1&parent=store&parent_key="+Dom.get('store_key').value
	    this.dataSource1 = new YAHOO.util.DataSource(request);
		//alert(request);
	     this.dataSource1.table_id=tableid;
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
			 "id",
			 "state",
			 "customer",
			 "date",
			 "date",
			 "total_amount","orders","dns","type","method"
			 ]};

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['orders']['invoices']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders']['invoices']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders']['invoices']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['orders']['invoices']['f_field']?>',value:'<?php echo$_SESSION['state']['orders']['invoices']['f_value']?>'};

 var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"id", label:"<?php echo _('Number')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"date", label:"<?php echo _('Date')?>", width:155,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"customer",label:"<?php echo _('Customer')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      				       ,{key:"type", label:"<?php echo _('Type')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				      ,{key:"state", label:"<?php echo _('Status')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       ,{key:"weight",label:"<?php echo _('Weight')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"parcels",label:"<?php echo _('Parcels')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       


					 ];
		request="ar_orders.php?tipo=dn&tableid=2&where=&parent=store&parent_key="+Dom.get('store_key').value
		//alert(request)
	    this.dataSource2 = new YAHOO.util.DataSource(request);
	    this.dataSource2.table_id=tableid;
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
			 "id",
			 "type",
			 "customer",
			 "date",
			 "state","weight","parcels"
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource2, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['orders']['dn']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders']['dn']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders']['dn']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'<?php echo$_SESSION['state']['orders']['dn']['f_field']?>',value:'<?php echo$_SESSION['state']['orders']['dn']['f_value']?>'};

	};
    });



function show_export_dialog(e, table_id) {

    if (table_id == 0) {
        Dom.get('export_xls').onclick = function() {
            window.location = 'export.php?ar_file=ar_orders&tipo=orders&parent=store&parent_key=' + Dom.get('store_key').value + '&output=xls'
        };
        Dom.get('export_csv').onclick = function() {
            window.location = 'export.php?ar_file=ar_orders&tipo=orders&parent=store&parent_key=' + Dom.get('store_key').value + '&output=csv'
        };

    } else if (table_id == 1) {
        Dom.get('export_xls').onclick = function() {
            window.location = 'export.php?ar_file=ar_orders&tipo=invoices&parent=store&parent_key=' + Dom.get('store_key').value + '&output=xls'
        };
        Dom.get('export_csv').onclick = function() {
            window.location = 'export.php?ar_file=ar_orders&tipo=invoices&parent=store&parent_key=' + Dom.get('store_key').value + '&output=csv'
        };

    } else {
        Dom.get('export_xls').onclick = function() {
            window.location = 'export.php?ar_file=ar_orders&tipo=dn&parent=store&parent_key=' + Dom.get('store_key').value + '&output=xls'
        };
        Dom.get('export_csv').onclick = function() {
            window.location = 'export.php?ar_file=ar_orders&tipo=dn&parent=store&parent_key=' + Dom.get('store_key').value + '&output=csv'
        };

    }

    region1 = Dom.getRegion('export' + table_id);
    region2 = Dom.getRegion('dialog_export');
    var pos = [region1.right - 20, region1.bottom]
    Dom.setXY('dialog_export', pos);
    dialog_export.show()
}





function change_order_elements(e, elements_type) {
    table_id = 0;

    if (elements_type == 'dispatch') ids = ['elements_order_dispatch_Cancelled', 'elements_order_dispatch_Suspended', 'elements_order_dispatch_Dispatched', 'elements_order_dispatch_Warehouse', 'elements_order_dispatch_InProcess', 'elements_order_dispatch_InProcessCustomer'];
    else if (elements_type == 'source') ids = ['elements_order_source_Other', 'elements_order_source_Internet', 'elements_order_source_Call', 'elements_order_source_Store', 'elements_order_source_Email', 'elements_order_source_Fax']
    else if (elements_type == 'payment') ids = ['elements_order_payment_PartiallyPaid', 'elements_order_payment_WaitingPayment', 'elements_order_payment_Unknown', 'elements_order_payment_Paid', 'elements_order_payment_NA']
    else if (elements_type == 'type') ids = ['elements_order_type_Other', 'elements_order_type_Donation', 'elements_order_type_Sample', 'elements_order_type_Order']

    if (Dom.hasClass(this, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(this, 'selected')

        }

    } else {
        Dom.addClass(this, 'selected')

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

function change_order_elements_dblclick(e, elements_type) {
    table_id = 0;

    if (elements_type == 'dispatch') ids = ['elements_order_dispatch_Cancelled', 'elements_order_dispatch_Suspended', 'elements_order_dispatch_Dispatched', 'elements_order_dispatch_Warehouse', 'elements_order_dispatch_InProcess', 'elements_order_dispatch_InProcessCustomer'];
    else if (elements_type == 'source') ids = ['elements_order_source_Other', 'elements_order_source_Internet', 'elements_order_source_Call', 'elements_order_source_Store', 'elements_order_source_Email', 'elements_order_source_Fax']
    else if (elements_type == 'payment') ids = ['elements_order_payment_PartiallyPaid', 'elements_order_payment_WaitingPayment', 'elements_order_payment_Unknown', 'elements_order_payment_Paid', 'elements_order_payment_NA']
    else if (elements_type == 'type') ids = ['elements_order_type_Other', 'elements_order_type_Donation', 'elements_order_type_Sample', 'elements_order_type_Order']


  Dom.removeClass(ids, 'selected')
    Dom.addClass(this, 'selected')


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

function change_invoice_elements(e, elements_type) {
    table_id = 1;

    if (elements_type == 'payment') ids = ['elements_invoice_payment_Partiall', 'elements_invoice_payment_Yes', 'elements_invoice_payment_No']
    else if (elements_type == 'type') ids = ['elements_invoice_type_Invoice', 'elements_invoice_type_Refund']

    if (Dom.hasClass(this, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(this, 'selected')

        }

    } else {
        Dom.addClass(this, 'selected')

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
function change_invoice_elements_dblclick(e, elements_type) {
    table_id = 1;

    if (elements_type == 'payment') ids = ['elements_invoice_payment_Partiall', 'elements_invoice_payment_Yes', 'elements_invoice_payment_No']
    else if (elements_type == 'type') ids = ['elements_invoice_type_Invoice', 'elements_invoice_type_Refund']

    
  Dom.removeClass(ids, 'selected')
    Dom.addClass(this, 'selected')


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

function change_dn_elements(e, elements_type) {
    table_id = 2;



    if (elements_type == 'dispatch') ids = ['elements_dn_dispatch_Ready', 'elements_dn_dispatch_Picking', 'elements_dn_dispatch_Packing', 'elements_dn_dispatch_Done', 'elements_dn_dispatch_Send', 'elements_dn_dispatch_Returned'];
       else if (elements_type == 'type') ids = ['elements_dn_type_Replacements', 'elements_dn_type_Donation', 'elements_dn_type_Sample', 'elements_dn_type_Order', 'elements_dn_type_Shortages']

    if (Dom.hasClass(this, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(this, 'selected')

        }

    } else {
        Dom.addClass(this, 'selected')

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


function change_dn_elements_dblclick(e, elements_type) {
    table_id = 2;



    if (elements_type == 'dispatch') ids = ['elements_dn_dispatch_Ready', 'elements_dn_dispatch_Picking', 'elements_dn_dispatch_Packing', 'elements_dn_dispatch_Done', 'elements_dn_dispatch_Send', 'elements_dn_dispatch_Returned'];
       else if (elements_type == 'type') ids = ['elements_dn_type_Replacements', 'elements_dn_type_Donation', 'elements_dn_type_Sample', 'elements_dn_type_Order', 'elements_dn_type_Shortages']

  
  Dom.removeClass(ids, 'selected')
    Dom.addClass(this, 'selected')


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

function get_numbers(tipo, from, to) {
    var ar_file = 'ar_orders.php';
    var request = 'tipo=number_' + tipo + 's_in_interval&parent=store&parent_key=' + Dom.get('store_key').value + '&from=' + from + '&to=' + to;
   //alert(request)
   YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

            if (tipo == 'delivery_note') tipo = 'dn';
        //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                for (i in r.elements_numbers) {
                    for (j in r.elements_numbers[i]) {
                        Dom.get('elements_' + tipo + '_' + i + '_' + j + '_number').innerHTML = r.elements_numbers[i][j]
                    }
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







function show_dialog_change_orders_element_chooser() {
    region1 = Dom.getRegion('order_element_chooser_menu_button');
    region2 = Dom.getRegion('dialog_change_orders_element_chooser');
    var pos = [region1.right - region2.width, region1.bottom + 3]
    Dom.setXY('dialog_change_orders_element_chooser', pos);
    dialog_change_orders_element_chooser.show()
}

function show_dialog_change_dns_element_chooser() {
    region1 = Dom.getRegion('dn_element_chooser_menu_button');
    region2 = Dom.getRegion('dialog_change_dns_element_chooser');
    var pos = [region1.right - region2.width, region1.bottom + 3]
    Dom.setXY('dialog_change_dns_element_chooser', pos);
    dialog_change_dns_element_chooser.show()
}


function change_orders_element_chooser(elements_type) {

    Dom.setStyle(['order_dispatch_chooser', 'order_type_chooser', 'order_source_chooser', 'order_payment_chooser'], 'display', 'none')
    Dom.setStyle('order_' + elements_type + '_chooser', 'display', '')

    Dom.removeClass(['orders_element_chooser_dispatch', 'orders_element_chooser_type', 'orders_element_chooser_source', 'orders_element_payment_dispatch', ], 'selected')
    Dom.addClass('orders_element_chooser_' + elements_type, 'selected')
    dialog_change_orders_element_chooser.hide()

    var table = tables.table0;
    var datasource = tables.dataSource0;
    var request = '&elements_type=' + elements_type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function show_dialog_change_invoices_element_chooser() {
    region1 = Dom.getRegion('invoice_element_chooser_menu_button');
    region2 = Dom.getRegion('dialog_change_invoices_element_chooser');
    var pos = [region1.right - region2.width, region1.bottom + 3]
    Dom.setXY('dialog_change_invoices_element_chooser', pos);
    dialog_change_invoices_element_chooser.show()
}

function change_dns_element_chooser(elements_type) {

    Dom.setStyle(['dn_dispatch_chooser', 'dn_type_chooser'], 'display', 'none')
    Dom.setStyle('dn_' + elements_type + '_chooser', 'display', '')

    Dom.removeClass(['dns_element_chooser_dispatch', 'dns_element_chooser_type' ], 'selected')
    Dom.addClass('dns_element_chooser_' + elements_type, 'selected')
    dialog_change_dns_element_chooser.hide()

    var table = tables.table2;
    var datasource = tables.dataSource2;
    var request = '&elements_type=' + elements_type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}


function change_invoices_element_chooser(elements_type) {

    Dom.setStyle(['invoice_type_chooser','invoice_payment_chooser'], 'display', 'none')
    Dom.setStyle('invoice_' + elements_type + '_chooser', 'display', '')

    Dom.removeClass(['invoices_element_chooser_type', 'invoices_element_payment_dispatch', ], 'selected')
    Dom.addClass('invoices_element_chooser_' + elements_type, 'selected')
    dialog_change_invoices_element_chooser.hide()

    var table = tables.table1;
    var datasource = tables.dataSource1;
    var request = '&elements_type=' + elements_type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}



function change_block_view(e) {

    ids = ['orders', 'invoices', 'dn'];
    block_ids = ['block_orders', 'block_invoices', 'block_dn'];

    if (this.id == 'invoices') {
    
        Dom.get('category_button').onclick = function() {
            window.location = 'invoice_categories.php?id=0&store=' + Dom.get('store_key').value
        };
        Dom.get('list_button').onclick = function() {
            window.location = 'invoices_lists.php?store=' + Dom.get('store_key').value
        };
    	Dom.setStyle('category_button','display','')
    
    } else if (this.id == 'orders') {
        Dom.get('category_button').onclick = function() {
            window.location = 'orders_categories.php?id=0&store=' + Dom.get('store_key').value
        };
        Dom.get('list_button').onclick = function() {
            window.location = 'invoices_lists.php?store=' + Dom.get('store_key').value
        };
            	Dom.setStyle('category_button','display','none')

    } else if (this.id == 'dn') {
        Dom.get('category_button').onclick = function() {
            window.location = 'dn_categories.php?id=0&store=' + Dom.get('store_key').value
        };
        Dom.get('list_button').onclick = function() {
            window.location = 'dn_lists.php?store=' + Dom.get('store_key').value
        };
                    	Dom.setStyle('category_button','display','none')

    }

    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=orders-view&value=' + this.id, {});

}



function init() {




    from = Dom.get('from').value
    to = Dom.get('to').value
    get_numbers('order', from, to)
    get_numbers('invoice', from, to)
    get_numbers('delivery_note', from, to)
   
    dialog_export = new YAHOO.widget.Dialog("dialog_export", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_export.render();
    YAHOO.util.Event.addListener('export0', "click", show_export_dialog, 0);
    YAHOO.util.Event.addListener('export1', "click", show_export_dialog, 1);
    YAHOO.util.Event.addListener('export2', "click", show_export_dialog, 2);


    init_search('orders_store');
    var ids = ['orders', 'invoices', 'dn'];
    Event.addListener(ids, "click", change_block_view);

    Event.addListener(['elements_order_dispatch_Cancelled', 'elements_order_dispatch_Suspended', 'elements_order_dispatch_Dispatched', 'elements_order_dispatch_Warehouse', 'elements_order_dispatch_InProcess', 'elements_order_dispatch_InProcessCustomer'], "click", change_order_elements, 'dispatch');
    Event.addListener(['elements_order_source_Other', 'elements_order_source_Internet', 'elements_order_source_Call', 'elements_order_source_Store', 'elements_order_source_Email', 'elements_order_source_Fax'], "click", change_order_elements, 'source');
    Event.addListener(['elements_order_payment_PartiallyPaid', 'elements_order_payment_WaitingPayment', 'elements_order_payment_Unknown', 'elements_order_payment_Paid', 'elements_order_payment_NA'], "click", change_order_elements, 'payment');
    Event.addListener(['elements_order_type_Other', 'elements_order_type_Donation', 'elements_order_type_Sample', 'elements_order_type_Order'], "click", change_order_elements, 'type');

    Event.addListener(['elements_order_dispatch_Cancelled', 'elements_order_dispatch_Suspended', 'elements_order_dispatch_Dispatched', 'elements_order_dispatch_Warehouse', 'elements_order_dispatch_InProcess', 'elements_order_dispatch_InProcessCustomer'], "dblclick", change_order_elements_dblclick, 'dispatch');
    Event.addListener(['elements_order_source_Other', 'elements_order_source_Internet', 'elements_order_source_Call', 'elements_order_source_Store', 'elements_order_source_Email', 'elements_order_source_Fax'], "dblclick", change_order_elements_dblclick, 'source');
    Event.addListener(['elements_order_payment_PartiallyPaid', 'elements_order_payment_WaitingPayment', 'elements_order_payment_Unknown', 'elements_order_payment_Paid', 'elements_order_payment_NA'], "dblclick", change_order_elements_dblclick, 'payment');
    Event.addListener(['elements_order_type_Other', 'elements_order_type_Donation', 'elements_order_type_Sample', 'elements_order_type_Order'], "dblclick", change_order_elements_dblclick, 'type');



    Event.addListener(['elements_invoice_type_Invoice', 'elements_invoice_type_Refund'], "click", change_invoice_elements, 'type');
    Event.addListener(['elements_invoice_payment_Partially', 'elements_invoice_payment_Yes', 'elements_invoice_payment_No'], "click", change_invoice_elements, 'payment');
   Event.addListener(['elements_invoice_type_Invoice', 'elements_invoice_type_Refund'], "dblclick", change_invoice_elements_dblclick, 'type');
    Event.addListener(['elements_invoice_payment_Partially', 'elements_invoice_payment_Yes', 'elements_invoice_payment_No'], "dblclick", change_invoice_elements_dblclick, 'payment');


    Event.addListener(['elements_dn_dispatch_Ready', 'elements_dn_dispatch_Picking', 'elements_dn_dispatch_Packing', 'elements_dn_dispatch_Done', 'elements_dn_dispatch_Send', 'elements_dn_dispatch_Returned'], "click", change_dn_elements, 'dispatch');
    Event.addListener(['elements_dn_type_Replacements', 'elements_dn_type_Donation', 'elements_dn_type_Sample', 'elements_dn_type_Order', 'elements_dn_type_Shortages'], "click", change_dn_elements, 'type');
   Event.addListener(['elements_dn_dispatch_Ready', 'elements_dn_dispatch_Picking', 'elements_dn_dispatch_Packing', 'elements_dn_dispatch_Done', 'elements_dn_dispatch_Send', 'elements_dn_dispatch_Returned'], "dblclick", change_dn_elements_dblclick, 'dispatch');
    Event.addListener(['elements_dn_type_Replacements', 'elements_dn_type_Donation', 'elements_dn_type_Sample', 'elements_dn_type_Order', 'elements_dn_type_Shortages'], "dblclick", change_dn_elements_dblclick, 'type');


    Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
    Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);
    Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
    Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);

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



    dialog_change_orders_element_chooser = new YAHOO.widget.Dialog("dialog_change_orders_element_chooser", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_orders_element_chooser.render();
    Event.addListener("order_element_chooser_menu_button", "click", show_dialog_change_orders_element_chooser);

    dialog_change_invoices_element_chooser = new YAHOO.widget.Dialog("dialog_change_invoices_element_chooser", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_invoices_element_chooser.render();
    Event.addListener("invoice_element_chooser_menu_button", "click", show_dialog_change_invoices_element_chooser);



    dialog_change_dns_element_chooser = new YAHOO.widget.Dialog("dialog_change_dns_element_chooser", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_dns_element_chooser.render();
    Event.addListener("dn_element_chooser_menu_button", "click", show_dialog_change_dns_element_chooser);


}

Event.onDOMReady(init);

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
