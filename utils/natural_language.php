<?php

function seconds_to_natural_string($seconds, $short = false) {



    if ($seconds == 0) {
        return '';
    }

    if ($seconds < 120) {

        if ($short) {
            return sprintf(_('%ss'), floor($seconds));
        } else {
            return sprintf("%d %s", $seconds, ngettext("second", "seconds", $seconds));

        }

    }elseif ($seconds < 5400) {

        if ($short) {

            return sprintf(_('%sm'), floor($seconds/60));
        } else {
            return sprintf("%d %s", $seconds/60, ngettext("minute", "minutes", $seconds/60));

        }

    }elseif ($seconds < 86400) {

        if ($short) {
            return sprintf(_('%sh'), floor($seconds/3600));
        } else {
            return sprintf("%d %s", $seconds/3600, ngettext("hour", "hours", $seconds/3600));

        }

    }







    $days = round($seconds / 86400);

    if ($days < 1) {

        if ($short) {
            return sprintf(_('%sd'), 1);
        } else {
            return sprintf("%d %s", 1, ngettext("day", "days", 1));

        }

    } elseif ($days < 100) {

        if ($short) {
            return sprintf(_('%sd'), $days);
        } else {


            return sprintf(
                "%d %s", $days, ngettext("day", "days", intval($days))
            );

        }

    } elseif ($days < 700) {
        $weeks = floor($days / 7);

        if ($short) {
            return sprintf(_('%sw'), $weeks);
        } else {
            return sprintf(
                "%d %s", $weeks, ngettext("week", "weeks", intval($weeks))
            );

        }

    } elseif ($days < 1095) {
        $months = floor($days / 30.4167);
        if ($short) {
            return sprintf(_('%sm'), $months);
        } else {
            return sprintf(
                "%d %s", $months, ngettext("month", "months", intval($months))
            );

        }

    } elseif ($days < 1825) {
        $years = floor($days / 365);

        if ($short) {
            return sprintf(_('%sy'), $years);
        } else {
            return sprintf(
                "%d %s", $years, ngettext("year", "years", intval($years))
            );

        }

    } else {

        return _('years');
    }


}

function seconds_to_string($seconds, $until = 'seconds', $short = false) {


    $units = array(
        "weeks"   => 604800,
        "days"    => 86400,
        "hours"   => 3600,
        "minutes" => 60,
        "seconds" => 1,
    );

    $start  = false;
    $end    = false;
    $string = '';
    foreach ($units as $key => $unit) {
        $quot = intval($seconds / $unit);
        $seconds -= $quot * $unit;


        if ($quot) {
            $start = true;
        }


        if ($start and !$end) {
            if ($quot) {

                if ($short) {
                    switch ($key) {
                        case 'weeks':
                            $string .= sprintf(_('%sw'), $quot);
                            break;
                        case 'days':
                            $string .= ' '.sprintf(_('%sd'), $quot);
                            break;
                        case 'hours':
                            $string .= ' '.sprintf(_('%sh'), $quot);
                            break;
                        case 'minutes':
                            $string .= ' '.sprintf(_('%sm'), $quot);
                            break;
                        case 'seconds':
                            $string .= ' '.sprintf(_('%ss'), $quot);
                            break;
                    }
                } else {
                    switch ($key) {
                        case 'weeks':
                            $string .= sprintf(
                                "%d %s", $quot, ngettext("week", "weeks", $quot)
                            );
                            break;
                        case 'days':
                            $string .= ' '.sprintf(
                                    "%d %s", $quot, ngettext("day", "days", $quot)
                                );
                            break;
                        case 'hours':
                            $string .= ' '.sprintf(
                                    "%d %s", $quot, ngettext("h", "hrs", $quot)
                                );
                            break;
                        case 'minutes':
                            $string .= ' '.sprintf(_('%sm'), $quot);
                            break;
                        case 'seconds':
                            $string .= ' '.sprintf(_('%ss'), $quot);
                            break;
                    }
                }
            }

        }

        if ($until == $key) {
            $end = true;
        }

    }


    return $string;


}

function seconds_to_hourminutes($seconds) {
    $units = array(

        "hours"   => 3600,
        "minutes" => 60,

    );

    $start  = false;
    $end    = false;
    $string = '';
    foreach ($units as $key => $unit) {
        $quot = intval($seconds / $unit);
        $seconds -= $quot * $unit;

        switch ($key) {

            case 'hours':
                $string .= ' '.sprintf(_('%s:'), $quot);
                break;
            case 'minutes':
                $string .= sprintf(_('%02d'), $quot);
                break;

        }


        //if ($until==$key)$end=true;

    }


    return $string;


}

