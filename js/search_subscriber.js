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
function search()
{
  //alert(state);


 xmlHttp=GetXmlHttpObject()
 if (xmlHttp==null)
 {
    alert ("Your browser does not support AJAX!");
    return;
 }
 else
 {
       
  	xmlHttp.onreadystatechange=stateChanged;
  	var q = document.getElementById('list_search').value;
     	if(q == ''){
            document.getElementById('list_search').value = 'Search List Subscribers';
            exit;
        }
   		url="search_subscriber.php?id="+q+"&u="+Math.random();
   		xmlHttp.open("GET",url,true);
  		xmlHttp.send(null);
  
	  
 }  
}

//After getting response from server,response text is being manupulated here.
//===========================================================================
function stateChanged()
{

 if (xmlHttp.readyState==1)
 {
     document.getElementById('search_result').innerHTML = 'Loading...';
    
 }


 if (xmlHttp.readyState==4)
 {
    var str=xmlHttp.responseText;
    //alert(str);
    
        document.getElementById('search_result').style.display = 'block';
        document.getElementById('search_result').innerHTML = str;


 }
}



