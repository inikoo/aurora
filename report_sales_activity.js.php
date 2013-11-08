<?php
include_once('common.php');
?>
    var view='invoices';
    var panel1;

 var show_invoices=function(){
     Dom.get('clean_table_title0').innerHTML='<?php echo _('Orders invoiced').' '.$_SESSION['state']['report_activity']['period']?>.';
     request="ar_orders.php?tipo=report_invoices&saveto=report_sales&where="+escape('where true')+"&view=invoices&sf=0&nr=10&from=<?php echo$_SESSION['state']['report_activity']['from']?>&to=<?php echo$_SESSION['state']['report_activity']['to']?>"
     //  alert(request);
     var table=tables.table0;
     var datasource=tables.dataSource0;
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
     panel1.show();

 }
 var show_invoices_home=function(){
     Dom.get('clean_table_title0').innerHTML='<?php echo$myconf['_home']." "._('orders invoiced (excluding partners)').' '.$_SESSION['state']['report_activity']['period']?>.';
     request="ar_orders.php?tipo=report_invoices&saveto=report_sales&where="+escape('where  `Invoice For Partner`="No" and `Invoice Billing Country 2 Alpha Code`="<?php echo$myconf['country_2acode']?>"')+"&view=invoices&sf=0&nr=10&from=<?php echo$_SESSION['state']['report_activity']['from']?>&to=<?php echo$_SESSION['state']['report_activity']['to']?>"
     // alert(request);
     var table=tables.table0;
     var datasource=tables.dataSource0;
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
     panel1.show();

 }

var show_invoices_nohome=function(){
     Dom.get('clean_table_title0').innerHTML='<?php echo _('Export orders invoiced (excluding partners)').' '.$_SESSION['state']['report_activity']['period']?>';
     request="ar_orders.php?tipo=report_invoices&saveto=report_sales&where="+escape('where  `Invoice For Partner`="No" and  `Invoice Billing Country 2 Alpha Code`!="<?php echo$myconf['country_2acode']?>"')+"&view=invoices&sf=0&nr=10&from=<?php echo$_SESSION['state']['report_activity']['from']?>&to=<?php echo$_SESSION['state']['report_activity']['to']?>"

     var table=tables.table0;
     var datasource=tables.dataSource0;
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
     panel1.show();

 }
   
var show_invoices_partner=function(){
     Dom.get('clean_table_title0').innerHTML='<?php echo _('Partners orders invoiced').' '.$_SESSION['state']['report_activity']['period']?>';
     request="ar_orders.php?tipo=report_invoices&saveto=report_sales&where="+escape('where  `Invoice For Partner`="Yes" ')+"&view=invoices&sf=0&nr=10&from=<?php echo$_SESSION['state']['report_activity']['from']?>&to=<?php echo$_SESSION['state']['report_activity']['to']?>"

     var table=tables.table0;
     var datasource=tables.dataSource0;
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
     panel1.show();

 }

    var show_invoices_country=function(country_code,name){

     Dom.get('clean_table_title0').innerHTML=name+' <?php echo _('orders invoiced').' '.$_SESSION['state']['report_activity']['period']?>';
     request="ar_orders.php?tipo=report_invoices&saveto=report_sales&where="+escape('where  `Invoice For Partner`="No"    and `Invoice Billing Country 2 Alpha Code`="'+country_code+'"')+"&view=invoices&sf=0&nr=10&from=<?php echo$_SESSION['state']['report_activity']['from']?>&to=<?php echo$_SESSION['state']['report_activity']['to']?>"

     var table=tables.table0;
     var datasource=tables.dataSource0;
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
     panel1.show();

 }



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	 
	    
	    
	    
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var OrdersColumnDefs = [
				       {key:"id", label:"<?php echo _('Number')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       //  {key:"titulo", label:"<?php echo _('Type')?>", width:115,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"customer",label:"<?php echo _('Customer')?>", width:280,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"date", label:"<?php echo _('Date')?>", width:145,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				        {key:"net", label:"<?php echo _('Net')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_amount", label:"<?php echo _('Total')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       
				       //					 {key:"families", label:"<?php echo _('Customers')?>", sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				      //{key:"active", label:"<?php echo _('Customers')?>", sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      

					 ];
	    
	    this.dataSource0 = new YAHOO.util.DataSource("ar_orders.php?tipo=report_invoices&view="+view+"&nr=10&from=<?php echo$_SESSION['state']['report_activity']['from']?>&to=<?php echo$_SESSION['state']['report_activity']['to']?>");

	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    rtext:"resultset.rtext",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 "id",
			 "customer",
			 "date",
			 "total_amount",
			 "state",
			 "orders","net",
			 "dns"
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
	    this.table0.filter={key:'public_id',value:''};

	    
	};
    });



function init(){
    
//var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
// oACDS.queryMatchContains = true;
// var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 //oAutoComp.minQueryLength = 0; 


 //panel1 = new YAHOO.widget.Panel("orders1", { visible:false, constraintoviewport:true } );
  //  panel1.render();

   // YAHOO.util.Event.addListener("invoices", "click", show_invoices);
   // YAHOO.util.Event.addListener("invoices_total", "click", show_invoices);
   // YAHOO.util.Event.addListener("invoices_home", "click", show_invoices_home);
   // YAHOO.util.Event.addListener("invoices_nohome", "click", show_invoices_nohome);
   // YAHOO.util.Event.addListener("invoices_partner", "click", show_invoices_partner);


}

YAHOO.util.Event.onDOMReady(init);

function change_period(e,x){
location.href='report_sales_activity.php?period='+e;
}

function change_compare(){
alert('x')
}
YAHOO.util.Event.onContentReady("period_menu", function () {
	var oMenu = new YAHOO.widget.Menu("period_menu", { context:["period_label","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("period_label", "click", oMenu.show, null, oMenu);
    });
YAHOO.util.Event.onContentReady("compare_menu", function () {
	var oMenu = new YAHOO.widget.Menu("compare_menu", { context:["compare_label","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("compare_label", "click", oMenu.show, null, oMenu);
    });