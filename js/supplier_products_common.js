function change_supplier_products_view(e, data) {

    tipo = this.id;
    if (tipo == 'supplier_products_general') tipo = 'general';
    else if (tipo == 'supplier_products_sales') tipo = 'sales';
    else if (tipo == 'supplier_products_stock') tipo = 'stock';
    else if (tipo == 'supplier_products_profit') tipo = 'profit';



    var table = tables['table' + data.table_id];









    table.hideColumn('description');
    table.hideColumn('used_in');
    table.hideColumn('stock');
    table.hideColumn('weeks_until_out_of_stock');
    table.hideColumn('required');
    table.hideColumn('dispatched');
    table.hideColumn('sold');
    table.hideColumn('sales');
    table.hideColumn('profit');
    table.hideColumn('margin');





    if (tipo == 'sales') {
        table.showColumn('required');
        table.showColumn('provided');
        table.showColumn('dispatched');
        table.showColumn('sold');
        table.showColumn('sales');
        table.showColumn('used_in');

        Dom.get('supplier_products_period_options').style.display = '';
        // Dom.get('supplier_products_avg_options').style.display='';
    } else if (tipo == 'general') {

        Dom.get('supplier_products_period_options').style.display = 'none';
        // Dom.get('supplier_products_avg_options').style.display='none';
        table.showColumn('description');
        table.showColumn('used_in');

    } else if (tipo == 'stock') {

        table.showColumn('stock');
        table.showColumn('weeks_until_out_of_stock');
        table.showColumn('used_in');
        Dom.get('supplier_products_period_options').style.display = 'none';
        // Dom.get('supplier_products_avg_options').style.display='none';
    } else if (tipo == 'profit') {
        table.showColumn('margin');

        table.showColumn('profit');
        table.showColumn('used_in');

        Dom.get('supplier_products_period_options').style.display = '';
        // Dom.get('supplier_products_avg_options').style.display='';
    }

    Dom.removeClass(Dom.getElementsByClassName('table_option', 'button', this.parentNode), 'selected')
    Dom.addClass(this, "selected");
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=' + data.parent + '-supplier_products-view&value=' + escape(tipo), {});


}

function get_supplier_products_numbers() {
    var ar_file = 'ar_suppliers.php';
    var request = 'tipo=get_supplier_products_numbers&parent=' + Dom.get('subject').value + '&parent_key=' + Dom.get('subject_key').value;
//alert(ar_file+'?'+request)
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                for (i in r.elements_numbers) {
                    for (j in r.elements_numbers[i]) {
                        Dom.get('elements_sp_' + i + '_' + j + '_number').innerHTML = r.elements_numbers[i][j]
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


var already_clicked_elements_sp_state_click = false

function change_elements_sp_state() {

    el = this;
    var elements_sp_state_type = '';
    if (already_clicked_elements_sp_state_click) {
        already_clicked_elements_sp_state_click = false; // reset
        clearTimeout(alreadyclickedTimeout); // prevent this from happening
        change_elements_sp_state_dblclick(el, elements_sp_state_type)
    } else {
        already_clicked_elements_sp_state_click = true;
        alreadyclickedTimeout = setTimeout(function() {
            already_clicked_elements_sp_state_click = false; // reset when it happens
            change_elements_sp_state_click(el, elements_sp_state_type)
        }, 300); // <-- dblclick tolerance here
    }
    return false;
}

function change_elements_sp_state_click(el, elements_sp_state_type) {

    ids = ['elements_sp_state_Available', 'elements_sp_state_NoAvailable', 'elements_sp_state_Discontinued'];


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

    table_id = Dom.get('supplier_products_table_id').value;
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

    // alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


}

function change_elements_sp_state_dblclick(el, elements_sp_state_type) {

    ids = ['elements_sp_state_Available', 'elements_sp_state_NoAvailable', 'elements_sp_state_Discontinued'];



    Dom.removeClass(ids, 'selected')

    Dom.addClass(el, 'selected')

    table_id = Dom.get('supplier_products_table_id').value;
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

    // alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


}


function init_supplier_products() {
get_supplier_products_numbers()

    ids = ['elements_sp_state_Available', 'elements_sp_state_NoAvailable', 'elements_sp_state_Discontinued'];
    Event.addListener(ids, "click", change_elements_sp_state);

}

YAHOO.util.Event.onDOMReady(init_supplier_products);
