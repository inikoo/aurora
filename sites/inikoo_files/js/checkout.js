function choose_payment_account(payment_service_provider_code, payment_account_key) {

    Dom.get('payment_service_provider_code').value = payment_service_provider_code
    Dom.get('payment_account_key').value = payment_account_key


    Dom.removeClass(Dom.getElementsByClassName('payment_method_button', 'div', 'payment_chooser'), 'selected')



    Dom.addClass('payment_account_container_' + payment_service_provider_code, 'selected')
    Dom.addClass('confirm_payment', 'positive')

    Dom.setStyle('info_payment_account', 'display', '')

    Dom.get('info_payment_account').innerHTML = Dom.get('payment_account_info_' + payment_service_provider_code).innerHTML;


    if (payment_service_provider_code == 'Bank' || payment_service_provider_code == 'Cash') {
        Dom.setStyle('place_order', 'display', '')
        Dom.setStyle('confirm_payment', 'display', 'none')

    } else {
        Dom.setStyle('place_order', 'display', 'none')
        Dom.setStyle('confirm_payment', 'display', '')

    }


}

function confirm_payment() {

    if (Dom.hasClass('confirm_payment', 'waiting')) {
        return;
    }



    Dom.setStyle('info_payment_account', 'display', '')

    if (Dom.get('payment_service_provider_code').value == '') {
        Dom.get('info_payment_account').innerHTML = Dom.get('payment_account_not_selected').innerHTML;

    } else {

        var request = 'ar_edit_payments.php?tipo=create_payment&payment_account_key=' + Dom.get('payment_account_key').value + '&order_key=' + Dom.get('order_key').value


        Dom.get('confirm_payment_img').src = "art/loading.gif"
        Dom.addClass('confirm_payment', 'waiting')



        YAHOO.util.Connect.asyncRequest('POST', request, {
            success: function(o) {
                //alert(o.responseText)
                var r = YAHOO.lang.JSON.parse(o.responseText);
                if (r.state == 200) {

                    fill_payment_form(r.payment_service_provider_code, r.payment_data)
                    Dom.get(r.payment_service_provider_code + '_form').submit();

                } else {


                }
            }
        });

    }

}


function fill_payment_form(payment_service_provider_code, payment_data) {
    if (payment_service_provider_code == 'Sofort') {
        fill_Sofort_payment_form(payment_data)
    } else if (payment_service_provider_code == 'Paypal') {
        fill_Paypal_payment_form(payment_data)

    } else if (payment_service_provider_code == 'Worldpay') {
        fill_Worldpay_payment_form(payment_data)

    }

}






function fill_Worldpay_payment_form(payment_data) {

    Dom.get('Worldpay_form').action = payment_data.Payment_Account_URL_Link
    Dom.get('Worldpay_Payment_Account_ID').value = payment_data.Payment_Account_ID
    Dom.get('Worldpay_Payment_Account_Cart_ID').value = payment_data.Payment_Account_Cart_ID
    Dom.get('Worldpay_Order_Currency').value = payment_data.Payment_Currency_Code
    Dom.get('Worldpay_Customer_Main_Contact_Name').value = payment_data.Customer_Contact_Name
    Dom.get('Worldpay_Customer_Main_Plain_Email').value = payment_data.Customer_Main_Plain_Email
    Dom.get('Worldpay_Payment_Account_Business_Name').value = payment_data.Payment_Account_Business_Name
    Dom.get('Worldpay_Customer_Key').value = payment_data.Payment_Customer_Key
    Dom.get('Worldpay_Customer_Billing_Address_Line_1').value = payment_data.Billing_To_Line1
    Dom.get('Worldpay_Customer_Billing_Address_Line_2').value = payment_data.Billing_To_Line2
    Dom.get('Worldpay_Customer_Billing_Address_Line_3').value = payment_data.Billing_To_Line3
    Dom.get('Worldpay_Customer_Billing_Address_Town').value = payment_data.Billing_To_Town
    Dom.get('Worldpay_Customer_Billing_Address_Postal_Code').value = payment_data.Billing_To_Postal_Code
    Dom.get('Worldpay_Customer_Billing_Address_2_Alpha_Country_Code').value = payment_data.Billing_To_2_Alpha_Code

    Dom.get('Worldpay_Customer_Main_Plain_Telephone').value = payment_data.Customer_Main_Plain_Telephone
    Dom.get('Worldpay_Order_Balance_Total_Amount1').value = payment_data.Payment_Balance
    Dom.get('Worldpay_Order_Balance_Total_Amount2').value = payment_data.Payment_Balance
    Dom.get('Worldpay_Order_Balance_Total_Amount3').value = payment_data.Payment_Balance
    Dom.get('Worldpay_Description').value = payment_data.Description
    Dom.get('Worldpay_signature').value = payment_data.signature
    Dom.get('Worldpay_Test_Mode').value = '100'
    Dom.get('Worldpay_Payment_Service_Provider_Key').value = payment_data.Payment_Service_Provider_Key
    Dom.get('Worldpay_Payment_Key').value = payment_data.Payment_Key


}


