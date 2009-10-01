<?php 
include_once('common.php');


?>
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
var Dom   = YAHOO.util.Dom;


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


function parse_money($a){
   
    if(is_string($a)){
	$a=$a.replace ( /[^\d\<?php echo $_SESSION['locale_info']['decimal_point']?>]/g,'')

    }    

    return parse_number($a);
}


function parse_number($a){
    if(is_string($a)){
	$a.replace ( /\<?php echo $_SESSION['locale_info']['thousands_sep']?>/,'');
	$a.replace ( /\<?php echo $_SESSION['locale_info']['decimal_point']?>/,'.');
    }    
    return parseFloat($a);
}





function number($a,$fixed,$force_fix){
   
    if($fixed== undefined)
	$fixed=1;
    if($force_fix== undefined)
	$force_fixed=false;


  $floored=floor($a);
  if($floored==$a && !$force_fix)
    $fixed=0;

  $a=number_format($a,$fixed,'<? echo $_SESSION['locale_info']['decimal_point']?>','<? echo $_SESSION['locale_info']['thousands_sep']?>');
  
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
    $amount=number_format($amount,2,'<? echo $_SESSION['locale_info']['decimal_point'] ?>','<? echo $_SESSION['locale_info']['thousands_sep']?>');
    $symbol='<?php echo $_SESSION['locale_info']['currency_symbol']?>';
    $amount=($neg?'-':$positive_sign)+$symbol+$amount;
    return $amount;
  }else{
    switch($locale){
    case('EUR'):
      $amount=number_format($amount,2,'<? echo $_SESSION['locale_info']['decimal_point'] ?>','<? echo $_SESSION['locale_info']['thousands_sep']?>');
      $symbol='€';
      $amount=($neg?'-':$positive_sign)+$symbol+$amount;
      return $amount;
      break;
    case('GBP'):
      $amount=number_format($amount,2,'<? echo $_SESSION['locale_info']['decimal_point'] ?>','<? echo $_SESSION['locale_info']['thousands_sep']?>');
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
   

function isValidEmail(email){
    var RegExp = /^((([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+(\.([a-z]|[0-9]|!|#|$|%|&|'|\*|\+|\-|\/|=|\?|\^|_|`|\{|\||\}|~)+)*)@((((([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.))*([a-z]|[0-9])([a-z]|[0-9]|\-){0,61}([a-z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/i
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
    return is_numeric(number);
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
		alert("<?php echo _("Cannot select a date before 1/1/2006 or after 12/31/2008")?>");
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

var show_percentages=function(e,location){


     var state=this.getAttribute('state');

     if(state==1){
	 state=0
	 
	 this.setAttribute('state',0);
     }else{
	 state=1

	 this.setAttribute('state',1);
     }

     var tmp=this.innerHTML;
      this.innerHTML=this.getAttribute('atitle');
      this.setAttribute('atitle',tmp);
      var table=tables['table0'];
      var datasource=tables.dataSource0;
      var request='&percentages='+state;
      datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   

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


function change_period(e,table_id){

    tipo=this.id;
    Dom.get(period).className="";
    Dom.get(tipo).className="selected";	
    period=tipo;
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
    var request='&period=' + this.getAttribute('period');
    // alert(request);
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}
function change_avg(e,table_id){

    //  alert(avg);
    tipo=this.id;
    Dom.get(avg).className="";
    Dom.get(tipo).className="selected";	
    avg=tipo;
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
    var request='&avg=' + this.getAttribute('avg');
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}

function trim(str)
{
     s = str.replace(/^(\s)*/, '');
    s = s.replace(/(\s)*$/, '');
     s = s.replace(/\s{2,}/, ' ');
    return s;
}

same_arrays = function(thisArr,testArr) {
  
  if (thisArr.length != testArr.length) 
    return false;
  
  testArr.sort;
  thisArr.sort;

    for (var i = 0; i < testArr.length; i++) {
      if(testArr[i]!=thisArr[i])
	return false
    }
    return true;
}

function addEvent( obj, type, fn ) {
  if ( obj.attachEvent ) {
    obj['e'+type+fn] = fn;
    obj[type+fn] = function(){obj['e'+type+fn]( window.event );}
    obj.attachEvent( 'on'+type, obj[type+fn] );
  } else
    obj.addEventListener( type, fn, false );
}






function star_rating($score,$max_score){
  if($max_score==undefined || $max_score==0)
    $max_score=1;
  
  $score=$score/$max_score;

  var new_star_rating = Dom.get('star_rating_template').cloneNode(true);
  var element_array=Dom.getElementsByClassName('star','img',new_star_rating);
  
  if($score>=1)
      element_array[4].src='art/icons/star.png';
  if($score>=.8)
      element_array[3].src='art/icons/star.png';
  if($score>=.6)
      element_array[2].src='art/icons/star.png';
  if($score>=.4)
      element_array[1].src='art/icons/star.png';
  if($score>=.2)
      element_array[0].src='art/icons/star.png';


  return new_star_rating;


}



