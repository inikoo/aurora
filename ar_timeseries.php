<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 January 2016 at 14:53:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
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
        part_stock($db, $data, $account);
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
                'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Type`=%s AND `Timeseries Parent`=%s AND `Timeseries Parent Key`=%d', prepare_mysql($data['type']),
                prepare_mysql($data['parent']), $data['parent_key']
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


    $where = sprintf(
        ' where `Timeseries Record Timeseries Key`=%d', $data['id']
    );
    $order = '`Timeseries Record Date`';
    $fields
           = "`Timeseries Record Type`,`Timeseries Record Date`,`Timeseries Record Float A`,`Timeseries Record Float B`,`Timeseries Record Float C`,`Timeseries Record Float D`,`Timeseries Record Integer A`,`Timeseries Record Integer B`";

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
                'date'      => date(
                    "U", strtotime($data['Timeseries Record Date'].' +0:00')
                ),
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

    global $memcache_ip;

    switch ($data['parent']) {


        case 'part':
            $fields
                   = ' `Date`,sum(`Sold Amount`)  as `Sales`, sum(`Quantity Sold`)  as  `Volume`';
            $where = sprintf("where `Part SKU`=%d", $data['parent_key']);
            $table = "`Inventory Spanshot Fact`";
            $group = 'group by `Date`';
            break;

        case 'product':
            $fields = ' `Date`,`Sales`,`Invoices` as Volume';
            $where  = sprintf("where `Product ID`=%d", $data['parent_key']);
            $table  = "`Order Spanshot Fact`";
            $group  = '';
            break;
        case 'product_category':


            $sql = sprintf(
                'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Category" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`="Daily" AND  `Timeseries Type`="ProductCategorySales" ',
                $data['parent_key']
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $timeseries_key = $row['Timeseries Key'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $fields
                   = ' `Timeseries Record Date` as Date,sum(`Timeseries Record Float A`) as Sales ,sum(`Timeseries Record Integer A`) as Invoices';
            $where = sprintf(
                "where `Timeseries Record Timeseries Key`=%d", $timeseries_key
            );
            $table = "`Timeseries Record Dimension`  ";

            $group = 'group by `Date`';
            break;
        case 'part_category':


            $sql = sprintf(
                'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Category" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`="Daily" AND  `Timeseries Type`="PartCategorySales" ',
                $data['parent_key']
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $timeseries_key = $row['Timeseries Key'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $fields
                   = ' `Timeseries Record Date` as Date,sum(`Timeseries Record Float A`) as Sales ,sum(`Timeseries Record Integer A`) as Volume';
            $where = sprintf(
                "where `Timeseries Record Timeseries Key`=%d", $timeseries_key
            );
            $table = "`Timeseries Record Dimension`  ";

            $group = 'group by `Date`';
            break;


        case 'store':
            $fields = ' `Date`,sum(`Sales`) as Sales';
            $where  = sprintf("where `Store Key`=%d", $data['parent_key']);
            $group  = 'group by `Date`';
            break;
        case 'supplier':

            $sql = sprintf(
                'SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Supplier" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`="Daily" AND  `Timeseries Type`="SupplierSales" ',
                $data['parent_key']
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $timeseries_key = $row['Timeseries Key'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            $fields
                   = ' `Timeseries Record Date` as Date,sum(`Timeseries Record Float A`) as Sales ,sum(`Timeseries Record Integer A`) as Volume';
            $where = sprintf(
                "where `Timeseries Record Timeseries Key`=%d", $timeseries_key
            );
            $table = "`Timeseries Record Dimension`  ";
            $group = 'group by `Date`';
            break;
        default:
            return;

    }

    $where_interval = prepare_mysql_dates($data['from'], $data['to'], '`Date`');
    $where .= $where_interval['mysql'];

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);

    $sql = sprintf(
        "SELECT %s FROM %s %s %s ORDER BY `Date` DESC", $fields, $table, $where, $group
    );

    //	print $sql;

    $result = $cache->get($account->get('Code').'SQL'.md5($sql));
    if ($result and false) {
        print "Date,Open,Volume\n";
        foreach ($result as $row) {
            print sprintf(
                "%s,%s\n", $row['Date'], $row['Sales'], $row['Volume']
            );
        }

    } else {

        $res = mysql_query($sql);
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


        $cache->set($account->get('Code').'SQL'.md5($sql), $res, 86400);
    }


}


function part_stock($db, $data, $account) {

    global $memcache_ip;

    switch ($data['parent']) {
        case 'part':
            $fields
                   = '`Date`,sum(`Quantity Open`) as open ,sum(`Quantity High`) as high,sum(`Quantity Low`) as low,sum(`Quantity On Hand`) as close';
            $where = sprintf("where `Part SKU`=%d", $data['parent_key']);
            $group = ' group by `Date`';
            break;

        default:
            return;

    }

    $where_interval = prepare_mysql_dates($data['from'], $data['to'], '`Date`');
    $where .= $where_interval['mysql'];

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);

    $sql    = sprintf(
        "SELECT %s FROM `Inventory Spanshot Fact` %s   %s  ORDER BY `Date` DESC ", $fields, $where, $group
    );
    $result = $cache->get($account->get('Code').'SQL'.md5($sql));
    if ($result and false) {
        print "Date,Open,High,Low,Close,Volume,Adj Close\n";
        foreach ($result as $row) {

            print sprintf(
                "%s,%s,%s,%s,%s\n", $row['Date'], $row['open'], $row['high'], $row['low'], $row['close']
            );

        }

    } else {


        $res = array();
        print "Date,Open,High,Low,Close,Volume,Adj Close\n";


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                print sprintf(
                    "%s,%.1f,%.1f,%.1f,%.1f\n", $row['Date'], $row['open'], $row['high'], $row['low'], $row['close']
                );

                $res[] = $row;

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print $sql;
            exit;
        }


        $cache->set($account->get('Code').'SQL'.md5($sql), $res, 86400);
    }


}


?>