function fill_Paypal_payment_form(payment_data) {

    Dom.get('Paypal_form').action = payment_data.Payment_Account_URL_Link


    Dom.get('Paypal_Payment_Account_Return_Link_Good').value = payment_data.Payment_Account_Return_Link_Good
    Dom.get('Paypal_Payment_Account_Return_Link_Bad').value = payment_data.Payment_Account_Return_Link_Bad
    Dom.get('Paypal_language_settings').value = payment_data.Language
    Dom.get('Paypal_Description').value = payment_data.Description
    Dom.get('Paypal_Order_Balance_Total_Amount').value = payment_data.Payment_Balance
    Dom.get('Paypal_Order_Public_ID').value = payment_data.Description
    Dom.get('Paypal_Payment_Account_Login').value = payment_data.Payment_Account_Login
    Dom.get('Paypal_Order_Currency').value = payment_data.Payment_Currency_Code
    Dom.get('Paypal_Payment_Key').value = payment_data.Payment_Key
    Dom.get('Paypal_first_name').value = payment_data.First_Name
    Dom.get('Paypal_last_name').value = payment_data.Last_Name
    Dom.get('Paypal_Customer_Billing_Address_2_Alpha_Country_Code').value = payment_data.xxx
    Dom.get('Paypal_Customer_Billing_Address_Line_1').value = payment_data.Billing_To_Line1
    Dom.get('Paypal_Customer_Billing_Address_Line_2').value = payment_data.Billing_To_Line2
    Dom.get('Paypal_Customer_Billing_Address_Town').value = payment_data.Billing_To_Line2
    Dom.get('Paypal_Customer_Billing_Address_Postal_Code').value = payment_data.xxx
    Dom.get('Paypal_Customer_Main_Plain_Email').value = payment_data.Customer_Main_Plain_Email
}


function fill_Sofort_payment_form(payment_data) {

    Dom.get('Sofort_form').action = payment_data.Payment_Account_URL_Link
    Dom.get('Sofort_Order_Balance_Total_Amount').value = payment_data.Payment_Balance
    Dom.get('Sofort_Order_Currency').value = payment_data.Payment_Currency_Code
    Dom.get('Sofort_Description').value = payment_data.Description
    Dom.get('Sofort_Description2').value = payment_data.Description2
    Dom.get('Sofort_Order_Customer_Name').value = payment_data.Customer_Name
    Dom.get('Sofort_Order_Billing_To_Country_2_Alpha_Code').value = payment_data.Billing_To_2_Alpha_Code
    Dom.get('Sofort_Payment_Account_ID').value = payment_data.Payment_Account_ID
    Dom.get('Sofort_Payment_Account_Login').value = payment_data.Payment_Account_Login
    Dom.get('Sofort_Payment_Random_String').value = payment_data.Payment_Random_String
    Dom.get('Sofort_Payment_Key').value = payment_data.Payment_Key

}







function init_checkout() {
    Event.addListener('confirm_payment', "click", confirm_payment);



}

YAHOO.util.Event.onDOMReady(init_checkout);
