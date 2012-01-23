<?php
include_once('common.php');
if(!$user->can_view('orders'))
  exit();
?>

var dialog_pick_it;
var dialog_pack_it;

YAHOO.namespace ("orders"); 


YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.orders.XHR_JSON = new function() {


		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var OrdersColumnDefs = [
				     {key:"part", label:"<?php echo _('Part')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				     ,{key:"description", label:"<?php echo _('Description')?>",width:400,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     
				     ,{key:"quantity",label:"<?php echo _('Qty')?>", width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"picked",label:"<?php echo _('Picked')?>", width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"packed",label:"<?php echo _('Packed')?>", width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"notes",label:"<?php echo _('Notes')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC},action:'edit_object',object:'pending_transactions'}

				  
					 ];

	    this.OrdersDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=transactions_in_process_in_dn&tid=0");
	    this.OrdersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.OrdersDataSource.connXhrMode = "queueRequests";
	    this.OrdersDataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "part","description","quantity","picked","packed","notes"
			 ]};
	    this.OrdersDataTable = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
								   this.OrdersDataSource, {
								       renderLoopSize: 50
								   }
								   
								   );
	 
	    
	    this.OrdersDataTable.id=tableid;
	    this.OrdersDataTable.editmode=false;

	    
	};
    });




function init(){
init_search('orders_store');


    function mygetTerms(query) {
	var Dom = YAHOO.util.Dom
	var table=YAHOO.orders.XHR_JSON.OrdersDataTable;
	var data=table.getDataSource();
	var newrequest="&sf=0&f_field="+Dom.get('f_field0').value+"&f_value="+Dom.get('f_input0').value;

	//	alert(newrequest);
	data.sendRequest(newrequest,{success:table.onDataReturnInitializeTable, scope:table});
    };
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
    
Event.addListener("pick_it", "click", pick_it);
Event.addListener("pack_it", "click", pack_it);


    dialog_pick_it = new YAHOO.widget.Dialog("dialog_pick_it", {context:["pick_it","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_pick_it.render();
Event.addListener("close_dialog_pick_it", "click", dialog_pick_it.hide,dialog_pick_it , true);

   dialog_pack_it = new YAHOO.widget.Dialog("dialog_pack_it", {context:["pack_it","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
dialog_pack_it.render();
Event.addListener("close_dialog_pack_it", "click", dialog_pack_it.hide,dialog_pack_it , true);




}

function pick_it(){
state=Dom.get('dn_state').value;
if(Dom.get('dn_picker_key').value){
window.location='order_pick_aid.php?id='+Dom.get('dn_key').value;
}else{
	dialog_pick_it.show()
}
}

function pack_it(){
if(Dom.get('dn_picker_key').value){
window.location='order_pack_aid.php?id='+Dom.get('dn_key').value;
}else if (Dom.get('dn_picker_key').value){
dialog_pack_it.show
}

}

YAHOO.util.Event.onDOMReady(init);
