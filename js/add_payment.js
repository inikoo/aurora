var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_add_payment;
var dialog_refund_payment;
var dialog_add_credit_note_to_customer;




function save_refund_payment() {

    if (Dom.hasClass('save_refund_payment', 'disabled')) {
        add_credit_note_show_errors()
    } else {


        Dom.setStyle('save_refund_payment_wait', 'display', '')
        Dom.setStyle(['save_refund_payment', 'close_refund_payment'], 'display', 'none')


        var request = 'ar_edit_payments.php?tipo=refund_payment&refund_amount=' + Dom.get('refund_payment_amount').value + "&refund_payment_method=" + Dom.get('refund_payment_method').value + "&refund_reference=" + Dom.get('refund_payment_reference').value + "&payment_key=" + Dom.get('refund_payment_key').value + '&parent_key=' + Dom.get('order_key').value + '&parent=order'
        //alert(request);return;
        YAHOO.util.Connect.asyncRequest('POST', request, {
            success: function(o) {
                //      alert(o.responseText)
                var r = YAHOO.lang.JSON.parse(o.responseText);



                if (r.state == 200) {
                    location.reload()
                } else {

                    alert(o.responseText)
                }

            }
        });

    }


}


function save_add_payment() {

    if (Dom.hasClass('save_add_payment', 'disabled')) {
        add_credit_note_show_errors()
    } else {


        Dom.setStyle('save_add_payment_wait', 'display', '')
        Dom.setStyle(['save_add_payment', 'close_add_payment'], 'display', 'none')


        var request = 'ar_edit_payments.php?tipo=add_payment&payment_amount=' + Dom.get('add_payment_amount').value + "&payment_method=" + Dom.get('add_payment_method').value + "&payment_reference=" + Dom.get('add_payment_reference').value + "&payment_account_key=" + Dom.get('add_payment_payment_account_key').value + '&parent_key=' + Dom.get('order_key').value + '&parent=order'

        YAHOO.util.Connect.asyncRequest('POST', request, {
            success: function(o) {
                //      alert(o.responseText)
                var r = YAHOO.lang.JSON.parse(o.responseText);

                location.reload()

                if (r.state == 200) {

                } else {}

            }
        });

    }


}


function cancel_payment(payment_key) {


    var request = 'ar_edit_payments.php?tipo=cancel_payment&payment_key=' + payment_key + '&order_key=' + Dom.get('order_key').value;



    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

            location.reload()

            if (r.state == 200) {
                location.reload()
            } else {}

        }
    });


}

function complete_payment(payment_key) {


    var request = 'ar_edit_payments.php?tipo=complete_payment&payment_key=' + payment_key + '&order_key=' + Dom.get('order_key').value;



    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            
            var r = YAHOO.lang.JSON.parse(o.responseText);

           

            if (r.state == 200) {
            location.reload()
            } else {
             alert(o.responseText)
            }

        }
    });


}



function add_payment(parent, parent_key) {

    if (parent == 'order') {
        max_amount = Dom.get('show_add_payment_to_order').getAttribute('amount')
        add_payment_to_order(parent_key, max_amount)
    }

}

function add_payment_show_other_amount_field() {

    Dom.setStyle(['amount_paid_total', , 'show_other_amount_field'], 'display', 'none')
    Dom.setStyle(['add_payment_amount', 'add_payment_pay_max_amount'], 'display', '')

    Dom.get('add_payment_amount_formated').innerHTML = money(0, Dom.get('currency_code').value)
    Dom.get('add_payment_amount').value = '';
    Dom.get('add_payment_amount').focus();


}

function refund_payment_show_other_amount_field() {

    Dom.setStyle(['refund_payment_amount_formated',  'show_other_amount_field'], 'display', 'none')
    Dom.setStyle(['refund_payment_amount', 'refund_payment_pay_max_amount'], 'display', '')

    Dom.get('refund_payment_amount_formated').innerHTML = money(0, Dom.get('currency_code').value)
    Dom.get('refund_payment_amount').value = '';
    Dom.get('refund_payment_amount').focus();


}

function update_add_payment_amount(o) {
    Dom.get('add_payment_amount_formated').innerHTML = money(o.value, Dom.get('currency_code').value)

}


function add_payment_pay_max_amount() {
    Dom.setStyle(['amount_paid_total', , 'show_other_amount_field'], 'display', '')
    Dom.setStyle(['add_payment_amount', 'add_payment_pay_max_amount'], 'display', 'none')

    Dom.get('add_payment_amount_formated').innerHTML = money(Dom.get('add_payment_max_amount').value, Dom.get('currency_code').value)
    Dom.get('add_payment_amount').value = Dom.get('add_payment_max_amount').value;


}

function add_payment_to_order(order_key, max_amount) {

    Dom.get('add_payment_reference').value = '';
    Dom.get('add_payment_max_amount').value = max_amount;
    Dom.get('add_payment_amount').value = max_amount;

    Dom.get('add_payment_amount_formated').innerHTML = money(max_amount, Dom.get('currency_code').value);

    Dom.removeClass(Dom.getElementsByClassName('item', 'button', 'add_payment_payment_account_container'), 'selected')
    Dom.removeClass(['add_payment_payment_method_creditcard', 'add_payment_payment_method_bank_transfer', 'add_payment_payment_method_paypal', 'add_payment_payment_method_cash', 'add_payment_payment_method_cheque', 'add_payment_payment_method_other'], 'selected')

    Dom.get('add_payment_method').value = '';
    Dom.get('add_payment_payment_account_key').value = ''
    can_submit_payment()

    region1 = Dom.getRegion('show_add_payment_to_order');
    region2 = Dom.getRegion('dialog_add_payment');

    var pos = [region1.right - region2.width, region1.top]
    Dom.setXY('dialog_add_payment', pos);
    dialog_add_payment.show()

}


function add_payment_change_account(payment_account_key) {


    var payment_account = Dom.get('add_payment_payment_account_' + payment_account_key)

    Dom.removeClass(Dom.getElementsByClassName('item', 'button', 'add_payment_payment_account_container'), 'selected')


    Dom.addClass('add_payment_payment_account_' + payment_account_key, 'selected')
    Dom.get('add_payment_payment_account_key').value = payment_account_key;


    Dom.removeClass(Dom.getElementsByClassName('item', 'button', 'type_of_payment'), 'selected')
    Dom.setStyle(Dom.getElementsByClassName('item', 'button', 'type_of_payment'), 'display', 'none')
    // alert(payment_account.id)
    var valid_payment_methods = payment_account.getAttribute('valid_payment_methods').split(",");
    var number_methods = 0;
    for (index = 0; index < valid_payment_methods.length; ++index) {
        number_methods++;
        if (index == 0) {
            Dom.addClass('add_payment_payment_method_' + valid_payment_methods[index], 'selected')
            Dom.get('add_payment_method').value = Dom.get('add_payment_payment_method_' + valid_payment_methods[index]).getAttribute('tag');
        }

        Dom.setStyle('add_payment_payment_method_' + valid_payment_methods[index], 'display', '')
    }






    if (number_methods > 1) {
        Dom.setStyle('payment_methods', 'display', '')
    } else {
        Dom.setStyle('payment_methods', 'display', 'none')
    }

    can_submit_payment()
}

function select_payment_method() {

    Dom.removeClass(Dom.getElementsByClassName('item', 'button', 'type_of_payment'), 'selected')

    Dom.addClass(this, 'selected')
    Dom.get('add_payment_method').value = this.getAttribute('tag');

    can_submit_payment()

}


function can_submit_payment() {
    if (Dom.get('add_payment_method').value != '' && Dom.get('add_payment_payment_account_key').value != '' && Dom.get('add_payment_reference').value != '') {
        Dom.removeClass('save_add_payment', 'disabled')
    } else {
        Dom.addClass('save_add_payment', 'disabled')

    }
}


function can_submit_refund() {
    if (Dom.get('refund_payment_method').value == 'online') {

        Dom.removeClass('save_refund_payment', 'disabled')

    } else {

        if (Dom.get('refund_payment_reference').value != '') {
            Dom.removeClass('save_refund_payment', 'disabled')
        } else {
            Dom.addClass('save_refund_payment', 'disabled')

        }
    }




}


