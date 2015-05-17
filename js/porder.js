function change_block() {

    Dom.setStyle('order_details_panel', 'display', '')

    ids = ['tandc', 'attachments', 'notes']
    block_ids = ['block_tandc', 'block_attachments', 'block_notes'];

    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');



}

function get_history_numbers() {



    var ar_file = 'ar_porders.php';
    var request = 'tipo=get_history_numbers&subject=porder&subject_key=' + Dom.get('po_key').value;

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

function show_dialog_attach_bis() {
    region1 = Dom.getRegion('attach_bis');
    region2 = Dom.getRegion('dialog_attach');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_attach', pos);
    dialog_attach.show()


}

function post_add_attachment_actions(result) {


    var ar_file = 'ar_porders.php';
    var request = 'tipo=get_attachments_showcase&subject=porder&subject_key=' + Dom.get('po_key').value;



    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
                Dom.get('attachments_showcase').innerHTML = r.attachments_showcase
                Dom.get('attachments_label').innerHTML = r.attachments_label
                $('.imgpop').fancyzoom({
                    Speed: 250
                });
            }
        },
        failure: function(o) {},
        scope: this
    }, request

    );


}



function show_estimated_delivery_dialog() {
    region1 = Dom.getRegion('edit_estimated_delivery');
    region2 = Dom.getRegion('edit_estimated_delivery_dialog');
    var pos = [region1.left, region1.bottom + 5]
    Dom.setXY('edit_estimated_delivery_dialog', pos);
    edit_estimated_delivery_dialog.show()
}

function show_estimated_delivery_dialog_calendar() {
    Dom.get('estimated_delivery_msg').innerHTML = '';
    cal2.show()
    Dom.setStyle('estimated_delivery_Container', 'z-index', 10000)
    region1 = Dom.getRegion('v_calpop_estimated_delivery');
    region2 = Dom.getRegion('estimated_delivery_Container');

    var pos = [region1.left - region2.width, region1.top]
    Dom.setXY('estimated_delivery_Container', pos);

}

function submit_edit_estimated_delivery() {
    var date = Dom.get('v_calpop_estimated_delivery').value;

    var ar_file = 'ar_edit_porders.php';
    request = 'tipo=edit_porder_quick&key=estimated_delivery&okey=estimated_delivery&newvalue=' + encodeURIComponent(date) + '&po_key=' + Dom.get('po_key').value;
    // alert(ar_file+'?'+request)
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            // alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('estimated_delivery').innerHTML = r.newvalue;
                edit_estimated_delivery_dialog.hide();

                Dom.get('v_calpop_estimated_delivery').value = r.estimated_delivery;
                //	callback(true, r.newvalue);
            } else {
                Dom.get('estimated_delivery_msg').innerHTML = r.msg
                //	callback();
            }
        },
        failure: function(o) {
            alert(o.statusText);
            // callback();
        },
        scope: this
    }, request

    );
}

function hide_order_details() {
    Dom.setStyle('show_order_details', 'display', '')
    Dom.setStyle('order_details_panel', 'display', 'none')
}


function init() {

    Event.addListener(['tandc', 'attachments', 'notes'], "click", change_block);
    Event.addListener("attach_bis", "click", show_dialog_attach_bis);

    cal2 = new YAHOO.widget.Calendar("cal2", "estimated_delivery_Container", {
        close: true
    });

    cal2.update = updateCal;
    cal2.id = '_estimated_delivery';
    cal2.render();
    cal2.update();
    cal2.selectEvent.subscribe(handleSelect, cal2, true);
    YAHOO.util.Event.addListener("estimated_delivery_pop", "click", show_estimated_delivery_dialog_calendar);


    edit_estimated_delivery_dialog = new YAHOO.widget.Dialog("edit_estimated_delivery_dialog", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    edit_estimated_delivery_dialog.render();
    Event.addListener("edit_estimated_delivery", "click", show_estimated_delivery_dialog);

    Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    var oACDS0 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS0.queryMatchContains = true;
    oACDS0.table_id = 0;
    var oAutoComp0 = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS0);
    oAutoComp0.minQueryLength = 0;

    Event.addListener("hide_order_details", "click", hide_order_details);

}

YAHOO.util.Event.onDOMReady(init);
