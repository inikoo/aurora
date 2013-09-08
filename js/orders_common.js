var already_clicked_order_elements_click=false
function change_order_elements(e, elements_type) {
    var el = this
  
        if (already_clicked_order_elements_click)
        {
            already_clicked_order_elements_click=false; // reset
            clearTimeout(alreadyclickedTimeout); // prevent this from happening
            change_order_elements_dblclick(el, elements_type)
        }
        else
        {
            already_clicked_order_elements_click=true;
            alreadyclickedTimeout=setTimeout(function(){
                already_clicked_order_elements_click=false; // reset when it happens
                 change_order_elements_click(el, elements_type)
            },300); // <-- dblclick tolerance here
        }
        return false;
}

function change_order_elements_click(el, elements_type) {
    table_id = Dom.get('orders_table_id').value;

    if (elements_type == 'dispatch') ids = ['elements_order_dispatch_Cancelled', 'elements_order_dispatch_Suspended', 'elements_order_dispatch_Dispatched', 'elements_order_dispatch_Warehouse', 'elements_order_dispatch_InProcess', 'elements_order_dispatch_InProcessCustomer'];
    else if (elements_type == 'source') ids = ['elements_order_source_Other', 'elements_order_source_Internet', 'elements_order_source_Call', 'elements_order_source_Store', 'elements_order_source_Email', 'elements_order_source_Fax']
    else if (elements_type == 'payment') ids = ['elements_order_payment_PartiallyPaid', 'elements_order_payment_WaitingPayment', 'elements_order_payment_Unknown', 'elements_order_payment_Paid', 'elements_order_payment_NA']
    else if (elements_type == 'type') ids = ['elements_order_type_Other', 'elements_order_type_Donation', 'elements_order_type_Sample', 'elements_order_type_Order']

    if (Dom.hasClass(el, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(el, 'selected')

        }

    } else {
        Dom.addClass(el, 'selected')

    }

    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';
    for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' + ids[i] + '=1'
        } else {
            request = request + '&' + ids[i] + '=0'

        }
    }
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function change_order_elements_dblclick(el, elements_type) {
    table_id = Dom.get('orders_table_id').value;


    if (elements_type == 'dispatch') ids = ['elements_order_dispatch_Cancelled', 'elements_order_dispatch_Suspended', 'elements_order_dispatch_Dispatched', 'elements_order_dispatch_Warehouse', 'elements_order_dispatch_InProcess', 'elements_order_dispatch_InProcessCustomer'];
    else if (elements_type == 'source') ids = ['elements_order_source_Other', 'elements_order_source_Internet', 'elements_order_source_Call', 'elements_order_source_Store', 'elements_order_source_Email', 'elements_order_source_Fax']
    else if (elements_type == 'payment') ids = ['elements_order_payment_PartiallyPaid', 'elements_order_payment_WaitingPayment', 'elements_order_payment_Unknown', 'elements_order_payment_Paid', 'elements_order_payment_NA']
    else if (elements_type == 'type') ids = ['elements_order_type_Other', 'elements_order_type_Donation', 'elements_order_type_Sample', 'elements_order_type_Order']


  Dom.removeClass(ids, 'selected')
    Dom.addClass(el, 'selected')


  var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];

    var request = '';
    for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' + ids[i] + '=1'
        } else {
            request = request + '&' + ids[i] + '=0'

        }
    }
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function change_orders_element_chooser(elements_type) {

    Dom.setStyle(['order_dispatch_chooser', 'order_type_chooser', 'order_source_chooser', 'order_payment_chooser'], 'display', 'none')
    Dom.setStyle('order_' + elements_type + '_chooser', 'display', '')

    Dom.removeClass(['orders_element_chooser_dispatch', 'orders_element_chooser_type', 'orders_element_chooser_source', 'orders_element_payment_dispatch', ], 'selected')
    Dom.addClass('orders_element_chooser_' + elements_type, 'selected')
    dialog_change_orders_element_chooser.hide()

    var table = tables.table0;
    var datasource = tables.dataSource0;
    var request = '&elements_type=' + elements_type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}




function show_dialog_change_orders_element_chooser() {
    region1 = Dom.getRegion('order_element_chooser_menu_button');
    region2 = Dom.getRegion('dialog_change_orders_element_chooser');
    var pos = [region1.right - region2.width, region1.bottom + 3]
    Dom.setXY('dialog_change_orders_element_chooser', pos);
    dialog_change_orders_element_chooser.show()
}


function init_orders(){
 dialog_export['orders'] = new YAHOO.widget.Dialog("dialog_export_orders", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
   dialog_export['orders'].render();
    Event.addListener("export_orders", "click", show_export_dialog, 'orders');
    Event.addListener("export_csv_orders", "click", export_table, {
        output: 'csv',
        table: 'orders',
        parent: 'store',
        'parent_key': Dom.get('store_key').value
    });
    Event.addListener("export_xls_orders", "click", export_table, {
        output: 'xls',
        table: 'orders',
        parent: 'store',
        'parent_key': Dom.get('store_key').value
    });

    Event.addListener("export_result_download_link_orders", "click", download_export_file,'orders');


   Event.addListener(['elements_order_dispatch_Cancelled', 'elements_order_dispatch_Suspended', 'elements_order_dispatch_Dispatched', 'elements_order_dispatch_Warehouse', 'elements_order_dispatch_InProcess', 'elements_order_dispatch_InProcessCustomer'], "click", change_order_elements, 'dispatch');
    Event.addListener(['elements_order_source_Other', 'elements_order_source_Internet', 'elements_order_source_Call', 'elements_order_source_Store', 'elements_order_source_Email', 'elements_order_source_Fax'], "click", change_order_elements, 'source');
    Event.addListener(['elements_order_payment_PartiallyPaid', 'elements_order_payment_WaitingPayment', 'elements_order_payment_Unknown', 'elements_order_payment_Paid', 'elements_order_payment_NA'], "click", change_order_elements, 'payment');
    Event.addListener(['elements_order_type_Other', 'elements_order_type_Donation', 'elements_order_type_Sample', 'elements_order_type_Order'], "click", change_order_elements, 'type');

  dialog_change_orders_element_chooser = new YAHOO.widget.Dialog("dialog_change_orders_element_chooser", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_orders_element_chooser.render();
    Event.addListener("order_element_chooser_menu_button", "click", show_dialog_change_orders_element_chooser);




}

Event.onDOMReady(init_orders);


