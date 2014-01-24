<?php
include_once('common.php');


?>

var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
     



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {



   var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"public_id", label:"<?php echo _('Order ID')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				   	{key:"total_amount", label:"<?php echo _('Total')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},

				   {key:"date", label:"<?php echo _('Order Date')?>", width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				     {key:"dispatch_state",label:"<?php echo _('Dispatch')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				     {key:"payment_state",label:"<?php echo _('Payment')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       //{key:"weight", label:"<?php echo _('Weight')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       //{key:"picks", label:"<?php echo _('Picks')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				      
				      
				      {key:"operations", label:"<?php echo _('Actions')?>", width:220,hidden:false,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				      // {key:"see_link", label:"",sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},

				];

	    this.dataSource1 = new YAHOO.util.DataSource("ar_edit_orders.php?tipo=store_pending_orders&tableid=1&parent_key="+Dom.get('store_key').value);
		//alert("ar_edit_orders.php?tipo=ready_to_pick_orders");
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
			 "id","public_id",
			 "weight","picks",
			 "customer",
			 "date","picker","packer","dispatch_state","payment_state","operations","see_link","total_amount"
			
			 ]};

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['customers']['pending_orders']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customers']['pending_orders']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customers']['pending_orders']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['customers']['pending_orders']['f_field']?>',value:'<?php echo$_SESSION['state']['customers']['pending_orders']['f_value']?>'};

	    
	    this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);

	
	
	
	
	};
    });


function change_reason_cancel(){

	if(Dom.get('cancel_input').value!=''){
	Dom.removeClass('cancel_save','disabled')
	}else{
		Dom.addClass('cancel_save','disabled')
	}

}

function approve_dispatching(o, order_key) {

    if (Dom.get('approve_dispatching_img_' + order_key) != undefined) Dom.get('approve_dispatching_img_' + order_key).src = 'art/loading.gif';

    ar_file = 'ar_edit_orders.php';
    request = ar_file + '?tipo=approve_dispatching_order&order_key=' + order_key;
    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                Dom.get('operations' + r.order_key).innerHTML = r.order_operations;
                Dom.get('dispatch_state_' + r.order_key).innerHTML = r.order_dispatch_state;
                Dom.get('payment_state_' + r.order_key).innerHTML = r.order_payment_state;
            }
        },
        failure: function(o) {
            alert(o.statusText);
        },
        scope: this
    });
}

function create_invoice(o, order_key) {
    if (Dom.get('create_invoice_img_' + order_key) != undefined) Dom.get('create_invoice_img_' + order_key).src = 'art/loading.gif'


    var request = 'ar_edit_orders.php?tipo=create_invoice_order&order_key=' + escape(order_key);
    //  alert(request); //return;
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('operations' + r.order_key).innerHTML = r.order_operations;
                alert('dispatch_state_' + r.order_key)
                // 267599
                Dom.get('dispatch_state_' + r.order_key).innerHTML = r.order_dispatch_state;
                Dom.get('payment_state_' + r.order_key).innerHTML = r.order_payment_state;

            } else {
                alert(r.msg)

            }
        }
    });

}


function save_cancel(){
  if (Dom.hasClass('cancel_save', 'disabled')) {
            return;
        }
        Dom.setStyle('cancel_buttons', 'display', 'none')
        Dom.setStyle('cancel_wait', 'display', '')
        var value = encodeURIComponent(Dom.get("cancel_input").value);
        var ar_file = 'ar_edit_orders.php';
        var request = 'tipo=cancel&note=' + value+'&order_key='+Dom.get('cancel_order_key').value;
        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {
                //alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {

                    Dom.get('operations' + r.order_key).innerHTML = r.operations;
                    Dom.get('dispatch_state_' + r.order_key).innerHTML = r.dispatch_state;
                    Dom.get('payment_state_' + r.order_key).innerHTML = r.payment_state;
					dialog_cancel.hide()
                   get_store_pending_orders_numbers('','')

                } else {
                    alert(r.msg)
                    Dom.setStyle('cancel_buttons', 'display', '')
                    Dom.setStyle('cancel_wait', 'display', 'none')
                }
            },
            failure: function(o) {
                alert(o.statusText);

            },
            scope: this
        }, request

        );


}

function cancel(o,order_key,order_info){

 region1 = Dom.getRegion(o);
    region2 = Dom.getRegion('dialog_cancel');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_cancel', pos);
    Dom.get('cancel_order_number_label').innerHTML = order_info;
Dom.get('cancel_input').value='';
		Dom.addClass('cancel_save','disabled')

Dom.setStyle('cancel_buttons', 'display', '')
        Dom.setStyle('cancel_wait', 'display', 'none')


    Dom.get('cancel_order_key').value = order_key;
    dialog_cancel.show();
    Dom.get('cancel_input').focus();

}

function get_store_pending_orders_numbers(from, to) {
		
    Dom.get('elements_InProcessbyCustomer_number').innerHTML='<img style="width:12.9px" src="art/loading.gif"/>';
     Dom.get('elements_InProcess_number').innerHTML='<img style="width:12.9px" src="art/loading.gif"/>';
    Dom.get('elements_SubmittedbyCustomer_number').innerHTML='<img style="width:12.9px" src="art/loading.gif"/>';
    Dom.get('elements_Packed_number').innerHTML='<img style="width:12.9px" src="art/loading.gif"/>';
    Dom.get('elements_InWarehouse_number').innerHTML='<img style="width:12.9px" src="art/loading.gif"/>';
   
        var ar_file = 'ar_orders.php';
        var request = 'tipo=number_store_pending_orders_in_interval&parent=store&parent_key=' + Dom.get('store_key').value + '&from=' + from + '&to=' + to;
        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {

                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {
                    for (i in r.elements_numbers) {
                        Dom.get('elements_' + i + '_number').innerHTML = r.elements_numbers[i]
                    }
                }
            },
            failure: function(o) {
                // alert(o.statusText);
            },
            scope: this
        }, request

        );
    }


function init() {

get_store_pending_orders_numbers('','')

  init_search('orders_store');

    YAHOO.util.Event.addListener('clean_table_filter_show0', "click", show_filter, 1);
    YAHOO.util.Event.addListener('clean_table_filter_hide0', "click", hide_filter, 1);
    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS);
    oAutoComp.minQueryLength = 0;


  dialog_cancel = new YAHOO.widget.Dialog("dialog_cancel", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_cancel.render();

}


YAHOO.util.Event.onDOMReady(init);


YAHOO.util.Event.onContentReady("filtermenu1", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
        trigger: "filter_name1"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});


YAHOO.util.Event.onContentReady("rppmenu1", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {
        trigger: "rtext_rpp1"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});
