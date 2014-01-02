var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;
var select_category_head_from_list_action
var dialog_category_heads_list;
var dialog_delete_category;
var dialog_delete_category_from_list;

var number_checked_no_assigned_subjects = 0;
var checked_no_assigned_subjects = [];
var unchecked_no_assigned_subjects = [];
var no_assigned_subjects_check_start_type = 'unchecked';

var number_checked_assigned_subjects = 0;
var checked_assigned_subjects = [];
var unchecked_assigned_subjects = [];
var assigned_subjects_check_start_type = 'unchecked';


var dialog_edit_subjects;


function check_all_no_assigned_subject() {
  
   number_checked_no_assigned_subjects = 0;
    checked_no_assigned_subjects = [];
    unchecked_no_assigned_subjects = [];
    no_assigned_subjects_check_start_type = 'checked';
    Dom.setStyle(['uncheck_all_no_assigned_subjects', 'checked_no_assigned_subjects_dialog', 'checked_no_assigned_subjects_assign_to_category_button'], 'display', '')
    var table = tables.table3;
    var datasource = tables.dataSource3;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function set_checked_all_numbers_no_assigned_subject() {

    if (no_assigned_subjects_check_start_type == 'checked') {
        number_checked_no_assigned_subjects = this.get('paginator').getTotalRecords()
        Dom.get('number_checked_no_assigned_subjects').innerHTML = number_checked_no_assigned_subjects;
    }
}

function uncheck_all_no_assigned_subject() {
    number_checked_no_assigned_subjects = 0;
    checked_no_assigned_subjects = [];
    unchecked_no_assigned_subjects = [];
    no_assigned_subjects_check_start_type = 'unchecked';
    Dom.setStyle(['uncheck_all_no_assigned_subjects', 'checked_no_assigned_subjects_dialog', 'checked_no_assigned_subjects_assign_to_category_button'], 'display', 'none')
    var request = '&checked_all=0';
    var table = tables.table3;
    var datasource = tables.dataSource3;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function check_no_assigned_subject(id) {
    checkbox = Dom.get('no_assigned_subject_' + id);
    if (checkbox.getAttribute('checked') == 1) {
        checkbox.src = "art/icons/checkbox_unchecked.png";
        checkbox.setAttribute('checked', 0)
        number_checked_no_assigned_subjects--;

        if (no_assigned_subjects_check_start_type == 'unchecked') {
            var index = checked_no_assigned_subjects.indexOf(id);
            checked_no_assigned_subjects.splice(index, 1);
        } else {
            unchecked_no_assigned_subjects.push(id.toString())
        }
    } else {
        checkbox.src = "art/icons/checkbox_checked.png";
        checkbox.setAttribute('checked', 1)
        number_checked_no_assigned_subjects++;

        if (no_assigned_subjects_check_start_type == 'unchecked') {
            checked_no_assigned_subjects.push(id.toString())
        } else {
            var index = unchecked_no_assigned_subjects.indexOf(id);
            unchecked_no_assigned_subjects.splice(index, 1);
        }
    }

    Dom.get('number_checked_no_assigned_subjects').innerHTML = number_checked_no_assigned_subjects;
    if (number_checked_no_assigned_subjects == 0) {
        Dom.setStyle(['uncheck_all_no_assigned_subjects', 'checked_no_assigned_subjects_dialog', 'checked_no_assigned_subjects_assign_to_category_button'], 'display', 'none')
    } else {
        Dom.setStyle(['uncheck_all_no_assigned_subjects', 'checked_no_assigned_subjects_dialog', 'checked_no_assigned_subjects_assign_to_category_button'], 'display', '')
    }
}

function assign_to_category_checked_no_assigned_subject() {


    if (Dom.get('branch_type').value == 'Head') {
        assign_to_category_checked_no_assigned_subject_from_list(Dom.get('category_key').value)
        return;
    }

    select_category_head_from_list_action = 'associate_multiple_subject_to_category';

    region1 = Dom.getRegion('checked_no_assigned_subjects_assign_to_category_button');
    region2 = Dom.getRegion('dialog_category_heads_list');
    var pos = [region1.right - (region2.width / 2), region1.bottom]
    Dom.setXY('dialog_category_heads_list', pos);
    dialog_category_heads_list.show()

}

function assign_to_category_checked_no_assigned_subject_from_list(category_key) {
    if (no_assigned_subjects_check_start_type == 'checked') {
        subject_source_checked_subjects = unchecked_no_assigned_subjects
    } else {
        subject_source_checked_subjects = checked_no_assigned_subjects
    }

    request = 'ar_edit_categories.php?tipo=associate_multiple_subject_to_category&category_key=' + category_key + '&subject_source_checked_type=' + no_assigned_subjects_check_start_type + '&subject_source_checked_subjects=' + subject_source_checked_subjects + '&callback_category_key=' + Dom.get('category_key').value + '&subject_source=0'

    Dom.setStyle('wait_checked_no_assigned_subjects_assign_to_category', 'display', '')
    Dom.setStyle(['check_all_no_assigned_subjects', 'uncheck_all_no_assigned_subjects', 'checked_no_assigned_subjects_dialog', 'checked_no_assigned_subjects_assign_to_category_button'], 'display', 'none')
    number_checked_no_assigned_subjects = 0;
    checked_no_assigned_subjects = [];
    unchecked_no_assigned_subjects = [];
    no_assigned_subjects_check_start_type = 'unchecked';

    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                Dom.setStyle('wait_checked_no_assigned_subjects_assign_to_category', 'display', 'none')
                Dom.setStyle('check_all_no_assigned_subjects', 'display', '')


                var table = tables.table3;
                var datasource = tables.dataSource3;
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                var table = tables.table2;
                var datasource = tables.dataSource2;
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                var table = tables.table1;
                var datasource = tables.dataSource1;
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                update_category_history_elements()
                dialog_category_heads_list.hide()
                Dom.get('number_category_subjects_not_assigned').innerHTML = r.number_category_subjects_not_assigned
                Dom.get('number_category_subjects_assigned').innerHTML = r.number_category_subjects_assigned
            } else {
                alert(r.msg);
            }
        },
        failure: function(fail) {
            alert(fail.statusText);
        },
        scope: this
    });


}

