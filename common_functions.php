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


if (!function_exists('money_format')) {
    function money_format($format, $number) {
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
function getEnumValues($table, $field) {
    $enum_array = array();
    $query = 'SHOW COLUMNS FROM `' . $table . '` LIKE "' . $field . '"';
    // print $query;
    $result = mysql_query($query);
    $row = mysql_fetch_row($result);
    preg_match_all('/\'(.*?)\'/', $row[1], $enum_array);
    if (!empty($enum_array[1])) {
        // Shift array keys to match original enumerated index in MySQL (allows for use of index values instead of strings)
        foreach($enum_array[1] as $mkey => $mval) $enum_fields[$mkey+1] = $mval;
        return $enum_fields;
    } else return array(); // Return an empty array to avoid possible errors/warnings if array is passed to foreach() without first being checked with !empty().
}


function money($amount,$currency='') {


    return money_locale($amount,'',$currency);
}


function money_locale($amount,$locale='',$currency_code='') {



    if (!is_numeric($amount))
        $amount=0;
    global $_client_locale;
    $format='%i';
    if ($locale) {
        $locale.='.UTF-8';
        setlocale(LC_MONETARY, ($locale));
    }
    if ($currency_code) {

        $locale_info = localeconv();


        $client_currency=_trim($locale_info['int_curr_symbol']);
        //print("->".$client_currency."<-");
        $format='%i';

        $money=preg_replace("/$client_currency/",$currency_code,money_format($format,$amount));
    } else {
        $money=money_format($format,$amount);
    }

//


    //exit($money);
    if (preg_match('/[A-Z]{3}/',$money,$match)) {
        $money=preg_replace('/[A-Z]{3}/',currency_symbol($match[0]),$money);

    }


    setlocale(LC_MONETARY, ($_client_locale));

    // exit($money);

    return $money;
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

function unformat_money($number) {
    $locale_info = localeconv();


    $number=preg_replace('/\\'.$locale_info['thousand_sep'].'/','',$number);
    $number=preg_replace('/\\'.$locale_info['decimal_point'].'/','.',$number);
    return $number;
}

function getOrdinal($number) {
// get first digit
    $digit = abs($number) % 10;
    $ext = 'th';
$ext = ((abs($number) %100 < 21 && abs($number) %100 > 4) ? 'th' : (($digit < 4) ? ($digit < 3) ? ($digit < 2) ? ($digit < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
    return $number.$ext;
}

function prepare_mysql_dates($date1='',$date2='',$date_field='date',$options='') {

    $start='';
    $end='';
    if (preg_match('/start.*end/i',$options)) {
        $start=' start';
        $end=' end';

    }
    if (preg_match('/dates?_only|dates? only|only dates|date|only_dates?/i',$options)) {
        $d_option='date';



        $date_only=true;
    } else {
        $d_option='';
        $date_only=false;
    }
    $tmp=prepare_mysql_datetime($date1,$d_option.$start);
    $mysql_date1=$tmp['mysql_date'];
    $ok1=$tmp['ok'];
    if ($tmp['status']=='empty')
        $ok1=true;

    $tmp=prepare_mysql_datetime($date2,$d_option.$end);



    $mysql_date2=$tmp['mysql_date'];

    $ok2=$tmp['ok'];
    if ($tmp['status']=='empty')
        $ok2=true;

    if (!$ok1 or !$ok2)
        $error=1;
    else
        $error=0;


    if (preg_match('/complete months/i',$options)) {
        list($_date1['y'],$_date1['m'],$_date1['d'])=preg_split('/-/',$mysql_date1);
        list($_date2['y'],$_date2['m'],$_date2['d'])=preg_split('/-/',$mysql_date2);
        if ($_date1['d']>1)
            list($_date1['y'],$_date1['m'],$_date1['d'])=preg_split('/-/',date("Y-m-d", mktime(0, 0, 0, $_date1['m']+1, 1, $_date1['y'])));
        $last_day= getLastDayOfMonth($_date2['m'], $_date2['y']);
        if ($_date2['d']!= $last_day  )
            list($_date2['y'],$_date2['m'],$_date2['d'])=preg_split('/-/',date("Y-m-d", mktime(0, 0, 0, $_date2['m']-1,$last_day-1 , $_date2['y'])));

        $mysql_date1=$_date1['y'].'-'.$_date1['m'].'-'.$_date1['d'];
        $mysql_date2=$_date2['y'].'-'.$_date2['m'].'-'.$_date2['d'];

    }





    $date_field=addslashes($date_field);

    if ($mysql_date2=='' and $mysql_date1=='' )
        $mysql_interval="";
    else if ($mysql_date2!='' and $mysql_date1!='') {
        $mysql_interval=" and $date_field>='$mysql_date1' and $date_field<='$mysql_date2'";

    } else if ($mysql_date2!='')
        $mysql_interval=" and $date_field<='$mysql_date2'";
    else
        $mysql_interval=" and $date_field>='$mysql_date1' ";

    return array('0'=>$mysql_interval,'1'=>$date1,'2'=>$date2,'3'=>$error,'error'=>$error,'mysql'=>$mysql_interval,'from'=>$date1,'to'=>$date2);


}


function prepare_mysql_datetime($datetime,$tipo='datetime') {


    if ($datetime=='')
        return array('mysql_date'=>'','status'=>'empty','ok'=>false);
    $time='';

    if (preg_match('/datetime/',$tipo)) {
        if (preg_match('/^[12]\d{3}[\-\/][01]\d[\-\/][0123]\d\s[012]\d:[0123456]\d$/',$datetime))
            $datetime=$datetime.':00';
        if (!preg_match('/^[12]\d{3}[\-\/][01]\d[\-\/][0123]\d\s[012]\d:[0123456]\d:[0123456]\d$/',$datetime))
            return array('mysql_date'=>'','status'=>_('error, date time not reconozied')." $datetime",'ok'=>false);
        $ts=date('U',strtotime($datetime));
        list($date,$time)=preg_split('/\s+/',$datetime);
    } else {


        if (preg_match('/[0123]\d[\-\/][01]\d[\-\/][12]\d{3}/',$datetime)) {
            $tmp=preg_split('/\-|\//',$datetime);
            if (count($tmp)==3) {
                $datetime=$tmp[2].'-'.$tmp[1].'-'.$tmp[0];
            }
        }

        if (!preg_match('/^[12]\d{3}[\-\/][01]\d[\-\/][0123]\d/',$datetime))
            return array('mysql_date'=>'','status'=>'wrong date','ok'=>false);
        $date=$datetime;
        $ts=date('U',strtotime($date));
    }


//BfcGlE80Qt;D

    $date=str_replace('/','-',$date);
    $date=preg_split('/-/',$date);


    if (preg_match('/datetime/',$tipo)) {

        $mysql_datetime= trim(join ('-',$date).' '.$time);
    } else {


        $mysql_datetime= join ('-',$date);
        if (preg_match('/start/i',$tipo))
            $mysql_datetime.=' 00:00:00';
        if (preg_match('/midday/i',$tipo))
            $mysql_datetime.=' 12:00:00';
        elseif(preg_match('/end/i',$tipo))
        $mysql_datetime.=' 23:59:59';

    }
    return array('ts'=>$ts,'mysql_date'=>$mysql_datetime,'status'=>'ok','ok'=>true);

}

function prepare_mysql_date($string,$default='NOW()') {
    if ($string=='')
        return $default;
    else {
        $string=str_replace("'",'',$string);
        return "'".addslashes($string)."'";
    }
}




function getLastDayOfMonth($month, $year) {
    return idate('d', mktime(0, 0, 0, ($month + 1), 0, $year));
}


function date_base($from,$to,$step='m',$tipo='complete_both') {


    $tmp=prepare_mysql_datetime($from,'date');
    $mysql_date1=$tmp['mysql_date'];
    $ok1=$tmp['ok'];
    if ($tmp['status']=='empty')
        $ok1=true;
    $tmp=prepare_mysql_datetime($to,'date');
    $mysql_date2=$tmp['mysql_date'];
    $ok2=$tmp['ok'];
    if ($tmp['status']=='empty')
        $ok2=true;
    if ( !$ok1  or !$ok2)
        return array();
    list($date1['y'],$date1['m'],$date1['d'])=preg_split('/-/',$mysql_date1);
    list($date2['y'],$date2['m'],$date2['d'])=preg_split('/-/',$mysql_date2);
    $base=array();


    switch ($step) {
    case('m'):

        if (preg_match('/(^|\s|,)complete($|\s|,)|complete_both|complete_first|complete_from|only_complete|complete months/i',$tipo)) {

            if ($date1['d']>1) {
                list($date1['y'],$date1['m'],$date1['d'])=preg_split('/-/',date("Y-m-d", mktime(0, 0, 0, $date1['m']+1, 1, $date1['y'])));
            }
        }
        if (preg_match('/(^|\s|,)complete($|\s|,)|complete_both|complete_second|complete_to|only_complete|complete months/i',$tipo)) {
            $last_day= getLastDayOfMonth($date2['m'], $date2['y']);

            if ($date2['d']!= $last_day  ) {
                list($date2['y'],$date2['m'],$date2['d'])=preg_split('/-/',date("Y-m-d", mktime(0, 0, 0, $date2['m']-1,$last_day-1 , $date2['y'])));
            }
        }



        foreach(range($date1['y'],$date2['y']) as $y) {
            foreach(range(1,12) as $m) {
                if ($y==$date1['y'] and $m<$date1['m'])
                    continue;
                if ($y==$date2['y'] and $m>$date2['m'])
                    break;
                $base[sprintf('%d-%02d',$y,$m)]='';

            }
        }




    }

    return $base;
}





function getmicrotime() {
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}



function delta($current_value,$old_value) {

    if ($current_value==$old_value) {
        return '--';
    }
    return percentage($current_value-$old_value,$old_value,1,'NA','%',true);
}

function percentage($a,$b,$fixed=1,$error_txt='NA',$psign='%',$plus_sing=false) {

    $locale_info = localeconv();

    $per='';
    $error_txt=_($error_txt);
    if ($b>0) {
        if ($plus_sing and $a>0)
            $sing='+';
        else
            $sing='';
        $per=$sing.number_format((100*($a/$b)),$fixed,$locale_info['decimal_point'],$locale_info['thousands_sep']).$psign;
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
function parse_money($amount,$currency=false) {
    global $myconf;
    //	preg_match('/(\$|\£|\€|EUR|GBP|USD)[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?/',$term_description , $match){

    $locale_info = localeconv();

    if (!$currency)
        $currency=$corporate_currency;
    else
        $currency=$currency;
    if (preg_match('/$|£|¥|€|zł/i',$amount,$match)) {
        if ($match[0]=='$')
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

    }
    elseif(preg_match('/[a-z]{3}/i',$amount,$match)) {
        //todo integrate do country db
        if (preg_match('/usd|eur|gbp|jpy|cad|aud|inr|pkr|mxn|nok/i',$match[0])) {
            $currency=strtoupper($match[0]);
        }
    }
    $locale_info = localeconv();
    $amount=preg_replace("/[^\d\.".$locale_info['decimal_point']."\-]/i","",$amount);
    return array($currency,ParseFloat($amount));

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

    default:
        return '¤';
    }

}




function money_cents($amount) {
    $amount=sprintf("%02d",100*($amount-floor($amount)));
    return $amount;
}


function weight($w,$unit='Kg') {
    return number($w).'Kg';
}

function RelativeTime($timestamp) {
    $difference = time() - $timestamp;
    $periods = array("sec", "min", "hour", "day", "week", "month", "years", "decade");
    $lengths = array("60","60","24","7","4.35","12","10");

    if ($difference > 0) { // this was in the past
        $ending = "ago";
    } else { // this was in the future
        $difference = -$difference;
        $ending = "to go";
    }
    for ($j = 0; $difference >= $lengths[$j]; $j++) $difference /= $lengths[$j];
    $difference = round($difference);
    if ($difference != 1) $periods[$j].= "s";
    $text = "$difference $periods[$j] $ending";
    return $text;
}



function number($a,$fixed=1,$force_fix=false) {
    if (!$a)
        $a=0;

    $locale_info = localeconv();
    $floored=floor($a);
    if ($floored==$a and !$force_fix)
        $fixed=0;
    $a=number_format($a,$fixed,$locale_info['decimal_point'],$locale_info['thousands_sep']);

    return $a;
}

function endmonth($m,$y) {
    return idate('d', mktime(0, 0, 0, ($m + 1), 0, $y));

}

function detect_interval($date1,$date2) {

    return false;
}


function display_dif($present,$past) {
    if ($present==_('NA'))
        $present=0;

    if ($past==_('NA'))
        return '<td class="same"> '._('ND').' </td>';
    elseif($past==$present)
    return '<td class="same"><span class="arrow">&harr;</span> 0% </td>';
    elseif($past==0)
    return '<td class="same"> '._('ND').'   </td>';
    else {


        $dif=100*($present-$past)/$past;

        if ($dif>0) {
            $class='up';
            $arrow='&uarr;';
        }
        elseif($dif<0) {
            $class='down';
            $arrow='&darr;';
        }
        else {
            $class='same';
            $arrow='&harr;';
        }
        $dif_str='<td class="'.$class.'"><span class="arrow">'.$arrow.'</span>   '.number_format($dif,1).'%</td>';


        return $dif_str;
    }
}


function ft_request($from,$to,$output='phpdate',$mysql_date='date') {

    if ($to=='')
        $to=$from;
    if ($from!='') {
        $day_from=preg_split('/-/',$from);
        $day_to=preg_split('/-/',$to);


        if (count($day_from)==3 and count($day_to)==3) {
            switch ($output) {
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
                if ($date1==$date2)
                    $where_sql="( date='$date1'   )";
                else
                    $where_sql="( DATE($mysql_date)>='$date1' and DATE($mysql_date)<='$date2'  )";
                return array(true,$where_sql,'');

            }
        }
    }
    return array(false,'','');


}

function ft_dates($from,$to,$output='phpdate',$mysql_date='date') {


    switch ($output) {
    case('mysql_dates'):
        $date1=date('Y-m-d',$from);
        $date2=date('Y-m-d',$to);
        return array(true,$date1,$date2);
    case('mysqlwhen'):
        $date1=date('Y-m-d',$from);
        $date2=date('Y-m-d',$to);
        if ($date1==$date2)
            $where_sql="(date='$date1')";
        else
            $where_sql="(DATE($mysql_date)>='$date1' and DATE($mysql_date)<='$date2')";
        return array(true,$where_sql,'');

    }



}





function selfURL() {

    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
}

function strleft($s1, $s2) {
    return substr($s1, 0, strpos($s1, $s2));
}

function get_page() {
    $url=explode("/", $_SERVER['REQUEST_URI']);
    $url=array_reverse($url);
    return $url[0];
}
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

    if ($fdoy == 1) {
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
function translate($string) {
    $arg = array();
    for ($i = 1 ; $i < func_num_args(); $i++)
        $arg[] = func_get_arg($i);

    return vsprintf(gettext($string), $arg);
}

function interval($days,$units='auto') {

    switch ($units) {
    case('auto'):

        if (!is_numeric($days) or $days<=0)
            $interval='';
        else if ($days<14)
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


function get_time_interval($d1,$d2,$units='days') {

    $interval=$d2-$d1;

    switch ($units) {
    case('days'):
        $interval=$interval/3600/24;

    }

    return $interval;
}


function customers_awhere($awhere) {
    // $awhere=preg_replace('/\\\"/','"',$awhere);
    //    print "$awhere";


    $where_data=array(
                    'product_ordered1'=>'∀',
                    'geo_constraints'=>'',
                    'product_not_ordered1'=>'',
                    'product_not_received1'=>'',
                    'ordered_from'=>'',
                    'ordered_to'=>'',
                    'customer_created_from'=>'',
                    'customer_created_to'=>'',
                    'dont_have'=>array(),
                    'have'=>array(),
                    'allow'=>array(),
                    'dont_allow'=>array(),
                    'customers_which'=>array(),
                    'not_customers_which'=>array(),
                    'categories'=>'',
                    'lost_customer_from'=>'',
                    'lost_customer_to'=>'',
                    'invoice_option'=>array(),
                    'number_of_invoices_upper'=>'',
                    'number_of_invoices_lower'=>'',
                    'sales_lower'=>'',
                    'sales_upper'=>'',
                    'sales_option'=>array(),
                    'store_key'=>0,
                    'order_option'=>array(),
                    'order_time_units_since_last_order_qty'=>false,
                    'order_time_units_since_last_order_units'=>false
                );

    //  $awhere=json_decode($awhere,TRUE);


    foreach ($awhere as $key=>$item) {
        $where_data[$key]=$item;
    }




    $where=sprintf('where  `Customer Store Key`=%d ',$where_data['store_key']);
    $table='`Customer Dimension` C ';

//print_r($where_data);
    $use_product=false;
    $use_categories =false;
    $use_otf =false;

    $where_categories='';
    if ($where_data['categories']!='') {

        $categories_keys=preg_split('/,/',$where_data['categories']);
        $valid_categories_keys=array();
        foreach ($categories_keys as $item) {
            if (is_numeric($item))
                $valid_categories_keys[]=$item;
        }
        $categories_keys=join($valid_categories_keys,',');
        if ($categories_keys) {
            $use_categories =true;
            $where_categories=sprintf(" and `Subject`='Customer' and `Category Key` in (%s)",$categories_keys);
        }


    }

    $where_geo_constraints='';
    if ($where_data['geo_constraints']!='') {
        $where_geo_constraints=extract_customer_geo_groups($where_data['geo_constraints']);
    }


    if ($where_data['product_ordered1']=='')
        $where_data['product_ordered1']='∀';


    if ($where_data['product_ordered1']!='') {
        if ($where_data['product_ordered1']!='∀') {
            $use_otf=true;
            list($where_product_ordered1,$use_product)=extract_product_groups($where_data['product_ordered1'],$where_data['store_key']);
        } else
            $where_product_ordered1='true';
    } else {
        $where_product_ordered1='false';
    }

    /*
        if ($where_data['product_not_ordered1']!='') {
            if ($where_data['product_not_ordered1']!='ALL') {
                $use_otf=true;
                $where_product_not_ordered1=extract_product_groups($where_data['product_ordered1'],'O.`Product Code` not like','transaction.product_id not like','OTF.`Product Family Key` not in ','O.`Product Family Key` like');
            } else
                $where_product_not_ordered1='false';
        } else
            $where_product_not_ordered1='true';

        if ($where_data['product_not_received1']!='') {
            if ($where_data['product_not_received1']!='∀') {
                $use_otf=true;
                $where_product_not_received1=extract_product_groups($where_data['product_ordered1'],'(ordered-dispatched)>0 and    product.code  like','(ordered-dispatched)>0 and  transaction.product_id not like','(ordered-dispatched)>0 and  product_group.name not like','(ordered-dispatched)>0 and  product_group.id like');
            } else {
                $use_otf=true;
                $where_product_not_received1=' ((ordered-dispatched)>0)  ';
            }
        } else
            $where_product_not_received1='true';

    */

    $date_interval_when_ordered=prepare_mysql_dates($where_data['ordered_from'],$where_data['ordered_to'],'`Order Date`','only_dates');
    if ($date_interval_when_ordered['mysql']) {
        $use_otf=true;
    }
    $date_interval_when_customer_created=prepare_mysql_dates($where_data['customer_created_from'],$where_data['customer_created_to'],'`Customer First Contacted Date`','only_dates');
    if ($date_interval_when_customer_created['mysql']) {

    }

    $date_interval_lost_customer=prepare_mysql_dates($where_data['lost_customer_from'],$where_data['lost_customer_to'],'`Customer Lost Date`','only_dates');

    if ($where_data['sales_lower']!='')
        $use_otf=true;

    if ($use_otf) {
        $table='`Customer Dimension` C  left join  `Order Transaction Fact` OTF  on (C.`Customer Key`=OTF.`Customer Key`)   ';
    }
    if ($use_product) {
        $table='`Customer Dimension` C  left join  `Order Transaction Fact` OTF  on (C.`Customer Key`=OTF.`Customer Key`) left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)  ';
    }



    if ($use_categories) {

        $table.='  left join   `Category Bridge` CatB on (C.`Customer Key`=CatB.`Subject Key`)   ';
    }





    $where.=' and (  '.$where_product_ordered1.$date_interval_when_ordered['mysql'].$date_interval_when_customer_created['mysql'].$date_interval_lost_customer['mysql'].") $where_categories $where_geo_constraints";

    foreach($where_data['dont_have'] as $dont_have) {
        switch ($dont_have) {
        case 'tel':
            $where.=sprintf(" and `Customer Main Telephone Key` IS NULL ");
            break;
        case 'email':
            $where.=sprintf(" and `Customer Main Email Key` IS NULL ");
            break;
        case 'fax':
            $where.=sprintf(" and `Customer Main Fax Key` IS NULL ");
            break;
        case 'address':
            $where.=sprintf(" and `Customer Main Address Incomplete`='Yes' ");
            break;
        }
    }
    foreach($where_data['have'] as $dont_have) {
        switch ($dont_have) {
        case 'tel':
            $where.=sprintf(" and `Customer Main Telephone Key` IS NOT NULL ");
            break;
        case 'email':
            $where.=sprintf(" and `Customer Main Email Key` IS NOT NULL ");
            break;
        case 'fax':
            $where.=sprintf(" and `Customer Main Fax Key` IS NOT NULL ");
            break;
        case 'address':
            $where.=sprintf(" and `Customer Main Address Incomplete`='No' ");
            break;
        }
    }

    $allow_where='';
    foreach($where_data['allow'] as $allow) {
        switch ($allow) {
        case 'newsletter':
            $allow_where.=sprintf(" or `Customer Send Newsletter`='Yes' ");
            break;
        case 'marketing_email':
            $allow_where.=sprintf(" or `Customer Send Email Marketing`='Yes'  ");
            break;
        case 'marketing_post':
            $allow_where.=sprintf(" or  `Customer Send Postal Marketing`='Yes'  ");
            break;

        }



    }
    $allow_where=preg_replace('/^\s*or/','',$allow_where);
    if ($allow_where!='') {
        $where.="and ($allow_where)";
    }

    $dont_allow_where='';
    foreach($where_data['dont_allow'] as $dont_allow) {
        switch ($dont_allow) {
        case 'newsletter':
            $dont_allow_where.=sprintf(" or `Customer Send Newsletter`='No' ");
            break;
        case 'marketing_email':
            $dont_allow_where.=sprintf(" or `Customer Send Email Marketing`='No'  ");
            break;
        case 'marketing_post':
            $dont_allow_where.=sprintf(" or  `Customer Send Postal Marketing`='No'  ");
            break;
        }



    }
    $dont_allow_where=preg_replace('/^\s*or/','',$dont_allow_where);
    if ($dont_allow_where!='') {
        $where.="and ($dont_allow_where)";
    }


    $customers_which_where='';
    foreach($where_data['customers_which'] as $customers_which) {
        switch ($customers_which) {
        case 'active':
            $customers_which_where.=sprintf(" or `Customer Active`='Yes' ");
            break;
        case 'losing':
            $customers_which_where.=sprintf(" or `Customer Type by Activity`='Losing'  ");
            break;
        case 'lost':
            $customers_which_where.=sprintf(" or `Customer Active`='No'  ");
            break;
        }
    }
    $customers_which_where=preg_replace('/^\s*or/','',$customers_which_where);
    if ($customers_which_where!='') {
        $where.="and ($customers_which_where)";
    }

//print_r($where_data);
    if ($where_data['order_time_units_since_last_order_qty']>0) {

        switch ($where_data['order_time_units_since_last_order_units']) {
        case 'days':
            $where.=sprintf(' and Date(`Customer Last Order Date`)=DATE(DATE_SUB(NOW(), INTERVAL %d day)) ',$where_data['order_time_units_since_last_order_qty']);
            break;
        default:

            break;
        }

    }




    $invoice_option_where='';
    foreach($where_data['invoice_option'] as $invoice_option) {
        switch ($invoice_option) {
        case 'less':
            $invoice_option_where.=sprintf(" and `Customer Orders`<'%d' ",$where_data['number_of_invoices_lower']);
            break;
        case 'equal':
            $invoice_option_where.=sprintf(" and `Customer Orders`='%d'  ",$where_data['number_of_invoices_lower']);
            break;
        case 'more':
            $invoice_option_where.=sprintf(" and  `Customer Orders`>'%d'  ",$where_data['number_of_invoices_lower']);
            break;

        case 'between':
            $invoice_option_where.=sprintf(" and  `Customer Orders`>'%d'  and `Customer Orders`<'%d'", $where_data['number_of_invoices_lower'], $where_data['number_of_invoices_upper']);
            break;
        }
    }
    $invoice_option_where=preg_replace('/^\s*and/','',$invoice_option_where);

    if ($invoice_option_where!='') {
        $where.="and ($invoice_option_where)";
    }

    $order_option_where='';
    foreach($where_data['order_option'] as $order_option) {
        switch ($order_option) {
        case 'less':
            $order_option_where.=sprintf(" and `Customer Orders`<'%d' ",$where_data['number_of_orders_lower']);
            break;
        case 'equal':
            $order_option_where.=sprintf(" and `Customer Orders`='%d'  ",$where_data['number_of_orders_lower']);
            break;
        case 'more':
            $order_option_where.=sprintf(" and  `Customer Orders`>'%d'  ",$where_data['number_of_orders_lower']);
            break;

        case 'between':
            $order_option_where.=sprintf(" and  `Customer Orders`>'%d'  and `Customer Orders`<'%d'", $where_data['number_of_orders_lower'], $where_data['number_of_orders_upper']);
            break;
        }
    }





    $order_option_where=preg_replace('/^\s*and/','',$order_option_where);






    if ($order_option_where!='') {
        $where.="and ($order_option_where)";
    }


    $sales_option_where='';
    foreach($where_data['sales_option'] as $sales_option) {
        switch ($sales_option) {
        case 'sales_less':
            $sales_option_where.=sprintf(" and `Invoice Transaction Gross Amount`<'%s' ",$where_data['sales_lower']);
            break;
        case 'sales_equal':
            $sales_option_where.=sprintf(" and `Invoice Transaction Gross Amount`='%s'  ",$where_data['sales_lower']);
            break;
        case 'sales_more':
            $sales_option_where.=sprintf(" and  `Invoice Transaction Gross Amount`>'%s'  ",$where_data['sales_lower']);
            break;

        case 'sales_between':
            $sales_option_where.=sprintf(" and  `Invoice Transaction Gross Amount`>'%s'  and `Invoice Transaction Gross Amount`<'%s'", $where_data['sales_lower'], $where_data['sales_upper']);
            break;
        }
    }
    $sales_option_where=preg_replace('/^\s*and/','',$sales_option_where);





    if ($sales_option_where!='') {
        $where.="and ($sales_option_where)";
    }


    /*
    $not_customers_which_where='';
    foreach($where_data['not_customers_which'] as $not_customers_which) {
        switch ($not_customers_which) {
        case 'active':
            $not_customers_which_where.=sprintf(" or `Customer Active`='No' ");
            break;
        case 'losing':
            $not_customers_which_where.=sprintf(" or `Customer Type by Activity`='Active'  ");
            break;
        case 'lost':
            $not_customers_which_where.=sprintf(" or  `Customer Active`='Yes'  ");
            break;
        }
    }

    $not_customers_which_where=preg_replace('/^\s*or/','',$not_customers_which_where);
    if($not_customers_which_where!=''){
    $where.="and ($not_customers_which_where)";
    }
    *///print $table;print $where;
//print $where; exit;
    return array($where,$table);


}

function invoices_awhere($awhere) {
    // $awhere=preg_replace('/\\\"/','"',$awhere);



    $where_data=array(
                    //'product_ordered1'=>'∀',
                    'invoice_date_from'=>'',
                    'invoice_date_to'=>'',
                    'invoice_paid_date_from'=>'',
                    'invoice_paid_date_to'=>'',
                    'billing_geo_constraints'=>'',
                    'delivery_geo_constraints'=>'',
                    'total_net_amount_lower'=>'',
                    'total_net_amount_upper'=>'',
                    'total_tax_amount_lower'=>'',
                    'total_tax_amount_upper'=>'',
                    'total_profit_lower'=>'',
                    'total_profit_upper'=>'',
                    'total_amount_lower'=>'',
                    'total_amount_upper'=>'',
                    'tax_code'=>'',
                    'paid_status'=>array(),
                    'not_paid_status'=>array(),
                    'total_net_amount'=>array(),
                    'total_tax_amount'=>array(),
                    'total_profit'=>array(),
                    'total_amount'=>array(),
                    'category'=>array(),
                    'store_key'=>false
                );

    //  $awhere=json_decode($awhere,TRUE);


    foreach ($awhere as $key=>$item) {
        $where_data[$key]=$item;
    }

    $where='where true';
    $table='`Invoice Dimension` I ';

    $use_product=false;
    //$use_categories =false;
    $use_otf =false;



    $where_billing_geo_constraints='';
    if ($where_data['billing_geo_constraints']!='') {
        $where_billing_geo_constraints=sprintf(" and `Order Main Country 2 Alpha Code`='%s'",$where_data['billing_geo_constraints']);
    }

    $where_delivery_geo_constraints='';
    if ($where_data['delivery_geo_constraints']!='') {
        $where_delivery_geo_constraints=sprintf(" and `Order Ship To Country Code`='%s'",$where_data['delivery_geo_constraints']);
    }

    $where_tax_code='';
    if ($where_data['tax_code']!='') {
        $where_delivery_geo_constraints=sprintf(" and `Invoice Tax Code`='%s'",$where_data['tax_code']);
    }


    $date_interval_invoice_created=prepare_mysql_dates($where_data['invoice_date_from'],$where_data['invoice_date_to'],'`Invoice Date`','only_dates');
    $date_interval_invoice_paid=prepare_mysql_dates($where_data['invoice_paid_date_from'],$where_data['invoice_paid_date_to'],'`Invoice Paid Date`','only_dates');


    $where='where ( true '.$date_interval_invoice_created['mysql'].$date_interval_invoice_paid['mysql'].") $where_billing_geo_constraints $where_delivery_geo_constraints $where_tax_code";
//print $where;exit;


    $paid_status_where='';
    foreach($where_data['paid_status'] as $paid_status) {
        switch ($paid_status) {
        case 'partially':
            $paid_status_where.=sprintf(" or `Invoice Paid`='Partially' ");
            break;
        case 'yes':
            $paid_status_where.=sprintf(" or `Invoice Paid`='Yes'  ");
            break;
        case 'no':
            $paid_status_where.=sprintf(" or  `Invoice Paid`='No'  ");
            break;

        }



    }
    $paid_status_where=preg_replace('/^\s*or/','',$paid_status_where);
    if ($paid_status_where!='') {
        $where.="and ($paid_status_where)";
    }

    $not_paid_status_where='';
    foreach($where_data['not_paid_status'] as $not_paid_status) {
        switch ($not_paid_status) {
        case 'partially':
            $not_paid_status_where.=sprintf(" or `Invoice Paid`!='Partially' ");
            break;
        case 'yes':
            $not_paid_status_where.=sprintf(" or `Invoice Paid`!='Yes'  ");
            break;
        case 'no':
            $not_paid_status_where.=sprintf(" or  `Invoice Paid`!='No'  ");
            break;
        }



    }
    $not_paid_status_where=preg_replace('/^\s*or/','',$not_paid_status_where);
    if ($not_paid_status_where!='') {
        $where.="and ($not_paid_status_where)";
    }


    $total_net_amount_where='';
    foreach($where_data['total_net_amount'] as $total_net_amount) {
        switch ($total_net_amount) {
        case 'less':
            $total_net_amount_where.=sprintf(" and `Invoice Total Net Amount`<'%s' ",$where_data['total_net_amount_lower']);
            break;
        case 'equal':
            $total_net_amount_where.=sprintf(" and `Invoice Total Net Amount`='%s'  ",$where_data['total_net_amount_lower']);
            break;
        case 'more':
            $total_net_amount_where.=sprintf(" and `Invoice Total Net Amount`>'%s'  ",$where_data['total_net_amount_upper']);
            break;
        case 'between':
            $total_net_amount_where.=sprintf(" and  `Invoice Total Net Amount`>'%s'  and `Invoice Total Net Amount`<'%s'", $where_data['total_net_amount_lower'], $where_data['total_net_amount_upper']);
            break;
        }
    }
    $total_net_amount_where=preg_replace('/^\s*and/','',$total_net_amount_where);

    if ($total_net_amount_where!='') {
        $where.="and ($total_net_amount_where)";
    }


    $total_tax_amount_where='';
    foreach($where_data['total_tax_amount'] as $total_tax_amount) {
        switch ($total_tax_amount) {
        case 'less':
            $total_tax_amount_where.=sprintf(" and `Invoice Total Tax Amount`<'%s' ",$where_data['total_tax_amount_lower']);
            break;
        case 'equal':
            $total_tax_amount_where.=sprintf(" and `Invoice Total Tax Amount`='%s'  ",$where_data['total_tax_amount_lower']);
            break;
        case 'more':
            $total_tax_amount_where.=sprintf(" and `Invoice Total Tax Amount`>'%s'  ",$where_data['total_tax_amount_upper']);
            break;
        case 'between':
            $total_tax_amount_where.=sprintf(" and  `Invoice Total Tax Amount`>'%s'  and `Invoice Total Tax Amount`<'%s'", $where_data['total_tax_amount_lower'], $where_data['total_tax_amount_upper']);
            break;
        }
    }
    $total_tax_amount_where=preg_replace('/^\s*and/','',$total_tax_amount_where);

    if ($total_tax_amount_where!='') {
        $where.="and ($total_tax_amount_where)";
    }

    $total_profit_where='';
    foreach($where_data['total_profit'] as $total_profit) {
        switch ($total_profit) {
        case 'less':
            $total_profit_where.=sprintf(" and `Invoice Total Profit`<'%s' ",$where_data['total_profit_lower']);
            break;
        case 'equal':
            $total_profit_where.=sprintf(" and `Invoice Total Profit`='%s'  ",$where_data['total_profit_lower']);
            break;
        case 'more':
            $total_profit_where.=sprintf(" and `Invoice Total Profit`>'%s'  ",$where_data['total_profit_upper']);
            break;
        case 'between':
            $total_profit_where.=sprintf(" and  `Invoice Total Profit`>'%s'  and `Invoice Total Profit`<'%s'", $where_data['total_profit_lower'], $where_data['total_profit_upper']);
            break;
        }
    }
    $total_profit_where=preg_replace('/^\s*and/','',$total_profit_where);

    if ($total_profit_where!='') {
        $where.="and ($total_profit_where)";
    }

    $total_amount_where='';
    foreach($where_data['total_amount'] as $total_amount) {
        switch ($total_amount) {
        case 'less':
            $total_amount_where.=sprintf(" and `Invoice Total Amount`<'%s' ",$where_data['total_amount_lower']);
            break;
        case 'equal':
            $total_amount_where.=sprintf(" and `Invoice Total Amount`='%s'  ",$where_data['total_amount_lower']);
            break;
        case 'more':
            $total_amount_where.=sprintf(" and `Invoice Total Amount`>'%s'  ",$where_data['total_amount_upper']);
            break;
        case 'between':
            $total_amount_where.=sprintf(" and  `Invoice Total Amount`>'%s'  and `Invoice Total Amount`<'%s'", $where_data['total_amount_lower'], $where_data['total_amount_upper']);
            break;
        }
    }
    $total_amount_where=preg_replace('/^\s*and/','',$total_amount_where);

    if ($total_amount_where!='') {
        $where.="and ($total_amount_where)";
    }


    /*
    	   $total_net_amount_where='';
    foreach($where_data['total_net_amount'] as $total_net_amount) {
        switch ($total_net_amount) {
        case 'less':
            $total_net_amount_where.=sprintf(" and `Invoice Total Net Amount`<'%s' ",$where_data['total_net_amount_lower']);
            break;
        case 'equal':
            $total_net_amount_where.=sprintf(" and `Invoice Total Net Amount`='%s'  ",$where_data['total_net_amount_lower']);
            break;
        case 'more':
            $total_net_amount_where.=sprintf(" and `Invoice Total Net Amount`>'%s'  ",$where_data['total_net_amount_upper']);
            break;
    	case 'between':
    		$total_net_amount_where.=sprintf(" and  `Invoice Total Net Amount`>'%s'  and `Invoice Total Net Amount`<'%s'", $where_data['total_net_amount_lower'], $where_data['total_net_amount_upper']);
    		break;
    	}
    }
    $total_net_amount_where=preg_replace('/^\s*and/','',$total_net_amount_where);

    if($total_net_amount_where!=''){
    	$where.="and ($total_net_amount_where)";
    }
    */

    $category_where='';
    foreach($where_data['category'] as $category) {
        $sql=sprintf("select `Subject Key` from `Category Bridge` where `Category Key`=%d", $category);
        $result=mysql_query($sql);
        $subject_keys=array();
        while ($row=mysql_fetch_array($result)) {
            $subject_keys[]=$row['Subject Key'];
        }
        $subject_keys=join(",", $subject_keys);
        //print_r($subject_keys);exit;
        $category_where.=sprintf(" and `Invoice Key` in ($subject_keys)");
    }
    $category_where=preg_replace('/^\s*and/','',$category_where);

    if ($category_where!='') {
        $where.="and ($category_where)";
    }



    //print $table. $where; exit;
    return array($where,$table);


}

//Parts awhere
function parts_awhere($awhere) {

    $where_data=array(
                    //'product_ordered1'=>'∀',
                    //'price'=>array(),
                    //'invoice'=>array(),
                    //'web_state'=>array(),
                    //'availability_state'=>array(),
                    'geo_constraints'=>'',
                    'part_valid_from'=>'',
                    'part_valid_to'=>'',
                    //'product_valid_to'=>'',
                    //'price_lower'=>'',
                    //'price_upper'=>'',
                    //'invoice_lower'=>'',
                    // 'invoice_upper'=>''
                );


    //  $awhere=json_decode($awhere,TRUE);


    foreach ($awhere as $key=>$item) {
        $where_data[$key]=$item;
    }


    $where='where true';
    //$table='`Part Dimension` P ';
    $table='`Part Dimension` P  left join  `Inventory Transaction Fact` ITF  on (P.`Part SKU`=ITF.`Part SKU`) left join  `Order Transaction Fact` OTF  on (ITF.`Map To Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) left join kbase.`Country Dimension` CD on (CD.`Country 2 Alpha Code`=OTF.`Destination Country 2 Alpha Code`) ';

    $use_product=false;
    //$use_categories =false;
    $use_otf =false;


    /*
        $price_where='';
        foreach($where_data['price'] as $price) {
            switch ($price) {
            case 'less':
                $price_where.=sprintf(" and `Product Price`<%s ",prepare_mysql($where_data['price_lower']));
                break;
            case 'equal':
                $price_where.=sprintf(" and `Product Price`=%s  ",prepare_mysql($where_data['price_lower']));
                break;
            case 'more':
                $price_where.=sprintf(" and `Product Price`>%s  ",prepare_mysql($where_data['price_upper']));
                break;
            case 'between':
                $price_where.=sprintf(" and  `Product Price`>%s  and `Product Price`<%s", prepare_mysql($where_data['price_lower']), prepare_mysql($where_data['price_upper']));
                break;
            }
        }
        $price_where=preg_replace('/^\s*and/','',$price_where);

        if ($price_where!='') {
            $where.=" and ($price_where)";
        }

    */

    $date_interval_from=prepare_mysql_dates($where_data['part_valid_from'],$where_data['part_valid_to'],'ITF.`Date`','only_dates');
    //$date_interval_to=prepare_mysql_dates('',$where_data['product_valid_to'],'`Product Valid To`','only_dates');



    $where.=$date_interval_from['mysql'];
    //print $where;exit;


    $where_geo_constraints='';
    if ($where_data['geo_constraints']!='') {
        $where_geo_constraints=extract_products_geo_groups($where_data['geo_constraints'],'CD.`Country Code`','CD.`World Region Code`');
    }
    $where.=$where_geo_constraints;
    //print $where_geo_constraints;exit;
    /*

    $where_billing_geo_constraints='';
    if ($where_data['billing_geo_constraints']!='') {
        $where_billing_geo_constraints=sprintf(" and `Order Main Country 2 Alpha Code`='%s'",$where_data['billing_geo_constraints']);
    }


    */

    //print $table. $where; exit;

    return array($where,$table);
}
////



function product_awhere($awhere) {


    $where_data=array(
                    //'product_ordered1'=>'∀',
                    'price'=>array(),
                    'invoice'=>array(),
                    'web_state'=>array(),
                    'availability_state'=>array(),
                    'geo_constraints'=>'',
                    'created_date_to'=>'',
                    'product_valid_from'=>'',
                    'product_valid_to'=>'',
                    'price_lower'=>'',
                    'price_upper'=>'',
                    'invoice_lower'=>'',
                    'invoice_upper'=>''
                );


    //  $awhere=json_decode($awhere,TRUE);


    foreach ($awhere as $key=>$item) {
        $where_data[$key]=$item;
    }


    $where='where true';
    $table='`Product Dimension` P ';

    $use_product=false;
    //$use_categories =false;
    $use_otf =false;



    $price_where='';
    foreach($where_data['price'] as $price) {
        switch ($price) {
        case 'less':
            $price_where.=sprintf(" and `Product Price`<%s ",prepare_mysql($where_data['price_lower']));
            break;
        case 'equal':
            $price_where.=sprintf(" and `Product Price`=%s  ",prepare_mysql($where_data['price_lower']));
            break;
        case 'more':
            $price_where.=sprintf(" and `Product Price`>%s  ",prepare_mysql($where_data['price_upper']));
            break;
        case 'between':
            $price_where.=sprintf(" and  `Product Price`>%s  and `Product Price`<%s", prepare_mysql($where_data['price_lower']), prepare_mysql($where_data['price_upper']));
            break;
        }
    }
    $price_where=preg_replace('/^\s*and/','',$price_where);

    if ($price_where!='') {
        $where.=" and ($price_where)";
    }

    $invoice_where='';
    foreach($where_data['invoice'] as $invoice) {
        switch ($invoice) {
        case 'less':
            $invoice_where.=sprintf(" and `Product Total Invoiced Amount`<%s ",prepare_mysql($where_data['invoice_lower']));
            break;
        case 'equal':
            $invoice_where.=sprintf(" and `Product Total Invoiced Amount`=%s  ",prepare_mysql($where_data['invoice_lower']));
            break;
        case 'more':
            $invoice_where.=sprintf(" and `Product Total Invoiced Amount`>%s  ",prepare_mysql($where_data['invoice_upper']));
            break;
        case 'between':
            $invoice_where.=sprintf(" and `Product Total Invoiced Amount`>%s  and `Product Total Invoiced Amount`<%s", prepare_mysql($where_data['invoice_lower']), prepare_mysql($where_data['invoice_upper']));
            break;
        }
    }
    $invoice_where=preg_replace('/^\s*and/','',$invoice_where);

    if ($invoice_where!='') {
        $where.=" and ($invoice_where)";
    }



    $web_state_where='';
    foreach($where_data['web_state'] as $web_state) {
        switch ($web_state) {
        case 'online_force_out_of_stock':
            $web_state_where.=sprintf(" or `Product Web Configuration`='Online Force Out of Stock' ");
            break;
        case 'online_auto':
            $web_state_where.=sprintf(" or `Product Web Configuration`='Online Auto'  ");
            break;
        case 'offline':
            $web_state_where.=sprintf(" or  `Product Web Configuration`='Offline'  ");
            break;
        case 'unknown':
            $web_state_where.=sprintf(" or  `Product Web Configuration`='Unknown'  ");
            break;
        case 'online_force_for_sale':
            $web_state_where.=sprintf(" or  `Product Web Configuration`='Online Force For Sale'  ");
            break;
        }
    }
    $web_state_where=preg_replace('/^\s*or/','',$web_state_where);
    if ($web_state_where!='') {
        $where.=" and ($web_state_where)";
    }

    $availability_state_where='';
    foreach($where_data['availability_state'] as $availability_state) {
        switch ($availability_state) {
        case 'optimal':
            $availability_state_where.=sprintf(" or `Product Availability State`='Optimal' ");
            break;
        case 'low':
            $availability_state_where.=sprintf(" or `Product Availability State`='Low'  ");
            break;
        case 'critical':
            $availability_state_where.=sprintf(" or  `Product Availability State`='Critical'  ");
            break;
        case 'surplus':
            $availability_state_where.=sprintf(" or  `Product Availability State`='Surplus'  ");
            break;
        case 'out_of_stock':
            $availability_state_where.=sprintf(" or  `Product Availability State`='Out of Stock'  ");
            break;

        case 'unknown':
            $availability_state_where.=sprintf(" or  `Product Availability State`='Unknown'  ");
            break;

        case 'no_applicable':
            $availability_state_where.=sprintf(" or  `Product Availability State`='No applicable'  ");
            break;
        }
    }
    $availability_state_where=preg_replace('/^\s*or/','',$availability_state_where);
    if ($availability_state_where!='') {
        $where.=" and ($availability_state_where)";
    }



    $date_interval_from=prepare_mysql_dates($where_data['product_valid_from'],'','`Product Valid From`','only_dates');
    $date_interval_to=prepare_mysql_dates('',$where_data['product_valid_to'],'`Product Valid To`','only_dates');



    $where.=$date_interval_from['mysql'].$date_interval_to['mysql'];


    /*

    $where_billing_geo_constraints='';
    if ($where_data['billing_geo_constraints']!='') {
        $where_billing_geo_constraints=sprintf(" and `Order Main Country 2 Alpha Code`='%s'",$where_data['billing_geo_constraints']);
    }


    */

    //print $table. $where; exit;

    return array($where,$table);
}

function dn_awhere($awhere) {


    $where_data=array(
                    //'product_ordered1'=>'∀',
                    'weight'=>array(),
                    'state'=>array(),
                    'note_type'=>array(),
                    'dispatch_method'=>array(),
                    'parcel_type'=>array(),
                    'created_date_from'=>'',
                    'created_date_to'=>'',
                    'start_picking_date_from'=>'',
                    'start_picking_date_to'=>'',
                    'finish_picking_date_from'=>'',
                    'finish_picking_date_to'=>'',
                    'start_packing_date_from'=>'',
                    'start_packing_date_to'=>'',
                    'finish_packing_date_from'=>'',
                    'finish_packing_date_to'=>'',
                    'dispatched_approved_date_from'=>'',
                    'dispatched_approved_date_to'=>'',
                    'delivery_note_date_from'=>'',
                    'delivery_note_date_to'=>'',
                    'billing_geo_constraints'=>'',
                    'weight_lower'=>'',
                    'weight_upper'=>''
                );

    //  $awhere=json_decode($awhere,TRUE);


    foreach ($awhere as $key=>$item) {
        $where_data[$key]=$item;
    }


    $where='where true';
    $table='`Delivery Note Dimension` D ';

    $use_product=false;
    //$use_categories =false;
    $use_otf =false;




    $weight_where='';
    foreach($where_data['weight'] as $weight) {
        switch ($weight) {
        case 'less':
            $weight_where.=sprintf(" and `Delivery Note Weight`<'%s' ",$where_data['weight_lower']);
            break;
        case 'equal':
            $weight_where.=sprintf(" and `Delivery Note Weight`='%s'  ",$where_data['weight_lower']);
            break;
        case 'more':
            $weight_where.=sprintf(" and `Delivery Note Weight`>'%s'  ",$where_data['weight_upper']);
            break;
        case 'between':
            $weight_where.=sprintf(" and  `Delivery Note Weight`>'%s'  and `Delivery Note Weight`<'%s'", $where_data['weight_lower'], $where_data['weight_upper']);
            break;
        }
    }
    $weight_where=preg_replace('/^\s*and/','',$weight_where);

    if ($weight_where!='') {
        $where.=" and ($weight_where)";
    }

    $state_where='';
    foreach($where_data['state'] as $state) {
        switch ($state) {
        case 'picking_and_packing':
            $state_where.=sprintf(" or `Delivery Note State`='Picking & Packing' ");
            break;
        case 'packer_assigned':
            $state_where.=sprintf(" or `Delivery Note State`='Packer Assigned'  ");
            break;
        case 'ready_to_be_picked':
            $state_where.=sprintf(" or  `Delivery Note State`='Ready to be Picked'  ");
            break;
        case 'picker_assigned':
            $state_where.=sprintf(" or  `Delivery Note State`='Picker Assigned'  ");
            break;
        case 'picking':
            $state_where.=sprintf(" or  `Delivery Note State`='Picking' ");
            break;
        case 'picked':
            $state_where.=sprintf(" or  `Delivery Note State`='Picked'  ");
            break;
        case 'packing':
            $state_where.=sprintf(" or  `Delivery Note State`='Packing'  ");
            break;
        case 'packed':
            $state_where.=sprintf(" or  `Delivery Note State`='Packed'  ");
            break;
        case 'approved':
            $state_where.=sprintf(" or  `Delivery Note State`='Approved'  ");
            break;
        case 'dispatched':
            $state_where.=sprintf(" or  `Delivery Note State`='Dispatched'  ");
            break;
        case 'cancelled':
            $state_where.=sprintf(" or  `Delivery Note State`='Cancelled'  ");
            break;
        case 'cancelled_to_restock':
            $state_where.=sprintf(" or  `Delivery Note State`='Cancelled to Restock'  ");
            break;

        }
    }
    $state_where=preg_replace('/^\s*or/','',$state_where);
    if ($state_where!='') {
        $where.=" and ($state_where)";
    }

    $note_type_where='';
    foreach($where_data['note_type'] as $note_type) {
        switch ($note_type) {
        case 'replacement_and_shortages':
            $note_type_where.=sprintf(" or `Delivery Note Type`='Replacement & Shortages' ");
            break;
        case 'order':
            $note_type_where.=sprintf(" or `Delivery Note Type`='Order'  ");
            break;
        case 'replacement':
            $note_type_where.=sprintf(" or  `Delivery Note Type`='Replacement'  ");
            break;
        case 'shortages':
            $note_type_where.=sprintf(" or  `Delivery Note Type`='Shortages'  ");
            break;
        case 'sample':
            $note_type_where.=sprintf(" or  `Delivery Note Type`='Sample' ");
            break;
        case 'donation':
            $note_type_where.=sprintf(" or  `Delivery Note Type`='Donation'  ");
            break;

        }
    }
    $note_type_where=preg_replace('/^\s*or/','',$note_type_where);
    if ($note_type_where!='') {
        $where.=" and ($note_type_where)";
    }



    $dispatch_method_where='';
    foreach($where_data['dispatch_method'] as $dispatch_method) {
        switch ($dispatch_method) {
        case 'dispatch':
            $dispatch_method_where.=sprintf(" or `Delivery Note Dispatch Method`='Dispatch' ");
            break;
        case 'collection':
            $dispatch_method_where.=sprintf(" or `Delivery Note Dispatch Method`='Collection'  ");
            break;
        case 'unknown':
            $dispatch_method_where.=sprintf(" or  `Delivery Note Dispatch Method`='Unknown'  ");
            break;
        case 'na':
            $dispatch_method_where.=sprintf(" or  `Delivery Note Dispatch Method`='NA'  ");
            break;

        }
    }
    $dispatch_method_where=preg_replace('/^\s*or/','',$dispatch_method_where);
    if ($dispatch_method_where!='') {
        $where.=" and ($dispatch_method_where)";
    }



    $parcel_type_where='';
    foreach($where_data['parcel_type'] as $parcel_type) {
        switch ($parcel_type) {
        case 'box':
            $parcel_type_where.=sprintf(" or `Delivery Note Parcel Type`='Box' ");
            break;
        case 'pallet':
            $parcel_type_where.=sprintf(" or `Delivery Note Parcel Type`='Pallet'  ");
            break;
        case 'envelope':
            $parcel_type_where.=sprintf(" or  `Delivery Note Parcel Type`='Envelope'  ");
            break;

        }
    }
    $parcel_type_where=preg_replace('/^\s*or/','',$parcel_type_where);
    if ($parcel_type_where!='') {
        $where.=" and ($parcel_type_where)";
    }


    $date_interval_created=prepare_mysql_dates($where_data['created_date_from'],$where_data['created_date_to'],'`Delivery Note Date Created`','only_dates');
    $date_interval_start_picking=prepare_mysql_dates($where_data['start_picking_date_from'],$where_data['start_picking_date_to'],'`Delivery Note Date Start Picking`','only_dates');
    $date_interval_finish_picking=prepare_mysql_dates($where_data['finish_picking_date_from'],$where_data['finish_picking_date_to'],'`Delivery Note Date Finish Picking`','only_dates');
    $date_interval_start_packing=prepare_mysql_dates($where_data['start_packing_date_from'],$where_data['start_packing_date_to'],'`Delivery Note Date Start Packing`','only_dates');
    $date_interval_finish_packing=prepare_mysql_dates($where_data['finish_packing_date_from'],$where_data['finish_packing_date_to'],'`Delivery Note Date Finish Packing`','only_dates');
    $date_interval_dispatched_approved=prepare_mysql_dates($where_data['dispatched_approved_date_from'],$where_data['dispatched_approved_date_to'],'`Delivery Note Date Dispatched Approved`','only_dates');
    $date_interval_delivery_note=prepare_mysql_dates($where_data['delivery_note_date_from'],$where_data['delivery_note_date_to'],'`Delivery Note Date`','only_dates');



    $where.=$date_interval_created['mysql'].$date_interval_start_picking['mysql'].$date_interval_finish_picking['mysql'].$date_interval_start_packing['mysql'].$date_interval_finish_packing['mysql'].$date_interval_dispatched_approved['mysql'].$date_interval_delivery_note['mysql'];




    $where_billing_geo_constraints='';
    if ($where_data['billing_geo_constraints']!='') {
        $where_billing_geo_constraints=sprintf(" and `Order Main Country 2 Alpha Code`='%s'",$where_data['billing_geo_constraints']);
    }




    //print $table. $where; exit;

    return array($where,$table);
}

function orders_awhere($awhere) {
    // $awhere=preg_replace('/\\\"/','"',$awhere);



    $where_data=array(
                    //'product_ordered1'=>'∀',
                    'geo_constraints'=>'',
                    'product_not_ordered1'=>'',
                    'product_not_received1'=>'',
                    'billing_geo_constraints'=>'',
                    'delivery_geo_constraints'=>'',
                    'dont_have'=>array(),
                    'have'=>array(),
                    'allow'=>array(),
                    'dont_allow'=>array(),
                    'categories'=>'',
                    'product_ordered_or_from'=>'',
                    'product_ordered_or_to'=>'',
                    'order_created_from'=>'',
                    'order_created_to'=>'',
                    'product_ordered_or'=>'',
                    'store_key'=>false
                );

    //  $awhere=json_decode($awhere,TRUE);


    foreach ($awhere as $key=>$item) {
        $where_data[$key]=$item;
    }

    $where='where true';
    $table='`Order Dimension` O ';

    $use_product=false;
    //$use_categories =false;
    $use_otf =false;
    /*
        $where_categories='';
        if ($where_data['categories']!='') {

            $categories_keys=preg_split('/,/',$where_data['categories']);
            $valid_categories_keys=array();
            foreach ($categories_keys as $item) {
                if (is_numeric($item))
                    $valid_categories_keys[]=$item;
            }
            $categories_keys=join($valid_categories_keys,',');
            if ($categories_keys) {
                $use_categories =true;
                $where_categories=sprintf(" and `Subject`='Customer' and `Category Key` in (%s)",$categories_keys);
            }


        }
    */
    $wr=array();
    $country=array();
    $city=array();
    $postal_code=array();


    $where_billing_geo_constraints='';
    print $where_data['billing_geo_constraints'];
    $pattern_wr = array("/^wr\(/", "/\)/");
    $pattern_city = array("/^t\(/", "/\)/");
    $pattern_pc = array("/^pc\(/", "/\)/");
    $pattern_country = '';

    $temp=explode(",",$where_data['billing_geo_constraints']);
    foreach($temp as $key=>$value) {
        if (preg_match('/^wr\(/', $value))
            $wr[]=preg_replace($pattern_wr,'',$value);
        else if (preg_match('/^t\(/', $value))
            $city[]=preg_replace($pattern_city,'',$value);
        else if (preg_match('/^pc\(/', $value))
            $postal_code[]=preg_replace($pattern_pc,'',$value);
        else
            $country[]=$value;
    }
    //print 'wr';
    //print_r($wr);
    //print 'country';
    //print_r($country);
    //print 'city';
    //print_r($city);
    //print 'pc';
    //print_r($postal_code);
    //exit;
    if ($where_data['billing_geo_constraints']!='') {
        $where_billing_geo_constraints=sprintf(" and `Order Main Country 2 Alpha Code`='%s'",$where_data['billing_geo_constraints']);
    }

    $where_delivery_geo_constraints='';
    if ($where_data['delivery_geo_constraints']!='') {
        $where_delivery_geo_constraints=sprintf(" and `Order Ship To Country Code`='%s'",$where_data['delivery_geo_constraints']);
    }

    if ($where_data['product_ordered_or']=='')
        $where_data['product_ordered_or']='∀';

    if ($where_data['product_ordered_or']!='') {
        if ($where_data['product_ordered_or']!='∀') {
            $use_otf=true;
            $where_product_ordered1=true;
            //list($where_product_ordered1,$use_product)=extract_product_groups($where_data['product_ordered_or'],$where_data['store_key']);
        } else
            $where_product_ordered1='true';
    } else {
        $where_product_ordered1='false';
    }
    //print $where_product_ordered1;


    $date_interval_order_created=prepare_mysql_dates($where_data['order_created_from'],$where_data['order_created_to'],'`Order Date`','only_dates');

    /*
        $date_interval_when_customer_created=prepare_mysql_dates($where_data['customer_created_from'],$where_data['customer_created_to'],'`Customer First Contacted Date`','only_dates');
        if ($date_interval_when_ordered['mysql']) {
            $use_otf=true;
        }

    	$date_interval_when_ordered=prepare_mysql_dates($where_data['product_ordered_or_from'],$where_data['product_ordered_or_to'],'`Order Date`','only_dates');
        if ($date_interval_when_customer_created['mysql']) {

        }

    	$date_interval_lost_customer=prepare_mysql_dates($where_data['lost_customer_from'],$where_data['lost_customer_to'],'`Customer Lost Date`','only_dates');

        if ($use_otf) {
            $table='`Customer Dimension` C  left join  `Order Transaction Fact` OTF  on (C.`Customer Key`=OTF.`Customer Key`)   ';
        }
        if ($use_product) {
            $table='`Customer Dimension` C  left join  `Order Transaction Fact` OTF  on (C.`Customer Key`=OTF.`Customer Key`) left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)  ';
        }



        if ($use_categories) {

            $table.='  left join   `Category Bridge` CatB on (C.`Customer Key`=CatB.`Subject Key`)   ';
        }

    */


    //  $where='where (  '.$where_product_ordered1.$date_interval_when_customer_created['mysql'].") $where_billing_geo_constraints where_delivery_geo_constraints";

    $where='where (  '.$where_product_ordered1.$date_interval_order_created['mysql'].") $where_billing_geo_constraints $where_delivery_geo_constraints";
//print $where;exit;
    /*
        foreach($where_data['dont_have'] as $dont_have) {
            switch ($dont_have) {
            case 'tel':
                $where.=sprintf(" and `Customer Main Telephone Key` IS NULL ");
                break;
            case 'email':
                $where.=sprintf(" and `Customer Main Email Key` IS NULL ");
                break;
            case 'fax':
                $where.=sprintf(" and `Customer Main Fax Key` IS NULL ");
                break;
            case 'address':
                $where.=sprintf(" and `Customer Main Address Incomplete`='Yes' ");
                break;
            }
        }
        foreach($where_data['have'] as $dont_have) {
            switch ($dont_have) {
            case 'tel':
                $where.=sprintf(" and `Customer Main Telephone Key` IS NOT NULL ");
                break;
            case 'email':
                $where.=sprintf(" and `Customer Main Email Key` IS NOT NULL ");
                break;
            case 'fax':
                $where.=sprintf(" and `Customer Main Fax Key` IS NOT NULL ");
                break;
            case 'address':
                $where.=sprintf(" and `Customer Main Address Incomplete`='No' ");
                break;
            }
        }
    */
    /*
        $allow_where='';
       foreach($where_data['allow'] as $allow) {
            switch ($allow) {
            case 'newsletter':
                $allow_where.=sprintf(" or `Customer Send Newsletter`='Yes' ");
                break;
            case 'marketing_email':
                $allow_where.=sprintf(" or `Customer Send Email Marketing`='Yes'  ");
                break;
            case 'marketing_post':
                $allow_where.=sprintf(" or  `Customer Send Postal Marketing`='Yes'  ");
                break;

            }



        }
        $allow_where=preg_replace('/^\s*or/','',$allow_where);
        if($allow_where!=''){
        $where.="and ($allow_where)";
        }

        $dont_allow_where='';
       foreach($where_data['dont_allow'] as $dont_allow) {
            switch ($dont_allow) {
            case 'newsletter':
                $dont_allow_where.=sprintf(" or `Customer Send Newsletter`='No' ");
                break;
            case 'marketing_email':
                $dont_allow_where.=sprintf(" or `Customer Send Email Marketing`='No'  ");
                break;
            case 'marketing_post':
                $dont_allow_where.=sprintf(" or  `Customer Send Postal Marketing`='No'  ");
                break;
            }



        }
        $dont_allow_where=preg_replace('/^\s*or/','',$dont_allow_where);
        if($dont_allow_where!=''){
        $where.="and ($dont_allow_where)";
        }


    	$customers_which_where='';
       foreach($where_data['customers_which'] as $customers_which) {
            switch ($customers_which) {
            case 'active':
                $customers_which_where.=sprintf(" or `Customer Active`='Yes' ");
                break;
            case 'losing':
                $customers_which_where.=sprintf(" or `Customer Type by Activity`='Losing'  ");
                break;
            case 'lost':
                $customers_which_where.=sprintf(" or `Customer Active`='No'  ");
                break;
            }
        }
        $customers_which_where=preg_replace('/^\s*or/','',$customers_which_where);
        if($customers_which_where!=''){
        $where.="and ($customers_which_where)";
        }

    	$invoice_option_where='';
       foreach($where_data['invoice_option'] as $invoice_option) {
            switch ($invoice_option) {
            case 'less':
                $invoice_option_where.=sprintf(" and `Customer Has More Invoices Than`<'%d' ",$where_data['number_of_invoices_lower']);
                break;
            case 'equal':
                $invoice_option_where.=sprintf(" and `Customer Has More Invoices Than`='%d'  ",$where_data['number_of_invoices_lower']);
                break;
            case 'more':
                $invoice_option_where.=sprintf(" and  `Customer Has More Invoices Than`>'%d'  ",$where_data['number_of_invoices_lower']);
                break;

    		case 'between':
    			$invoice_option_where.=sprintf(" and  `Customer Has More Invoices Than`>'%d'  and `Customer Has More Invoices Than`<'%d'", $where_data['number_of_invoices_lower'], $where_data['number_of_invoices_upper']);
    			break;
    		}
        }
        $invoice_option_where=preg_replace('/^\s*and/','',$invoice_option_where);

        if($invoice_option_where!=''){
        $where.="and ($invoice_option_where)";
        }

    	$sales_option_where='';
       foreach($where_data['sales_option'] as $sales_option) {
            switch ($sales_option) {
            case 'sales_less':
                $sales_option_where.=sprintf(" and `Invoice Transaction Gross Amount`<'%s' ",$where_data['sales_lower']);
                break;
            case 'sales_equal':
                $sales_option_where.=sprintf(" and `Invoice Transaction Gross Amount`='%s'  ",$where_data['sales_lower']);
                break;
            case 'sales_more':
                $sales_option_where.=sprintf(" and  `Invoice Transaction Gross Amount`>'%s'  ",$where_data['sales_lower']);
                break;

    		case 'sales_between':
    			$sales_option_where.=sprintf(" and  `Invoice Transaction Gross Amount`>'%s'  and `Invoice Transaction Gross Amount`<'%s'", $where_data['sales_lower'], $where_data['sales_upper']);
    			break;
    		}
        }
        $sales_option_where=preg_replace('/^\s*and/','',$sales_option_where);

        if($sales_option_where!=''){
        $where.="and ($sales_option_where)";
        }

    */
    /*
    $not_customers_which_where='';
    foreach($where_data['not_customers_which'] as $not_customers_which) {
        switch ($not_customers_which) {
        case 'active':
            $not_customers_which_where.=sprintf(" or `Customer Active`='No' ");
            break;
        case 'losing':
            $not_customers_which_where.=sprintf(" or `Customer Type by Activity`='Active'  ");
            break;
        case 'lost':
            $not_customers_which_where.=sprintf(" or  `Customer Active`='Yes'  ");
            break;
        }
    }

    $not_customers_which_where=preg_replace('/^\s*or/','',$not_customers_which_where);
    if($not_customers_which_where!=''){
    $where.="and ($not_customers_which_where)";
    }
    *///print $table;print $where;
    //print $table;
    //print '|';
    //print $where; exit;

    //exit;
    return array($where,$table);


}

function extract_product_groups($str,$store_key=0,$q_prod_name='OTF.`Product Code` like',$q_prod_id='OTF.`Product ID`',$q_group_id='OTF.`Product Family Key` in',$q_department_id='OTF.`Product Department Key`  in') {


    if ($str=='')
        return '';
    $where='';
    $where_g='';
    $where_d='';
    $use_product=false;




    $department_names=array();
    $department_ids=array();

    if (preg_match_all('/d\([a-z0-9\-\,]*\)/i',$str,$matches)) {


        foreach($matches[0] as $match) {

            $_groups=preg_replace('/\)$/i','',preg_replace('/^d\(/i','',$match));
            $_groups=preg_split('/\s*,\s*/i',$_groups);

            foreach($_groups as $group) {
                //$use_product=true;
                if (is_numeric($group)) {
                    $department_ids[$group]=$group;
                } else {
                    $department_names[$group]=prepare_mysql($group);

                }

            }
        }
        $str=preg_replace('/d\([a-z0-9\-\,]*\)/i','',$str);
    }
    if (count($department_names)>0) {
        if ($store_key and is_numeric($store_key))
            $store_where=' and `Product Department Store Key`='.$store_key;
        else
            $store_where='';
        $sql=sprintf("select `Product Department Key` from `Product Department Dimension` where `Product Department Code` in (%s) %s ",join(',',$department_names),$store_where);
        $res=mysql_query($sql);

        while ($row=mysql_fetch_assoc($res)) {
            $department_ids[$row['Product Department Key']]=$row['Product Department Key'];
        }

    }

    if (count($department_ids)>0) {
        $where_d='or '.$q_department_id.' ('.join(',',$department_ids).') ';
        //   $use_product=true;
    }



    $family_names=array();
    $family_ids=array();

    if (preg_match_all('/f\([a-z0-9\-\,]*\)/i',$str,$matches)) {

        foreach($matches[0] as $match) {

            $_groups=preg_replace('/\)$/i','',preg_replace('/^f\(/i','',$match));
            $_groups=preg_split('/\s*,\s*/i',$_groups);

            foreach($_groups as $group) {
                //$use_product=true;
                if (is_numeric($group)) {
                    $family_ids[$group]=$group;
                } else {
                    $family_names[$group]=prepare_mysql($group);

                }

            }
        }
        $str=preg_replace('/f\([a-z0-9\-\,]*\)/i','',$str);
    }



    if (count($family_names)>0) {
        if ($store_key and is_numeric($store_key))
            $store_where=' and `Product Family Store Key`='.$store_key;
        else
            $store_where='';
        $sql=sprintf("select `Product Family Key` from `Product Family Dimension` where `Product Family Code` in (%s) %s ",join(',',$family_names),$store_where);
        $res=mysql_query($sql);

        while ($row=mysql_fetch_assoc($res)) {
            $family_ids[$row['Product Family Key']]=$row['Product Family Key'];
        }

    }

    if (count($family_ids)>0) {
        $where_g='or '.$q_group_id.' ('.join(',',$family_ids).') ';
        // $use_product=true;
    }
    //print_r($family_ids);


    $products=preg_split('/\s*,\s*/i',$str);

    $where_p='';
    foreach($products as $product) {
        if ($product!='') {
            $product=addslashes($product);
            if (is_numeric($product))
                $where_p.= " or $q_prod_id  '$product'";
            else
                $where_p.= " or $q_prod_name  '$product'";
        }
    }



    $where=preg_replace('/^\s*or\s*/i','',$where_d.$where_g.$where_p);




    return array('('.$where.')',$use_product);

}

function extract_customer_geo_groups($str,$q_country_code='C.`Customer Main Country Code`',$q_wregion_code='C.`Customer Main Country Code`',$q_town_name='C.`Customer Main Town`',$q_post_code='C.`Customer Main Postal Code`') {
    if ($str=='')
        return '';
    $where='';
    $where_c='';
    $where_t='';
    $where_pc='';
    $where_wr='';
    $use_product=false;
    $town_names=array();
    $post_code_names=array();

    $country_codes=array();
    $wregion_codes=array();

    if (preg_match_all('/t\([a-z0-9\-\,]*\)/i',$str,$matches)) {
        foreach($matches[0] as $match) {
            $_towns=preg_replace('/\)$/i','',preg_replace('/^t\(/i','',$match));
            $_towns=preg_split('/\s*,\s*/i',$_towns);
            foreach($_towns as $town) {
                if ($town!='') {
                    $town=addslashes($town);
                    $town_names[$town]=$town;
                } else {
                    $town_names['_none_']='';
                }
            }
        }
        if (count($town_names)>0)
            $where_t.= " or $q_town_name in ('".join("','",$town_names)."')";

        $str=preg_replace('/t\([a-z0-9\-\,]*\)/i','',$str);
    }

    if (preg_match_all('/pc\([a-z0-9\-\,]*\)/i',$str,$matches)) {
        foreach($matches[0] as $match) {
            $_post_codes=preg_replace('/\)$/i','',preg_replace('/^pc\(/i','',$match));
            $_post_codes=preg_split('/\s*,\s*/i',$_post_codes);
            foreach($_post_codes as $post_code) {
                if ($post_code!='') {
                    $post_code=addslashes($post_code);
                    $post_code_names[$post_code]=$post_code;
                } else {
                    $town_names['_none_']='';
                }
            }
        }
        if (count($post_code_names)>0)
            $where_t.= " or $q_post_code in ('".join("','",$post_code_names)."')";

        $str=preg_replace('/pc\([a-z0-9\-\,]*\)/i','',$str);
    }


    if (preg_match_all('/wr\([a-z0-9\-\,]*\)/i',$str,$matches)) {


        foreach($matches[0] as $match) {

            $_world_regions=preg_replace('/\)$/i','',preg_replace('/^wr\(/i','',$match));
            $_world_regions=preg_split('/\s*,\s*/i',$_world_regions);

            // print_r($_world_regions);
            foreach($_world_regions as $world_region) {
                if ($world_region!='' and strlen($world_region)==4) {
                    $world_region=addslashes($world_region);
                    $wregion_codes[$world_region]=$world_region;
                }

            }
        }


        $sql=sprintf("select `Country Code` from kbase.`Country Dimension` where `World Region Code` in (%s)","'".join("','",$wregion_codes)."'");
        $res=mysql_query($sql);
        while ($row=mysql_fetch_assoc($res)) {
            $country_codes[$row['Country Code']]=$row['Country Code'];

        }
        $str=preg_replace('/wr\([a-z0-9\-\,]*\)/i','',$str);
    }



    $products=preg_split('/\s*,\s*/i',$str);


    $where_c='';
    foreach($products as $product) {
        if ($product!='' and strlen($product)==3) {
            $product=addslashes($product);
            $country_codes[$product]=$product;

        }
    }




    if (count($country_codes)>0)
        $where_c.= " or $q_country_code in ('".join("','",$country_codes)."')";

    $where=preg_replace('/^\s*or\s*/i','',$where_wr.$where_c.$where_pc.$where_t);
    if ($where!='')
        $where=' and '.$where;
    return $where;

}


function _trim($string) {
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
    $str=_trim($str);
    if (preg_match('/^PO BOX\s+/i',$str))
        return strtoupper($str);



    $result='';


    $words=preg_split('/ /',$str);
    $first=true;
    foreach($words as $word) {
        if (preg_match('/([a-z]\.){1,}$/i',$word)) {
            $result.=' '.strtoupper($word);
            continue;
        }
        elseif(!$first and preg_match('/^(UK|USA|HP|IBM|GB|MB|CD|DVD|USB)$/i',$word)) {
            $result.=' '.strtoupper($word);
            continue;
        }
        elseif(!$first and preg_match('/^(and|y|o|or|of|at|des|les|las|le)$/i',$word)) {
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


    if (preg_match('/^PO BOX\s+/i',$str))
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

    $separator = array("-","+",","," ");

    $str = mb_strtolower(trim($str),"UTF-8");
    foreach($separator as $s) {
        $word = explode($s, $str);

        $return = "";
        foreach ($word as $val) {


            if (preg_match('/^www\.[^\s]+/i',$val)) {
                $return .= $s .mb_strtolower($val,"UTF-8");
            }
            elseif(preg_match('/^[a-z]{2,}\.$/i',$val)) {
                $return .= $s .mb_strtolower($val,"UTF-8");
            }
            elseif(preg_match('/^(st|Mr|Mrs|Miss|Dr|Ltd)$/i',$val)) {
                $return .= $s
                           . mb_strtoupper($val {0},"UTF-8")
                           . mb_substr($val,1,mb_strlen($val,"UTF-8")-1,"UTF-8");
            }

            elseif(preg_match('/^[^\s]+\.(com|uk|info|biz|org)$/i',$val)) {
                $return .= $s .mb_strtolower($val,"UTF-8");
            }
            elseif(preg_match('/^(aa|ee|ii|oo|uu)$/i',$val)) {
                $return .= $s .mb_strtoupper($val,"UTF-8");
            }
            elseif(preg_match('/^([a-z]\.){1,}$/i',$val)) {
                $return .= $s .mb_strtoupper($val,"UTF-8");
            }
            elseif(preg_match('/^c\/o$/i',$val)) {
                $return .= $s .'C/O';

            }
            elseif(preg_match('/^t\/a$/i',$val)) {
                $return .= $s .'T/A';

            }
            elseif(preg_match('/^([^(aeoiu)]{2,3})$/i',$val)) {

                $return .= $s .mb_strtoupper($val,"UTF-8");
            }
            elseif(preg_match('/^\(.+\)$/i',$val)) {
                $text=preg_replace('/^\(|\)$/i','',$val);
                //print "*** $text\n";
                $return .= $s.'('.mb_ucwords($text).')';
            }

            elseif(mb_strlen($val,"UTF-8")>0) {
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

    foreach($exceptions as $find=>$replace) {
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

function prepare_mysql($string,$null_if_empty=true) {

    if (is_numeric($string)) {
        return "'".$string."'";
    }
    elseif($string=='' and $null_if_empty) {
        return 'NULL';
    }
    else {
        return "'".addslashes($string)."'";


    }
}





function is_url($url) {
    if (preg_match('/^(www\.)?[a-z0-9-]+(.[a-z0-9-]+)*\.(com|uk|fr|biz|net|info|mx|jp|org)$/i',$url))
        return true;
    else
        return false;
}





function array_transverse($a,$cols) {
    $total=count($a);
    $rows=ceil($total/$cols);
    $to_add=($rows*$cols)-$total;
    $new_total=($rows*$cols);
    for ($i=0; $i<$to_add; $i++) {
        $a[]='';
    }
    $tmp=$cols;
    $cols=$rows;
    $rows=$tmp;

    // print "$total $cols $rows $to_add $new_total\n";


    $i=0;
    $j=-1;
    $old=array();
    foreach($a as $key=>$value) {




        if (fmod($i,$cols)==0) {
            $i=0;
            $j++;
        }
        //  print "$key $value ; $i $j\n";
        $old[$i][$j]=$value;
        $i++;
    }
    $new=array();
    for ($i=0; $i<$cols; $i++) {
        for ($j=0; $j<$rows; $j++) {
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

    switch ($interval) {

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

function number_weeks($days,$day) {

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

function deviation ($array) {

    $avg = average($array);
    if (!$avg)
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
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        if (strtotime($row['Currency Exchange Last Updated'])<date("Y-m-d H:i:s",strtotime('today -1 hour')))
            $reload=true;
        $exchange_rate=$row['Exchange'];
    } else {
        $reload=true;
        $in_db=false;
    }
    if ($reload) {
        $url = "http://quote.yahoo.com/d/quotes.csv?s=". $currency_from . $currency_to . "=X". "&f=l1&e=.csv";
        // print $url;
        $handle = fopen($url, "r");
        $contents = fread($handle,2000);
        fclose($handle);
        if (is_numeric($contents) and $contents>0) {
            $exchange_rate=$contents;
            if ($in_db) {
                $sql=sprintf("update `Currency Exchange Dimension` set `Exchange`=%f,`Currency Exchange Last Updated`=NOW() where `Currency Pair`=%s",$exchange_rate,prepare_mysql($currency_from.$currency_to));
                $res = mysql_query($sql);
            } else {
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
        foreach($array as $value) {
            if (!empty($value))
                return false;
        }
        return true;
    } else
        return empty($array);
}
/*
function:yearweek
returns: equivalent of YEARWEEK mysql function
 */
function yearweek($str) {
    $date=strtotime($str);
    $w=date('W',$date);
    $y=date("Y",$date);
    $m=date("m",$date);

    if ($w==1 and $m==12) {
        $y=$y+1;
    }
    if ($w>=52 and $m==1) {
        $y=$y-1;
    }
    return sprintf("%d%02d",$y,$w);
}

function quarter($date) {
    $date=strtotime($date);
    $month=date('m',$date);
    if ($month<=3)
        return 1;
    elseif($month<=6)
    return 2;
    elseif($month<=9)
    return 3;
    else
        return 4;

}

function yearquarter($date) {

    return date('Y',strtotime($date)).quarter($date);

}


function translate_written_number($string) {

    $numbers=array('zero','one','two','three','four','five','six','seven','eight','nine','ten','eleven');
    $common_suffixes=array('hundreds?'=>100,'thousands?'=>1000,'millons?'=>100000);

    $number_flat=join("|",$numbers);
    $common_suffixes_flat=join("|",$common_suffixes);
    if (preg_match("/$number_flat/i",$string)) {
        if (preg_match("/$common_suffixes_flat/i",$string)) {
            foreach($numbers as $number=>$number_string) {
                foreach($common_suffixes as $common_suffix=>$number_common_suffix) {
                    $string=_trim(preg_replace('/^(.*\s+|)$number_string\s?$common_suffix(\s+.*|)$/ '," ".($number*$number_common_suffix)." ",$string));
                }
            }
        } else {
            foreach($numbers as $number=>$number_string)
            $string=_trim(preg_replace('/^(.*\s+|)$number_string(\s+.*|)$/ '," $number ",$string));
        }
    }
    return $string;
}

function shuffle_assoc(&$array) {
    if (count($array)==0)
        return;
    $keys = array_keys($array);

    shuffle($keys);

    foreach($keys as $key) {
        $new[$key] = $array[$key];
    }

    $array = $new;

    return true;
}



function guess_file_format($filename) {

    $mimetype='Unknown';


    ob_start();
    system("uname");
    $system='Unknown';
    $_system = ob_get_clean();

    // print "S:$system M:$mimetype\n";

    if (preg_match('/darwin/i',$_system)) {
        ob_start();
        $system='Mac';
        system('file -I "'.addslashes($filename).'"');
        $mimetype=ob_get_clean();
        $mimetype=preg_replace('/^.*\:/','',$mimetype);

    }
    elseif(preg_match('/linux/i',$_system)) {
        ob_start();
        $system='Linux';
        $mimetype = system('file -ib "'.addslashes($filename).'"');
        $mimetype=ob_get_clean();
    }
    else {
        $system='Other';

    }


//print "** $filename **";

    if (preg_match('/png/i',$mimetype))
        $format='png';
    else if(preg_match('/jpeg/i',$mimetype))
    $format='jpeg';
    else if(preg_match('/image.psd/i',$mimetype))
    $format='psd';
    else if(preg_match('/gif/i',$mimetype))
    $format='gif';
    else if(preg_match('/wbmp$/i',$mimetype))
    $format='wbmp';

    else {
        $format='other';
    }
//  print "S:$system M:$mimetype\n";
    // return;

    return $format;

}

function guess_file_mime($file) {

    ob_start();
    system("uname");
    $mimetype='unknown';
    $system='Unknown';

    $_system = ob_get_clean();

    if (preg_match('/darwin/i',$_system)) {
        ob_start();
        $system='Mac';
        system("file -I $file");
        $mimetype=ob_get_clean();
        $mimetype=preg_replace('/^.*\:\s*/','',$mimetype);
        $mimetype=preg_replace('/\s*;.*$/','',$mimetype);
    }
    elseif(preg_match('/linux/i',$_system)) {
        $system='Linux';
        ob_start();
        system("file -ib $file ");
        $mimetype=ob_get_clean();
    }
    else {
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


function getEnumVals($table,$field,$sorted=true) {

    $result=mysql_query('show columns from '.$table.';');

    while ($tuple=mysql_fetch_assoc($result)) {
        if ($tuple['Field'] == $field) {
            $types=$tuple['Type'];
            $beginStr=strpos($types,"(")+1;
            $endStr=strpos($types,")");
            $types=substr($types,$beginStr,$endStr-$beginStr);
            $types=str_replace("'","",$types);
            $types=preg_split('/\,/',$types);
            if ($sorted)
                sort($types);
            break;
        }
    }

    return($types);
}

function parse_number($value) {
    if (is_numeric($value))
        return $value;

    $value=preg_replace('/[^\.^\,\d]/','',$value);
    if (preg_match('/\.\d?$/',$value)) {
        $value=preg_replace('/\,/','',$value);

    }
    elseif(preg_match('/\..*\,\d?$/',$value)) {
        $value=preg_replace('/\./','',$value);
        $value=preg_replace('/,/',',',$value);
    }
    return (float) $value;


}

function parse_weight($value) {
    $unit='Kg';
    $value=_trim($value);
    if (preg_match('/(kg|kilo?|kilograms?)$/i',$value)) {
        $value=parse_number($value);
        $unit='Kg';
    }
    elseif(preg_match('/(lb?s|pounds?|libras?)$/i',$value)) {
        $value=parse_number($value)*.4545 ;
        $unit='Lb';
    }
    elseif(preg_match('/(g|grams?|gms)$/i',$value)) {
        $value=parse_number($value)*0.001 ;
        $unit='g';
    }
    elseif(preg_match('/(tons?|tonnes?|t)$/i',$value)) {
        $value=parse_number($value)*1000 ;
        $unit='t';
    }
    else
        $value=parse_number($value);

    return array($value,$unit);
}


function convert_weigth($value,$from,$to) {
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

    if (array_key_exists($from.$to,$factors)) {
        return $factors[$from.$to]*$value;
    } else
        return $value;


}

function parse_volume($value) {
    $unit='L';
    $value=_trim($value);
    if (preg_match('/(cc|cm3)$/i',$value)) {
        $value=parse_number($value)*.001 ;
        $unit='cm3';
    }
    elseif(preg_match('/(cubic meter|cubic m|m3)$/i',$value)) {
        $value=parse_number($value)*1000 ;
        $unit='m3';
    }
    elseif(preg_match('/(mL)$/i',$value)) {
        $value=parse_number($value)*001 ;
        $unit='mL';
    }
    else
        $value=parse_number($value);

    return array($value,$unit);
}

function parse_distance($value) {


    $original_unit='m';
    $value=_trim($value);
    if (preg_match('/(cent.metro?|centimeter?|cm)$/i',$value)) {
        $value=parse_number($value)/100 ;
        $original_unit='cm';
    }
    elseif(preg_match('/(mm|milimiters?)$/i',$value)) {
        $value=parse_number($value)/1000 ;
        $original_unit='mm';
    }
    elseif(preg_match('/(inches|inch|in)$/i',$value)) {
        $value=parse_number($value)*0.0254;
        $original_unit='inch';
    }
    elseif(preg_match('/(foot|feets?|ft)$/i',$value)) {
        $value=parse_number($value)*0.3048;
        $original_unit='ft';
    }
    elseif(preg_match('/(mile?|milles?)$/i',$value)) {
        $value=parse_number($value)* 1609.344;
        $original_unit='mile';
    }
    else
        $value=parse_number($value);

    return array($value,$original_unit);
}

function distance($value,$unit='m') {
    switch ($unit) {
    case('cm'):
        return number($value*100).'cm';
    case('mm'):
        return number($value*1000).'mm';
    default:
        return number($value).'m';
    }

}


function volume($value,$unit='L') {

    return number($value).'L';
}


function parse_parcels($value) {
    $unit='Box';
    $value=_trim($value);
    if (preg_match('/(pallet)$/i',$value)) {
        $value=parse_number($value)*.4545 ;
        $unit='Pallet';
    }
    elseif(preg_match('/(sobre|envelope)$/i',$value)) {
        $value=parse_number($value)*0.001 ;
        $unit='Envelope';
    }
    else
        $value=parse_number($value);

    return array($value,$unit);
}


function number2alpha($number) {
    $alpha=  chr(65+fmod($number-1,26));
    $pos=floor(($number-1)/26);

    $prefix='';
    if ($pos>0) {
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

        $consonants .= '!=/[]{}~\<>$%^&*()_+@#.,)(*%%';
    }
    if ($strength & 8) {

        $consonants .= '2345678906789$%^&*(';

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

function get_corporation_data() {
    $sql=sprintf("select * from `HQ Dimension`");
    $res=mysql_query($sql);
    $corporate_data=array('HQ Currency'=>'GBP');
    if ($corporate_data=mysql_fetch_assoc($res)) {
        true;
    }
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




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function products_awhere($awhere) {
    // $awhere=preg_replace('/\\\"/','"',$awhere);
    //    print "$awhere";


    $where_data=array(
                    'product_ordered1'=>'∀',
                    'geo_constraints'=>'',
                    'product_not_ordered1'=>'',
                    'product_not_received1'=>'',
                    'ordered_from'=>'',
                    'ordered_to'=>'',
                    'product_valid_from'=>'',
                    'product_valid_to'=>'',
                    'dont_have'=>array(),
                    'have'=>array(),
                    'categories'=>''
                );

    //  $awhere=json_decode($awhere,TRUE);


    foreach ($awhere as $key=>$item) {
        $where_data[$key]=$item;
    }


    $where='where true';
    $table='`Product Dimension` C ';

//print_r($where_data);
    $use_product=false;
    $use_categories =false;
    $use_otf =false;


    $where_geo_constraints='';
    if ($where_data['geo_constraints']!='') {
        $where_geo_constraints=extract_products_geo_groups($where_data['geo_constraints']);
    }


    if ($where_data['product_ordered1']=='')
        $where_data['product_ordered1']='∀';


    if ($where_data['product_ordered1']!='') {
        if ($where_data['product_ordered1']!='∀') {
            $use_otf=true;
            list($where_product_ordered1,$use_product)=extract_products_groups($where_data['product_ordered1']);
        } else
            $where_product_ordered1='true';
    } else {
        $where_product_ordered1='false';
    }

    if ($where_data['product_not_ordered1']!='') {
        if ($where_data['product_not_ordered1']!='ALL') {
            $use_otf=true;
            $where_product_not_ordered1=extract_products_groups($where_data['product_ordered1'],'O.`Product Code` not like','transaction.product_id not like','F.`Product Family Code` not like','O.`Product Family Key` like');
        } else
            $where_product_not_ordered1='false';
    } else
        $where_product_not_ordered1='true';

    if ($where_data['product_not_received1']!='') {
        if ($where_data['product_not_received1']!='∀') {
            $use_otf=true;
            $where_product_not_received1=extract_products_groups($where_data['product_ordered1'],'(ordered-dispatched)>0 and    product.code  like','(ordered-dispatched)>0 and  transaction.product_id not like','(ordered-dispatched)>0 and  product_group.name not like','(ordered-dispatched)>0 and  product_group.id like');
        } else {
            $use_otf=true;
            $where_product_not_received1=' ((ordered-dispatched)>0)  ';
        }
    } else
        $where_product_not_received1='true';

    $date_interval_when_ordered=prepare_mysql_dates($where_data['ordered_from'],$where_data['ordered_to'],'`Invoice Date`','only_dates');
    if ($date_interval_when_ordered['mysql']) {
        $use_otf=true;
    }
    $date_interval_when_customer_created=prepare_mysql_dates($where_data['product_valid_from'],$where_data['product_valid_to'],'`Product Valid From`','only_dates');
    if ($date_interval_when_customer_created['mysql']) {

    }


    if ($use_otf) {
        $table='`Product Dimension` C   ';
    }
    if ($use_product) {
        $table=' `Product Dimension` C ';
    }









    $where='where (  '.$where_product_ordered1.' and '.$where_product_not_ordered1.' and '.$where_product_not_received1.$date_interval_when_ordered['mysql'].$date_interval_when_customer_created['mysql'].")  $where_geo_constraints";


    return array($where,$table);

}

//--------------------------------------------------------------------------------------------------------------

function extract_products_groups($str,$q_prod_name='C.`Product Code` like',$q_prod_id='C.`Product ID`',$q_department_name='C.`Product Main Department Code` like',$q_department_id='C.`Product Main Department Key` like') {
    if ($str=='')
        return '';
    $where='';
    $where_g='';
    $where_d='';
    $use_product=false;

    if (preg_match_all('/d\([a-z0-9\-\,]*\)/i',$str,$matches)) {


        foreach($matches[0] as $match) {

            $_departments=preg_replace('/\)$/i','',preg_replace('/^d\(/i','',$match));
            $_departments=preg_split('/\s*,\s*/i',$_departments);

            foreach($_departments as $department) {
                $department_ordered=addslashes($department);
                if (is_numeric($department_ordered))
                    $where_d.=" or $q_department_id  '$department_ordered'";
                else {
                    $where_d.=" or $q_department_name '$department_ordered'";
                    $use_product=true;
                }
            }
        }
        $str=preg_replace('/d\([a-z0-9\-\,]*\)/i','',$str);
    }

    if (preg_match_all('/f\([a-z0-9\-\,]*\)/i',$str,$matches)) {


        foreach($matches[0] as $match) {

            $_groups=preg_replace('/\)$/i','',preg_replace('/^f\(/i','',$match));
            $_groups=preg_split('/\s*,\s*/i',$_groups);

            foreach($_groups as $group) {
                $group_ordered=addslashes($group);
                if (is_numeric($group_ordered))
                    $where_g.=" or $q_group_id  '$group_ordered'";
                else {
                    $where_g.=" or $q_group_name '$group_ordered'";
                    $use_product=true;
                }
            }
        }
        $str=preg_replace('/f\([a-z0-9\-\,]*\)/i','',$str);
    }


    $products=preg_split('/\s*,\s*/i',$str);

    $where_p='';
    foreach($products as $product) {
        if ($product!='') {
            $product=addslashes($product);
            if (is_numeric($product))
                $where_p.= " or $q_prod_id  '$product'";
            else
                $where_p.= " or $q_prod_name  '$product'";
        }
    }



    $where=preg_replace('/^\s*or\s*/i','',$where_d.$where_g.$where_p);
    return array('('.$where.')',$use_product);

}
function extract_products_geo_groups($str,$q_country_code='C.`Customer Main Country Code`',$q_wregion_code='C.`Customer Main Country Code`',$q_town_name='C.`Customer Main Town`',$q_post_code='C.`Customer Main Postal Code`') {
    if ($str=='')
        return '';
    $where='';
    $where_c='';
    $where_t='';
    $where_pc='';
    $where_wr='';
    $use_product=false;
    $town_names=array();
    $post_code_names=array();

    $country_codes=array();
    $wregion_codes=array();

    if (preg_match_all('/t\([a-z0-9\-\,]*\)/i',$str,$matches)) {
        foreach($matches[0] as $match) {
            $_towns=preg_replace('/\)$/i','',preg_replace('/^t\(/i','',$match));
            $_towns=preg_split('/\s*,\s*/i',$_towns);
            foreach($_towns as $town) {
                if ($town!='') {
                    $town=addslashes($town);
                    $town_names[$town]=$town;
                } else {
                    $town_names['_none_']='';
                }
            }
        }
        if (count($town_names)>0)
            $where_t.= " or $q_town_name in ('".join("','",$town_names)."')";

        $str=preg_replace('/t\([a-z0-9\-\,]*\)/i','',$str);
    }

    if (preg_match_all('/pc\([a-z0-9\-\,]*\)/i',$str,$matches)) {
        foreach($matches[0] as $match) {
            $_post_codes=preg_replace('/\)$/i','',preg_replace('/^pc\(/i','',$match));
            $_post_codes=preg_split('/\s*,\s*/i',$_post_codes);
            foreach($_post_codes as $post_code) {
                if ($post_code!='') {
                    $post_code=addslashes($post_code);
                    $post_code_names[$post_code]=$post_code;
                } else {
                    $town_names['_none_']='';
                }
            }
        }
        if (count($post_code_names)>0)
            $where_t.= " or $q_post_code in ('".join("','",$post_code_names)."')";

        $str=preg_replace('/pc\([a-z0-9\-\,]*\)/i','',$str);
    }
    if (preg_match_all('/wr\([a-z0-9\-\,]*\)/i',$str,$matches)) {


        foreach($matches[0] as $match) {

            $_world_regions=preg_replace('/\)$/i','',preg_replace('/^wr\(/i','',$match));
            $_world_regions=preg_split('/\s*,\s*/i',$_world_regions);

            // print_r($_world_regions);
            foreach($_world_regions as $world_region) {
                if ($world_region!='' and strlen($world_region)==4) {
                    $world_region=addslashes($world_region);
                    $wregion_codes[$world_region]=$world_region;
                }

            }
        }
        $sql=sprintf("select `Country Code` from kbase.`Country Dimension` where `World Region Code` in (%s)","'".join("','",$wregion_codes)."'");
        $res=mysql_query($sql);
        while ($row=mysql_fetch_assoc($res)) {
            $country_codes[$row['Country Code']]=$row['Country Code'];

        }
        $str=preg_replace('/wr\([a-z0-9\-\,]*\)/i','',$str);
    }
    $products=preg_split('/\s*,\s*/i',$str);
    $where_c='';
    foreach($products as $product) {
        if ($product!='' and strlen($product)==3) {
            $product=addslashes($product);
            $country_codes[$product]=$product;

        }
    }
    if (count($country_codes)>0)
        $where_c.= " or $q_country_code in ('".join("','",$country_codes)."')";

    $where=preg_replace('/^\s*or\s*/i','',$where_wr.$where_c.$where_pc.$where_t);
    if ($where!='')
        $where=' and '.$where;
    return $where;

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
    return($csv);
}

function aasort (&$array, $key) {
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

function sentence_similarity($a,$b) {
    // print "$a | $b\n";

    $words_to_ignore=array('the','of','from','is','are','to','and','or','for','at','an','as','so');

    $a=_trim($a);
    $a=preg_split('/\s/',$a);
    $b=_trim($b);
    $b=preg_split('/\s/',$b);

    $a=array_diff($a,$words_to_ignore);
    $b=array_diff($b,$words_to_ignore);

    $similarity_array=array();
    $max_sim=0;

    foreach($a as $item_a) {

        foreach($b as $item_b) {
            similar_text($item_a, $item_b, $sim);

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


?>