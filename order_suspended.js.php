<?php
include_once('common.php');?>

YAHOO.namespace ("invoice"); 


YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.invoice.XHR_JSON = new function() {


		
	    //START OF THE TABLE=========================================================================================================================
		
		var tableid=0; 
	    // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var InvoiceColumnDefs = [
				     {key:"code", label:"<?php echo _('Code')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo _('Description')?>",width:330,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     //,{key:"tariff_code", label:"<?php echo _('Tariff Code')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"quantity",label:"<?php echo _('Qty')?>", width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"gross",label:"<?php echo _('Amount')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"discount",label:"<?php echo _('Discounts')?>", width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"to_charge",label:"<?php echo _('To Charge')?>", width:75,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ];


	    this.InvoiceDataSource = new YAHOO.util.DataSource("ar_orders.php?tipo=transactions_cancelled&order_key="+Dom.get('order_key').value+"&tableid=0");
	    this.InvoiceDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.InvoiceDataSource.connXhrMode = "queueRequests";
	    this.InvoiceDataSource.responseSchema = {
		resultsList: "resultset.data", 
		fields: [
			 "code"
			 ,"description"
			 ,"quantity"
			 ,"discount"
			 ,"to_charge","gross","tariff_code"
			 // "promotion_id",
			 ]};
	    this.InvoiceDataTable = new YAHOO.widget.DataTable(tableDivEL, InvoiceColumnDefs,
								   this.InvoiceDataSource, {
								       renderLoopSize: 50
								   }
								   
								   );
	


	


    
    };
  });

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
        var request = 'tipo=cancel&note=' + value;
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

function init(){

init_search('orders_store');
   dialog_cancel = new YAHOO.widget.Dialog("dialog_cancel", {
        context: ["cancel", "tr", "tl"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_cancel.render();
    YAHOO.util.Event.addListener("cancel", "click", open_cancel_dialog);

}

YAHOO.util.Event.onDOMReady(init);
