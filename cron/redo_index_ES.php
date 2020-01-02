<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  01 December 2019  17:34::02  +0100, Mijas Costa, Spain

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

require 'common.php';

require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;

print "\n";

$params = ['body' => []];
$global_counter=0;

$client       = ClientBuilder::create()->setHosts(get_ES_hosts())->build();

update_parts_index($db);

update_categories_index($db);
update_products_index($db);

update_prospects_index($db);

update_customers_index($db);
update_orders_index($db);


/*

update_webpages_index($db);

*/
if (!empty($params['body'])) {
    $responses = $client->bulk($params);
}




function process_indexing($indexer){
    global $params,$client;
    global $global_counter;
    $global_counter++;


    $_index_data=$indexer->get_index_header();
    $params['body'][] = [
        'index' => [
            '_index' => $_index_data['index'],
            '_id'    =>  $_index_data['id']
        ]
    ];

    $params['body'][] = $indexer->get_index_body();

    // Every 1000 documents stop and send the bulk request
    if ($global_counter % 1000 == 0) {

        $responses = $client->bulk($params);
        $params = ['body' => []];
        unset($responses);
    }
}


/**
 * @param $db \PDO
 */
function update_categories_index($db) {



    $object_name = 'Categories';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Category Key` from `Category Dimension`  ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Category', $row['Category Key']);
        process_indexing($object->index_elastic_search($hosts, true));
        $contador++;
        if ($print_est) {
            print_lap_times($object_name, $contador, $total, $lap_time0);
        }
    }
    print "\n";
}

/**
 * @param $db \PDO
 */
function update_webpages_index($db) {


    $object_name = 'Webpages';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Page Key` from `Page Store Dimension`  ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Webpage', $row['Page Key']);
        process_indexing($object->index_elastic_search($hosts, true));
        $contador++;
        if ($print_est) {
            print_lap_times($object_name, $contador, $total, $lap_time0);
        }
    }
    print "\n";
}

/**
 * @param $db \PDO
 */
function update_products_index($db) {

    $object_name = 'Products';
    $hosts       = get_ES_hosts();
    $print_est   = true;
    $total     = get_total_objects($db, $object_name);

    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Product ID` from `Product Dimension`  ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Product', $row['Product ID']);
        process_indexing($object->index_elastic_search($hosts, true));
        $contador++;
        if ($print_est) {
            print_lap_times($object_name, $contador, $total, $lap_time0);
        }
    }
    print "\n";
}


/**
 * @param $db \PDO
 */
function update_parts_index($db) {



    $object_name = 'Parts';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Part SKU` from `Part Dimension`  ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Part', $row['Part SKU']);
        process_indexing($object->index_elastic_search($hosts, true));


        $contador++;
        if ($print_est) {
            print_lap_times($object_name, $contador, $total, $lap_time0);
        }
    }
    print "\n";
}


/**
 * @param $db \PDO
 */
function update_orders_index($db) {

    $object_name = 'Orders';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Order Key` from `Order Dimension` order by `Order Key` desc ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Order', $row['Order Key']);
        process_indexing($object->index_elastic_search($hosts, true));
        $contador++;
        if ($print_est) {
            print_lap_times($object_name, $contador, $total, $lap_time0);
        }
    }
    print "\n";
}


/**
 * @param $db \PDO
 */
function update_customers_index($db) {
    $object_name = 'Customers';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Customer Key` from `Customer Dimension` order by `Customer Key` desc ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {

        $object = get_object('Customer', $row['Customer Key']);
        process_indexing($object->index_elastic_search($hosts, true));

        $contador++;
        if ($print_est) {
            print_lap_times($object_name, $contador, $total, $lap_time0);
        }


    }
    print "\n";
}

/**
 * @param $db \PDO
 */
function update_prospects_index($db) {

    $object_name = 'Prospects';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Prospect Key` from `Prospect Dimension` order by `Prospect Key` desc ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {

        $object = get_object('Prospect', $row['Prospect Key']);


        process_indexing($object->index_elastic_search($hosts, true));

        $contador++;
        if ($print_est) {
            print_lap_times($object_name, $contador, $total, $lap_time0);
        }

    }
    print "\n";
}


function print_lap_times($label = '', $contador, $total, $lap_time0) {
    $lap_time1 = microtime_float();
    print $label.'  '.percentage($contador, $total, 3)."  lap time ".sprintf("%.4f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
            "%.4f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 60
        )."m  ($contador/$total)  t: ".sprintf("%.2f", $lap_time1 - $lap_time0 )."s  \r";

}


function get_total_objects($db, $object_name) {

    $total_objects = 0;

    switch ($object_name) {
        case 'Customers':
            $sql = "select count(*) as num from `Customer Dimension`";
            break;
        case 'Prospects':
            $sql = "select count(*) as num from `Prospect Dimension`";
            break;
        case 'Orders':
            $sql = "select count(*) as num from `Order Dimension`";
            break;
        case 'Parts':
            $sql = "select count(*) as num from `Part Dimension`";
            break;
        case 'Webpages':
            $sql = "select count(*) as num from `Page Store Dimension`";
            break;
        case 'Products':
            $sql = "select count(*) as num from `Product Dimension`";
            break;
        case 'Categories':
            $sql = "select count(*) as num from `Category Dimension`";
            break;
        default:
            return $total_objects;
    }

    $stmt = $db->prepare($sql);
    $stmt->execute();
    if ($row = $stmt->fetch()) {
        $total_objects = $row['num'];
    }

    return $total_objects;
}