function update_category_history_elements() {}

function check_all_assigned_subject() {
    number_checked_assigned_subjects = 0;
    checked_assigned_subjects = [];
    unchecked_assigned_subjects = [];
    assigned_subjects_check_start_type = 'checked';
    Dom.setStyle(['uncheck_all_assigned_subjects', 'checked_assigned_subjects_dialog', 'edit_subjects_buttons'], 'display', '')
    //post_check_change_subject()
    var table = tables.table2;
    var datasource = tables.dataSource2;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
   
}


function set_checked_all_numbers_assigned_subject() {

    if (assigned_subjects_check_start_type == 'checked') {
        number_checked_assigned_subjects = this.get('paginator').getTotalRecords()
        Dom.get('number_checked_assigned_subjects').innerHTML = number_checked_assigned_subjects;
    }
}

function uncheck_all_assigned_subject() {
    number_checked_assigned_subjects = 0;
    checked_assigned_subjects = [];
    unchecked_assigned_subjects = [];
    assigned_subjects_check_start_type = 'unchecked';
    Dom.setStyle(['uncheck_all_assigned_subjects', 'checked_assigned_subjects_dialog', 'edit_subjects_buttons'], 'display', 'none')
    post_check_change_subject()
    
    var request = '&checked_all=0';
    var table = tables.table2;
    var datasource = tables.dataSource2;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}




function check_assigned_subject(id) {
    var checkbox = Dom.get('assigned_subject_' + id);
    if (checkbox.getAttribute('checked') == 1) {
        checkbox.src = "art/icons/checkbox_unchecked.png";
        checkbox.setAttribute('checked', 0)
        number_checked_assigned_subjects--;

        if (assigned_subjects_check_start_type == 'unchecked') {
            var index = checked_assigned_subjects.indexOf(id);
            checked_assigned_subjects.splice(index, 1);
        } else {
            unchecked_assigned_subjects.push(id.toString())
        }
    } else {
        checkbox.src = "art/icons/checkbox_checked.png";
        checkbox.setAttribute('checked', 1)
        number_checked_assigned_subjects++;

        if (assigned_subjects_check_start_type == 'unchecked') {
            checked_assigned_subjects.push(id.toString())
        } else {
            var index = unchecked_assigned_subjects.indexOf(id);
            unchecked_assigned_subjects.splice(index, 1);
        }
    }

    Dom.get('number_checked_assigned_subjects').innerHTML = number_checked_assigned_subjects;
    if (number_checked_assigned_subjects == 0) {
        Dom.setStyle(['uncheck_all_assigned_subjects', 'checked_assigned_subjects_dialog', 'edit_subjects_buttons'], 'display', 'none')
    		
    } else {
        Dom.setStyle(['uncheck_all_assigned_subjects', 'checked_assigned_subjects_dialog', 'edit_subjects_buttons'], 'display', '')
    	
    }
}

