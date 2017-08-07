<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
    Refurnished: 7 August 2017 at 15:13:54 CEST, Tranava, Slovakia

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once 'common.php';


if(isset($_REQUEST['id'])){
    $sitemap_key=$_REQUEST['id'];
}else{

    $sql = sprintf(
        "SELECT `Sitemap Key`  AS sitemap_key ,`Sitemap Date` FROM `Sitemap Dimension` WHERE `Sitemap Website Key`=%d LIMIT 1", $website_key
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $sitemap_key = $row['sitemap_key'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }
}



$sql = sprintf(
    "SELECT `Sitemap Content` FROM  `Sitemap Dimension` WHERE `Sitemap Key`=%d", $sitemap_key
);

if ($result=$db->query($sql)) {
    if ($row = $result->fetch()) {
        $xml = $row['Sitemap Content'];
	}else{
        $xml = false;
    }
}else {
	print_r($error_info=$db->errorInfo());
	print "$sql\n";
	exit;
}


header("Content-Type:text/xml");
print $xml;
exit;

?>
