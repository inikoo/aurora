var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;

function init(){

elements=Dom.getChildren('content')
max_bottom=0;

content_region=Dom.getRegion('content');

//alert(elements.length)
for ( var i=elements.length-1; i>=0; --i ){
region = Dom.getRegion(elements[i])
//alert(region)
if( region && region.bottom>max_bottom){
max_bottom=region.bottom;
}
}

//alert(elements)
//for (x in elements){
  //element=Dom.get(elements[x]);

 // alert(element)
  
 //if (element.key !== undefined)
 //   alert(element)
  //region = Dom.getRegion(element)
 // }
  
//  if(Dom.getRegion(element)!=undefined){
  //
 // }
  
  // region = Dom.getRegion(elements[x])
// x=parseFloat(  region.bottom)
/// alert(x)
 //if(x>0){
  //  max_bottom=x
 //}
 
//}
   
altura=  max_bottom-content_region.top
//alert(altura)
Dom.setStyle('content','height',altura+'px')

}

Event.onDOMReady(init);