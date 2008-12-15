
var Dom   = YAHOO.util.Dom;
var submit_search_on_enter=function(e,tipo){
     var key;     
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox     

     if (key == 13)
	 submit_search(e,tipo);
 }


 

     var submit_search=function(e,tipo){

	 //alert(tipo);
     var q =Dom.get(tipo+'_search').value;
     if(q=='')
	 return;
     var request='ar_search.php?tipo='+tipo+'&q='+escape(q);
     YAHOO.util.Connect.asyncRequest('POST',request ,{
	     success:function(o) {

		 var r =  YAHOO.lang.JSON.parse(o.responseText);
		 if (r.state == 200){
		     window.location.href=r.url;
		 }else if(r.state==400){
		     Dom.get(tipo+'_search_msg').innerHTML=r.msg1;
		     Dom.get(tipo+'_search_sugestion').innerHTML=r.msg2;
		 }else
		     Dom.get(tipo+'_search_msg').innerHTML=r.msg;
	    }
	 });
 }