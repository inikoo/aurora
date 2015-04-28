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

function save_quick_edit_Order_Customer_Fiscal_Name() {
    request = 'ar_edit_orders.php?tipo=update_order&order_key=' + Dom.get('order_key').value + '&key=Customer_Fiscal_Name&value=' + Dom.get('Order_Customer_Fiscal_Name').value;


    YAHOO.util.Connect.asyncRequest('GET', request, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

                Dom.get('Order_Customer_Fiscal_Name').value == r.value
                Dom.get('Order_Customer_Fiscal_Name').setAttribute('ovalue', r.value)
                Dom.get('order_customer_fiscal_name').innerHTML = r.value;
                dialog_quick_edit_Order_Customer_Fiscal_Name.hide()

                Dom.get('billing_address').innerHTML = r.billing_to

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
    
     
    
    Event.addListener("update_customer_fiscal_name", "click", show_dialog_quick_edit_Order_Customer_Fiscal_Name);
    Event.addListener("close_quick_edit_customer_fiscal_name", "click", cancel_quick_edit_Order_Customer_Fiscal_Name);
    Event.addListener("save_quick_edit_customer_fiscal_name", "click", save_quick_edit_Order_Customer_Fiscal_Name);

  


}



YAHOO.util.Event.onDOMReady(init_edit_orders_details);
