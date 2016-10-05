<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 24 March 2016 at 13:57:44 GMT+8, Kuala Lumpur, Malysia
 Created: 2013
 Copyright (c) 2016, Inikoo

 Version 3

*/

error_reporting(E_ALL ^ E_DEPRECATED);

include 'utils/aes.php';
include 'utils/general_functions.php';
include 'utils/system_functions.php';

include 'export.fork.php';
include 'export_edit_template.fork.php';
include 'upload_edit.fork.php';
include 'housekeeping.fork.php';
include 'asset_sales.fork.php';




$count_number_used=0;


$worker= new GearmanWorker();
$worker->addServer('127.0.0.1');
$worker->addFunction("au_export", "fork_export");
$worker->addFunction("au_export_edit_template", "fork_export_edit_template");
$worker->addFunction("au_upload_edit", "fork_upload_edit");
$worker->addFunction("au_housekeeping", "fork_housekeeping");
$worker->addFunction("au_asset_sales", "fork_asset_sales");



$db=false;
$account=false;

while ($worker->work()) {
	if ($worker->returnCode() == GEARMAN_SUCCESS) {
		$count_number_used++;
		exec("kill -9 ". getmypid());
		die();
	}
}

function get_fork_metadata($job) {

	global $db, $account;

	$fork_encrypt_key=md5('huls0fjhslsshskslgjbtqcwijnbxhl2391');
	$fork_raw_data=$job->workload();
	$fork_metadata=json_decode(AESDecryptCtr(base64_decode($fork_raw_data), $fork_encrypt_key, 256), true);


	$inikoo_account_code=$fork_metadata['code'];
	if (!ctype_alnum($inikoo_account_code)) {

		print_r($fork_metadata);

		print "can't find account code ->".$inikoo_account_code."<-  \n";
		return false;
	}

	require_once "keyring/dns.$inikoo_account_code.php";
	require_once "class.Account.php";

	$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd , array(\PDO::MYSQL_ATTR_INIT_COMMAND =>"SET time_zone = '+0:00';"));
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


	$default_DB_link=mysql_connect($dns_host, $dns_user, $dns_pwd );
	if (!$default_DB_link) {
		print "Error can not connect with database server\n";
		return false;
	}
	$db_selected=mysql_select_db($dns_db, $default_DB_link);
	if (!$db_selected) {
		print "Error can not access the database\n";
		return false;
	}
	mysql_set_charset('utf8');
	mysql_query("SET time_zone='+0:00'");


	$account=new Account($db);

	if ($account->get('Timezone')) {
		date_default_timezone_set($account->get('Timezone'));
	}else {
		setTimezone('UTC');
	}

	return array($account,$db,$fork_metadata['data']);


}


function get_fork_data($job) {

	global $db, $account;

	$fork_encrypt_key=md5('huls0fjhslsshskslgjbtqcwijnbxhl2391');
	$fork_raw_data=$job->workload();
	$fork_metadata=json_decode(AESDecryptCtr(base64_decode($fork_raw_data), $fork_encrypt_key, 256), true);


	$inikoo_account_code=$fork_metadata['code'];
	if (!ctype_alnum($inikoo_account_code)) {

		print_r($fork_metadata);

		print "can't find account code ->".$inikoo_account_code."<-  \n";
		return false;
	}

	require_once "keyring/dns.$inikoo_account_code.php";
	require_once "class.Account.php";



	$fork_key=$fork_metadata['fork_key'];
	$token=$fork_metadata['token'];

	$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd , array(\PDO::MYSQL_ATTR_INIT_COMMAND =>"SET time_zone = '+0:00';"));
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


	$default_DB_link=mysql_connect($dns_host, $dns_user, $dns_pwd );
	if (!$default_DB_link) {
		print "Error can not connect with database server\n";
		return false;
	}
	$db_selected=mysql_select_db($dns_db, $default_DB_link);
	if (!$db_selected) {
		print "Error can not access the database\n";
		return false;
	}
	mysql_set_charset('utf8');
	mysql_query("SET time_zone='+0:00'");


	$account=new Account($db);

	if ($account->get('Timezone')) {
		date_default_timezone_set($account->get('Timezone'));
	}else {
		setTimezone('UTC');
	}



	$sql=sprintf("select `Fork Process Data` from `Fork Dimension` where `Fork Key`=%d and `Fork Token`=%s",
		$fork_key,
		prepare_mysql($token)
	);


	if ($result=$db->query($sql)) {
		if ($row = $result->fetch()) {
			$fork_data=json_decode($row['Fork Process Data'], true);


			return array('fork_key'=>$fork_key, 'inikoo_account_code'=>$inikoo_account_code, 'fork_data'=>$fork_data, 'db'=>$db);
		}else {
			print "fork data not found";
			return false;
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}




}


?>
