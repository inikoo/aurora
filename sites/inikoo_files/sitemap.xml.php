<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once 'common.php';

if(isset($sitemap_key)){

}elseif (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$sitemap_key=$_REQUEST['id'];

} else {

	$sql=sprintf("select `Sitemap Key` ,`Sitemap Date` from `Sitemap Dimension` where `Sitemap Site Key`=%d limit 1",
		$site->id
	);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$sitemap_key=$row['sitemap_key'];
		
	}
}


$sql=sprintf("select `Sitemap Content` from  `Sitemap Dimension` where `Sitemap Key`=%d",
	$sitemap_key
);
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	$xml = $row['Sitemap Content'];

}else {
	$xml=false;
}


header("Content-Type:text/xml");
print $xml;
exit;

?>
