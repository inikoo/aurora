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

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

if (!function_exists('hash_equals')) {
    function hash_equals($str1, $str2) {
        if (strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;
            for ($i = strlen($res) - 1; $i >= 0; $i--) {
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

    if(!is_numeric($current_value)){
        $current_value=0;
    }
    if(!is_numeric($old_value)){
        $old_value=0;
    }

    if ($current_value == $old_value) {
        return '--';
    }




    return percentage(
        $current_value - $old_value, $old_value, 1, 'NA', '%', true
    );
}


function delta_raw($current_value, $old_value){
    if($old_value==0){
        if($current_value==0){
            return 0;
        }else{
            return 0;
        }
    }
    return  ($current_value - $old_value)/$old_value;

}


function delta_icon($_value, $_value_1yb, $inverse = false) {

    if ($inverse) {
        $up_arrow   = '<i title="%s" class="fa fa-fw fa-play fa-rotate-270 error" aria-hidden="true"></i>';
        $down_arrow = '<i title="%s" class="fa fa-fw fa-play fa-rotate-90 success" aria-hidden="true"></i>';
    } else {
        $up_arrow   = '<i title="%s" class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>';
        $down_arrow = '<i title="%s" class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>';
    }
    $no_change = '<i class="fa fa-fw fa-pause fa-rotate-90 super_discreet" aria-hidden="true"></i>';

    $delta_icon = sprintf($no_change);


    if ($_value != 0 and $_value_1yb != 0) {

        $delta = delta($_value, $_value_1yb);

        if ($_value > $_value_1yb) {
            $delta_icon = sprintf($up_arrow, $delta);
        } elseif ($_value < $_value_1yb) {
            $delta_icon = sprintf($down_arrow, $delta);

        }
    } elseif ($_value_1yb == 0 and $_value > 0) {
        $delta_icon = sprintf($up_arrow, '');
    } elseif ($_value_1yb == 0 and $_value < 0) {
        $delta_icon = sprintf($down_arrow, '');
    }

    return $delta_icon;
}


function percentage($a, $b, $fixed = 1, $error_txt = 'NA', $psign = '%', $plus_sing = false) {

    $locale_info = localeconv();


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


function eta($done, $total, $start_datetime) {


    if ($done >= $total or $total <= 0 or $done == 0 or $start_datetime == '') {
        return '';
    }

    $start   = gmdate('U', strtotime($start_datetime.' +0:00'));
    $now = gmdate('U');
    if ($start > $now) {
        return '';
    }

    $eta_seconds = ($total - $done) * (($now - $start) / $done);

    return _('ETA').': '.seconds_to_natural_string($eta_seconds,true);

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


function safeEncrypt($message, $key) {
    $nonce = random_bytes(
        SODIUM_CRYPTO_SECRETBOX_NONCEBYTES
    );

    $cipher = base64_url_encode(
        $nonce.sodium_crypto_secretbox(
            $message, $nonce, $key
        )
    );
    sodium_memzero($message);
    sodium_memzero($key);

    return $cipher;
}

/**
 * Decrypt a message
 *
 * @param string $encrypted - message encrypted with safeEncrypt()
 * @param string $key       - encryption key
 *
 * @return string
 */
function safeDecrypt($encrypted, $key) {
    $decoded = base64_url_decode($encrypted);
    if ($decoded === false) {
        throw new Exception('Scream bloody murder, the encoding failed');
    }
    if (mb_strlen($decoded, '8bit') < (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES)) {
        throw new Exception('Scream bloody murder, the message was truncated');
    }
    $nonce      = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
    $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

    $plain = sodium_crypto_secretbox_open(
        $ciphertext, $nonce, $key
    );
    if ($plain === false) {
        throw new Exception('the message was tampered with in transit');
    }
    sodium_memzero($ciphertext);
    sodium_memzero($key);

    return $plain;
}

function float2rat($n, $tolerance = 1.e-6) {
    if($n==0){
        return 0;
    }
    $h1=1; $h2=0;
    $k1=0; $k2=1;
    $b = 1/$n;
    do {
        $b = 1/$b;
        $a = floor($b);
        $aux = $h1; $h1 = $a*$h1+$h2; $h2 = $aux;
        $aux = $k1; $k1 = $a*$k1+$k2; $k2 = $aux;
        $b = $b-$a;
    } while (abs($n-$h1/$k1) > $n*$tolerance);

    if($k1==1){
        return $h1;
    }

    if($h1==$k1){
        return $h1;
    }

    return "$h1/$k1";
}

