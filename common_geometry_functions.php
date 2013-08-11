<?php

function get_volume($shape,$w='',$d='',$l='',$dia='',$result_type='force_volume') {

	$pi=3.141592;

	switch ($shape) {
	case 'Rectangular':
		if (!test_volume_field($w) or !test_volume_field($d) or !test_volume_field($l))
			$volume='';
		else
			$volume=$w*$d*$l;
		break;
	case 'Cilinder':
		if (!test_volume_field($l) or !test_volume_field($dia))
			$volume='';
		else
			$volume=$pi*($dia*0.5)*($dia*0.5)*$l;
		break;
	case 'Sphere':
		if (!test_volume_field($dia))
			$volume='';
		else
			$volume=$pi*(4/3)*($dia*0.5)*($dia*0.5)*($dia*0.5);
		break;
	case 'String':
		if ($result_type=='force_numeric') {
			$dia=0.005;
		}

		if (!test_volume_field($l) or !test_volume_field($dia))
			$volume='';
		else
			$volume=$pi*($dia*0.5)*($dia*0.5)*$l;
		break;
	case 'Sheet':
		if ($result_type=='force_numeric') {
			$d=0.005;
		}
		if (!test_volume_field($w) or !test_volume_field($d) or !test_volume_field($l))
			$volume='';
		else
			$volume=$w*$d*$l;
		break;

	default:
		$volume='';
	}

	if ($result_type=='force_numeric' and $volume=='') {
		$volume=0;
	}
	if ($result_type=='force_volume' and $volume==0) {
		$volume='';
	}

	if($volume!='')$volume=$volume*1000;

	return $volume;

}


function test_volume_field($value) {
	if (!is_numeric($value) or $value<0) {
		return false;
	}else {
		return true;
	}
}

?>
