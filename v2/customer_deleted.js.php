<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2011 LW
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

function init(){
 init_search('customers_store');
}

YAHOO.util.Event.onDOMReady(init);

