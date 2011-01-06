<?php
include_once('common.php');
?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

function change_block(){
ids=['population','geo','data'];
block_ids=['block_population','block_geo','block_data'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=customers-stats_view&value='+this.id ,{});
}


 function init(){

  init_search('customers_store');

Event.addListener(['population','geo','data'], "click",change_block);
}

YAHOO.util.Event.onDOMReady(init);


