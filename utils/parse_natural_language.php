<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 24 February 2016 at 10:21:04 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


include_once 'utils/units_functions.php';

function parse_dimensions($dimension) {




	$dimension=trim($dimension);
	//print $dimension."<\n";
	$units='cm';
	if (preg_match('/\((cm|mm|m\yd"in|ft)\)$/', $dimension, $match)) {
		//print_r($match);
		$units=$match[1];
	}
	$dimension=preg_replace('/\s*\((cm|mm|m)\)$/', '', $dimension);


	//print $dimension."<\n";

	$dimensions=preg_split('/x/', $dimension);

	//print_r($dimensions);

	if (count($dimensions)==3) {
		$l=convert_units(floatval($dimensions[0]), $units, 'm');
		$w=convert_units(floatval($dimensions[1]), $units, 'm');
		$h=convert_units(floatval($dimensions[2]), $units, 'm');
		$vol=convert_units($l*$w*$h, 'm3', 'l');
		$type='Rectangular';
	}elseif (count($dimensions)==1) {
		if (preg_match('/^L:(.+)\s*(d|dia|&#8709;|∅):(.+)/', $dimension, $match)) {
			//print_r($match);
			$l=convert_units(floatval($match[3]), $units, 'm');
			$w=$l;
			$h=convert_units(floatval($match[1]), $units, 'm');
			$vol=convert_units($l*$w*$h, 'm3', 'l');
			$type='Cilinder';

		}elseif (preg_match('/^(d|dia|&#8709;|∅):(.+)/', $dimension, $match)) {
			//print_r($match);
			$l=convert_units(floatval($match[2]), $units, 'm');
			$w=$l;
			$h=$l;
			$vol=convert_units($l*$w*$h, 'm3', 'l');
			$type='Sphere';
			//print json_encode(array('l'=>$l, 'w'=>$w, 'h'=>$h, 'units'=>$units, 'vol'=>$vol, 'type'=>$type));
             //   exit;
		}else {
			//exit("shit");
			return '';
		}

	}else {
		//exit("shit");
		return '';
	}


	return json_encode(array('l'=>$l, 'w'=>$w, 'h'=>$h, 'units'=>$units, 'vol'=>$vol, 'type'=>$type));


}


?>
