function change_block() {


    Dom.setStyle('order_details_panel', 'display', '')

    ids = ['details', 'notes', 'customer_data']
    block_ids = ['block_details', 'block_notes', 'block_customer_data'];

    Dom.setStyle(block_ids, 'display', 'none');

    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');

}



var already_clicked_customer_data_elements_click = false

function change_customer_data_elements() {
    el = this;
    var elements_type = '';
    if (already_clicked_customer_data_elements_click) {
        already_clicked_customer_data_elements_click = false; // reset
        clearTimeout(alreadyclickedTimeout); // prevent this from happening
        change_customer_data_elements_dblclick(el, elements_type)
    } else {
        already_clicked_customer_data_elements_click = true;
        alreadyclickedTimeout = setTimeout(function() {
            already_clicked_customer_data_elements_click = false; // reset when it happens
            change_customer_data_elements_click(el, elements_type)
        }, 300); // <-- dblclick tolerance here
    }
    return false;
}

function change_customer_data_elements_click(el, elements_type) {

    ids = ['elements_customer_data_changes', 'elements_customer_data_orders', 'elements_customer_data_notes', 'elements_customer_data_attachments', 'elements_customer_data_emails', 'elements_customer_data_weblog'];


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

    table_id = 2;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';
    for (i in ids) {
        tmp=ids[i].replace("customer_data_", ""); 
        
    
        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' +tmp + '=1'
        } else {
            request = request + '&' + tmp + '=0'

        }
    }

    
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


}

function change_customer_data_elements_dblclick(el, elements_type) {

    ids = ['elements_customer_data_changes', 'elements_customer_data_orders', 'elements_customer_data_notes', 'elements_customer_data_attachments', 'elements_customer_data_emails', 'elements_customer_data_weblog'];



    Dom.removeClass(ids, 'selected')

    Dom.addClass(el, 'selected')

    table_id = 2;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';
    for (i in ids) {
        tmp=ids[i].replace("customer_data_", ""); 
        
    
        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' +tmp + '=1'
        } else {
            request = request + '&' + tmp + '=0'

        }
    }

    // alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


}




function get_customer_data_history_numbers() {



    var ar_file = 'ar_contacts.php';
    var request = 'tipo=get_history_numbers&subject=customer&subject_key=' + Dom.get('customer_key').value;


    Dom.get('elements_customer_data_history_Changes_number').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('elements_customer_data_history_Orders_number').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('elements_customer_data_history_Notes_number').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('elements_customer_data_history_Attachments_number').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('elements_customer_data_history_Emails_number').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('elements_customer_data_history_WebLog_number').innerHTML = '<img src="art/loading.gif" style="height:11px">';



    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {

                for (i in r.elements_numbers) {
                    if (Dom.get('elements_customer_data_history_' + i + '_number') != undefined) Dom.get('elements_customer_data_history_' + i + '_number').innerHTML = r.elements_numbers[i]
                }
            }
        },
        failure: function(o) {},
        scope: this
    }, request

    );

}

var already_clicked_elements_click = false

function change_history_elements() {

    el = this;
    var elements_type = '';
    if (already_clicked_elements_click) {
        already_clicked_elements_click = false; // reset
        clearTimeout(alreadyclickedTimeout); // prevent this from happening
        change_elements_dblclick(el, elements_type)
    } else {
        already_clicked_elements_click = true;
        alreadyclickedTimeout = setTimeout(function() {
            already_clicked_elements_click = false; // reset when it happens
            change_elements_click(el, elements_type)
        }, 300); // <-- dblclick tolerance here
    }
    return false;
}

function change_elements_click(el, elements_type) {

    ids = ['elements_order_history_changes', 'elements_order_history_notes', 'elements_order_history_attachments'];


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

    table_id = 3;
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

function change_elements_dblclick(el, elements_type) {

    ids = ['elements_order_history_changes', 'elements_order_history_notes', 'elements_order_history_attachments'];



    Dom.removeClass(ids, 'selected')

    Dom.addClass(el, 'selected')

    table_id = 3;
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





function get_history_numbers() {



    var ar_file = 'ar_orders.php';
    var request = 'tipo=get_history_numbers&subject=order&subject_key=' + Dom.get('order_key').value;

    //   alert(ar_file+'?'+request)
    Dom.get('elements_history_Changes_number').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('elements_history_Notes_number').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('elements_history_Attachments_number').innerHTML = '<img src="art/loading.gif" style="height:11px">';


    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {

                for (i in r.elements_numbers) {
                    if (Dom.get('elements_history_' + i + '_number') != undefined) Dom.get('elements_history_' + i + '_number').innerHTML = r.elements_numbers[i]
                }
            }
        },
        failure: function(o) {},
        scope: this
    }, request

    );

}

function hide_order_details() {
    ids = ['details', 'notes', 'customer_data']

    Dom.removeClass(ids, 'selected');

    Dom.setStyle('order_details_panel', 'display', 'none')
}

function init_common() {

    get_customer_data_history_numbers()
    

    Event.addListener(['details', 'notes', 'customer_data'], "click", change_block);
    Event.addListener(['elements_customer_data_changes', 'elements_customer_data_orders', 'elements_customer_data_notes', 'elements_customer_data_attachments', 'elements_customer_data_emails', 'elements_customer_data_weblog'], "click", change_customer_data_elements);
    Event.addListener(['elements_order_history_changes', 'elements_order_history_notes', 'elements_order_history_attachments'], "click", change_history_elements);


    Event.addListener("hide_order_details", "click", hide_order_details);
}

YAHOO.util.Event.onDOMReady(init_common)
