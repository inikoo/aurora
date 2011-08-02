<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
// function prepare_mysql_date($date){

//   if($date=='')
//     return array('',0);
//   if(!preg_match('/^[0123]\d[\-\/][01]\d[\-\/]\d{4}$/',$date)){
//     return array('',1);

//   }

//   $date=str_replace('/','-',$date);
//   $date=('-',$date);
//   $mysql_date= join ('-',array_reverse($date));
//   return array($mysql_date,0);
// }
if(!function_exists('money_format')){
function money_format($format, $number)
{
    $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.
              '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
    if (setlocale(LC_MONETARY, 0) == 'C') {
        setlocale(LC_MONETARY, '');
    }
    $locale = localeconv();
    preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
    foreach ($matches as $fmatch) {
        $value = floatval($number);
        $flags = array(
            'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
                           $match[1] : ' ',
            'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
            'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
                           $match[0] : '+',
            'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
            'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
        );
        $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
        $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
        $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
        $conversion = $fmatch[5];

        $positive = true;
        if ($value < 0) {
            $positive = false;
            $value  *= -1;
        }
        $letter = $positive ? 'p' : 'n';

        $prefix = $suffix = $cprefix = $csuffix = $signal = '';

        $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
        switch (true) {
            case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
                $prefix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
                $suffix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
                $cprefix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
                $csuffix = $signal;
                break;
            case $flags['usesignal'] == '(':
            case $locale["{$letter}_sign_posn"] == 0:
                $prefix = '(';
                $suffix = ')';
                break;
        }
        if (!$flags['nosimbol']) {
            $currency = $cprefix .
                        ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
                        $csuffix;
        } else {
            $currency = '';
        }
        $space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';

        $value = number_format($value, $right, $locale['mon_decimal_point'],
                 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
        $value = @explode($locale['mon_decimal_point'], $value);

        $n = strlen($prefix) + strlen($currency) + strlen($value[0]);
        if ($left > 0 && $left > $n) {
            $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
        }
        $value = implode($locale['mon_decimal_point'], $value);
        if ($locale["{$letter}_cs_precedes"]) {
            $value = $prefix . $currency . $space . $value . $suffix;
        } else {
            $value = $prefix . $value . $space . $currency . $suffix;
        }
        if ($width > 0) {
            $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
                     STR_PAD_RIGHT : STR_PAD_LEFT);
        }

        $format = str_replace($fmatch[0], $value, $format);
    }
    return $format;
} 
}
function log_visit($session_key) {


global $user_click_key;
$user_click_key=0;
   // $file = $_SERVER["SCRIPT_NAME"]; //current file path gets stored in $file
$file = $_SERVER["PHP_SELF"];
//echo $file;
 
 $break = Explode('/', $file);
    $cur_file = $break[count($break) - 1];
if(preg_match('/^ar\_/',$cur_file)){
    return;
}

if(preg_match('/^ar_/',$cur_file) or preg_match('/\.js/',$cur_file)){
return;
}

// function to get the full url of the current page
function slfURL() 
{ $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
 $protocol = strleft1(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI']; 
}

 function strleft1($s1, $s2) 
{ return substr($s1, 0, strpos($s1, $s2)); }

$cur_fullurl=slfURL();
//print "$cur_fullurl<br>";
$break = Explode('/', $cur_fullurl);
$cur_url = $break[count($break) - 1];
//print $cur_url;



//echo $file;
   // print "current file : $cur_file <br>";           //current file name gets stored in $file

    $purl = (isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'');        //previous page url
    $break = Explode('/', $purl);
    $prev_url = $break[count($break) - 1];   //previous page file name with value passed to it

    //$pos = strpos($prev_url, '?');

    //$prev_file = substr($prev_url,0, $pos);
   // print "previous file : $prev_file<br>";
//echo("<br>");



    if (isset($user)) {
        $user_key=$user->id;
    } else {
        $user_key=0;
    }

    $page_key=0;
    $date=date("Y-m-d H:i:s");
   
   
   if(isset($_SESSION['prev_page_key']))
    $prev_page_key=$_SESSION['prev_page_key'];
  else
  $prev_page_key=0;
  
  
        $sql1=sprintf("INSERT INTO `User Click Dimension` (

                      `User Key` ,
                      `URL` ,
                      `Page Key` ,
                      `Date` ,
                      `Previous Page` ,
                      `Session Key` ,
                      `Previous Page Key`
                      )
                      VALUES (
                      %d,%s, %d,%s, %s, %d,%d
                      );",
                      $user_key,
                      prepare_mysql($cur_url),
                      $page_key,
                      prepare_mysql($date),
                      prepare_mysql($prev_url),
                      $session_key,
                      $prev_page_key
                     );

        //print($sql1);
        mysql_query($sql1);
        $user_click_key= mysql_insert_id();

  

}
function update_page_key_visit_log($page_key){
    global $user_click_key;
    $sql=sprintf("update `User Click Dimension`  set `Page Key`=%d where `User Click Key`=%d",$page_key,$user_click_key);
    mysql_query($sql);

}


function getEnumValues($table, $field) {
    $enum_array = array();
    $query = 'SHOW COLUMNS FROM `' . $table . '` LIKE "' . $field . '"';
    $result = mysql_query($query);
    $row = mysql_fetch_row($result);
    preg_match_all('/\'(.*?)\'/', $row[1], $enum_array);
    if(!empty($enum_array[1])) {
        // Shift array keys to match original enumerated index in MySQL (allows for use of index values instead of strings)
        foreach($enum_array[1] as $mkey => $mval) $enum_fields[$mkey+1] = $mval;
        return $enum_fields;
    }
    else return array(); // Return an empty array to avoid possible errors/warnings if array is passed to foreach() without first being checked with !empty().
}


function money($amount,$currency=''){
  return money_locale($amount,'',$currency);
}


function money_locale($amount,$locale='',$currency_code=''){
  if(!is_numeric($amount))
    $amount=0;
  global $_client_locale;
  $format="%n";
  if($locale){
    $locale.='.UTF-8';
    setlocale(LC_MONETARY, ($locale));
  }
  if($currency_code){
    $locale_info = localeconv();
    $client_currency=$locale_info['int_curr_symbol'];
    $format="%i";
  }
  $money=money_format($format,$amount);
  if($currency_code){
    $money=preg_replace('/[A-Z]{3}/',currency_symbol($currency_code),$money);
  }

  setlocale(LC_MONETARY, ($_client_locale));
  return $money;
}


function get_currency_symbol_from_locale($locale){
  global $_client_locale;
  setlocale(LC_MONETARY, $locale);
  $locale_info = localeconv();
  $currency_code=$locale_info['currency_symbol'];
  setlocale(LC_MONETARY, $_client_locale);
  return $currency_code;
}


/* Function: clean_accents
 
Replace Non-English characters to its ANSI equivalent.

 Parameter:
 str - String
 
 Return:
 String without accents
 
 Example:
 >  echo clean_accents('Hola Raúl);
 >   Hola Raul

 */


