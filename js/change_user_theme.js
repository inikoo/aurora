function Inint_AJAX() {
try { return new ActiveXObject("Msxml2.XMLHTTP");  } catch(e) {} //IE
try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch(e) {} //IE
try { return new XMLHttpRequest();          } catch(e) {} //Native Javascript
alert("XMLHttpRequest not supported");
return null;
};


function change_user_theme(selectedIndex) {

	

var result;


		
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
			result = 'Theme has been changed ';

		}

		document.getElementById('display').innerHTML=result;

           }
      }
 };
	
   req.open("GET", "change_user_style.php?selectedIndex="+selectedIndex); 
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null); 
}


