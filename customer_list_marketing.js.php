<?php
include_once('common.php');
?>

var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

function change_block(e){
    var ids = ["metrics","newsletter","email","web_internal","web","other"]; 
    var block_ids = ["block_metrics","block_newsletter","block_email","block_web_internal","block_web","block_other"];
	Dom.setStyle(block_ids,'display','none');
	Dom.setStyle('block_'+this.id,'display','');
	Dom.removeClass(ids,'selected');
	Dom.addClass(this, 'selected');
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=marketing-view&value='+this.id ,{});
}



  
function init(){


    var ids = ["metrics","newsletter","email","web_internal","web","other"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
   
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

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {trigger:"filter_name1"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });

