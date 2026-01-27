<?php

/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 2020-11-30T14:36:23+08:00 Kuala Lumpur

 Copyright (c) 2017, Inikoo

 Version 2.0
*/
error_reporting(E_ALL ^ E_DEPRECATED);

require_once '../vendor/autoload.php';

require 'keyring/dns.php';
require 'keyring/au_deploy_conf.php';

require_once 'utils/sentry.php';

include_once 'utils/natural_language.php';
include_once 'utils/general_functions.php';
include_once 'utils/public_object_functions.php';
include_once 'utils/network_functions.php';
include_once 'utils/aes.php';
include_once 'utils/web_common.php';


$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);

if (session_id() == '' || !isset($_SESSION)) {
    session_start();
}


if (empty($_SESSION['website_key'])) {
    include_once('utils/find_website_key.include.php');
    $_SESSION['website_key'] = get_website_key_from_domain($redis);
}


require_once 'keyring/key.php';

if (!isset($db) or is_null($db)) {
    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}

$website = get_object('Website', $_SESSION['website_key']);
$store   = get_object('Store', $website->get('Website Store Key'));


$sql = "select `Product Code`,`Product Name`,
(select concat('[image_address]',`Image Subject Image Key`)  from `Image Subject Bridge` where `Image Subject Object`='Product' and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`='Yes'  order by `Image Subject Order` limit 1 offset 0) as img1,
       (select concat('[image_address]',`Image Subject Image Key`)  from `Image Subject Bridge` where `Image Subject Object`='Product' and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`='Yes'  order by `Image Subject Order` limit 1 offset 1) as img2,
    (select concat('[image_address]',`Image Subject Image Key`)  from `Image Subject Bridge` where `Image Subject Object`='Product' and `Image Subject Object Key`=P.`Product ID` and `Image Subject Is Public`='Yes'  order by `Image Subject Order` limit 1 offset 2) as img3 ,
     
       
       `Product Published Webpage Description` ,`Product Units Per Case`,`Product Price`,`Product Availability`,
       `Product RRP`,`Product Unit Weight`,`Product Origin Country Code`,`Product Tariff Code`,`Product Barcode Number`,`Product Brand`
from `Product Dimension` P 

where `Product Store Key`=? and `Product Web State` in ('For Sale','Out of Stock')  and  `Product Price`>0  ";


$placeholders = array(
    '[image_address]' => 'https://'.$website->get('Website URL').'/wi.php?id='
);

$sql = strtr($sql, $placeholders);

$data = [];
$stmt = $db->prepare($sql);
$stmt->execute(
    array(
        $website->get('Website Store Key')
    )
);


while ($row = $stmt->fetch()) {
    $short_product_description = $row['Product Published Webpage Description'];
    $short_product_description = trim(preg_replace('/\s\s+/', ' ', $short_product_description));

    $short_product_description = str_replace("\r\n", " ", $short_product_description);
    $short_product_description = str_replace("\n", " ", $short_product_description);

    $short_product_description = preg_replace("/<p[^>]*?>/", "", $short_product_description);
    $short_product_description = str_replace("</p>", "<br />", $short_product_description);


    if ($row['Product Units Per Case'] == 0) {
        $row['Product Units Per Case'] = 1;
    }

    $data[] = [
        'market_place_allocation'                 => 'EU',
        'item_number'                             => $row['Product Code'],
        'ean_code'                                => $row['Product Barcode Number'],
        'ean_ve'                                  => '',
        'product_description'                     => $row['Product Name'],
        'short_product_description'               => ($short_product_description == '' ? $row['Product Name'] : $short_product_description),
        'detailed_product_description'            => '',
        'brand_name'                              => $row['Product Brand'],
        'image'                                   => $row['img1'],
        'image2'                                  => $row['img2'],
        'image3'                                  => $row['img3'],
        'currency'                                => $store->get('Store Currency Code'),
        'VAT'                                     => '23',
        'quantity_of_units_per_package'           => $row['Product Units Per Case'],
        'minimum_order_quantity_in_packing_units' => 1,
        'net_price_per_unit'                      => $row['Product Price'] / $row['Product Units Per Case'],
        'promotion_discount'                      => '',
        'volumedbasedpricing_quantity1'           => '',
        'volumebasedpricing_price1'               => '',
        'volumedbasedpricing_quantity2'           => '',
        'volumedbasedpricing_quantity3'           => '',
        'available_quantity_in_packing_units'     => floor($row['Product Availability']),
        'promotion_discount'                      => '',
        'recommended_retail_price'                => $row['Product RRP'] / $row['Product Units Per Case'],
        'activ_until'                             => '',
        'weight'                                  => $row['Product Unit Weight'],
        'collection'                              => '',
        'statistical_number'                      => $row['Product Tariff Code'],
        'country_of_origin'                       => $row['Product Origin Country Code'],
        'dangerous_goods'                         => '',
        'energyEfficiency'                        => '',
        'energyEffImage'                          => ''


    ];
}


//print_r($data);
outputCsv('items.csv', $data);


function outputCsv($fileName, $assocDataArray)
{
    ob_clean();
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename='.$fileName);
    if (isset($assocDataArray['0'])) {
        $fp = fopen('php://output', 'w');
        fputcsv($fp, array_keys($assocDataArray['0']), ';');
        foreach ($assocDataArray as $values) {
            fputcsv($fp, $values, ';');
        }
        fclose($fp);
    }
    ob_flush();
}
