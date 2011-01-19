<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2010 LW
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;

var link='report_first_order.php';
function department_selected(sType, aArgs){
var department_key=aArgs[2][1];

Dom.get('choosed_department').innerHTML=aArgs[2][0]
Dom.get('department_key').value=department_key

Dom.get('department_chooser_tr').style.display='none';
Dom.get('department_choosed_tr').style.display='';

   var request='ar_reports.php?tipo=first_order_share_histogram&department_key=' + department_key+ '&from=' +Dom.get("from").value + '&to=' +Dom.get("to").value 

	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		     alert(o.responseText);
		    	   	   
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
			
			
			Dom.get('share_tr').style.display='';
			
			    for(result_key in r.histogram){
			Dom.get(result_key+"_orders").innerHTML=r.histogram[result_key]
			}
			
		
		    }else{
			alert(r.msg);
			
		    }
		    
		
			    
		}
	    });


}

function choose_store(o){
Dom.addClass(o,'selected');

Dom.get('store_key').value=o.getAttribute('key');
}


 function init(){
 
 ids=['avg_totals','avg_month','avg_week',"avg_month_eff","avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,0);
 
 	var oDS = new YAHOO.util.XHRDataSource("ar_search.php");

 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["name","key"]
 	};
	
 	var oAC = new YAHOO.widget.AutoComplete("department","department_Container", oDS);

 	oAC.generateRequest = function(sQuery) {
//alert("?tipo=departments&scope=store_key&scope_key="+Dom.get("store_key").value+"&q=" + sQuery) 	
 	    return "?tipo=departments&scope=store_key&scope_key="+Dom.get("store_key").value+"&q=" + sQuery ;
 	};
	oAC.forceSelection = true; 
	


	
	oAC.itemSelectEvent.subscribe(department_selected); 
 


 }

YAHOO.util.Event.onDOMReady(init);





