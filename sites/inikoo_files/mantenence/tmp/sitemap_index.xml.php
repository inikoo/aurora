<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once 'common.php';


$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
$sql=sprintf("select `Sitemap Key` ,`Sitemap Date` from `Sitemap Dimension` where `Sitemap Site Key`=%d",
	$site->id
);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$xml .= '  <sitemap>' . "\n";
	$xml .= '    <loc>http://'. $site->data['Site URL'].'/sitemap'.$row['Sitemap Key'].'.xml</loc>' . "\n";
	$xml .= '    <lastmod>' . date('Y-m-d', strtotime($row['Sitemap Date'])) . '</lastmod>' . "\n";
	$xml .= '  </sitemap>' . "\n";

}

$xml .= '</sitemapindex>' . "\n";

header("Content-Type:text/xml");
print $xml;

?>
