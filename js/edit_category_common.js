var Event = YAHOO.util.Event;
var Dom = YAHOO.util.Dom;
var select_category_head_from_list_action
var dialog_category_heads_list;

var number_checked_no_assigned_subjects = 0;
var checked_no_assigned_subjects = [];
var unchecked_no_assigned_subjects = [];
var no_assigned_subjects_check_start_type = 'unchecked';

var number_checked_assigned_subjects = 0;
var checked_assigned_subjects = [];
var unchecked_assigned_subjects = [];
var assigned_subjects_check_start_type = 'unchecked';





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


	if(Dom.get('branch_type').value=='Head'){
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

    request = 'ar_edit_categories.php?tipo=associate_multiple_subject_to_category&category_key=' + category_key + '&subject_source_checked_type=' + no_assigned_subjects_check_start_type + '&subject_source_checked_subjects=' + subject_source_checked_subjects + '&callback_category_key=' + Dom.get('category_key').value+'&subject_source=0'

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


function check_all_assigned_subject() {
    number_checked_assigned_subjects = 0;
    checked_assigned_subjects = [];
    unchecked_assigned_subjects = [];
    assigned_subjects_check_start_type = 'checked';
    Dom.setStyle(['uncheck_all_assigned_subjects', 'checked_assigned_subjects_dialog', 'checked_assigned_subjects_assign_to_category_button', 'checked_assigned_subjects_remove_from_category_button'], 'display', '')
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
    Dom.setStyle(['uncheck_all_assigned_subjects', 'checked_assigned_subjects_dialog', 'checked_assigned_subjects_assign_to_category_button', 'checked_assigned_subjects_remove_from_category_button'], 'display', 'none')
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
        Dom.setStyle(['uncheck_all_assigned_subjects', 'checked_assigned_subjects_dialog', 'checked_assigned_subjects_assign_to_category_button', 'checked_assigned_subjects_remove_from_category_button'], 'display', 'none')
    } else {
        Dom.setStyle(['uncheck_all_assigned_subjects', 'checked_assigned_subjects_dialog', 'checked_assigned_subjects_assign_to_category_button', 'checked_assigned_subjects_remove_from_category_button'], 'display', '')
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

    request = 'ar_edit_categories.php?tipo=associate_multiple_subject_to_category&category_key=' + category_key + '&subject_source_checked_type=' + assigned_subjects_check_start_type + '&subject_source_checked_subjects=' + subject_source_checked_subjects + '&callback_category_key=' + Dom.get('category_key').value+'&subject_source='+Dom.get('category_key').value


    Dom.setStyle('wait_checked_assigned_subjects_assign_to_category', 'display', '')
    Dom.setStyle(['check_all_assigned_subjects', 'uncheck_all_assigned_subjects', 'checked_assigned_subjects_dialog', 'checked_assigned_subjects_assign_to_category_button', 'checked_assigned_subjects_remove_from_category_button'], 'display', 'none')
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
    }else if(select_category_head_from_list_action == 'move_multiple_subject_to_category') {
  
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
    Dom.setStyle(['check_all_assigned_subjects', 'uncheck_all_assigned_subjects', 'checked_assigned_subjects_dialog', 'checked_assigned_subjects_assign_to_category_button', 'checked_assigned_subjects_remove_from_category_button'], 'display', 'none')
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
    
    	var subject_key=record.getData('subject_key')
    	
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
        show_cell_dialog(this, oArgs);
        break;


    case 'edit':

        if (column.object == 'post_to_send') ar_file = 'ar_edit_contacts.php';



        //alert(ar_file+'?tipo=edit_'+column.object + myBuildUrl(this,record))
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
    Dom.get('new_category_name').value = '';
    Dom.setStyle('new_category_no_name_msg', 'display', 'none')

    dialog_new_category.hide();

}

function dialog_new_category_show() {

    dialog_new_category.show();
    Dom.get('new_category_name').focus();
}

function post_create_actions() {

}

function save_new_category() {

    var name = Dom.get("new_category_name").value;
    var store_key = Dom.get("new_category_store_key").value;
    var warehouse_key = Dom.get("new_category_warehouse_key").value;

    var parent_key = Dom.get("new_category_parent_key").value;
    var subject = Dom.get("new_category_subject").value;

    if (name == '') {
        Dom.setStyle('new_category_no_name_msg', 'display', '')
        return;
    } else {
        Dom.setStyle('new_category_no_name_msg', 'display', 'none')

    }

    var ar_file = 'ar_edit_categories.php';
    var request = 'tipo=new_category&subject=' + subject + '&name=' + name + '&store_key=' + store_key + '&warehouse_key=' + warehouse_key + '&parent_key=' + parent_key;

    //alert(request);
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {


            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
/*
							table_id=1;
							var table=tables['table'+table_id];
    						var datasource=tables['dataSource'+table_id];
    						var request='&table_id='+table_id;
    						datasource.sendRequest(request,table.onDataReturnInitializeTable, table);     
					
							table_id=2;
							var table=tables['table'+table_id];
							if(table!= undefined){
								var datasource=tables['dataSource'+table_id];
    							var request='&table_id=_history';
    							datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   
							*/
                post_create_actions()
                cancel_new_category()

            } else {
                alert(r.msg)
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

    Event.addListener("new_category", "click", dialog_new_category_show, true);
    Event.addListener("new_category_cancel", "click", cancel_new_category, true);
    Event.addListener("new_category_save", "click", save_new_category, true);

});



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
    save_edit_general('subcategory');
}

