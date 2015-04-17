function close_dialog_sticky_note() {
    dialog_sticky_note.hide()
}


function save_sticky_note() {
    var request = 'ar_edit_notes.php?tipo=edit_sticky_note&parent=order&parent_key=' + Dom.get('order_key').value + '&note=' + my_encodeURIComponent(Dom.get("sticky_note_input").value)

    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
        //    alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);



            if (r.state == 200) {

                Dom.get('sticky_note_content').innerHTML = r.newvalue;

                close_dialog_sticky_note()
                if (r.newvalue == '') {
                    Dom.setStyle(['sticky_note_div'], 'display', 'none');


                } else {


                    Dom.setStyle(['sticky_note_div'], 'display', '');

                }




            } else Dom.get('sticky_note_msg').innerHTML = r.msg;

        }
    });


}

function change_sticky_note() {

}

function show_sticky_note(e,anchor) {
    Dom.setStyle('dialog_sticky_note', 'display', '')
    region1 = Dom.getRegion(anchor);
    region2 = Dom.getRegion('dialog_sticky_note');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_sticky_note', pos);
    dialog_sticky_note.show()
    Dom.get('sticky_note_input').focus();
}



function init_notes() {

    dialog_sticky_note = new YAHOO.widget.Dialog("dialog_sticky_note", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_sticky_note.render();

    Event.addListener(["sticky_note_button"], "click", show_sticky_note,'sticky_note_button');
    Event.addListener(["sticky_note_bis"], "click", show_sticky_note,'sticky_note_bis');

}

YAHOO.util.Event.onDOMReady(init_notes);
