function Inint_AJAX() {
try { return new ActiveXObject("Msxml2.XMLHTTP");} catch(e) {} //IE
try { return new ActiveXObject("Microsoft.XMLHTTP");} catch(e) {} //IE
try { return new XMLHttpRequest();} catch(e) {} //Native Javascript
alert("XMLHttpRequest not supported");
return null;
};

function get_default(v) {
 var v;
 var qstring = window.location.toString();
 arr=qstring.split("?");
 var str=arr[1];

//alert(v);
 var req = Inint_AJAX();
 req.onreadystatechange = function () {
      if (req.readyState==4) {
           if (req.status==200) {

                document.getElementById('call_table').innerHTML=req.responseText; 
           }
      }
 };

 req.open("GET", "import_csv_record.php?v="+v+"&"+str);
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null);
}

function getPrev(v,limit) {
 var v;
 var qstring = window.location.toString();
 arr=qstring.split("?");
 var str=arr[1];

	//alert(document.getElementById('ignore_message').innerHTML);

	document.getElementById('ignore_message').innerHTML="";
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
v = v-1;//alert(v);
 //req.open("GET", "getPage.php?data="+r+"&val="+val); 
 req.open("GET", "import_csv_record.php?v="+v+"&prevArray="+prevArray+"&"+str);
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null); 
}

function getNext(v,num) {
 var v;
 var qstring = window.location.toString();
 arr=qstring.split("?");
 var str=arr[1];
	//alert(document.getElementById('ignore_message').innerHTML);
document.getElementById('ignore_message').innerHTML="";

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

 req.open("GET", "import_csv_record.php?v="+v+"&myArray="+myArray+"&"+str);
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null);
}


function getIgnore(v) {
 var v;


	//create an array to insert the color change id 
	var colorArray = new Array();
	
		if(v>=0){	
			//v = v+1;
			colorArray.push(v);

		}
	
	//alert(document.getElementById('ignore_message').innerHTML);

 var req = Inint_AJAX();
 req.onreadystatechange = function () {
      if (req.readyState==4) {
           if (req.status==200) {

		//alert(req.responseText);

		var splitter = req.responseText;

		var splitterResult = splitter.split("@");

		//alert(req.responseText);

		//alert(splitterResult[1]);

		document.getElementById('display').innerHTML=splitterResult[0]; 

                document.getElementById('ignore_message').innerHTML="This data will be ignored";
                document.getElementById('show').innerHTML=splitterResult[1];

           }
      }
 };
	//v=v+1;

   req.open("GET", "removeResult.php?v="+v+"&colorArray="+colorArray);
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null);
}





