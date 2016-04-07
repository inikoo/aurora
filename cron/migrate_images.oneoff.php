<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2016 at 09:45:54 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$default_DB_link=@mysql_connect($dns_host, $dns_user, $dns_pwd );
if (!$default_DB_link) {
	print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
	print "Error can not access the database\n";
	exit;
}
mysql_set_charset('utf8');
mysql_query("SET time_zone='+0:00'");



require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Image.php';
require_once 'class.Store.php';
require_once 'class.Warehouse.php';
require_once 'class.Part.php';

require_once 'class.StoreProduct.php';
include_once 'utils/parse_materials.php';
$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);



/*
//update `Image Bridge` set  `Subject Type`='Store Product' where `Subject Type`='Product';<--- do it later ????


ALTER TABLE `Image Bridge` ADD `Image Public` ENUM('Yes','No') NOT NULL DEFAULT 'No' AFTER `Image Caption`, ADD `Image Order` SMALLINT UNSIGNED NOT NULL DEFAULT '1' AFTER `Image Public`;


ALTER TABLE `Image Bridge` ADD `Image Bridge Key` mediumint PRIMARY KEY AUTO_INCREMENT;
ALTER TABLE `Image Bridge` CHANGE `Image Bridge Key` `Image Bridge Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `Image Bridge` CHANGE `Image Bridge Key` `Image Bridge Key` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST;
ALTER TABLE `Image Bridge` ADD `Date` DATETIME NULL DEFAULT NULL AFTER `Image Order`;
ALTER TABLE `Image Bridge` CHANGE `Image Bridge Key` `Image Subject Key` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT, CHANGE `Subject Type` `Image Subject Subject` ENUM('Store Product','Site Favicon','Product','Family','Department','Store','Part','Supplier Product','Store Logo','Store Email Template Header','Store Email Postcard','Email Image','Page','Page Header','Page Footer','Page Header Preview','Page Footer Preview','Page Preview','Site Menu','Site Search','User Profile','Attachment Thumbnail','Category') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `Subject Key` `Image Subject Subject Key` MEDIUMINT(8) UNSIGNED NOT NULL, CHANGE `Image Key` `Image Subject Image Key` MEDIUMINT(8) UNSIGNED NOT NULL, CHANGE `Is Principal` `Image Subject Is Principal` ENUM('Yes','No') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Yes', CHANGE `Image Caption` `Image Subject Image Caption` VARCHAR(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `Image Public` `Image Subject Image Public` ENUM('Yes','No') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No', CHANGE `Image Order` `Image Subject Order` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '1', CHANGE `Date` `Image Subject Date` DATETIME NULL DEFAULT NULL;
ALTER TABLE `Image Subject Bridge` CHANGE `Image Subject Image Public` `Image Subject Is Public` ENUM('Yes','No') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No';
ALTER TABLE `Image Subject Bridge` CHANGE `Image Subject Subject` `Image Subject Object` ENUM('Store Product','Site Favicon','Product','Family','Department','Store','Part','Supplier Product','Store Logo','Store Email Template Header','Store Email Postcard','Email Image','Page','Page Header','Page Footer','Page Header Preview','Page Footer Preview','Page Preview','Site Menu','Site Search','User Profile','Attachment Thumbnail','Category') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `Image Subject Subject Key` `Image Subject Object Key` MEDIUMINT(8) UNSIGNED NOT NULL;

*/


$sql=sprintf('select * from `Image Bridge` where `Subject Type`  ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {


		$sql=sprintf("insert into `Image Subject Bridge` (`Image Subject Object`,`Image Subject Object Key`,`Image Subject Image Key`,`Image Subject Is Principal`,`Image Subject Image Caption`) values (%s,%d,%d,%s,%s)",
			prepare_mysql($row['Subject Type']),

			$row['Subject Key'],
			$row['Image Key'],

			prepare_mysql($row['Is Principal']),
			prepare_mysql($row['Image Caption'],false)

		);
		
		//print "$sql\n";
        $db->exec($sql);
        
	}
}else {
	print_r($error_info=$db->errorInfo());
	exit;
}


$sql=sprintf('select * from `Part Dimension`  ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {

		set_order($db, 'Part', $row['Part SKU']);



	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}


function set_order($db, $object, $object_key) {

	$sql=sprintf("select `Image Subject Key` from `Image Subject Bridge` where `Image Subject Object`=%s and   `Image Subject Object Key`=%d order by `Image Subject Is Principal`,`Image Subject Date`,`Image Subject Key`",
		prepare_mysql($object),
		$object_key
	);
	//print $sql;
	$order=1;
	if ($result=$db->query($sql)) {
		foreach ($result as $row) {

			$sql=sprintf("update `Image Subject Bridge` set `Image Subject Order`=%d where `Image Subject Key`=%d ",
				$order,
				$row['Image Subject Key']
			);

			$db->exec($sql);
			$order++;
		}
	}else {
		print_r($error_info=$db->errorInfo()); print "$sql";
		exit;
	}




}


?>
