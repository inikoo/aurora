<?php
//@author Raul Perusquia <raul@inikoo.com>
//Copyright (c) 2013 Inikoo
include_once 'app_files/db/dns.php';
require_once 'ar_edit_common.php';

if ( isset($argv[1]) )
	$fork_key = $argv[1];
else {
	syslog(LOG_ERR,"the input parameter is absent");
	exit;
}

$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$default_DB_link) {
	print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
	print "Error can not access the database\n";
	exit;
}

mysql_query("SET NAMES 'utf8'");
require_once 'conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;
mysql_query("SET time_zone='+0:00'");

$sql=sprinf("select `Fork Process Data` from `Fork Dimension` where `Fork Key`=%d",$fork_key);
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	$fork_data=unserialize($row['Fork Process Data'])

}else {
	print "Error no fork data\n";
	exit;
}

switch ($fork_data['tipo']) {
case 'edit_parts';
	$data=prepare_values($fork_data,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'subject_source_checked_type'=>array('type'=>'string'),
			'subject_source_checked_subjects'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'value'=>array('type'=>'string'),
			'fork_key'=>$fork_key

		));
	edit_parts($data);

}


function edit_parts($data) {

	$number_parts=0;
	$number_parts_updated=0;
	$number_parts_no_change=0;
	$number_parts_errors=0;




	if ($data['subject_source_checked_type']=='unchecked') {

		$subject_source_checked_subjects=preg_split('/,/',$data['subject_source_checked_subjects']);
		$estimated_number_parts=count($subject_source_checked_subjects);

		$sql=sprintf("update `Fork Dimension` set `Fork State`='In Process' ,`Fork State=%s",
			prepare_mysql(serialize(array('total'=>$estimated_number_parts,'done'=>0))),
			$data['fork_key']
		);
		mysql_query($sql);

		foreach ($subject_source_checked_subjects as $subject_key) {
			$part= new Part($subject_key);
			if ($part->sku) {
				$number_parts++;
				$part->update(array($data['key']=>$data['value']));
				if ($part->error) {
					$number_parts_errors++;
				}elseif ($part->updated) {
					$number_parts_updated++;
				}else {
					$number_parts_no_change++;
				}

				$sql=sprintf("update `Fork Dimension` set `Fork State=%s where `Fork Key`",
					prepare_mysql(serialize(array('total'=>$estimated_number_parts,'done'=>$number_parts))),
					$data['fork_key']

				);
				mysql_query($sql);
			}
		}
	}
	else {

		switch ($data['parent']) {
		case 'category':
			$f_value=$_SESSION['state']['part_categories']['edit_parts']['f_value'];
			$f_field=$_SESSION['state']['part_categories']['edit_parts']['f_field'];
			break;
		case 'warehouse':
			$f_value=$_SESSION['state']['warehouse']['edit_parts']['f_value'];
			$f_field=$_SESSION['state']['warehouse']['edit_parts']['f_field'];
			break;
		}


		$wheref='';
		if ($f_field=='used_in' and $f_value!='')
			$wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
		elseif ($f_field=='description' and $f_value!='')
			$wheref.=" and  `Part Unit Description` like '%".addslashes($f_value)."%'";
		elseif ($f_field=='supplied_by' and $f_value!='')
			$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
		elseif ($f_field=='sku' and $f_value!='')
			$wheref.=" and  P.`Part SKU` ='".addslashes($f_value)."'";


		switch ($data['parent']) {
		case 'category':

			$sql=sprintf("select `Subject Key` as `Part SKU` from `Category Bridge` B left join `Part Dimension` P on (`Part SKU`=`Subject Key`)  where `Subject`='Part' and `Category Key`=%d %s",$data['parent_key'],$wheref);

			break;
		case 'warehouse':
			$sql=sprintf("select B.`Part SKU` from `Part Warehouse Bridge`  from `Category Bridge` B left join `Part Dimension` P on (`Part SKU`=`Subject Key`) where `Warehouse Key`=%d %s",$data['parent_key'],$wheref);
			break;
		}

		$res=mysql_query($sql);
		$no_checked_subjects=preg_split('/,/',$data['subject_source_checked_subjects']);

		$estimated_number_parts = mysql_num_rows($res)-count($no_checked_subjects);
		$sql=sprintf("update `Fork Dimension` set `Fork State`='In Process' ,`Fork State=%s",
			prepare_mysql(serialize(array('total'=>$estimated_number_parts,'done'=>0))),
			$data['fork_key']
		);
		mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			if (!in_array($row['Part SKU'],$no_checked_subjects)) {
				$part= new Part($row['Part SKU']);
				if ($part->sku) {
					$number_parts++;
					$part->update(array($data['key']=>$data['value']));
					if ($part->error) {
						$number_parts_errors++;
					}elseif ($part->updated) {
						$number_parts_updated++;
					}else {
						$number_parts_no_change++;
					}
					$sql=sprintf("update `Fork Dimension` set `Fork State=%s where `Fork Key`",
						prepare_mysql(serialize(array('total'=>$estimated_number_parts,'done'=>$number_parts))),
						$data['fork_key']

					);
					mysql_query($sql);

				}
			}

		}


	}


}

?>
