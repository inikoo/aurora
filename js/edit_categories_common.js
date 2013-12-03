var dialog_delete_category_from_list;
var category_show_name={'Yes':'Yes','No':'No'};




function show_history() {
    Dom.setStyle(['show_history', ''], 'display', 'none')
    Dom.setStyle(['hide_history', 'history_table'], 'display', '')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=customer_categories-show_history&value=1', {});

}

function hide_history() {
    Dom.setStyle(['show_history', ''], 'display', '')
    Dom.setStyle(['hide_history', 'history_table'], 'display', 'none')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=customer_categories-show_history&value=0', {});

}


function cancel_new_category() {
    Dom.get('new_category_code').value = '';
    Dom.setStyle('new_category_no_name_msg', 'display', 'none')

    dialog_new_category.hide();

}

function show_new_category_dialog() {

    Dom.setStyle('category_form_chooser', 'display', '')
    Dom.setStyle(['new_category_msg','custom_category_form', 'simple_category_form', 'new_category_save_buttons', 'new_category_save_buttons', 'new_category_show_options'], 'display', 'none')
    dialog_new_category.show();
    Dom.get("new_category_code").value = '';
    Dom.get("new_category_label").value = '';
    Dom.get("new_category_max_deep").value = 2;

}

function post_edit_in_table(r){

	update_category_history_elements();
	     var table = tables.table1;
    var datasource = tables.dataSource1;
    var request = '';
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function post_create_actions() {
update_category_history_elements()


    var table = tables.table0;
    var datasource = tables.dataSource0;
    var request = '';
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    
     var table = tables.table1;
    var datasource = tables.dataSource1;
    var request = '';
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function save_new_category() {

    var code = Dom.get("new_category_code").value;
    var label = Dom.get("new_category_label").value;

    var store_key = Dom.get("new_category_store_key").value;
    var warehouse_key = Dom.get("new_category_warehouse_key").value;

    var allow_other = Dom.get("new_category_allow_other").value;
    var multiplicity = Dom.get("new_category_multiplicity").value;
    var max_deep = Dom.get("new_category_max_deep").value;
    var show_registration = Dom.get('new_category_show_registration').value
    var show_profile = Dom.get('new_category_show_profile').value
    var show_ui = Dom.get('new_category_show_ui').value

    var subject = Dom.get("new_category_subject").value;

    if (code == '') {
        Dom.setStyle('new_category_no_code_msg', 'display', '')
        return;
    } else {
        Dom.setStyle('new_category_no_code_msg', 'display', 'none')

    }

    if (label == '') {
        Dom.setStyle('new_category_no_label_msg', 'display', '')
        return;
    } else {
        Dom.setStyle('new_category_no_label_msg', 'display', 'none')

    }

    if (!(!isNaN(parseInt(max_deep)) && (parseFloat(max_deep) == parseInt(max_deep))) || max_deep < 2) {
        Dom.setStyle('new_category_wrong_max_deep_msg', 'display', '')
        return;
    } else {
        Dom.setStyle('new_category_wrong_max_deep_msg', 'display', 'none')

    }


    var ar_file = 'ar_edit_categories.php';
    var request = 'tipo=new_main_category&subject=' + subject + '&code=' + code + '&label=' + label + '&store_key=' + store_key + '&warehouse_key=' + warehouse_key + '&allow_other=' + allow_other + '&multiplicity=' + multiplicity + '&max_deep=' + max_deep + '&show_registration=' + show_registration + '&show_profile=' + show_profile + '&show_ui=' + show_ui;


    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
//alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                update_branch_type_elements()
                post_create_actions()
                cancel_new_category()

            } else {
                Dom.setStyle('new_category_msg', 'display', '')

                Dom.get('new_category_msg_text').innerHTML = r.msg
            }

        },
        failure: function(o) {
            alert(o.statusText);

        },
        scope: this
    }, request

    );
}



YAHOO.util.Event.onContentReady("dialog_new_category", function() {
    dialog_new_category = new YAHOO.widget.Dialog("dialog_new_category", {
        context: ["new_category", "tr", "tl"],
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });

    dialog_new_category.render();

    Event.addListener("new_category", "click", show_new_category_dialog, true);
    Event.addListener("new_category_cancel", "click", cancel_new_category, true);
    Event.addListener("new_category_save", "click", save_new_category, true);





});

function set_allow_other(value) {
    Dom.removeClass(['set_allow_other_Yes', 'set_allow_other_No'], 'selected')
    Dom.addClass('set_allow_other_' + value, 'selected')
    Dom.get('new_category_allow_other').value = value
}

function set_multiplicity(value) {
    Dom.removeClass(['set_multiplicity_Yes', 'set_multiplicity_No'], 'selected')
    Dom.addClass('set_multiplicity_' + value, 'selected')
    Dom.get('new_category_multiplicity').value = value
}

function set_show_registration(value) {
    Dom.removeClass(['set_show_registration_Yes', 'set_show_registration_No'], 'selected')
    Dom.addClass('set_show_registration_' + value, 'selected')
    Dom.get('new_category_show_registration').value = value
}

