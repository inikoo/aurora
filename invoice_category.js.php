<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012 LW
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;
var Event   = YAHOO.util.Event;


var link='invoice_category.php';
   var invoice_views_ids = ['invoices_general', 'invoices_contact', 'invoices_address', 'invoices_ship_to_address', 'invoices_balance', 'invoices_rank', 'invoices_weblog','invoices_other_value'];




var  subcategories_period_ids=['subcategories_period_all',
 'subcategories_period_yesterday',
 'subcategories_period_last_w',
 'subcategories_period_last_m','subcategories_period_three_year','subcategories_period_year','subcategories_period_yeartoday','subcategories_period_six_month','subcategories_period_quarter','subcategories_period_month','subcategories_period_ten_day','subcategories_period_week','subcategories_period_monthtoday','subcategories_period_weektoday','subcategories_period_today'];




function change_history_elements(e, table_id) {
    ids = ['elements_Changes', 'elements_Assign'];
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


function change_invoices_view_save(tipo){
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=invoice_categories-invoices-view&value=' + escape(tipo), {});

}



function change_block(){
ids=['subcategories','subjects','overview','history','sales','no_assigned'];
block_ids=['block_subcategories','block_subjects','block_overview','block_history','block_sales','block_no_assigned'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
//alert('ar_sessions.php?tipo=update&keys=invoice_categories-'+Dom.get('state_type').value+'_block_view&value='+this.id )
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=invoice_categories-'+Dom.get('state_type').value+'_block_view&value='+this.id ,{
success: function(o) {
			
		}

});
}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

if(Dom.get('show_subjects').value){
	    var tableid=0;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
							       {key:"id", label:"<?php echo _('ID')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"date", label:"<?php echo _('Date')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"customer",label:"<?php echo _('Customer')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"orders",label:"<?php echo _('Order')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"dns",label:"<?php echo _('Delivery Note')?>", width:140,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       
				       ,{key:"total_amount", label:"<?php echo _('Total')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"state", label:"<?php echo _('Status')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				     ];
				     
				     request="ar_orders.php?tipo=invoices&tableid=0&where=&parent=category&sf=0&parent_key="+Dom.get('category_key').value+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value
				  //   alert(request)
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
			 "id",
			 "state",
			 "customer",
			 "date",
			 "date",
			 "total_amount","orders","dns"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['invoice_categories']['invoices']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<span id='paginator_info0'>{CurrentPageReport}</span>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['invoice_categories']['invoices']['order']?>",
									 dir: "<?php echo $_SESSION['state']['invoice_categories']['invoices']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
  		this.table0.table_id=tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);
		
	    
	    
	    this.table0.view='<?php echo $_SESSION['state']['invoice_categories']['invoices']['view']?>';
	    this.table0.filter={key:'<?php echo $_SESSION['state']['invoice_categories']['invoices']['f_field']?>',value:'<?php echo $_SESSION['state']['invoice_categories']['invoices']['f_value']?>'};
		
}

if(Dom.get('show_subcategories').value){
	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				
				    {key:"code", label:"<?php echo _('Code')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"label", label:"<?php echo _('Label')?>", width:360,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"subjects", label:"<?php echo _('Invoices')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"sales", label:"<?php echo _('Sales')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					//,{key:"delta_sales", label:"<?php echo '&Delta;'._('Sales')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				
				     ];
	  request="ar_orders.php?tipo=invoice_categories&sf=0&tableid=1&parent=category&parent_key="+Dom.get('category_key').value
	// alert(request)
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
			"code","subjects","sold","sales","label","delta_sales"
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['invoice_categories']['subcategories']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['invoice_categories']['subcategories']['order']?>",
									 dir: "<?php echo$_SESSION['state']['invoice_categories']['subcategories']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table1.view='<?php echo$_SESSION['state']['invoice_categories']['view']?>';
	    this.table1.filter={key:'<?php echo$_SESSION['state']['invoice_categories']['subcategories']['f_field']?>',value:'<?php echo$_SESSION['state']['invoice_categories']['subcategories']['f_value']?>'};
		this.table1.table_id=tableid;
     	this.table1.subscribe("renderEvent", myrenderEvent);
		
		}
 var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	  	  	    var InvoicesColumnDefs = [
				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				      ,{key:"date", label:"<?php echo _('Date')?>",className:"aright",width:120,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:"<?php echo _('Time')?>",className:"aleft",width:50}
				      ,{key:"handle", label:"<?php echo _('Author')?>",className:"aleft",width:100,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"note", formatter:this.prepare_note,label:"<?php echo _('Notes')?>",className:"aleft",width:520}
				       ];
		request="ar_history.php?tipo=invoice_categories&parent=category&parent_key="+Dom.get('category_key').value+"&tableid=2";
	   	  
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
			 "key"
			 ,"date"
			 ,'time','handle','note'
			 ]};
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, InvoicesColumnDefs,
						     this.dataSource2
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['invoice_categories']['history']['nr']?>,containers : 'paginator2', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['invoice_categories']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['invoice_categories']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;

		this.table2.table_id=tableid;
     	this.table2.subscribe("renderEvent", myrenderEvent);

		    
	    this.table2.filter={key:'<?php echo$_SESSION['state']['invoice_categories']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['invoice_categories']['history']['f_value']?>'};







	};
    });