function assign_to_category_checked_assigned_subject() {
    select_category_head_from_list_action = 'move_multiple_subject_to_category';

    region1 = Dom.getRegion('checked_assigned_subjects_assign_to_category_button');
    region2 = Dom.getRegion('dialog_category_heads_list');
    var pos = [region1.right - (region2.width / 2), region1.bottom]
    Dom.setXY('dialog_category_heads_list', pos);
    dialog_category_heads_list.show()

}

function assign_to_category_checked_assigned_subject_from_list(category_key) {
    if (assigned_subjects_check_start_type == 'checked') {
        subject_source_checked_subjects = unchecked_assigned_subjects
    } else {
        subject_source_checked_subjects = checked_assigned_subjects
    }

    request = 'ar_edit_categories.php?tipo=associate_multiple_subject_to_category&category_key=' + category_key + '&subject_source_checked_type=' + assigned_subjects_check_start_type + '&subject_source_checked_subjects=' + subject_source_checked_subjects + '&callback_category_key=' + Dom.get('category_key').value + '&subject_source=' + Dom.get('category_key').value


    Dom.setStyle('wait_checked_assigned_subjects_assign_to_category', 'display', '')
    Dom.setStyle(['check_all_assigned_subjects', 'uncheck_all_assigned_subjects', 'checked_assigned_subjects_dialog', 'edit_subjects_buttons'], 'display', 'none')
    number_checked_assigned_subjects = 0;
    checked_assigned_subjects = [];
    unchecked_assigned_subjects = [];
    assigned_subjects_check_start_type = 'unchecked';

    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                Dom.setStyle('wait_checked_assigned_subjects_assign_to_category', 'display', 'none')
                Dom.setStyle('check_all_assigned_subjects', 'display', '')


                var table = tables.table2;
                var datasource = tables.dataSource2;
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                var table = tables.table1;
                var datasource = tables.dataSource1;
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                update_category_history_elements()
                dialog_category_heads_list.hide()
                Dom.get('number_category_subjects_not_assigned').innerHTML = r.number_category_subjects_not_assigned
                Dom.get('number_category_subjects_assigned').innerHTML = r.number_category_subjects_assigned
            } else {
                alert(r.msg);
            }
        },
        failure: function(fail) {
            alert(fail.statusText);
        },
        scope: this
    });


}





function select_category_head_from_list(oArgs) {

    category_key = tables.table5.getRecord(oArgs.target).getData('key')

    if (select_category_head_from_list_action == 'associate_multiple_subject_to_category') {
        assign_to_category_checked_no_assigned_subject_from_list(category_key)
        return;
    } else if (select_category_head_from_list_action == 'move_multiple_subject_to_category') {

        assign_to_category_checked_assigned_subject_from_list(category_key)
        return;
    }

    request = 'ar_edit_categories.php?tipo=' + select_category_head_from_list_action + '&category_key=' + category_key + '&subject_key=' + select_category_head_from_list_subject_key + '&callback_category_key=' + Dom.get('category_key').value

    Dom.setStyle('wait_checked_no_assigned_subjects_assign_to_category', 'display', '')
    Dom.setStyle(['check_all_no_assigned_subjects', 'uncheck_all_no_assigned_subjects', 'checked_no_assigned_subjects_dialog', 'checked_no_assigned_subjects_assign_to_category_button'], 'display', 'none')
    number_checked_no_assigned_subjects = 0;
    checked_no_assigned_subjects = [];
    unchecked_no_assigned_subjects = [];
    no_assigned_subjects_check_start_type = 'unchecked';



    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.setStyle('wait_checked_no_assigned_subjects_assign_to_category', 'display', 'none')
                Dom.setStyle('check_all_no_assigned_subjects', 'display', '')


                var table = tables.table3;
                var datasource = tables.dataSource3;
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                var table = tables.table2;
                var datasource = tables.dataSource2;
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                var table = tables.table1;
                var datasource = tables.dataSource1;
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                update_category_history_elements()
                dialog_category_heads_list.hide()
                Dom.get('number_category_subjects_not_assigned').innerHTML = r.number_category_subjects_not_assigned
                Dom.get('number_category_subjects_assigned').innerHTML = r.number_category_subjects_assigned
            } else {
                alert(r.msg);
            }
        },
        failure: function(fail) {
            alert(fail.statusText);
        },
        scope: this
    });


}


function remove_from_category_checked_assigned_subject() {

    if (assigned_subjects_check_start_type == 'checked') {
        subject_source_checked_subjects = unchecked_assigned_subjects
    } else {
        subject_source_checked_subjects = checked_assigned_subjects
    }

    request = 'ar_edit_categories.php?tipo=disassociate_multiple_subject_from_category&category_key=' + Dom.get('category_key').value + '&subject_source_checked_type=' + assigned_subjects_check_start_type + '&subject_source_checked_subjects=' + subject_source_checked_subjects + '&callback_category_key=' + Dom.get('category_key').value


    Dom.setStyle('wait_checked_assigned_subjects_assign_to_category', 'display', '')
    Dom.setStyle(['check_all_assigned_subjects', 'uncheck_all_assigned_subjects', 'checked_assigned_subjects_dialog', 'edit_subjects_buttons'], 'display', 'none')
    number_checked_assigned_subjects = 0;
    checked_assigned_subjects = [];
    unchecked_assigned_subjects = [];
    assigned_subjects_check_start_type = 'unchecked';





    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                Dom.setStyle('wait_checked_assigned_subjects_assign_to_category', 'display', 'none')
                Dom.setStyle('check_all_assigned_subjects', 'display', '')


                var table = tables.table2;
                var datasource = tables.dataSource2;
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                var table = tables.table3;
                var datasource = tables.dataSource3;
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                var table = tables.table1;
                var datasource = tables.dataSource1;
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                update_category_history_elements()

                Dom.get('number_category_subjects_not_assigned').innerHTML = r.number_category_subjects_not_assigned
                Dom.get('number_category_subjects_assigned').innerHTML = r.number_category_subjects_assigned
            } else {
                alert(r.msg);
            }
        },
        failure: function(fail) {
            alert(fail.statusText);
        },
        scope: this
    });

    table = tables.table2;
    for (var rs = table.getRecordSet(), l = rs.getLength(), i = 0; i < l; i++) {

        record = rs.getRecord(i);
        var checkbox = Dom.get('assigned_subject_' + record.getData("sku"));

        if (checkbox.getAttribute('checked') == 1) {
            checkbox.src = 'art/loading.gif'
        }

    }



}

