function get_part_transaction_numbers(from, to) {


    var ar_file = 'ar_parts.php';
    var request = 'tipo=number_transactions_in_interval&parent=part&parent_key=' + Dom.get('part_sku').value + '&from=' + from + '&to=' + to;
 //   Dom.setStyle(['transactions_all_transactions_wait', 'transactions_in_transactions_wait', 'transactions_out_transactions_wait', 'transactions_audit_transactions_wait', 'transactions_oip_transactions_wait', 'transactions_move_transactions_wait'], 'display', '');
    Dom.get('transactions_type_elements_OIP_numbers').innerHTML = '<img src="art/loading.gif" style="height:11px">';
  Dom.get('transactions_type_elements_Out_numbers').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('transactions_type_elements_In_numbers').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('transactions_type_elements_Audit_numbers').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('transactions_type_elements_Move_numbers').innerHTML = '<img src="art/loading.gif" style="height:11px">';
 
  Dom.get('transactions_type_elements_NoDispatched_numbers').innerHTML = '<img src="art/loading.gif" style="height:11px">';

//alert(ar_file+'?'+request)


    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

              for (i in r.transactions) {
             
              Dom.get('transactions_type_elements_'+i+'_numbers').innerHTML=r.transactions[i]
              }
            }
        },
        failure: function(o) {
        },
        scope: this
    }, request

    );
}


var already_clicked_transactions_type_elements_click = false
function change_transactions_type_elements() {
el=this;
var elements_type='';
    if (already_clicked_transactions_type_elements_click) {
        already_clicked_transactions_type_elements_click = false; // reset
        clearTimeout(alreadyclickedTimeout); // prevent this from happening
        change_transactions_type_elements_dblclick(el, elements_type)
    } else {
        already_clicked_transactions_type_elements_click = true;
        alreadyclickedTimeout = setTimeout(function() {
            already_clicked_transactions_type_elements_click = false; // reset when it happens
            change_transactions_type_elements_click(el, elements_type)
        }, 300); // <-- dblclick tolerance here
    }
    return false;
}

function change_transactions_type_elements_click(el,elements_type) {

    var ids = Array("transactions_type_elements_OIP", "transactions_type_elements_In", "transactions_type_elements_Out", "transactions_type_elements_Audit", "transactions_type_elements_Move", "transactions_type_elements_NoDispatched");


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

    table_id = Dom.get('transactions_table_id').value;
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

 //  alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


}

function change_transactions_type_elements_dblclick(el,elements_type) {

    var ids = Array("transactions_type_elements_OIP", "transactions_type_elements_In", "transactions_type_elements_Out", "transactions_type_elements_Audit", "transactions_type_elements_Move", "transactions_type_elements_NoDispatched");


    
         Dom.removeClass(ids, 'selected')

     Dom.addClass(el, 'selected')

    table_id = Dom.get('transactions_table_id').value;
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


function init_part(){

 	get_part_transaction_numbers(Dom.get('from').value, Dom.get('to').value)
 var ids = Array("transactions_type_elements_OIP", "transactions_type_elements_In", "transactions_type_elements_Out", "transactions_type_elements_Audit", "transactions_type_elements_Move", "transactions_type_elements_NoDispatched");
    Event.addListener(ids, "click", change_transactions_type_elements);

}

 YAHOO.util.Event.onDOMReady(init_part);
