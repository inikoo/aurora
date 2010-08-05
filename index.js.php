<?php
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;


function change_splinter(id){
var key=Dom.get(id).getAttribute('key');
	    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=home-display&value=' + escape(key),{},null );

}


function init(){
 
    
}

YAHOO.util.Event.onDOMReady(init);