function show_cell_dialog(datatable, oArgs) {

    var target = oArgs.target;
    var column = datatable.getColumn(target);
    var record = datatable.getRecord(target);
    var recordIndex = datatable.getRecordIndex(record);

    switch (column.object) {
    case 'delete_category':

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


function onCellClick(oArgs) {
    var target = oArgs.target,
        column = this.getColumn(target),
        record = this.getRecord(target);

    var recordIndex = this.getRecordIndex(record);
    //alert(column.object); return;
    switch (column.action) {
    case 'assign_here':



        request = 'ar_edit_categories.php?tipo=associate_subject_to_category&category_key=' + Dom.get('category_key').value + '&subject_key=' + record.getData('subject_key')


        Dom.setStyle('wait_checked_no_assigned_subjects_assign_to_category', 'display', '')
        Dom.setStyle(['check_all_no_assigned_subjects', 'uncheck_all_no_assigned_subjects', 'checked_no_assigned_subjects_dialog', 'checked_no_assigned_subjects_assign_to_category_button'], 'display', 'none')
        number_checked_no_assigned_subjects = 0;
        checked_no_assigned_subjects = [];
        unchecked_no_assigned_subjects = [];
        no_assigned_subjects_check_start_type = 'unchecked';


        YAHOO.util.Connect.asyncRequest('GET', request, {
            success: function(o) {
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {


                    Dom.setStyle('wait_checked_no_assigned_subjects_assign_to_category', 'display', 'none')
                    Dom.setStyle('check_all_no_assigned_subjects', 'display', '')




                    var table = tables.table3;
                    var datasource = tables.dataSource3;
                    datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                    var table = tables.table2;
                    var datasource = tables.dataSource2;
                    datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                    var table = tables.table1;
                    var datasource = tables.dataSource1;
                    datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                    update_category_history_elements()
                   // Dom.get('number_category_subjects_not_assigned').innerHTML = r.number_category_subjects_not_assigned
                    Dom.get('number_category_subjects_assigned').innerHTML = r.number_category_subjects_assigned
					dialog_no_asssigned_subjects.hide()
                } else {
                    alert(r.msg);
                }
            },
            failure: function(fail) {
                alert(fail.statusText);
            },
            scope: this
        });




        break;
    case 'assign':
        select_category_head_from_list_action = 'associate_subject_to_category';
        select_category_head_from_list_subject_key = record.getData('subject_key');
        region1 = Dom.getRegion(target);
        region2 = Dom.getRegion('dialog_category_heads_list');
        var pos = [region1.right - region2.width, region1.bottom]
        Dom.setXY('dialog_category_heads_list', pos);
        dialog_category_heads_list.show()
        break;
    case 'remove':

        var subject_key = record.getData('subject_key')

        request = 'ar_edit_categories.php?tipo=disassociate_subject&category_key=' + Dom.get('category_key').value + '&subject_key=' + subject_key

        var checkbox = Dom.get('assigned_subject_' + subject_key);
        if (checkbox.getAttribute('checked') == 1) {
            check_assigned_subject(subject_key)

        }

        YAHOO.util.Connect.asyncRequest('GET', request, {
            success: function(o) {
                // alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {
                    var table = tables.table2;
                    var datasource = tables.dataSource2;
                    datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                    var table = tables.table1;
                    var datasource = tables.dataSource1;
                    datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                    update_category_history_elements()
                    uncheck_all_no_assigned_subject()



                    Dom.get('number_category_subjects_not_assigned').innerHTML = r.number_category_subjects_not_assigned
                    Dom.get('number_category_subjects_assigned').innerHTML = r.number_category_subjects_assigned

                } else {
                    alert(r.msg);
                }
            },
            failure: function(fail) {
                alert(fail.statusText);
            },
            scope: this
        });

        break;
    case 'delete':
        if (record.getData('delete') != '') {

            var delete_type = record.getData('delete_type');
            if (delete_type == undefined) delete_type = 'delete';


            if (confirm('Are you sure, you want to ' + delete_type + ' this row?')) {


                ar_file = 'ar_edit_categories.php';



                //alert(ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record))
                YAHOO.util.Connect.asyncRequest('GET', ar_file + '?tipo=delete_' + column.object + myBuildUrl(this, record), {

                    success: function(o) {
                        //   alert(o.responseText);
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                        if (r.state == 200 && r.action == 'deleted') {

                            this.deleteRow(target);



                            var table = this;
                            var datasource = this.getDataSource();
                            datasource.sendRequest('', table.onDataReturnInitializeTable, table);

                            post_delete_actions(column.object);

                            //alert(datatable)
                        } else if (r.state == 200 && r.action == 'discontinued') {

                            var data = record.getData();
                            //data['delete']=r.delete;
                            data['delete_type'] = r.delete_type;
                            this.updateRow(recordIndex, data);
                        } else {
                            alert(r.msg);
                        }
                    },
                    failure: function(fail) {
                        alert(fail.statusText);
                    },
                    scope: this
                });
            }
        }
        break;
    case 'dialog':
    case 'dialog_delete':
        show_cell_dialog(this, oArgs);
        break;


    case 'edit':




        // alert(ar_file + '?tipo=edit_' + column.object + myBuildUrl(this, record))
        YAHOO.util.Connect.asyncRequest('GET', ar_file + '?tipo=edit_' + column.object + myBuildUrl(this, record), {
            success: function(o) {
                //alert(o.responseText);
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200 && r.action == 'edited') {
                    var table = this;
                    var datasource = this.getDataSource();
                    datasource.sendRequest('', table.onDataReturnInitializeTable, table);


                } else {
                    alert(r.msg);
                }
            },
            failure: function(fail) {
                alert(fail.statusText);
            },
            scope: this
        });


        break;



    default:

        this.onEventShowCellEditor(oArgs);
        break;
    }
};