function clean_accents($str){

  
  $str=preg_replace('/é|è|ê|ë|æ/','e',$str);
  $str=preg_replace('/á|à|â|ã|ä|å|æ|ª/','a',$str);
  $str=preg_replace('/ù|ú|û|ü/','u',$str);
  $str=preg_replace('/ò|ó|ô|õ|ö|ø|°/','o',$str);
  $str=preg_replace('/ì|í|î|ï/','i',$str);

  $str=preg_replace('/É|È|Ê|Ë|Æ/','E',$str);
  $str=preg_replace('/Á|À|Â|Ã|Ä|Å|Æ|ª/','A',$str);
  $str=preg_replace('/Ù|Ú|Û|Ü/','U',$str);
  $str=preg_replace('/Ò|Ó|Ô|Õ|Ö|Ø|°/','O',$str);
  $str=preg_replace('/Ì|Í|Î|Ï/','I',$str);

  $str=preg_replace('/ñ/','n',$str);
  $str=preg_replace('/Ñ/','N',$str);
  $str=preg_replace('/ç|¢|©/','c',$str);
  $str=preg_replace('/Ç/','C',$str);
  $str=preg_replace('/ß|§/i','s',$str);

  return $str;
}

function unformat_money($number){
$locale_info = localeconv();


  $number=preg_replace('/\\'.$locale_info['thousand_sep'].'/','',$number);
  $number=preg_replace('/\\'.$locale_info['decimal_point'].'/','.',$number);
  return $number;
}

