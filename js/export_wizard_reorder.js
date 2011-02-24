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
