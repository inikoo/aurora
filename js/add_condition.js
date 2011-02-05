
function addEvent(m)
{
var m;
mydiv = 'myDiv'+m;	
var ni = document.getElementById(mydiv);
var numi = document.getElementById('theValue');
var num = (document.getElementById("theValue").value -1)+ 2;
numi.value = num;
var divIdName = "Condition"+num;
var newdiv = document.createElement('div');
newdiv.setAttribute("id",divIdName);


	

	newdiv.innerHTML = "<table border=\"0\"><tr><td>Match <select name=\"match\"><option value=\"any\">Any</option><option value=\"all\">All</option></select> of the following : </td></tr><tr><td><select name=\"drpDwn1\" id=\"drpDwn1\"><option value=\"email_address\">Email Address</option><option value=\"first_name\">First Name</option><option value=\"last_name\">Last Name</option></select><select name=\"drpDwn2\" id=\"drpDwn2\"><option value=\"is\">Is</option><option value=\"is_not\">Is not</option><option value=\"contains\">Contains</option><option value=\"does_not_contain\">Does not contain</option><option value=\"start_with\">Start With</option><option value=\"ends_with\">Ends With</option><option value=\"is_greater_than\">Is greater than</option><option value=\"is_less_than\">Is less than</option></select><input type=\"textbox\" name=\"box\" id=\"box\" ></td></tr></table><a href=\"javascript:;\" onclick=\"removeEvent(\'"+divIdName+"\',\'"+m+"\')\" ><span style=\"font-size:10px; color:#CC66OD;\">Remove Condition</span></a><div style=\"font-size:10px; color:#CC66OD; width:700px;\">Campaign will go to <div id=\"count_segment\" style=\"width:20px;\">0</div> segment <a href=\"#\" onClick=\"popup()\">View Segment</a> &nbsp;<a href=\"#\"  onClick=\"getRefreshValue()\">Count Refresh</a>&nbsp;&nbsp;<a href=\"#\" onClick=\"showSlidingDiv(\'"+m+"\'); return false;\">Cancel</a></div>";

ni.appendChild(newdiv);
}


function removeEvent(divNum,k)
{

var k;
divId = 'myDiv'+k;
var d = document.getElementById(divId);
var olddiv = document.getElementById(divNum);
d.removeChild(olddiv);
}



function popup()
{
	var querystring;
	querystring = document.getElementById('box').value;
	
	nW = window.open("",'segment','height=400, width=400');
	nW.location.href = 'view_segment.php?segID='+querystring;
	nW=null;
}



//call ajax
function getRefreshValue()
{
	var objDrp1;
	var objDrp2;
	var objText;
	objDrp1 = document.getElementById('drpDwn1').options[document.getElementById('drpDwn1').selectedIndex].value;
	objDrp2 = document.getElementById('drpDwn2').options[document.getElementById('drpDwn2').selectedIndex].value;	
	objText = document.getElementById('box').value;	


	//alert(objDrp1);
	//alert(objDrp2);
	//alert(objText);
		

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
	//alert(xmlhttp.responseText);
    document.getElementById("count_segment").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","count_refresh.php?select="+objDrp1+'&where='+objDrp2+'&check='+objText,true);
xmlhttp.send();
}