function getOrdinal($number){
 // get first digit
 $digit = abs($number) % 10;
 $ext = 'th';
 $ext = ((abs($number) %100 < 21 && abs($number) %100 > 4) ? 'th' : (($digit < 4) ? ($digit < 3) ? ($digit < 2) ? ($digit < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
 return $number.$ext;
}


function prepare_mysql_datetime($datetime,$tipo='datetime'){

 
  if($datetime=='')
    return array('mysql_date'=>'','status'=>'empty','ok'=>false);
  $time='';
  if(preg_match('/datetime/',$tipo)){
     if(preg_match('/^[0123]\d[\-\/][01]\d[\-\/]\d{4}\s[012]\d:[0123456]\d$/',$datetime))
       $datetime=$datetime.':00';
    if(!preg_match('/^[0123]\d[\-\/][01]\d[\-\/]\d{4}\s[012]\d:[0123456]\d:[0123456]\d$/',$datetime))
      return array('mysql_date'=>'','status'=>_('error, date time not reconozied')." $datetime",'ok'=>false);
    $ts=date('U',strtotime($datetime));
    list($date,$time)=preg_split('/\s+/',$datetime);
  }else{
    if(!preg_match('/^[0123]\d[\-\/][01]\d[\-\/]\d{4}/',$datetime))
      return array('mysql_date'=>'','status'=>'wrong date','ok'=>false);
    $date=$datetime;
    $ts=date('U',strtotime($date));
  }



  
  $date=str_replace('/','-',$date);
  $date=preg_split('/-/',$date);


 if(preg_match('/datetime/',$tipo)){
   
   $mysql_datetime= trim(join ('-',array_reverse($date)).' '.$time);
 }else{
   

   $mysql_datetime= join ('-',array_reverse($date));
    if(preg_match('/start/i',$tipo))
      $mysql_datetime.=' 00:00:00';
     if(preg_match('/midday/i',$tipo))
      $mysql_datetime.=' 12:00:00';
    elseif(preg_match('/end/i',$tipo))
      $mysql_datetime.=' 23:59:59';
    
  }
  return array('ts'=>$ts,'mysql_date'=>$mysql_datetime,'status'=>'ok','ok'=>true);

}
 

function getLastDayOfMonth($month, $year)
{
return idate('d', mktime(0, 0, 0, ($month + 1), 0, $year));
}


function date_base($from,$to,$step='m',$tipo='complete_both'){


  $tmp=prepare_mysql_datetime($from,'date');
  $mysql_date1=$tmp['mysql_date'];
  $ok1=$tmp['ok'];
  if($tmp['status']=='empty')
    $ok1=true;
  $tmp=prepare_mysql_datetime($to,'date');
  $mysql_date2=$tmp['mysql_date'];
  $ok2=$tmp['ok'];
  if($tmp['status']=='empty')
    $ok2=true;
  if( !$ok1  or !$ok2)
    return array();
  list($date1['y'],$date1['m'],$date1['d'])=preg_split('/-/',$mysql_date1);
  list($date2['y'],$date2['m'],$date2['d'])=preg_split('/-/',$mysql_date2);
  $base=array();


  switch($step){
  case('m'):

    if(preg_match('/(^|\s|,)complete($|\s|,)|complete_both|complete_first|complete_from|only_complete|complete months/i',$tipo)){

      if($date1['d']>1){
	list($date1['y'],$date1['m'],$date1['d'])=preg_split('/-/',date("Y-m-d", mktime(0, 0, 0, $date1['m']+1, 1, $date1['y'])));
      }
    }
    if(preg_match('/(^|\s|,)complete($|\s|,)|complete_both|complete_second|complete_to|only_complete|complete months/i',$tipo)){
      $last_day= getLastDayOfMonth($date2['m'], $date2['y']);

      if($date2['d']!= $last_day  ){
	list($date2['y'],$date2['m'],$date2['d'])=preg_split('/-/',date("Y-m-d", mktime(0, 0, 0, $date2['m']-1,$last_day-1 , $date2['y'])));
      }
    }
    


    foreach(range($date1['y'],$date2['y']) as $y){
      foreach(range(1,12) as $m){
	if($y==$date1['y'] and $m<$date1['m'])
	  continue;
	if($y==$date2['y'] and $m>$date2['m'])
	  break;
	$base[sprintf('%d-%02d',$y,$m)]='';
	
      }
    }



    
  }
  
  return $base;
}

function prepare_mysql_dates($date1='',$date2='',$date_field='date',$options=''){
  $start='';
  $end='';
  if(preg_match('/start.*end/i',$options)){
    $start=' start';
    $end=' end';

  }
  if(preg_match('/dates?_only|dates? only|only dates?/i',$options)){
    $d_option='date';

    

    $date_only=true;
  }else{
    $d_option='';
    $date_only=false;
  }
  $tmp=prepare_mysql_datetime($date1,$d_option.$start);
  $mysql_date1=$tmp['mysql_date'];
  $ok1=$tmp['ok'];
  if($tmp['status']=='empty')
    $ok1=true;

  $tmp=prepare_mysql_datetime($date2,$d_option.$end);
  $mysql_date2=$tmp['mysql_date'];
  $ok2=$tmp['ok'];
  if($tmp['status']=='empty')
    $ok2=true;
  if(!$ok1 or !$ok2)
    $error=1;
  else
    $error=0;

  
  if(preg_match('/complete months/i',$options)){
    list($_date1['y'],$_date1['m'],$_date1['d'])=preg_split('/-/',$mysql_date1);
    list($_date2['y'],$_date2['m'],$_date2['d'])=preg_split('/-/',$mysql_date2);
    if($_date1['d']>1)
      list($_date1['y'],$_date1['m'],$_date1['d'])=preg_split('/-/',date("Y-m-d", mktime(0, 0, 0, $_date1['m']+1, 1, $_date1['y'])));
    $last_day= getLastDayOfMonth($_date2['m'], $_date2['y']);
    if($_date2['d']!= $last_day  )
      list($_date2['y'],$_date2['m'],$_date2['d'])=preg_split('/-/',date("Y-m-d", mktime(0, 0, 0, $_date2['m']-1,$last_day-1 , $_date2['y'])));
    
    $mysql_date1=$_date1['y'].'-'.$_date1['m'].'-'.$_date1['d'];
    $mysql_date2=$_date2['y'].'-'.$_date2['m'].'-'.$_date2['d'];

  }





  $date_field=addslashes($date_field);
  
  if($mysql_date2=='' and $mysql_date1=='' )
    $mysql_interval="";
  else if($mysql_date2!='' and $mysql_date1!=''){
    $mysql_interval=" and $date_field>='$mysql_date1' and $date_field<='$mysql_date2'";
    
  }else if($mysql_date2!='')
    $mysql_interval=" and $date_field<='$mysql_date2'";
  else
    $mysql_interval=" and $date_field>='$mysql_date1' ";
  
  return array('0'=>$mysql_interval,'1'=>$date1,'2'=>$date2,'3'=>$error,'error'=>$error,'mysql'=>$mysql_interval,'from'=>$date1,'to'=>$date2);


}



function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}



function percentage($a,$b,$fixed=1,$error_txt='NA',$psign='%',$plus_sing=false){

  $locale_info = localeconv();

  $per='';
  $error_txt=_($error_txt);
  if($b>0){
    if($plus_sing and $a>0)
      $sing='+';
    else
      $sing='';
    $per=$sing.number_format((100*($a/$b)),$fixed,$locale_info['decimal_point'],$locale_info['thousands_sep']).$psign;
    }
  else
    $per=$error_txt;
  return $per;
}


/* Function: parse_money
 
 Parse a string extracting an numeric value

 Parameter:
 amount - *string* String to be parsed
 currency - *string* Currency  [£|¥|€|(3 Letter Currency Code)|false=$myconf['currency_code'] ]

 
 Return:
 Array (Currency Code, Number)
 
 Example:
 (start code)
 print_r ( parse_money('Price: 99.99 euros','€'));
  Array
  (
  [0] => 'EUR'
  [1] => 99.99
  )
(end code)

 */  
function parse_money($amount,$currency=false){
  global $myconf;
  //	preg_match('/(\$|\£|\€|EUR|GBP|USD)[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?/',$term_description , $match){

$locale_info = localeconv();

  if(!$currency)
    $currency=$myconf['currency_code'];
  else
    $currency=$currency;
  if(preg_match('/$|£|¥|€|zł/i',$amount,$match)){
    if($match[0]=='$')
      $currency='USD';
    elseif($match[0]=='€')
      $currency='EUR';
    elseif($match[0]=='£')
      $currency='GBP';
    elseif($match[0]=='¥')
      $currency='JPY';
    elseif($match[0]=='¥')
      $currency='JPY';
 elseif($match[0]=='zł')
      $currency='PLN';

  }elseif(preg_match('/[a-z]{3}/i',$amount,$match)){
    //todo integrate do country db
    if(preg_match('/usd|eur|gbp|jpy|cad|aud|inr|pkr|mxn|nok/i',$match[0])){
      $currency=strtoupper($match[0]);
    }
  }
  

  
  return array($currency,ParseFloat($amount));

}

function ParseFloat($floatString){
    $LocaleInfo = localeconv();
    $floatString = str_replace($LocaleInfo["mon_thousands_sep"] , "", $floatString);
    $floatString = str_replace($LocaleInfo["mon_decimal_point"] , ".", $floatString);
    return floatval($floatString);
} 


function currency_symbol($currency){
   switch($currency){
   case('GBP'):
     return '£';
     break;
   case('EUR'):
     return '€';
     break;
   case('USD'):
      return '$';
      break;
 case('PLN'):
      return 'zł';
      break;

   default:
     return '¤';
   }

}




function money_cents($amount){
  $amount=sprintf("%02d",100*($amount-floor($amount)));
  return $amount;
}


function weight($w,$unit='Kg'){
  return number($w).'Kg';
}

function RelativeTime($timestamp){
    $difference = time() - $timestamp;
    $periods = array("sec", "min", "hour", "day", "week", "month", "years", "decade");
    $lengths = array("60","60","24","7","4.35","12","10");

    if ($difference > 0) { // this was in the past
        $ending = "ago";
    } else { // this was in the future
        $difference = -$difference;
        $ending = "to go";
    }       
    for($j = 0; $difference >= $lengths[$j]; $j++) $difference /= $lengths[$j];
    $difference = round($difference);
    if($difference != 1) $periods[$j].= "s";
    $text = "$difference $periods[$j] $ending";
    return $text;
} 



function number($a,$fixed=1,$force_fix=false){
if(!$a)
$a=0;

$locale_info = localeconv();
  $floored=floor($a);
  if($floored==$a and !$force_fix)
    $fixed=0;
  $a=number_format($a,$fixed,$locale_info['decimal_point'],$locale_info['thousands_sep']);
  
  return $a;
}

function endmonth($m,$y){
return idate('d', mktime(0, 0, 0, ($m + 1), 0, $y));

}

function detect_interval($date1,$date2){
  
  return false;
}


function display_dif($present,$past){
  if($present==_('NA'))
      $present=0;

  if($past==_('NA'))
    return '<td class="same"> '._('ND').' </td>';
  elseif($past==$present)
     return '<td class="same"><span class="arrow">&harr;</span> 0% </td>';
  elseif($past==0)
    return '<td class="same"> '._('ND').'   </td>';
  else{
    
    
    $dif=100*($present-$past)/$past;

    if($dif>0){
      $class='up';
      $arrow='&uarr;';
    }
    elseif($dif<0){
      $class='down';
      $arrow='&darr;';
    }
    else{
      $class='same';
      $arrow='&harr;';
    }
    $dif_str='<td class="'.$class.'"><span class="arrow">'.$arrow.'</span>   '.number_format($dif,1).'%</td>';
    
    
   return $dif_str;
  }
}


function ft_request($from,$to,$output='phpdate',$mysql_date='date'){

  if($to=='')
    $to=$from;
  if($from!=''){
    $day_from=preg_split('/-/',$from);
    $day_to=preg_split('/-/',$to);


    if(count($day_from)==3 and count($day_to)==3)
      {
	switch($output){
	case('mysql_dates'):
	  $date1=join ('-',array_reverse($day_from));
	  $date2=join ('-',array_reverse($day_to));
	  return array(true,$date1,$date2);
	  
	case('phpdate'):
	  $date1=strtotime(join ('-',array_reverse($day_from)));
	  $date2=strtotime(join ('-',array_reverse($day_to)));
	  return array(true,$date1,$date2);
	case('mysqlwhere'):
	  $date1=join ('-',array_reverse($day_from));
	  $date2=join ('-',array_reverse($day_to));
	  if($date1==$date2)
	    $where_sql="( date='$date1'   )";
	  else
	    $where_sql="( DATE($mysql_date)>='$date1' and DATE($mysql_date)<='$date2'  )";
	  return array(true,$where_sql,'');
	    
       }
     }
  }
  return array(false,'','');


}

function ft_dates($from,$to,$output='phpdate',$mysql_date='date'){


  switch($output){
  case('mysql_dates'):
    $date1=date('Y-m-d',$from);
    $date2=date('Y-m-d',$to);
    return array(true,$date1,$date2);
  case('mysqlwhen'):
    $date1=date('Y-m-d',$from);
    $date2=date('Y-m-d',$to);
    if($date1==$date2)
      $where_sql="(date='$date1')";
    else
      $where_sql="(DATE($mysql_date)>='$date1' and DATE($mysql_date)<='$date2')";
    return array(true,$where_sql,'');
    
  }



}

function ip()
	{
		global $REMOTE_ADDR;
		global $HTTP_X_FORWARDED_FOR, $HTTP_X_FORWARDED, $HTTP_FORWARDED_FOR, $HTTP_FORWARDED;
		global $HTTP_VIA, $HTTP_X_COMING_FROM, $HTTP_COMING_FROM;
		// Get some server/environment variables values
		if (empty($REMOTE_ADDR)) {
			if (!empty($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) {
				$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
			}
			else if (!empty($_ENV) && isset($_ENV['REMOTE_ADDR'])) {
				$REMOTE_ADDR = $_ENV['REMOTE_ADDR'];
			}
			else if (@getenv('REMOTE_ADDR')) {
				$REMOTE_ADDR = getenv('REMOTE_ADDR');
			}
		} // end if
		if (empty($HTTP_X_FORWARDED_FOR)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$HTTP_X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED_FOR'])) {
				$HTTP_X_FORWARDED_FOR = $_ENV['HTTP_X_FORWARDED_FOR'];
			}
			else if (@getenv('HTTP_X_FORWARDED_FOR')) {
				$HTTP_X_FORWARDED_FOR = getenv('HTTP_X_FORWARDED_FOR');
			}
		} // end if
		if (empty($HTTP_X_FORWARDED)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED'])) {
				$HTTP_X_FORWARDED = $_SERVER['HTTP_X_FORWARDED'];
			}
			else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED'])) {
				$HTTP_X_FORWARDED = $_ENV['HTTP_X_FORWARDED'];
			}
			else if (@getenv('HTTP_X_FORWARDED')) {
				$HTTP_X_FORWARDED = getenv('HTTP_X_FORWARDED');
			}
		} // end if
		if (empty($HTTP_FORWARDED_FOR)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED_FOR'])) {
				$HTTP_FORWARDED_FOR = $_SERVER['HTTP_FORWARDED_FOR'];
			}
			else if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED_FOR'])) {
				$HTTP_FORWARDED_FOR = $_ENV['HTTP_FORWARDED_FOR'];
			}
			else if (@getenv('HTTP_FORWARDED_FOR')) {
				$HTTP_FORWARDED_FOR = getenv('HTTP_FORWARDED_FOR');
			}
		} // end if
		if (empty($HTTP_FORWARDED)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED'])) {
				$HTTP_FORWARDED = $_SERVER['HTTP_FORWARDED'];
			}
			else if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED'])) {
				$HTTP_FORWARDED = $_ENV['HTTP_FORWARDED'];
			}
			else if (@getenv('HTTP_FORWARDED')) {
				$HTTP_FORWARDED = getenv('HTTP_FORWARDED');
			}
		} // end if
		if (empty($HTTP_VIA)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_VIA'])) {
				$HTTP_VIA = $_SERVER['HTTP_VIA'];
			}
			else if (!empty($_ENV) && isset($_ENV['HTTP_VIA'])) {
				$HTTP_VIA = $_ENV['HTTP_VIA'];
			}
			else if (@getenv('HTTP_VIA')) {
				$HTTP_VIA = getenv('HTTP_VIA');
			}
		} // end if
		if (empty($HTTP_X_COMING_FROM)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_X_COMING_FROM'])) {
				$HTTP_X_COMING_FROM = $_SERVER['HTTP_X_COMING_FROM'];
			}
			else if (!empty($_ENV) && isset($_ENV['HTTP_X_COMING_FROM'])) {
				$HTTP_X_COMING_FROM = $_ENV['HTTP_X_COMING_FROM'];
			}
			else if (@getenv('HTTP_X_COMING_FROM')) {
				$HTTP_X_COMING_FROM = getenv('HTTP_X_COMING_FROM');
			}
		} // end if
		if (empty($HTTP_COMING_FROM)) {
			if (!empty($_SERVER) && isset($_SERVER['HTTP_COMING_FROM'])) {
				$HTTP_COMING_FROM = $_SERVER['HTTP_COMING_FROM'];
			}
			else if (!empty($_ENV) && isset($_ENV['HTTP_COMING_FROM'])) {
				$HTTP_COMING_FROM = $_ENV['HTTP_COMING_FROM'];
			}
			else if (@getenv('HTTP_COMING_FROM')) {
				$HTTP_COMING_FROM = getenv('HTTP_COMING_FROM');
			}
		} // end if
	
		// Gets the default ip sent by the user
		if (!empty($REMOTE_ADDR)) {
			$direct_ip = $REMOTE_ADDR;
		}
	
		// Gets the proxy ip sent by the user
		$proxy_ip	 = '';
		if (!empty($HTTP_X_FORWARDED_FOR)) {
			$proxy_ip = $HTTP_X_FORWARDED_FOR;
		} else if (!empty($HTTP_X_FORWARDED)) {
			$proxy_ip = $HTTP_X_FORWARDED;
		} else if (!empty($HTTP_FORWARDED_FOR)) {
			$proxy_ip = $HTTP_FORWARDED_FOR;
		} else if (!empty($HTTP_FORWARDED)) {
			$proxy_ip = $HTTP_FORWARDED;
		} else if (!empty($HTTP_VIA)) {
			$proxy_ip = $HTTP_VIA;
		} else if (!empty($HTTP_X_COMING_FROM)) {
			$proxy_ip = $HTTP_X_COMING_FROM;
		} else if (!empty($HTTP_COMING_FROM)) {
			$proxy_ip = $HTTP_COMING_FROM;
		} // end if... else if...
	
		// Returns the true IP if it has been found, else FALSE
		if (empty($proxy_ip)) {
			// True IP without proxy
			return $direct_ip;
		} else {
			$is_ip = preg_match('|^([0-9]{1,3}\.){3,3}[0-9]{1,3}|', $proxy_ip, $regs);
			if ($is_ip && (count($regs) > 0)) {
				// True IP behind a proxy
				return $regs[0];
			} else {
				// Can't define IP: there is a proxy but we don't have
				// information about the true IP
				return FALSE;
			}
		} // end if... else...
	}



