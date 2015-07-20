function undo_dispatch(dn_key) {


    Dom.get('undo_dispatch_icon_'+dn_key).src = 'art/loading.gif'
    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=undo_delivery_note_dispatch&dn_key=' + dn_key;
    // alert(ar_file+'?'+request)
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //  alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                location.reload();
            } else {

            }
        },
        failure: function(o) {
            // alert(o.statusText);
        },
        scope: this
    }, request

    );


}
