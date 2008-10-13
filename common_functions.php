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

function prepare_mysql_datetime($datetime,$tipo='datetime'){

  if($datetime=='')
    return array('',0);

  $time='';
  if($tipo=='datetime'){
    if(!preg_match('/^[0123]\d[\-\/][01]\d[\-\/]\d{4}\s[012]\d:[0123456]\d:[0123456]\d$/',$datetime))
      return array('',1);
    list($date,$time)=split(' ',$datetime);
  }else{
    if(!preg_match('/^[0123]\d[\-\/][01]\d[\-\/]\d{4}/',$datetime))
      return array('',1);
    
    $date=$datetime;
  }

  

  
  $date=str_replace('/','-',$date);
  $date=split('-',$date);
  if($tipo=='date'){

    $mysql_datetime= join ('-',array_reverse($date));


  }else
    $mysql_datetime= trim(join ('-',array_reverse($date)).' '.$time);
  
 
  return array($mysql_datetime,0);
}
 

function getLastDayOfMonth($month, $year)
{
return idate('d', mktime(0, 0, 0, ($month + 1), 0, $year));
}


function date_base($from,$to,$step='m',$tipo='complete_both'){


  list($mysql_date1,$error1)=prepare_mysql_datetime($from,'date');
  list($mysql_date2,$error2)=prepare_mysql_datetime($to,'date');
  if($error1 or $error2)
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
  list($mysql_date1,$error1)=prepare_mysql_datetime($date1,$d_option);
  list($mysql_date2,$error2)=prepare_mysql_datetime($date2,$d_option);

  if($error1 or $error2)
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
  if($a<0)
    $neg=true;
  else
    $neg=false;
  $a=abs($a);

  $a=number_format($a,2,$_SESSION['locale_info']['mon_decimal_point'],$_SESSION['locale_info']['mon_thousands_sep']);
  if($symbol)
    $a=($neg?'-':'').$_SESSION['locale_info']['currency_symbol'].$a;
  return $a;
}

function money_cents($a){
  $a=sprintf("%02d",100*($a-floor($a)));
  return $a;
}

function number($a,$fixed=1){
    $floored=floor($a);
  if($floored==$a)
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


function get_time_interval($d1,$d2,$units='days'){
  $interval=$d2-$d1;
  switch($units){
  case('days'):
    $interval=number($interval/3600/24,2);
    
  }

  return $interval;
}

?>