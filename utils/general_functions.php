<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 13 December 2015 at 16:23:32 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW


if(!function_exists('hash_equals'))
{
    function hash_equals($str1, $str2)
    {
        if(strlen($str1) != strlen($str2))
        {
            return false;
        }
        else
        {
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--)
            {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }
}


function get_prev_next($pivot, $array) {
    $prev_key = current($array);
    $next_key = false;
    while (current($array) !== $pivot && key($array) !== null) {
        $prev_key = current($array);
        next($array);
    }
    $current_key = current($array);
    if ($prev_key == $current_key) {
        $next_key = next($array);
        $prev_key = end($array);
    } else {
        $next_key = next($array);
        if (!$next_key) {
            $next_key = reset($array);
        }

    }

    return array(
        $prev_key,
        $next_key
    );
}






function delta($current_value, $old_value) {

    if ($current_value == $old_value) {
        return '--';
    }

    return percentage(
        $current_value - $old_value, $old_value, 1, 'NA', '%', true
    );
}


function delta_icon($_value, $_value_1yb,$inverse=false) {

    if($inverse){
        $up_arrow   = '<i title="%s" class="fa fa-fw fa-play fa-rotate-270 error" aria-hidden="true"></i>';
        $down_arrow = '<i title="%s" class="fa fa-fw fa-play fa-rotate-90 success" aria-hidden="true"></i>';
    }else {
        $up_arrow   = '<i title="%s" class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>';
        $down_arrow = '<i title="%s" class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>';
    }
        $no_change = '<i class="fa fa-fw fa-pause fa-rotate-90 super_discreet" aria-hidden="true"></i>';

    $delta_icon = sprintf($no_change);


    if ($_value != 0 and $_value_1yb != 0) {

        $diff  = $_value - $_value_1yb;
        $delta = delta($_value, $_value_1yb);

        if ($_value > $_value_1yb) {
            $delta_icon = sprintf($up_arrow, $delta);
        } elseif ($_value < $_value_1yb) {
            $delta_icon = sprintf($down_arrow, $delta);

        }
    }elseif($_value_1yb==0 and $_value>0){
        $delta_icon = sprintf($up_arrow, '');
    }elseif($_value_1yb==0 and $_value<0){
        $delta_icon = sprintf($down_arrow, '');
    }

    return $delta_icon;
}


function percentage($a, $b, $fixed = 1, $error_txt = 'NA', $psign = '%', $plus_sing = false) {

    $locale_info = localeconv();

    $per       = '';
    $error_txt = _($error_txt);
    if ($b > 0) {
        if ($plus_sing and $a > 0) {
            $sing = '+';
        } else {
            $sing = '';
        }
        $per = $sing.number_format(
                (100 * ($a / $b)), $fixed, $locale_info['decimal_point'], $locale_info['thousands_sep']
            ).$psign;
    } else {
        $per = $error_txt;
    }

    return $per;
}


function ratio($a, $b) {

    if ($b == 0) {
        return 1;
    }

    return $a / $b;

}



function ParseFloat($floatString) {
    $LocaleInfo  = localeconv();
    $floatString = str_replace(
        $LocaleInfo["mon_thousands_sep"], "", $floatString
    );
    $floatString = str_replace(
        $LocaleInfo["mon_decimal_point"], ".", $floatString
    );

    return floatval($floatString);
}


function money_cents($amount) {
    $amount = sprintf("%02d", 100 * ($amount - floor($amount)));

    return $amount;
}


function endmonth($m, $y) {
    return idate('d', mktime(0, 0, 0, ($m + 1), 0, $y));

}



function _trim($string) {
    $string = trim($string);

    return $string;
}




function capitalize($str, $encoding = 'UTF-8') {
    $str = trim($str);

    return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).mb_strtolower(mb_substr($str, 1, mb_strlen($str), $encoding), $encoding);

}


function prepare_mysql($string, $null_if_empty = true) {



    if (is_numeric($string)) {
        return "'".$string."'";
    } elseif ($string == '' and $null_if_empty) {
        return 'NULL';
    } else {
        return "'".addslashes($string)."'";


    }
}



function average($array) {
    $sum   = array_sum($array);
    $count = count($array);
    if ($count == 0) {
        return false;
    }

    return $sum / $count;
}


//The average function can be use independently but the deviation function uses the average function.

function deviation($array) {

    $avg = average($array);
    if (!$avg) {
        return false;
    }

    foreach ($array as $value) {
        $variance[] = pow($value - $avg, 2);
    }
    $deviation = sqrt(average($variance));

    return $deviation;
}





function parse_number($value) {
    if (is_numeric($value)) {
        return $value;
    }

    $value = preg_replace('/[^\.^\,\d]/', '', $value);
    if (preg_match('/\.\d?$/', $value)) {
        $value = preg_replace('/\,/', '', $value);

    } elseif (preg_match('/\..*\,\d?$/', $value)) {
        $value = preg_replace('/\./', '', $value);
        $value = preg_replace('/,/', ',', $value);
    }

    return (float)$value;


}




function number2alpha($number) {
    $alpha = chr(65 + fmod($number - 1, 26));
    $pos   = floor(($number - 1) / 26);

    $prefix = '';
    if ($pos > 0) {
        $prefix = number2alpha($pos);
    }

    return $prefix.$alpha;
}








function number($number, $fixed = 1, $force_fix = false, $locale = false) {

    if (!$locale) {
        global $locale;
    }

    if ($number == '') {
        $number = 0;
    }


    //$floored=floor($number);
    //if ($floored==$number and !$force_fix)
    //  $fixed=0;
    //$number=number_format($number, $fixed, $locale_info['decimal_point'], $locale_info['thousands_sep']);

    $_number = new NumberFormatter($locale, NumberFormatter::DECIMAL);

    $_number->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $fixed);

    if ($force_fix) {
        $_number->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, $fixed);
    }

    return $_number->format($number);


}


function get_ordinal_suffix($n, $locale = false) {

    if (!$locale) {
        global $locale;
    }

    $nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);

    return $nf->format($n);

}

function base64_url_encode($input) {
    return strtr(base64_encode($input), '+/=', '-_.');
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_.', '+/='));
}


?>
