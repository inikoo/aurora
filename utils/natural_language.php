<?php

function seconds_to_string($secs,$suffix=false) {
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
	foreach ($units as $key=>$value) {
		if ($value) {
			switch ($key) {
			case 'weeks':
				$string.=' '.sprintf("%d %s",$value,ngettext("week", "weeks", $value));
				break 2;
			case 'days':
				$string.=' '.sprintf("%d %s",$value,ngettext("day", "days", $value));
				break 2;
			case 'hours':
				$string.=' '.sprintf("%d %s",$value,ngettext("hour", "hours", $value));
				break 2;
			case 'minutes':
				$string.=' '.sprintf("%d %s",$value,ngettext("minute", "minutes", $value));
				break 2;
			case 'seconds':
				$string.=' '.sprintf("%d %s",$value,ngettext("second", "seconds", $value));
				break 2;
			}
		}
	}
	$string=trim($string);
	if ($suffix) {
		$string.=' '._('ago');
	}
	return $string;

}

?>