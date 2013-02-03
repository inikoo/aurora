<?php
include_once('common.php');


?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;



var validate_scope_data=
{
    'corporation':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid HQ Name')?>'}],dbname:'HQ Name'},
	'currency':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'currency','validation':[{'regexp':"[a-z]{3}",'invalid_msg':'<?php echo _('Invalid Currency Code')?>'}],dbname:'HQ Currency'}

  }
  
};

var validate_scope_metadata={
'corporation':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'','key':false}

};



var todo_after_select_country= function(){
    new_store_changed()
    return;
}

function change_block(e){
    
}

function cancel_add_store(){
    Dom.get('new_code').value='';
    Dom.get('new_name').value='';
    Dom.get('address_country_code').value='';
    Dom.get('address_country').value='';

    hide_add_store_dialog(); 
}



function init(){

  



     var ids = ["description"]; 
     YAHOO.util.Event.addListener(ids, "click", change_block);
     YAHOO.util.Event.addListener('add_store', "click", show_add_store_dialog);

     var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
     Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a"]}
     var Countries_AC = new YAHOO.widget.AutoComplete("address_country", "address_country_container", Countries_DS);
     Countries_AC.useShadow = true;
     Countries_AC.resultTypeList = false;
     Countries_AC.formatResult = country_formatResult;
     Countries_AC.itemSelectEvent.subscribe(onCountrySelected);
     
     
     var Countries_AC = new YAHOO.widget.AutoComplete("currency", "currency_container", Countries_DS);
     Countries_AC.useShadow = true;
     Countries_AC.resultTypeList = false;
     Countries_AC.formatResult = country_formatResult;
     Countries_AC.itemSelectEvent.subscribe(onCountrySelected);
     

}



 

YAHOO.util.Event.onDOMReady(init);

