<?
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../common.php');
?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;


YAHOO.util.Event.addListener(window, "load", function(){

		new YAHOO.inputEx.Form( { 
				fields: [ 
					{type: 'select', inputParams: {label: 'Title', name: 'title', selectValues: ['Mr','Mrs','Mme'] } },
					{inputParams: {label: 'Firstname', name: 'firstname', required: true, value:'Jacques' } }, 
					{inputParams: {label: 'Lastname', name: 'lastname', value:'Dupont' } }, 
					{type:'email', inputParams: {label: 'Email', name: 'email'}}, 
					{type:'url', inputParams: {label: 'Website',name:'website'}} 
				], 
				buttons: [{type: 'submit', value: 'Change'}], 	
				parentEl: 'container1' 
			});
	

		
    });
			


