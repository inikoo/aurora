var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_add_payment;

function add_payment(parent, parent_key, max_amount) {

    if (parent == 'order') {
        add_payment_to_order(parent_key, max_amount)
    }

}

function add_payment_show_other_amount_field(){

Dom.setStyle(['amount_paid_total',,'show_other_amount_field'],'display','none')
Dom.setStyle(['amount_paid','pay_all'],'display','')
}

function add_payment_to_order(order_key, max_amount) {

    Dom.get('add_payment_max_amount').value = max_amount;
    Dom.get('add_payment_amount_formated').innerHTML = money(max_amount, Dom.get('currency_code').value);

    Dom.removeClass(Dom.getElementsByClassName('item', 'button', 'add_payment_payment_account_container'), 'selected')
    Dom.removeClass(['add_payment_payment_method_creditcard', 'add_payment_payment_method_bank_transfer', 'add_payment_payment_method_paypal', 'add_payment_payment_method_cash', 'add_payment_payment_method_cheque', 'add_payment_payment_method_other'], 'selected')

    Dom.get('add_payment_method').value = '';
    Dom.get('add_payment_payment_account_key').value = ''
    can_submit_payment()
    
    region1 = Dom.getRegion('show_add_payment_to_order'); 
    region2 = Dom.getRegion('dialog_add_payment'); 

	var pos =[region1.right-region2.width,region1.top]
	Dom.setXY('dialog_add_payment', pos);
    dialog_add_payment.show()
    
}


function add_payment_change_account(payment_account_key) {

    Dom.removeClass(Dom.getElementsByClassName('item', 'button', 'add_payment_payment_account_container'), 'selected')


    Dom.addClass('add_payment_payment_account_' + payment_account_key, 'selected')
    Dom.get('add_payment_payment_account_key').value = this.id;

    Dom.removeClass(['add_payment_payment_method_creditcard', 'add_payment_payment_method_bank_transfer', 'add_payment_payment_method_paypal', 'add_payment_payment_method_cash', 'add_payment_payment_method_cheque', 'add_payment_payment_method_other'], 'selected')
    Dom.get('add_payment_method').value = '';


    can_submit_payment()
}

function select_payment_method() {

    Dom.removeClass(['add_payment_payment_method_creditcard', 'add_payment_payment_method_bank_transfer', 'add_payment_payment_method_paypal', 'add_payment_payment_method_cash', 'add_payment_payment_method_cheque', 'add_payment_payment_method_other'], 'selected')

    Dom.addClass(this, 'selected')
    Dom.get('add_payment_method').value = this.id;

    can_submit_payment()

}


function can_submit_payment() {
    if (Dom.get('add_payment_method').value != '' && Dom.get('add_payment_payment_account_key').value != '') {
        Dom.removeClass('save_add_payment', 'disabled')
    } else {
        Dom.addClass('save_add_payment', 'disabled')

    }
}


function init_add_payment() {
    YAHOO.util.Event.addListener(['add_payment_payment_method_creditcard', 'add_payment_payment_method_bank_transfer', 'add_payment_payment_method_paypal', 'add_payment_payment_method_cash', 'add_payment_payment_method_cheque', 'add_payment_payment_method_other'], "click", select_payment_method);


    dialog_add_payment = new YAHOO.widget.Dialog("dialog_add_payment", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_add_payment.render();

}
Event.onDOMReady(init_add_payment);
