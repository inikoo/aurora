<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once 'common.php';

print "var tax_categories_customers=[";
$tmp='';
foreach ($_SESSION['state']['report_sales_with_no_tax'][$_SESSION['state']['report_sales_with_no_tax']['country']]['tax_category'] as $key=>$value) {
	$tmp.=",'elements_tax_category_$key"."_customers'";
}
$tmp=preg_replace('/^,/','',$tmp);
print "$tmp];";


print "var tax_categories_invoices=[";
$tmp='';
foreach ($_SESSION['state']['report_sales_with_no_tax'][$_SESSION['state']['report_sales_with_no_tax']['country']]['tax_category'] as $key=>$value) {
	$tmp.=",'elements_tax_category_$key"."_invoices'";
}
$tmp=preg_replace('/^,/','',$tmp);
print "$tmp];";


$country=$_SESSION['state']['report_sales_with_no_tax']['country'];
$elements_region=$_SESSION['state']['report_sales_with_no_tax'][$country]['regions'];
$_region='';
$_region2='';
foreach ($elements_region as $element_region=>$value) {
	$_region.=",'elements_region_".$element_region."_customers'";
	$_region2.=",'elements_region_".$element_region."_invoices'";
}
$_region=preg_replace('/^,/','',$_region);
$_region2=preg_replace('/^,/','',$_region2);
print "var regions_customers=[".$_region."];";
print "var regions_invoices=[".$_region2."];";



?>

var Dom   = YAHOO.util.Dom;


var link='report_sales_with_no_tax.php';




YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"<?php echo _('Public ID')?>", width:55,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"date", label:"<?php echo _('Date')?>", width:65,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"customer",label:"<?php echo _('Customer')?>", width:160,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"tax_number",label:"<?php echo _('Tax Number')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    
				    ,{key:"orders",label:"<?php echo _('Order')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},'hidden':true}
				    ,{key:"dns",label:"<?php echo _('Delivery Note')?>", width:100,sortable:false,className:"aleft",'hidden':true}
				    ,{key:"send_to",label:"<?php echo _('Send to')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    
				    ,{key:"total_amount", label:"<?php echo _('Total')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //  ,{key:"state", label:"<?php echo _('Status')?>", width:33,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    
				    
				    
				     ];
	    
	    this.dataSource0 = new YAHOO.util.DataSource("ar_reports.php?tipo=invoices_with_no_tax&tableid=0");
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
				 "total_amount","orders","dns","send_to","tax_number"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['report_sales_with_no_tax']['invoices']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['report_sales_with_no_tax']['invoices']['order']?>",
									 dir: "<?php echo$_SESSION['state']['report_sales_with_no_tax']['invoices']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table0.doBeforeLoadData=mydoBeforeLoadData;


	    

	    this.table0.filter={key:'<?php echo$_SESSION['state']['report_sales_with_no_tax']['invoices']['f_field']?>',value:'<?php echo$_SESSION['state']['report_sales_with_no_tax']['invoices']['f_value']?>'};

		


	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				   
				    {key:"name",label:"<?php echo _('Customer')?>", width:160,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"tax_number",label:"<?php echo _('Tax Number')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    
				    ,{key:"send_to",label:"<?php echo _('Send to')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"num_invoices",label:"<?php echo _('Invoices')?>", width:50,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"net_hq", label:"<?php echo _('Net')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"tax_hq", label:"<?php echo _('Tax')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //  ,{key:"state", label:"<?php echo _('Status')?>", width:33,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    
				    
				    
				     ];
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_reports.php?tipo=customers_with_no_tax&tableid=1");
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
			 "name","tax_number","num_invoices","send_to","total_amount","tax_hq","net_hq"
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {
							 //draggableColumns:true,
							 renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage:<?php echo$_SESSION['state']['report_sales_with_no_tax']['customers']['nr']?>,containers : 'paginator1', 
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['report_sales_with_no_tax']['customers']['order']?>",
							     dir: "<?php echo$_SESSION['state']['report_sales_with_no_tax']['customers']['order_dir']?>"
							 }
							 ,dynamicData : true
							 
						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.doBeforeLoadData=mydoBeforeLoadData;
	    
	    
	    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['report_sales_with_no_tax']['customers']['f_field']?>',value:'<?php echo$_SESSION['state']['report_sales_with_no_tax']['customers']['f_value']?>'};








	    var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				   
				    {key:"category",label:"<?php echo _('Category')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"tax_code",label:"<?php echo _('Tax Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    				    ,{key:"invoices", label:"<?php echo _('Invoices')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"net",label:"<?php echo _('Net')?>", width:110,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"tax",label:"<?php echo _('Tax')?>", width:110,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"total",label:"<?php echo _('Total')?>", width:110,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    //  ,{key:"state", label:"<?php echo _('Status')?>", width:33,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    
				    
				    
				     ];
	    
	    this.dataSource2 = new YAHOO.util.DataSource("ar_reports.php?tipo=tax_overview&tableid=2");
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
			 "category","tax_code","net","invoices","tax","total"
			 ]};
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource2, {
							 //draggableColumns:true,
							 renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage:<?php echo$_SESSION['state']['report_sales_with_no_tax']['overview']['nr']?>,containers : 'paginator2', 
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['report_sales_with_no_tax']['overview']['order']?>",
							     dir: "<?php echo$_SESSION['state']['report_sales_with_no_tax']['overview']['order_dir']?>"
							 }
							 ,dynamicData : true
							 
						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.doBeforeLoadData=mydoBeforeLoadData;
	    
	    
	    
	    this.table2.filter={key:'<?php echo$_SESSION['state']['report_sales_with_no_tax']['overview']['f_field']?>',value:'<?php echo$_SESSION['state']['report_sales_with_no_tax']['overview']['f_value']?>'};











	};
    });


