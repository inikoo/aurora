function change_order_promotion_bonus(el, value) {
    var bonus_deal_component_key = el.getAttribute('bonus_deal_component_key')
    var pid = el.getAttribute('pid')

    if (value) {

        Dom.setStyle(Dom.getElementsByClassName('checkbox_checked', 'img', 'bonus_options_' + bonus_deal_component_key), 'display', 'none')
        Dom.setStyle(Dom.getElementsByClassName('checkbox_unchecked', 'img', 'bonus_options_' + bonus_deal_component_key), 'display', '')


        Dom.setStyle('order_promotion_bonus_checked_' + bonus_deal_component_key + '_' + pid, 'display', '')
        Dom.setStyle('order_promotion_bonus_unchecked_' + bonus_deal_component_key + '_' + pid, 'display', 'none')
    } else {
        Dom.setStyle('order_promotion_bonus_checked_' + bonus_deal_component_key + '_' + pid, 'display', 'none')
        Dom.setStyle('order_promotion_bonus_unchecked_' + bonus_deal_component_key + '_' + pid, 'display', '')


    }

    var ar_file = 'ar_edit_orders.php';
    var request = 'tipo=update_meta_bonus&value=' + value + '&order_key=' + Dom.get('order_key').value + '&pid=' + pid + '&deal_component_key=' + bonus_deal_component_key + '&customer_key=' + Dom.get('customer_key').value + '&product_key=' + el.getAttribute('product_key') + '&family_key=' + el.getAttribute('family_key')+ '&code=' + el.getAttribute('product_code');;



    // alert(ar_file+'?'+request)
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            //    alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {


            } else {
                //  alert(r)
            }
        },
        failure: function(o) {


        },
        scope: this
    }, request

    );


}


function init_edit_bonus() {}

YAHOO.util.Event.onDOMReady(init_edit_bonus);
