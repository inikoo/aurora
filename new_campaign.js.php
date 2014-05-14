<?php
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;


function reset_new_campaign(){
	reset_edit_general('campaign')
}


function save_new_campaign(){

 save_new_general('campaign');
}


function validate_campaign_code(query) {
	validate_general('campaign', 'code', query);
}
function validate_campaign_name(query) {
	validate_general('campaign', 'name', query);
}
function validate_campaign_description(query) {
	validate_general('campaign', 'description', query);
}

function init(){

init_search('products_store');


validate_scope_data=
{
    'campaign':{
				'code':{'changed':false,'validated':false,'required':true,'group':1,'type':'item','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Code')?>'}],'name':'campaign_code','ar':'find','ar_request':'ar_deals.php?tipo=is_campaign_code_in_store&store_key='+Dom.get('store_key').value+'&query=', 'dbname':'Deal Campaign Code'}
				
				,'name':{'changed':false,'validated':false,'required':true,'group':1,'type':'item','name':'campaign_name','ar':false,'dbname':'Deal Campaign Name', 'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid name'}]}
				,'description':{'changed':false,'validated':false,'required':false,'group':1,'type':'item','name':'campaign_description','ar':false,'dbname':'Deal Campaign Description', 'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid description'}]}
				,'from':{'changed':false,'validated':false,'required':false,'group':1,'type':'item','name':'campaign_from','ar':false,'dbname':'Deal Campaign Valid From', 'validation':false}
				,'to':{'changed':false,'validated':false,'required':false,'group':1,'type':'item','name':'campaign_to','ar':false,'dbname':'Deal Campaign Valid To', 'validation':false}

		}
};
	
	

	
validate_scope_metadata={
    'campaign':{'type':'new','ar_file':'ar_edit_deals.php','key_name':'store_key', 'key':Dom.get('store_key').value}
    

};



    var campaign_code_oACDS = new YAHOO.util.FunctionDataSource(validate_campaign_code);
    campaign_code_oACDS.queryMatchContains = true;
    var campaign_code_oAutoComp = new YAHOO.widget.AutoComplete("campaign_code","campaign_code_Container", campaign_code_oACDS);
    campaign_code_oAutoComp.minQueryLength = 0; 
    campaign_code_oAutoComp.queryDelay = 0.1;

    var campaign_name_oACDS = new YAHOO.util.FunctionDataSource(validate_campaign_name);
    campaign_name_oACDS.queryMatchContains = true;
    var campaign_name_oAutoComp = new YAHOO.widget.AutoComplete("campaign_name","campaign_name_Container", campaign_name_oACDS);
    campaign_name_oAutoComp.minQueryLength = 0; 
    campaign_name_oAutoComp.queryDelay = 0.1;

    var campaign_description_oACDS = new YAHOO.util.FunctionDataSource(validate_campaign_description);
    campaign_description_oACDS.queryMatchContains = true;
    var campaign_description_oAutoComp = new YAHOO.widget.AutoComplete("campaign_description","campaign_description_Container", campaign_description_oACDS);
    campaign_description_oAutoComp.minQueryLength = 0; 
    campaign_description_oAutoComp.queryDelay = 0.1;

   YAHOO.util.Event.addListener('save_new_campaign', "click",save_new_campaign)


}

YAHOO.util.Event.onDOMReady(init);



