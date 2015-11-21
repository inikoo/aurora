{literal}
<code>
<?php
date_default_timezone_set('UTC');

include_once 'read_timesheets.conf.php';
/*
Conf file has the following variables
$timesheets_directory 
$api_key 
$service_url 
*/


$files = scandir($timesheets_directory);

foreach ($files as $file) {
	if (preg_match('/\.csv/i', $file)) {
		$csv = array_map('str_getcsv', file($timesheets_directory.'/'.$file));

		$ok=false;
		$error=false;

		if (is_array($csv)) {
			foreach ($csv as $row) {
				if (is_array($row) and count($row)>=2) {

					$datetime=parse_datetime($row[2], 'biotime');
					$staff_payroll_id=parse_staff_payroll_id($row[1], 'biotime');
					if ($datetime and $staff_payroll_id) {
						$result=process_timesheet_reading($api_key.$service_url, $staff_payroll_id, $datetime);
						if ($result['state']=='Error') {
							$error=true;
						}else {
							$ok=true;
						}
					}
				}
			}
		}

		if ($error) {
			rename($timesheets_directory.'/'.$file, $timesheets_directory.'/error/'.$file);
		}elseif ($ok) {
			rename($timesheets_directory.'/'.$file, $timesheets_directory.'/done/'.$file);
		}

	}

}


function parse_staff_payroll_id($date, $source) {

	switch ($source) {
	case 'biotime':
		return parse_staff_payroll_id_biotime($date);
		break;
	default:
		return false;
		break;
	}


}

function parse_datetime($date, $source) {

	switch ($source) {
	case 'biotime':
		return parse_date_biotime($date);
		break;
	default:
		return false;
		break;
	}


}

function parse_staff_payroll_id_biotime($id) {

	if (is_numeric($id)) {
		if ($id>=1000) {
			return 'T'.$id;
		}else {
			return 'AW'.sprintf('%02d', $id);
		}
	}else {
		return false;
	}

}

function parse_date_biotime($date) {

	$datetime_components=preg_split('/\s/', $date);
	if (count($datetime_components)==2) {

		$date_components=preg_split('/\//', $datetime_components[0]);

		$date=$date_components[2].'-'.$date_components[1].'-'.$date_components[0];
		$time=$datetime_components[1];

		return $date.' '.$time;

	}
	return false;
}

function process_timesheet_reading($api_key,$service_url, $staff_payroll_id, $date) {

	
	$curl = curl_init($service_url);
	$curl_post_data = array(
		'Staff Official ID' => $staff_payroll_id,
		'Date' => $date,
		'Source'=>'ClockingMachine'

	);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-Auth-Key: '.$api_key));


	$curl_response = curl_exec($curl);


	if ($curl_response === false) {
		$info = curl_getinfo($curl);
		curl_close($curl);
		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);


	$result = json_decode($curl_response, true);

	return $result;



}


?>

</code>
</literal>