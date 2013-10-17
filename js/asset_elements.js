function show_dialog_change_products_element_chooser() {
    region1 = Dom.getRegion('product_element_chooser_menu_button');
    region2 = Dom.getRegion('dialog_change_products_element_chooser');
    var pos = [region1.right - region2.width, region1.bottom + 3]
    Dom.setXY('dialog_change_products_element_chooser', pos);
    dialog_change_products_element_chooser.show()
}

function change_products_elements_stock_aux(e, data) {

    ids = ['elements_stock_aux_InWeb', 'elements_stock_aux_ForSale', 'elements_stock_aux_All']
    Dom.removeClass(ids, 'selected')
    Dom.addClass(this, 'selected')

    var table = tables['table' + data.table_id];
    var datasource = tables['dataSource' + data.table_id];
    var request = '&elements_stock_aux=' + this.getAttribute('table_type');

    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function change_products_elements(e, data) {


    if (data.tipo == 'type') ids = ['elements_type_Historic', 'elements_type_Discontinued', 'elements_type_Private', 'elements_type_NoSale', 'elements_type_Sale'];
    else if (data.tipo == 'web') ids = ['elements_web_Offline', 'elements_web_Discontinued', 'elements_web_OutofStock', 'elements_web_Online'];
    else if (data.tipo == 'stock') ids = ['elements_stock_Error', 'elements_stock_Excess', 'elements_stock_Normal', 'elements_stock_Low', 'elements_stock_VeryLow', 'elements_stock_OutofStock'];



    if (data.click_type == 'click') {
        if (Dom.hasClass(this, 'selected')) {
            var number_selected_elements = 0;
            for (i in ids) {
                if (Dom.hasClass(ids[i], 'selected')) {
                    number_selected_elements++;
                }
            }
            if (number_selected_elements > 1) {
                Dom.removeClass(this, 'selected')
            }
        } else {
            Dom.addClass(this, 'selected')
        }
    } else {
        Dom.removeClass(ids, 'selected')
        Dom.addClass(this, 'selected')
    }

    //alert(data.table_id)
    var table = tables['table' + data.table_id];
    var datasource = tables['dataSource' + data.table_id];
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



function change_products_element_chooser(elements_type) {

    Dom.setStyle(['product_type_chooser', 'product_web_chooser', 'product_stock_chooser'], 'display', 'none')
    Dom.setStyle('product_' + elements_type + '_chooser', 'display', '')


    Dom.removeClass(['products_element_chooser_type', 'products_element_chooser_web', 'products_element_chooser_stock'], 'selected')
    Dom.addClass('products_element_chooser_' + elements_type, 'selected')
    dialog_change_products_element_chooser.hide()


    var table = tables['table' + Dom.get('products_table_id').value];
    var datasource = tables['dataSource' + Dom.get('products_table_id').value];

    var request = '&elements_type=' + elements_type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}


function get_products_elements_numbers() {
    var ar_file = 'ar_assets.php';
   
    var request = 'tipo=get_product_elements_numbers&parent=' + Dom.get('subject').value + '&parent_key=' + Dom.get('subject_key').value
  
    Dom.get(['elements_Error_number', 'elements_Excess_number', 'elements_Normal_number', 'elements_Low_number', 'elements_VeryLow_number', 'elements_OutofStock_number']).innerHTML = '<img src="art/loading.gif" style="height:12.9px" />';
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                for (i in r.elements_numbers) {
                    for (j in r.elements_numbers[i]) {
                        Dom.get('elements_' + i + '_' + j + '_number').innerHTML = r.elements_numbers[i][j]
                    }
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

function products_myrenderEvent() {

    ostate = this.getState();
    paginator = ostate.pagination

    if (paginator.totalRecords <= paginator.rowsPerPage) {
        Dom.setStyle('paginator' + this.table_id, 'display', 'none')
    }
    get_products_elements_numbers()

}


function init_asset_elements() {



    dialog_change_products_element_chooser = new YAHOO.widget.Dialog("dialog_change_products_element_chooser", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_products_element_chooser.render();
    Event.addListener("product_element_chooser_menu_button", "click", show_dialog_change_products_element_chooser);



    ids = ['elements_stock_Error', 'elements_stock_Excess', 'elements_stock_Normal', 'elements_stock_Low', 'elements_stock_VeryLow', 'elements_stock_OutofStock'];
    Event.addListener(ids, "click", change_products_elements, {
        table_id: Dom.get('products_table_id').value,
        tipo: 'stock',
        click_type: 'click'
    });

    ids = ['elements_web_Offline', 'elements_web_Discontinued', 'elements_web_OutofStock', 'elements_web_Online'];
    Event.addListener(ids, "click", change_products_elements, {
        table_id: Dom.get('products_table_id').value,
        tipo: 'web',
        click_type: 'click'
    });

    ids = ['elements_type_Historic', 'elements_type_Discontinued', 'elements_type_Private', 'elements_type_NoSale', 'elements_type_Sale'];
    Event.addListener(ids, "click", change_products_elements, {
        table_id: Dom.get('products_table_id').value,
        tipo: 'type',
        click_type: 'click'
    });

    ids = ['elements_stock_Error', 'elements_stock_Excess', 'elements_stock_Normal', 'elements_stock_Low', 'elements_stock_VeryLow', 'elements_stock_OutofStock'];
    Event.addListener(ids, "dblclick", change_products_elements, {
        table_id: Dom.get('products_table_id').value,
        tipo: 'stock',
        click_type: 'dblclick'

    });

    ids = ['elements_web_Offline', 'elements_web_Discontinued', 'elements_web_OutofStock', 'elements_web_Online'];
    Event.addListener(ids, "dblclick", change_products_elements, {
        table_id: Dom.get('products_table_id').value,
        tipo: 'web',
        click_type: 'dblclick'
    });

    ids = ['elements_type_Historic', 'elements_type_Discontinued', 'elements_type_Private', 'elements_type_NoSale', 'elements_type_Sale'];
    Event.addListener(ids, "dblclick", change_products_elements, {
        table_id: Dom.get('products_table_id').value,
        tipo: 'type',
        click_type: 'dblclick'
    });

    ids = ['elements_stock_aux_InWeb', 'elements_stock_aux_ForSale', 'elements_stock_aux_All']
    Event.addListener(ids, "click", change_products_elements_stock_aux, {
        table_id: Dom.get('products_table_id').value,
    });

    Event.addListener(ids, "dblclick", change_elements_dblclick, 'activity');

}

YAHOO.util.Event.onDOMReady(init_asset_elements);
