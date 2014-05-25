function display_new_delivery_address() {
    Dom.get('delivery_address_key').value = '';
    Dom.get('delivery_address_fuzzy').value = 'Yes';

    Dom.get('delivery_address_contact').value = '';
    Dom.get('delivery_address_telephone').value = '';
    Dom.get('delivery_address_internal').value = '';
    Dom.get('delivery_address_building').value = '';
    Dom.get('delivery_address_town').value = '';
    Dom.get('delivery_address_town_d1').value = '';
    Dom.get('delivery_address_town_d2').value = '';
    Dom.get('delivery_address_postal_code').value = '';
    Dom.get('delivery_address_country_d5').value = '';
    Dom.get('delivery_address_country_d4').value = '';
    Dom.get('delivery_address_country_d3').value = '';
    Dom.get('delivery_address_country_d2').value = '';
    Dom.get('delivery_address_country_d1').value = '';
    Dom.get('delivery_address_country_d5_code').value = '';
    Dom.get('delivery_address_country_d4_code').value = '';
    Dom.get('delivery_address_country_d3_code').value = '';
    Dom.get('delivery_address_country_d2_code').value = '';
    Dom.get('delivery_address_country_d1_code').value = '';
    Dom.get('delivery_address_town_code').value = '';
    Dom.get('delivery_address_country_code').value = '';
    Dom.get('delivery_address_country_2acode').value = '';

    Dom.get('delivery_address_country').value = '';
    Dom.get('delivery_address_street').value = '';


    Dom.setStyle(['add_new_delivery_address', 'delivery_address_showcase', 'delivery_address_components'], 'display', 'none')



    Dom.setStyle('dialog_new_delivery_address', 'display', '')
    Dom.get('delivery_address_country').focus();

}

function display_edit_delivery_address(address_id) {


    edit_address(address_id, 'delivery_')
    Dom.setStyle(['add_new_delivery_address', 'delivery_address_showcase'], 'display', 'none')
    Dom.setStyle('dialog_new_delivery_address', 'display', '')

    if (Dom.get('delivery_address_country').value == '') Dom.get('delivery_address_country').focus();
    else Dom.get('delivery_address_street').focus();

}

function hide_new_delivery_address() {


    reset_address(false, 'delivery_')
    Dom.setStyle(['add_new_delivery_address', 'delivery_address_showcase'], 'display', '')
    Dom.setStyle('dialog_new_delivery_address', 'display', 'none')
}

function post_create_delivery_address_function(r) {

    // hide_new_delivery_address();
    //  window.location.reload()
}


function init() {

    YAHOO.util.Event.addListener('add_new_delivery_address', "click", display_new_delivery_address);
    var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
    Countries_DS.responseSchema = {
        fields: ["id", "name", "code", "code2a", "postal_regex"]
    }
    var Countries_AC = new YAHOO.widget.AutoComplete("delivery_address_country", "delivery_address_country_container", Countries_DS);
    Countries_AC.forceSelection = true;
    Countries_AC.useShadow = true;
    Countries_AC.suffix = 'delivery_';
    Countries_AC.prefix = 'delivery_';
    Countries_AC.resultTypeList = false;
    Countries_AC.formatResult = countries_format_results;
    Countries_AC.itemSelectEvent.subscribe(onCountrySelected);
    var ids = ["delivery_address_contact", "delivery_address_telephone", "delivery_address_country_code", "delivery_address_description", "delivery_address_country_d1", "delivery_address_country_d2", "delivery_address_town", "delivery_address_town_d2", "delivery_address_town_d1", "delivery_address_postal_code", "delivery_address_street", "delivery_address_internal", "delivery_address_building"];
    YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change, 'delivery_');
    YAHOO.util.Event.addListener(ids, "change", on_address_item_change, 'delivery_');

    YAHOO.util.Event.addListener('delivery_save_address_button', "click", save_address, {
        prefix: 'delivery_',
        subject: 'Customer',
        subject_key: customer_id,
        type: 'Delivery'
    });

    YAHOO.util.Event.addListener('delivery_reset_address_button', "click", hide_new_delivery_address, 'delivery_');
}
YAHOO.util.Event.onDOMReady(init);
