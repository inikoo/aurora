function change_user_elements(e, data) {

    table_id = data.table_id
    tipo = data.tipo
    if (tipo == 'user_state') ids = ['users_staff_state_Inactive', 'users_staff_state_Active'];
    else if (tipo == 'staff_type') ids = ['elements_NotWorking', 'elements_Working'];
    else return

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
    //alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function get_user_staff_elements_numbers() {
    var ar_file = 'ar_users.php';
    var request = 'tipo=get_user_staff_elements_numbers'
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

function user_staff_init() {
    get_user_staff_elements_numbers()
    var ids = ['users_staff_state_Inactive', 'users_staff_state_Active'];
    YAHOO.util.Event.addListener(ids, "click", change_user_elements, {
        table_id: 0,
        tipo: 'user_state'
    });

    ids = ['elements_NotWorking', 'elements_Working'];
    YAHOO.util.Event.addListener(ids, "click", change_user_elements, {
        table_id: 0,
        tipo: 'staff_type'
    });

}
YAHOO.util.Event.onDOMReady(user_staff_init);
