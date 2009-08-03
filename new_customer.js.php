<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../common.php');
?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
 

function init(){
    var objFinder = new PCASmartForm(document.getElementById("smartAddress"), "", "", cb);
    objFinder.ShowAllCountries = true;
    objFinder.DrawForm();
    //objFinder.HomeCountry="AND";
    
    function cb()
    {
	document.getElementById("lblAddress").innerHTML = objFinder.Label().replace(/\n/g, "<br/>");
    }


}

YAHOO.util.Event.onDOMReady(init);
