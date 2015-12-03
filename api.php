<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 21 November 2015 at 14:41:00 GMT GMT Sheffield UK

 Version 2.0
*/

error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once 'utils/general_functions.php';

$db=get_db();

list($user_key, $api_key_key, $scope)= authenticate($db);
authorization($db, $user_key, $api_key_key, $scope);



function authorization($db, $user_key, $api_key_key, $scope) {
	$method=$_SERVER['REQUEST_METHOD'];
	$parsed_scope=parse_scope($_SERVER['PATH_INFO']);

	if (!$scope) {
		$response= log_api_key_access_failture($db, $api_key_key, 'Fail_Access', 'Wrong path');
		echo json_encode($response);
		exit;
	}

	if ($parsed_scope!=$scope) {
		$response= log_api_key_access_failture($db, $api_key_key, 'Fail_Access', "Path and Scope don't match $parsed_scope $scope  ");
		echo json_encode($response);
		exit;
	}

	$user=check_permisions($db, $user_key, $scope, $api_key_key);


	$editor=array(
		'Author Name'=>$user->data['User Alias'],
		'Author Alias'=>$user->data['User Alias'],
		'Author Type'=>$user->data['User Type'],
		'Author Key'=>$user->data['User Parent Key'],
		'User Key'=>$user->id,
		'Date'=>gmdate('Y-m-d H:i:s')
	);


	switch ($scope) {
	case 'Timesheet':
		include_once 'api_timesheet.php';

		if ($method=='POST') {
			post_timesheet($db, $editor, $api_key_key);

		}else {
			$response= log_api_key_access_failture($db, $api_key_key, 'Fail_Access', "Unauthorized request method");
			echo json_encode($response);
			exit;

		}


		break;
	default:
		$response= log_api_key_access_failture($db, $api_key_key, 'Fail_Access', 'Wrong path');
		echo json_encode($response);
		exit;
		break;
	}


}


function check_permisions($db, $user_key, $scope) {
	include_once 'class.User.php';
	$user=new User($user_key);
	return $user;
}


function parse_scope($request) {

	if ($request=='/timesheet_record') {
		return 'Timesheet';
	}

	return false;

}


function authenticate($db) {

	if (!isset($_SERVER['HTTP_X_AUTH_KEY'])) {

		$response= log_api_key_access_failture($db, 0, 'Fail_Attempt', 'No API key header');

		echo json_encode($response);
		exit;
	}else {
		$api_key=$_SERVER['HTTP_X_AUTH_KEY'];


		if (preg_match('/^([a-z0-9]{8})(.+)$/', $api_key, $matches)) {

			$api_key_code=$matches[1];
			$api_key_secret=base64_decode($matches[2]);



			$sql=sprintf('select `API Key Scope`,`User Key`,`User Active`,`API Key Key`,`API Key Active`,`API Key User Key`,`API Key Hash` from `API Key Dimension` left join `User Dimension` on (`API Key User Key`=`User Key`) where `API Key Code`=%s', prepare_mysql($api_key_code));
			if ($row = $db->query($sql)->fetch()) {

				if ($row['API Key Active']!='Yes') {
					$response= log_api_key_access_failture($db, $row['API Key Key'], 'Fail_Access', 'API Key not active');
					echo json_encode($response);
					exit;
				}

				if (!password_verify($api_key_secret, $row['API Key Hash'])) {



					$response= log_api_key_access_failture($db,  $row['API Key Key'], 'Fail_Access', 'API Key not valid');
					echo json_encode($response);
					exit;
				}


				if ($row['User Active']!='Yes') {

					if ($row['User Key']=='') {
						$response= log_api_key_access_failture($db, $row['API Key Key'], 'Fail_Access', 'User not found');
						echo json_encode($response);
						exit;
					}else {
						$response= log_api_key_access_failture($db, $row['API Key Key'], 'Fail_Access', 'User is not active');
						echo json_encode($response);
						exit;
					}
				}





				return array($row['API Key User Key'], $row['API Key Key'], $row['API Key Scope']);
			}else {
				$response= log_api_key_access_failture($db, 0, 'Fail_Attempt', 'API Key not found');

				echo json_encode($response);
				exit;
			}





		}else {

			$response= log_api_key_access_failture($db, 0, 'Fail_Attempt', 'Invalid API key');
			echo json_encode($response);
			exit;
		}



	}

}


function get_db() {


	include_once 'conf/dns.php';



	$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd , array(\PDO::MYSQL_ATTR_INIT_COMMAND =>"SET time_zone = '+0:00';"));
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


	return $db;


}


function log_api_key_access_failture($db, $api_key_key , $fail_type, $fail_reason) {

	include_once 'utils/detect_agent.php';

	$fail_code=hash('crc32', $fail_reason, false);
	$method=$_SERVER['REQUEST_METHOD'];
	$sql=sprintf('insert into `API Request Dimension` (`API Key Key`,`Date`,`Response`,`Response Code`,`IP`,`HTTP Method`) values(%d,%s,%s,%s,%s,%s)',
		$api_key_key,
		prepare_mysql(gmdate('Y-m-d H:i:s')),
		prepare_mysql($fail_type),
		prepare_mysql($fail_code),
		prepare_mysql(ip()),
		prepare_mysql( $method)
	);

	$db->exec($sql);

	return array('state'=>'Error', 'code'=>$fail_code, 'msg'=>$fail_reason);

}


function log_api_key_access_success($db, $api_key_key , $success_reason) {

	include_once 'utils/detect_agent.php';

	$success_code=hash('crc32', $success_reason, false);

	$method=$_SERVER['REQUEST_METHOD'];

	$sql=sprintf('insert into `API Request Dimension` (`API Key Key`,`Date`,`Response`,`Response Code`,`IP`,`HTTP Method`) values(%d,%s,%s,%s,%s,%s)',
		$api_key_key,
		prepare_mysql(gmdate('Y-m-d H:i:s')),
		prepare_mysql('OK'),
		prepare_mysql($success_code),
		prepare_mysql(ip()),
		prepare_mysql( $method)
	);

	$db->exec($sql);

	return array('state'=>'Success', 'code'=>$success_code, 'msg'=>$success_reason);

}


?>
