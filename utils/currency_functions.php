<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 July 2016 at 19:05:57 GMT+8, Kuala Lumpu, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


function currency_conversion($db, $currency_from, $currency_to, $update_interval="-1 hour") {
	$reload=false;
	$in_db=false;
	$exchange_rate=1;


	//get info from database;
	$sql=sprintf("select * from kbase.`Currency Exchange Dimension` where `Currency Pair`=%s", prepare_mysql($currency_from.$currency_to));


	if ($result=$db->query($sql)) {
		if ($row = $result->fetch()) {

			$date1=$row['Currency Exchange Last Updated'];
			$date2=gmdate("Y-m-d H:i:s", strtotime('now '.$update_interval));


			if ( strtotime($date1)<strtotime($date2)) {

				$reload=true;
				

			}
			$exchange_rate=$row['Exchange'];

		}else {
			$reload=true;
			$in_db=false;
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}





	if ($reload) {
		$url = "http://quote.yahoo.com/d/quotes.csv?s=". $currency_from . $currency_to . "=X". "&f=l1&e=.csv";
		
		$handle = fopen($url, "r");
		$contents = floatval(fread($handle, 2000));
		fclose($handle);



		if (is_numeric($contents) and $contents>0) {
			$exchange_rate=$contents;



			$sql=sprintf("insert into kbase.`Currency Exchange Dimension`  (`Currency Pair`,`Exchange`,`Currency Exchange Last Updated`,`Currency Exchange Source`) values (%s,%f,NOW(),'Yahoo')  ON DUPLICATE KEY update `Exchange`=%f,`Currency Exchange Last Updated`=NOW(),`Currency Exchange Source`='Yahoo'",
				prepare_mysql($currency_from.$currency_to), $exchange_rate, $exchange_rate);

			$db->exec($sql);


		}


	}

	return $exchange_rate;
}


?>
