<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  01 December 2019  17:34::02  +0100, Mijas Costa, Spain

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

require_once __DIR__.'/cron_common.php';
require_once 'utils/natural_language.php';
require_once 'class.Invoice.php';

require 'vendor/autoload.php';

use Elasticsearch\ClientBuilder;


$params         = ['body' => []];
$global_counter = 0;

$client = ClientBuilder::create()->setHosts(get_elasticsearch_hosts())
    ->setApiKey(ES_KEY1,ES_KEY2)
    ->setSSLVerification(ES_SSL)
    ->build();


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
    if (!empty($params['body'])) {
        // print_r($params);
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('categories', $objects)) {
    update_categories_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('parts', $objects)) {
    update_parts_index($db);

    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }

}
if (in_array('products', $objects)) {
    update_products_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('mailshots', $objects)) {
    update_mailshots_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('deal_components', $objects)) {
    update_deal_components_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('deals', $objects)) {
    update_deals_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('deal_campaigns', $objects)) {
    update_deal_campaigns_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}

if (in_array('lists', $objects)) {
    update_lists_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}

if (in_array('delivery_notes', $objects)) {
    update_delivery_notes_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}

if (in_array('invoices', $objects)) {
    update_invoices_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('deleted_invoices', $objects)) {
    update_deleted_invoices_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('payments', $objects)) {
    update_payments_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('users', $objects)) {
    update_users_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('staff', $objects)) {
    update_staff_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}

if (in_array('suppliers', $objects)) {
    update_suppliers_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('agents', $objects)) {
    update_agents_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('supplier_products', $objects)) {
    update_supplier_products_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('webpages', $objects)) {
    update_webpages_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('prospects', $objects)) {
    update_prospects_index($db);

    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('orders', $objects)) {
    update_orders_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}
if (in_array('locations', $objects)) {
    update_locations_index($db);

    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}

if (in_array('isf', $objects)) {
    update_isf_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}

if (in_array('wisf', $objects)) {
    update_wisf_index($db);
    if (!empty($params['body'])) {
        add_indices($client, $params);
        $params = ['body' => []];
    }
}

if (!empty($params['body'])) {

    add_indices($client, $params);
    $params = ['body' => []];
}


function process_indexing($indexer) {
    global $params, $client;
    global $global_counter;


    $_index_body = $indexer->get_index_body();

    if (is_array($_index_body) and count($_index_body) > 0) {


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


    if ($global_counter > 0 && $global_counter % 1000 == 0 and count($params['body']) > 0) {

        add_indices($client, $params);

        $params = ['body' => []];

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
    return;

    global $params, $client;
    global $global_counter;

    $object_name = 'ISF';
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;

    $chunk_size = 5000;
    $offset     = 0;


    $warehouse = get_object('Warehouse', 1);

    $from = date("Y-m-d", strtotime($warehouse->get('Warehouse Valid From')));
    $to   = date("Y-m-d", strtotime('now'));



    $sql = sprintf(
        "SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=%s AND `Date`<=%s ORDER BY `Date` DESC", prepare_mysql($from), prepare_mysql($to)
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row2) {


            $offset = $offset + $chunk_size;


            $sql = "select `Quantity In`,`Quantity Sold`,`Sold Amount`,`Location Warehouse Key`, `Quantity Lost`, `Inventory Spanshot Amount In PO`,`Inventory Spanshot Amount In Other`,`Inventory Spanshot Amount Out Sales`,`Inventory Spanshot Amount Out Other`,  `Date`,ISF.`Part SKU`,ISF.`Location Key`,`Location Code` ,`Part Reference`,`Quantity On Hand`,`Value At Cost`,`Value Commercial`,`Value At Day Cost` from `Inventory Spanshot Fact` ISF  
    left join `Part Dimension` P on (P.`Part SKU`=ISF.`Part SKU`)  left join `Location Dimension` L on (L.`Location Key`=ISF.`Location Key`) 
where `Date`=?";


            $stmt = $db->prepare($sql);
            $stmt->execute(
                [
                    $row2['Date']
                ]
            );

            while ($row = $stmt->fetch()) {



                $global_counter++;

                $params['body'][] = [
                    'index' => [
                        '_index' => 'au_part_location_isf_'.strtolower(DNS_ACCOUNT_CODE),
                        '_id'    => DNS_ACCOUNT_CODE.'.'.$row['Part SKU'].'_'.$row['Location Key'].'.'.$row['Date'],

                    ]
                ];


                $params['body'][] = [
                    'tenant'          => strtolower(DNS_ACCOUNT_CODE),
                    'date'            => $row['Date'],
                    '1st_day_year'    => (preg_match('/\d{4}-01-01/ ', $row['Date']) ? true : false),
                    '1st_day_month'   => (preg_match('/\d{4}-\d{2}-01/', $row['Date']) ? true : false),
                    '1st_day_quarter' => (preg_match('/\d{4}-(01|04|07|10)-01/', $row['Date']) ? true : false),
                    '1st_day_week'    => (gmdate('w', strtotime($row['Date'])) == 0 ? true : false),
                    'sku'             => $row['Part SKU'],
                    'location_key'    => $row['Location Key'],

                    'warehouse' => ($row['Location Warehouse Key']==''?1:$row['Location Warehouse Key']),

                    'stock_on_hand'           => $row['Quantity On Hand'],
                    'stock_cost'              => $row['Value At Cost'],
                    'stock_value_at_day_cost' => $row['Value At Day Cost'],
                    'stock_commercial_value'  => $row['Value Commercial'],


                    'sold_amount' => $row['Sold Amount'],

                    'book_in' => $row['Quantity In'],
                    'sold'    => $row['Quantity Sold'],
                    'lost'    => $row['Quantity Lost'],

                    'stock_value_in_purchase_order' => $row['Inventory Spanshot Amount In PO'],
                    'stock_value_in_other'          => $row['Inventory Spanshot Amount In Other'],
                    'stock_value_out_sales'         => $row['Inventory Spanshot Amount Out Sales'],
                    'stock_value_out_other'         => $row['Inventory Spanshot Amount Out Other'],


                    'part_reference' => $row['Part Reference'],
                    'location_code'  => $row['Location Code'],

                ];


                if ($global_counter > 0 && $global_counter % 1000 == 0) {

                    add_indices($client, $params);

                    $params = ['body' => []];


                }


                $contador++;
                if ($print_est) {
                   print_lap_times($row2['Date'], $contador, $total, $lap_time0);
                }
            }




            $sql = "select `Part Package Description`, `Part Valid From`,`Part Reference`,`Date`,ISF.`Part SKU`, 
       group_concat(DISTINCT `Location Key`) as locations,
              group_concat(DISTINCT `Warehouse Key`) as warehouses,

       sum(`Quantity On Hand`) as stock_on_hand,sum(`Value At Cost`) as stock_cost,sum(`Value Commercial`) as stock_commercial_value,sum(`Value At Day Cost`) as stock_value_at_day_cost ,
       sum(`Quantity Given`) as given,sum(`Quantity In`) as book_in,sum(`Inventory Spanshot Stock Left 1 Year Ago`) stock_left_1_year_ago,`Dormant 1 Year` as no_sales_1_year,
       sum(`Quantity Sold`) as sold,sum(`Quantity Lost`) as lost,`Inventory Spanshot Warehouse SKO Value`,sum(`Sold Amount`) as sold_amount,
       sum(`Inventory Spanshot Amount In PO`) as stock_value_in_purchase_order,
        sum(`Inventory Spanshot Amount In Other`) as stock_value_in_other,
        sum(`Inventory Spanshot Amount Out Sales`) as stock_value_out_sales, 
       sum(`Inventory Spanshot Amount Out Other`) as stock_value_out_other
       

from `Inventory Spanshot Fact` ISF 
        left join `Part Dimension` P on (P.`Part SKU`=ISF.`Part SKU`) 
                where `Date`=?  group by ISF.`Part SKU`";


            $stmt = $db->prepare($sql);
            $stmt->execute(
                [
                    $row2['Date']
                ]
            );
            $_datagms_1_year_back = gmdate('U', strtotime($row['Date'].' - 1 year'));
            $_parts=0;
            while ($row = $stmt->fetch()) {

                $_parts++;


                $global_counter++;

                $params['body'][] = [
                    'index' => [
                        '_index' => 'au_part_isf_'.strtolower(DNS_ACCOUNT_CODE),
                        '_id'    => DNS_ACCOUNT_CODE.'.'.$row['Part SKU'].'.'.$row['Date'],

                    ]
                ];

                if (gmdate('U', strtotime($row['Part Valid From'])) > $_datagms_1_year_back) {
                    $no_sales_1_year_icon = 'fal fa-seedling';
                } else {
                    switch ($row['no_sales_1_year']) {
                        case 'Yes':
                            $no_sales_1_year_icon = 'fa fa-snooze';
                            break;
                        case 'No':
                            $no_sales_1_year_icon = 'fa success fa-check';
                            break;
                        default:
                            $no_sales_1_year_icon = 'error fa fa-question';
                            break;
                    }
                }

                $params['body'][] = [
                    'tenant'          => strtolower(DNS_ACCOUNT_CODE),
                    'date'            => $row['Date'],
                    '1st_day_year'    => (preg_match('/\d{4}-01-01/ ', $row['Date']) ? true : false),
                    '1st_day_month'   => (preg_match('/\d{4}-\d{2}-01/', $row['Date']) ? true : false),
                    '1st_day_quarter' => (preg_match('/\d{4}-(01|04|07|10)-01/', $row['Date']) ? true : false),
                    '1st_day_week'    => (gmdate('w', strtotime($row['Date'])) == 0 ? true : false),
                    'sku'             => $row['Part SKU'],


                    'locations'  => preg_split('/,/', $row['locations']),
                    'warehouses' => preg_split('/,/', $row['warehouses']),


                    'stock_on_hand'           => $row['stock_on_hand'],
                    'stock_cost'              => $row['stock_cost'],
                    'stock_value_at_day_cost' => $row['stock_value_at_day_cost'],
                    'stock_commercial_value'  => $row['stock_commercial_value'],

                    'sold_amount' => $row['sold_amount'],
                    'book_in'     => $row['book_in'],
                    'sold'        => $row['sold'],
                    'lost'        => $row['lost'],

                    'stock_value_in_purchase_order' => $row['stock_value_in_purchase_order'],
                    'stock_value_in_other'          => $row['stock_value_in_other'],
                    'stock_value_out_sales'         => $row['stock_value_out_sales'],
                    'stock_value_out_other'         => $row['stock_value_out_other'],


                    'sko_cost'              => $row['Inventory Spanshot Warehouse SKO Value'],
                    'stock_left_1_year_ago' => $row['stock_left_1_year_ago'],
                    'no_sales_1_year'       => ($row['no_sales_1_year'] == 'Yes' ? true : false),
                    'no_sales_1_year_icon'  => $no_sales_1_year_icon,
                    'part_reference'        => $row['Part Reference'],
                    'part_description'      => $row['Part Package Description'],

                ];


                if ($global_counter > 0 && $global_counter % 1000 == 0) {

                    add_indices($client, $params);

                    $params = ['body' => []];


                }


                if ($print_est) {
                  print_lap_times($row2['Date'], $contador, $total, $lap_time0);
                }
            }
        }

    }


    print "\n";
}


/**
 * @param $db \PDO
 */
function update_wisf_index($db) {
    return;
    global $params, $client;
    global $global_counter;

    $object_name = 'WISF';
    $print_est   = true;

    $total     = get_total_objects($db, $object_name);
    $lap_time0 = microtime_float();
    $contador  = 0;

    $chunk_size = 5000;
    $offset     = 0;


    $warehouse = get_object('Warehouse', 1);

    $from = date("Y-m-d", strtotime($warehouse->get('Warehouse Valid From')));
    $to   = date("Y-m-d", strtotime('now'));


    $sql = sprintf(
        "SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=%s AND `Date`<=%s ORDER BY `Date` DESC", prepare_mysql($from), prepare_mysql($to)
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row2) {


            $offset = $offset + $chunk_size;

            $sql = "select * from `Inventory Warehouse Spanshot Fact` where `Date`=?";


            $stmt = $db->prepare($sql);
            $stmt->execute(
                [
                    $row2['Date']
                ]
            );
            while ($row = $stmt->fetch()) {

                $global_counter++;

                $params['body'][] = [
                    'index' => [
                        '_index' => 'au_warehouse_isf_'.strtolower(DNS_ACCOUNT_CODE),
                        '_id'    => DNS_ACCOUNT_CODE.'.'.$row['Date'],

                    ]
                ];


                $params['body'][] = [
                    'tenant'          => strtolower(DNS_ACCOUNT_CODE),
                    'date'            => $row['Date'],
                    '1st_day_year'    => (preg_match('/\d{4}-01-01/ ', $row['Date']) ? true : false),
                    '1st_day_month'   => (preg_match('/\d{4}-\d{2}-01/', $row['Date']) ? true : false),
                    '1st_day_quarter' => (preg_match('/\d{4}-(01|04|07|10)-01/', $row['Date']) ? true : false),
                    '1st_day_week'    => (gmdate('w', strtotime($row['Date'])) == 0 ? true : false),


                    'parts'     => $row['Parts'],
                    'locations' => $row['Locations'],

                    'stock_cost'              => $row['Value At Cost'],
                    'stock_value_at_day_cost' => $row['Value At Day Cost'],
                    'stock_commercial_value'  => $row['Value Commercial'],

                    'stock_value_in_purchase_order' => $row['Inventory Warehouse Spanshot In PO'],
                    'stock_value_in_other'          => $row['Inventory Warehouse Spanshot In Other'],
                    'stock_value_out_sales'         => $row['Inventory Warehouse Spanshot Out Sales'],
                    'stock_value_out_other'         => $row['Inventory Warehouse Spanshot Out Other'],


                    'stock_value_dormant_1y'   => $row['Dormant 1 Year Value At Day Cost'],
                    'parts_with_no_sales_1y'   => $row['Inventory Warehouse Spanshot Fact Dormant Parts'],
                    'parts_with_stock_left_1y' => $row['Inventory Warehouse Spanshot Fact Stock Left 1 Year Parts'],


                ];


                if ($global_counter > 0 && $global_counter % 1000 == 0) {

                    add_indices($client, $params);

                    $params = ['body' => []];


                }


                $contador++;
                if ($print_est) {
                    print_lap_times($row2['Date'], $contador, $total, $lap_time0);
                }
            }
        }

    }


    print "\n";
}

function add_indices($client, $params) {
    $results = $client->bulk($params);


    if (!empty($results['errors'])) {
        print_r($results);
    }


    foreach ($results['items'] as $res) {
        // print $res['index']['result']."\n";


        if (!isset($res['index']['result']) or $res['index']['result'] != 'updated') {
            //  print_r($res);
        }
    }


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
function update_locations_index($db) {


    include_once 'class.Location.php';

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

    $sql  = "select `Location Deleted Key` from `Location Deleted Dimension` order by `Location Deleted Key` desc  ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $object = new Location('deleted', $row['Location Deleted Key']);

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


    $sql = "select `Customer Key` from `Customer Dimension`  order by `Customer Key` desc ";

    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {

        $object = get_object('Customer', $row['Customer Key']);
        process_indexing(
            $object->index_elastic_search(
                $hosts, true, [
                          'quick'

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
        case 'WISF':
            $sql = "select count(*) as num from `Inventory Warehouse Spanshot Fact`";
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

