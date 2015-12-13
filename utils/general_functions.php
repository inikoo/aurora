<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 13 December 2015 at 16:23:32 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW

function get_prev_next($pivot, $array) {
	$prev_key=current($array);
	$next_key=false;
	while (current($array) !== $pivot && key($array) !== null) {
		$prev_key=current($array);
		next($array);
	}
	$current_key=current($array);
	if ($prev_key==$current_key) {
		$next_key=next($array);
		$prev_key=end($array);
	}else {
		$next_key=next($array);
		if (!$next_key) {
			$next_key=reset($array);
		}

	}
	return array($prev_key, $next_key);
}



function getEnumValues($table, $field) {
	$enum_array = array();
	$query = 'SHOW COLUMNS FROM `' . $table . '` LIKE "' . $field . '"';
	// print $query;
	$result = mysql_query($query);
	$row = mysql_fetch_row($result);
	preg_match_all('/\'(.*?)\'/', $row[1], $enum_array);
	if (!empty($enum_array[1])) {
		// Shift array keys to match original enumerated index in MySQL (allows for use of index values instead of strings)
		foreach ($enum_array[1] as $mkey => $mval) $enum_fields[$mkey+1] = $mval;
		return $enum_fields;
	} else return array(); // Return an empty array to avoid possible errors/warnings if array is passed to foreach() without first being checked with !empty().
}






