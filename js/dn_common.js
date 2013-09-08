var already_clicked_dn_elements_click=false
function change_dn_elements(e, elements_type) {
    var el = this
  
        if (already_clicked_dn_elements_click)
        {
            already_clicked_dn_elements_click=false; // reset
            clearTimeout(alreadyclickedTimeout); // prevent this from happening
            change_dn_elements_dblclick(el, elements_type)
        }
        else
        {
            already_clicked_dn_elements_click=true;
            alreadyclickedTimeout=setTimeout(function(){
                already_clicked_dn_elements_click=false; // reset when it happens
                 change_dn_elements_click(el, elements_type)
            },300); // <-- dblclick tolerance here
        }
        return false;
}

function change_dn_elements_click(el, elements_type) {
    table_id = Dom.get('dn_table_id').value;



    if (elements_type == 'dispatch') ids = ['elements_dn_dispatch_Ready', 'elements_dn_dispatch_Picking', 'elements_dn_dispatch_Packing', 'elements_dn_dispatch_Done', 'elements_dn_dispatch_Send', 'elements_dn_dispatch_Returned'];
       else if (elements_type == 'type') ids = ['elements_dn_type_Replacements', 'elements_dn_type_Donation', 'elements_dn_type_Sample', 'elements_dn_type_Order', 'elements_dn_type_Shortages']

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


function change_dn_elements_dblclick(el, elements_type) {
    table_id = Dom.get('dn_table_id').value;



    if (elements_type == 'dispatch') ids = ['elements_dn_dispatch_Ready', 'elements_dn_dispatch_Picking', 'elements_dn_dispatch_Packing', 'elements_dn_dispatch_Done', 'elements_dn_dispatch_Send', 'elements_dn_dispatch_Returned'];
       else if (elements_type == 'type') ids = ['elements_dn_type_Replacements', 'elements_dn_type_Donation', 'elements_dn_type_Sample', 'elements_dn_type_Order', 'elements_dn_type_Shortages']

  
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

function show_dialog_change_dns_element_chooser() {
    region1 = Dom.getRegion('dn_element_chooser_menu_button');
    region2 = Dom.getRegion('dialog_change_dns_element_chooser');
    var pos = [region1.right - region2.width, region1.bottom + 3]
    Dom.setXY('dialog_change_dns_element_chooser', pos);
    dialog_change_dns_element_chooser.show()
}


function change_dns_element_chooser(elements_type) {

    Dom.setStyle(['dn_dispatch_chooser', 'dn_type_chooser'], 'display', 'none')
    Dom.setStyle('dn_' + elements_type + '_chooser', 'display', '')

    Dom.removeClass(['dns_element_chooser_dispatch', 'dns_element_chooser_type' ], 'selected')
    Dom.addClass('dns_element_chooser_' + elements_type, 'selected')
    dialog_change_dns_element_chooser.hide()

    var table = tables.table2;
    var datasource = tables.dataSource2;
    var request = '&elements_type=' + elements_type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}


function init_dn(){
  dialog_export['dn'] = new YAHOO.widget.Dialog("dialog_export_dn", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
   dialog_export['dn'].render();

    Event.addListener("export_dn", "click", show_export_dialog, 'dn');
    Event.addListener("export_csv_dn", "click", export_table, {
        output: 'csv',
        table: 'dn',
        parent: 'store',
        'parent_key': Dom.get('store_key').value
    });
    Event.addListener("export_xls_dn", "click", export_table, {
        output: 'xls',
        table: 'dn',
        parent: 'store',
        'parent_key': Dom.get('store_key').value
    });

    Event.addListener("export_result_download_link_dn", "click", download_export_file,'dn');
  
    Event.addListener(['elements_dn_dispatch_Ready', 'elements_dn_dispatch_Picking', 'elements_dn_dispatch_Packing', 'elements_dn_dispatch_Done', 'elements_dn_dispatch_Send', 'elements_dn_dispatch_Returned'], "click", change_dn_elements, 'dispatch');
    Event.addListener(['elements_dn_type_Replacements', 'elements_dn_type_Donation', 'elements_dn_type_Sample', 'elements_dn_type_Order', 'elements_dn_type_Shortages'], "click", change_dn_elements, 'type');


 dialog_change_dns_element_chooser = new YAHOO.widget.Dialog("dialog_change_dns_element_chooser", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_dns_element_chooser.render();
    Event.addListener("dn_element_chooser_menu_button", "click", show_dialog_change_dns_element_chooser);


}


Event.onDOMReady(init_dn);
