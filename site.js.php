<?php
include_once('common.php');


?>
 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;


 function init(){

  init_search('products_store');
 YAHOO.util.Event.addListener('details', "click",change_details,'site');



 }

YAHOO.util.Event.onDOMReady(init);
