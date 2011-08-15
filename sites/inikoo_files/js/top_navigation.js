var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;

function init_common(){
/*
if (self.innerHeight) // all except Explorer
	{
		scnWid = self.innerWidth;
		scnHei = self.innerHeight;
	}
	else if (document.documentElement && document.documentElement.clientHeight)
		// Explorer 6 Strict Mode
	{
		scnWid = document.documentElement.clientWidth;
		scnHei = document.documentElement.clientHeight;
	}
	else if (document.body) // other Explorers
	{
		scnWid = document.body.clientWidth;
		scnHei = document.body.clientHeight;
	}


scnHei=-scnHei;
alert(cnHei)
*/
	Dom.setStyle('footer_container', 'display','')

}



YAHOO.util.Event.onDOMReady(init_common);

