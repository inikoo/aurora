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

function recapture_preview() {
    Dom.setStyle('recapture_preview_processing', 'display', '')
    Dom.setStyle('recapture_preview', 'display', 'none')
   request='ar_edit_sites.php?tipo=update_preview_snapshot&parent='+Dom.get('splinter_type').value+'&parent_key=' + Dom.get('splinter_key').value
  // alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            Dom.setStyle('recapture_preview_processing', 'display', 'none')
            Dom.setStyle('recapture_preview', 'display', '')
            Dom.get('capture_preview_date').innerHTML = ', ' + r.formated_date

            Dom.get('splinter_preview_snapshot').src = 'image.php?id=' + r.image_key

        }
    });
}

function init(){
 	var ids = ['description','images']; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
    
      YAHOO.util.Event.addListener('recapture_preview', "click",recapture_preview);


	validate_scope_data=
	{
		'page_splinter':{
	'source':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'html_editor','dbname':'Source','ar':false}
	}
};
	
	

	
validate_scope_metadata={
    'page_splinter':{'type':'edit','ar_file':'ar_edit_site.php','key_name':'page_splinter_key','key':Dom.get('splinter_key').value}
    

};

	
init_search('site');
	Event.addListener('save_edit_page_splinter', "click", save_page_splinter);
	Event.addListener('reset_edit_page_splinter', "click", reset_page_splinter);
	
	
}

YAHOO.util.Event.onDOMReady(init);





	