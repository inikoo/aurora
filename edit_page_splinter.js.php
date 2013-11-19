<?php  include_once('common.php');

 ?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

function change_block(e){
   
     Dom.setStyle(['description_block','images_block'],'display','none');
 	 Dom.get(this.id+'_block').style.display='';
	 Dom.removeClass(['description','images'],'selected');
	 Dom.addClass(this, 'selected');
//	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=location-edit&value='+this.id ,{});
   }


function save_page_splinter(){

}

function reset_page_splinter(){

}

function init(){
 	var ids = ['description','images']; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
    

	validate_scope_data=
	{
		'page_splinter':{
	'source':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'html_editor','dbname':'Source','ar':false}
	}
};
	
	

	
validate_scope_metadata={
    'page_splinter':{'type':'edit','ar_file':'ar_edit_site.php','key_name':'page_splinter_key','key':Dom.get('splinter_key').value}
    

};

	alert("x")
init_search('site');
	Event.addListener('save_edit_page_splinter', "click", save_page_splinter);
	Event.addListener('reset_edit_page_splinter', "click", reset_page_splinter);
	
	
}

YAHOO.util.Event.onDOMReady(init);





	