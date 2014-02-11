//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW



var Dom   = YAHOO.util.Dom;


function my_encodeURIComponent(str) {
    str = encodeURIComponent(str);
    return (str + '').replace(/'/g, '%27');
}

function showdetails(oimg){

//alert(o)
    var history_id=oimg.getAttribute('hid');
    var details=oimg.getAttribute('d');
    tr=Dom.getAncestorByTagName(oimg,'tr');
    row_index=tr.rowIndex+1;
    var table=Dom.getAncestorByTagName(oimg,'table');
  
    if(details=='no'){
	row_class=tr.getAttribute('class');

	var request="ar_history.php?tipo=history_details&id="+history_id;
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
	     
	      var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			var x=table.insertRow(row_index);
			x.setAttribute('class',row_class);
//			x.setAttribute('id','chd'+history_id);

			var c1=x.insertCell(0);
			var c2=x.insertCell(1);
			var c3=x.insertCell(2);
			x.setAttribute('style','padding:10px 0 ;border-top:none')
			c1.innerHTML="";
			c2.innerHTML="";
			c3.setAttribute('style','padding:10px 0 ;');


			c3.setAttribute('colspan',3);
			c3.innerHTML=r.details;
			Dom.get(oimg).src='art/icons/showed.png';
			Dom.get(oimg).setAttribute('d','yes');

			
		    }
		       
		}
	    });   
    }
    else{
	Dom.get(oimg).src='art/icons/closed.png';
	Dom.get(oimg).setAttribute('d','no');
	table.deleteRow(row_index);

    }
     
	
}






function percentage($a,$b,$fixed,$error_txt,$psign,$plus_sing){
    
    if($error_txt== undefined)
	$error_txt='NA';
    if($psign== undefined)
	$psign='%';
    if($plus_sing== undefined)
	$plus_sign=false;
    if($fixed== undefined)
	$fixed=1;
    $per='';
    $error_txt=$error_txt;
    if($b>0){
	if($plus_sing && $a>0)
	    $sing='+';
	else
	    $sing='';
	
	$per=$sing+number_format((100*($a/$b)),$fixed)+$psign;
	
    }
    else
	$per=$error_txt;
    return $per;
}




function parse_number(a) {
    if (is_string(a)) {

        if (Dom.get('thousands_sep') != '') {
            var sRegExInput = new RegExp(Dom.get('thousands_sep'), "g");;
            a = a.replace(sRegExInput, '');

        }

        var sRegExInput = new RegExp(Dom.get('decimal_point'), "g");;
        a = a.replace(sRegExInput, '.');

    }
    return parseFloat(a);
}





function number($a, $fixed, $force_fix) {

    if ($fixed == undefined) $fixed = 1;
    if ($force_fix == undefined) $force_fixed = false;


    $floored = floor($a);
    if ($floored == $a && !$force_fix) $fixed = 0;

    $a = number_format($a, $fixed, YAHOO.util.Dom.get('decimal_point'), YAHOO.util.Dom.get('thousands_sep'));

    return $a;
}


function money($amount,$locale,$force_sign){
    
    if($locale== undefined)
	$locale=false;
    if($force_sign== undefined)
	$force_sign=false;

    $positive_sign='';
    if($force_sign)
	$positive_sign='+';

  if($amount<0)
    $neg=true;
  else
    $neg=false;
  $amount=abs($amount);
  
  if(!$locale){
    $amount=number_format($amount,2,YAHOO.util.Dom.get('decimal_point').value,YAHOO.util.Dom.get('thousands_sep').value);
        $symbol=Dom.get('currency_symbol').value;

    $amount=($neg?'-':$positive_sign)+$symbol+$amount;
    return $amount;
  }else{
    switch($locale){
    case('EUR'):
      $amount=number_format($amount,2,YAHOO.util.Dom.get('decimal_point').value,YAHOO.util.Dom.get('thousands_sep').value);
      $symbol='€';
      $amount=($neg?'-':$positive_sign)+$symbol+$amount;
      return $amount;
      break;
    case('GBP'):
      $amount=number_format($amount,2,YAHOO.util.Dom.get('decimal_point').value,YAHOO.util.Dom.get('thousands_sep').value);
      $symbol='£';
      $amount=($neg?'-':$positive_sign)+$symbol+$amount;
      return $amount;
      break;


    }

  }

}



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

var myBuildUrl = function(datatable,record) {
    var url = '';
    var cols = datatable.getColumnSet().keys;
    for (var i = 0; i < cols.length; i++) {
        if (cols[i].isPrimaryKey) {
            url += '&' + cols[i].key + '=' + escape(record.getData(cols[i].key));
        }else if (cols[i].isTypeKey) {
            url += '&' + cols[i].key + '=' + escape(record.getData(cols[i].key));
        }
	

    }
    return url;
};




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


function isValidURL(url){
    var RegExp = /^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/;
    if(RegExp.test(url)){
        return true;
    }else{
        return false;
    } 
}
   

var regexp_valid_email="^((([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+(\.([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+)*)@((((([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.))*([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$";
var regexp_valid_email="^(|((([a-z]|[0-9]|!|#|$|%|&|'|\\*|\\+|\\-|\\/|=|\\?|\\^|_|`|\\{|\\||\\}|~)+(\\.([a-z]|[0-9]|!|#|$|%|&|'|\\*|\\+|\\-|\\/|=|\\?|\\^|_|`|\\{|\\||\\}|~)+)*)@((((([a-z]|[0-9])([a-z]|[0-9]|\\-){0,61}([a-z]|[0-9])\\.))*([a-z]|[0-9])([a-z]|[0-9]|\\-){0,61}([a-z]|[0-9])\\.)[\\w]{2,4}|(((([0-9]){1,3}\\.){3}([0-9]){1,3}))|(\\[((([0-9]){1,3}\\.){3}([0-9]){1,3})\\]))))\\s*$";

