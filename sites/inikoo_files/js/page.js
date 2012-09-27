var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

function init(){
elements=Dom.getChildren('content')
max_bottom=0;
content_region=Dom.getRegion('content');
for ( var i=elements.length-1; i>=0; --i ){
region = Dom.getRegion(elements[i])
if( region && region.bottom>max_bottom){
max_bottom=region.bottom;
}
}
altura=  max_bottom-content_region.top+20

Dom.setStyle('content','height',altura+'px')

}

Event.onDOMReady(init);