<?php

function convert_units($value,$from,$to) {

	if ($from==$to)
		return $value;
	switch ($from) {
	case 'Kg':
		switch ($to) {
		case 'g':
			return $value*1000;
		case 'lb':
			return $value*2.20462;
		case 'oz':
			return $value*35.274;
		default:
			return $value;
		}
		break;
	case 'g':
		switch ($to) {
		case 'Kg':
			return $value*.001;
		case 'lb':
			return $value*0.00220462;
		case 'oz':
			return $value*0.035274;
		default:
			return $value;
		}
		break;
	case 'lb':
		switch ($to) {
		case 'Kg':
			return $value*0.453592;
		case 'g':
			return $value*453.592;
		case 'oz':
			return $value*16;
		default:
			return $value;
		}
		break;
	case 'oz':
		switch ($to) {
		case 'Kg':
			return $value*0.0283495;
		case 'g':
			return $value*28.3495;
		case 'lb':
			return $value*0.0625;
		default:
			return $value;
		}
		break;
	case 'm':
		switch ($to) {
		case 'mm':
			return $value*1000;
		case 'cm':
			return $value*100;
		case 'yd':
			return $value*1.09361;
		case 'in':
			return $value*39.3701;
		case 'ft':
			return $value*3.28084;
		default:
			return $value;
		}
		break;
	case 'mm':
		switch ($to) {
		case 'mm':
			return $value*0.001;
		case 'cm':
			return $value*.1;
		case 'yd':
			return $value*0.00109361;
		case 'in':
			return $value*0.0393701;
		case 'ft':
			return $value*0.00328084;
		default:
			return $value;
		}
		break;
	case 'cm':
		switch ($to) {
		case 'mm':
			return $value*10;
		case 'm':
			return $value*0.01;
		case 'yd':
			return $value*0.0109361;
		case 'in':
			return $value*0.393701;
		case 'ft':
			return $value*0.0328084;
		default:
			return $value;
		}
		break;
	case 'yd':
		switch ($to) {
		case 'mm':
			return $value*914.4;
		case 'cm':
			return $value*91.44;
		case 'm':
			return $value*0.9144;
		case 'in':
			return $value*36;
		case 'ft':
			return $value*3;
		default:
			return $value;
		}
		break;
	case 'in':
		switch ($to) {
		case 'mm':
			return $value*25.4;
		case 'cm':
			return $value*2.54;
		case 'yd':
			return $value*0.0277778;
		case 'm':
			return $value*0.0254;
		case 'ft':
			return $value*0.0833333;
		default:
			return $value;
		}
		break;
	case 'ft':
		switch ($to) {
		case 'mm':
			return $value*304.8;
		case 'cm':
			return $value*30.48;
		case 'yd':
			return $value*0.333333;
		case 'in':
			return $value*12;
		case 'm':
			return $value*0.3048;
		default:
			return $value;
		}
		break;
	default:
		return $value;
	}



}


?>
