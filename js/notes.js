var dialog_note;
var dialog_sticky_note;
var dialog_attach;
var dialog_delete_history_record_from_list;


var onCellClick = function(oArgs) {
        var target = oArgs.target,
            column = this.getColumn(target),
            record = this.getRecord(target);

        var recordIndex = this.getRecordIndex(record);
        ar_file = 'ar_edit_notes.php';

        switch (column.action) {
        case ('edit'):
            Dom.get('edit_note_history_key').value = record.getData('key');

            Dom.get('edit_note_input').value = record.getData('note');

            Dom.get('record_index').value = recordIndex;


            var y = (Dom.getY(target)) - 0
            var x = (Dom.getX(target)) - 200
            Dom.setX('dialog_edit_note', x)
            Dom.setY('dialog_edit_note', y)
            dialog_edit_note.show();
            break;
                case 'dialog':
        case 'dialog_delete':
         if (record.getData('can_delete')) {
            show_cell_dialog(this, oArgs);
            }else{



                    if (record.getData('strikethrough') == 'Yes')
                    var action = 'unstrikethrough_';
                    else var action = 'strikethrough_';
                    request = ar_file + '?tipo=' + action + 'history&parent=' + Dom.get('subject').value + myBuildUrl(this, record)
                    YAHOO.util.Connect.asyncRequest('GET', request, {
                        success: function(o) {
                            //         alert(o.responseText);
                            var r = YAHOO.lang.JSON.parse(o.responseText);

                            var data = record.getData();
                            data['strikethrough'] = r.strikethrough;
                            data['delete'] = r.delete;

                            //data['delete_type']=r.delete_type;
                            this.updateRow(recordIndex, data);


                        },
                        failure: function(o) {

                        },
                        scope: this
                    });


            }

            break;

        case 'delete':
            if (record.getData('delete') != '') {

                if (record.getData('can_delete')) {
                    //window.confirm('Are you ?');
                    var delete_type = record.getData('delete_type');



                    if (window.confirm('Are you sure, you want to ' + delete_type + ' this row?')) {


                        YAHOO.util.Connect.asyncRequest(

                        'GET',

                        ar_file + '?tipo=delete_history&parent=' + Dom.get('subject').value + myBuildUrl(this, record), {

                            success: function(o) {

                                //  alert(o.responseText);
                                var r = YAHOO.lang.JSON.parse(o.responseText);

                                if (r.state == 200 && r.action == 'deleted') {

                                    this.deleteRow(target);

                                } else if (r.state == 200 && r.action == 'discontinued') {

                                    var data = record.getData();

                                    data['delete'] = r.delete_;


                                    data['delete_type'] = r.delete_type;
                                    this.updateRow(recordIndex, data);


                                } else {
                                    alert(r.msg);
                                }
                            },
                            failure: function(o) {

                            },
                            scope: this
                        });
                    }
                } else {


                    if (record.getData('strikethrough') == 'Yes') var action = 'unstrikethrough_';
                    else var action = 'strikethrough_';
                    request = ar_file + '?tipo=' + action + 'history&parent=' + Dom.get('subject').value + myBuildUrl(this, record)
                    YAHOO.util.Connect.asyncRequest('GET', request, {
                        success: function(o) {
                            //         alert(o.responseText);
                            var r = YAHOO.lang.JSON.parse(o.responseText);

                            var data = record.getData();
                            data['strikethrough'] = r.strikethrough;
                            data['delete'] = r.delete;

                            //data['delete_type']=r.delete_type;
                            this.updateRow(recordIndex, data);


                        },
                        failure: function(o) {

                        },
                        scope: this
                    });

                }



            }
            break;

        default:

            this.onEventShowCellEditor(oArgs);
            break;
        }
    };


function save_delete_history_record_from_list() {



    var table = tables['table' + Dom.get('delete_from_list_table_id').value];
    var table_record_index = Dom.get('delete_from_list_record_index').value;
	    var history_id = Dom.get('delete_from_list_history_key').value;

request='ar_edit_notes.php?tipo=delete_history&parent=' + Dom.get('subject').value+'&parent_key=' + Dom.get('subject_key').value + '&key='+history_id;
   YAHOO.util.Connect.asyncRequest('GET',request, {

        success: function(o) {
//alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200 && r.action == 'deleted') {

                table.deleteRow(parseInt(table_record_index));
                dialog_delete_history_record_from_list.hide();
				
				get_history_numbers()
				
				//Dom.get('elements_attachments_number').innerHTML=r.elements_numbers.Attachments;
				//Dom.get('elements_notes_number').innerHTML=r.elements_numbers.Notes;
				
            } else {
                alert(r.msg);
            }
        },
        failure: function(o) {

        },
        scope: this
    });







}


