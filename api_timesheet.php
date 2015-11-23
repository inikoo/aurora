<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 21 November 2015 at 14:41:00 GMT GMT Sheffield UK

 Version 2.0
*/

include_once 'class.Staff.php';
include_once 'class.Timesheet_Record.php';
include_once 'class.Timesheet.php';

function post_timesheet($db, $editor, $api_key_key) {


	if (isset($_REQUEST['Staff_Key'])) {
		$staff=new Staff($_REQUEST['Staff_Key']);
	}elseif (isset($_REQUEST['Staff Key'])) {
		$staff=new Staff($_REQUEST['Staff Key']);
	}elseif (isset($_REQUEST['Staff_Official_ID'])) {
		$staff=new Staff('staff_id', $_REQUEST['Staff_Official_ID']);
	}elseif (isset($_REQUEST['Staff Official ID'])) {
		$staff=new Staff('staff_id', $_REQUEST['Staff Official ID']);
	}elseif (isset($_REQUEST['Staff_Alias'])) {
		$staff=new Staff('alias', $_REQUEST['Staff_Alias']);
	}elseif (isset($_REQUEST['Staff Alias'])) {
		$staff=new Staff('alias', $_REQUEST['Staff Alias']);
	}else {
		$response= log_api_key_access_failture($db, $api_key_key, 'Fail_Operation', "Invalid staff id field");
		echo json_encode($response);
		exit;
	}

	if (!$staff->id) {
		$response= log_api_key_access_failture($db, $api_key_key, 'Fail_Operation', "Staff not found");
		echo json_encode($response);
		exit;

	}
	$source='API';
	
	if (isset($_REQUEST['Source'])) {
		if (in_array($_REQUEST['Source'], array('ClockingMachine'))) {
			$source=$_REQUEST['Source'];
		}

	}
	$data=array(
		'Timesheet Record Date'=>$_REQUEST['Date'],
		'Timesheet Record Source'=>$source,
		'editor'=>$editor
	);

	$staff->create_timesheet_record($data);
	$staff->editor=$editor;
	if ($staff->create_timesheet_record_error) {

		if ($staff->create_timesheet_record_duplicated) {
			$response= log_api_key_access_failture($db, $api_key_key, 'Fail_Operation', "Record already exists");

		}else {
			$response= log_api_key_access_failture($db, $api_key_key, 'Fail_Operation', "Error creating record");

		}

		echo json_encode($response);
		exit;
	}else {
		$response= log_api_key_access_success($db, $api_key_key , 'Record created');
		echo json_encode($response);
		exit;

	}


}



?>
