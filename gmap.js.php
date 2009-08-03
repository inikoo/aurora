
var map;
var localSearch = new GlocalSearch();

var icon = new GIcon();
icon.image = "http://www.google.com/mapfiles/marker.png";
icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
icon.iconSize = new GSize(20, 34);
icon.shadowSize = new GSize(37, 34);
icon.iconAnchor = new GPoint(10, 34);


function usePointFromQuery(query, callbackFunction) {
	
	localSearch.setSearchCompleteCallback(null, 
		function() {
			
			if (localSearch.results[0])
			{		
				var resultLat = localSearch.results[0].lat;
				var resultLng = localSearch.results[0].lng;
				var point = new GLatLng(resultLat,resultLng);
				callbackFunction(point);
			}else{

				alert("Query not found!");

						
			}
		});	
		
	localSearch.execute(query);
}




function getPointFromQuery(query) {
	
	localSearch.setSearchCompleteCallback(null, 
		function() {
			
			if (localSearch.results[0])
			{		
				var resultLat = localSearch.results[0].lat;
				var resultLng = localSearch.results[0].lng;
				var point = new GLatLng(resultLat,resultLng);
				map.setCenter(new GLatLng(54.622978,-2.592773, 5, G_NORMAL_MAP);
			}else{

				alert("Query not found!");

						
			}
		});	
		
	localSearch.execute(query);

}



function placeMarkerAtPoint(point)
{
	var marker = new GMarker(point,icon);
	map.addOverlay(marker);
}

function setCenterToPoint(point)
{
	map.setCenter(point, 4);
}

function showPointLatLng(point)
{
	alert("Latitude: " + point.lat() + "\nLongitude: " + point.lng());
}

function mapLoad() {
	if (GBrowserIsCompatible()) {
		map = new GMap2(document.getElementById("map"));
		
		
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());


	       
		//var query='<?php echo$_REQUEST['q']?>';
		
		//	getPointFromQuery('uk');

		map.setCenter(new GLatLng(54.622978,-2.592773), 5, G_NORMAL_MAP);
		//usePointFromQuery(query, setCenterToPoint) 

	}
}

function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}

function addUnLoadEvent(func) {
	var oldonunload = window.onunload;
	if (typeof window.onunload != 'function') {
	  window.onunload = func;
	} else {
	  window.onunload = function() {
	    oldonunload();
	    func();
	  }
	}
}

addLoadEvent(mapLoad);
addUnLoadEvent(GUnload);
