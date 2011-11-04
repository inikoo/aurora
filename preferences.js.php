var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;



 function change_theme(theme_key){


var user_key=Dom.get('user_key').value;
	
	var request='ar_edit_preferences.php?tipo=change_theme&user_key='+user_key+'&theme_key='+theme_key
//alert(request)
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		    window.location.reload()
		
		}else{
		  
	    }
	    }
	    });
	



 }


function init(){



}

YAHOO.util.Event.onDOMReady(init);
