<?php
include_once('common.php');
	
$order_key=0;
if(isset($_REQUEST['order_key']) )
    $order_key=$_REQUEST['order_key'];
print "var order_key=$order_key;";

?>


var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_cancel,dialog_edit_shipping;
var change_staff_discount;
YAHOO.namespace ("invoice"); 

var edit_delivery_address;




YAHOO.util.Event.addListener(window, "load", function() {
 tables  = new function() {

	    
		
	    var tableid=0; 
	    // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;


	    var InvoiceColumnDefs = [
				        {key:"pid", label:"Product ID", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				     
					,{key:"code", label:"Code",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"Description",width:400,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"stock",label:"Able", hidden:(Dom.get('dispatch_state').value=='In Process'?false:true),width:80,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"quantity",label:"Qty", width:40,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"add",label:"",hidden:(Dom.get('dispatch_state').value=='In Process'?false:true), width:5,sortable:false}
					,{key:"remove",label:"",hidden:(Dom.get('dispatch_state').value=='In Process'?false:true), width:5,sortable:false}
			     ,{key:"to_charge",label:"To Charge", width:75,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"dispatching_status",label:"Status" ,hidden:(Dom.get('dispatch_state').value!='In Process'?false:true),width:90,sortable:false,className:"aright"}

				];

		//alert("ar_edit_orders.php?tipo=transactions_to_process&tid=0&sf=0&f_value=&display="+Dom.get('products_display_type').value);
	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_orders.php?tipo=transactions_to_process&tid=0&sf=0&f_value=&display="+Dom.get('products_display_type').value);
	    //alert("ar_edit_orders.php?tipo=transactions_to_process&tid=0&sf=0&f_value=&display="+Dom.get('products_display_type').value)
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
			 "code"
			 ,"description"
			 ,"quantity"
			 ,"discount"
			 ,"to_charge","gross","tariff_code","stock","add","remove","pid",'dispatching_status'
			 // "promotion_id",
			 ]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
								   this.dataSource0, {
								      renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['order'][$_SESSION['state']['order']['products']['display']]['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['order']['products']['order']?>",
									 dir: "<?php echo$_SESSION['state']['order']['products']['order_dir']?>"
								     }
							   ,dynamicData : true
								   }
								   
								   );
	

	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginator = mydoBeforePaginatorChange;
	  	
	  	
	  			this.table0.table_id=tableid;
     	this.table0.subscribe("renderEvent", myrenderEvent);

	    this.table0.filter={key:'<?php echo$_SESSION['state']['order']['products']['f_field']?>',value:''};
    
    };
  });






function init(){

 init_search('products_store');

   var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
    oAutoComp.minQueryLength = 0; 

Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);


Event.addListener("modify_order", "click", modify_order);




}


function modify_order(){
window.location='order.php?id='+Dom.get('order_key').value+'&amend=1';
}

YAHOO.util.Event.onDOMReady(init);





YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });
