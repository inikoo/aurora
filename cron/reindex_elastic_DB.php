<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  01 December 2019  17:34::02  +0100, Mijas Costa, Spain

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

require 'common.php';
require_once 'utils/natural_language.php';
require_once 'class.Invoice.php';

require 'vendor/autoload.php';

use Elasticsearch\ClientBuilder;



$params         = ['body' => []];
$global_counter = 0;

$client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();


array_shift($argv);
if (count($argv) > 0) {
    $objects = $argv;
} else {
    $objects = [
        'categories',
        'customers',
        'parts',
        'products',
        'mailshots',
        'deals',
        'deal_components',
        'deal_campaigns',
        'lists',
        'delivery_notes',
        'invoices',
        'deleted_invoices',
        'payments',
        'users',
        'staff',

        'suppliers',
        'agents',
        'supplier_products',
        'webpages',
        'prospects',
        'orders',
        'locations'
    ];

}

if (in_array('customers', $objects)) {
    update_customers_index($db);
}
if (in_array('categories', $objects)) {
    update_categories_index($db);
}
if (in_array('parts', $objects)) {
    update_parts_index($db);
}
if (in_array('products', $objects)) {
    update_products_index($db);
}
if (in_array('mailshots', $objects)) {
    update_mailshots_index($db);
}
if (in_array('deal_components', $objects)) {
    update_deal_components_index($db);
}
if (in_array('deals', $objects)) {
    update_deals_index($db);
}
if (in_array('deal_campaigns', $objects)) {
    update_deal_campaigns_index($db);
}

if (in_array('lists', $objects)) {
    update_lists_index($db);
}

if (in_array('delivery_notes', $objects)) {
    update_delivery_notes_index($db);
}

if (in_array('invoices', $objects)) {
    update_invoices_index($db);
}
if (in_array('deleted_invoices', $objects)) {
    update_deleted_invoices_index($db);
}
if (in_array('payments', $objects)) {
    update_payments_index($db);
}
if (in_array('users', $objects)) {
    update_users_index($db);
}
if (in_array('staff', $objects)) {
    update_staff_index($db);
}

if (in_array('suppliers', $objects)) {
    update_suppliers_index($db);
}
if (in_array('agents', $objects)) {
    update_agents_index($db);
}
if (in_array('supplier_products', $objects)) {
    update_supplier_products_index($db);
}
if (in_array('webpages', $objects)) {
    update_webpages_index($db);
}
if (in_array('prospects', $objects)) {
    update_prospects_index($db);
}
if (in_array('orders', $objects)) {
    update_orders_index($db);
}
if (in_array('locations', $objects)) {
    update_locations_index($db);
}

if (in_array('isf', $objects)) {
    update_isf_index($db);
}

if (!empty($params['body'])) {

    $responses = $client->bulk($params);

}


function process_indexing($indexer) {
    global $params, $client;
    global $global_counter;


    if($_index_body = $indexer->get_index_body()) {


        $global_counter++;
        foreach ($indexer->get_index_header() as $_key => $index_header) {

            $params['body'][] = [
                'index' => [
                    '_index' => $index_header['index'],
                    '_id'    => $index_header['id']
                ]
            ];


            $params['body'][] = $_index_body[$_key];
        }

    }


    if ($global_counter > 0 && $global_counter % 200 == 0) {

        $responses = $client->bulk($params);

        $params = ['body' => []];
        unset($responses);
    }
}

/**
 * @param $db \PDO
 */
function update_mailshots_index($db) {


    $object_name = 'Email Campaigns';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Email Campaign Key` from `Email Campaign Dimension` ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Email Campaign', $row['Email Campaign Key']);
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
function update_deals_index($db) {


    $object_name = 'Deals';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Deal Key` from `Deal Dimension`  where `Deal Number Components`=1 ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Deal', $row['Deal Key']);
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
function update_isf_index($db) {

    global $params, $client;
    global $global_counter;

    $object_name = 'ISF';
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;

    $chunk_size=100000;
    $offset=0;
    while($offset<$total){

        $limit ="limit $offset , $chunk_size";


        $offset=$offset+$chunk_size;

        $sql  = "select `Date`,P.`Part SKU`,L.`Location Key`,`Location Code` ,`Part Reference`,`Quantity On Hand`,`Value At Cost`,`Value Commercial` from `Inventory Spanshot Fact` ISF  left join `Part Dimension` P on (P.`Part SKU`=ISF.`Part SKU`)  left join `Location Dimension` L on (L.`Location Key`=ISF.`Location Key`) $limit";


        $stmt = $db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch()) {

            $global_counter++;

            $params['body'][] = [
                'index' => [
                    '_index' => 'au_isf_'.strtolower(DNS_ACCOUNT_CODE),
                ]
            ];


            $params['body'][] = [
                'tenant'=>strtolower(DNS_ACCOUNT_CODE),
                'date'=>$row['Date'],
                'sku'=>$row['Part SKU'],
                'location_key'=>$row['Location Key'],
                'part'=>$row['Part Reference'],
                'location'=>$row['Location Code'],
                'qty'=>$row['Quantity On Hand'],
                'cost_paid'=>$row['Value At Cost'],
                'value'=>$row['Value Commercial'],

            ];



            if ($global_counter > 0 && $global_counter % 1000 == 0) {

                $responses = $client->bulk($params);

                $params = ['body' => []];
                unset($responses);
            }


            $contador++;
            if ($print_est) {
                print_lap_times($object_name, $contador, $total, $lap_time0);
            }
        }



    }








    print "\n";
}


