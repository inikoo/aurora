

inputEx.YQL.genTrimpathCallback = function(scriptTag) {
  return function(results) {
     //console.log(results);
     var t = TrimPath.parseTemplate(scriptTag.innerHTML);
	  var templateResult = t.process(results);
     scriptTag.parentNode.innerHTML += "<div class='trimpathDiv'>"+templateResult+"</div>";
  };
};

inputEx.YQL.initTrimpathPage = function(additionalCallbacks) {
   
	 var templates = YAHOO.util.Dom.getElementsBy( function(el) {
   		return (el.type && el.type == "text/trimpath");
   } , "script" );

 	var callbacks = [];

	for(var i = 0 ; i < templates.length ; i++) {
		var t = templates[i];
		var split = t.src.split('#');
		var requestId = parseInt(split[split.length-1], 10);
		if(!callbacks[requestId]) callbacks[requestId] = [];
		callbacks[requestId].push( inputEx.YQL.genTrimpathCallback(t) );
	}
	
	if(additionalCallbacks) {
	   for(i = 0 ; i < additionalCallbacks.length ; i++) {
	      var cbks = additionalCallbacks[i];
	      if(YAHOO.lang.isArray(cbks)) {
	         if(!callbacks[i]) callbacks[i] = [];
	         for(var j = 0 ; j < cbks.length ; j++) {
   		      callbacks[i].push( cbks[j] );
		      }
	      }
	   }
   }

   console.log(callbacks);
	inputEx.YQL.init(callbacks);
};
