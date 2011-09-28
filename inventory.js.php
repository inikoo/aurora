<?php
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

function init(){
init_search('parts');
init_search('locations');

}

YAHOO.util.Event.onDOMReady(init);