function hide_add_payment() {
    dialog_add_payment.hide()
}


function hide_refund_payment() {
    dialog_refund_payment.hide()
}

function change_refund_payment(method) {
    Dom.removeClass(['refund_payment_online', 'refund_payment_manual'], 'selected')

    Dom.addClass('refund_payment_' + method, 'selected')
    Dom.get('refund_payment_method').value = method

    if (method == 'online') {
        Dom.setStyle('refund_payment_reference_tr', 'display', 'none')


    } else {
        Dom.setStyle('refund_payment_reference_tr', 'display', '')

    }
    can_submit_refund()

}


function add_credit_note_to_customer() {

    Dom.get('add_payment_reference').value = '';
    Dom.get('add_payment_max_amount').value = max_amount;
    Dom.get('add_payment_amount').value = max_amount;

    Dom.get('add_payment_amount_formated').innerHTML = money(max_amount, Dom.get('currency_code').value);

    Dom.removeClass(Dom.getElementsByClassName('item', 'button', 'add_payment_payment_account_container'), 'selected')
    Dom.removeClass(['add_payment_payment_method_creditcard', 'add_payment_payment_method_bank_transfer', 'add_payment_payment_method_paypal', 'add_payment_payment_method_cash', 'add_payment_payment_method_cheque', 'add_payment_payment_method_other'], 'selected')

    Dom.get('add_payment_method').value = '';
    Dom.get('add_payment_payment_account_key').value = ''
    can_submit_payment()

    region1 = Dom.getRegion('show_add_payment_to_order');
    region2 = Dom.getRegion('dialog_add_payment');

    var pos = [region1.right - region2.width, region1.top]
    Dom.setXY('dialog_add_payment', pos);
    dialog_add_payment.show()


}


function refund_payment(payment_key) {

    Dom.get('refund_payment_key').value = payment_key;

    if (Dom.get('to_pay_label_amount').value < 0) {

        refund_amount = Dom.get('to_pay_label_amount').value
    } else {
        refund_amount = Dom.get('payment_max_refund_amount_' + payment_key).value

    }

    if (Dom.get('payment_online_refund_' + payment_key).value == 'Yes') {
        Dom.get('refund_payment_method').value = 'online';

    } else {
        Dom.get('refund_payment_method').value = 'manual'

    }



    if (Dom.get('payment_online_refund_' + payment_key).value == 'Yes') {
        Dom.setStyle('refund_payment_method_tr', 'display', '')
        Dom.setStyle('refund_payment_reference_tr', 'display', 'none')
        Dom.addClass('refund_payment_online', 'selected')
        Dom.removeClass('refund_payment_manual', 'selected')

    } else {
        Dom.setStyle('refund_payment_method_tr', 'display', 'none')
        Dom.setStyle('refund_payment_reference_tr', 'display', '')
        Dom.removeClass('refund_payment_online', 'selected')
        Dom.addClass('refund_payment_manual', 'selected')

    }


    Dom.get('refund_payment_max_amount').value = Dom.get('payment_max_refund_amount_' + payment_key).value
    Dom.get('refund_payment_amount').value = refund_amount


    Dom.get('refund_payment_amount_formated').innerHTML = money(refund_amount, Dom.get('currency_code').value);


    region1 = Dom.getRegion('add_refund_' + payment_key);
    region2 = Dom.getRegion('dialog_refund_payment');

    var pos = [region1.right - region2.width, region1.top]
    Dom.setXY('dialog_refund_payment', pos);
    dialog_refund_payment.show()
    can_submit_refund()

}

function init_add_payment() {



    YAHOO.util.Event.addListener(Dom.getElementsByClassName('item', 'button', 'type_of_payment'), "click", select_payment_method);


    dialog_add_payment = new YAHOO.widget.Dialog("dialog_add_payment", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_add_payment.render();

    dialog_refund_payment = new YAHOO.widget.Dialog("dialog_refund_payment", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_refund_payment.render();


    dialog_add_credit_note_to_customer = new YAHOO.widget.Dialog("dialog_add_credit_note_to_customer", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_add_credit_note_to_customer.render();



}
Event.onDOMReady(init_add_payment);
