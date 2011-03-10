function Inint_AJAX() {
try { return new ActiveXObject("Msxml2.XMLHTTP");  } catch(e) {} //IE
try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch(e) {} //IE
try { return new XMLHttpRequest();          } catch(e) {} //Native Javascript
alert("XMLHttpRequest not supported");
return null;
};


function change_style(s) {

var result;
var theme;

		var make_default = confirm ("Are you want to make it default ?")

		if(make_default)
		{
		   theme = 0;
		}	
		else
		{
		  theme = 1;	
		}



 var req = Inint_AJAX();
 req.onreadystatechange = function () {
      if (req.readyState==4) {
           if (req.status==200) {
		
		//alert(req.responseText);
		if(req.responseText == 0)
		{
			result = "There is an error while saving the settings";
		}
		else
		{
			result = "<img src='art/icons/tick.png'>"+"Your setting has been saved";

		}

		document.getElementById('display').innerHTML=result;

           }
      }
 };
	
   req.open("GET", "website_style.php?style="+s+"&theme="+theme); 
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null); 
}