function selfURL() { 

  $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : ""; 
  $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; 
  $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
  return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI']; }

function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }


/**
 * @return timestamp
 * @param integer year
 * @param integer woy
 * @param integer dow
 * @desc This function retrieves the time for the given year, week of year and day of the week and returns it.
 */
function mktimefromcw($year, $woy=1, $dow=1) {
  # $year = year (four digits)
  # $woy  = week of the year (1..53)
  # $dow  = day of the week (1..7)

  $dow = ($dow) % 7;
  $woy = ($woy) - 1;

  # Get reference value (this is the first monday of the first week of the year, not easy to calculate)
  $fdoy_timestamp = mktime(0,0,0,1,1,$year);
  $fdoy = ((date("w", $fdoy_timestamp) + 6) % 7) + 1;

  if($fdoy == 1) {
    # This first day of the year is a monday
    $fcwstart = $fdoy_timestamp;
  }
  elseif($fdoy < 5) {
    # The first day if before Friday, therefor the first Monday can be found in the previos year (this is no fun, believe in it!).
    $fcwstart = strtotime("last Monday", $fdoy_timestamp);
  }
  else {
    # The first day is a friday or later, so the first days belong to calender week 53 (yes, this is possible!) of the previous year, do not count them for this year.
    $fcwstart = strtotime("next Monday", $fdoy_timestamp);
  }

  # Create timestamp
  $timestr = date("d F Y", $fcwstart)." +$woy week +$dow day";
  $time = strtotime($timestr);

  # Return timestamp
  return $time;
}
function translate($string)
{
    $arg = array();
    for($i = 1 ; $i < func_num_args(); $i++)
        $arg[] = func_get_arg($i);
   
    return vsprintf(gettext($string), $arg);
}