function cancel_delete_history_record_from_list(){
dialog_delete_history_record_from_list.hide();
}



function save_attachment() {

    YAHOO.util.Connect.setForm('upload_attach_form', true, true);
    var request = 'ar_edit_notes.php?tipo=add_attachment&parent=' + Dom.get('subject').value + "&parent_key=" + Dom.get('subject_key').value + '&caption=' + Dom.get('attachment_caption').value
    //alert(request)
    var uploadHandler = {
        upload: function(o) {
       // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(base64_decode(o.responseText));

            if (r.state == 200) {
                table_id = Dom.get('history_table_id').value
                var table = tables['table' + table_id];
                var datasource = tables['dataSource' + table_id];
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
                close_dialog('attach');
                //Dom.get('elements_attachments_number').innerHTML = r.elements_numbers.Attachments;
				get_history_numbers()


            } else {
                dialog_attach.show();
                Dom.get('attach_msg').innerHTML = r.msg;
            }
        }
    };

    YAHOO.util.Connect.asyncRequest('POST', request, uploadHandler);

}


function save(tipo) {

    switch (tipo) {
    case ('edit_note'):

        request = 'ar_edit_notes.php?tipo=edit_note&parent=' + Dom.get('subject').value + "&parent_key=" + Dom.get('subject_key').value + '&note_key=' + Dom.get('edit_note_history_key').value + '&note=' + my_encodeURIComponent(Dom.get('edit_note_input').value) + '&date=' + Dom.get('edit_note_date').getAttribute('value') + '&record_index=' + Dom.get('record_index').value;
        //      alert(request)
        YAHOO.util.Connect.asyncRequest('GET', request, {
            success: function(o) {
                //    alert(o.responseText)
                var r = YAHOO.lang.JSON.parse(o.responseText);

                if (r.state == 200) {
                    var table = tables['table' + Dom.get('history_table_id').value];

                    record = table.getRecord(r.record_index);

                    var data = record.getData();
                    data['note'] = r.newvalue;
                    table.updateRow(r.record_index, data);

                    close_dialog('edit_note');;

                } else {
                    Dom.get('edit_note_msg').innerHTML = r.msg;

                }



            },
            failure: function(o) {

            },
            scope: this
        });


        break;
    case ('note'):

        if (Dom.hasClass('note_save', 'disabled')) return;



        var value = my_encodeURIComponent(Dom.get(tipo + "_input").value);
        var note_type = my_encodeURIComponent(Dom.get("note_type").getAttribute('value'));

        var request = "ar_edit_notes.php?tipo=add_note&parent=" + Dom.get('subject').value + "&parent_key=" + Dom.get('subject_key').value + "&note=" + value + "&details=&note_type=" + note_type;
        // alert(request);
        YAHOO.util.Connect.asyncRequest('POST', request, {
            success: function(o) {

                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {
               
                    close_dialog(tipo)
                    var table = tables['table' + Dom.get('history_table_id').value];
                    var datasource = tables['dataSource' + Dom.get('history_table_id').value];
                    var request = '';
                    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
                     
				//Dom.get('elements_notes_number').innerHTML=r.elements_numbers.Notes;
get_history_numbers()


                } else Dom.get(tipo + '_msg').innerHTML = r.msg;
            }
        });


        break;
    case ('sticky_note'):



        var request = 'ar_edit_notes.php?tipo=edit_sticky_note&parent=' + Dom.get('subject').value + "&parent_key=" + Dom.get('subject_key').value + '&note=' + my_encodeURIComponent(Dom.get(tipo + "_input").value)

        //alert(request)
        YAHOO.util.Connect.asyncRequest('POST', request, {
            success: function(o) {
                alert(o.responseText)
                var r = YAHOO.lang.JSON.parse(o.responseText);



                if (r.state == 200) {

                    Dom.get('sticky_note_content').innerHTML = r.newvalue;

                    close_dialog(r.key);

                    if (r.newvalue == '') {
                        Dom.setStyle(['sticky_note_div', 'sticky_note_bis_tr'], 'display', 'none');


                    } else {


                        Dom.setStyle(['sticky_note_div', 'sticky_note_bis_tr'], 'display', '');

                    }
					
					
					
					
                    var table = tables['table' + Dom.get('history_table_id').value];
                    var datasource = tables['dataSource' + Dom.get('history_table_id').value];
                    var request = '';
                    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

                } else Dom.get(tipo + '_msg').innerHTML = r.msg;

            }
        });




        break;

  
    }




};