function reset_edit_subcategory() {
    reset_edit_general('subcategory')
}

function reset_new_subcategory() {
    reset_edit_general('subcategory');
}

function save_display_category(key, value, id) {

    var request = 'ar_edit_categories.php?tipo=edit_category&okey=' + key + '&key=' + key + '&newvalue=' + value + '&category_key=' + id

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.removeClass([r.key + ' Yes', r.key + ' No'], 'selected');
                Dom.addClass(r.key + ' ' + r.newvalue, 'selected');
            } else {
                alert(r.msg)


            }
        }
    });

}

function post_item_updated_actions(branch, r) {
    key = r.key;
    newvalue = r.newvalue;
    if (key == 'name') {
        Dom.get('title_name').innerHTML = newvalue;
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

function edit_category_init() {

validate_scope_data={
'category':{

    'code':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('msg_invalid_category_code').value}],'name':'Category_Code'
	    ,'ar':false,'ar_request':false}
	
	,'label':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':Dom.get('msg_invalid_category_label').value}],'name':'Category_Label'
	    ,'ar':false,'ar_request':false}
	}

  
};
 validate_scope_metadata={'category':{'type':'edit','ar_file':'ar_edit_categories.php','key_name':'category_key','key':Dom.get('category_key').value}};

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
    
    
  
       var category_code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    category_code_oACDS.queryMatchContains = true;
    var category_code_oAutoComp = new YAHOO.widget.AutoComplete("Category_Code","Category_Code_Container", category_code_oACDS);
    category_code_oAutoComp.minQueryLength = 0; 
    category_code_oAutoComp.queryDelay = 0.1;
    
      var category_label_oACDS = new YAHOO.util.FunctionDataSource(validate_label);
    category_label_oACDS.queryMatchContains = true;
    var category_label_oAutoComp = new YAHOO.widget.AutoComplete("Category_Label","Category_Label_Container", category_label_oACDS);
    category_label_oAutoComp.minQueryLength = 0; 
    category_label_oAutoComp.queryDelay = 0.1;
    
     
    //Event.addListener('clean_table_filter_show0', "click",show_filter,0);
// Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
Event.addListener('clean_table_filter_show3', "click",show_filter,3);
 Event.addListener('clean_table_filter_hide3', "click",hide_filter,3);
Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);
/*
var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
  oACDS.table_id=0;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 
 */
 var oACDS3 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS3.queryMatchContains = true;
  oACDS3.table_id=3;
 var oAutoComp3 = new YAHOO.widget.AutoComplete("f_input3","f_container3", oACDS3);
 oAutoComp3.minQueryLength = 0; 

 var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
  oACDS2.table_id=2;
 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);
 oAutoComp2.minQueryLength = 0; 
    
    

}

YAHOO.util.Event.onDOMReady(edit_category_init);
