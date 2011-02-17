function getListData()
{
	var textValue;
	var typeValue;

	textValue = document.getElementById('list_name').value;
	
	if(document.getElementById('static').checked == true)
	{
		typeValue = document.getElementById('static').value;	
	}
	if(document.getElementById('dynamic').checked == true)
	{
		typeValue = document.getElementById('dynamic').value;	
	}	

		

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
	alert(xmlhttp.responseText);
    document.getElementById("showDiv").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","get_list_data.php?textValue="+textValue+'&typeValue='+typeValue,true);
xmlhttp.send();


	
}