function change(e, o, tipo) {
    switch (tipo) {
    case ('note'):
        if (o.value != '') {
            enable_save(tipo);
/*
	    if(window.event)
		key = window.event.keyCode; //IE
	    else
		key = e.which; //firefox

	    if (key == 13)
		save(tipo);
*/

        } else disable_save(tipo);
        break;

    }
};


function enable_save(tipo) {
    switch (tipo) {
    case ('note'):
        Dom.removeClass(tipo + '_save', 'disabled')
        break;

    }
};

function disable_save(tipo) {
    switch (tipo) {
    case ('note'):
        Dom.addClass(tipo + '_save', 'disabled')
        break;

    }
};


function close_dialog(tipo) {

    switch (tipo) {
        //   case('long_note'):
        // 	//Dom.get(tipo+"_input").value='';
        // 	dialog_note.hide();
        // 	break;
    case ('attach'):

        Dom.get('upload_attach_file').value = '';
        Dom.get('attachment_caption').value = '';
Dom.get('attach_msg').innerHTML='';
        dialog_attach.hide();

        break;
    case ('edit_note'):

        Dom.get("edit_note_input").value = '';

        dialog_edit_note.hide();
        break;
    case ('note'):

        Dom.get(tipo + "_input").value = '';
        Dom.addClass(tipo + '_save', 'disabled');

        dialog_note.hide();
        break;
    case ('sticky_note'):
        dialog_sticky_note.hide();
        Dom.get('sticky_note_input').value = Dom.get('sticky_note_content').innerHTML;
        break;



    case ('export'):
        dialog_export.hide();
        break;
    case ('make_order'):

        //	Dom.get(tipo+"_input").value='';
        //Dom.get(tipo+'_save').style.visibility='hidden';
        dialog_make_order.hide();

        break;
    }


};



function show_dialog_attach() {
        region1 = Dom.getRegion('attach');
    region2 = Dom.getRegion('dialog_attach');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_attach', pos);
    dialog_attach.show()


}

function show_dialog_note() {
        region1 = Dom.getRegion('note');
    region2 = Dom.getRegion('dialog_note');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_note', pos);
    dialog_note.show()


    Dom.get('note_input').focus();
}

function show_sticky_note() {

    region1 = Dom.getRegion('sticky_note_button');
    region2 = Dom.getRegion('dialog_sticky_note');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_sticky_note', pos);
    dialog_sticky_note.show()
    Dom.get('sticky_note_input').focus();
}


function show_cell_dialog(datatable, oArgs) {

    var target = oArgs.target;
    var column = datatable.getColumn(target);
    var record = datatable.getRecord(target);
    var recordIndex = datatable.getRecordIndex(record);
    switch (column.object) {
    case 'delete_note':

        Dom.get('delete_from_list_history_key').value = record.getData('key');
        Dom.get('delete_from_list_record_index').value=recordIndex;
        Dom.get('delete_from_list_table_id').value=datatable.table_id;
        

        
        //Dom.get('delete_from_list_category_code').innerHTML = record.getData('code');
        region1 = Dom.getRegion(target);
        region2 = Dom.getRegion('dialog_delete_history_record_from_list');
        var pos = [region1.right - region2.width, region1.top]
        Dom.setXY('dialog_delete_history_record_from_list', pos);
        dialog_delete_history_record_from_list.show();
        break;
    }

}

function check_if_file_selected() {

    if (Dom.get('upload_attach_file').value == '') {
        Dom.addClass('save_attach_button', 'disabled')
    } else {
        Dom.removeClass('save_attach_button', 'disabled')
    }

}


function init_notes() {




    dialog_note = new YAHOO.widget.Dialog("dialog_note", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_note.render();

    dialog_edit_note = new YAHOO.widget.Dialog("dialog_edit_note", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_edit_note.render();


    dialog_sticky_note = new YAHOO.widget.Dialog("dialog_sticky_note", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_sticky_note.render();



    dialog_attach = new YAHOO.widget.Dialog("dialog_attach", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_attach.render();



dialog_delete_history_record_from_list =  new YAHOO.widget.Dialog("dialog_delete_history_record_from_list", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_delete_history_record_from_list.render();


    Event.addListener(["sticky_note_button", "sticky_note_bis"], "click", show_sticky_note);

    Event.addListener("note", "click", show_dialog_note);
    Event.addListener("upload_attach_file", "change", check_if_file_selected);
    Event.addListener("save_attach_button", "click", save_attachment);



    Event.addListener("attach", "click", show_dialog_attach);

    if (Dom.get('sticky_note_content').innerHTML == '') {
        Dom.setStyle(['sticky_note_div', 'sticky_note_bis_tr'], 'display', 'none');
    } else {
        Dom.setStyle('sticky_note_div', 'display', '');
    }



}
YAHOO.util.Event.onDOMReady(init_notes);
