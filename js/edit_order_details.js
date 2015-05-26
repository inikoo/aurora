function show_dialog_quick_edit_Order_Customer_Fiscal_Name() {
    region1 = Dom.getRegion('order_customer_fiscal_name_label');
    region2 = Dom.getRegion('dialog_quick_edit_Order_Customer_Fiscal_Name');
    var pos = [region1.left, region1.bottom]
    Dom.setXY('dialog_quick_edit_Order_Customer_Fiscal_Name', pos);
    dialog_quick_edit_Order_Customer_Fiscal_Name.show()
    Dom.get('Order_Customer_Fiscal_Name').focus()
}

function cancel_quick_edit_Order_Customer_Fiscal_Name() {
    Dom.get('Order_Customer_Fiscal_Name').value = Dom.get('Order_Customer_Fiscal_Name').getAttribute('ovalue')
    dialog_quick_edit_Order_Customer_Fiscal_Name.hide()

}

function show_dialog_quick_edit_Order_Customer_Name() {
    region1 = Dom.getRegion('order_customer_name_label');
    region2 = Dom.getRegion('dialog_quick_edit_Order_Customer_Name');
    var pos = [region1.left, region1.bottom]
    Dom.setXY('dialog_quick_edit_Order_Customer_Name', pos);
    dialog_quick_edit_Order_Customer_Name.show()
    Dom.get('Order_Customer_Name').focus()
}

function cancel_quick_edit_Order_Customer_Name() {
    Dom.get('Order_Customer_Name').value = Dom.get('Order_Customer_Name').getAttribute('ovalue')
    dialog_quick_edit_Order_Customer_Name.hide()

}

function show_dialog_quick_edit_Order_Customer_Contact_Name() {
    region1 = Dom.getRegion('order_customer_contact_name_label');
    region2 = Dom.getRegion('dialog_quick_edit_Order_Customer_Contact_Name');
    var pos = [region1.left, region1.bottom]
    Dom.setXY('dialog_quick_edit_Order_Customer_Contact_Name', pos);
    dialog_quick_edit_Order_Customer_Contact_Name.show()
    Dom.get('Order_Customer_Contact_Name').focus()
}

function cancel_quick_edit_Order_Customer_Contact_Name() {
    Dom.get('Order_Customer_Contact_Name').value = Dom.get('Order_Customer_Contact_Name').getAttribute('ovalue')
    dialog_quick_edit_Order_Customer_Contact_Name.hide()

}


function show_dialog_quick_edit_Order_Customer_Telephone() {
    region1 = Dom.getRegion('order_customer_telephone_label');
    region2 = Dom.getRegion('dialog_quick_edit_Order_Customer_Telephone');
    var pos = [region1.left, region1.bottom]
    Dom.setXY('dialog_quick_edit_Order_Customer_Telephone', pos);
    dialog_quick_edit_Order_Customer_Telephone.show()
    Dom.get('Order_Customer_Telephone').focus()
}

function cancel_quick_edit_Order_Customer_Telephone() {
    Dom.get('Order_Customer_Telephone').value = Dom.get('Order_Customer_Telephone').getAttribute('ovalue')
    dialog_quick_edit_Order_Customer_Telephone.hide()

}


function show_dialog_quick_edit_Order_Customer_Email() {
    region1 = Dom.getRegion('order_customer_email_label');
    region2 = Dom.getRegion('dialog_quick_edit_Order_Customer_Email');
    var pos = [region1.left, region1.bottom]
    Dom.setXY('dialog_quick_edit_Order_Customer_Email', pos);
    dialog_quick_edit_Order_Customer_Email.show()
    Dom.get('Order_Customer_Email').focus()
}

function cancel_quick_edit_Order_Customer_Email() {
    Dom.get('Order_Customer_Email').value = Dom.get('Order_Customer_Email').getAttribute('ovalue')
    dialog_quick_edit_Order_Customer_Email.hide()

}






