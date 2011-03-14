var Callback = {
success: function(o) {
	
	
	if(o.responseText == 0)
		{
			result = "There is an error while saving the settings";
		}
		else
		{
			result = 'Processing ...';
			location.href ="change_user_theme.php";

		}

		document.getElementById('display').innerHTML=result;

},
failure: function(o) {

 alert(o.statusText);

},
timeout: 6000
};

function change_user_theme(n) {
	
sUrl = "change_user_style.php?selectedIndex="+n;


YAHOO.util.Connect.asyncRequest('GET',sUrl,Callback);
}








