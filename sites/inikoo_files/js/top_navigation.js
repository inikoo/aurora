var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

function init_common(){

Dom.setStyle('footer_container', 'display','')
height=parseInt(Dom.getDocumentHeight())+30;
	Dom.setStyle('footer_container', 'top',height+'px')

}



YAHOO.util.Event.onDOMReady(init_common);

