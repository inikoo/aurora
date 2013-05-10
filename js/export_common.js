function show_export_dialog(e, tag) {

    //  Dom.get('export_xls').onclick = function() {
    //     window.location = 'export.php?ar_file=ar_contacts&tipo=customers&parent=store&parent_key=' + Dom.get('store_key').value + '&output=xls'
    //  };
    //  Dom.get('export_csv').onclick = function() {
    //      window.location = 'export.php?ar_file=ar_contacts&tipo=customers&parent=store&parent_key=' + Dom.get('store_key').value + '&output=csv'
    // };


    Dom.setStyle('dialog_export_' + tag, 'display', '');

    region1 = Dom.getRegion('export_' + tag);
    region2 = Dom.getRegion('dialog_export');

    var pos = [region1.right - 20, region1.bottom]
    Dom.setXY('dialog_export_' + tag, pos);


    Dom.setStyle(['dialog_export_form_' + tag, 'export_result_wait_' + tag], 'display', '')
    Dom.setStyle(['dialog_export_maps_' + tag, 'dialog_export_fields_' + tag, 'dialog_export_result_' + tag, 'export_result_download_' + tag], 'display', 'none')
    Dom.get('export_result_download_link_' + tag).href = '';
    Dom.get('dialog_export_progress_' + tag).innerHTML = '';

    dialog_export.show()

}

function show_export_map_fields(tag) {

    Dom.setStyle('dialog_export_fields_' + tag, 'display', '')
    Dom.addClass('dialog_export_map_fields_' + tag, 'selected')
    Dom.removeClass('dialog_export_map_library_' + tag, 'selected')

}

function field_map_changed(tag) {
    changed = false
    fields = Dom.getElementsByClassName('map_field_' + tag, 'img');

    for (i in fields) {

        field = fields[i];
        if (field.getAttribute('checked') != field.getAttribute('ovalue')) {
            changed = true;
            //break;
        }

    }

    return changed;
}


function update_fields_state(changed,tag) {

    if (changed) {
        //Dom.setStyle('field_map_save_as_'+tag,'visibility','visible')
        Dom.setStyle(['field_map_buttons_' + tag, 'map_modified_' + tag], 'display', '')

        Dom.removeClass('export_fields_reset_' + tag, 'disabled')

    } else {
        Dom.setStyle(['field_map_buttons_' + tag, 'map_modified_' + tag], 'display', 'none')
        //	Dom.setStyle('field_map_buttons_'+tag,'visibility','hidden')
        Dom.addClass('export_fields_reset_' + tag, 'disabled')


    }

}

function update_map_field(o, tag) {

    if (o.getAttribute('checked') == 1) {
        o.src = 'art/icons/checkbox_unchecked.png';
        o.setAttribute('checked', 0)
    } else {
        o.src = 'art/icons/checkbox_checked.png';
        o.setAttribute('checked', 1)

    }
Dom.setStyle('export_no_field_msg_'+tag,'display','none')

    update_fields_state(field_map_changed(tag),tag)



}


function reset_export_fields(tag) {
    fields = Dom.getElementsByClassName('map_field_' + tag, 'img');

    for (i in fields) {

        field = fields[i];
        checked = field.getAttribute('ovalue');
       
        field.setAttribute('checked', checked)
        if (checked==1) {
            field.src = 'art/icons/checkbox_checked.png';

        } else {
            field.src = 'art/icons/checkbox_unchecked.png';


        }

    }
    
     update_fields_state(false,tag)
}


function get_export_table_wait_info(fork_key, table) {
    request = 'ar_export.php?tipo=get_wait_info&fork_key=' + fork_key + '&table=' + table
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //   alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                if (r.fork_state == 'Queued') {
                    setTimeout(function() {
                        get_export_table_wait_info(r.fork_key, r.table)
                    }, 1000);


                } else if (r.fork_state == 'In Process') {
                    // alert(r.msg)
                    //Dom.get('dialog_edit_subjects_wait_done').innerHTML = r.msg
                    Dom.get('dialog_export_progress_' + r.table).innerHTML = r.progress;


                    setTimeout(function() {
                        get_export_table_wait_info(r.fork_key, r.table)
                    }, 1000);

                } else if (r.fork_state == 'Finished') {


                    Dom.setStyle('export_result_wait_' + r.table, 'display', 'none')
                    Dom.get('export_result_download_link_' + r.table).href = 'download.php?f=' + r.result;

                    Dom.setStyle('export_result_download_' + r.table, 'display', '')



                }


            }
        }

    });

}




function export_table(e, data) {


	list_fields='';
 fields = Dom.getElementsByClassName('map_field_' + data.table, 'img');

    for (i in fields) {
        field = fields[i];
        if (field.getAttribute('checked')==1) {
    		  list_fields+=','+field.getAttribute('name')
      }

    }
    list_fields=list_fields.replace(/^,/g, '')

if(list_fields==''){
Dom.setStyle('export_no_field_msg_'+data.table,'display','')
return;
}



    request = 'ar_export.php?tipo=export&table=' + data.table + '&parent=' + data.parent + '&parent_key=' + data.parent_key + '&output=' + data.output+'&fields='+list_fields
  
    YAHOO.util.Connect.asyncRequest('POST', request, {

        success: function(o) {
            //	alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == '200') {

                Dom.setStyle(['dialog_export_form_' + r.table, 'dialog_export_maps_' + r.table, 'dialog_export_fields_' + r.table], 'display', 'none')
                Dom.setStyle('dialog_export_result_' + r.table, 'display', '')

                get_export_table_wait_info(r.fork_key, r.table);


            } else {

                //Dom.get('send_reset_password_msg').innerHTML = r.msg;
            }


        }
    });

}

function download_export_file() {
    dialog_export.hide()

}