function interval($days,$units='auto'){

  switch($units){
  case('auto'):
    
    if(!is_numeric($days) or $days<=0)
      $interval='';
    else if($days<14)
      $interval=number($days)._('d');
    elseif($days<89)
      $interval=number($days/7)._('w');
    elseif($days<534)
      $interval=number($days/30)._('m');
    elseif($days<1826)
      $interval=number($days/365.25)._('y');
    else
      $interval=">5"._('y');
      
  }
  return $interval;
}
  

function get_time_interval($d1,$d2,$units='days'){

  $interval=$d2-$d1;

  switch($units){
  case('days'):
    $interval=$interval/3600/24;
    
  }

  return $interval;
}

function extract_product_groups($str,
				$q_prod_name='P.`Product Code` like',
				$q_prod_id='P.`Product ID`',
				$q_group_name='`Product Family Code` like',
				$q_group_id='`Product Family Key` like',
				$q_department_name='P.`Product Main Department Code` like',
				$q_department_id='P.`Product Main Department Key` like'
				){
  if($str=='')
    return '';
  $where='';
  $where_g='';
  $where_d='';


   if(preg_match_all('/d\([a-z0-9\-\,]*\)/i',$str,$matches)){
    

    foreach($matches[0] as $match){
      
      $_departments=preg_replace('/\)$/i','',preg_replace('/^d\(/i','',$match));
      $_departments=preg_split('/\s*,\s*/i',$_departments);

      foreach($_departments as $department){
	$department_ordered=addslashes($department);
	if(is_numeric($department_ordered))
	  $where_d.=" or $q_department_id  '$department_ordered'";
	else
	  $where_d.=" or $q_department_name '$department_ordered'";
      }
    }
    $str=preg_replace('/d\([a-z0-9\-\,]*\)/i','',$str);
  }

  if(preg_match_all('/f\([a-z0-9\-\,]*\)/i',$str,$matches)){
    

    foreach($matches[0] as $match){
      
      $_groups=preg_replace('/\)$/i','',preg_replace('/^f\(/i','',$match));
      $_groups=preg_split('/\s*,\s*/i',$_groups);

      foreach($_groups as $group){
	$group_ordered=addslashes($group);
	if(is_numeric($group_ordered))
	  $where_g.=" or $q_group_id  '$group_ordered'";
	else
	  $where_g.=" or $q_group_name '$group_ordered'";
      }
    }
    $str=preg_replace('/f\([a-z0-9\-\,]*\)/i','',$str);
  }
  

  $products=preg_split('/\s*,\s*/i',$str);
  
  $where_p='';
  foreach($products as $product){
    if($product!=''){
      $product=addslashes($product);
      if(is_numeric($product))
	$where_p.= " or $q_prod_id  '$product'";
      else
	$where_p.= " or $q_prod_name  '$product'";
    }
  }
  
  

  $where=preg_replace('/^\s*or\s*/i','',$where_d.$where_g.$where_p);
  return '('.$where.')';
  
}


//print _trim('        d ca        ca  caca    ');

function _trim($string){
 $string=trim($string);

  //$string=preg_replace('/\xC2\xA0\s*$/',' ',$string);
  // $string=preg_replace('/\xA0\s*/',' ',$string);
 // $string=preg_replace('/\s+/',' ',trim($string));

 //  $string=preg_replace('/^\s*/','',$string);
//   $string=preg_replace('/\s*$/','',$string);
//   $string=preg_replace('/\s+/',' ',$string);

  return $string;
}


function mb_ucwords($str) {

    if(preg_match('/^PO BOX\s+/i',$str))
     return strtoupper($str);

    $result='';
    $str=_trim($str);
    
    $words=preg_split('/ /',$str);
    $first=true;
    foreach($words as $word){
        
        if(!$first and preg_match('/^(UK|USA|HP|IBM|GB|MB|CD|DVD|USB)$/i',$word)){
             $result.=' '.strtoupper($word);
            continue;
        }
        if(!$first and preg_match('/^(and|y|o|or|of|at|des|les|las|le)$/i',$word)){
             $result.=' '.strtoupper($word);
            continue;
        }
        $result.=' '.capitalize($word);
        $first=false;
    }
        
    return _trim($result);
}




