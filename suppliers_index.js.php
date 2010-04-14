<?php
include_once('common.php');
?>

    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
Event.addListener('product_submit_search', "click",submit_search,'product');
Event.addListener('product_search', "keydown", submit_search_on_enter,'product');

Event.addListener('order_submit_search', "click",submit_search,'order');
Event.addListener('order_search', "keydown", submit_search_on_enter,'order');

Event.addListener('customer_submit_search', "click",submit_search,'customer');
Event.addListener('customer_search', "keydown", submit_search_on_enter,'customer');