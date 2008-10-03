

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Maps JavaScript API Example</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAPoXbQ8YmZcYb3ItlLWLgjxQ5sY5P7TpWZCgOhj2r0K56J54w0hQw1Z4OLwjYsshTPYorpsvMog4Sjw" type="text/javascript"></script>
    <script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=ABQIAAAAPoXbQ8YmZcYb3ItlLWLgjxQ5sY5P7TpWZCgOhj2r0K56J54w0hQw1Z4OLwjYsshTPYorpsvMog4Sjw" type="text/javascript"></script>

    <script type="text/javascript">

    //<![CDATA[

    function load() {
   if (GBrowserIsCompatible()) {
     var localSearch = new GlocalSearch();
     
function getMapFromQuery(query) {

	localSearch.setSearchCompleteCallback(null, 
		function() {
			
			if (localSearch.results[0])
			{		
				var resultLat = localSearch.results[0].lat;
				var resultLng = localSearch.results[0].lng;
				var point = new GLatLng(resultLat,resultLng);

				map.setCenter(point, z, G_NORMAL_MAP);
			}else{

				alert("Query not found!");

						
			}
		});	
					     
	localSearch.execute(query);

}

     var query=<? if(   isset($_REQUEST['q']) ) echo "'".$_REQUEST['q']."';\n"; else echo "'';\n" ?>
     var x=<? if(   isset($_REQUEST['x']) and    is_numeric($_REQUEST['x'])) echo $_REQUEST['x'].";\n"; else echo '54.622978;'."\n" ?>
     var y=<? if(   isset($_REQUEST['y']) and    is_numeric($_REQUEST['y'])) echo $_REQUEST['y'].";\n"; else echo '-2.592773;'."\n" ?>
     var z=<? if(   isset($_REQUEST['z']) and    is_numeric($_REQUEST['z'])) echo $_REQUEST['z'].";\n"; else echo '3;'."\n" ?>
     


     var map = new GMap2(document.getElementById("map"));
    
     if(query!='')
       getMapFromQuery(query)
     else{
       map.setCenter(new GLatLng(x,y), z);
   
     }

}
 }
   
 //]]>
   </script>
   </head>
   

   <body onload="load()" onunload="GUnload()" style="padding:0;margin:0">
   


   <div id="map" style="width: 350px; height: 350px" ></div>
   </body>
   </html>
   






