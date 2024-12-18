<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 January 2016 at 14:53:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/date_functions.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {

    case 'asset_sales':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'     => array('type' => 'string'),
                         'parent_key' => array('type' => 'string'),
                         'from'       => array('type' => 'string'),
                         'to'         => array('type' => 'string')

                     )
        );
        asset_sales($db, $data, $account);
        break;

    case 'part_stock':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'     => array('type' => 'string'),
                         'parent_key' => array('type' => 'string'),
                         'from'       => array('type' => 'string'),
                         'to'         => array('type' => 'string')

                     )
        );
        part_stock($data);
        break;

    case 'csv':
        $data = prepare_values(
            $_REQUEST, array(
                         'id'         => array(
                             'type'     => 'key',
                             'optional' => true
                         ),
                         'type'       => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'parent'     => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'parent_key' => array(
                             'type'     => 'string',
                             'optional' => true
                         ),

                     )
        );
        get_csv_records($db, $data);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function get_csv_records($db, $data) {


    if (!isset($data['id'])) {
        if (isset($data['type']) and isset($data['parent']) and isset($data['parent_key'])) {
            $sql = sprintf(
                'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Type`=%s AND `Timeseries Parent`=%s AND `Timeseries Parent Key`=%d', prepare_mysql($data['type']), prepare_mysql($data['parent']), $data['parent_key']
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $data['id'] = $row['Timeseries Key'];
                } else {
                    $response = array(
                        'state' => 405,
                        'resp'  => 'unable to find timeserie'
                    );
                    echo json_encode($response);
                    exit;
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


        } else {
            $response = array(
                'state' => 405,
                'resp'  => 'unable to indentify timeserie'
            );
            echo json_encode($response);
            exit;
        }
    }


    $table = '`Timeseries Record Dimension` TR ';


    $where  = sprintf(
        ' where `Timeseries Record Timeseries Key`=%d', $data['id']
    );
    $order  = '`Timeseries Record Date`';
    $fields = "`Timeseries Record Type`,`Timeseries Record Date`,`Timeseries Record Float A`,`Timeseries Record Float B`,`Timeseries Record Float C`,`Timeseries Record Float D`,`Timeseries Record Integer A`,`Timeseries Record Integer B`";

    $sql   = "select $fields from $table $where  order by $order ";
    $adata = array();

    if ($result = $db->query($sql)) {

        $adata[] = array(
            'date'      => 'timestamp',
            'float_a'   => 'open',
            'float_b'   => 'float_b',
            'float_c'   => 'float_c',
            'float_d'   => 'float_d',
            'integer_a' => 'volume',
            'integer_b' => 'integer_b'

        );

        foreach ($result as $data) {
            $adata[] = array(
                'date'      => gmdate("U", strtotime($data['Timeseries Record Date'])),
                'float_a'   => $data['Timeseries Record Float A'],
                'float_b'   => $data['Timeseries Record Float B'],
                'float_c'   => $data['Timeseries Record Float C'],
                'float_d'   => $data['Timeseries Record Float D'],
                'integer_a' => $data['Timeseries Record Integer A'],
                'integer_b' => $data['Timeseries Record Integer B'],

            );
        }


        outputCSV($adata);


    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
    }


}


function outputCSV($data) {

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=file.csv");
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = fopen("php://output", "w");
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
}


function asset_sales($db, $data, $account) {
    include_once 'utils/object_functions.php';


    $table          = "`Timeseries Record Dimension`  ";
    $timeseries_key = 0;
    switch ($data['parent']) {


        case 'part':

            include_once 'elastic/isf.elastic.php';


            $results_raw = get_part_inventory_transaction_fact('sales', $data['parent_key']);

            $results = $results_raw['aggregations']['stock_per_day']['buckets'];
            print "Date,Open,Volume\n";

            foreach (array_reverse($results) as $result) {
                print $result['key_as_string'].','.$result['sold_amount']['value'].','.$result['sold']['value']."\n";
            }

            exit();


            break;

        case 'product':

            $product = get_object('Product', $data['parent_key']);


            $where = sprintf("where   `Invoice Key` is not null  and `Product ID`=%d", $data['parent_key']);
            $table = "`Order Transaction Fact` TR ";
            $group = 'group by `Date`';


            $fields = "`Invoice Date` as `Date`,
sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as `Sales`,
count(distinct `Invoice Key`) as `Volume`,
count(distinct `Customer Key`) as customers
";


            break;
        case 'product_category':


            $sql = sprintf(
                'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Category" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`="Daily" AND  `Timeseries Type`="ProductCategorySales" ', $data['parent_key']
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $timeseries_key = $row['Timeseries Key'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $fields = ' `Timeseries Record Date` as Date,sum(`Timeseries Record Float A`) as Sales ,sum(`Timeseries Record Integer A`) as Volume';
            $where  = sprintf(
                "where `Timeseries Record Timeseries Key`=%d", $timeseries_key
            );
            $table  = "`Timeseries Record Dimension`  ";

            $group = 'group by `Date`';
            break;
        case 'part_category':


            $sql = sprintf(
                'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Category" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`="Daily" AND  `Timeseries Type`="PartCategorySales" ', $data['parent_key']
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $timeseries_key = $row['Timeseries Key'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $fields = ' `Timeseries Record Date` as Date,sum(`Timeseries Record Float A`) as Sales ,sum(`Timeseries Record Integer A`) as Volume';
            $where  = sprintf(
                "where `Timeseries Record Timeseries Key`=%d", $timeseries_key
            );
            $table  = "`Timeseries Record Dimension`  ";

            $group = 'group by `Date`';
            break;


        case 'store':

            $sql = sprintf(
                'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Store" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`="Daily" AND  `Timeseries Type`="StoreSales" ', $data['parent_key']
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $timeseries_key = $row['Timeseries Key'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            if (!$timeseries_key) {
                print "Date,Open,Volume\n";
                print gmdate('Y-m-d').",0,0\n";

                return;
            }


            $fields = ' `Timeseries Record Date` as Date,sum(`Timeseries Record Float A`) as Sales ,sum(`Timeseries Record Integer A`) as Volume';
            $where  = sprintf("where `Timeseries Record Timeseries Key`=%d", $timeseries_key);
            $group  = 'group by `Date`';
            break;
        case 'account':

            $sql = sprintf(
                'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Account" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`="Daily" AND  `Timeseries Type`="AccountSales" ', $data['parent_key']
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $timeseries_key = $row['Timeseries Key'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            $fields = ' `Timeseries Record Date` as Date,sum(`Timeseries Record Float A`) as Sales ,sum(`Timeseries Record Integer A`) as Volume';
            $where  = sprintf("where `Timeseries Record Timeseries Key`=%d", $timeseries_key);
            $group  = 'group by `Date`';
            break;
        case 'supplier':

            $sql = sprintf(
                'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Supplier" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`="Daily" AND  `Timeseries Type`="SupplierSales" ', $data['parent_key']
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $timeseries_key = $row['Timeseries Key'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            $fields = ' `Timeseries Record Date` as Date,sum(`Timeseries Record Float A`) as Sales ,sum(`Timeseries Record Integer A`) as Volume';
            $where  = sprintf("where `Timeseries Record Timeseries Key`=%d", $timeseries_key);

            $group = 'group by `Date`';
            break;
        case 'agent':

            $sql = sprintf(
                'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Agent" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`="Daily" AND  `Timeseries Type`="AgentSales" ', $data['parent_key']
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $timeseries_key = $row['Timeseries Key'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            $fields = ' `Timeseries Record Date` as Date,sum(`Timeseries Record Float A`) as Sales ,sum(`Timeseries Record Integer A`) as Volume';
            $where  = sprintf("where `Timeseries Record Timeseries Key`=%d", $timeseries_key);

            $group = 'group by `Date`';
            break;
        case 'customer':

            $sql = sprintf(
                'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Customer" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`="Daily" AND  `Timeseries Type`="CustomerSales" ', $data['parent_key']
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $timeseries_key = $row['Timeseries Key'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            $fields = ' `Timeseries Record Date` as Date,sum(`Timeseries Record Float A`) as Sales ,sum(`Timeseries Record Integer A`) as Volume';
            $where  = sprintf("where `Timeseries Record Timeseries Key`=%d", $timeseries_key);

            $group = 'group by `Date`';
            break;
        default:
            return;

    }

    $where_interval = prepare_mysql_dates($data['from'], $data['to'], '`Date`');
    $where          .= $where_interval['mysql'];


    $sql = sprintf("SELECT %s FROM %s %s %s ORDER BY `Date` DESC", $fields, $table, $where, $group);


    $res = array();
    print "Date,Open,Volume\n";


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            if ($row['Volume'] == 0) {
                // continue;
            }

            print sprintf(
                "%s,%.1f,%d\n", $row['Date'], $row['Sales'], $row['Volume']

            );

            $res[] = $row;

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


//    $cache->set($account->get('Code').'SQL'.md5($sql), $res, 86400);


}


function part_stock($data) {

    include_once 'elastic/isf.elastic.php';

    print "Date,Open,High,Low,Close,Volume,Adj Close\n";


    $results_raw = get_part_inventory_transaction_fact('stock', $data['parent_key']);

    $results = $results_raw['aggregations']['stock_per_day']['buckets'];
    foreach (array_reverse($results) as $result) {
        print $result['key_as_string'].',0,0,0,'.$result['stock']['value']."\n";
    }


}








