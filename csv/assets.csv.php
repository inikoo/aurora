<?php
/*

 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 28 March 2014 11:08:39 CET, Malaga Spain

 Copyright (c) 2012, Inikoo
 Version 2.0
*/
require_once '../common.php';
require_once '../ar_common.php';

if (!isset($_REQUEST['tipo'])) {

	exit;
}

/*
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");
header("Pragma: no-cache");
header("Expires: 0");
*/
$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case ('asset_sales'):
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'key'),
			'parent'=>array('type'=>'string'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	asset_sales($data);
	break;
default:

}

function asset_sales($data) {

	global $memcache_ip,$account_code;

	switch ($data['parent']) {
	case 'product':
		$fields=' `Date`,`Sales`';
		$where=sprintf("where `Product ID`=%d",$data['parent_key']);
		$group='';
		break;
	case 'family':
		$fields=' `Date`,sum(`Sales`) as Sales';
		$where=sprintf("where `Product Family Key`=%d",$data['parent_key']);
		$group='group by `Date`';
		break;
	case 'department':
		$fields=' `Date`,sum(`Sales`) as Sales';

		$where=sprintf("where `Product Department Key`=%d",$data['parent_key']);
		$group='group by `Date`';
		break;
	case 'store':
		$fields=' `Date`,sum(`Sales`) as Sales';
		$where=sprintf("where `Store Key`=%d",$data['parent_key']);
		$group='group by `Date`';
		break;
	default:
		return;

	}

	$where_interval=prepare_mysql_dates($data['from'],$data['to'],'`Date`');
	$where.=$where_interval['mysql'];

	$cache = new Memcached();
	$cache->addServer($memcache_ip, 11211);

	$sql=sprintf("select %s from `Order Spanshot Fact` %s %s",
		$fields,
		$where,
		$group
	);

	$result=$cache->get($account_code.'SQL'.md5($sql));
	if ($result) {
		print "date,sales\n";
		foreach ($result as $row) {
			print sprintf("%s,%s\n",$row['Date'],$row['Sales']);
		}

	}else {

		$res=mysql_query($sql);
		$result=array();
		print "date,sales\n";
		while ($row=mysql_fetch_assoc($res)) {
			print sprintf("%s,%s\n",$row['Date'],$row['Sales']);
			$result[]=$row;
		}
		$cache->set($account_code.'SQL'.md5($sql),$result,86400 );
	}








}

?>
