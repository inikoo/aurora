<?php
//@author Raul Perusquia <raul@inikoo.com>
//Copyright (c) 2013 Inikoo

error_reporting(E_ALL);

require_once 'aes.php';
require_once 'common_functions.php';
require_once 'conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;

include('gearman/export.php');
include('gearman/ping_sitemap.php');
include('gearman/import.php');

$count_number_used=0;


$worker= new GearmanWorker();
$worker->addServer('127.0.0.1');
$worker->addFunction("export", "fork_export");
$worker->addFunction("ping_sitemap", "fork_ping_sitemap");
$worker->addFunction("import", "fork_import");

while ($worker->work()) {

	if ($worker->returnCode() == GEARMAN_SUCCESS) {
		$count_number_used++;

		exec("kill -9 ". getmypid());
		die();

	}

}




function get_fork_data($job) {

	$fork_encrypt_key=md5('huls0fjhslsshskslgjbtqcwijnbxhl2391');


	$fork_raw_data=$job->workload();
	
	
	$fork_metadata=json_decode(AESDecryptCtr(base64_decode($fork_raw_data),$fork_encrypt_key,256),true);
	



	$inikoo_account_code=$fork_metadata['code'];
	
	if(!ctype_alnum($inikoo_account_code)){
		print "cant fint account code\n";
		return false;
	}
	
	include "gearman/conf/dns.$inikoo_account_code.php";



	$fork_key=$fork_metadata['fork_key'];
	$token=$fork_metadata['token'];
	$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
	if (!$default_DB_link) {
		print "Error can not connect with database server\n";
		return false;
	}
	$db_selected=mysql_select_db($dns_db, $default_DB_link);
	if (!$db_selected) {
		print "Error can not access the database\n";
		return false;
	}

	mysql_query("SET NAMES 'utf8'");
	mysql_query("SET time_zone='+0:00'");
	$sql=sprintf("select `Fork Process Data` from `Fork Dimension` where `Fork Key`=%d and `Fork Token`=%s",
		$fork_key,
		prepare_mysql($token)
	);



	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$fork_data=json_decode($row['Fork Process Data'],true);

		return array('fork_key'=>$fork_key,'inikoo_account_code'=>$inikoo_account_code,'fork_data'=>$fork_data);
	}else {

		print "fork data not found";
		return false;
	}

}




?>