function update_invoice_category_history_elements() {

    var ar_file = 'ar_history.php';
    var request = 'tipo=get_invoice_category_history_elements&parent=category&parent_key=' + Dom.get('category_key').value;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            for (key in r.elements_number) {
                Dom.get('elements_' + key + '_number').innerHTML = r.elements_number[key]
            }
        },
        failure: function(o) {},
        scope: this
    }, request

    );
}



function post_change_period_actions(period, from, to) {

    request = '&from=' + from + '&to=' + to;

    table_id = 0
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


    Dom.get('rtext0').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp0').innerHTML = '';

 get_numbers('invoice', from, to)
 
 
 

}


function get_numbers(tipo, from, to) {
    var ar_file = 'ar_orders.php';
    var request = 'tipo=number_' + tipo + 's_in_interval&parent=category&parent_key=' + Dom.get('category_key').value + '&from=' + from + '&to=' + to;

    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

            if (tipo == 'delivery_note') tipo = 'dn';
          //  alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                for (i in r.elements_numbers) {
                    for (j in r.elements_numbers[i]) {
                        Dom.get('elements_' + tipo + '_' + i + '_' + j + '_number').innerHTML = r.elements_numbers[i][j]
                    }
                }
                
                Dom.get('number_of_invoices').innerHTML=r.number_invoices;
            }
        },
        failure: function(o) {
            // alert(o.statusText);
        },
        scope: this
    }, request

    );
}

function show_dialog_change_invoices_element_chooser() {
    region1 = Dom.getRegion('invoice_element_chooser_menu_button');
    region2 = Dom.getRegion('dialog_change_invoices_element_chooser');
    var pos = [region1.right - region2.width, region1.bottom + 3]
    Dom.setXY('dialog_change_invoices_element_chooser', pos);
    dialog_change_invoices_element_chooser.show()
}

function change_invoices_element_chooser(elements_type) {

    Dom.setStyle(['invoice_type_chooser', 'invoice_payment_chooser'], 'display', 'none')
    Dom.setStyle('invoice_' + elements_type + '_chooser', 'display', '')

    Dom.removeClass(['invoices_element_chooser_type', 'invoices_element_payment_dispatch', ], 'selected')
    Dom.addClass('invoices_element_chooser_' + elements_type, 'selected')
    dialog_change_invoices_element_chooser.hide()

    var table = tables.table0;
    var datasource = tables.dataSource0;
    var request = '&elements_type=' + elements_type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function init() {


    from = Dom.get('from').value
    to = Dom.get('to').value
    get_numbers('invoice', from, to)

    dialog_change_invoices_element_chooser = new YAHOO.widget.Dialog("dialog_change_invoices_element_chooser", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_invoices_element_chooser.render();
    Event.addListener("invoice_element_chooser_menu_button", "click", show_dialog_change_invoices_element_chooser);




    ids = ['subcategories', 'subjects', 'overview', 'history', 'sales', 'no_assigned'];
    Event.addListener(ids, "click", change_block);

    init_search('orders');






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

    Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
    Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);
    Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
    Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);

    YAHOO.util.Event.addListener(invoice_views_ids, "click", change_view_invoices, 0);


    //ids=['elements_all_contacts_lost','label_all_contacts_losing','elements_all_contacts_active'];
    //Event.addListener(ids, "click",change_invoices_elements,0);
    ids = ['elements_Changes', 'elements_Assign'];
    Event.addListener(ids, "click", change_history_elements, 2);


    YAHOO.util.Event.addListener(invoices_period_ids, "click", change_invoices_period, 0);


    ids = ['invoices_avg_totals', 'invoices_avg_month', 'invoices_avg_week', "invoices_avg_month_eff", "invoices_avg_week_eff"];
    YAHOO.util.Event.addListener(ids, "click", change_invoices_avg, 0);

    YAHOO.util.Event.addListener(subcategories_period_ids, "click", change_subcategories_period, 1);
    ids = ['category_period_all', 'category_period_three_year', 'category_period_year', 'category_period_yeartoday', 'category_period_six_month', 'category_period_quarter', 'category_period_month', 'category_period_ten_day', 'category_period_week', 'category_period_monthtoday', 'category_period_weektoday', 'category_period_today', 'category_period_yesterday', 'category_period_last_m', 'category_period_last_w'];
    YAHOO.util.Event.addListener(ids, "click", change_sales_period);





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
