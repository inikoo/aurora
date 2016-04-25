<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 21 April 2016 at 14:02:21 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 20156 Inikoo

 Version 3

*/

function get_currencies($db) {

	$data=array();
	$sql=sprintf("select `Currency Name`,`Currency Code`,`Currency Symbol`,`Currency Country 2 Alpha Code` from kbase.`Currency Dimension` where `Currency Status`='Active' ");

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$data[]=array(
				'name'=>_($row['Currency Name']).' ('.$row['Currency Code'].')',
				'iso2'=>strtolower($row['Currency Country 2 Alpha Code']),
				'code'=>$row['Currency Code'],

			);
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	usort($data, "cmp");

	$formated_data='[ ';
	foreach ($data as $key=>$value) {
		$formated_data.=sprintf('{name:"%s",iso2:"%s",code:"%s"},', $value['name'], $value['iso2'], $value['code']);
	}
	$formated_data=preg_replace('/\,$/', '', $formated_data);
	$formated_data.=']';
	;
	return $formated_data;

}


function get_countries($db) {

	$data=array();
	$sql=sprintf("select `Country Name`,`Country Local Name`,`Country Code`,`Country Currency Code`,`Country 2 Alpha Code` from kbase.`Country Dimension` where `Country Display Address Field`='Yes' ");

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$name=_($row['Country Name']);
			$data[]=array(
				'name'=>($name==$row['Country Local Name'] ?$name:$name.' ('.$row['Country Local Name'].')'),
				'iso2'=>strtolower($row['Country 2 Alpha Code']),
				'code'=>$row['Country Code'],
				'currency'=>$row['Country Currency Code'],
			);
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	usort($data, "cmp");

	$formated_data='[ ';
	foreach ($data as $key=>$value) {
		$formated_data.=sprintf('{name:"%s",iso2:"%s",code:"%s"},', $value['name'], $value['iso2'], $value['code']);
	}
	$formated_data=preg_replace('/\,$/', '', $formated_data);

	$formated_data.=']';
	return $formated_data;

}



function cmp($a, $b) {
	if ($a['name'] == $b['name']) {
		return 0;
	}
	return ($a['name'] < $b['name']) ? -1 : 1;
}


?>