function select_subject_from_list(oArgs) {
    alert("selection subject from list")
}


function cancel_new_category() {



    Dom.setStyle('new_category_no_name_msg', 'display', 'none')

    dialog_new_category.hide();

}

function dialog_new_category_show() {
    Dom.get('new_category_code').value = '';
    Dom.get('new_category_label').value = '';
    Dom.setStyle(['new_category_no_label_msg', 'new_category_no_code_msg', 'new_category_msg'], 'display', 'none')
    set_new_category_as_normal()
    dialog_new_category.show();
    Dom.get('new_category_code').focus();
}



function save_new_category() {

    var code = Dom.get("new_category_code").value;
    var label = Dom.get("new_category_label").value;

    var store_key = Dom.get("new_category_store_key").value;
    var warehouse_key = Dom.get("new_category_warehouse_key").value;

    var parent_key = Dom.get("new_category_parent_key").value;
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



    var ar_file = 'ar_edit_categories.php';
    var request = 'tipo=new_category&subject=' + subject + '&label=' + label + '&code=' + code + '&parent_key=' + parent_key + '&other=' + Dom.get('new_category_other').value;

    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                if (r.is_category_other == 'Yes') {
                    Dom.setStyle('is_category_other_tr', 'display', 'none')
                }
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


function set_new_category_as_other() {
    Dom.setStyle(['set_new_category_as_other', 'new_category_label_tr'], 'display', 'none')
    Dom.setStyle(['set_new_category_as_normal', 'new_category_other_title'], 'display', '')
    Dom.get('new_category_other').value = 'Yes';
    Dom.get('new_category_label').value = 'Other';
}

function set_new_category_as_normal() {
    Dom.setStyle(['set_new_category_as_other', 'new_category_label_tr'], 'display', '')
    Dom.setStyle(['set_new_category_as_normal', 'new_category_other_title'], 'display', 'none')
    Dom.get('new_category_other').value = 'No';
    Dom.get('new_category_label').value = '';

}


YAHOO.util.Event.onContentReady("dialog_new_category", function() {
    dialog_new_category = new YAHOO.widget.Dialog("dialog_new_category", {
        context: ["new_category", "tl", "bl"],
        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });

    dialog_new_category.render();
   
});

YAHOO.util.Event.onContentReady("dialog_no_asssigned_subjects", function() {
    dialog_no_asssigned_subjects = new YAHOO.widget.Dialog("dialog_no_asssigned_subjects", {
       
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });

    dialog_no_asssigned_subjects.render();
});



function save_delete_category_from_list() {
    var request = 'ar_edit_categories.php?tipo=delete_category&category_key=' + Dom.get('delete_from_list_category_key').value
    Dom.setStyle('deleting_from_list', 'display', '');
    Dom.setStyle('delete_category_buttons_from_list', 'display', 'none');

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                var table = tables.table0;
                var datasource = tables.dataSource0;
                var request = '';
                datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
                var table = tables.table1;
                var datasource = tables.dataSource1;
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                update_category_history_elements()
                dialog_delete_category_from_list.hide()

                if (r.was_category_other == 'Yes') {
                    Dom.setStyle('is_category_other_tr', 'display', '')

                }


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


var total_parts_checked = 0;




function validate_code(query) {
    validate_general('category', 'code', unescape(query));
}

function validate_label(query) {
    validate_general('category', 'label', unescape(query));
}

function validate_subcategory_name(query) {
    validate_general('subcategory', 'subcategory_name', unescape(query));
}

function reset_new_category() {
    reset_edit_general('category');
}

function reset_edit_category() {
    reset_edit_general('category')
}

function save_edit_subcategory() {
    save_edit_general_bulk('subcategory');
}

function reset_edit_subcategory() {
    reset_edit_general('subcategory')
}

function reset_new_subcategory() {
    reset_edit_general('subcategory');
}

function save_display_category(key, value, id) {

    var request = 'ar_edit_categories.php?tipo=edit_categories&okey=' + key + '&key=' + key + '&newvalue=' + value + '&id=' + id
    //   alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {


                Dom.removeClass([r.key + ' Yes', r.key + ' No'], 'selected');
                Dom.addClass(r.key + ' ' + r.newvalue, 'selected');
                var table = tables.table1;
                var datasource = tables.dataSource1;
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                update_category_history_elements()

                Dom.get('user_view_icon').innerHTML = r.user_view_icon

            } else {
                alert(r.msg)


            }
        }
    });

}