function set_show_profile(value) {
    Dom.removeClass(['set_show_profile_Yes', 'set_show_profile_No'], 'selected')
    Dom.addClass('set_show_profile_' + value, 'selected')
    Dom.get('new_category_show_profile').value = value
}

function set_show_ui(value) {
    Dom.removeClass(['set_show_ui_Yes', 'set_show_ui_No'], 'selected')
    Dom.addClass('set_show_ui_' + value, 'selected')
    Dom.get('new_category_show_ui').value = value
}


function show_simple_category_form() {
    Dom.setStyle('category_form_chooser', 'display', 'none')
    Dom.setStyle(['simple_category_form', 'new_category_save_buttons', 'new_category_show_options'], 'display', '')

    Dom.get('new_category_code').focus();

}

function show_custom_category_form() {
    Dom.setStyle('category_form_chooser', 'display', 'none')
    Dom.setStyle(['custom_category_form', 'new_category_save_buttons', 'simple_category_form', 'new_category_show_options'], 'display', '')
    Dom.get('new_category_code').focus();

}






function change_category_elements(e, table_id) {
    ids = ['elements_Root', 'elements_Node', 'elements_Head'];
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

    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function change_history_elements(e, table_id) {
    ids = ['elements_Change', 'elements_Assign'];
    // alert("caca")
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

function show_cell_dialog(datatable, oArgs) {

    var target = oArgs.target;
    var column = datatable.getColumn(target);
    var record = datatable.getRecord(target);

    var recordIndex = datatable.getRecordIndex(record);

    switch (column.object) {
    case 'delete_category':

 Dom.setStyle('deleting_from_list', 'display', 'none');
    Dom.setStyle('delete_category_buttons_from_list', 'display', '');



        // Dom.get('objective_time_limit').value = record.getData('temporal_formated_metadata');
        Dom.get('delete_from_list_category_key').value = record.getData('id');
        Dom.get('delete_from_list_category_code').innerHTML = record.getData('code');


        region1 = Dom.getRegion(target);
        region2 = Dom.getRegion('dialog_delete_category_from_list');
        var pos = [region1.right - region2.width, region1.top]
        Dom.setXY('dialog_delete_category_from_list', pos);





        dialog_delete_category_from_list.show();

        break;


    }

}

function save_delete_category_from_list() {
    var request = 'ar_edit_categories.php?tipo=delete_category&category_key=' + Dom.get('delete_from_list_category_key').value
    Dom.setStyle('deleting_from_list', 'display', '');
    Dom.setStyle('delete_category_buttons_from_list', 'display', 'none');

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
		//alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                var table = tables.table0;
                var datasource = tables.dataSource0;
                var request = '';
                datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
                update_branch_type_elements()
                
                
                update_category_history_elements()
  
 
    
     var table = tables.table1;
    var datasource = tables.dataSource1;
    var request = '';
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
                

                dialog_delete_category_from_list.hide()

            } else {
                Dom.setStyle('deleting_from_list', 'display', 'none');
                Dom.setStyle('delete_category_buttons_from_list', 'display', '');
                Dom.get('delete_category_msg_from_list').innerHTML = r.msg
            }
        }
    });
}

function cancel_delete_category_from_list() {
    dialog_delete_category_from_list.hide()

}



function init_edit_categories() {

    ids = ['elements_Node', 'elements_Root', 'elements_Head'];
    Event.addListener(ids, "click", change_category_elements, 0);
    
     ids = ['elements_Changes', 'elements_Assign'];
    Event.addListener(ids, "click", change_history_elements, 1);

 dialog_delete_category_from_list = new YAHOO.widget.Dialog("dialog_delete_category_from_list", {
       
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });

    dialog_delete_category_from_list.render();
    
    
        var oACDS0 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS0.queryMatchContains = true;
     oACDS0.table_id = 0;
     var oAutoComp0 = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS0);
     oAutoComp0.minQueryLength = 0;
    var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS1.queryMatchContains = true;
     oACDS1.table_id = 1;
     var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS1);
     oAutoComp1.minQueryLength = 0;




     Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
     Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);
     Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
     Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    


}

YAHOO.util.Event.onDOMReady(init_edit_categories);
YAHOO.util.Event.onContentReady("filtermenu0", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
        trigger: "filter_name0"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});


YAHOO.util.Event.onContentReady("rppmenu0", function() {
    rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {
        trigger: "rtext_rpp0"
    });
    rppmenu.render();
    rppmenu.subscribe("show", rppmenu.focus);
});


YAHOO.util.Event.onContentReady("filtermenu1", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
        trigger: "filter_name1"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});


YAHOO.util.Event.onContentReady("rppmenu1", function() {
    rppmenu = new YAHOO.widget.ContextMenu("rppmenu1", {
        trigger: "rtext_rpp1"
    });
    rppmenu.render();
    rppmenu.subscribe("show", rppmenu.focus);
});


