function display_new_billing_address() {
    Dom.get('billing_address_key').value = '';
    Dom.get('billing_address_fuzzy').value = 'Yes';

    Dom.get('billing_address_contact').value = '';
    Dom.get('billing_address_telephone').value = '';
    Dom.get('billing_address_internal').value = '';
    Dom.get('billing_address_building').value = '';
    Dom.get('billing_address_town').value = '';
    Dom.get('billing_address_town_d1').value = '';
    Dom.get('billing_address_town_d2').value = '';
    Dom.get('billing_address_postal_code').value = '';
    Dom.get('billing_address_country_d5').value = '';
    Dom.get('billing_address_country_d4').value = '';
    Dom.get('billing_address_country_d3').value = '';
    Dom.get('billing_address_country_d2').value = '';
    Dom.get('billing_address_country_d1').value = '';
    Dom.get('billing_address_country_d5_code').value = '';
    Dom.get('billing_address_country_d4_code').value = '';
    Dom.get('billing_address_country_d3_code').value = '';
    Dom.get('billing_address_country_d2_code').value = '';
    Dom.get('billing_address_country_d1_code').value = '';
    Dom.get('billing_address_town_code').value = '';
    Dom.get('billing_address_country_code').value = '';
    Dom.get('billing_address_country_2acode').value = '';

    Dom.get('billing_address_country').value = '';
    Dom.get('billing_address_street').value = '';


    Dom.setStyle(['add_new_billing_address', 'billing_address_showcase'], 'display', 'none')


    set_country('billing_', Dom.get('default_country_2alpha').value)

    Dom.setStyle('dialog_new_billing_address', 'display', '')

    if (Dom.get('close_edit_billing_address_dialog') != undefined) {
        Dom.setStyle('close_edit_billing_address_dialog', 'display', 'none')
    }


}

function display_edit_billing_address(address_id) {


    edit_address(address_id, 'billing_')
    // set_country( 'billing_',Dom.get('default_country_2alpha').value)
    Dom.setStyle(['add_new_billing_address', 'billing_address_showcase'], 'display', 'none')
    Dom.setStyle('dialog_new_billing_address', 'display', '')

    //  if (Dom.get('billing_address_country').value == '') Dom.get('billing_address_country').focus();
    // else Dom.get('billing_address_street').focus();
    if (Dom.get('close_edit_billing_address_dialog') != undefined) {
        Dom.setStyle('close_edit_billing_address_dialog', 'display', 'none')
    }


}

function hide_new_billing_address() {

    reset_address(false, 'billing_')
    Dom.setStyle(['add_new_billing_address', 'billing_address_showcase'], 'display', '')
    Dom.setStyle('dialog_new_billing_address', 'display', 'none')

    if (Dom.get('close_edit_billing_address_dialog') != undefined) {
        Dom.setStyle('close_edit_billing_address_dialog', 'display', '')
    }


}

function post_create_billing_address_function(r) {



    hide_new_billing_address();
    //  window.location.reload()
}



function init() {

    YAHOO.util.Event.addListener('add_new_billing_address', "click", display_new_billing_address);

    var ids = ["billing_address_contact", "billing_address_telephone", "billing_address_country_code", "billing_address_description", "billing_address_country_d1", "billing_address_country_d2", "billing_address_town", "billing_address_town_d2", "billing_address_town_d1", "billing_address_postal_code", "billing_address_street", "billing_address_internal", "billing_address_building"];
    YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change, 'billing_');
    YAHOO.util.Event.addListener(ids, "change", on_address_item_change, 'billing_');

    YAHOO.util.Event.addListener('billing_save_address_button', "click", save_address, {
        prefix: 'billing_',
        subject: 'Customer',
        subject_key: Dom.get('subject_key').value,

        type: 'Billing'
    });

    YAHOO.util.Event.addListener('billing_reset_address_button', "click", hide_new_billing_address, 'billing_');


}
YAHOO.util.Event.onDOMReady(init);
