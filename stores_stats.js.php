<?php
include_once('common.php');
?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;





function change_block(){
ids=['sales','grown','orders','customers'];
block_ids=['block_sales','block_grown','block_orders','block_customers'];

Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-stats_view&value='+this.id ,{});
}



 function init(){

    init_search('products');


  Event.addListener(['sales','grown','orders','customers'], "click",change_block);
}

YAHOO.util.Event.onDOMReady(init);


