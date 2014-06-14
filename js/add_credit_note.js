var dialog_add_credit_note;

function can_save_add_credit_note() {

    Dom.setStyle(['add_credit_note_error_fill_values', 'add_credit_note_error_fill_description', 'add_credit_note_error_fill_amount'], 'display', 'none')

    if (Dom.get('add_credit_note_description').value == '' || Dom.get('add_credit_note_total').value == 0) {
        Dom.addClass(['add_credit_note_customer_account', 'add_credit_note_other_payment_account'], 'disabled')
    } else {
        Dom.removeClass(['add_credit_note_customer_account', 'add_credit_note_other_payment_account'], 'disabled')
    }

}

function change_tax_category_add_credit(o) {

    Dom.removeClass(Dom.getElementsByClassName('item', 'button', 'add_credit_note_tax_categories_options'), 'selected')
    Dom.addClass(o, 'selected');
    Dom.get('add_credit_note_tax_rate').value = o.getAttribute('rate')
    Dom.get('add_credit_note_tax_code').value = o.getAttribute('tax_category_code')

    add_credit_note_net_changed()

}

function change_tax_category_add_credit_with_rate(o) {

    Dom.removeClass(Dom.getElementsByClassName('item', 'button', 'add_credit_note_tax_categories_with_rate_options'), 'selected')
    Dom.addClass(o, 'selected');
    Dom.get('add_credit_note_tax_code_only_tax').value = o.getAttribute('tax_category_code')
    add_credit_note_net_changed()

}


function calculate_add_credit_note_total() {

    var net = parseFloat(Dom.get('add_credit_note_net_amount').value);
    var tax = parseFloat(Dom.get('add_credit_note_tax_amount').value);

    if (isNaN(net)) net = 0;
    if (isNaN(tax)) tax = 0;



    total = net + tax
    Dom.get('add_credit_note_total').value = total

    Dom.get('add_credit_note_total_formated').innerHTML = money(total, Dom.get('currency_code').value)

    if (total < 0) {
        Dom.setStyle('add_note_warning_negative_amount', 'display', '')
    } else {
        Dom.setStyle('add_note_warning_negative_amount', 'display', 'none')

    }
    can_save_add_credit_note()
}

function add_credit_note_net_changed() {
    var net = parseFloat(Dom.get('add_credit_note_net_amount').value);
    if (isNaN(net)) net = 0;
    Dom.get('add_credit_note_tax_amount').value = net * Dom.get('add_credit_note_tax_rate').value
    calculate_add_credit_note_total()
}

function add_credit_note_tax_changed() {


    calculate_add_credit_note_total()
}


function credit_note_only_tax() {
    Dom.get('add_credit_note_type').value = 'only_tax';

    Dom.get('add_credit_note_net_amount').value = '';
    Dom.get('add_credit_note_tax_amount').value = '';
    calculate_add_credit_note_total()
    Dom.setStyle(['add_credit_note_net_amount_tr', 'credit_note_not_only_tax_tr'], 'display', 'none')
    Dom.setStyle(['add_credit_note_tax_amount_tr', 'credit_note_only_tax_tr'], 'display', '')

}

function credit_note_not_only_tax() {
    Dom.get('add_credit_note_type').value = 'normal';

    Dom.get('add_credit_note_net_amount').value = 0;
    Dom.get('add_credit_note_tax_amount').value = '';
    calculate_add_credit_note_total()
    Dom.setStyle(['add_credit_note_net_amount_tr', 'credit_note_not_only_tax_tr'], 'display', '')
    Dom.setStyle(['add_credit_note_tax_amount_tr', 'credit_note_only_tax_tr'], 'display', 'none')
    
    
}

function add_credit_note_show_errors() {

    if (Dom.get('add_credit_note_description').value == '' && Dom.get('add_credit_note_total').value == 0) {
        Dom.setStyle('add_credit_note_error_fill_values', 'display', '')
    } else if (Dom.get('add_credit_note_description').value == '') {
        Dom.setStyle('add_credit_note_error_fill_description', 'display', '')
    } else if (Dom.get('add_credit_note_total').value == 0) {
        Dom.setStyle('add_credit_note_error_fill_amount', 'display', '')
    }

}

function add_credit_note(payment_account) {

    if (Dom.hasClass('add_credit_note_' + payment_account, 'disabled')) {
        add_credit_note_show_errors()
    } else {


        if (Dom.get('add_credit_note_type').value == 'only_tax') {
            var tax_category_code = Dom.get('add_credit_note_tax_code_only_tax').value
        } else {
            var tax_category_code = Dom.get('add_credit_note_tax_code').value

        }


        var request = 'ar_edit_orders.php?tipo=new_refund&net=' + Dom.get('add_credit_note_net_amount').value + "&tax=" + Dom.get('add_credit_note_tax_amount').value + "&tax_category_code=" + tax_category_code + "&customer_key=" + Dom.get('customer_key').value+'&description='+Dom.get('add_credit_note_description').value+'&payment_account='+payment_account
        alert(request);
        YAHOO.util.Connect.asyncRequest('POST', request, {
            success: function(o) {
                alert(o.responseText)
                var r = YAHOO.lang.JSON.parse(o.responseText);

               

                if (r.state == 200) {
                   
                } else {}
                
            }
        });

    }

}

function close_dialog_add_credit_note(){
                    dialog_add_credit_note.hide()

}

function show_add_credit_note(){
 region1 = Dom.getRegion('add_credit_note');
                    region2 = Dom.getRegion('dialog_add_credit_note');
                    var pos = [region1.right - region2.width+350, region1.bottom]
                    Dom.setXY('dialog_add_credit_note', pos);

                    Dom.get('add_credit_note_description').value = ''
                    Dom.get('add_credit_note_net_amount').value = ''
                    Dom.get('add_credit_note_tax_amount').value = ''
                    
                    credit_note_not_only_tax()
                    
                    dialog_add_credit_note.show()
                    
                    
                    Dom.get('add_credit_note_description').focus()

}

function init_add_credit_note(){
dialog_add_credit_note = new YAHOO.widget.Dialog("dialog_add_credit_note", {visible : false,close:true,underlay: "none",draggable:false});
dialog_add_credit_note.render();
Event.addListener("add_credit_note", "click", show_add_credit_note , true);



}

YAHOO.util.Event.onDOMReady(init_add_credit_note);
