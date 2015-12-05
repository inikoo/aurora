<?php

function seconds_to_string($secs, $suffix=false, $short=false) {
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

	if ($short) {
		foreach ($units as $key=>$value) {
			if ($value) {
				switch ($key) {
				case 'weeks':
					$string.=' '.sprintf(_('%s w'), $value);
					break 2;
				case 'days':
					$string.=' '.sprintf(_('%s d'), $value);
					break 2;
				case 'hours':
					$string.=' '.sprintf("%d %s", $value, ngettext("h", "hrs", $value));
					break 2;
				case 'minutes':
					$string.=' '.' '.sprintf(_('%smin'), $value);
					break 2;
				case 'seconds':
					$string.=' '.sprintf("%d %s", $value, ngettext("sec", "secs", $value));
					break 2;
				}
			}
		}
	}else {

		foreach ($units as $key=>$value) {
			if ($value) {
				switch ($key) {
				case 'weeks':
					$string.=' '.sprintf("%d %s", $value, ngettext("week", "weeks", $value));
					break 2;
				case 'days':
					$string.=' '.sprintf("%d %s", $value, ngettext("day", "days", $value));
					break 2;
				case 'hours':
					$string.=' '.sprintf("%d %s", $value, ngettext("hour", "hours", $value));
					break 2;
				case 'minutes':
					$string.=' '.sprintf("%d %s", $value, ngettext("minute", "minutes", $value));
					break 2;
				case 'seconds':
					$string.=' '.sprintf("%d %s", $value, ngettext("second", "seconds", $value));
					break 2;
				}
			}
		}
	}

	$string=trim($string);
	if ($suffix) {
		$string.=' '._('ago');
	}
	return $string;

}


 function file_size($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 1) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 1) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024,0) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}



?>
