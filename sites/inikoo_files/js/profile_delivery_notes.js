var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;




YAHOO.namespace ("orders"); 


YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.orders.XHR_JSON = new function() {


	    this.orderLink=  function(el, oRecord, oColumn, oData) {
		var url="order.php?id="+oRecord.getData("id");
		el.innerHTML = oData.link(url);
	    }
	    
	    this.customerLink=  function(el, oRecord, oColumn, oData) {
		if(oData==null)
		    oData='Error, no customer name';

		var url="contact.php?id="+oRecord.getData("customer_id");
		el.innerHTML = oData.link(url);
	    }


	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var OrdersColumnDefs = [
				     {key:"part", label:"Part",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"used_in", label:"Used In",width:330,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				     ,{key:"description", label:"Description",width:330,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     
				     ,{key:"quantity",label:"Qty", width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      
				  
					 ];
		//alert("ar_orders.php?tipo=transactions_in_process_in_dn&tid=0");
	    this.OrdersDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=transactions_in_process_in_dn&tid=0&id="+Dom.get('dn_key').value);
	    this.OrdersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.OrdersDataSource.connXhrMode = "queueRequests";
	    this.OrdersDataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "part","description","quantity","used_in"

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


}

YAHOO.util.Event.onDOMReady(init);
