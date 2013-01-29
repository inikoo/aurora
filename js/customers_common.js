function change_view_customers(e, table_id) {

    var tipo = this.id;


    if (tipo == 'customers_general') tipo = 'general';
    else if (tipo == 'customers_contact') tipo = 'contact';
    else if (tipo == 'customers_address') tipo = 'address';
    else if (tipo == 'customers_balance') tipo = 'balance';
    else if (tipo == 'customers_weblog') tipo = 'weblog';
    else if (tipo == 'customers_rank') tipo = 'rank';
    else if (tipo == 'customers_rank') tipo = 'rank';
    else if (tipo == 'customers_other_value') tipo = 'other_value';


    var table = tables['table' + table_id];




    Dom.removeClass(customer_views_ids, 'selected')
    Dom.addClass(this, 'selected')

    table.hideColumn('location');
    table.hideColumn('last_order');
    table.hideColumn('orders');
    table.hideColumn('other_value');

    table.hideColumn('email');
    table.hideColumn('telephone');
    table.hideColumn('contact_name');

    table.hideColumn('address');
    table.hideColumn('billing_address');
    table.hideColumn('delivery_address');
    table.hideColumn('contact_since');

    table.hideColumn('total_payments');
    table.hideColumn('net_balance');
    table.hideColumn('total_refunds');
    table.hideColumn('total_profit');

    table.hideColumn('balance');
    table.hideColumn('top_orders');
    table.hideColumn('top_invoices');
    table.hideColumn('top_balance');
    table.hideColumn('top_profits');
    table.hideColumn('activity');

    table.hideColumn('logins');
    table.hideColumn('failed_logins');
    table.hideColumn('requests');

    if (tipo == 'general') {
        table.showColumn('name');
        table.showColumn('location');
        table.showColumn('last_order');
        table.showColumn('orders');
        table.showColumn('activity');
        table.showColumn('contact_since');


    } else if (tipo == 'contact') {
        table.showColumn('name');
        table.showColumn('contact_name');
        table.showColumn('email');
        table.showColumn('telephone');

    } else if (tipo == 'address') {
        table.showColumn('address');
        table.showColumn('billing_address');
        table.showColumn('delivery_address');

    } else if (tipo == 'balance') {
        table.showColumn('name');
        table.showColumn('net_balance');
        table.showColumn('total_refunds');
        table.showColumn('total_payments');
        table.showColumn('total_profit');

        table.showColumn('balance');

    } else if (tipo == 'rank') {
        table.showColumn('name');
        table.showColumn('top_orders');
        table.showColumn('top_invoices');
        table.showColumn('top_balance');
        table.showColumn('top_profits');

    } else if (tipo == 'weblog') {
        table.showColumn('name');

        table.showColumn('logins');
        table.showColumn('failed_logins');
        table.showColumn('requests');

    } else if (tipo == 'other_value') {
        table.showColumn('name');
        table.showColumn('location');
        table.showColumn('other_value');

    }


    change_customers_view_save(tipo)
}


function change_customers_view_save(tipo) {
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=customers-table-view&value=' + escape(tipo), {});

}


function get_elememts_numbers() {

    var ar_file = 'ar_contacts.php';
    var request = 'tipo=get_contacts_elements_numbers&parent=' + Dom.get('parent').value + '&parent_key=' + Dom.get('parent_key').value
    //alert(request)
    //Dom.get(['elements_Error_number','elements_Excess_number','elements_Normal_number','elements_Low_number','elements_VeryLow_number','elements_OutofStock_number']).innerHTML='<img src="art/loading.gif" style="height:12.9px" />';
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

            //  alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                for (i in r.elements_numbers) {
                    //alert('elements_'+ i +'_number '+'  '+Dom.get('elements_'+ i +'_number')+'  '+r.elements_numbers[i])
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

function show_dialog_change_customers_element_chooser() {
    region1 = Dom.getRegion('customer_element_chooser_menu_button');
    region2 = Dom.getRegion('dialog_change_customers_element_chooser');
    var pos = [region1.right - region2.width, region1.bottom + 3]
    Dom.setXY('dialog_change_customers_element_chooser', pos);
    dialog_change_customers_element_chooser.show()
}

function change_customers_element_chooser(elements_type) {

    Dom.setStyle(['customer_activity_chooser', 'customer_level_type_chooser'], 'display', 'none')
    Dom.setStyle('customer_' + elements_type + '_chooser', 'display', '')

    Dom.removeClass(['customers_element_chooser_activity', 'customers_element_chooser_level_type'], 'selected')
    Dom.addClass('customers_element_chooser_' + elements_type, 'selected')
    dialog_change_customers_element_chooser.hide()


    var table = tables['table0'];
    var datasource = tables['dataSource0'];
    var request = '&elements_type=' + elements_type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function customers_myrenderEvent() {


    ostate = this.getState();
    paginator = ostate.pagination

    if (paginator.totalRecords <= paginator.rowsPerPage) {
        Dom.setStyle('paginator' + this.table_id, 'display', 'none')
    }

    get_elememts_numbers()

}

function change_elements_orders_type() {

    Dom.removeClass(['elements_orders_type_all_contacts', 'elements_orders_type_contacts_with_orders'], 'selected')
    Dom.addClass(this, 'selected')

    var table = tables['table0'];
    var datasource = tables['dataSource0'];

    var request = '&orders_type=' + this.getAttribute('table_type');

    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

}

function change_elements(e, type) {



    if (type == 'activity') ids = ['elements_Lost', 'elements_Losing', 'elements_Active']
    else if (type == 'level_type') ids = ['elements_Normal', 'elements_VIP', 'elements_Partner', 'elements_Staff']
    else return;


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

    table_id = 0;
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
function change_elements_dblclick(e, type) {



    if (type == 'activity') ids = ['elements_Lost', 'elements_Losing', 'elements_Active']
    else if (type == 'level_type') ids = ['elements_Normal', 'elements_VIP', 'elements_Partner', 'elements_Staff']
    else return;



Dom.removeClass(ids, 'selected')
Dom.addClass(this, 'selected')



    table_id = 0;
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

function customers_init() {

    ids = ['elements_orders_type_contacts_with_orders', 'elements_orders_type_all_contacts']
    Event.addListener(ids, "click", change_elements_orders_type);
	dialog_change_customers_element_chooser = new YAHOO.widget.Dialog("dialog_change_customers_element_chooser", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });

    dialog_change_customers_element_chooser.render();
    Event.addListener("customer_element_chooser_menu_button", "click", show_dialog_change_customers_element_chooser);
    ids = ['elements_Lost', 'elements_Losing', 'elements_Active']
    Event.addListener(ids, "click", change_elements, 'activity');
    Event.addListener(ids, "dblclick", change_elements_dblclick, 'activity');

    ids = ['elements_Normal', 'elements_VIP', 'elements_Partner', 'elements_Staff']
    Event.addListener(ids, "click", change_elements, 'level_type');
    Event.addListener(ids, "dblclick", change_elements_dblclick, 'level_type');


}

YAHOO.util.Event.onDOMReady(customers_init);