function post_item_updated_actions(branch, r) {
    key = r.key;
    newvalue = r.newvalue;

    if (key == 'code') {
        Dom.get('title_code').innerHTML = newvalue;

        Dom.get('branch_tree').innerHTML = r.branch_tree;
    }



    var table = tables.table1;
    var datasource = tables.dataSource1;
    var request = '';
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    var table_id = 1


    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];


    var request = '&tableid=' + table_id + '&sf=0';
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

    update_category_history_elements()

}

function post_create_actions(branch) {
    var table = tables.table1;
    var datasource = tables.dataSource1;
    var request = '';
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

    var table = tables.table0;
    var datasource = tables.dataSource0;
    var request = '';
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function cancel_add_category() {
    reset_new_category();
}

function cancel_add_subcategory() {
    reset_new_subcategory();
}


function delete_category() {
    region1 = Dom.getRegion('delete_category');
    region2 = Dom.getRegion('dialog_delete_category');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_delete_category', pos);
    dialog_delete_category.show();
}

function save_delete_category() {

    var request = 'ar_edit_categories.php?tipo=delete_category&category_key=' + Dom.get('category_key').value
    Dom.setStyle('deleting', 'display', '');
    Dom.setStyle('delete_category_buttons', 'display', 'none');
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                location.href = 'category.php?id=' + r.category_key
            } else {
                Dom.setStyle('deleting', 'display', 'none');
                Dom.setStyle('delete_category_buttons', 'display', '');
                Dom.get('delete_category_msg').innerHTML = r.msg
            }
        }
    });
}

function cancel_delete_category() {
    dialog_delete_category.hide();
}

