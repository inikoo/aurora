<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  01 December 2019  17:34::02  +0100, Mijas Costa, Spain

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

require 'common.php';
use Elasticsearch\ClientBuilder;

require 'vendor/autoload.php';
include_once 'class.Country.php';

$hosts=array('10.0.0.1');

$client = ClientBuilder::create()->setHosts($hosts)->build();



$sql = "select `Customer Key` from `Customer Dimension` order by `Customer Key` desc ";

$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()){

    $customer = get_object('Customer', $row['Customer Key']);
    $customer->index_elastic_search($hosts);

    print $customer->id."\r";

   // print_r($response);

}

