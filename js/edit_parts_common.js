var active_field = '';
var edit_pid = 0;

function edit_selected_parts_status() {

    ids = ['edit_selected_parts_status_In_Use', 'edit_selected_parts_status_Not_In_Use']


    if (Dom.hasClass(this, 'selected')) {
        Dom.removeClass(this, 'selected')
    } else {
        Dom.removeClass(ids, 'selected')
        Dom.addClass(this, 'selected')
    }

    var number_selected_elements = 0;
    for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
            number_selected_elements++;
        }
    }

    if (number_selected_elements > 0) {
        active_field = 'status'
        Dom.removeClass('save_edit_selected_parts', 'disabled')
        Dom.setStyle('cancel_edit_selected_parts', 'display', '')
        Dom.setStyle('close_edit_selected_parts', 'display', 'none')

        block_other_fields('status')
    } else {
        Dom.addClass('save_edit_selected_parts', 'disabled')
        Dom.setStyle('cancel_edit_selected_parts', 'display', 'none')
        Dom.setStyle('close_edit_selected_parts', 'display', '')
        unblock_fieds()

    }

}



function reset_selected_parts_fields() {
    ids = ['edit_selected_parts_status_In_Use', 'edit_selected_parts_status_Not_In_Use']
    Dom.removeClass(ids, 'selected')

    Dom.get('edit_selected_parts_weight').value = '';

}


function cancel_edit_selected_parts() {



    reset_selected_parts_fields();
    Dom.addClass('save_edit_selected_parts', 'disabled')
    Dom.setStyle('cancel_edit_selected_parts', 'display', 'none')
    Dom.setStyle('close_edit_selected_parts', 'display', '')
    unblock_fieds()


}


function close_edit_selected_parts() {
    Dom.removeClass('show_subjects_edit_options_button', 'selected');

    dialog_edit_subjects.hide()
}

function block_other_fields(field) {
    fields = ['status', 'weight']

    for (i in fields) {
        if (fields[i] != field) {

            Dom.addClass('edit_selected_parts_' + fields[i] + '_tr', 'blocked');
        }
    }

}

function unblock_fieds() {
    ids = ['edit_selected_parts_status_tr', 'edit_selected_parts_weight_tr']
    Dom.removeClass(ids, 'blocked');



}


function get_edit_selected_parts_wait_info() {

    if (edit_pid) {
        request = 'ar_edit_parts.php?tipo=get_edit_selected_parts_wait_info&edit_pid=' + edit_pid
        //alert(request)
        YAHOO.util.Connect.asyncRequest('POST', request, {
            success: function(o) {
                // alert(o.responseText)
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 251) {
                    Dom.get('dialog_edit_subjects_wait_done').innerHTML = r.msg

                }

                setInterval(function() {
                    get_edit_selected_parts_wait_info()
                }, 1000);
            }

        });
    }
}

function save_edit_selected_parts() {
    if (assigned_subjects_check_start_type == 'checked') {
        subject_source_checked_subjects = unchecked_assigned_subjects
    } else {
        subject_source_checked_subjects = checked_assigned_subjects
    }

    switch (active_field) {

    case 'status':

        key = 'Part Status';

        if (Dom.hasClass('edit_selected_parts_status_In_Use', 'selected')) value = 'In Use';
        else if (Dom.hasClass('edit_selected_parts_status_Not_In_Use', 'selected')) value = 'Not In Use';
        break;
    }
    edit_pid = (1 + Math.floor(Math.random() * 1001));
    //edit_pid=500
    request = 'ar_edit_parts.php?tipo=edit_parts&parent=' + Dom.get('parent').value + '&parent_key=' + Dom.get('parent_key').value + '&subject_source_checked_type=' + assigned_subjects_check_start_type + '&subject_source_checked_subjects=' + subject_source_checked_subjects + '&key=' + key + '&value=' + value + '&edit_pid=' + edit_pid

    Dom.setStyle(['dialog_edit_subjects_fields', 'dialog_edit_subjects_results'], 'display', 'none')
    Dom.setStyle('dialog_edit_subjects_wait', 'display', '')
    //alert(request)
    get_edit_selected_parts_wait_info();

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                edit_pid = 0;
                Dom.setStyle(['dialog_edit_subjects_results'], 'display', '')
                Dom.setStyle(['dialog_edit_subjects_wait', 'dialog_edit_subjects_fields'], 'display', 'none')
                if (r.number_parts_errors == 0) {
                    Dom.setStyle('dialog_edit_subjects_parts_errors_tr', 'display', 'none')
                } else {
                    Dom.setStyle('dialog_edit_subjects_parts_errors_tr', 'display', '')
                    Dom.get('dialog_edit_subjects_parts_errors').innerHTML = r.number_parts_errors
                }
                if (r.number_parts_no_change == 0) {
                    Dom.setStyle('dialog_edit_subjects_parts_nochanged_tr', 'display', 'none')
                } else {
                    Dom.setStyle('dialog_edit_subjects_parts_nochanged_tr', 'display', '')
                    Dom.get('dialog_edit_subjects_parts_nochanged').innerHTML = r.number_parts_no_change
                }

                Dom.get('dialog_edit_subjects_parts_updated').innerHTML = r.number_parts_updated


            }


        }

    });

}


function init_edit_parts() {
    ids = ['edit_selected_parts_status_In_Use', 'edit_selected_parts_status_Not_In_Use']
    YAHOO.util.Event.addListener(ids, "click", edit_selected_parts_status);


}
YAHOO.util.Event.onDOMReady(init_edit_parts);
