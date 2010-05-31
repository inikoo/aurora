<?php include_once('common.php');

print "var top=".$_SESSION['state']['report']['customers']['top'].";";
print "var criteria='".$_SESSION['state']['report']['customers']['criteria']."';";

?>


YAHOO.util.Event.onContentReady("table<?php print $_REQUEST['table_id']?>", function () {



	     //START OF THE TABLE=========================================================================================================================
<?php print "var tableid=".$_REQUEST['table_id'].";";?>

		
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"position", label:"", width:2,sortable:false,className:"aleft"}
				       ,{key:"name", label:"<?php echo _('Customer Name')?>", width:185,sortable:false,className:"aleft"}

				      
				       ,{key:"last_order", label:"<?php echo _('Last Order')?>",width:70,sortable:false,className:"aright"}
				       ,{key:"invoices", label:"<?php echo _('Invoices')?>",sortable:false,className:"aright"}
				    
				       
				     	      
				      ,{key:"net_balance", label:"<?php echo _('Balance')?>",sortable:false,className:"aright"}
				      
				      
				      
				     
				       

					 ];
	    //?tipo=customers&tid=0"
	    this.dataSourcetopcust = new YAHOO.util.DataSource("ar_reports.php?tipo=customers&nr=20&tableid="+tableid);
	  //  alert("ar_reports.php?tipo=customers&nr=20&tableid="+tableid)
	    this.dataSourcetopcust.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSourcetopcust.connXhrMode = "queueRequests";
	    this.dataSourcetopcust.responseSchema = {
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



	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSourcetopcust
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
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table1.view='<?php echo$_SESSION['state']['customers']['view']?>';

	    this.table1.filter={key:'<?php echo$_SESSION['state']['customers']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['table']['f_value']?>'};

	    //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table1,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	});

function change_criteria(){
 var table=tables['table1'];
      var datasource=tables.dataSource0;
      var request='&o='+this.id;
      var ids=['net_balance','invoices'];
        Dom.removeClass(ids,'selected');
      Dom.addClass(this,'selected');
      datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   

}
function change_top(){
var table=tables['table1'];
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
  //  var ids=['net_balance','invoices'];
   // YAHOO.util.Event.addListener(ids, "click", change_criteria);
    //var ids=['top10','top25','top100','top200'];
 
   //YAHOO.util.Event.addListener(ids, "click", change_top);

   //YAHOO.util.Event.addListener('export', "click", export_data);
   //YAHOO.util.Event.addListener('export', "contextmenu", change_export_type,'export');



  //var oContextMenu = new YAHOO.widget.ContextMenu("export_menu", {
   //     trigger: 'export'
   // });
 
  
    //oContextMenu.render(document.body);





}

YAHOO.util.Event.onDOMReady(init);