function change_currency_type() {

    var sURL = unescape(window.location.pathname);
    location.href=sURL+'?currency_type='+this.id;
}


function change_block(){
ids=['overview','customers','invoices'];
block_ids=['block_overview','block_customers','block_invoices'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=report_sales_with_no_tax-view&value='+this.id ,{});
}


function get_tax_categories_numbers() {
    var ar_file = 'ar_reports.php';
    var request = 'tipo=get_tax_categories_numbers&from=' + Dom.get('from').value + '&to=' + Dom.get('to').value+'&country='+Dom.get('corporate_country_code').value
   // alert(request)
//    Dom.get(['elements_Error_number','elements_Excess_number','elements_Normal_number','elements_Low_number','elements_VeryLow_number','elements_OutofStock_number']).innerHTML='<img src="art/loading.gif" style="height:12.9px" />';
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

      //     alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                for (i in r.elements_numbers) {
                    for (j in r.elements_numbers[i]) {

						//alert(Dom.get('elements_tax_category_'+j+'_'+i+'_number')+'  elements_tax_category_'+i+'_'+j+'_number')
                        Dom.get('elements_tax_category_'+j+'_'+i+'_number').innerHTML = r.elements_numbers[i][j]
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



function change_elements(e, data) {

    type = data.type
    subject = data.subject

    id = this.id;
    if (subject == 'invoices') var x_id = id.replace("_invoices", "_customers");
    else var x_id = id.replace("_customers", "_invoices");

    if (type == 'region') {
        if (subject == 'invoices') {
            ids = regions_invoices
        } else {
            ids = regions_customers
        }


    } else {
        if (subject == 'invoices') {
            ids = tax_categories_invoices
        } else {
            ids = tax_categories_customers
        }
    }

    if (Dom.hasClass(this, 'selected')) {
        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(this, 'selected')
            Dom.removeClass(x_id, 'selected')
        }

    } else {
        Dom.addClass(this, 'selected')
        Dom.addClass(x_id, 'selected')
    }


    var request = '';
    for (i in ids) {

 

        if (subject == 'invoices') {
            x = ids[i].replace("_invoices", "")
        } else {
              x = ids[i].replace("_customers", "")
        }


        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' + x + '=1'

        } else {
            request = request + '&' + x + '=0'

        }
    }

   

    table_id = 0;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

    table_id = 1;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


}






 function init(){
 
get_tax_categories_numbers()
 
 Event.addListener(regions_customers, "click",change_elements,{type:'region',subject:'customers'});
  Event.addListener(regions_invoices, "click",change_elements,{type:'region',subject:'invoices'});

Event.addListener(tax_categories_customers, "click",change_elements,{type:'tax_codes',subject:'customers'});
Event.addListener(tax_categories_invoices, "click",change_elements,{type:'tax_codes',subject:'invoices'});
 
   Event.addListener(['overview','customers','invoices'], "click",change_block);

     var ids=['original','corparate_currency','hm_revenue_and_customs'];
     YAHOO.util.Event.addListener(ids, "click", change_currency_type);

 YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);

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
	 var oMenu = new YAHOO.widget.Menu("rppmenu1", { context:["rtext_rpp1","tl", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp1", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name1", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu1", { context:["rtext_rpp1","tl", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp1", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name1", "click", oMenu.show, null, oMenu);
    });






