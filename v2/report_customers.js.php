<?php 
include_once('common.php');
?>


var dialog_report_options;
YAHOO.util.Event.addListener(window, "load", function() {

 var Dom   = YAHOO.util.Dom;

    tables = new function() {



	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"position", label:"", width:15,sortable:false,className:"aleft"}
				       ,{key:"store", label:"S", width:15,sortable:false,className:"aleft"}
				       ,{key:"id", label:"<?php echo _('ID')?>",width:50,sortable:false,<?php echo($_SESSION['state']['report_customers']['view']=='general'?'':'hidden:true,')?>className:"aright"}
				       ,{key:"name", label:"<?php echo _('Customer Name')?>", width:220,sortable:false,className:"aleft"}

				      
				       ,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['report_customers']['view']=='general'?'':'hidden:true,')?> width:200,sortable:false,className:"aleft"}
				       ,{key:"last_order", label:"<?php echo _('Last Order')?>",<?php echo($_SESSION['state']['report_customers']['view']=='general'?'':'hidden:true,')?>width:100,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"invoices", label:"<?php echo _('Invoices')?>",<?php echo($_SESSION['state']['report_customers']['view']=='general'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				       
				       // ,{key:"total_payments", label:"<?php echo _('Total')?>",<?php echo($_SESSION['state']['report_customers']['view']=='general'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}} 
				       ,{key:"contact_name", label:"<?php echo _('Contact Name')?>",<?php echo($_SESSION['state']['report_customers']['view']=='contact'?'':'hidden:true,')?>sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       
				       ,{key:"email", label:"<?php echo _('Email')?>",<?php echo($_SESSION['state']['report_customers']['view']=='contact'?'':'hidden:true,')?>sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"telephone", label:"<?php echo _('Telephone')?>",<?php echo($_SESSION['state']['report_customers']['view']=='contact'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       
				       ,{key:"address", label:"<?php echo _('Main Address')?>",<?php echo($_SESSION['state']['report_customers']['view']=='address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"town", label:"<?php echo _('Town')?>",<?php echo($_SESSION['state']['report_customers']['view']=='address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"postcode", label:"<?php echo _('Postal Code')?>",<?php echo($_SESSION['state']['report_customers']['view']=='address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"region", label:"<?php echo _('Region')?>",<?php echo($_SESSION['state']['report_customers']['view']=='address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"country", label:"<?php echo _('Country')?>",<?php echo($_SESSION['state']['report_customers']['view']=='address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       //				       ,{key:"ship_address", label:"<?php echo _('Ship to Address')?>",<?php echo($_SESSION['state']['report_customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"ship_town", label:"<?php echo _('Town')?>",<?php echo($_SESSION['state']['report_customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"ship_postcode", label:"<?php echo _('Postal Code')?>",<?php echo($_SESSION['state']['report_customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"ship_region", label:"<?php echo _('Region')?>",<?php echo($_SESSION['state']['report_customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"ship_country", label:"<?php echo _('Country')?>",<?php echo($_SESSION['state']['report_customers']['view']=='ship_to_address'?'':'hidden:true,')?>sortable:false,className:"aright"}
				       ,{key:"total_payments", label:"<?php echo _('Payments')?>",<?php echo($_SESSION['state']['report_customers']['view']=='balance'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_refunds", label:"<?php echo _('Refunds')?>",<?php echo($_SESSION['state']['report_customers']['view']=='balance'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"net_balance", label:"<?php echo _('Total Net')?>",<?php echo(($_SESSION['state']['report_customers']['view']=='general' or $_SESSION['state']['report_customers']['view']=='balance') ?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"balance", label:"<?php echo _('Outstanding')?>",<?php echo($_SESSION['state']['report_customers']['view']=='balance'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"total_profit", label:"<?php echo _('Profit')?>",<?php echo($_SESSION['state']['report_customers']['view']=='balance'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"activity", label:"<?php echo _('Status')?>",<?php echo($_SESSION['state']['report_customers']['view']=='general'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       ,{key:"top_orders", label:"<?php echo _('Rank Orders')?>",<?php echo($_SESSION['state']['report_customers']['view']=='rank'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_invoices", label:"<?php echo _('Rank Invoices')?>",<?php echo($_SESSION['state']['report_customers']['view']=='rank'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_balance", label:"<?php echo _('Rank Balance')?>",<?php echo($_SESSION['state']['report_customers']['view']=='rank'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"top_profits", label:"<?php echo _('Rank Profits')?>",<?php echo($_SESSION['state']['report_customers']['view']=='rank'?'':'hidden:true,')?>sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       

					 ];
	    //?tipo=customers&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_reports.php?tipo=top_customers&tableid=0");
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
									      rowsPerPage    : <?php echo$_SESSION['state']['report_customers']['top'] ?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['report_customers']['criteria']?>",
									 dir: "desc"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	  
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	  
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	      
		this.table0.table_id=tableid;
		
      
       this.table0.subscribe("renderEvent", myrenderEvent);
		    
	    this.table0.view='<?php echo$_SESSION['state']['report_customers']['view']?>';

	    this.table0.filter={key:'<?php echo$_SESSION['state']['report_customers']['f_field']?>',value:'<?php echo$_SESSION['state']['report_customers']['f_value']?>'};

	

	
	};
    });

function change_criteria() {
    var table = tables['table0'];
    var datasource = tables.dataSource0;
    var request = '&o=' + this.id;
    var ids = ['net_balance', 'invoices'];
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    dialog_report_options.hide()


}

function change_top() {
    var table = tables['table0'];
    var datasource = tables.dataSource0;
    var request = '&nr=' + this.getAttribute('top');
    //alert(request)
    var ids = ['top10', 'top25', 'top100', 'top200'];
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    dialog_report_options.hide()

}

function show_dialog_options() {

    region1 = Dom.getRegion('rtext0');
    region2 = Dom.getRegion('dialog_options');
    var pos = [region1.left, region1.bottom]
    Dom.setXY('dialog_options', pos);
    dialog_report_options.show()
}


function post_change_period_actions(r) {
period=r.period;
to=r.to;
from=r.from;


    request = '&from=' + from + '&to=' + to;

    table_id = 0
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
  
    Dom.get('rtext0').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp0').innerHTML = '';
  



}


function init() {
    var ids = ['net_balance', 'invoices'];
    YAHOO.util.Event.addListener(ids, "click", change_criteria);
    var ids = ['top10', 'top25', 'top100', 'top200'];
    YAHOO.util.Event.addListener(ids, "click", change_top);

    dialog_report_options = new YAHOO.widget.Dialog("dialog_options", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_report_options.render();


}

YAHOO.util.Event.onDOMReady(init);
