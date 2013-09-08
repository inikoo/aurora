var already_clicked_invoice_elements_click=false
function change_invoice_elements(e, elements_type) {
    var el = this
  
        if (already_clicked_invoice_elements_click)
        {
            already_clicked_invoice_elements_click=false; // reset
            clearTimeout(alreadyclickedTimeout); // prevent this from happening
            change_invoice_elements_dblclick(el, elements_type)
        }
        else
        {
            already_clicked_invoice_elements_click=true;
            alreadyclickedTimeout=setTimeout(function(){
                already_clicked_invoice_elements_click=false; // reset when it happens
                 change_invoice_elements_click(el, elements_type)
            },300); // <-- dblclick tolerance here
        }
        return false;
}


function change_invoice_elements_click(el, elements_type) {
    table_id = Dom.get('invoices_table_id').value;

    if (elements_type == 'payment') ids = ['elements_invoice_payment_Partiall', 'elements_invoice_payment_Yes', 'elements_invoice_payment_No']
    else if (elements_type == 'type') ids = ['elements_invoice_type_Invoice', 'elements_invoice_type_Refund']

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
function change_invoice_elements_dblclick(el, elements_type) {
    table_id = Dom.get('invoices_table_id').value;

    if (elements_type == 'payment') ids = ['elements_invoice_payment_Partially', 'elements_invoice_payment_Yes', 'elements_invoice_payment_No']
    else if (elements_type == 'type') ids = ['elements_invoice_type_Invoice', 'elements_invoice_type_Refund']

    
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

function change_invoices_element_chooser(elements_type) {

    Dom.setStyle(['invoice_type_chooser','invoice_payment_chooser'], 'display', 'none')
    Dom.setStyle('invoice_' + elements_type + '_chooser', 'display', '')

    Dom.removeClass(['invoices_element_chooser_type', 'invoices_element_payment_dispatch', ], 'selected')
    Dom.addClass('invoices_element_chooser_' + elements_type, 'selected')
    dialog_change_invoices_element_chooser.hide()

    var table = tables.table1;
    var datasource = tables.dataSource1;
    var request = '&elements_type=' + elements_type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}



function show_dialog_change_invoices_element_chooser() {
    region1 = Dom.getRegion('invoice_element_chooser_menu_button');
    region2 = Dom.getRegion('dialog_change_invoices_element_chooser');
    var pos = [region1.right - region2.width, region1.bottom + 3]
    Dom.setXY('dialog_change_invoices_element_chooser', pos);
    dialog_change_invoices_element_chooser.show()
}


function init_invoices(){
 
  dialog_export['invoices'] = new YAHOO.widget.Dialog("dialog_export_invoices", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_export['invoices'].render();


    Event.addListener("export_invoices", "click", show_export_dialog, 'invoices');
    Event.addListener("export_csv_invoices", "click", export_table, {
        output: 'csv',
        table: 'invoices',
        parent: 'store',
        'parent_key': Dom.get('store_key').value
    });
    Event.addListener("export_xls_invoices", "click", export_table, {
        output: 'xls',
        table: 'invoices',
        parent: 'store',
        'parent_key': Dom.get('store_key').value
    });

    Event.addListener("export_result_download_link_invoices", "click", download_export_file,'invoices');


  Event.addListener(['elements_invoice_type_Invoice', 'elements_invoice_type_Refund'], "click", change_invoice_elements, 'type');
    Event.addListener(['elements_invoice_payment_Partially', 'elements_invoice_payment_Yes', 'elements_invoice_payment_No'], "click", change_invoice_elements, 'payment');
 
    dialog_change_invoices_element_chooser = new YAHOO.widget.Dialog("dialog_change_invoices_element_chooser", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_invoices_element_chooser.render();
    Event.addListener("invoice_element_chooser_menu_button", "click", show_dialog_change_invoices_element_chooser);




}

Event.onDOMReady(init_invoices);
