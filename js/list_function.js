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


function checkListTable()
{

	//alert('ewfwf');

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
   	if(xmlhttp.responseText == 0)
	{
	   alert('Please create the list first');
	   return false;
	}
	else
	{
	   location.href='new_campaign.php';
	}
    }
  }
//xmlhttp.open("GET","check_list_table.php?textValue="+textValue+'&typeValue='+typeValue,true);
xmlhttp.open("GET","check_list_table.php",true);
xmlhttp.send();



}




