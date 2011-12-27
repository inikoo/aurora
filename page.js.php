<?php
include_once('common.php');


?>
 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;

function change_block(){
ids=['details','hits','visitors'];
block_ids=['block_details','block_hits','block_visitors'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=page-view&value='+this.id ,{});
}



function recapture_preview(){
Dom.setStyle('recapture_preview_processing','display','')
Dom.setStyle('recapture_preview','display','none')

  YAHOO.util.Connect.asyncRequest('POST','ar_edit_sites.php?tipo=update_page_preview_snapshot&id='+Dom.get('page_key').value,{
  success: function(o) {
 //  alert(o.responseText)
   var r = YAHOO.lang.JSON.parse(o.responseText);
   Dom.setStyle('recapture_preview_processing','display','none')
Dom.setStyle('recapture_preview','display','')
Dom.get('capture_preview_date').innerHTML=', '+r.formated_date
   
   Dom.get('page_preview_snapshot').src='image.php?id='+r.image_key
   
  }
  });
}

function recapture_page(){
  YAHOO.util.Connect.asyncRequest('POST','ar_edit_sites.php?tipo=update_page_preview_snapshot&id='+Dom.get('page_key').value,{
  success: function(o) {
   alert(o.responseText)
   var r = YAHOO.lang.JSON.parse(o.responseText);
   //Dom.get('page_preview_snapshot_image').src='image.php?id='+r.image_key
   
  }
  });
}


 function init(){

  init_search('site');
  
    YAHOO.util.Event.addListener('recapture_page', "click",recapture_page);
  YAHOO.util.Event.addListener('recapture_preview', "click",recapture_preview);

  
 Event.addListener(['details','hits','visitors'], "click",change_block);


  YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 
 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 


 }

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	

    });
