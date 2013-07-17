<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once 'common.php';

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$sitemap_key=$_REQUEST['id'];

} else {
	exit("error, no sitemap key");
}


$sql=sprintf("select `Sitemap Content` from  `Sitemap Dimension` where `Sitemap Key`=%d",
	$sitemap_key
);
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	$xml = $row['Sitemap Content'];

}else{
$xml=false;
}


header("Content-Type:text/xml");
print $xml;


?>