function save_quick_edit(e, field) {
    request = 'ar_edit_orders.php?tipo=update_order&order_key=' + Dom.get('order_key').value + '&key=' + field + '&value=' + Dom.get('Order_' + field).value;

    Dom.setStyle(field + '_wait', 'display', '')
    Dom.setStyle(['save_' + field, 'reset_' + field], 'display', 'none')

    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {
           // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                Dom.setStyle(field + '_wait', 'display', 'none')
                Dom.setStyle(['save_' + field, 'reset_' + field], 'display', '')

                if (r.okey == 'Customer_Fiscal_Name') {
                    Dom.get('Order_Customer_Fiscal_Name').value = r.value
                    Dom.get('Order_Customer_Fiscal_Name').setAttribute('ovalue', r.value)
                    Dom.get('order_customer_fiscal_name').innerHTML = r.value;
                    dialog_quick_edit_Order_Customer_Fiscal_Name.hide()

                } else if (r.okey == 'Customer_Name') {
                    Dom.get('Order_Customer_Name').value = r.value
                    Dom.get('Order_Customer_Name').setAttribute('ovalue', r.value)
                    Dom.get('order_customer_name').innerHTML = r.value;
                    Dom.get('customer_name').innerHTML = r.value;
                    dialog_quick_edit_Order_Customer_Name.hide()

                } else if (r.okey == 'Customer_Contact_Name') {
                    Dom.get('Order_Customer_Contact_Name').value = r.value
                    Dom.get('Order_Customer_Contact_Name').setAttribute('ovalue', r.value)
                    Dom.get('order_customer_contact_name').innerHTML = r.value;
                    Dom.get('customer_contact_name').innerHTML = r.value;
                    dialog_quick_edit_Order_Customer_Contact_Name.hide()

                } else if (r.okey == 'Customer_Telephone') {
                    Dom.get('Order_Customer_Telephone').value = r.value
                    Dom.get('Order_Customer_Telephone').setAttribute('ovalue', r.value)
                    Dom.get('order_customer_telephone').innerHTML = r.value;
                    dialog_quick_edit_Order_Customer_Telephone.hide()


                } else if (r.okey == 'Customer_Email') {
                    Dom.get('Order_Customer_Email').value = r.value
                    Dom.get('Order_Customer_Email').setAttribute('ovalue', r.value)
                    Dom.get('order_customer_email').innerHTML = r.value;
                    dialog_quick_edit_Order_Customer_Email.hide()

                }
                //alert(r.billing_to)
                Dom.get('billing_address').innerHTML = r.billing_to
                Dom.get('delivery_address').innerHTML = r.ship_to

            }

        },
        failure: function(o) {
            // alert(o.statusText);
        },
        scope: this
    });

}


function init_edit_orders_details() {

    dialog_quick_edit_Order_Customer_Fiscal_Name = new YAHOO.widget.Dialog("dialog_quick_edit_Order_Customer_Fiscal_Name", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_quick_edit_Order_Customer_Fiscal_Name.render();

    dialog_quick_edit_Order_Customer_Name = new YAHOO.widget.Dialog("dialog_quick_edit_Order_Customer_Name", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_quick_edit_Order_Customer_Name.render();

    dialog_quick_edit_Order_Customer_Contact_Name = new YAHOO.widget.Dialog("dialog_quick_edit_Order_Customer_Contact_Name", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_quick_edit_Order_Customer_Contact_Name.render();

    dialog_quick_edit_Order_Customer_Telephone = new YAHOO.widget.Dialog("dialog_quick_edit_Order_Customer_Telephone", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_quick_edit_Order_Customer_Telephone.render();

    dialog_quick_edit_Order_Customer_Email = new YAHOO.widget.Dialog("dialog_quick_edit_Order_Customer_Email", {

        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_quick_edit_Order_Customer_Email.render();



    Event.addListener("update_customer_fiscal_name", "click", show_dialog_quick_edit_Order_Customer_Fiscal_Name);
    Event.addListener("reset_Customer_Fiscal_Name", "click", cancel_quick_edit_Order_Customer_Fiscal_Name);
    Event.addListener("save_Customer_Fiscal_Name", "click", save_quick_edit, 'Customer_Fiscal_Name');

    Event.addListener("update_customer_name", "click", show_dialog_quick_edit_Order_Customer_Name);
    Event.addListener("reset_Customer_Name", "click", cancel_quick_edit_Order_Customer_Name);
    Event.addListener("save_Customer_Name", "click", save_quick_edit, 'Customer_Name');

    Event.addListener("update_customer_contact_name", "click", show_dialog_quick_edit_Order_Customer_Contact_Name);
    Event.addListener("reset_Customer_Contact_Name", "click", cancel_quick_edit_Order_Customer_Contact_Name);
    Event.addListener("save_Customer_Contact_Name", "click", save_quick_edit, 'Customer_Contact_Name');


    Event.addListener("update_customer_telephone", "click", show_dialog_quick_edit_Order_Customer_Telephone);
    Event.addListener("reset_Customer_Telephone", "click", cancel_quick_edit_Order_Customer_Telephone);
    Event.addListener("save_Customer_Telephone", "click", save_quick_edit, 'Customer_Telephone');


    Event.addListener("update_customer_email", "click", show_dialog_quick_edit_Order_Customer_Email);
    Event.addListener("reset_Customer_Email", "click", cancel_quick_edit_Order_Customer_Email);
    Event.addListener("save_Customer_Email", "click", save_quick_edit, 'Customer_Email');






}



YAHOO.util.Event.onDOMReady(init_edit_orders_details);
