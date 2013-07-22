<?php
/*



 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once 'common.php';
include_once 'class.Site.php';

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$site_key=$_REQUEST['id'];

} else {
	exit("error, no site key");
}

$site=new Site($site_key);


if (!$site->id) {
	exit("error, no site with this key");
}

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
$sql=sprintf("select `Sitemap Key` ,`Sitemap Date` from `Sitemap Dimension` where `Sitemap Site Key`=%d",
	$site->id
);

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$xml .= '  <sitemap>' . "\n";
	$xml .= '    <loc>' . $site->data['Site URL'].'/sitemap'.$row['Sitemap Key'].'.xml</loc>' . "\n";
	$xml .= '    <lastmod>' . date('Y-m-d', strtotime($row['Sitemap Date'])) . '</lastmod>' . "\n";
	$xml .= '  </sitemap>' . "\n";

}

$xml .= '</sitemapindex>' . "\n";

header("Content-Type:text/xml");
print $xml;


?>
