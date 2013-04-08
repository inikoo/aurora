<?php


function get_address($address_id) {

	$address1='';
	$address2='';
	$town='';
	$postcode='';
	$country_div='';
	$country='';

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity_varchar` WHERE `attribute_id` = 24 AND `entity_id` =%d",$address_id);
	$res3=mysql_query($sql);
	if ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$town=$row3['value'];
	}

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity_varchar` WHERE `attribute_id` = 24 AND `entity_id` =%d",$address_id);
	$res3=mysql_query($sql);
	if ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$country=$row3['value'];
	}
	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity_varchar` WHERE `attribute_id` = 25 AND `entity_id` =%d",$address_id);
	$res3=mysql_query($sql);
	if ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$country=$row3['value'];
	}
	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity_varchar` WHERE `attribute_id` = 26 AND `entity_id` =%d",$address_id);
	$res3=mysql_query($sql);
	if ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$country_div=$row3['value'];
	}
	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity_varchar` WHERE `attribute_id` = 28 AND `entity_id` =%d",$address_id);
	$res3=mysql_query($sql);
	if ($row3=mysql_fetch_assoc($res3)) {
		if ($row3['value']!='')
			$postcode=$row3['value'];
	}

	$sql=sprintf("SELECT * FROM ancient_dropshipnew.`customer_address_entity_text` WHERE `attribute_id` = 23 AND `entity_id` =%d",$address_id);
	$res3=mysql_query($sql);
	if ($row3=mysql_fetch_assoc($res3)) {

		$address1=$row3['value'];

		$array = preg_split('/$\R?^/m', $row3['value']);

		if (count($array)==2) {
			$address1=$array[0];
			$address2=$array[1];

		}else {
			$address1=$row3['value'];

		}
	}
	
	return array($address1,$address2,$town,$postcode,$country_div,$country);
	
}

?>
