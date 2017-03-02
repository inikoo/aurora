<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 February 2016 at 10:21:04 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


include_once 'utils/units_functions.php';

function parse_dimensions($dimension) {


    $dimension = trim($dimension);
    //print "\n".$dimension."<\n";


    $units = 'cm';
    if (preg_match('/\((cm|mm|m|yd|in|ft)\)$/i', $dimension, $match)) {
        //print_r($match);
        $units = strtolower($match[1]);
    }
    $dimension = preg_replace('/\((cm|mm|m|yd|in|ft)\)$/i', '', $dimension);


    //print $dimension."<\n";

    $dimensions = preg_split('/x/', $dimension);
    //print_r($dimensions);
    if (count($dimensions) == 3) {
        $l    = convert_units(floatval($dimensions[0]), $units, 'm');
        $w    = convert_units(floatval($dimensions[1]), $units, 'm');
        $h    = convert_units(floatval($dimensions[2]), $units, 'm');
        $vol  = convert_units($l * $w * $h, 'm3', 'l');
        $box_vol=$vol;
        $type = 'Rectangular';
    } elseif (count($dimensions) == 2) {
        $l    = convert_units(floatval($dimensions[0]), $units, 'm');
        $w    = convert_units(floatval($dimensions[1]), $units, 'm');
        $h    = 0;
        $vol  = 0;
        $box_vol=$vol;
        $type = 'Sheet';
    } elseif (count($dimensions) == 1) {

        // print_r($dimensions);
        if (preg_match(
            '/^L:(.+)\s*(d|dia|&#8709;|∅):(.+)/i', $dimension, $match
        )) {
            //print_r($match);
            $l    = convert_units(floatval($match[3]), $units, 'm');
            $w    = $l;
            $h    = convert_units(floatval($match[1]), $units, 'm');
            $r=$l/2;

            $vol  = convert_units(3.141592 * $r  * $r * $h, 'm3', 'l');
            $box_vol=convert_units($l * $w * $h, 'm3', 'l');
            $type = 'Cilinder';

        } elseif (preg_match(
            '/^(d|dia|&#8709;|∅):(.+)/i', $dimension, $match
        )) {
            //print_r($match);
            $l    = convert_units(floatval($match[2]), $units, 'm');
            $w    = $l;
            $h    = $l;

            $r=$l/2;

            $vol  = convert_units(3.141592*4/3*$r * $r * $r, 'm3', 'l');
            $box_vol=convert_units($l * $w * $h, 'm3', 'l');
            $type = 'Sphere';
            //print json_encode(array('l'=>$l, 'w'=>$w, 'h'=>$h, 'units'=>$units, 'vol'=>$vol, 'type'=>$type));
            //   exit;
        } elseif (preg_match('/^L:(.+)/i', $dimension, $match)) {
            //print_r($match);
            $l    = convert_units(floatval($match[1]), $units, 'm');
            $w    = 0;
            $h    = 0;
            $vol  = 0;
            $box_vol=0;
            $type = 'String';
            //print json_encode(array('l'=>$l, 'w'=>$w, 'h'=>$h, 'units'=>$units, 'vol'=>$vol, 'type'=>$type));
            //   exit;
        } else {
            //exit("shit");
            return '';
        }

    } else {
        //exit("shit");
        return '';
    }


    return json_encode(
        array(
            'l'     => $l,
            'w'     => $w,
            'h'     => $h,
            'units' => $units,
            'vol'   => $vol,
            'box_vol'   => $box_vol,
            'type'  => $type
        )
    );


}


?>