/**
 * @param $db \PDO
 */
function update_deal_campaigns_index($db) {


    $object_name = 'Deal Campaigns';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Deal Campaign Key` from `Deal Campaign Dimension`  ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Deal_Campaign', $row['Deal Campaign Key']);
        process_indexing($object->index_elastic_search($hosts, true));
        $contador++;
        if ($print_est) {
            print_lap_times($object_name, $contador, $total, $lap_time0);
        }
    }
    print "\n";
}


function update_deal_components_index($db) {


    $object_name = 'Deal Components';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Deal Key` from `Deal Dimension`  where `Deal Number Components`>1  ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {

        $deal = get_object('Deal', $row['Deal Key']);

        foreach ($deal->get_deal_components('objects', 'All') as $object) {
            process_indexing($object->index_elastic_search($hosts, true));

        }

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
function update_staff_index($db) {


    $object_name = 'Staff';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Staff Key` from `Staff Dimension`  ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Staff', $row['Staff Key']);
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
    $total       = get_total_objects($db, $object_name);

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


    $sql  = "select `Part SKU` from `Part Dimension` where `Part Reference`='mol-09' ";
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
function update_locations_index($db) {

    $object_name = 'Locations';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Location Key` from `Location Dimension` order by `Location Key` desc  ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Location', $row['Location Key']);
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
function update_suppliers_index($db) {

    $object_name = 'Suppliers';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Supplier Key` from `Supplier Dimension` order by `Supplier Key` desc ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Supplier', $row['Supplier Key']);
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
function update_agents_index($db) {

    $object_name = 'Agents';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Agent Key` from `Agent Dimension` order by `Agent Key` desc ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Agent', $row['Agent Key']);
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
function update_supplier_products_index($db) {

    $object_name = 'Supplier Parts';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;


    $sql  = "select `Supplier Part Key` from `Supplier Part Dimension` order by `Supplier Part Key` desc ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Supplier Part', $row['Supplier Part Key']);
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


    $sql = "select `Customer Key` from `Customer Dimension` order by `Customer Key` desc ";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {

        $object = get_object('Customer', $row['Customer Key']);
        process_indexing(
            $object->index_elastic_search(
                $hosts, true, [
                'quick',
                'favourites',
                'assets',
                'assets_interval'
            ]
            )
        );

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


/**
 * @param $db \PDO
 */
function update_users_index($db) {
    $object_name = 'Users';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;

    $sql  = "select `User Key` from `User Dimension` order by `User Key` desc ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('User', $row['User Key']);
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
function update_deleted_invoices_index($db) {
    $object_name = 'Deleted_Invoices';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;

    $sql  = "select `Invoice Deleted Key` from `Invoice Deleted Dimension` order by `Invoice Deleted Key` desc ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = new Invoice('deleted', $row['Invoice Deleted Key']);
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
function update_invoices_index($db) {
    $object_name = 'Invoices';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;

    $sql  = "select `Invoice Key` from `Invoice Dimension` order by `Invoice Key` desc ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Invoice', $row['Invoice Key']);
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
function update_delivery_notes_index($db) {
    $object_name = 'Delivery Notes';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;

    $sql  = "select `Delivery Note Key` from `Delivery Note Dimension` order by `Delivery Note Key` desc ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Delivery Note', $row['Delivery Note Key']);
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
function update_payments_index($db) {
    $object_name = 'Payments';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;

    $sql  = "select `Payment Key` from `Payment Dimension` order by `Payment Key` desc ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('Payment', $row['Payment Key']);
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
function update_lists_index($db) {
    $object_name = 'Lists';
    $hosts       = get_ES_hosts();
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;

    $sql  = "select `List Key` from `List Dimension` order by `List Key` desc ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = get_object('List', $row['List Key']);
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
        )."m  ($contador/$total)  t: ".sprintf("%.2f", $lap_time1 - $lap_time0)."s  \r";

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
        case 'Locations':
            $sql = "select count(*) as num from `Location Dimension`";
            break;
        case 'Suppliers':
            $sql = "select count(*) as num from `Supplier Dimension`";
            break;
        case 'Agents':
            $sql = "select count(*) as num from `Agent Dimension`";
            break;
        case 'Supplier Parts':
            $sql = "select count(*) as num from `Supplier Part Dimension`";
            break;
        case 'Staff':
            $sql = "select count(*) as num from `Staff Dimension`";
            break;
        case 'Users':
            $sql = "select count(*) as num from `User Dimension`";
            break;
        case 'Invoices':
            $sql = "select count(*) as num from `Invoice Dimension`";
            break;
        case 'Deleted_Invoices':
            $sql = "select count(*) as num from `Invoice Deleted Dimension`";
            break;
        case 'Delivery Notes':
            $sql = "select count(*) as num from `Delivery Note Dimension`";
            break;
        case 'Payments':
            $sql = "select count(*) as num from `Payment Dimension`";
            break;
        case 'Lists':
            $sql = "select count(*) as num from `List Dimension`";
            break;
        case 'Deals':
            $sql = "select count(*) as num from `Deal Dimension`  where `Deal Number Components`=1  ";
            break;
        case 'Deal Components':
            $sql = "select count(*) as num from `Deal Dimension`  where `Deal Number Components`>1  ";
            break;
        case 'Deal Campaigns':
            $sql = "select count(*) as num from `Deal Campaign Dimension`";
            break;
        case 'Email Campaigns':
            $sql = "select count(*) as num from `Email Campaign Dimension`";
            break;
        case 'ISF':
            $sql = "select count(*) as num from `Inventory Spanshot Fact`";
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