var regexp_valid_www="^(([\\w]+:)?\\/\\/)?(([\\d\\w]|%[a-fA-f\\d]{2,2})+(:([\\d\\w]|%[a-fA-f\\d]{2,2})+)?@)?([\\d\\w][-\\d\\w]{0,253}[\\d\\w]\.)+[\\w]{2,4}(:[\\d]+)?(\\/([-+_~.\\d\\w]|%[a-fA-f\\d]{2,2})*)*(\\?(&?([-+_~.\\d\\w]|%[a-fA-f\\d]{2,2})=?)*)?(#([-+_~.\\d\\w]|%[a-fA-f\\d]{2,2})*)?$"

function isValidEmail(email){
    var RegExp = /^((([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+(\.([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+)*)@((((([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.))*([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/i
	if(RegExp.test(email)){
	    return true;
	}else{
	    return false;
	}
}

function isValidNumber(number, ok_null) {

    if (ok_null) {
        if (number == '') return true

    }
    return is_numeric(number);
}




function FormatClean(num) {
    var sVal = '';
    var nVal = num.length;
    var sChar = '';

    try {
        for (c = 0; c < nVal; c++) {
            sChar = num.charAt(c);
            nChar = sChar.charCodeAt(0);
            if ((nChar >= 48) && (nChar <= 57)) {
                sVal += num.charAt(c);
            }
        }
    } catch (exception) {
        AlertError("Format Clean", exception);
    }
    return sVal;
}



function AlertError(methodName, e) {
    if (e.description == null) {
        alert(methodName + " Exception: " + e.message);
    } else {
        alert(methodName + " Exception: " + e.description);
    }
}



function key_filter(e, filter) {
    var keynum;
    var keychar;
    var numcheck;

    if (window.event) // IE
    {
        Keynum = e.keyCode;
    } else if (e.which) // Netscape/Firefox/Opera
    {
        Keynum = e.which;
    }


    if (typeof(keynum) == 'undefined') return
    keychar = String.fromCharCode(keynum);

    return filter.test(keychar);


}


function updateCal() {

    var txtDate1 = document.getElementById("v_calpop" + this.id);

    if (txtDate1.value != "") {
        temp = txtDate1.value.split('-');
        var date = temp[1] + '/' + temp[0] + '/' + temp[2];

        this.select(date);

        var selectedDates = this.getSelectedDates();

        if (selectedDates.length > 0) {
            var firstDate = selectedDates[0];
            this.cfg.setProperty("pagedate", (firstDate.getMonth() + 1) + "/" + firstDate.getFullYear());
            this.render();
        } else {
            alert("Cannot select a date before 1/1/2006 or after 12/31/2008");
        }

    }
}

function handleSelect(type, args, obj) {
   
  
   
   
   var dates = args[0];
    var date = dates[0];
    var year = date[0],
        month = date[1],
        day = date[2];


    if (month < 10) month = '0' + month;
    if (day < 10) day = '0' + day;
    var txtDate1 = document.getElementById("v_calpop" + this.id);
    txtDate1.value = day + "-" + month + "-" + year;
    this.hide();
   
}



var show_percentages = function(e, location) {


        var state = this.getAttribute('state');

        if (state == 1) {
            state = 0

            this.setAttribute('state', 0);
        } else {
            state = 1

            this.setAttribute('state', 1);
        }

        var tmp = this.innerHTML;
        this.innerHTML = this.getAttribute('atitle');
        this.setAttribute('atitle', tmp);
        var table = tables['table0'];
        var datasource = tables.dataSource0;
        var request = '&percentages=' + state;
        datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

    }



function trim(str) {
    s = str.replace(/^(\s)*/, '');
    s = s.replace(/(\s)*$/, '');
    s = s.replace(/\s{2,}/, ' ');
    return s;
}

function ame_arrays(thisArr, testArr) {

    if (thisArr.length != testArr.length) return false;

    testArr.sort;
    thisArr.sort;

    for (var i = 0; i < testArr.length; i++) {
        if (testArr[i] != thisArr[i]) return false
    }
    return true;
}

function addEvent(obj, type, fn) {
    if (obj.attachEvent) {
        obj['e' + type + fn] = fn;
        obj[type + fn] = function() {
            obj['e' + type + fn](window.event);
        }
        obj.attachEvent('on' + type, obj[type + fn]);
    } else obj.addEventListener(type, fn, false);
}






function star_rating($score, $max_score) {
    if ($max_score == undefined || $max_score == 0) $max_score = 1;

    $score = $score / $max_score;

    var new_star_rating = Dom.get('star_rating_template').cloneNode(true);
    var element_array = Dom.getElementsByClassName('star', 'img', new_star_rating);

    if ($score >= 1) element_array[4].src = 'art/icons/star.png';
    if ($score >= .8) element_array[3].src = 'art/icons/star.png';
    if ($score >= .6) element_array[2].src = 'art/icons/star.png';
    if ($score >= .4) element_array[1].src = 'art/icons/star.png';
    if ($score >= .2) element_array[0].src = 'art/icons/star.png';


    return new_star_rating;


}

function auto_logout_timer() {
    var t = setTimeout("auto_logout()", Dom.get('max_session_time_in_milliseconds'));

}

function auto_logout() {
    //location.href="index.php?logout=1&r=tos"
}

function init_common() {
    auto_logout_timer()
}

YAHOO.util.Event.onDOMReady(init_common);
