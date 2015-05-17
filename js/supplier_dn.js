function change_block() {

    Dom.setStyle('order_details_panel', 'display', '')

    ids = ['attachments', 'notes']
    block_ids = ['block_attachments', 'block_notes'];

    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');



}

function get_history_numbers() {

    var ar_file = 'ar_porders.php';
    var request = 'tipo=get_history_numbers&subject=supplier_dn&subject_key=' + Dom.get('supplier_delivery_note_key').value;

     
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
    var request = 'tipo=get_attachments_showcase&subject=supplier_dn&subject_key=' + Dom.get('supplier_delivery_note_key').value;



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

function hide_order_details() {
    Dom.setStyle('show_order_details', 'display', '')
    Dom.setStyle('order_details_panel', 'display', 'none')
}

function init() {


 init_search('supplier_products_supplier');


    Event.addListener(['attachments', 'notes'], "click", change_block);
    Event.addListener("attach_bis", "click", show_dialog_attach_bis);



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
