var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;


Event.addListener(window, "load", function() {
    tables = new function() {

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
	   
	    
			     {key:"code", label:Dom.get('label_code').value,width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:Dom.get('label_description').value,width:530,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"quantity",label:Dom.get('label_quantity').value, width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     //  ,{key:"gross",label:Dom.get('label_gross').value,  width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"discount",label:Dom.get('label_discount').value,  width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"to_charge",label:Dom.get('label_net').value, width:85,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ];
		//alert("ar_orders.php?tipo=orders&where=");
		request="ar_orders.php?tipo=transactions&parent=order_in_process_by_customer&parent_key="+Dom.get('order_key').value+"&tableid=0"
	 
	    this.dataSource0 = new YAHOO.util.DataSource(request);
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
			 "code"
			 ,"description"
			 ,"quantity"
			 ,"discount"
			 ,"to_charge","gross","tariff_code","created","last_updated"
			 // "promotion_id",
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : 500,containers : 'paginator0', 
 									      pageReportTemplate : '(Page {currentPage} of {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    
	     this.table0.request=request;
  this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);

	    
	    this.table0.filter={key:'',value:''};

	


    
    };
  });

function cancel_order(){

   //Dom.setStyle('cancel_buttons', 'display', 'none')
        Dom.get('cancel_order_img').src='art/loading.gif';
        var value = encodeURIComponent('Cancelled by customer');
        var ar_file = 'ar_edit_orders.php';
        var request = 'tipo=cancel&note=' + value+'&order_key='+Dom.get('order_key').value;
     //   alert(request)
        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {
        //        alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {
					
					location.href='basket.php?cancelled=1';

                } else {
                    alert('EC23'+r.msg)
                    //Dom.setStyle('cancel_buttons', 'display', '')
                    //Dom.setStyle('cancel_wait', 'display', 'none')
                }
            },
            failure: function(o) {
                alert(o.statusText);

            },
            scope: this
        }, request

        );


}

function show_cancel_order_info(){
Dom.setStyle('cancel_order_info','display','')
}

function hide_cancel_order_info(){
Dom.setStyle('cancel_order_info','display','none')

}

function init_basket(){
	 Event.addListener('cancel_order', "click",cancel_order);
	 Event.addListener('cancel_order', "mouseover",show_cancel_order_info);
	 Event.addListener('cancel_order', "mouseout",hide_cancel_order_info);


}

YAHOO.util.Event.onDOMReady(init_basket);