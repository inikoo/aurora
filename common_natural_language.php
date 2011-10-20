<?php

function seconds_to_string($secs) {
    $units = array(
                 "weeks"   => 7*24*3600,
                 "days"    =>   24*3600,
                 "hours"   =>      3600,
                 "minutes" =>        60,
                 "seconds" =>         1,
             );

    foreach ( $units as &$unit ) {
        $quot  = intval($secs / $unit);
        $secs -= $quot * $unit;
        $unit  = $quot;
    }

    $string='';
    //$filled_units=array();
    foreach($units as $key=>$value) {
        if ($value) {
            //$filled_units[$key]=$value;
            switch ($key) {
            case 'weeks':
                $string.=sprintf(ngettext("%d week", "%d weeks", $value), $value);
                break;
            case 'days':
                $string.=' '.sprintf(ngettext("%d day", "%d days", $value), $value);
                break;
            case 'hours':
                $string.=' '.sprintf(ngettext("%d hour", "%d hours", $value), $value);
                break;
            case 'minutes':
                $string.=' '.sprintf(ngettext("%d minute", "%d minutes", $value), $value);
                break;
            case 'seconds':
                $string.=' '.sprintf(ngettext("%d second", "%d seconds", $value), $value);
                break;
            }
        }
    }


    $string=_trim($string);
    return $string;

}



?>

