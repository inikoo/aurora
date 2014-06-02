var Dom = YAHOO.util.Dom;
var Event = YAHOO.util.Event;


function back_to_shop(){
	location.href="page.php?id="+Dom.get('last_basket_page_key').value

}

function init_basket(){
	

}

YAHOO.util.Event.onDOMReady(init_basket);