function mb_ucwordsols($str) {
  $str=_trim($str);
  
  if(strlen($str==1)){
    return strtoupper($str);

  }
  
  
  if(preg_match('/^PO BOX\s+/i',$str))
    return strtoupper($str);
  
  $exceptions = array();
  $exceptions['Uk'] = 'UK';
  $exceptions['Usa'] = 'USA';
  $exceptions['Hp'] = 'HP';
  $exceptions['Ibm'] = 'IBM';
  $exceptions['Gb'] = 'GB';
  $exceptions['Mb'] = 'MB';
  $exceptions['Cd'] = 'CD';
  $exceptions['Dvd'] = 'DVD';
  $exceptions['Usb'] = 'USB';
  $exceptions['Mm'] = 'mm';
  $exceptions['Cm'] = 'cm';

  $exceptions['De'] = 'de';
  $exceptions['A'] = 'a';
  $exceptions['Del'] = 'del';
  $exceptions['Y'] = 'y';
  $exceptions['O'] = 'o';
  
  $exceptions['The'] = 'the';
  $exceptions['Of'] = 'of';
  $exceptions['Les'] = 'les';
  $exceptions['Las'] = 'las';
  $exceptions['Le'] = 'le';
  $exceptions['And'] = 'and';
  $exceptions['Or'] = 'or';
  $exceptions['At'] = 'at';
  $exceptions['Des'] = 'des';
  $exceptions['Fao'] = 'FAO';
  $exceptions['MRS'] = 'Mrs';
  $exceptions['MR'] = 'Mr';
  $exceptions['SR'] = 'Sr';
  $exceptions['RD'] = 'Rd';
  $exceptions['MS'] = 'Ms';
    
  if($str=='' or $str==',' or  $str=='+' or  $str=='-')
    return $str;
  //  print "$str\n";

  //print "=========================\n";

  $separator = array("-","+",","," ");
   
  $str = mb_strtolower(trim($str),"UTF-8");
  foreach($separator as $s){
    $word = explode($s, $str);
   
    $return = "";
    foreach ($word as $val){
      
      
      if(preg_match('/^www\.[^\s]+/i',$val)){
	    $return .= $s .mb_strtolower($val,"UTF-8");
      }elseif(preg_match('/^[a-z]{2,}\.$/i',$val)){
	$return .= $s .mb_strtolower($val,"UTF-8");
      }elseif(preg_match('/^(st|Mr|Mrs|Miss|Dr|Ltd)$/i',$val)){
		$return .= $s 
		  . mb_strtoupper($val{0},"UTF-8") 
		  . mb_substr($val,1,mb_strlen($val,"UTF-8")-1,"UTF-8");
      }
      
      elseif(preg_match('/^[^\s]+\.(com|uk|info|biz|org)$/i',$val)){
	$return .= $s .mb_strtolower($val,"UTF-8");
      }
      elseif(preg_match('/^(aa|ee|ii|oo|uu)$/i',$val)){
	$return .= $s .mb_strtoupper($val,"UTF-8");
      }elseif(preg_match('/^([a-z]\.){1,}$/i',$val)){
	$return .= $s .mb_strtoupper($val,"UTF-8");
      }elseif(preg_match('/^c\/o$/i',$val)){
	$return .= $s .'C/O';
     
      }elseif(preg_match('/^t\/a$/i',$val)){
	$return .= $s .'T/A';
	
      }elseif(preg_match('/^([^(aeoiu)]{2,3})$/i',$val)){
	
	$return .= $s .mb_strtoupper($val,"UTF-8");
      }elseif(preg_match('/^\(.+\)$/i',$val)){
	$text=preg_replace('/^\(|\)$/i','',$val);
	//print "*** $text\n";
	$return .= $s.'('.mb_ucwords($text).')';
      }
      
      elseif(mb_strlen($val,"UTF-8")>0){
	$return.=$s.capitalize($val);
	//	$return .= $s 
	//	  . mb_strtoupper($val{0},"UTF-8") 
	//	  . mb_substr($val,1,mb_strlen($val,"UTF-8")-1,"UTF-8");
	//	print "return: $s ->  ".$val{0}." -> $mp_a $return \n";
      }

    }
    
    $str = mb_substr($return, 1);


  }


  
  
    $return=capitalize($return);

  
    // $return{1}=mb_strtoupper($return{1},"UTF-8");
    //$return=mb_substr($return, 1);
  
  foreach($exceptions as $find=>$replace){
    $return = preg_replace('/\s+'.$find.'\s+|\s+'.$find.'$/', ' '.$replace.' ', $return);
    
  }
  $return=_trim($return);
  //  }else
  //  $return='';
  // print $return."\n";
  return $return;
}
function capitalize($str, $encoding = 'UTF-8') {
  $str=trim($str);
    return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) . mb_strtolower(mb_substr($str, 1, mb_strlen($str), $encoding), $encoding);

}


/*
 Function: prepare_mysql
Prepare string to be useed in the Database

If string is empty returs NULL unless $null_if_empty is false

Parameter:
$string - *string* to be prepared
$null_if_empty - *bolean* config flag
 */

function prepare_mysql($string,$null_if_empty=true){

  if(is_numeric($string)){
    return "'".$string."'";
  }elseif($string=='' and $null_if_empty){
    return 'NULL';
  }else{
     return "'".addslashes($string)."'";
  

  }
}


function prepare_mysql_date($string,$default='NOW()'){
  if($string=='')
    return $default;
  else{
     $string=str_replace("'",'',$string);
    return "'".addslashes($string)."'";
  }
}


function is_url($url){
  if(preg_match('/^(www\.)?[a-z0-9-]+(.[a-z0-9-]+)*\.(com|uk|fr|biz|net|info|mx|jp|org)$/i',$url))
    return true;
  else
    return false;
}





function array_transverse($a,$cols){
  $total=count($a);
  $rows=ceil($total/$cols);
  $to_add=($rows*$cols)-$total;
  $new_total=($rows*$cols);
  for($i=0;$i<$to_add;$i++){
    $a[]='';
  }
  $tmp=$cols;
  $cols=$rows;
  $rows=$tmp;
  
  // print "$total $cols $rows $to_add $new_total\n";


  $i=0;
  $j=-1;
  $old=array();
  foreach($a as $key=>$value){

  


  if(fmod($i,$cols)==0){
    $i=0;
    $j++;
  }
  //  print "$key $value ; $i $j\n";
  $old[$i][$j]=$value;
  $i++;
}
 $new=array();
for($i=0;$i<$cols;$i++){
  for($j=0;$j<$rows;$j++){
       $new[]=$old[$i][$j];
 }



 }
 return array($new,$rows);
 
}



function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
  /*
   $interval can be:
   yyyy - Number of full years
   q - Number of full quarters
   m - Number of full months
   y - Difference between day numbers
   (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
   d - Number of full days
   w - Number of full weekdays
   ww - Number of full weeks
   h - Number of full hours
   n - Number of full minutes
   s - Number of full seconds (default)
  */
  
  if (!$using_timestamps) {
    $datefrom = strtotime($datefrom, 0);
    $dateto = strtotime($dateto, 0);
  }
  $difference = $dateto - $datefrom; // Difference in seconds
  
 switch($interval) {

 case 'yyyy': // Number of full years
  
 $years_difference = floor($difference / 31536000);
 if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
 $years_difference--;
 }
 if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
 $years_difference++;
 }
 $datediff = $years_difference;
 break;
  
 case "q": // Number of full quarters
  
 $quarters_difference = floor($difference / 8035200);
 while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
 $months_difference++;
 }
 $quarters_difference--;
 $datediff = $quarters_difference;
 break;
  
 case "m": // Number of full months
  
 $months_difference = floor($difference / 2678400);
 while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
 $months_difference++;
 }
 $months_difference--;
 $datediff = $months_difference;
 break;
  
 case 'y': // Difference between day numbers
  
 $datediff = date("z", $dateto) - date("z", $datefrom);
 break;
  
 case "d": // Number of full days
  
 $datediff = floor($difference / 86400);
 break;
  
 case "w": // Number of full weekdays
  
 $days_difference = floor($difference / 86400);
 $weeks_difference = floor($days_difference / 7); // Complete weeks
 $first_day = date("w", $datefrom);
 $days_remainder = floor($days_difference % 7);
 $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
 if ($odd_days > 7) { // Sunday
 $days_remainder--;
 }
 if ($odd_days > 6) { // Saturday
 $days_remainder--;
 }
 $datediff = ($weeks_difference * 5) + $days_remainder;
 break;
  
 case "ww": // Number of full weeks
  
 $datediff = floor($difference / 604800);
 break;
  
 case "h": // Number of full hours
  
 $datediff = floor($difference / 3600);
 break;
  
 case "n": // Number of full minutes
  
 $datediff = floor($difference / 60);
 break;
 
 default : // Number of full seconds (default)
  
 $datediff = $difference;
 break;
 }
  
 return $datediff;
  
 }

function number_weeks($days,$day){

  $rainbow=array(
		 array(0,0.2,0.2,0.2,0.4,0.6,0.8),
		 array(0,0.2,0.4,2,0.4,0.6,0.8),
		 array(0,0.2,0.4,0.6,0.6,0.6,0.8),
		 array(0,0.2,0.4,0.6,0.8,0.8,0.8),
		 array(0,0.2,0.4,0.6,0.8,1,1),
		 array(0,0,0.2,0.4,0.6,0.8,1),
		 array(0,0,0,0.2,0.4,0.6,0.8)
		 );
  //print fmod($days,7);
  // print " $day \n";
  return floor($days/7)+$rainbow[$day][fmod($days,7)];
}

