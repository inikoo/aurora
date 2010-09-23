<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2010 LW
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;


function department_selected(sType, aArgs){

Dom.get('choosed_department').innerHTML=aArgs[2][0]
Dom.get('department_key').value=aArgs[2][1]

Dom.get('department_chooser_tr').style.display='none';
Dom.get('department_choosed_tr').style.display='';
}

 function init(){
 

 
 	var oDS = new YAHOO.util.XHRDataSource("ar_search.php");

 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["name","key"]
 	};
	
 	var oAC = new YAHOO.widget.AutoComplete("department","department_Container", oDS);

 	oAC.generateRequest = function(sQuery) {
 	    return "?tipo=departments&scope=store_key&scope_key="+Dom.get("store_key").value+"&q=" + sQuery ;
 	};
	oAC.forceSelection = true; 
	


	
	oAC.itemSelectEvent.subscribe(department_selected); 
 


 }

YAHOO.util.Event.onDOMReady(init);





