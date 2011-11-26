var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;



function change_theme(theme_key){
    var user_key=Dom.get('user_key').value;
	var request='ar_edit_preferences.php?tipo=change_theme&user_key='+user_key+'&theme_key='+theme_key
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
    	    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
		        window.location.reload()		
		    }else{
		  
	        }
	    }
    });
}
change_background_theme
function change_background_theme(background_theme_key){
    var user_key=Dom.get('user_key').value;
	var request='ar_edit_preferences.php?tipo=change_background_theme&user_key='+user_key+'&background_theme_key='+background_theme_key
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
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
