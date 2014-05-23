<?php
include_once('common.php');?>




Event.addListener(window, "load", function() {
    tables = new function() {





	
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
			     {key:"code", label:"<?php echo _('Code')?>",width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo _('Description')?>",width:330,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     //,{key:"tariff_code", label:"<?php echo _('Tariff Code')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"quantity",label:"<?php echo _('Qty')?>", width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"gross",label:"<?php echo _('Amount')?>",<?php echo(($_SESSION['state']['order_in_process_by_customer']['items']['view']=='basket')  ?'':'hidden:true,')?>  width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"discount",label:"<?php echo _('Discounts')?>",<?php echo(($_SESSION['state']['order_in_process_by_customer']['items']['view']=='basket')  ?'':'hidden:true,')?>  width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"to_charge",label:"<?php echo _('To Charge')?>",<?php echo(($_SESSION['state']['order_in_process_by_customer']['items']['view']=='basket')  ?'':'hidden:true,')?> width:85,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"created",label:"<?php echo _('Created')?>", <?php echo(($_SESSION['state']['order_in_process_by_customer']['items']['view']=='times')  ?'':'hidden:true,')?>width:190,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"last_updated",label:"<?php echo _('Updated')?>",<?php echo(($_SESSION['state']['order_in_process_by_customer']['items']['view']=='times')  ?'':'hidden:true,')?> width:190,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ];
		//alert("ar_orders.php?tipo=orders&where=");
		request="ar_orders.php?tipo=transactions&parent=order_in_process_by_customer&parent_key="+Dom.get('order_key').value+"&tableid=0"
	   // alert(request)
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
									       rowsPerPage    : <?php echo$_SESSION['state']['order_in_process_by_customer']['items']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['order_in_process_by_customer']['items']['order']?>",
									 dir: "<?php echo$_SESSION['state']['order_in_process_by_customer']['items']['order_dir']?>"
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

	    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['order_in_process_by_customer']['items']['f_field']?>',value:'<?php echo$_SESSION['state']['order_in_process_by_customer']['items']['f_value']?>'};

	


    
    };
  });

function change_items_view(e, data) {
    tipo = this.id;



    if (tipo == 'items_basket') tipo = 'basket';
    else if (tipo == 'items_times') tipo = 'times';

    var table = tables['table' + data.table_id];
    table.hideColumn('quantity');
    table.hideColumn('gross');
    table.hideColumn('discount');
    table.hideColumn('to_charge');
    table.hideColumn('created');
    table.hideColumn('last_updated');


    if (tipo == 'basket') {
        table.showColumn('quantity');
        table.showColumn('gross');
        table.showColumn('discount');
        table.showColumn('to_charge');
        table.showColumn('smallname');
    } else if (tipo == 'times') {
        table.showColumn('created');
        table.showColumn('last_updated');
    }
    Dom.removeClass(Dom.getElementsByClassName('table_option', 'button', this.parentNode), 'selected')
    Dom.addClass(this, "selected");


    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=' + data.parent + '-items-view&value=' + escape(tipo), {});

}

function save(tipo) {
    //alert(tipo)
    switch (tipo) {
    case ('cancel'):

        if (Dom.hasClass('cancel_save', 'disabled')) {
            return;
        }
        Dom.setStyle('cancel_buttons', 'display', 'none')
        Dom.setStyle('cancel_wait', 'display', '')
        var value = encodeURIComponent(Dom.get("cancel_input").value);
        var ar_file = 'ar_edit_orders.php';
        var request = 'tipo=cancel&note=' + value+'&order_key='+Dom.get('order_key').value;
        //alert('R:'+request);
        YAHOO.util.Connect.asyncRequest('POST', ar_file, {
            success: function(o) {
                //alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {

                    window.location.reload();
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


        break;
    }

}

function open_cancel_dialog() {


    Dom.get("cancel_input").value = '';
    Dom.addClass('cancel_save', 'disabled')

    dialog_cancel.show();
    Dom.get('cancel_input').focus();
}

function change(e, o, tipo) {
    switch (tipo) {
    case ('cancel'):


        if (o.value != '') {
            enable_save(tipo);

            if (window.event) key = window.event.keyCode; //IE
            else key = e.which; //firefox     
            if (key == 13) save(tipo);


        } else disable_save(tipo);
        break;
    }
};

function enable_save(tipo) {
    switch (tipo) {
    case ('cancel'):

        Dom.removeClass(tipo + '_save', 'disabled')

        break;
    }
};

function disable_save(tipo) {
    switch (tipo) {
    case ('cancel'):
        Dom.addClass(tipo + '_save', 'disabled')
        break;
    }
};


function close_dialog(tipo) {
    switch (tipo) {


    case ('cancel'):


        dialog_cancel.hide();

        break;
    }
};


function init() {

    init_search('orders_store');
  dialog_cancel = new YAHOO.widget.Dialog("dialog_cancel", {
        context: ["cancel", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_cancel.render();
    
    ids = ['items_basket', 'items_times'];
    
            YAHOO.util.Event.addListener(ids, "click", change_items_view, {
        'table_id': 0,
        'parent': 'order_in_process_by_customer'
    })
    
        YAHOO.util.Event.addListener("cancel", "click", open_cancel_dialog);


    }

    YAHOO.util.Event.onDOMReady(init);