function array_change_key_name( $orig, $new, &$array )
{
    foreach ( $array as $k => $v )
        $return[ ( $k === $orig ) ? $new : $k ] = $v;
    return ( array ) $return;
}


function average($array){
    $sum   = array_sum($array);
    $count = count($array);
    if($count==0)
      return false;
    return $sum/$count;
}

//The average function can be use independantly but the deviation function uses the average function.

function deviation ($array){
   
    $avg = average($array);
    if(!$avg)
      return false;

    foreach ($array as $value) {
        $variance[] = pow($value-$avg, 2);
    }
    $deviation = sqrt(average($variance));
    return $deviation;
}



function currency_conversion ($currency_from, $currency_to) {
  $reload=false;
  $in_db=false;
  $exchange_rate=1;
  //get info from database;
  $sql=sprintf("select * from kbase.`Currency Exchange Dimension` where `Currency Pair`=%s",prepare_mysql($currency_from.$currency_to));
  $res = mysql_query($sql);
  if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    if(strtotime($row['Currency Exchange Last Updated'])<date("Y-m-d H:i:s",strtotime('today -1 hour')))
      $reload=true;
    $exchange_rate=$row['Exchange'];
  }else{
    $reload=true;
    $in_db=false;
  }
  if($reload){
  $url = "http://quote.yahoo.com/d/quotes.csv?s=". $currency_from . $currency_to . "=X". "&f=l1&e=.csv";
  // print $url;
  $handle = fopen($url, "r");
  $contents = fread($handle,2000);
  fclose($handle);
  if(is_numeric($contents) and $contents>0){
    $exchange_rate=$contents;
    if($in_db){
      $sql=sprintf("update `Currency Exchange Dimension` set `Exchange`=%f,`Currency Exchange Last Updated`=NOW() where `Currency Pair`=%s",$exchange_rate,prepare_mysql($currency_from.$currency_to));
      $res = mysql_query($sql);
    }else{
      $sql=sprintf("intert into `Currency Exchange Dimension`  (`Currency Pair`,`Exchange`,`Currency Exchange Last Updated`,`Currency Exchange Source`) values (%s,%f,NOW(),'Yahoo')",prepare_mysql($currency_from.$currency_to),$exchange_rate);
      $res = mysql_query($sql);
    }
      

  }
  

  }

  return $exchange_rate;
}



  /**
     *  compares two strings and returns longest common substring
     *
     *  Compares the two source strings character by character, captures every common substring
     *  between them, and returns the longest common substring found. Substrings of less than
     *  two characters long are ignored, and if there are multiple longest common substrings,
     *  the one that appears first in the first source string is returned.
     *
     *  @author Charlie Greenbacker charlie@artificialminds.net
     *
     *  @param $str1 - String - first source string for comparison
     *  @param $str2 - String - second source string for comparison
     *
     *  @return String - longest common substring of the two source strings
     */
    function strlcs($str1, $str2)
    {
        $arySubstrings = array(); //stores all common substrings
        //iterate one-by-one through every character in both strings
        for ($i = 0; $i < strlen($str1); $i++) {
            for ($j = 0; $j < strlen($str2); $j++) {
                /* common substrings of less than 2 characters are inconsequential, so a match is
                 * initiated only when a common substring with a length of 2 is found
                 */
                if (substr($str1, $i, 2) == substr($str2, $j, 2)) { //initial match found
                    $substring = substr($str1, $i, 2); //start with first 2 matching characters
                    /* $i_temp is used to move character-by-character in $str1 while keeping track
                     * of the starting position of the substring with $i
                     */
                    $i_temp = $i + 2;
                    $j = $j + 2; //move to the next character after the initial match in $str2
                    /* continue while subsequent character pairs match and the ends of both strings
                     * have not been reached
                     */
                    while (($str1{$i_temp} == $str2{$j}) && ($i_temp < strlen($str1)) && ($j < strlen($str2))) {
                        //append this matched character to the end of the substring
                        $substring .= $str1{$i_temp};
                        $i_temp++; //move to the next character pair
                        $j++;
                    }
                    $arySubstrings[] = trim($substring); //remove excess whitespace and add to array
                }
            }
        }
        $arySubstrings = array_unique($arySubstrings); //remove duplicate common substrings
        /* return the longest substring in the array; if more than one are longest,
         * the first of them is returned
         */
        $strLCS = $arySubstrings[0];
        foreach ($arySubstrings as $strCurrent) {
            if (strlen($strCurrent) > strlen($strLCS)) {
                $strLCS = $strCurrent;
            }
        }
        return $strLCS;
    }

/*
Function array_empty
Check if all elemaents of the array are empty

Parameter: an associative array

Return:
true - if all elemtents are empty or zero
false -if one or more elements have a non-empty or non-zero value

 */

function array_empty($array){
  if(is_array($array)){
    foreach($array as $value){
      if(!empty($value))
	return false;
    }
    return true;
  }else
    return empty($array);
}
/*
function:yearweek
returns: equivalent of YEARWEEK mysql function
 */
function yearweek($str){
  $date=strtotime($str);
  $w=date('W',$date);
  $y=date("Y",$date);
  $m=date("m",$date);

  if($w==1 and $m==12){
    $y=$y+1;
  }
 if($w>=52 and $m==1){
    $y=$y-1;
  }
 return sprintf("%d%02d",$y,$w);
}

function quarter($date){
 $date=strtotime($date);
  $month=date('m',$date);
  if($month<=3)
    return 1;
  elseif($month<=6)
    return 2;
  elseif($month<=9)
    return 3;
  else
    return 4;

}

function yearquarter($date){

  return date('Y',strtotime($date)).quarter($date);
  
}


function translate_written_number($string){
  
  $numbers=array('zero','one','two','three','four','five','six','seven','eight','nine','ten','eleven');
  $common_suffixes=array('hundreds?'=>100,'thousands?'=>1000,'millons?'=>100000);
  
  $number_flat=join("|",$numbers);
  $common_suffixes_flat=join("|",$common_suffixes);
  if(preg_match("/$number_flat/i",$string)){
    if(preg_match("/$common_suffixes_flat/i",$string)){
      foreach($numbers as $number=>$number_string){
	    foreach($common_suffixes as $common_suffix=>$number_common_suffix){
	        $string=_trim(preg_replace('/^(.*\s+|)$number_string\s?$common_suffix(\s+.*|)$/ '," ".($number*$number_common_suffix)." ",$string));
            }
        }
   }else{
      foreach($numbers as $number=>$number_string)
	$string=_trim(preg_replace('/^(.*\s+|)$number_string(\s+.*|)$/ '," $number ",$string));
    }
  }
  return $string;
}    

 function shuffle_assoc(&$array) {
        if(count($array)==0)
           return;
        $keys = array_keys($array);

        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $array[$key];
        }

        $array = $new;

        return true;
    }


function guess_file_mime($file){
    
    ob_start();
    system("uname");
    $mimetype='unknown';
    $system='Unknown';
    
     $_system = ob_get_clean();
   
   if(preg_match('/darwin/i',$_system)){
     ob_start();
     $system='Mac';
     system("file -I $file");
     $mimetype=ob_get_clean();
     
   }elseif(preg_match('/linux/i',$_system)){
     $system='Linux'; 
     ob_start();
     system("file -ib $file ");
     $mimetype=ob_get_clean();
   }else{
     $system='Other';  
   }
   //ob_get_clean();
   
   return $mimetype;
  
}






