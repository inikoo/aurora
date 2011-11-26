<?php include_once('common.php');

print "var top=".$_SESSION['state']['report_customers']['top'].";";
print "var criteria='".$_SESSION['state']['report_customers']['criteria']."';";


?>
var link='report_customers.php';
YAHOO.util.Event.addListener(window, "load", function() {

 var Dom   = YAHOO.util.Dom;

    tables = new function() {



	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"position", label:"", width:20,sortable:false,className:"aleft"}
				       ,{key:"store", label:"S", width:20,sortable:false,className:"aleft"}
				       ,{key:"id", label:"<?php echo$customers_ids[0]?>",width:60,sortable:false,<?php echo($_SESSION['state']['customers']['table']['view']=='general'?'':'hidden:true,')?>className:"aright"}
				       ,{key:"name", label:"<?php echo _('Customer Name')?>", width:240,sortable:false,className:"aleft"}

				      
				       ,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='general'?'':'hidden:true,')?> width:230,sortable:false,className:"aleft"}
				       ,{key:"last_order", label:"<?php echo _('Last Order')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='loyalty'?'':'hidden:true,')?>width:100,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"invoices", label:"<?php echo _('Invoices')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='general'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				       
				       // ,{key:"total_payments", label:"<?php echo _('Total')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='general'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}} 
				       ,{key:"contact_name", label:"<?php echo _('Contact Name')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='contact'?'':'hidden:true,')?>sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       
				       ,{key:"email", label:"<?php echo _('Email')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='contact'?'':'hidden:true,')?>sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"telephone", label:"<?php echo _('Telephone')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='contact'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       
				       ,{key:"address", label:"<?php echo _('Main Address')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"town", label:"<?php echo _('Town')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"postcode", label:"<?php echo _('Postal Code')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"region", label:"<?php echo _('Region')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"country", label:"<?php echo _('Country')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       //				       ,{key:"ship_address", label:"<?php echo _('Ship to Address')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"ship_town", label:"<?php echo _('Town')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"ship_postcode", label:"<?php echo _('Postal Code')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"ship_region", label:"<?php echo _('Region')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"ship_country", label:"<?php echo _('Country')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"total_payments", label:"<?php echo _('Payments')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='balance'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_refunds", label:"<?php echo _('Refunds')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='balance'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"net_balance", label:"<?php echo _('Balance')?>",<?php echo(($_SESSION['state']['customers']['table']['view']=='general' or $_SESSION['state']['customers']['table']['view']=='balance') ?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"balance", label:"<?php echo _('Outstanding')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='balance'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_profit", label:"<?php echo _('Profit')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='balance'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"activity", label:"<?php echo _('Status')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='general'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       ,{key:"top_orders", label:"<?php echo _('Rank Orders')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='rank'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_invoices", label:"<?php echo _('Rank Invoices')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='rank'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_balance", label:"<?php echo _('Rank Balance')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='rank'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_profits", label:"<?php echo _('Rank Profits')?>",<?php echo($_SESSION['state']['customers']['table']['view']=='rank'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       

					 ];
	    //?tipo=customers&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_reports.php?tipo=customers");
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
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 'position',
			 'id',
			 'name',
			 'location',
			 'orders',
			 'email',
			 'telephone',
			 'last_order','activity',
			 'total_payments','contact_name'
			 ,"address","town","postcode","region","country"
			 ,"ship_address","ship_town","ship_postcode","ship_region","ship_country"
			 ,"total_paymants","total_refunds","net_balance","total_profit","balance"
			 ,"top_orders","top_invoices","top_balance","top_profits","invoices","store"
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : top,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: criteria,
									 dir: 'desc'
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.view='<?php echo$_SESSION['state']['customers']['table']['view']?>';

	    this.table0.filter={key:'<?php echo$_SESSION['state']['customers']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['table']['f_value']?>'};

	    //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	};
    });


function change_criteria(){
 var table=tables['table0'];
      var datasource=tables.dataSource0;
      var request='&o='+this.id;
      var ids=['net_balance','invoices'];
        Dom.removeClass(ids,'selected');
      Dom.addClass(this,'selected');
      datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   

}
function change_top(){
var table=tables['table0'];
      var datasource=tables.dataSource0;
      var request='&nr='+this.getAttribute('top');
      var ids=['top10','top25','top100'];
       Dom.removeClass(ids,'selected');
      Dom.addClass(this,'selected');
      datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   
}

function export_data(){
    o=Dom.get('export');
    output=o.getAttribute('output');
    location.href='export.php?ar_file=ar_reports&tipo=customers&output='+output;
    
}


function init(){
    var ids=['net_balance','invoices'];
    YAHOO.util.Event.addListener(ids, "click", change_criteria);
    var ids=['top10','top25','top100','top200'];
 
   YAHOO.util.Event.addListener(ids, "click", change_top);

   YAHOO.util.Event.addListener('export', "click", export_data);
   //YAHOO.util.Event.addListener('export', "contextmenu", change_export_type,'export');



  var oContextMenu = new YAHOO.widget.ContextMenu("export_menu", {
        trigger: 'export'
    });
 
  
    oContextMenu.render(document.body);





}

YAHOO.util.Event.onDOMReady(init);
