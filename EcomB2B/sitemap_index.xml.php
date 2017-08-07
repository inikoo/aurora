<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
    Refurnished: 7 August 2017 at 15:18:52 CEST, Tranava, Slovakia

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once 'common.php';


$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
$sql=sprintf("select `Sitemap Key` ,`Sitemap Date` from `Sitemap Dimension` where `Sitemap Website Key`=%d",
	$website_key
);

	$site_protocol='https';

	if ($result=$db->query($sql)) {
			foreach ($result as $row) {
                $xml .= '  <sitemap>' . "\n";
                $xml .= '    <loc>https://'. $website->get('Website URL').'/sitemap'.$row['Sitemap Key'].'.xml</loc>' . "\n";
                $xml .= '    <lastmod>' . date('Y-m-d', strtotime($row['Sitemap Date'])) . '</lastmod>' . "\n";
                $xml .= '  </sitemap>' . "\n";
			}
	}else {
			print_r($error_info=$db->errorInfo());
			print "$sql\n";
			exit;
	}



$xml .= '</sitemapindex>' . "\n";

header("Content-Type:text/xml");
print $xml;

?>
