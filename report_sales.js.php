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
   
var show_invoices_unknown=function(){
     Dom.get('clean_table_title0').innerHTML='<?php echo _('Unknown location orders').' '.$_SESSION['state']['report']['sales']['period']?>';
     request="ar_orders.php?tipo=report_invoices&saveto=report_sales&where="+escape('where  `Invoice For Partner`="No" and `Invoice Billing Country 2 Alpha Code`="XX"')+"&view=invoices&sf=0&nr=10&from=<?php echo$_SESSION['state']['report']['sales']['from']?>&to=<?php echo$_SESSION['state']['report']['sales']['to']?>"

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
//alert("ar_orders.php?tipo=report_invoices&view="+view+"&nr=10&from=<?php echo$_SESSION['state']['report']['sales']['from']?>&to=<?php echo$_SESSION['state']['report']['sales']['to']?>")
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
									 key: "<?php echo $_SESSION['state']['report']['sales']['order']?>",
									 dir: "<?php echo $_SESSION['state']['report']['sales']['order_dir']?>"
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



	var go_free = function(e){
	    var from=Dom.get('v_calpop1').value;
	    var to=Dom.get('v_calpop2').value;
	    location.href='report_sales.php?tipo=f&from='+from+'&to='+to; 
	};

function quick_link(e,tipo){
    location.href='report_sales.php?tipo='+tipo;
};

function init(){
  

    Event.addListener(['quick_all','quick_this_month','quick_this_week','quick_yesterday','quick_today'], "click", quick_link);



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
    YAHOO.util.Event.addListener("invoices_unknown", "click", show_invoices_unknown);
    YAHOO.util.Event.addListener("invoices_partner", "click", show_invoices_partner);


    
 
	


	
	

    


}

YAHOO.util.Event.onDOMReady(init);