function change_history_elements(e, table_id) {
    ids = ['elements_Changes', 'elements_Assign'];
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

function show_history() {
    Dom.setStyle(['show_history', ''], 'display', 'none')
    Dom.setStyle(['hide_history', 'history_table'], 'display', '')

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=part_categories-show_history&value=1', {});

}

function hide_history() {
    Dom.setStyle(['show_history', ''], 'display', '')
    Dom.setStyle(['hide_history', 'history_table'], 'display', 'none')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=part_categories-show_history&value=0', {});

}


function reset_edit_subjects_fields(){};

function show_dialog_edit_subjects(){

 Dom.setStyle(['dialog_edit_subjects_fields'], 'display', '')
    Dom.setStyle(['dialog_edit_subjects_wait','dialog_edit_subjects_results'], 'display', 'none')
Dom.get('dialog_edit_subjects_wait_done').innerHTML='';

 region1 = Dom.getRegion('show_subjects_edit_options_button');
 
 Dom.addClass('show_subjects_edit_options_button','selected');
 
   region2 = Dom.getRegion('dialog_edit_subjects');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_edit_subjects', pos);
    reset_edit_subjects_fields();
    dialog_edit_subjects.show();
}


function show_assign_subject_dialog(){
region1 = Dom.getRegion('chooser_ul');
   region2 = Dom.getRegion('assign_subject');
    var pos = [region1.left-20 , region2.bottom]
    Dom.setXY('dialog_no_asssigned_subjects', pos);
    dialog_no_asssigned_subjects.show();
}

function init_edit_category() {

    Event.addListener("new_category", "click", dialog_new_category_show, true);
    Event.addListener("new_category_cancel", "click", cancel_new_category, true);
    Event.addListener("new_category_save", "click", save_new_category, true);


    Event.addListener("assign_subject", "click", show_assign_subject_dialog, true);




    validate_scope_data = {
        'category': {

            'code': {
                'changed': false,
                'validated': true,
                'required': true,
                'group': 1,
                'type': 'item',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('msg_invalid_category_code').value
                }],
                'name': 'Category_Code',
                'ar': false,
                'ar_request': false
            }

            ,
            'label': {
                'changed': false,
                'validated': true,
                'required': true,
                'group': 1,
                'type': 'item',
                'validation': [{
                    'regexp': "[a-z\d]+",
                    'invalid_msg': Dom.get('msg_invalid_category_label').value
                }],
                'name': 'Category_Label',
                'ar': false,
                'ar_request': false
            }
        }


    };
    validate_scope_metadata = {
        'category': {
            'type': 'edit',
            'ar_file': 'ar_edit_categories.php',
            'key_name': 'category_key',
            'key': Dom.get('category_key').value
        }
    };

    YAHOO.util.Event.addListener('save_edit_category', "click", save_new_category);
    YAHOO.util.Event.addListener('reset_edit_category', "click", cancel_add_category);


    dialog_subject_no_assigned_list = new YAHOO.widget.Dialog("dialog_subject_no_assigned_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_subject_no_assigned_list.render();
    dialog_category_heads_list = new YAHOO.widget.Dialog("dialog_category_heads_list", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_category_heads_list.render();


    dialog_delete_category = new YAHOO.widget.Dialog("dialog_delete_category", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_delete_category.render();


    var category_code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    category_code_oACDS.queryMatchContains = true;
    var category_code_oAutoComp = new YAHOO.widget.AutoComplete("Category_Code", "Category_Code_Container", category_code_oACDS);
    category_code_oAutoComp.minQueryLength = 0;
    category_code_oAutoComp.queryDelay = 0.1;

    var category_label_oACDS = new YAHOO.util.FunctionDataSource(validate_label);
    category_label_oACDS.queryMatchContains = true;
    var category_label_oAutoComp = new YAHOO.widget.AutoComplete("Category_Label", "Category_Label_Container", category_label_oACDS);
    category_label_oAutoComp.minQueryLength = 0;
    category_label_oAutoComp.queryDelay = 0.1;

    Event.addListener('clean_table_filter_show5', "click", show_filter, 5);
    Event.addListener('clean_table_filter_hide5', "click", hide_filter, 5);
    Event.addListener('clean_table_filter_show4', "click", show_filter, 4);
    Event.addListener('clean_table_filter_hide4', "click", hide_filter, 4);

    Event.addListener('clean_table_filter_show3', "click", show_filter, 3);
    Event.addListener('clean_table_filter_hide3', "click", hide_filter, 3);
    Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
    Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);

    Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
    Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);
    Event.addListener('clean_table_filter_show2', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide2', "click", hide_filter, 0);

    var oACDS5 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS5.queryMatchContains = true;
    oACDS5.table_id = 5;
    var oAutoComp5 = new YAHOO.widget.AutoComplete("f_input5", "f_container5", oACDS5);
    oAutoComp5.minQueryLength = 0;

    var oACDS4 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS4.queryMatchContains = true;
    oACDS4.table_id = 4;
    var oAutoComp4 = new YAHOO.widget.AutoComplete("f_input4", "f_container4", oACDS4);
    oAutoComp4.minQueryLength = 0;

    var oACDS3 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS3.queryMatchContains = true;
    oACDS3.table_id = 3;
    var oAutoComp3 = new YAHOO.widget.AutoComplete("f_input3", "f_container3", oACDS3);
    oAutoComp3.minQueryLength = 0;

    var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS2.queryMatchContains = true;
    oACDS2.table_id = 2;
    var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2", "f_container2", oACDS2);
    oAutoComp2.minQueryLength = 0;

    var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS1.queryMatchContains = true;
    oACDS1.table_id = 1;
    var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS1);
    oAutoComp1.minQueryLength = 0;

    var oACDS0 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS0.queryMatchContains = true;
    oACDS0.table_id = 0;
    var oAutoComp0 = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS0);
    oAutoComp0.minQueryLength = 0;




    Event.addListener('delete_category', "click", delete_category);
    Event.addListener('save_delete_category', "click", save_delete_category);
    Event.addListener('cancel_delete_category', "click", cancel_delete_category);

    dialog_delete_category_from_list = new YAHOO.widget.Dialog("dialog_delete_category_from_list", {

        visible: false,
        close: false,
        underlay: "none",
        draggable: false
    });
    dialog_delete_category_from_list.render();

    ids = ['elements_Changes','elements_Assign'];
    Event.addListener(ids, "click", change_history_elements, 1);
    
    
    dialog_edit_subjects = new YAHOO.widget.Dialog("dialog_edit_subjects", {visible : false,close:false,underlay: "none",draggable:false});
	dialog_edit_subjects.render();
    Event.addListener('show_subjects_edit_options_button', "click", show_dialog_edit_subjects);

}

YAHOO.util.Event.onDOMReady(init_edit_category);
