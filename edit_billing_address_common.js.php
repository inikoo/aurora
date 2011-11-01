


function display_new_billing_address(){
    Dom.setStyle('add_new_billing_address','display','none')
    Dom.setStyle('new_billing_address_table','display','')
}

function display_edit_billing_address(address_id){
    edit_address(address_id,'billing_')
    Dom.setStyle('new_billing_address_table','display','')
    Dom.setStyle('billing_tr_address_type','display','none')
    Dom.setStyle('billing_tr_address_function','display','none')
}

function hide_new_billing_address(){
    reset_address(false,'billing_')
    Dom.setStyle('add_new_billing_address','display','')
    Dom.setStyle('new_billing_address_table','display','none')
}

function post_create_address_function(r){
    hide_new_billing_address();
}


function init(){

    YAHOO.util.Event.addListener('add_new_billing_address', "click",display_new_billing_address );
    var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
	Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a","postal_regex"]}
	var Countries_AC = new YAHOO.widget.AutoComplete("billing_address_country", "billing_address_country_container", Countries_DS);
	Countries_AC.forceSelection = true; 
	Countries_AC.useShadow = true;
    Countries_AC.suffix='billing_';
    Countries_AC.prefix='billing_';
	Countries_AC.resultTypeList = false;
	Countries_AC.formatResult = countries_format_results;
	Countries_AC.itemSelectEvent.subscribe(onCountrySelected);
    var ids = ["billing_address_contact","billing_address_telephone","billing_address_country_code","billing_address_description","billing_address_country_d1","billing_address_country_d2","billing_address_town","billing_address_town_d2","billing_address_town_d1","billing_address_postal_code","billing_address_street","billing_address_internal","billing_address_building"]; 
    YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change,'billing_');
    YAHOO.util.Event.addListener(ids, "change",on_address_item_change,'billing_');
	//YAHOO.util.Event.addListener('billing_save_address_button', "click",save_address,{prefix:'billing_',subject:'Customer',subject_key:customer_id,type:'Billing'});
	YAHOO.util.Event.addListener('billing_reset_address_button', "click",hide_new_billing_address,'billing_');
}
YAHOO.util.Event.onDOMReady(init);