function file_size($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 1).' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 1).' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 0).' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes.' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes.' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function get_file_as($StartCode) {

    $PaddingAmount = 4;
    $s             = preg_replace("/[^0-9]/", "-", $StartCode);

    for ($qq = 0; $qq < 10; $qq++) {
        $s = preg_replace("/--/", "-", $s);
    }


    $pieces = explode("-", $s);

    for ($qq = 0; $qq < count($pieces); $qq++) {
        $ss = str_pad($pieces[$qq], $PaddingAmount, '0', STR_PAD_LEFT);
        if (strlen($pieces[$qq]) > 0) {
            $StartCode      = preg_replace(
                '/'.$pieces[$qq].'/', ';xyz;', $StartCode, 1
            );
            $arr_parts[$qq] = $ss;
        }

    }


    for ($qq = 0; $qq < count($pieces); $qq++) {

        if (strlen($pieces[$qq]) > 0) {
            $ss        = $arr_parts[$qq];
            $StartCode = preg_replace('/;xyz;/', $ss, $StartCode, 1);
        }


    }


    return $StartCode;


}


function smart_weight($weight,$decimals=3){
    if ($weight < 1) {
        return weight($weight *1000, 'g');
    }elseif ($weight > 1000) {
        return weight($weight /1000, 't',$decimals);
    } else {
        return weight($weight,'Kg',$decimals);
    }
}

/**
 * @param        $w
 * @param string $unit
 * @param int    $number_decimals
 * @param bool   $simplify
 * @param bool   $zero_fill
 *
 * @return string
 */
function weight($w, $unit = 'Kg', $number_decimals = 3, $simplify = false, $zero_fill = false) {

    if ($w == '') {
        return '';
    }
    if ($simplify) {
        if ($w == 0) {
            return '0'.$unit;
        }

        $w = round($w);

        if ($w == 0) {
            return '~1'.$unit;
        } elseif ($w > 1000) {
            $w = number($w, 0);
        }

        return $w.$unit;
    } else {
        if ($zero_fill) {
            return number($w, $number_decimals, true).$unit;

        } else {
            return number($w, $number_decimals).$unit;
        }
    }
}

function volume($value, $unit = 'L') {
    if ($value == '') {
        return '';
    }

    return number($value, 3).'L';
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

function currency_label($currency, $db) {

    $sql = sprintf(
        "SELECT `Currency Code`,`Currency Name`,`Currency Symbol`,`Currency Flag` FROM kbase.`Currency Dimension` WHERE `Currency Code`=%s", prepare_mysql($currency)
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            return sprintf(
                '<span title="%s">%s (%s)</span>', $row['Currency Code'], $row['Currency Name'], $row['Currency Symbol']

            );
        } else {
            return $currency;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

}


function money($amount, $currency = '', $locale = false, $option = '') {
    if (!$locale) {
        global $locale;
    }

    $money = new NumberFormatter($locale, NumberFormatter::CURRENCY);




    if ($option == 'NO_FRACTION_DIGITS') {
        $money->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);
    } elseif ($option == 'SINGLE_FRACTION_DIGITS') {
        $money->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 1);
    }elseif ($option == 'FOUR_FRACTION_DIGITS') {


        $money->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 4);
    }


    $formatted_money=$money->formatCurrency($amount, $currency);

    // todo, remove when NumberFormatter support this symbols

    $formatted_money=preg_replace('/\s?PLN\s?/','zł',$formatted_money);
    $formatted_money=preg_replace('/\s?CZK\s?/','Kč ',$formatted_money);
    $formatted_money=preg_replace('/\s?IDR\s?/','Rp',$formatted_money);

    return $formatted_money;
}

