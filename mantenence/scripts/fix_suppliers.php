<?php
/*

 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 December 2013 22:43:55 CET, Malaga Spain
 Copyright (c) 2013, Inikoo

 Version 2.0
*/



include_once '../../app_files/db/dns.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.SupplierProduct.php';
date_default_timezone_set('UTC');

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';



$sql="select count(*) as number,GROUP_CONCAT(`Supplier Key`)  supplier_keys, `Supplier Code` from `Supplier Dimension` group by `Supplier Code` ";
$resx=mysql_query($sql);
while ($rowx=mysql_fetch_assoc($resx)) {
	if ($rowx['number']>1) {
		print $rowx['Supplier Code']."----------\n";


		$sql=sprintf("select min(`Supplier Product Valid From`) as date_from, max(`Supplier Product Valid To`) as date_to ,count(*) as number,`Supplier Product Code` from `Supplier Product Dimension` where `Supplier Key` in (%s) group by `Supplier Product Code`  ",
			$rowx['supplier_keys']
		);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			//print_r($row);
			if ($row['number']>1) {


				$sql=sprintf("select `Supplier Product Valid From` ,`Supplier Product ID`,`Supplier Product Current Key` ,`Supplier Product Code` from `Supplier Product Dimension` where  `Supplier Product Code`=%s and   `Supplier Key` in (%s) order by `Supplier Product Valid From` desc  ",
					prepare_mysql($row['Supplier Product Code']),
					$rowx['supplier_keys']
				);
				$contador=0;
				$res3=mysql_query($sql);
				while ($row3=mysql_fetch_assoc($res3)) {
					if ($contador==0) {
						$new_sp_pid=$row3['Supplier Product ID'];
						$new_sp_key=$row3['Supplier Product Current Key'];
					}else {

						$sql=sprintf("select  `Supplier Product Part Key` from `Supplier Product Part Dimension` where `Supplier Product ID`=%d ",
							$row3['Supplier Product ID']
						);
						$res4=mysql_query($sql);
						while ($row4=mysql_fetch_assoc($res4)) {

							$sql=sprintf("delete from `Supplier Product Part Dimension` where `Supplier Product Part Key`=%d ",$row4['Supplier Product Part Key']);
							mysql_query($sql);
							$sql=sprintf("delete from `Supplier Product Part List` where `Supplier Product Part Key`=%d ",$row4['Supplier Product Part Key']);
							mysql_query($sql);


						}

						$sql=sprintf("select  `Supplier Product Part Key` from `Supplier Product Part Dimension` where `Supplier Product Historic Key`=%d ",
							$row3['Supplier Product Current Key']
						);
						$res4=mysql_query($sql);
						while ($row4=mysql_fetch_assoc($res4)) {

							$sql=sprintf("delete from `Supplier Product Part Dimension` where `Supplier Product Part Key`=%d ",$row4['Supplier Product Part Key']);
							mysql_query($sql);
							$sql=sprintf("delete from `Supplier Product Part List` where `Supplier Product Part Key`=%d ",$row4['Supplier Product Part Key']);
							mysql_query($sql);


						}


						$sql=sprintf("update `Inventory Transaction Fact` set `Supplier Product ID`=%d where `Supplier Product ID`=%d ",$new_sp_pid,$row4['Supplier Product ID']);
						mysql_query($sql);
						$sql=sprintf("update `Inventory Transaction Fact` set `Supplier Product Historic Key`=%d where `Supplier Product Historic Key`=%d ",$new_sp_key,$row4['Supplier Product Part Key']);
						mysql_query($sql);
						$sql=sprintf("delete from `Supplier Product Dimension` where `Supplier Product ID`=%d ",$row4['Supplier Product ID']);
						mysql_query($sql);
						$sql=sprintf("delete from `Supplier Product History Dimension` where `Supplier Product ID`=%d ",$row4['Supplier Product ID']);
						mysql_query($sql);

					}

					$contador++;
				}







			}







		}







		$sql=sprintf("select `Supplier Valid From`,`Supplier Key` from `Supplier Dimension` where  `Supplier Code`=%s order by `Supplier Valid From` desc  ",
			prepare_mysql($rowx['Supplier Code'])
		);
		//print "$sql\n";
		$contador=0;
		$res3=mysql_query($sql);
		while ($row3=mysql_fetch_assoc($res3)) {

			if ($contador==0) {
				$new_supplier_key=$row3['Supplier Key'];
			}else {

				$sql=sprintf("update `Inventory Transaction Fact` set `Supplier Key`=%d where `Supplier Key`=%d ",$new_supplier_key,$row3['Supplier Key']);
				mysql_query($sql);

				$sql=sprintf("update `Supplier History Bridge` set `Supplier Key`=%d where `Supplier Key`=%d ",$new_supplier_key,$row3['Supplier Key']);
				mysql_query($sql);

				$sql=sprintf("update `Supplier Product Dimension` set `Supplier Key`=%d where `Supplier Key`=%d ",$new_supplier_key,$row3['Supplier Key']);
				mysql_query($sql);



				$sql=sprintf("delete from `Part Supplier Bridge` where `Supplier Key`=%d ",$row3['Supplier Key']);
				mysql_query($sql);
				$sql=sprintf("delete from `Supplier Dimension` where `Supplier Key`=%d ",$row3['Supplier Key']);
				mysql_query($sql);

				//print "$sql\n";



			}
			$contador++;

		}

	}


}


?>
