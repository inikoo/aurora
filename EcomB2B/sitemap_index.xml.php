<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
    Refurnished: 7 August 2017 at 15:18:52 CEST, Tranava, Slovakia

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once __DIR__.'/common.php';


$xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
$xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
$sql = "select `Sitemap Name` ,`Sitemap Date` from `Sitemap Dimension` where `Sitemap Website Key`=?";

$stmt = $db->prepare($sql);
$stmt->execute(
    array(
        $website_key
    )
);
while ($row = $stmt->fetch()) {
    $xml .= '  <sitemap>'."\n";
    $xml .= '    <loc>https://'.$website->get('Website URL').'/'.preg_replace('/-1\.xml\.gz/', '', $row['Sitemap Name']).'.xml</loc>'."\n";
    $xml .= '    <lastmod>'.date('Y-m-d', strtotime($row['Sitemap Date'])).'</lastmod>'."\n";
    $xml .= '  </sitemap>'."\n";
    }



$xml .= '</sitemapindex>'."\n";

header("Content-Type:text/xml");
print $xml;


