  var Dom   = YAHOO.util.Dom;
    var Event = YAHOO.util.Event;




function get_record_data(index){
  var ar_file='ar_import_csv.php';
    var request=ar_file+"?tipo=get_record_data&index="+index+"&scope="+Dom.get('scope').value; 
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	
	  success:function(o) {
	//alert(o.responseText)
	  
	//Dom.get('call_table').innerHTML=o.responseText;
		
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		  
		  Dom.get('call_table').innerHTML=r.result
		}else{
		    alert(r.msg);
		}
	    }
	});

}


function get_default(index) {

 
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
// alert("ar_import_csv.php?tipo=import_csv&v="+v+"&"+str)
 req.open("GET", "ar_import_csv.php?tipo=import_csv&v="+v+"&"+str);
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
 req.open("GET", "ar_import_csv.php?tipo=import_csv&v="+v+"&prevArray="+prevArray+"&"+str);
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

 req.open("GET", "ar_import_csv.php?tipo=import_csv&v="+v+"&myArray="+myArray+"&"+str);
 req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=tis-620"); // set Header
 req.send(null);
}


function next_record(index){
    var ar_file='ar_import_csv.php';
    var request=ar_file+"?tipo=import_csv&v="+index+"&myArray="+myArray+"&"+str; 
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		   Dom.setStyle(['ignore_record_label','unignore'],'display','');
		   Dom.setStyle(['ignore'],'display','none');
		}else{
		    alert(r.msg);
		}
	    }
	});
}

function ignore_record(index){
    var ar_file='ar_import_csv.php';
    var request=ar_file+'?tipo=ignore_record&index='+index; 
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		   Dom.setStyle(['ignore_record_label','unignore'],'display','');
		   Dom.setStyle(['ignore'],'display','none');
		}else{
		    alert(r.msg);
		}
	    }
	});
}

function read_record(index){
    var ar_file='ar_import_csv.php';
    var request=ar_file+'?tipo=read_record&index='+index; 
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		   Dom.setStyle(['ignore_record_label','unignore'],'display','none');
		   Dom.setStyle(['ignore'],'display','');
		}else{
		    alert(r.msg);
		}
	    }
	});
}

function option_changed(key,option_key){
   var ar_file='ar_import_csv.php';
    var request=ar_file+'?tipo=change_option&key='+key+'&option_key='+option_key; 
    YAHOO.util.Connect.asyncRequest('POST',request ,{});
}

function insert_data(){
window.location.href='insert_csv.php';
}

function init(){
get_record_data(0);
Event.addListener(['insert_data'], "click",insert_data);
}

YAHOO.util.Event.onDOMReady(init);






