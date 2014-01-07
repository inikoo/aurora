<?php
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;

var validate_scope_data;
var validate_scope_metadata;


    



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {





	
	};
    });



function validate_deal_code(query){
alert(query)
 validate_general('deal','code',unescape(query));
}

function validate_deal_name(query){
 validate_general('deal','name',unescape(query));
}
function validate_deal_description(query){
 validate_general('deal','description',unescape(query));
}



function init(){
var validate_scope_data=
{
    'deal':{
	'description':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Deal_Description','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Offer Description')?>'}]},
	'name':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Customer_Main_Contact_Name','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Offer Name')?>'}]},
	'code':{'ar':'find','ar_request':'ar_assets.php?tipo=code_in_other_deal&deal_key='+Dom.get('deal_key').value+'&store_key='+Dom.get('store_key').value+'&query=','changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Deal_Code','validation':false}
}  
};
var validate_scope_metadata={
'deal':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'deal_key','key':Dom.get('deal_key').value}

};
 var deal_code_oACDS = new YAHOO.util.FunctionDataSource(validate_deal_code);
    deal_code_oACDS.queryMatchContains = true;
    var deal_code_oAutoComp = new YAHOO.widget.AutoComplete("deal_code","deal_code_Container", deal_code_oACDS);
    deal_code_oAutoComp.minQueryLength = 0; 
    deal_code_oAutoComp.queryDelay = 0.1;
    
     var deal_name_oACDS = new YAHOO.util.FunctionDataSource(validate_deal_name);
    deal_name_oACDS.queryMatchContains = true;
    var deal_name_oAutoComp = new YAHOO.widget.AutoComplete("deal_name","deal_name_Container", deal_name_oACDS);
    deal_name_oAutoComp.minQueryLength = 0; 
    deal_name_oAutoComp.queryDelay = 0.1;
    
    
     var deal_description_oACDS = new YAHOO.util.FunctionDataSource(validate_deal_description);
    deal_description_oACDS.queryMatchContains = true;
    var deal_description_oAutoComp = new YAHOO.widget.AutoComplete("Deal_Description","Deal_Description_Container", deal_description_oACDS);
    deal_description_oAutoComp.minQueryLength = 0; 
    deal_description_oAutoComp.queryDelay = 0.1;




init_search('products_store');





}

YAHOO.util.Event.onDOMReady(init);



YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });



