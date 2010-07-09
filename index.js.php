<?php
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
function init(){
  search_scope='all';
     var store_name_oACDS = new YAHOO.util.FunctionDataSource(search_all);
     store_name_oACDS.queryMatchContains = true;
     var store_name_oAutoComp = new YAHOO.widget.AutoComplete(search_scope+"_search",search_scope+"_search_Container", store_name_oACDS);
     store_name_oAutoComp.minQueryLength = 0; 
     store_name_oAutoComp.queryDelay = 0.15;
   
   Event.addListener(search_scope+"_search", "keyup",search_events,search_scope)
      Event.addListener(search_scope+"_clean_search", "click",clear_search,search_scope);
      
}

YAHOO.util.Event.onDOMReady(init);
