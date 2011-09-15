<?php
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;


function change_splinter(id){
var key=Dom.get(id).getAttribute('key');
	    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=home-display&value=' + escape(key),{},null );

}


function sliders(){
var panes=Dom.getElementsByClassName('pane', 'div', 'content');

for (var j = 0; j < panes.length; j++) {
Dom.setStyle(panes[j],'display','')
}


myTabs = new SlidingTabs('buttons', 'panes');
			
			// this sets up the previous/next buttons, if you want them
			$('previous').addEvent('click', myTabs.previous.bind(myTabs));
			$('next').addEvent('click', myTabs.next.bind(myTabs));
			
			// this sets it up to work even if it's width isn't a set amount of pixels
			window.addEvent('resize', myTabs.recalcWidths.bind(myTabs));
}


function change_block(o){
var buttons=Dom.getElementsByClassName('splinter_buttons', 'li', 'buttons');
var panes=Dom.getElementsByClassName('pane', 'div', 'content');

Dom.removeClass(buttons,'active');
Dom.addClass(o,'active');
Dom.setStyle(panes,'display','none');
//alert(o.getAttribute('key'))
Dom.setStyle('pane_'+o.getAttribute('key'),'display','');
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=home-display&value='+escape(o.getAttribute('key')),{});

}




          







function init(){

// var panes=Dom.getElementsByClassName('splinter_buttons', 'li', 'buttons');
//for (var j = 0; j < panes.length; j++) {
//alert(panes[j].id)
//}

 //	window.addEvent('load', function () {
			
	//	});
   // window.setTimeout(sliders,500);
}

YAHOO.util.Event.onDOMReady(init);
