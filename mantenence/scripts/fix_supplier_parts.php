<?php
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
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



$sql="select * from `Supplier Product Part Dimension` where `Supplier Product Historic Key`=0";

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {

	// $supplier_product=new SupplierProduct($row['Supplier Product Key']);
	$historic_keys=array();
	$sql=sprintf("select `SPH Key` from `Supplier Product History Dimension` where `Supplier Product Key`=%d and `SPH Valid From`>=%s and `SPH Valid To`<=%s  ",
		$row['Supplier Product Key'],
		prepare_mysql($row['Supplier Product Part Valid From']),
		prepare_mysql($row['Supplier Product Part Valid To'])
	);
	//print "$sql\n";
	$res2=mysql_query($sql);

	while ($row2=mysql_fetch_array($res2)) {
		$historic_keys[$row2['SPH Key']]=$row2['SPH Key'];
	}

	$sql=sprintf("select `SPH Key` from `Supplier Product History Dimension` where `Supplier Product Key`=%d and `SPH Valid From`<=%s and `SPH Valid To`>=%s  ",
		$row['Supplier Product Key'],
		prepare_mysql($row['Supplier Product Part Valid From']),
		prepare_mysql($row['Supplier Product Part Valid From'])
	);
	//print "$sql\n";
	$res2=mysql_query($sql);

	while ($row2=mysql_fetch_array($res2)) {
		$historic_keys[$row2['SPH Key']]=$row2['SPH Key'];
	}


	$sql=sprintf("select `SPH Key` from `Supplier Product History Dimension` where `Supplier Product Key`=%d and `SPH Valid From`<=%s and `SPH Valid To`>=%s  ",
		$row['Supplier Product Key'],
		prepare_mysql($row['Supplier Product Part Valid To']),
		prepare_mysql($row['Supplier Product Part Valid To'])
	);
	//print "$sql\n";
	$res2=mysql_query($sql);

	while ($row2=mysql_fetch_array($res2)) {
		$historic_keys[$row2['SPH Key']]=$row2['SPH Key'];
	}


	$number_historic=count($historic_keys);
	if ($number_historic==0) {
		print $row['Supplier Product Key']." $number_historic\n";
		print_r($row);
		exit;

	}elseif ($number_historic>1) {



		$sql=sprintf("select `SPH Key` from `Supplier Product History Dimension` where `SPH Key` in (%s)  order by `SPH Valid To` desc, `SPH Key` desc  ",
			join(',',$historic_keys)
		);
		//print $sql;
		//exit;
		$resx=mysql_query($sql);
		if ($rowx=mysql_fetch_assoc($resx)) {
			$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Historic Key`=%d where `Supplier Product Part Key`=%d",
				$rowx['SPH Key'],
				$row['Supplier Product Part Key']
			);
			//print "$sql\n";
			//exit;
			mysql_query($sql);

		}


	}else {
		$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Historic Key`=%d where `Supplier Product Part Key`=%d",
			array_pop($historic_keys),
			$row['Supplier Product Part Key']
		);
		mysql_query($sql);
	}

}

$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Valid To`=NOW() where `Supplier Product Part Valid To` IS NULL or  `Supplier Product Part Valid To`>NOW() "

);
mysql_query($sql);

$sql="select `Supplier Product Key`,count(*) as num from `Supplier Product Part Dimension` where `Supplier Product Part Most Recent`='Yes' group by `Supplier Product Key`";
//print $sql;
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	if ($row['num']>1) {
		print $row['Supplier Product Key'].' '.$row['num']."====\n";

		$sql=sprintf("select * from `Supplier Product Part Dimension` where `Supplier Product Key`=%d order by `Supplier Product Part Valid To` desc ",$row['Supplier Product Key']);;
		//print $sql;
		$res2=mysql_query($sql);
		$count=0;
		while ($row2=mysql_fetch_assoc($res2)) {
			print_r($row2);

			if ($count>0) {
			
				$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Most Recent`='No',`Supplier Product Part In Use`='No' where `Supplier Product Part Key`=%d",
				$row2['Supplier Product Part Key']
				);
				mysql_query($sql);
				//print "$sql\n";
			}

			$count++;
		}
		
	}
}

