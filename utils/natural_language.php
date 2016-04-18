<?php


function days_to_string($days, $short=false) {
	$days=round($days);
	if ($days<1) {

		if ($short) {
			return sprintf(_('%sd'), 1);
		}else {
			return sprintf("%d %s", 1, ngettext("day", "days", 1));

		}

	}if ($days<100) {

		if ($short) {
			return sprintf(_('%sd'), $days);
		}else {
			return sprintf("%d %s", $days , ngettext("day", "days", $days));

		}

	}


}


function seconds_to_string($seconds, $until='seconds', $short=false) {
	$units = array(
		"weeks"   => 604800,
		"days"    => 86400,
		"hours"   => 3600,
		"minutes" => 60,
		"seconds" =>  1,
	);

	$start=false;
	$end=false;
	$string='';
	foreach ( $units as $key=>$unit ) {
		$quot  = intval($seconds / $unit);
		$seconds -= $quot * $unit;


		if ($quot) {
			$start=true;
		}



		if ($start and !$end) {
			if ($quot) {

				if ($short) {
					switch ($key) {
					case 'weeks':
						$string.=sprintf(_('%sw'), $quot);
						break;
					case 'days':
						$string.=' '.sprintf(_('%sd'), $quot);
						break;
					case 'hours':
						$string.=' '.sprintf(_('%sh'), $quot);
						break;
					case 'minutes':
						$string.=' '.sprintf(_('%sm'), $quot);
						break;
					case 'seconds':
						$string.=' '.sprintf(_('%ss'), $quot);
						break;
					}
				}else {
					switch ($key) {
					case 'weeks':
						$string.=sprintf("%d %s", $quot, ngettext("week", "weeks", $quot));
						break;
					case 'days':
						$string.=' '.sprintf("%d %s", $quot, ngettext("day", "days", $quot));
						break;
					case 'hours':
						$string.=' '.sprintf("%d %s", $quot, ngettext("h", "hrs", $quot));
						break;
					case 'minutes':
						$string.=' '.sprintf(_('%sm'), $quot);
						break;
					case 'seconds':
						$string.=' '.sprintf(_('%ss'), $quot);
						break;
					}
				}
			}

		}

		if ($until==$key)$end=true;

	}


	return $string;



}



function seconds_to_hourminutes($seconds) {
	$units = array(

		"hours"   => 3600,
		"minutes" => 60,

	);

	$start=false;
	$end=false;
	$string='';
	foreach ( $units as $key=>$unit ) {
		$quot  = intval($seconds / $unit);
		$seconds -= $quot * $unit;

		switch ($key) {

		case 'hours':
			$string.=' '.sprintf(_('%s:'), $quot);
			break;
		case 'minutes':
			$string.=sprintf(_('%02d'), $quot);
			break;

		}







		//if ($until==$key)$end=true;

	}


	return $string;



}




function file_size($bytes) {
	if ($bytes >= 1073741824) {
		$bytes = number_format($bytes / 1073741824, 1) . ' GB';
	}
	elseif ($bytes >= 1048576) {
		$bytes = number_format($bytes / 1048576, 1) . ' MB';
	}
	elseif ($bytes >= 1024) {
		$bytes = number_format($bytes / 1024, 0) . ' KB';
	}
	elseif ($bytes > 1) {
		$bytes = $bytes . ' bytes';
	}
	elseif ($bytes == 1) {
		$bytes = $bytes . ' byte';
	}
	else {
		$bytes = '0 bytes';
	}

	return $bytes;
}


function get_file_as($code) {
	$ncode=$code;
	$c=preg_split('/\-/', $code);
	if (count($c)==2) {
		if (is_numeric($c[1]))
			$ncode=sprintf("%s-%05d", strtolower($c[0]), $c[1]);
		else {
			if (preg_match('/^[^\d]+\d+$/', $c[1])) {
				if (preg_match('/\d*$/', $c[1], $match_num) and preg_match('/^[^\d]*/', $c[1], $match_alpha)) {
					$ncode=sprintf("%s-%s%05d", strtolower($c[0]), strtolower($match_alpha[0]), $match_num[0]);
					return $ncode;
				}
			}
			if (preg_match('/^\d+[^\d]+$/', $c[1])) {
				if (preg_match('/^\d*/', $c[1], $match_num) and preg_match('/[^\d]*$/', $c[1], $match_alpha)) {
					$ncode=sprintf("%s-%05d%s", strtolower($c[0]), $match_num[0], strtolower($match_alpha[0]));
					return $ncode;
				}
			}


			$ncode=sprintf("%s-%s", strtolower($c[0]), strtolower($c[1]));
		}

	}
	if (count($c)==3) {
		if (is_numeric($c[1]) and is_numeric($c[2])) {
			$ncode=sprintf("%s-%05d-%05d", strtolower($c[0]), $c[1], $c[2]);
			return $ncode;
		}
		if (!is_numeric($c[1]) and is_numeric($c[2])) {
			$ncode=sprintf("%s-%s-%05d", strtolower($c[0]), strtolower($c[1]), $c[2]);
			return $ncode;
		}
		if (is_numeric($c[1]) and !is_numeric($c[2])) {
			$ncode=sprintf("%s-%05d-%s", strtolower($c[0]), $c[1], strtolower($c[2]));
			return $ncode;
		}



	}


	return $ncode;
}


function weight($w, $unit='Kg', $number_decimals=3, $simplify=false, $zero_fill=false) {
	//print $w;
	if ($w=='') return '';
	if ($simplify) {
		if ($w==0) {
			return '0'.$unit;
		}

		$w=round($w);

		if ($w==0) {
			return '~1'.$unit;
		}elseif ($w>1000) {
			$w=number($w, 0);
		}
		return $w.$unit;
	}else {
		if ($zero_fill) {
			return number($w, $number_decimals, true).$unit;

		}else {
			return number($w, $number_decimals).$unit;
		}
	}
}


function volume($value, $unit='L') {
	if ($value=='') return '';
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

	$sql=sprintf("select `Currency Code`,`Currency Name`,`Currency Symbol`,`Currency Flag` from kbase.`Currency Dimension` where `Currency Code`=%s",
		prepare_mysql($currency)
	);

	if ($result=$db->query($sql)) {
		if ($row = $result->fetch()) {
			return sprintf('<span title="%s">%s (%s)</span>',
				$row['Currency Code'],
				$row['Currency Name'],
				$row['Currency Symbol']

			);
		}else {
			return $currency;
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

}


?>
