var spawnCallback = {
success: function(o) {
	
	if(o.responseText == 0)
		{
			result = "There is an error while saving the settings";
		}
		else
		{
			location.href ="change_style.php";
			result = "";

		}

		document.getElementById('display').innerHTML=result;

},
failure: function(o) {

 alert(o.statusText);

},
timeout: 2000
};

function change_style(s) {

	var make_default = confirm ("Press Ok to select default theme or Cancel to change the theme ?")

		if(make_default)
		{
		   theme = 0;
		}	
		else
		{
		  theme = 1;	
		}

	sUrl = "website_style.php?style=+s+&theme=+theme";
YAHOO.util.Connect.asyncRequest('GET',sUrl,spawnCallback);
}