function formatBytes($bytes, $precision = 1) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
  
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
  
    $bytes /= pow(1024, $pow);
  
    return number($bytes, $precision) . ' ' . $units[$pow];
} 


function getEnumVals($table,$field,$sorted=true)
{
   
    $result=mysql_query('show columns from '.$table.';');
    while($tuple=mysql_fetch_assoc($result))
    {
        if($tuple['Field'] == $field)
        {
            $types=$tuple['Type'];
            $beginStr=strpos($types,"(")+1;
            $endStr=strpos($types,")");
            $types=substr($types,$beginStr,$endStr-$beginStr);
            $types=str_replace("'","",$types);
            $types=preg_split('/\,/',$types);
            if($sorted)
                sort($types);
            break;
        }
    }
  
    return($types);
} 

function parse_number($value){
if(is_numeric($value))
return $value;

$value=preg_replace('/[^\.^\,\d]/','',$value);
if(preg_match('/\.\d?$/',$value)){
$value=preg_replace('/\,/','',$value);

}elseif(preg_match('/\..*\,\d?$/',$value)){
$value=preg_replace('/\./','',$value);
$value=preg_replace('/,/',',',$value);
}
return (float) $value;


}

function parse_weight($value){
$unit='Kg';
$value=_trim($value);
if(preg_match('/(kg|kilo?|kilograms?)$/i',$value)){
    $value=parse_number($value);
    $unit='Kg';
}elseif(preg_match('/(lb?s|pounds?|libras?)$/i',$value)){
    $value=parse_number($value)*.4545 ;
    $unit='Lb';
}elseif(preg_match('/(g|grams?|gms)$/i',$value)){
    $value=parse_number($value)*0.001 ;
    $unit='g';
}elseif(preg_match('/(tons?|tonnes?|t)$/i',$value)){
    $value=parse_number($value)*1000 ;
    $unit='t';
}else
$value=parse_number($value);

return array($value,$unit);
}


function convert_weigth($value,$from,$to){
  $factors['KgKg']=1;
  $factors['LbLb']=1;
  $factors['gg']=1;
  $factors['tt']=1;

  $factors['KgLb']=2.2;
  $factors['Kgg']=1000;
  $factors['Kgt']=.001;

  $factors['gLb']=.0022;
  $factors['gKg']=.001;
  $factors['gt']=.000001;

  $factors['tLb']=2200;
  $factors['tg']=1000000;
  $factors['tKg']=1000;
 
  $factors['LbKg']=0.4545;
  $factors['Lbg']=454.5;
  $factors['Lbt']=0.0004545;

  if(array_key_exists($from.$to,$factors)){
    return $factors[$from.$to]*$value;
  }else
    return $value;


}

function parse_volume($value){
$unit='L';
$value=_trim($value);
if(preg_match('/(cc|cm3)$/i',$value)){
    $value=parse_number($value)*.001 ;
    $unit='cm3';
}elseif(preg_match('/(cubic meter|cubic m|m3)$/i',$value)){
    $value=parse_number($value)*1000 ;
    $unit='m3';
}elseif(preg_match('/(mL)$/i',$value)){
    $value=parse_number($value)*001 ;
    $unit='mL';
}else
$value=parse_number($value);

return array($value,$unit);
}

function parse_distance($value){


$original_unit='m';
$value=_trim($value);
if(preg_match('/(cent.metro?|centimeter?|cm)$/i',$value)){
    $value=parse_number($value)/100 ;
    $original_unit='cm';
}elseif(preg_match('/(mm|milimiters?)$/i',$value)){
    $value=parse_number($value)/1000 ;
    $original_unit='mm';
}elseif(preg_match('/(inches|inch|in)$/i',$value)){
    $value=parse_number($value)*0.0254;
    $original_unit='inch';
}elseif(preg_match('/(foot|feets?|ft)$/i',$value)){
    $value=parse_number($value)*0.3048;
    $original_unit='ft';
}elseif(preg_match('/(mile?|milles?)$/i',$value)){
    $value=parse_number($value)* 1609.344;
    $original_unit='mile';
}else
$value=parse_number($value);

return array($value,$original_unit);
}

function distance($value,$unit='m'){
  switch($unit){
  case('cm'):
    return number($value*100).'cm';
  case('mm'):
    return number($value*1000).'mm';
  default:
    return number($value).'m';
  }

}


function volume($value,$unit='L'){

return number($value).'L';
}


function parse_parcels($value){
$unit='Box';
$value=_trim($value);
if(preg_match('/(pallet)$/i',$value)){
    $value=parse_number($value)*.4545 ;
    $unit='Pallet';
}elseif(preg_match('/(sobre|envelope)$/i',$value)){
    $value=parse_number($value)*0.001 ;
    $unit='Envelope';
}else
$value=parse_number($value);

return array($value,$unit);
}


function number2alpha($number){
  $alpha=  chr(65+fmod($number-1,26));
  $pos=floor(($number-1)/26);
  
  $prefix='';
  if($pos>0){
    $prefix=number2alpha($pos);
  }
  
  return $prefix.$alpha;




  

}


function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy'.md5(mt_rand());
	$consonants = 'bdghjmnpqrstvz'.md5(mt_rand());
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZlkjhgfduytrdqwertyuipasdfghjkzxcvbnm';
	}
	if ($strength & 2) {
		$vowels .= "AEUI";
	}
	if ($strength & 4) {
		$consonants .= '2345678906789$%^&*(';
	}
	if ($strength & 8) {
		$consonants .= '!=/[]{}~\<>$%^&*()_+@#.,)(*%%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(mt_rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(mt_rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

function get_corporation_data(){
$sql=sprintf("select * from `HQ Dimension`");
$res=mysql_query($sql);
$corporate_data=array('HQ Currency'=>'GBP');
if($corporate_data=mysql_fetch_assoc($res)){true;}
return $corporate_data;
}

function xml2array_to_delete($xml) {
        $xmlary = array();
               
        $reels = '/<(\w+)\s*([^\/>]*)\s*(?:\/>|>(.*)<\/\s*\\1\s*>)/s';
        $reattrs = '/(\w+)=(?:"|\')([^"\']*)(:?"|\')/';

        preg_match_all($reels, $xml, $elements);

        foreach ($elements[1] as $ie => $xx) {
                $xmlary[$ie]["name"] = $elements[1][$ie];
               
                if ($attributes = trim($elements[2][$ie])) {
                        preg_match_all($reattrs, $attributes, $att);
                        foreach ($att[1] as $ia => $xx)
                                $xmlary[$ie]["attributes"][$att[1][$ia]] = $att[2][$ia];
                }

                $cdend = strpos($elements[3][$ie], "<");
                if ($cdend > 0) {
                        $xmlary[$ie]["text"] = substr($elements[3][$ie], 0, $cdend - 1);
                }

                if (preg_match($reels, $elements[3][$ie]))
                        $xmlary[$ie]["elements"] = xml2array($elements[3][$ie]);
                else if ($elements[3][$ie]) {
                        $xmlary[$ie]["text"] = $elements[3][$ie];
                }
        }

        return $xmlary;
}

?>