exit;

//first we are going to fix the supplier-part list with no mos recent

$sql="select count(*) as num, `Part SKU` from `Supplier Product Part List` SPPL left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`)      group by `Part SKU` order by num desc";
//print $sql;
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {




	$sql=sprintf("select GROUP_CONCAT(`Supplier Product Part Most Recent`) as most_recent from `Supplier Product Part List` SPPL left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`)    where  `Part SKU`=%d",
		$row['Part SKU']
	);

	$res2=mysql_query($sql);
	while ($row2=mysql_fetch_array($res2)) {
		if (!preg_match('/Yes/',$row2['most_recent'])) {
			//print $row['Part SKU']."\n";
			//print $row2['most_recent']."\n";


			$sql=sprintf("select SPPD.`Supplier Product Part Key`,`Supplier Product Part Valid To` from `Supplier Product Part List` SPPL left join `Supplier Product Part Dimension` SPPD on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key`)    where  `Part SKU`=%d order by `Supplier Product Part Valid To`",
				$row['Part SKU']
			);

			$res3=mysql_query($sql);
			$counter=0;
			while ($row3=mysql_fetch_array($res3)) {
				if ($counter==0) {
					$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Most Recent`='Yes' where `Supplier Product Part Key`=%d  ",$row3['Supplier Product Part Key']);
					mysql_query($sql);
				}
				$counter++;
			}
		}
	}



}



$sql="select `Product Code`,`Product Store Key`,count(*) as caca from `Product Dimension` where `Product Code`='SoapD-08' and `Product Store Key`=1   group by `Product Code`,`Product Store Key`  ";
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {

	$code=$row['Product Code'];
	$store_key=$row['Product Store Key'];

	$sql=sprintf("select `Product ID`,`Product Code`,`Product Store Key`,`Product Valid To`,`Product Sales Type`,`Product Availability Type` from `Product Dimension`  where `Product Code`=%s and `Product Store Key`=%d order by `Product Valid To` desc",
		prepare_mysql($code),
		$store_key
	);

	$res2=mysql_query($sql);
	$count=0;
	while ($row2=mysql_fetch_assoc($res2)) {

		print $row2['Product ID']." count: $count\n";
		$product=new Product('pid',$row2['Product ID']);
		if ($count==0) {

			if ($product->data['Product Record Type']!='Normal') {
				$sql=sprintf("update `Product Dimension` set `Product Record Type`='Normal' where `Product ID`=%d"
					,$product->pid);

				mysql_query($sql);
				print "$sql\n";
				$product->data['Product Record Type']='Normal';


				if ($product->data['Product Sales Type']=='Not for Sale')
					$product->update_sales_type('Public Sale');
			}

		} else {

			//print_r($row2);

			$product->set_as_historic($row2['Product Valid To']);


			$current_part_skus=$product->get_current_part_skus();
			if (count($current_part_skus)==0)
				exit("error can not find part list\n");


			foreach ($current_part_skus as $_part_sku) {
				$part=new Part($_part_sku);
				$part->update_status('Not In Use');
				// print "xxx";
				$supplier_products=$part->get_supplier_products();

				if (count($supplier_products)==0) {
					//print_r($product);
					exit("xxxxxaaerror can not find part prod sup list\n");
				}
				foreach ($supplier_products as $supplier_product) {
					$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Status`='Not In Use' where `Supplier Product Key`=%d",
						$supplier_product['Supplier Product Key']
					);
					mysql_query($sql);
					//  print "$sql\n";
					$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part In Use`='No' where `Supplier Product Part Key`=%d",
						$supplier_product['Supplier Product Part Key']
					);
					mysql_query($sql);
					//    print "$sql\n";

				}

				//  exit;
			}






		}

		$count++;
	}

}




?>
