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

$(document).ready(function(){
        $('#dialog_link').live("click",function(){
	$('#dialog_text').css("display","block");
         $('#dialog').dialog('open');
	return false;
	});
				// Dialog			
				$('#dialog').dialog({
					autoOpen: false,
					width: 300,
                                        buttons: {
						"Yes": function() { 
							$(this).dialog("close"); 
                                                         theme = 0;
							sUrl = "website_style.php?style="+s+"&theme="+theme;

					YAHOO.util.Connect.asyncRequest('GET',sUrl,spawnCallback);								

						}, 
						"No": function() { 
							$(this).dialog("close");
                                                   theme = 1; 
sUrl = "website_style.php?style="+s+"&theme="+theme;

					YAHOO.util.Connect.asyncRequest('GET',sUrl,spawnCallback);
						} 
					}
				});
				
				// Dialog Link
				
				
				//hover states on the static widgets
				$('#dialog_link, ul#icons li').hover(
					function() { $(this).addClass('ui-state-hover'); }, 
					function() { $(this).removeClass('ui-state-hover'); }
				);
				
			});




}

