<?php


function get_address($address_id) {

	$address1='';
	$address2='';
	$town='';
	$postcode='';
	$country_div='';
	$country='';

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`sales_flat_order_address` WHERE `entity_id` =%d",$address_id);
	$res3=mysql_query($sql);
	if ($row3=mysql_fetch_assoc($res3)) {

		$town=$row3['city'];
		$postcode=$row3['postcode'];
		$address1=$row3['street'];

		$array = preg_split('/$\R?^/m', $row3['street']);

		if (count($array)==2) {
			$address1=$array[0];
			$address2=$array[1];

		}else {
			$address1=$row3['street'];

		}

		$country=$row3['country_id'];
		$country_div=$row3['region'];
	}


	return array($address1,$address2,$town,$postcode,$country_div,$country);

}

?>
