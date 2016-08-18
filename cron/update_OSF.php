<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 16 August 2016 at 19:02:58 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

require_once 'common.php';
require_once 'class.Product.php';


$from=date("Y-m-d", strtotime('now '));
$to=date("Y-m-d", strtotime('now '));

create_otf($from, $to);


function create_otf($from, $to) {


	$sql=sprintf("select `Date` from kbase.`Date Dimension` where `Date`>=%s and `Date`<=%s order by `Date` desc",
		prepare_mysql($from), prepare_mysql($to));


	if ($result=$db->query($sql)) {
		foreach ($result as $row) {


			$where=sprintf(" `Product Main Type` in ('Historic','Discontinued')  and  `Product Valid From`<=%s and `Product Valid To`>=%s ",
				prepare_mysql($row['Date'].' 00:00:00'),
				prepare_mysql($row['Date'].' 23:59:59')

			);
			$sql=sprintf('select `Product ID`  from `Product Dimension` where %s     ', $where);
			$res2=mysql_query($sql);
			$count=0;
			while ($row2=mysql_fetch_array($res2)) {
				$product=new Product("pid", $row2['Product ID']);
				$product->create_time_series($row['Date']);
				// $product->update_sales_averages();
				//print $row['Date']." disc ".$product->code."\n";

			}


			$where=sprintf("   `Product Main Type` not in ('Historic','Discontinued')  and  `Product Valid From`<=%s  ",
				prepare_mysql($row['Date'].' 00:00:00')
			);
			$sql=sprintf('select `Product ID`  from `Product Dimension` where %s     ', $where);
			$res2=mysql_query($sql);
			//print "$sql\n";
			$count=0;
			while ($row2=mysql_fetch_assoc($res2)) {
				$product=new Product("pid", $row2['Product ID']);
				$product->create_time_series($row['Date']);

			}




		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}




mod

}


?>