function seconds_to_until($seconds, $short = false) {


    if ($seconds == 0) {
        return 0;
    }

    $days = round($seconds / 86400);

    if ($days < 1) {

        if ($short) {
            return sprintf(_('%sd'), 1);
        } else {
            return sprintf("%d %s", 1, ngettext("day", "days", 1));

        }

    } elseif ($days < 100) {

        if ($short) {
            return sprintf(_('%sd'), $days);
        } else {


            return sprintf(
                "%d %s", $days, ngettext("day", "days", intval($days))
            );

        }

    } elseif ($days < 700) {
        $weeks = floor($days / 7);

        if ($short) {
            return sprintf(_('%sw'), $weeks);
        } else {
            return sprintf(
                "%d %s", $weeks, ngettext("week", "weeks", intval($weeks))
            );

        }

    } elseif ($days < 1095) {
        $months = floor($days / 30.4167);
        if ($short) {
            return sprintf(_('%sm'), $months);
        } else {
            return sprintf(
                "%d %s", $months, ngettext("month", "months", intval($months))
            );

        }

    } elseif ($days < 1825) {
        $years = floor($days / 365);

        if ($short) {
            return sprintf(_('%sy'), $years);
        } else {
            return sprintf(
                "%d %s", $years, ngettext("year", "years", intval($years))
            );

        }

    } else {

        return _('+5 years');
    }


}


function get_interval_label($interval) {

    switch ($interval) {


        case 'Total':
        case 'all':
            $db_interval = _('All time');

            break;

        case 'Last Month':
        case 'last_m':
            $db_interval = _('Last month');

            break;

        case 'Last Week':
        case 'last_w':
            $db_interval = _('Last week');


            break;

        case 'Yesterday':
        case 'yesterday':
            $db_interval = _('Yesterday');

            break;

        case 'Week To Day':
        case 'wtd':
        case 'weektoday':
            $db_interval = _('Week-to-date');

            break;
        case 'Today':
        case 'today':
            $db_interval = _('Today');

            break;


        case 'Month To Day':
        case 'mtd':
        case 'monthtoday':

            $db_interval = _('Month-to-date');

            break;
        case 'Year To Day':
        case 'ytd':
        case 'yeartoday';
            $db_interval = _('Year-to-day');

            break;
        case '3 Year':
        case '3y':
        case 'three_year':
            $db_interval = _('3 Year');

            break;
        case '1 Year':
        case '1y':
        case 'year':
            $db_interval = _('1 Year');

            break;
        case '6 Month':
        case '6m':
        case 'six_month':
            $db_interval = _('6 Months');

            break;
        case '1 Quarter':
        case '1q':
        case 'quarter':
            $db_interval = _('1 Quarter');

            break;
        case '1 Month':
        case '1m':
        case 'month':
            $db_interval = _('1 Month');

            break;
        case '10 Day':
        case '10d':
        case 'ten_day':
            $db_interval = _('10 Days');

            break;
        case '1 Week':
        case '1w':
        case 'week':
            $db_interval = _('1 Week');

            break;

        case '1 Day':
        case '1d':
        case 'day':
            $db_interval = _('1 Day');

            break;
        case 'hour':
        case '1 Hour':
        case '1h':
            $db_interval = _('1 Hour');

            break;

        default:
            return;
            break;
    }

    return $db_interval;
}


function translate_written_number($string) {

    $numbers         = array(
        'zero',
        'one',
        'two',
        'three',
        'four',
        'five',
        'six',
        'seven',
        'eight',
        'nine',
        'ten',
        'eleven'
    );
    $common_suffixes = array(
        'hundreds?'  => 100,
        'thousands?' => 1000,
        'millons?'   => 100000
    );

    $number_flat          = join("|", $numbers);
    $common_suffixes_flat = join("|", $common_suffixes);
    if (preg_match("/$number_flat/i", $string)) {
        if (preg_match("/$common_suffixes_flat/i", $string)) {
            foreach ($numbers as $number => $number_string) {
                foreach (
                    $common_suffixes as $common_suffix => $number_common_suffix
                ) {
                    $string = _trim(
                        preg_replace(
                            '/^(.*\s+|)$number_string\s?$common_suffix(\s+.*|)$/ ', " ".($number * $number_common_suffix)." ", $string
                        )
                    );
                }
            }
        } else {
            foreach ($numbers as $number => $number_string) {
                $string = _trim(
                    preg_replace(
                        '/^(.*\s+|)$number_string(\s+.*|)$/ ', " $number ", $string
                    )
                );
            }
        }
    }

    return $string;
}

function substrwords($text, $maxchar, $end='&hellip;') {
    if (strlen($text) > $maxchar || $text == '') {
        $words = preg_split('/\s/', $text);
        $output = '';
        $i      = 0;
        while (1) {
            $length = strlen($output)+strlen($words[$i]);
            if ($length > $maxchar) {
                break;
            }
            else {
                $output .= " " . $words[$i];
                ++$i;
            }
        }
        $output .= $end;
    }
    else {
        $output = $text;
    }
    return $output;
}



