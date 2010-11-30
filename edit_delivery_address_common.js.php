function display_new_delivery_address(){
  
    edit_address(0,'delivery_');
}

function init(){

    YAHOO.util.Event.addListener('add_new_delivery_address', "click",display_new_delivery_address );
    var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
	Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a","postal_regex"]}
	var Countries_AC = new YAHOO.widget.AutoComplete("delivery_address_country", "delivery_address_country_container", Countries_DS);
	Countries_AC.forceSelection = true; 
	Countries_AC.useShadow = true;
    Countries_AC.suffix='delivery_';
	Countries_AC.resultTypeList = false;
	Countries_AC.formatResult = countries_format_results;
	Countries_AC.itemSelectEvent.subscribe(onCountrySelected);


    var ids = ["delivery_address_description","delivery_address_country_d1","delivery_address_country_d2","delivery_address_town","delivery_address_town_d2","delivery_address_town_d1","delivery_address_postal_code","delivery_address_street","delivery_address_internal","delivery_address_building"]; 
    YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change,'delivery_');
    YAHOO.util.Event.addListener(ids, "change",on_address_item_change,'delivery_');
	YAHOO.util.Event.addListener('delivery_save_address_button', "click",save_address,{prefix:'delivery_',subject:'Customer',subject_key:customer_id,type:'Delivery'});
	YAHOO.util.Event.addListener('delivery_reset_address_button', "click",reset_address,'delivery_');



}
YAHOO.util.Event.onDOMReady(init);