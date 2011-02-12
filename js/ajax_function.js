
function Inint_AJAX() {
try { return new ActiveXObject("Msxml2.XMLHTTP");  } catch(e) {} //IE
try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch(e) {} //IE
try { return new XMLHttpRequest();          } catch(e) {} //Native Javascript
alert("XMLHttpRequest not supported");
return null;
};

function get_default(v) {
 var v;

//alert(v);
 var req = Inint_AJAX();
 req.onreadystatechange = function () {
      if (req.readyState==4) {
           if (req.status==200) {
		
                document.getElementById('call_table').innerHTML=req.responseText; 
           }
      }
 };
 //req.open("GET", "getPage.php?data="+r+"&val="+val); 
   req.open("GET", "getPage.php?v="+v); 
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null); 
}

function getPrev(v,limit) {
 var v;
	
	
	var prevArray = new Array();
	
	for(var l=0; l<limit; l++)
	{
	  prevArray.push(document.getElementById('assign_field_'+l).value);
	}

 var req = Inint_AJAX();
 req.onreadystatechange = function () {
      if (req.readyState==4) {
           if (req.status==200) {
		//alert(req.responseText);
                document.getElementById('call_table').innerHTML=req.responseText; 
           }
      }
 };
	v = v-1;
 //req.open("GET", "getPage.php?data="+r+"&val="+val); 
   req.open("GET", "getPage.php?v="+v+"&prevArray="+prevArray); 
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null); 
}

function getNext(v,num) {
 var v;
 
 var myArray = new Array();
	
	for(var k=0; k<num; k++)
	{
	  myArray.push(document.getElementById('assign_field_'+k).value);
	}
 var req = Inint_AJAX();
 req.onreadystatechange = function () {
      if (req.readyState==4) {
           if (req.status==200) {
		//alert(req.responseText);
                document.getElementById('call_table').innerHTML=req.responseText; 
           }
      }
 };
	v=v+1;

   req.open("GET", "getPage.php?v="+v+"&myArray="+myArray); 
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null); 
}


function getIgnore(v) {
 var v;

	
// alert(v);
 var req = Inint_AJAX();
 req.onreadystatechange = function () {
      if (req.readyState==4) {
           if (req.status==200) {
		//alert(req.responseText);
		
                document.getElementById('show').innerHTML=req.responseText; 
           }
      }
 };
	v=v+1;
 //req.open("GET", "getPage.php?data="+r+"&val="+val); 
   req.open("GET", "removeResult.php?v="+v); 
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null); 
}





