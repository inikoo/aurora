<?php

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
						$string.=sprintf("%d %s", $value, ngettext("week", "weeks", $value));
						break;
					case 'days':
						$string.=' '.sprintf("%d %s", $value, ngettext("day", "days", $value));
						break;
					case 'hours':
						$string.=' '.sprintf("%d %s", $value, ngettext("h", "hrs", $value));
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



?>