function get_currency_symbol_from_locale($locale) {
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


function clean_accents($str) {


	$str=preg_replace('/é|è|ê|ë|æ/', 'e', $str);
	$str=preg_replace('/á|à|â|ã|ä|å|æ|ª/', 'a', $str);
	$str=preg_replace('/ù|ú|û|ü/', 'u', $str);
	$str=preg_replace('/ò|ó|ô|õ|ö|ø|°/', 'o', $str);
	$str=preg_replace('/ì|í|î|ï/', 'i', $str);

	$str=preg_replace('/É|È|Ê|Ë|Æ/', 'E', $str);
	$str=preg_replace('/Á|À|Â|Ã|Ä|Å|Æ|ª/', 'A', $str);
	$str=preg_replace('/Ù|Ú|Û|Ü/', 'U', $str);
	$str=preg_replace('/Ò|Ó|Ô|Õ|Ö|Ø|°/', 'O', $str);
	$str=preg_replace('/Ì|Í|Î|Ï/', 'I', $str);

	$str=preg_replace('/ñ/', 'n', $str);
	$str=preg_replace('/Ñ/', 'N', $str);
	$str=preg_replace('/ç|¢|©/', 'c', $str);
	$str=preg_replace('/Ç/', 'C', $str);
	$str=preg_replace('/ß|§/i', 's', $str);

	return $str;
}


function unformat_money($number) {
	$locale_info = localeconv();


	$number=preg_replace('/\\'.$locale_info['thousand_sep'].'/', '', $number);
	$number=preg_replace('/\\'.$locale_info['decimal_point'].'/', '.', $number);
	return $number;
}



function prepare_mysql_dates($date1='', $date2='', $date_field='date', $options='') {



	$start='';
	$end='';
	if (preg_match('/start.*end/i', $options)) {
		$start=' start';
		$end=' end';

	}
	if (preg_match('/(dates?_only|dates? only|only dates|date|whole_day|only_dates?)/i', $options)) {
		$d_option='date';



		$date_only=true;
	} else {
		$d_option='datetime';
		$date_only=false;
	}


	$tmp=prepare_mysql_datetime($date1, $d_option.$start);



	$mysql_date1=$tmp['mysql_date'];
	$ok1=$tmp['ok'];
	if ($tmp['status']=='empty')
		$ok1=true;

	$tmp=prepare_mysql_datetime($date2, $d_option.$end);
	$mysql_date2=$tmp['mysql_date'];

	$ok2=$tmp['ok'];
	if ($tmp['status']=='empty')
		$ok2=true;

	if (!$ok1 or !$ok2)
		$error=1;
	else
		$error=0;


	if (preg_match('/complete months/i', $options)) {
		list($_date1['y'], $_date1['m'], $_date1['d'])=preg_split('/-/', $mysql_date1);
		list($_date2['y'], $_date2['m'], $_date2['d'])=preg_split('/-/', $mysql_date2);
		if ($_date1['d']>1)
			list($_date1['y'], $_date1['m'], $_date1['d'])=preg_split('/-/', date("Y-m-d", mktime(0, 0, 0, $_date1['m']+1, 1, $_date1['y'])));
		$last_day= getLastDayOfMonth($_date2['m'], $_date2['y']);
		if ($_date2['d']!= $last_day  )
			list($_date2['y'], $_date2['m'], $_date2['d'])=preg_split('/-/', date("Y-m-d", mktime(0, 0, 0, $_date2['m']-1, $last_day-1 , $_date2['y'])));

		$mysql_date1=$_date1['y'].'-'.$_date1['m'].'-'.$_date1['d'];
		$mysql_date2=$_date2['y'].'-'.$_date2['m'].'-'.$_date2['d'];

	}




	if (is_array($date_field)) {
		$date_field1=addslashes($date_field[0]);
		$date_field2=addslashes($date_field[1]);
	}else {

		$date_field1=addslashes($date_field);
		$date_field2=addslashes($date_field);

	}

	if ($options=='whole_day') {
		$mysql_date1=($mysql_date1==''?'':$mysql_date1.' 00:00:00');
		$mysql_date2=($mysql_date2==''?'':$mysql_date2.' 23:59:59');

	}

	if ($mysql_date2=='' and $mysql_date1=='' ) {
		$mysql_interval="";
	}elseif ($mysql_date2!='' and $mysql_date1!='') {
		$mysql_interval=" and $date_field1>='$mysql_date1' and $date_field2<='$mysql_date2'";

	}elseif ($mysql_date2!='') {
		$mysql_interval=" and $date_field2<='$mysql_date2'";
	}else {
		$mysql_interval=" and $date_field1>='$mysql_date1' ";
	}



	return array('0'=>$mysql_interval, '1'=>$date1, '2'=>$date2, '3'=>$error, 'error'=>$error, 'mysql'=>$mysql_interval, 'from'=>$date1, 'to'=>$date2, 'mysql_from'=>$mysql_date1, 'mysql_to'=>$mysql_date2);


}


function prepare_mysql_datetime($datetime, $tipo='datetime') {

	//print "** $tipo \n";exit;
	if ($datetime=='')
		return array('mysql_date'=>'', 'status'=>_('Empty field'), 'ok'=>false);
	$time='';

	if (preg_match('/datetime/', $tipo)) {
		if (preg_match('/^[12]\d{3}[\-\/][01]\d[\-\/][0123]\d\s[012]\d:[0123456]\d$/', $datetime))
			$datetime=$datetime.':00';

		if (preg_match('/^[0123]\d[\-\/][01]\d[\-\/][12]\d{3} /', $datetime)) {
			$_tmp=preg_split('/\s/', $datetime);

			$tmp=preg_split('/\-|\//', $_tmp[0]);
			if (count($tmp)==3) {
				$_datetime=$tmp[2].'-'.$tmp[1].'-'.$tmp[0];
			}

			$datetime=$_datetime.' '.$_tmp[1];




		}

		if (!preg_match('/^[12]\d{3}[\-\/][01]\d[\-\/][0123]\d\s[012]\d:[0123456]\d:[0123456]\d$/', $datetime))
			return array('mysql_date'=>'', 'status'=>"Error, date time not recognised $datetime", 'ok'=>false);
		$ts=date('U', strtotime($datetime));
		list($date, $time)=preg_split('/\s+/', $datetime);

		//exit;

	} else {


		if (preg_match('/[0123]\d[\-\/][01]\d[\-\/][12]\d{3}/', $datetime)) {
			$tmp=preg_split('/\-|\//', $datetime);
			if (count($tmp)==3) {
				$datetime=$tmp[2].'-'.$tmp[1].'-'.$tmp[0];
			}
		}

		if (!preg_match('/^[12]\d{3}[\-\/][01]\d[\-\/][0123]\d/', $datetime))
			return array('mysql_date'=>'', 'status'=>_('Invalid date'), 'ok'=>false);
		$date=$datetime;
		$ts=date('U', strtotime($date));
	}


	//BfxcGlE80Qt;D

	$date=str_replace('/', '-', $date);
	$date=preg_split('/-/', $date);


	if (preg_match('/datetime/', $tipo)) {

		$mysql_datetime= trim(join('-', $date).' '.$time);
	} else {


		$mysql_datetime= join('-', $date);
		if (preg_match('/start/i', $tipo))
			$mysql_datetime.=' 00:00:00';
		if (preg_match('/midday/i', $tipo))
			$mysql_datetime.=' 12:00:00';
		elseif (preg_match('/end/i', $tipo))
			$mysql_datetime.=' 23:59:59';

	}
	return array('ts'=>$ts, 'mysql_date'=>$mysql_datetime, 'status'=>'ok', 'ok'=>true);

}


function prepare_mysql_date($string, $default='NOW()') {
	if ($string=='')
		return $default;
	else {
		$string=str_replace("'", '', $string);
		return "'".addslashes($string)."'";
	}
}




function getLastDayOfMonth($month, $year) {
	return idate('d', mktime(0, 0, 0, ($month + 1), 0, $year));
}





function getmicrotime() {
	list($usec, $sec) = explode(" ", microtime());
	return (float)$usec + (float)$sec;
}



function delta($current_value, $old_value) {

	if ($current_value==$old_value) {
		return '--';
	}
	return percentage($current_value-$old_value, $old_value, 1, 'NA', '%', true);
}


function percentage($a, $b, $fixed=1, $error_txt='NA', $psign='%', $plus_sing=false) {

	$locale_info = localeconv();

	$per='';
	$error_txt=_($error_txt);
	if ($b>0) {
		if ($plus_sing and $a>0)
			$sing='+';
		else
			$sing='';
		$per=$sing.number_format((100*($a/$b)), $fixed, $locale_info['decimal_point'], $locale_info['thousands_sep']).$psign;
	} else
		$per=$error_txt;
	return $per;
}


/* Function: parse_money

 Parse a string extracting an numeric value

 Parameter:
 amount - *string* String to be parsed
 currency - *string* Currency  [£|¥|€|(3 Letter Currency Code)|false=$corporate_currency ]


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
function parse_money($amount, $currency=false) {
	global $myconf, $corporate_currency;
	// preg_match('/(\$|\£|\€|EUR|GBP|USD)[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?/',$term_description , $match){

	$locale_info = localeconv();

	if (!$currency)
		$currency=$corporate_currency;
	else
		$currency=$currency;
	if (preg_match('/$|£|¥|€|zł/i', $amount, $match)) {
		if ($match[0]=='$')
			$currency='USD';
		elseif ($match[0]=='€')
			$currency='EUR';
		elseif ($match[0]=='£')
			$currency='GBP';
		elseif ($match[0]=='¥')
			$currency='JPY';
		elseif ($match[0]=='¥')
			$currency='JPY';
		elseif ($match[0]=='zł')
			$currency='PLN';

	}
	elseif (preg_match('/[a-z]{3}/i', $amount, $match)) {
		//todo integrate do country db
		if (preg_match('/usd|eur|gbp|jpy|cad|aud|inr|pkr|mxn|nok/i', $match[0])) {
			$currency=strtoupper($match[0]);
		}
	}
	$locale_info = localeconv();
	$amount=preg_replace("/[^\d\.".$locale_info['decimal_point']."\-]/i", "", $amount);
	return array($currency, ParseFloat($amount));

}


function ParseFloat($floatString) {
	$LocaleInfo = localeconv();
	$floatString = str_replace($LocaleInfo["mon_thousands_sep"] , "", $floatString);
	$floatString = str_replace($LocaleInfo["mon_decimal_point"] , ".", $floatString);
	return floatval($floatString);
}


function currency_symbol($currency) {
	switch ($currency) {
	case('GBP'):
		return '£';
		break;
	case('EUR'):
	case('EU'):
		return '€';
		break;
	case('USD'):
		return '$';
		break;
	case('PLN'):
		return 'zł';
		break;
	case('DKK'):
	case('NOK'):
	case('SEK'):
		return 'kr ';
		break;
	case('CHF'):
		return 'CHF';
		break;
	case('INR'):
		return '₹';
		break;
	case('IDR'):
		return 'Rp';
		break;
	case('CNY'):
		return '¥';
		break;


	default:
		return '¤';
	}

}




function money_cents($amount) {
	$amount=sprintf("%02d", 100*($amount-floor($amount)));
	return $amount;
}


function weight($w, $unit='Kg', $number_decimals=3, $simplify=false, $zero_fill=false) {
	if ($w=='') return '';
	if ($simplify) {
		if ($w==0) {
			return '0'.$unit;
		}

		$w=round($w);

		if ($w==0) {
			return '~1'.$unit;
		}elseif ($w>1000) {
			$w=number($w, 0);
		}
		return $w.$unit;
	}else {
		if ($zero_fill) {
			return number($w, $number_decimals, true).$unit;

		}else {
			return number($w, $number_decimals).$unit;
		}
	}
}




function number($a, $fixed=1, $force_fix=false) {
	if (!$a)
		$a=0;

	$locale_info = localeconv();
	$floored=floor($a);
	if ($floored==$a and !$force_fix)
		$fixed=0;
	$a=number_format($a, $fixed, $locale_info['decimal_point'], $locale_info['thousands_sep']);

	return $a;
}


function endmonth($m, $y) {
	return idate('d', mktime(0, 0, 0, ($m + 1), 0, $y));

}





function display_dif($present, $past) {
	if ($present==_('NA'))
		$present=0;

	if ($past==_('NA'))
		return '<td class="same"> '._('ND').' </td>';
	elseif ($past==$present)
		return '<td class="same"><span class="arrow">&harr;</span> 0% </td>';
	elseif ($past==0)
		return '<td class="same"> '._('ND').'   </td>';
	else {


		$dif=100*($present-$past)/$past;

		if ($dif>0) {
			$class='up';
			$arrow='&uarr;';
		}
		elseif ($dif<0) {
			$class='down';
			$arrow='&darr;';
		}
		else {
			$class='same';
			$arrow='&harr;';
		}
		$dif_str='<td class="'.$class.'"><span class="arrow">'.$arrow.'</span>   '.number_format($dif, 1).'%</td>';


		return $dif_str;
	}
}





function interval($days, $units='auto') {

	switch ($units) {
	case('auto'):

		if (!is_numeric($days) or $days<=0)
			$interval='';
		else if ($days<14)
			$interval=number($days)._('d');
		elseif ($days<89)
			$interval=number($days/7)._('w');
		elseif ($days<534)
			$interval=number($days/30)._('m');
		elseif ($days<1826)
			$interval=number($days/365.25)._('y');
		else
			$interval=">5"._('y');

	}
	return $interval;
}


function get_time_interval($d1, $d2, $units='days') {

	$interval=$d2-$d1;

	switch ($units) {
	case('days'):
		$interval=$interval/3600/24;

	}

	return $interval;
}







function _trim($string) {
	$string=trim($string);
	return $string;
}


function mb_ucwords($str) {
	$str=_trim($str);
	if (preg_match('/^PO BOX\s+/i', $str))
		return strtoupper($str);



	$result='';


	$words=preg_split('/ /', $str);
	$first=true;
	foreach ($words as $word) {
		if (preg_match('/([a-z]\.){1,}$/i', $word)) {
			$result.=' '.strtoupper($word);
			continue;
		}
		elseif (!$first and preg_match('/^(UK|USA|HP|IBM|GB|MB|CD|DVD|USB)$/i', $word)) {
			$result.=' '.strtoupper($word);
			continue;
		}
		elseif (!$first and preg_match('/^(and|y|o|or|of|at|des|les|las|le)$/i', $word)) {
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

	if (strlen($str==1)) {
		return strtoupper($str);

	}


	if (preg_match('/^PO BOX\s+/i', $str))
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

	if ($str=='' or $str==',' or  $str=='+' or  $str=='-')
		return $str;
	//  print "$str\n";

	//print "=========================\n";

	$separator = array("-", "+", ",", " ");

	$str = mb_strtolower(trim($str), "UTF-8");
	foreach ($separator as $s) {
		$word = explode($s, $str);

		$return = "";
		foreach ($word as $val) {


			if (preg_match('/^www\.[^\s]+/i', $val)) {
				$return .= $s .mb_strtolower($val, "UTF-8");
			}
			elseif (preg_match('/^[a-z]{2,}\.$/i', $val)) {
				$return .= $s .mb_strtolower($val, "UTF-8");
			}
			elseif (preg_match('/^(st|Mr|Mrs|Miss|Dr|Ltd)$/i', $val)) {
				$return .= $s
					. mb_strtoupper($val {0}, "UTF-8")
					. mb_substr($val, 1, mb_strlen($val, "UTF-8")-1, "UTF-8");
			}

			elseif (preg_match('/^[^\s]+\.(com|uk|info|biz|org)$/i', $val)) {
				$return .= $s .mb_strtolower($val, "UTF-8");
			}
			elseif (preg_match('/^(aa|ee|ii|oo|uu)$/i', $val)) {
				$return .= $s .mb_strtoupper($val, "UTF-8");
			}
			elseif (preg_match('/^([a-z]\.){1,}$/i', $val)) {
				$return .= $s .mb_strtoupper($val, "UTF-8");
			}
			elseif (preg_match('/^c\/o$/i', $val)) {
				$return .= $s .'C/O';

			}
			elseif (preg_match('/^t\/a$/i', $val)) {
				$return .= $s .'T/A';

			}
			elseif (preg_match('/^([^(aeoiu)]{2,3})$/i', $val)) {

				$return .= $s .mb_strtoupper($val, "UTF-8");
			}
			elseif (preg_match('/^\(.+\)$/i', $val)) {
				$text=preg_replace('/^\(|\)$/i', '', $val);
				//print "*** $text\n";
				$return .= $s.'('.mb_ucwords($text).')';
			}

			elseif (mb_strlen($val, "UTF-8")>0) {
				$return.=$s.capitalize($val);
				// $return .= $s
				//   . mb_strtoupper($val{0},"UTF-8")
				//   . mb_substr($val,1,mb_strlen($val,"UTF-8")-1,"UTF-8");
				// print "return: $s ->  ".$val{0}." -> $mp_a $return \n";
			}

		}

		$str = mb_substr($return, 1);


	}




	$return=capitalize($return);


	// $return{1}=mb_strtoupper($return{1},"UTF-8");
	//$return=mb_substr($return, 1);

	foreach ($exceptions as $find=>$replace) {
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



function prepare_mysql($string, $null_if_empty=true) {

	if (is_numeric($string)) {
		return "'".$string."'";
	}
	elseif ($string=='' and $null_if_empty) {
		return 'NULL';
	}
	else {
		return "'".addslashes($string)."'";


	}
}





function is_url($url) {
	if (preg_match('/^(www\.)?[a-z0-9-]+(.[a-z0-9-]+)*\.(com|uk|fr|biz|net|info|mx|jp|org)$/i', $url))
		return true;
	else
		return false;
}







function array_change_key_name( $orig, $new, &$array ) {
	foreach ( $array as $k => $v )
		$return[ ( $k === $orig ) ? $new : $k ] = $v;
	return ( array ) $return;
}


function average($array) {
	$sum   = array_sum($array);
	$count = count($array);
	if ($count==0)
		return false;
	return $sum/$count;
}


//The average function can be use independantly but the deviation function uses the average function.

function deviation($array) {

	$avg = average($array);
	if (!$avg)
		return false;

	foreach ($array as $value) {
		$variance[] = pow($value-$avg, 2);
	}
	$deviation = sqrt(average($variance));
	return $deviation;
}



function currency_conversion($currency_from, $currency_to, $update_interval="-1 hour") {
	$reload=false;
	$in_db=false;
	$exchange_rate=1;
	//get info from database;
	$sql=sprintf("select * from kbase.`Currency Exchange Dimension` where `Currency Pair`=%s", prepare_mysql($currency_from.$currency_to));

	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		if (strtotime($row['Currency Exchange Last Updated'])<date("U", strtotime($update_interval)))
			$reload=true;
		$exchange_rate=$row['Exchange'];
	} else {
		$reload=true;
		$in_db=false;
	}
	if ($reload) {
		$url = "http://quote.yahoo.com/d/quotes.csv?s=". $currency_from . $currency_to . "=X". "&f=l1&e=.csv";

		$handle = fopen($url, "r");
		$contents = floatval(fread($handle, 2000));
		fclose($handle);



		if (is_numeric($contents) and $contents>0) {
			$exchange_rate=$contents;



			$sql=sprintf("insert into kbase.`Currency Exchange Dimension`  (`Currency Pair`,`Exchange`,`Currency Exchange Last Updated`,`Currency Exchange Source`) values (%s,%f,NOW(),'Yahoo')  ON DUPLICATE KEY update `Exchange`=%f,`Currency Exchange Last Updated`=NOW(),`Currency Exchange Source`='Yahoo'",
				prepare_mysql($currency_from.$currency_to), $exchange_rate, $exchange_rate);

			mysql_query($sql);


		}


	}

	return $exchange_rate;
}





function get_currency_other($from_Currency, $to_Currency) {

	$url    = 'http://download.finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $from_Currency . $to_Currency .'=X';



	$handle = @fopen($url, 'r');
	if ($handle) {
		$result = fgets($handle, 4096);
		fclose($handle);
	}


	$allData = explode(',', $result);



	$bace_rate =  $allData[1];


	$bace_rate_amount = $bace_rate;

	return  round($bace_rate_amount, 4);

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
function strlcs($str1, $str2) {
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
				while (($str1 {$i_temp} == $str2 {$j}) && ($i_temp < strlen($str1)) && ($j < strlen($str2))) {
					//append this matched character to the end of the substring
					$substring .= $str1 {$i_temp};
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

function array_empty($array) {
	if (is_array($array)) {
		foreach ($array as $value) {
			if (!empty($value))
				return false;
		}
		return true;
	} else
		return empty($array);
}






function translate_written_number($string) {

	$numbers=array('zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven');
	$common_suffixes=array('hundreds?'=>100, 'thousands?'=>1000, 'millons?'=>100000);

	$number_flat=join("|", $numbers);
	$common_suffixes_flat=join("|", $common_suffixes);
	if (preg_match("/$number_flat/i", $string)) {
		if (preg_match("/$common_suffixes_flat/i", $string)) {
			foreach ($numbers as $number=>$number_string) {
				foreach ($common_suffixes as $common_suffix=>$number_common_suffix) {
					$string=_trim(preg_replace('/^(.*\s+|)$number_string\s?$common_suffix(\s+.*|)$/ ', " ".($number*$number_common_suffix)." ", $string));
				}
			}
		} else {
			foreach ($numbers as $number=>$number_string)
				$string=_trim(preg_replace('/^(.*\s+|)$number_string(\s+.*|)$/ ', " $number ", $string));
		}
	}
	return $string;
}


function shuffle_assoc(&$array) {
	if (count($array)==0)
		return;
	$keys = array_keys($array);

	shuffle($keys);

	foreach ($keys as $key) {
		$new[$key] = $array[$key];
	}

	$array = $new;

	return true;
}












function getEnumVals($table, $field, $sorted=true) {

	$result=mysql_query('show columns from '.$table.';');

	while ($tuple=mysql_fetch_assoc($result)) {
		if ($tuple['Field'] == $field) {
			$types=$tuple['Type'];
			$beginStr=strpos($types, "(")+1;
			$endStr=strpos($types, ")");
			$types=substr($types, $beginStr, $endStr-$beginStr);
			$types=str_replace("'", "", $types);
			$types=preg_split('/\,/', $types);
			if ($sorted)
				sort($types);
			break;
		}
	}

	return $types;
}


function parse_number($value) {
	if (is_numeric($value))
		return $value;

	$value=preg_replace('/[^\.^\,\d]/', '', $value);
	if (preg_match('/\.\d?$/', $value)) {
		$value=preg_replace('/\,/', '', $value);

	}
	elseif (preg_match('/\..*\,\d?$/', $value)) {
		$value=preg_replace('/\./', '', $value);
		$value=preg_replace('/,/', ',', $value);
	}
	return (float) $value;


}


function parse_weight($value) {
	$unit='Kg';
	$value=_trim($value);
	if (preg_match('/(kg|kilo?|kilograms?)$/i', $value)) {
		$value=parse_number($value);
		$unit='Kg';
	}
	elseif (preg_match('/(lb?s|pounds?|libras?)$/i', $value)) {
		$value=parse_number($value)*.4545 ;
		$unit='Lb';
	}
	elseif (preg_match('/(g|grams?|gms)$/i', $value)) {
		$value=parse_number($value)*0.001 ;
		$unit='g';
	}
	elseif (preg_match('/(tons?|tonnes?|t)$/i', $value)) {
		$value=parse_number($value)*1000 ;
		$unit='t';
	}
	else
		$value=parse_number($value);

	return array($value, $unit);
}


function convert_weigth($value, $from, $to) {
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

	if (array_key_exists($from.$to, $factors)) {
		return $factors[$from.$to]*$value;
	} else
		return $value;


}


function parse_volume($value) {
	$unit='L';
	$value=_trim($value);
	if (preg_match('/(cc|cm3)$/i', $value)) {
		$value=parse_number($value)*.001 ;
		$unit='cm3';
	}
	elseif (preg_match('/(cubic meter|cubic m|m3)$/i', $value)) {
		$value=parse_number($value)*1000 ;
		$unit='m3';
	}
	elseif (preg_match('/(mL)$/i', $value)) {
		$value=parse_number($value)*001 ;
		$unit='mL';
	}
	else
		$value=parse_number($value);

	return array($value, $unit);
}


function parse_distance($value) {


	$original_unit='m';
	$value=_trim($value);
	if (preg_match('/(cent.metro?|centimeter?|cm)$/i', $value)) {
		$value=parse_number($value)/100 ;
		$original_unit='cm';
	}
	elseif (preg_match('/(mm|milimiters?)$/i', $value)) {
		$value=parse_number($value)/1000 ;
		$original_unit='mm';
	}
	elseif (preg_match('/(inches|inch|in)$/i', $value)) {
		$value=parse_number($value)*0.0254;
		$original_unit='inch';
	}
	elseif (preg_match('/(foot|feets?|ft)$/i', $value)) {
		$value=parse_number($value)*0.3048;
		$original_unit='ft';
	}
	elseif (preg_match('/(mile?|milles?)$/i', $value)) {
		$value=parse_number($value)* 1609.344;
		$original_unit='mile';
	}
	else
		$value=parse_number($value);

	return array($value, $original_unit);
}


function distance($value, $unit='m') {
	switch ($unit) {
	case('cm'):
		return number($value*100).'cm';
	case('mm'):
		return number($value*1000).'mm';
	default:
		return number($value).'m';
	}

}


function volume($value, $unit='L') {
	if ($value=='') return '';
	return number($value).'L';
}


function parse_parcels($value) {
	$unit='Box';
	$value=_trim($value);
	if (preg_match('/(pallet)$/i', $value)) {
		$value=parse_number($value)*.4545 ;
		$unit='Pallet';
	}
	elseif (preg_match('/(sobre|envelope)$/i', $value)) {
		$value=parse_number($value)*0.001 ;
		$unit='Envelope';
	}
	else
		$value=parse_number($value);

	return array($value, $unit);
}


function number2alpha($number) {
	$alpha=  chr(65+fmod($number-1, 26));
	$pos=floor(($number-1)/26);

	$prefix='';
	if ($pos>0) {
		$prefix=number2alpha($pos);
	}

	return $prefix.$alpha;
}







function array_to_CSV($data) {
	$outstream = fopen("php://temp", 'r+');
	fputcsv($outstream, $data, ',', '"');
	rewind($outstream);
	$csv = fgets($outstream);
	fclose($outstream);
	return $csv;
}


function CSV_to_array($data) {
	$instream = fopen("php://temp", 'r+');
	fwrite($instream, $data);
	rewind($instream);
	$csv = fgetcsv($instream, 9999999, ',', '"');
	fclose($instream);
	return $csv;
}


function aasort(&$array, $key) {
	$sorter=array();
	$ret=array();
	reset($array);
	foreach ($array as $ii => $va) {
		$sorter[$ii]=$va[$key];
	}
	arsort($sorter);
	foreach ($sorter as $ii => $va) {
		$ret[$ii]=$array[$ii];
	}
	$array=$ret;
}


function prepare_sentence_similar($a) {

	$a=preg_replace_callback(
		'/\b\w{1,3}\b/i',
		create_function(
			'$matches',
			'$ignore = array("red","tin","oro","925","eye");
         if (in_array($matches[0], $ignore)) {
            return $matches[0];
         } else {
            return \'\';
         }'
		),
		$a
	);

	$words_to_ignore=array('from');

	$a=_trim($a);

	$a=preg_split('/\s+/', $a);


	$a=array_diff($a, $words_to_ignore);


	foreach ($a as $key=>$value) {
		$_tmp=preg_replace('~[\W\s]~', '', $value);
		if ($_tmp=='') {
			unset($a[$key]);
		}
	}

	return $a;

}


function sentence_similarity($a, $b) {

	$a=prepare_sentence_similar($a);
	$b=prepare_sentence_similar($b);









	$similarity_array=array();
	$max_sim=0;

	foreach ($a as $item_a) {

		foreach ($b as $item_b) {
			similar_text($item_a, $item_b, $sim);
			$levenshtein=levenshtein($item_a, $item_b);

			if ($levenshtein>=0) {
				$max_strlen=max(strlen($item_a), strlen($item_b));
				$sim1= ($max_strlen-$levenshtein)/$max_strlen;
			}else {
				$sim1=0;
			}






			//print "$item_a, $item_b $sim\n";

			if ($sim>$max_sim)
				$max_sim=$sim;

			if (array_key_exists($item_a, $similarity_array)   ) {
				if ($similarity_array[$item_a]<$sim)
					$similarity_array[$item_a]=$sim;

			} else {
				$similarity_array[$item_a]=$sim;
			}

		}

	}


	$weight=0;
	$elements=count($similarity_array);
	if ($elements) {
		$weight=array_sum($similarity_array)/$elements;
	}

	$weight=($max_sim+$weight)/2;
	//print_r($similarity_array);
	//  print_r($a);
	// print_r($b);
	//exit($weight);
	return $weight;



}


function get_interval_db_name($interval) {

	switch ($interval) {


	case 'Total':
	case 'all':
		$db_interval='Total';

		break;

	case 'Last Month':
	case 'last_m':
		$db_interval='Last Month';

		break;

	case 'Last Week':
	case 'last_w':
		$db_interval='Last Week';



		break;

	case 'Yesterday':
	case 'yesterday':
		$db_interval='Yesterday';

		break;

	case 'Week To Day':
	case 'wtd':
	case 'weektoday':
		$db_interval='Week To Day';

		break;
	case 'Today':
	case 'today':
		$db_interval='Today';

		break;


	case 'Month To Day':
	case 'mtd':
	case 'monthtoday':

		$db_interval='Month To Day';

		break;
	case 'Year To Day':
	case 'ytd':
	case 'yeartoday';
		$db_interval='Year To Day';

		break;
	case '3 Year':
	case '3y':
	case 'three_year':
		$db_interval='3 Year';

		break;
	case '1 Year':
	case '1y':
	case 'year':
		$db_interval='1 Year';

		break;
	case '6 Month':
	case '6m':
	case 'six_month':
		$db_interval='6 Month';

		break;
	case '1 Quarter':
	case '1q':
	case 'quarter':
		$db_interval='1 Quarter';

		break;
	case '1 Month':
	case '1m':
	case 'month':
		$db_interval='1 Month';

		break;
	case '10 Day':
	case '10d':
	case 'ten_day':
		$db_interval='10 Day';

		break;
	case '1 Week':
	case '1w':
	case 'week':
		$db_interval='1 Week';

		break;
	case 'hour':

		$db_interval='1 Week';

		break;
	case '1 Day':
	case '1d':
	case 'day':
		$db_interval='1 Day';

		break;
	case '1 Hour':
	case '1h':
		$db_interval='1 Hour';

		break;

	default:
		return;
		break;
	}

	return $db_interval;
}





function calculate_interval_dates($interval) {

	$from_date=false;
	$to_date=false;

	$from_date_1yb=false;
	$to_1yb=false;

	switch ($interval) {


	case 'Total':
	case 'all':
	case 'All':
		$db_interval='Total';
		$from_date=false;
		$to_date=false;

		$from_date_1yb=false;
		$to_1yb=false;
		break;

	case 'Last Month':
	case 'last_m':
		$db_interval='Last Month';
		$from_date=date('Y-m-d 00:00:00', mktime(0, 0, 0, date('m')-1, 1, date('Y')));
		$to_date=date('Y-m-d 23:59:59', mktime(0, 0, -1, date('m'), 1, date('Y')));

		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("$to_date -1 year"));
		//print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";
		break;

	case 'Last Week':
	case 'last_w':
		$db_interval='Last Week';


		$sql=sprintf("select `First Day`  from kbase.`Week Dimension` where `Year`=%d and `Week`=%d", date('Y'), date('W'));
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$from_date=date('Y-m-d 00:00:00', strtotime($row['First Day'].' -1 week'));
			$to_date=date('Y-m-d 23:59:59', strtotime($row['First Day'].' -1 second'  ));

		} else {
			return;
		}



		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("$to_date -1 year"));
		break;

	case 'Yesterday':
	case 'yesterday':
		$db_interval='Yesterday';
		$from_date=date('Y-m-d 00:00:00', strtotime('today -1 day'));
		$to_date=date('Y-m-d 23:59:59', strtotime('today -1 day'));

		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("today -1 year"));
		break;

	case 'Week To Day':
	case 'wtd':
		$db_interval='Week To Day';

		$from_date=false;
		$from_date_1yb=false;

		$sql=sprintf("select `First Day`  from kbase.`Week Dimension` where `Year`=%d and `Week`=%d", date('Y'), date('W'));

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$from_date=$row['First Day'].' 00:00:00';
			$lapsed_seconds=strtotime('now')-strtotime($from_date);

		} else {
			return;
		}
        $to_date=gmdate('Y-m-d 23:59:59');


		$sql=sprintf("select `First Day`  from  kbase.`Week Dimension` where `Year`=%d and `Week`=%d", date('Y')-1, date('W'));
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$from_date_1yb=$row['First Day'].' 00:00:00';
		}


		$to_1yb=date('Y-m-d H:i:s', strtotime($from_date_1yb." +$lapsed_seconds seconds"));



		break;
	case 'Today':
	case 'today':
		$db_interval='Today';
		$from_date=date('Y-m-d 00:00:00');
		 $to_date=gmdate('Y-m-d 23:59:59');
		
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;


	case 'Month To Day':
	case 'mtd':
		$db_interval='Month To Day';
		$from_date=date('Y-m-01 00:00:00');
		 $to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case 'Year To Day':
	case 'ytd':
		$db_interval='Year To Day';
		$from_date=date('Y-01-01 00:00:00');
		 $to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		//print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";
		break;
	case '3 Year':
	case '3y':
		$db_interval='3 Year';
		$from_date=date('Y-m-d H:i:s', strtotime("now -3 year"));
		 $to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=false;
		$to_1yb=false;
		break;
	case '1 Year':
	case '1y':
	case 'year':
		$db_interval='1 Year';
		$from_date=date('Y-m-d H:i:s', strtotime("now -1 year"));
		 $to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '6 Month':
		$db_interval='6 Month';
		$from_date=date('Y-m-d H:i:s', strtotime("now -6 months"));
		 $to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '1 Quarter':
	case '1q':
		$db_interval='1 Quarter';
		$from_date=date('Y-m-d H:i:s', strtotime("now -3 months"));
		 $to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '1 Month':
	case '1m':
		$db_interval='1 Month';
		$from_date=date('Y-m-d H:i:s', strtotime("now -1 month"));
		 $to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '10 Day':
	case '10d':
		$db_interval='10 Day';
		$from_date=date('Y-m-d H:i:s', strtotime("now -10 days"));
		 $to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '1 Week':
	case '1w':
		$db_interval='1 Week';
		$from_date=date('Y-m-d H:i:s', strtotime("now -1 week"));
		 $to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '1 Day':
	case '1d':
		$db_interval='1 Day';
		$from_date=date('Y-m-d H:i:s', strtotime("now -1 day"));
		 $to_date=gmdate('Y-m-d 23:59:59');
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	case '1 Hour':
	case '1h':
		$db_interval='1 Hour';
		$from_date=date('Y-m-d H:i:s', strtotime("now -1 hour"));
		$from_date_1yb=date('Y-m-d H:i:s', strtotime("$from_date -1 year"));
		$to_1yb=date('Y-m-d H:i:s', strtotime("now -1 year"));
		break;
	default:
		return;
		break;
	}

	return array($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb);

}


function floattostr( $val ) {
	preg_match( "#^([\+\-]|)([0-9]*)(\.([0-9]*?)|)(0*)$#", trim($val), $o );
	return $o[1].sprintf('%d', $o[2]).($o[3]!='.'?$o[3]:'');
}



function get_ordinal_suffix( $n, $locale=false ) {

	if (!$locale) { global $locale;}

	$nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
	return $nf->format($n);

}


function money($amount, $currency='', $locale=false) {
	if (!$locale) {global $locale;}

	$money = new NumberFormatter($locale, NumberFormatter::CURRENCY);
	return $money->format($amount);
}


?>
