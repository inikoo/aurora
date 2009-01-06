<?

// function prepare_mysql_date($date){

//   if($date=='')
//     return array('',0);
//   if(!preg_match('/^[0123]\d[\-\/][01]\d[\-\/]\d{4}$/',$date)){
//     return array('',1);

//   }

//   $date=str_replace('/','-',$date);
//   $date=split('-',$date);
//   $mysql_date= join ('-',array_reverse($date));
//   return array($mysql_date,0);
// }


function unformat_money($number){
  $number=preg_replace('/\\'.$myconf['thosusand_sep'].'/','',$number);
  $number=preg_replace('/\\'.$myconf['decimal_point'].'/','.',$number);
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
  if($tipo=='datetime'){
     if(preg_match('/^[0123]\d[\-\/][01]\d[\-\/]\d{4}\s[012]\d:[0123456]\d$/',$datetime))
       $datetime=$datetime.':00';
    if(!preg_match('/^[0123]\d[\-\/][01]\d[\-\/]\d{4}\s[012]\d:[0123456]\d:[0123456]\d$/',$datetime))
      return array('mysql_date'=>'','status'=>_('error, date time not reconozied')." $datetime",'ok'=>false);
    $ts=date('U',strtotime($datetime));
    list($date,$time)=split(' ',$datetime);
  }else{
    if(!preg_match('/^[0123]\d[\-\/][01]\d[\-\/]\d{4}/',$datetime))
      return array('mysql_date'=>'','status'=>'wrong date','ok'=>false);
    $date=$datetime;
    $ts=date('U',strtotime($date));
  }

  

  
  $date=str_replace('/','-',$date);
  $date=split('-',$date);
  if($tipo=='date'){

    $mysql_datetime= join ('-',array_reverse($date));


  }else
    $mysql_datetime= trim(join ('-',array_reverse($date)).' '.$time);
  
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
  list($date1['y'],$date1['m'],$date1['d'])=split('-',$mysql_date1);
  list($date2['y'],$date2['m'],$date2['d'])=split('-',$mysql_date2);
  $base=array();


  switch($step){
  case('m'):

    if(preg_match('/(^|\s|,)complete($|\s|,)|complete_both|complete_first|complete_from|only_complete|complete months/i',$tipo)){

      if($date1['d']>1){
	list($date1['y'],$date1['m'],$date1['d'])=split('-',date("Y-m-d", mktime(0, 0, 0, $date1['m']+1, 1, $date1['y'])));
      }
    }
    if(preg_match('/(^|\s|,)complete($|\s|,)|complete_both|complete_second|complete_to|only_complete|complete months/i',$tipo)){
      $last_day= getLastDayOfMonth($date2['m'], $date2['y']);

      if($date2['d']!= $last_day  ){
	list($date2['y'],$date2['m'],$date2['d'])=split('-',date("Y-m-d", mktime(0, 0, 0, $date2['m']-1,$last_day-1 , $date2['y'])));
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

  if(preg_match('/dates?_only|dates? only|only dates?/i',$options)){
    $d_option='date';
    $date_only=true;
  }else{
    $d_option='';
    $date_only=false;
  }
  $tmp=prepare_mysql_datetime($date1,$d_option);
  $mysql_date1=$tmp['mysql_date'];
  $ok1=$tmp['ok'];
  if($tmp['status']=='empty')
    $ok1=true;

  $tmp=prepare_mysql_datetime($date2,$d_option);
  $mysql_date2=$tmp['mysql_date'];
  $ok2=$tmp['ok'];
  if($tmp['status']=='empty')
    $ok2=true;
  if(!$ok1 or !$ok2)
    $error=1;
  else
    $error=0;

  
  if(preg_match('/complete months/i',$options)){
    list($_date1['y'],$_date1['m'],$_date1['d'])=split('-',$mysql_date1);
    list($_date2['y'],$_date2['m'],$_date2['d'])=split('-',$mysql_date2);
    if($_date1['d']>1)
      list($_date1['y'],$_date1['m'],$_date1['d'])=split('-',date("Y-m-d", mktime(0, 0, 0, $_date1['m']+1, 1, $_date1['y'])));
    $last_day= getLastDayOfMonth($_date2['m'], $_date2['y']);
    if($_date2['d']!= $last_day  )
      list($_date2['y'],$_date2['m'],$_date2['d'])=split('-',date("Y-m-d", mktime(0, 0, 0, $_date2['m']-1,$last_day-1 , $_date2['y'])));
    
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
  $per='';
  $error_txt=_($error_txt);
  if($b>0){
    if($plus_sing and $a>0)
      $sing='+';
    else
      $sing='';
    // PRINT "** $a $b ".$sing.number_format(100*($a/$b),1,$_SESSION['locale_info']['decimal_point'],$_SESSION['locale_info']['thousands_sep']).$psign." <br>";
    $per=$sing.number_format((100*($a/$b)),$fixed,$_SESSION['locale_info']['decimal_point'],$_SESSION['locale_info']['thousands_sep']).$psign;
    //    $per=$sing.number_format(100*($a/$b),$fixed,$_SESSION['locale_info']['decimal_point'],$_SESSION['locale_info']['thousands_sep']).$psign;
    // print $per."  $fixed $psign $sing ".$_SESSION['locale_info']['decimal_point']." ".$_SESSION['locale_info']['thousands_sep']." <br>";
  }
  else
    $per=$error_txt;
  return $per;
}

function money($a,$symbol=true){
  global $myconf;
  if($a<0)
    $neg=true;
  else
    $neg=false;
  $a=abs($a);

  $a=number_format($a,2,$myconf['decimal_point'],$myconf['thosusand_sep']);
  if($symbol)
    $a=($neg?'-':'').$myconf['currency_symbol'].$a;
  return $a;
}

function money_cents($a){
  $a=sprintf("%02d",100*($a-floor($a)));
  return $a;
}

function number($a,$fixed=1,$force_fix=false){


  $floored=floor($a);
  if($floored==$a and !$force_fix)
    $fixed=0;

  $a=number_format($a,$fixed,$_SESSION['locale_info']['decimal_point'],$_SESSION['locale_info']['thousands_sep']);
  
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
    $day_from=split('-',$from);
    $day_to=split('-',$to);


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
    $interval=number($interval/3600/24,2);
    
  }

  return $interval;
}

function extract_product_groups($str,
				$q_prod_name='product.code like',
				$q_prod_id='transaction.product_id like',
				$q_group_name='product_group.name like',
				$q_group_id='product_group.id like',
				$q_department_name='product_department.name like',
				$q_department_id='product_department.id like'
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

  if(preg_match_all('/g\([a-z0-9\-\,]*\)/i',$str,$matches)){
    

    foreach($matches[0] as $match){
      
      $_groups=preg_replace('/\)$/i','',preg_replace('/^g\(/i','',$match));
      $_groups=preg_split('/\s*,\s*/i',$_groups);

      foreach($_groups as $group){
	$group_ordered=addslashes($group);
	if(is_numeric($group_ordered))
	  $where_g.=" or $q_group_id  '$group_ordered'";
	else
	  $where_g.=" or $q_group_name '$group_ordered'";
      }
    }
    $str=preg_replace('/g\([a-z0-9\-\,]*\)/i','',$str);
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



function _trim($string){
  $string=preg_replace('/^\s*/','',$string);
  $string=preg_replace('/\s*$/','',$string);
  $string=preg_replace('/\s+/',' ',$string);

  return $string;
}

function mb_ucwords($str) {
  $str=_trim($str);
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

    
  if($str=='' or $str==',' or  $str=='+')
    return $str;
  //  print "$str\n";


  $separator = array("-","+",","," ");
   
  $str = mb_strtolower(trim($str),"UTF-8");
  foreach($separator as $s){
    $word = explode($s, $str);

    $return = "";
    foreach ($word as $val){
      //print "* $val\n";
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
	$return .= $s 
	  . mb_strtoupper($val{0},"UTF-8") 
	  . mb_substr($val,1,mb_strlen($val,"UTF-8")-1,"UTF-8");
      }

    }
    $str = mb_substr($return, 1);
  }
  
  $return{1}=mb_strtoupper($return{1},"UTF-8");
  $return=mb_substr($return, 1);

  foreach($exceptions as $find=>$replace){
    $return = preg_replace('/\s+'.$find.'\s+|\s+'.$find.'$/', ' '.$replace.' ', $return);
    
  }
  $return=_trim($return);
  //  }else
  //  $return='';
  // print $return."\n";
  return $return;
}


function prepare_mysql($string,$no_null=false){
  if(is_array($string)){
    print "Warning is array (prepare_mysql)\n";
    print_r($string);
    return 'NULL';
  }

  if($no_null)
    return "'".addslashes($string)."'";
  else
    return ($string==''?'null':"'".addslashes($string)."'");
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

?>