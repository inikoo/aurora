// XMLHttpRequest object created after checking wheather the browser are compatible with ajax.
//============================================================================================
function GetXmlHttpObject()
{
  var xmlHttp=null;
  try
    {
    // Firefox, Opera 8.0+, Safari
     xmlHttp=new XMLHttpRequest();
    }
  catch (e)
    {
    // Internet Explorer
    try
      {
       xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
      }
    catch (e)
      {
       xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    }
  return xmlHttp;
}
//Getting XMLHttpRequest object for starting Ajax functionalities.
//===================================================================
function myfunc(s,d)
{

 var s;
 var d;

 xmlHttp=GetXmlHttpObject()
 if (xmlHttp==null)
 {
    alert ("Your browser does not support AJAX!");
    return;
 }
 else
 {
	xmlHttp.onreadystatechange=stateChanged;
        url="export_wizard_reorder.php?seq1="+s+"&seq2="+d+"&u="+Math.random();
	//alert(url);
        xmlHttp.open("GET",url,true);
        xmlHttp.send(null);
 }
}

//After getting response from server,response text is being manupulated here.
//===========================================================================
function stateChanged()
{
 if (xmlHttp.readyState==4)
 {
    var str=xmlHttp.responseText;
    //alert(str);
    //document.getElementById('result').innerHTML=str;
      window.location.reload();
 }
}

// Other Functions
// =================

function saveMap(){
var x=document.getElementById('save').checked;
if(x!=true){
	document.getElementById('maps').style.display='none';
}else{
	document.getElementById('maps').style.display='block';
	document.getElementById('map_name').focus();
}

}

function validate(){

var x=document.getElementById('save').checked;

	if (x==true){

		var map_name = document.getElementById('map_name').value.trim();
		var map_desc = document.getElementById('map_desc').value.trim();

		if(map_name == ''){

			alert("Enter a Map Name");
			document.getElementById('map_name').focus();
			return false;
			exit;
		}

		if(map_desc == ''){

			alert("Enter Map Description");
			document.getElementById('map_desc').focus();
			return false;
			exit;
		}

		return true;

	}
}

function go(url){
	window.location = url;
	return false;
}

