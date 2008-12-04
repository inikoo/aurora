
var Dom   = YAHOO.util.Dom;
if(!Array.indexOf){
	    Array.prototype.indexOf = function(obj){
	        for(var i=0; i<this.length; i++){
	            if(this[i]==obj){
	                return i;
	            }
	        }
	        return -1;
	    }
	}




 YAHOO.util.Event.onContentReady("langmenu", function () {
	 var oMenu = new YAHOO.widget.Menu("langmenu", { context:["language_flag","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("language_flag", "click", oMenu.show, null, oMenu);
    
    });


function gup( name )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}

function gup_str( name,str )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( str );
  if( results == null )
    return "";
  else
    return results[1];
}


// function validate_email(email){
//     if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))
// 	return (true);
//     else
// 	return (false);
// }

function isValidURL(url){
    var RegExp = /^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/;
    if(RegExp.test(url)){
        return true;
    }else{
        return false;
    } 
}


function isValidEmail(email){
    var RegExp = /^((([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+(\.([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+)*)@((((([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.))*([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/
	if(RegExp.test(email)){
	    return true;
	}else{
	    return false;
	}
}


function isValidNumber(number,ok_null){
    if(ok_null){
	if(number=='')
	    return true

    }
    
    var RegExp = /^[0-9\s]+$/
	if(RegExp.test(number)){
	    return true;
	}else{
	    return false;
	}
}


function xemailcheck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		    
		    return false
		 }

 		 return true					
	}



function number_format( number, decimals, dec_point, thousands_sep ) {
 
    var n = number, c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;
    var d = dec_point == undefined ? "," : dec_point;
    var t = thousands_sep == undefined ? "." : thousands_sep, s = n < 0 ? "-" : "";
    var i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}



function FormatClean(num)
{
     var sVal='';
     var nVal = num.length;
     var sChar='';
     
   try
   {
      for(c=0;c<nVal;c++)
      {
         sChar = num.charAt(c);
         nChar = sChar.charCodeAt(0);
         if ((nChar >=48) && (nChar <=57))  { sVal += num.charAt(c);   }
      }
   }
    catch (exception) { AlertError("Format Clean",exception); }
    return sVal;
}
  

function FormatNumber(num,dec,comma,decimalPlaces)
{       

  var minus='';
  var preDecimal='';
  var postDecimal='';
  var delete_comma=false;
  if(comma==''){
      comma=',';
      delete_comma=true;
  }


  num=''+num;
  var index=num.lastIndexOf('.')
  if(index>=0){
      var integers=num.substr(0,index);
      var decimals=FormatClean(num.substr(index))+FormatEmptyNumber('',decimalPlaces);
      //   alert(decimals);
      decimals=decimals.substr(0,decimalPlaces);

      num=integers+decimals
  }else{
      num=num+ FormatEmptyNumber('',decimalPlaces-1)
  }

  try 
  {
   
    decimalPlaces = parseInt(decimalPlaces);

    
    if (decimalPlaces < 1) { dec = ''; }
    if (num.lastIndexOf("-") == 0) { minus='-'; }
   
    preDecimal = FormatClean(num);
    // alert(preDecimal);
    // preDecimal doesn't contain a number at all.
    // Return formatted zero representation.

    if (preDecimal.length < 1)
    {
       return minus + FormatEmptyNumber(dec,decimalPlaces);
    }
    
    // preDecimal is 0 or a series of 0's.
    // Return formatted zero representation.
    
    if (parseInt(preDecimal) < 1)
    {
       return minus + FormatEmptyNumber(dec,decimalPlaces);
    }
    
    // predecimal has no numbers to the left.
    // Return formatted zero representation.

    if (preDecimal.length == decimalPlaces)
    {
      return minus + '0' + dec + preDecimal;
    }
    
    // predecimal has fewer characters than the
    // specified number of decimal places.
    // Return formatted leading zero representation.
    
    if (preDecimal.length < decimalPlaces)
    {
	//	alert(preDecimal.length);
       if (decimalPlaces == 2)
       {
        return minus + FormatEmptyNumber(dec,decimalPlaces - 1) + preDecimal;
       }
       return minus + FormatEmptyNumber(dec,decimalPlaces - preDecimal.length) + preDecimal;
    }

    // predecimal contains enough characters to
    // qualify to need decimal points rendered.
    // Parse out the pre and post decimal values
    // for future formatting.
    
    if (preDecimal.length > decimalPlaces)
    {
      postDecimal = dec + preDecimal.substring(preDecimal.length - decimalPlaces,
                                               preDecimal.length);
      preDecimal = preDecimal.substring(0,preDecimal.length - decimalPlaces);
    }

    // Place comma oriented delimiter every 3 characters
    // against the numeric represenation of the "left" side
    // of the decimal representation.  When finished, return
    // both the left side comma formatted value together with
    // the right side decimal formatted value.
    
    var regex  = new RegExp('(-?[0-9]+)([0-9]{3})');

    while(regex.test(preDecimal))
	{  
       preDecimal = preDecimal.replace(regex, '$1' + comma + '$2');
    }
       
  }
  catch (exception) { AlertError("Format Number",exception); }
  if(delete_comma){

      preDecimal=preDecimal.replace(/\,/,"");
  }

  return minus + preDecimal + postDecimal;
}

function FormatEmptyNumber(decimalDelimiter,decimalPlaces)
{
    var preDecimal = '0';
    var postDecimal = '';
 
    for(i=0;i<decimalPlaces;i++)
    {
      if (i==0) { postDecimal += decimalDelimiter; }
      postDecimal += '0';
    }
   return preDecimal + postDecimal;
}
  

 function AlertError(methodName,e)
 {
            if (e.description == null) { alert(methodName + " Exception: " + e.message); }
            else {  alert(methodName + " Exception: " + e.description); }
 }






function key_filter(e,filter)
{
var keynum;
var keychar;
var numcheck;

if(window.event) // IE
  {
  keynum = e.keyCode;
  }
else if(e.which) // Netscape/Firefox/Opera
  {
  keynum = e.which;
  }


 if(typeof(keynum)=='undefined')
   return
keychar = String.fromCharCode(keynum);

 return filter.test(keychar);
 

}
function appendRow(tblId)
{
	var tbl = document.getElementById(tblId);
	var newRow = tbl.insertRow(tbl.rows.length);
	var newCell = newRow.insertCell(0);
	newCell.innerHTML = 'Hello World!';
}
function deleteLastRow(tblId)
{
	var tbl = document.getElementById(tblId);
	if (tbl.rows.length > 0) tbl.deleteRow(tbl.rows.length - 1);
}
function insertRow(tblId, txtIndex, txtError)
{
	var tbl = document.getElementById(tblId);
	var rowIndex = document.getElementById(txtIndex).value;
	try {
		var newRow = tbl.insertRow(rowIndex);
		var newCell = newRow.insertCell(0);
		newCell.innerHTML = 'Hello World! insert';
	} catch (ex) {
		document.getElementById(txtError).value = ex;
	}
}
function deleteRow(tblId, txtIndex, txtError)
{
	var tbl = document.getElementById(tblId);
	var rowIndex = document.getElementById(txtIndex).value;
	try {
		tbl.deleteRow(rowIndex);
	} catch (ex) {
		document.getElementById(txtError).value = ex;
	}
}


 function updateCal() {
	

     var txtDate1 = document.getElementById("v_calpop"+this.id);

     if (txtDate1.value != "") {
	 temp = txtDate1.value.split('-');
	 var date=temp[1]+'/'+temp[0]+'/'+temp[2];
	 
	    this.select(date);
	    
	    var selectedDates = this.getSelectedDates();

	    if (selectedDates.length > 0) {
		var firstDate = selectedDates[0];
		this.cfg.setProperty("pagedate", (firstDate.getMonth()+1) + "/" + firstDate.getFullYear());
		this.render();
	    } else {
		alert("<?=_("Cannot select a date before 1/1/2006 or after 12/31/2008")?>");
	    }
	    
	}

    }

 function handleSelect(type,args,obj) {
		var dates = args[0];
		var date = dates[0];
		var year = date[0], month = date[1], day = date[2];


		if(month<10)
		    month='0'+month;
		if(day<10)
		    day='0'+day;

		var txtDate1 = document.getElementById("v_calpop"+this.id);
		txtDate1.value = day + "-" + month + "-" + year;
		this.hide();
    }

    
var show_details=function(e,location){

     var state=this.getAttribute('state');
     if(state==1){
	 Dom.get('details').style.display='none';
	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys='+location+'-details&value=0');
	 this.setAttribute('state',0);
     }else{
	 Dom.get('details').style.display='';
	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys='+location+'-details&value=1');
	 this.setAttribute('state',1);
     }

     var tmp=this.innerHTML;
     this.innerHTML=this.getAttribute('atitle');
     this.setAttribute('atitle',tmp);
     
 }




    Node.prototype.moveRow = function(){
        if(this && this.nodeName.match(/^(table|t(body|head|foot))$/i)){
            try {
                one = (!arguments[0] && arguments[0] != 0?-1:arguments[0]);
                two = (!arguments[1] && arguments[1] != 0?-1:arguments[1]);

                // Makes sure the row exists and then makes sure the insertable row isn't greater then the length
                if(!this.rows[one] || two > this.rows.length){
                    var err = new Error();
                    throw err;
                }

                // This is just so that it gets put in the right place.
                if(two > one)
                    two = two+1;
                else if(one > two)
                    one = one+1;

                newRow = this.insertRow(two);
                newRow.innerHTML = this.rows[one].innerHTML;
                this.deleteRow(one);
            } catch(e) {
            }
        }
    }
