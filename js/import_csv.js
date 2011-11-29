var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;	

function init(){
 init_search(Dom.get('search_type').value);



}

YAHOO.util.Event.onDOMReady(init);