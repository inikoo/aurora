<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012 Inikoo
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Category.php';
include_once '../../class.Node.php';




error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';

global $myconf;


$sql=sprintf("select * from `History Dimension` where `Direct Object`='Store'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$Store=new Store($row['Direct Object Key']);
	if ($Store->id) {
		$sql=sprintf("insert into  `Store History Bridge` (`Store Key`,`History Key`,`Type`) values (%d,%d,'Changes')",$Store->id,$row['History Key']);
		mysql_query($sql);
		//print "$sql\n";
	}
}

$sql=sprintf("select * from `History Dimension` where `Indirect Object`='Store'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$Store=new Store($row['Indirect Object Key']);
	if ($Store->id) {
		$sql=sprintf("insert into  `Store History Bridge` (`Store Key`,`History Key`,`Type`) values (%d,%d,'Changes')",$Store->id,$row['History Key']);
		mysql_query($sql);
		//print "$sql\n";
	}
}


$sql=sprintf("select * from `History Dimension` where `Direct Object`='Department'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$Department=new Department($row['Direct Object Key']);
	if ($Department->id) {
		$sql=sprintf("insert into  `Product Department History Bridge` (`Department Key`,`History Key`,`Type`) values (%d,%d,'Changes')",$Department->id,$row['History Key']);
		mysql_query($sql);
		//print "$sql\n";
	}
}

$sql=sprintf("select * from `History Dimension` where `Indirect Object`='Department'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$Department=new Department($row['Indirect Object Key']);
	if ($Department->id) {
		$sql=sprintf("insert into  `Product Department History Bridge` (`Department Key`,`History Key`,`Type`) values (%d,%d,'Changes')",$Department->id,$row['History Key']);
		mysql_query($sql);
		//print "$sql\n";
	}
}


$sql=sprintf("select * from `History Dimension` where `Direct Object`='Family'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$Family=new Family($row['Direct Object Key']);
	if ($Family->id) {
		$sql=sprintf("insert into  `Product Family History Bridge` (`Family Key`,`History Key`,`Type`) values (%d,%d,'Changes')",$Family->id,$row['History Key']);
		mysql_query($sql);
		//print "$sql\n";
	}
}

$sql=sprintf("select * from `History Dimension` where `Indirect Object`='Family'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$Family=new Family($row['Indirect Object Key']);
	if ($Family->id) {
		$sql=sprintf("insert into  `Product Family History Bridge` (`Family Key`,`History Key`,`Type`) values (%d,%d,'Changes')",$Family->id,$row['History Key']);
		mysql_query($sql);
		//print "$sql\n";
	}
}



$sql=sprintf("select * from `History Dimension` where `Direct Object`='Product'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$Product=new Product($row['Direct Object Key']);
	if ($Product->id) {
		$sql=sprintf("insert into  `Product History Bridge` (`Product Key`,`History Key`,`Type`) values (%d,%d,'Changes')",$Product->id,$row['History Key']);
		mysql_query($sql);
		//print "$sql\n";
	}
}

$sql=sprintf("select * from `History Dimension` where `Indirect Object`='Product'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$Product=new Product($row['Indirect Object Key']);
	if ($Product->id) {
		$sql=sprintf("insert into  `Product History Bridge` (`Product Key`,`History Key`,`Type`) values (%d,%d,'Changes')",$Product->id,$row['History Key']);
		mysql_query($sql);
		//print "$sql\n";
	}
}

$sql=sprintf("select * from `History Dimension` where `Direct Object`='Supplier'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$supplier=new Supplier($row['Direct Object Key']);
	if ($supplier->id) {
		$sql=sprintf("insert into  `Supplier History Bridge` (`Supplier Key`,`History Key`,`Type`) values (%d,%d,'Changes')",$supplier->id,$row['History Key']);
		mysql_query($sql);
		//print "$sql\n";
	}
}

$sql=sprintf("select * from `History Dimension` where `Indirect Object`='Supplier'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$supplier=new Supplier($row['Indirect Object Key']);
	if ($supplier->id) {
		$sql=sprintf("insert into  `Supplier History Bridge` (`Supplier Key`,`History Key`,`Type`) values (%d,%d,'Changes')",$supplier->id,$row['History Key']);
		mysql_query($sql);
		//print "$sql\n";
	}
}


?>
