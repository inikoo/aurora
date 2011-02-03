function showFolder()
{
	var str;
	str = 'New Folder';
	
if (str=="")
  {
  document.getElementById("newFolder").innerHTML="";
  return;
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
	//alert(xmlhttp.responseText);
    document.getElementById("folder").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","makeFolder.php?q="+str,true);
xmlhttp.send();
}
