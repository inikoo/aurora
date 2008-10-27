 var submit_search_on_enter=function(e){
     var key;     
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox     

     if (key == 13)
	 submit_search();
 }

 

 var submit_search=function(){
     var Dom   = YAHOO.util.Dom;
     var q =Dom.get('prod_search').value;
     if(q=='')
	 return;
     var request='ar_assets.php?tipo=search&q='+escape(q);
     YAHOO.util.Connect.asyncRequest('POST',request ,{
	     success:function(o) {

		 var r =  YAHOO.lang.JSON.parse(o.responseText);
		 if (r.state == 200){
		     window.location.href=r.url;
		 }else if(r.state==400){
		     Dom.get('search_msg').innerHTML=r.msg1;
		     Dom.get('search_sugestion').innerHTML=r.msg2;
		 }else
		     Dom.get('search_msg').innerHTML=r.msg;
	    }
	 });
 }