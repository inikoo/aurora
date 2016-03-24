<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 21 January 2016 at 12:25:20 GMT+8, Kuala Lumpur, Malaysia

 Version 2.0
*/
chdir('../');
require_once 'keyring/dns.php';
require_once 'keyring/key.php';
include_once 'utils/i18n.php';

require_once 'utils/general_functions.php';

$mem = new Memcached();
$mem->addServer($memcache_ip, 11211);

$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd , array(\PDO::MYSQL_ATTR_INIT_COMMAND =>"SET time_zone = '+0:00';"));
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


if (isset($_REQUEST['locale'])) {
	$locale=$_REQUEST['locale'];
}else {
	$locale='en_GB.UTF-8';
}

set_locale($locale);
$countries_data=array();
$sql=sprintf('select `Country Name`,`Country Local Name`,`Country 2 Alpha Code`,`Country Telephone Code`,`Country Telephone Code Metadata` from kbase.`Country Dimension` where `Country Display Address Field`="Yes"');
if ($result=$db->query($sql)) {

	foreach ($result as $data) {
		$countries_data[]=array(
			'name'=>_trim(_($data['Country Name'])),
			'local_name'=>   $data['Country Local Name'],
			'code'=>$data['Country 2 Alpha Code'],
			'telephone_code'=>$data['Country Telephone Code'],
			'telephone_code_metadata'=>$data['Country Telephone Code Metadata'],
		);

	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}

function cmp($a, $b) {
	if ($a['name'] == $b['name']) {
		return 0;
	}
	return ($a['name'] < $b['name']) ? -1 : 1;
}


usort($countries_data, "cmp");

$allCountries='var allCountries = [ ';
foreach ($countries_data as $country_data) {

	//print_r($country_data);

	if (preg_match('/^(\d+)$/', $country_data['telephone_code_metadata'], $matches)) {


		$priority=$matches[1];
		$area_codes='';
	}elseif (preg_match('/^(\d+),\[(.+)\]$/', $country_data['telephone_code_metadata'], $matches)) {
		

		$priority=$matches[1];
		$area_codes=' [ "'.preg_replace('/\,/','","',$matches[2]).'" ]';
		
	}else {
		$priority='';
		$area_codes='';
	}

	$allCountries.=sprintf('[ "%s%s", "%s", "%s"%s%s ], ',
		$country_data['name'], (($country_data['local_name']!='' and  $country_data['local_name']!=$country_data['name'])?' ('.$country_data['local_name'].')':''),
		strtolower($country_data['code']),
		$country_data['telephone_code'],
		($priority!=''?', '.$priority:''),
		($area_codes!=''?' ,'.$area_codes:'')
	);
}

$allCountries=preg_replace('/\, $/', '', $allCountries);
$allCountries.=']';
header('Content-Type: application/javascript');

print $allCountries;


?>
