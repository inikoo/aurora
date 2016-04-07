<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 April 2016 at 16:27:36 GMT+8, Kuala Lumpur, Maysia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/


function ean_checkdigit($code) {
	$code = str_pad($code, 12, "0", STR_PAD_LEFT);
	$sum = 0;
	for ($i=(strlen($code)-1);$i>=0;$i--) {
		$sum += (($i % 2) * 2 + 1 ) * $code[$i];
	}
	
	$check_digit=10 - ($sum % 10);
	if($check_digit==10)return 0;
	else return $check_digit;
}

 function drawCross($im, $color, $x, $y){
    imageline($im, $x - 10, $y, $x + 10, $y, $color);
    imageline($im, $x, $y- 10, $x, $y + 10, $color);
  }

?>