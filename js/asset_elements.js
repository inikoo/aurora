
function show_dialog_change_products_element_chooser() {
    region1 = Dom.getRegion('product_element_chooser_menu_button');
    region2 = Dom.getRegion('dialog_change_products_element_chooser');
    var pos = [region1.right - region2.width, region1.bottom + 3]
    Dom.setXY('dialog_change_products_element_chooser', pos);
    dialog_change_products_element_chooser.show()
}


function change_products_elements(e,data){

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


function get_product_elements_numbers() {
    var ar_file = 'ar_assets.php';
    var request = 'tipo=get_product_elements_numbers&parent=' + Dom.get('subject').value + '&parent_key=' + Dom.get('subject_key').value
    //alert(request)
    
    //Dom.get(['elements_Error_number','elements_Excess_number','elements_Normal_number','elements_Low_number','elements_VeryLow_number','elements_OutofStock_number']).innerHTML='<img src="art/loading.gif" style="height:12.9px" />';
    
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

          //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
   				for (i in r.elements_numbers) {
                    for (j in r.elements_numbers[i]) {
                       // alert(Dom.get('elements_' + i + '_' + j + '_number')+' '+'elements_'  i + '_' + j + '_number')
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


function init_asset_elements() {

get_product_elements_numbers()

    dialog_change_products_element_chooser = new YAHOO.widget.Dialog("dialog_change_products_element_chooser", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_products_element_chooser.render();
    Event.addListener("product_element_chooser_menu_button", "click", show_dialog_change_products_element_chooser);
    

  
     ids = ['elements_stock_Error','elements_stock_Excess','elements_stock_Normal','elements_stock_Low','elements_stock_VeryLow','elements_stock_OutofStock'];
    Event.addListener(ids, "click", change_products_elements, {table_id:Dom.get('products_table_id').value,tipo:'stock'});
   

}

YAHOO.util.Event.onDOMReady(init_asset_elements);
