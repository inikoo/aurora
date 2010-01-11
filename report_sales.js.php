<?php
include_once('common.php');
?>

var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

    var view='invoices';
    var panel1;

 var show_invoices=function(){
     Dom.get('clean_table_title0').innerHTML='<?php echo _('Orders invoiced').' '.$_SESSION['state']['report']['sales']['period']?>.';
     request="ar_orders.php?tipo=report_invoices&saveto=report_sales&where="+escape('where true')+"&view=invoices&sf=0&nr=10&from=<?php echo$_SESSION['state']['report']['sales']['from']?>&to=<?php echo$_SESSION['state']['report']['sales']['to']?>"
     //  alert(request);
     var table=tables.table0;
     var datasource=tables.dataSource0;
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
     panel1.show();

 }
 var show_invoices_home=function(){
     Dom.get('clean_table_title0').innerHTML='<?php echo$myconf['_home']." "._('orders invoiced (excluding partners)').' '.$_SESSION['state']['report']['sales']['period']?>.';
     request="ar_orders.php?tipo=report_invoices&saveto=report_sales&where="+escape('where  `Invoice For Partner`="No" and `Invoice Billing Country 2 Alpha Code`="<?php echo$myconf['country_2acode']?>"')+"&view=invoices&sf=0&nr=10&from=<?php echo$_SESSION['state']['report']['sales']['from']?>&to=<?php echo$_SESSION['state']['report']['sales']['to']?>"
     // alert(request);
     var table=tables.table0;
     var datasource=tables.dataSource0;
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
     panel1.show();

 }

var show_invoices_nohome=function(){
     Dom.get('clean_table_title0').innerHTML='<?php echo _('Export orders invoiced (excluding partners)').' '.$_SESSION['state']['report']['sales']['period']?>';
     request="ar_orders.php?tipo=report_invoices&saveto=report_sales&where="+escape('where  `Invoice For Partner`="No" and  `Invoice Billing Country 2 Alpha Code`!="<?php echo$myconf['country_2acode']?>"')+"&view=invoices&sf=0&nr=10&from=<?php echo$_SESSION['state']['report']['sales']['from']?>&to=<?php echo$_SESSION['state']['report']['sales']['to']?>"

     var table=tables.table0;
     var datasource=tables.dataSource0;
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
     panel1.show();

 }
   
var show_invoices_partner=function(){
     Dom.get('clean_table_title0').innerHTML='<?php echo _('Partners orders invoiced').' '.$_SESSION['state']['report']['sales']['period']?>';
     request="ar_orders.php?tipo=report_invoices&saveto=report_sales&where="+escape('where  `Invoice For Partner`="Yes" ')+"&view=invoices&sf=0&nr=10&from=<?php echo$_SESSION['state']['report']['sales']['from']?>&to=<?php echo$_SESSION['state']['report']['sales']['to']?>"

     var table=tables.table0;
     var datasource=tables.dataSource0;
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
     panel1.show();

 }

    var show_invoices_country=function(country_code,name){

     Dom.get('clean_table_title0').innerHTML=name+' <?php echo _('orders invoiced').' '.$_SESSION['state']['report']['sales']['period']?>';
     request="ar_orders.php?tipo=report_invoices&saveto=report_sales&where="+escape('where  `Invoice For Partner`="No"    and `Invoice Billing Country 2 Alpha Code`="'+country_code+'"')+"&view=invoices&sf=0&nr=10&from=<?php echo$_SESSION['state']['report']['sales']['from']?>&to=<?php echo$_SESSION['state']['report']['sales']['to']?>"

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
	    
	    this.dataSource0 = new YAHOO.util.DataSource("ar_orders.php?tipo=report_invoices&view="+view+"&nr=10&from=<?php echo$_SESSION['state']['report']['sales']['from']?>&to=<?php echo$_SESSION['state']['report']['sales']['to']?>");

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
									       rowsPerPage    : <?php echo$_SESSION['state']['orders']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders']['table']['order_dir']?>"
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

	Event.addListener('go_free_report', "click", go_free);

    	cal2 = new YAHOO.widget.Calendar("cal2","cal2Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
 
	cal2.update=updateCal;

	cal2.id=2;
	cal2.render();

	cal2.cfg.setProperty("iframe", true);
	
	cal2.cfg.setProperty("zIndex", 10);
	//cal2.stackIframe();
	//cal2..showIframe();
	//alert(updateCal);

	cal2.update();

	cal2.selectEvent.subscribe(handleSelect, cal2, true);
	
 
	cal1 = new YAHOO.widget.Calendar("cal1","cal1Container", { title:"<?php echo _('Choose a date')?>:", close:true } );
	cal1.update=updateCal;cal1.id=1;cal1.render();

	cal1.cfg.setProperty("iframe", true);
	
	cal1.cfg.setProperty("zIndex", 10);
	cal1.update();cal1.selectEvent.subscribe(handleSelect, cal1, true); 
	YAHOO.util.Event.addListener("calpop1", "click", cal1.show, cal1, true);
	YAHOO.util.Event.addListener("calpop2", "click", cal2.show, cal2, true);
	
	
	
	


    
var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 


 panel1 = new YAHOO.widget.Panel("orders1", { visible:false, constraintoviewport:true } );
    panel1.render();

    YAHOO.util.Event.addListener("invoices", "click", show_invoices);
    YAHOO.util.Event.addListener("invoices_total", "click", show_invoices);
    YAHOO.util.Event.addListener("invoices_home", "click", show_invoices_home);
    YAHOO.util.Event.addListener("invoices_nohome", "click", show_invoices_nohome);
    YAHOO.util.Event.addListener("invoices_partner", "click", show_invoices_partner);


}

YAHOO.util.Event.onDOMReady(init);