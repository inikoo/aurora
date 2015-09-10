<?php
  //@author Migara Ekanayake
  //Copyright (c) 2011 Inikoo
include_once('common.php');

//$scope='customer';
//$action_after_create='continue';

//$store_key=$_REQUEST['store_key'];

//print "var scope='$scope';\n";
print "var store_key='1';\n";
//print "var action_after_create='$action_after_create';\n";
?>
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

var validate_scope_data=
{
    'custom_field':{'User_Handle':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':true,'group':0,'type':'item', 'name':'User_Handle', 'dbname':'User Handle'}
					,'User_Password':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'User_Password', 'dbname':'User Password'}
					,'User_Active':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'User_Active', 'dbname':'User Active'}
					,'User_Alias':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'User_Alias', 'dbname':'User Alias'}
					,'User_Created':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'User_Created', 'dbname':'User Created'}
					,'User_Type':{'inputed':false,'validated':false,'regexp':"[^\\s]+",'required':false,'group':0,'type':'item', 'name':'User_Type', 'dbname':'User Type'}
}}

var validate_scope_metadata={
'custom_field':{'type':'new','ar_file':'ar_edit_contacts.php','key_name':'store_key','key':store_key}
};


function change_block(o){
	var buttons=Dom.getElementsByClassName('splinter_buttons', 'li', 'buttons');
	var panes=Dom.getElementsByClassName('pane', 'div', 'content');

	Dom.removeClass(buttons,'active');
	Dom.addClass(o,'active');
	Dom.setStyle(panes,'display','none');
	//alert(o.getAttribute('key'))
	Dom.setStyle('pane_'+o.getAttribute('key'),'display','');
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=home-display&value='+escape(o.getAttribute('key')),{});

}

function validate_User_Handle (query) {
	alert(query);
	validate_general('custom_field', 'User_Handle', query)
	
};
function save_new_super_user()
{
	save_new_general('custom_field');
}

function cancel_new_super_user(){
	window.location='customers.php';
}

function change_allow(o,key,value){

	Dom.get(key).value=value;
	Dom.removeClass(Dom.getElementsByClassName('option', 'span', o.parentNode ),'selected');
	Dom.addClass(o,'selected');

}

//MYB1047125B
function init(){

//	Event.addListener("save_new_super_user", "click", save_new_super_user , true);
	Event.addListener("cancel_new_super_user", "click", cancel_new_super_user , true);
	
	alert ("xxx")
		var superuser_handle_oACDS = new YAHOO.util.FunctionDataSource(validate_User_Handle);
	superuser_handle_oACDS.queryMatchContains = true;
	var superuser_handle_oAutoComp = new YAHOO.widget.AutoComplete("User_Handle","User_Handle_Container", superuser_handle_oACDS);
	superuser_handle_oAutoComp.minQueryLength = 0; 
	superuser_handle_oAutoComp.queryDelay = 0.75;


} 
YAHOO.util.Event.onDOMReady(init);