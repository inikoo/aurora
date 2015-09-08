<?php


function get_address($address_id) {
	global $con_drop;
	$address1='';
	$address2='';
	$town='';
	$postcode='';
	$country_div='';
	$country='';

	$sql=sprintf("SELECT * FROM livedb_upg.`sales_flat_order_address` WHERE `entity_id` =%d",$address_id);
	$res3=mysql_query($sql,$con_drop);
	if ($row3=mysql_fetch_assoc($res3)) {
//print_r($row3);
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
		
		if($country=='GB'){
		$country='United Kingdom';
		}
		
		$country_div=$row3['region'];
	}


	return array($address1,$address2,$town,$postcode,$country_div,$country);

}


function getMagentoAttNumber($dbh,$attribute_code,$entity_type_id) {

	global $con_drop;
	$Att_Got='';
	$sql = "SELECT `attribute_id` FROM livedb_upg.`eav_attribute` WHERE `attribute_code` LIKE '".$attribute_code."' AND `entity_type_id` =".$entity_type_id."  ";
	$res=mysql_query($sql,$con_drop);
	if ($row=mysql_fetch_assoc($res)) {



		$Att_Got=$row['attribute_id'];
	}else{
	    print $sql. "\n";
	    
	    echo mysql_errno($con_drop) . ": " . mysql_error($con_drop). "\n";
	    exit;
	}



	return $Att_Got;

}

?>
