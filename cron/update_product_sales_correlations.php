<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 April 2018 at 14:37:59 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/common.php';
$print_est = false;

$products = array();

if (!empty($argv[3]) and is_numeric($argv[3]) and $argv[3] > 0) {
    $time_limit = ceil(3600 * $argv[3]);
} else {
    $time_limit = 0;
}

if (!empty($argv[1]) and in_array(
        $argv[1], array(
                    'Same_Family',
                    'Exclude_Same_Family',
                    'Same_Department',
                    'Random',
                    'New',
                    'Best Sellers',
                    'Auto'
                )
    )) {
    $type = preg_replace('/\s/', '_', $argv[1]);
} else {
    $type = 'Auto';
}

if (!empty($argv[2]) and is_numeric($argv[2]) and $argv[2] > 0) {
    $limit = $argv[2];
} else {
    $limit = 10;
}


$sql = sprintf(
    "SELECT count(*) AS num FROM `Product Dimension`  where `Product Ignore Correlation`='No' and `Product Status` in ('Active','Discontinuing')"
);
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
}


$sql  = "SELECT P.`Product ID` FROM `Product Dimension` P left join `Product DC Data` D on (P.`Product ID`=D.`Product ID`)  where `Product Ignore Correlation`='No' and `Product Status` in ('Active','Discontinuing')  order by  RAND() limit 100000  ";
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {
    $products[] = $row['Product ID'];
}


$db = null;



$lap_time0 = date('U');
$contador  = 0;
foreach ($products as $_key=>$product_id) {

    /**
     * @var PDO
     */
    $db = new PDO(
        "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


    $dns_replica = $dns_replicas[array_rand($dns_replicas, 1)];
    $db_replica  = new PDO(
        "mysql:host=".$dns_replica['host'].";dbname=".$dns_replica['db'].";charset=utf8mb4", $dns_replica['user'], $dns_replica['pwd']
    );
    $db_replica->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


    $product = get_object('Product', $product_id, false, $db);
    $product->update_sales_correlations($type, $limit, $db_replica);

    $contador++;
    $lap_time1 = date('U');

    if ($print_est) {
        print 'Product sales correlation   '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
            )."h  ($contador/$total) \r";
    }

    if ($time_limit > 0 and ($lap_time1 - $lap_time0) > $time_limit) {
        print "Finishing after timeout reached\n";
        break;
    }

    $db         = null;
    $db_replica = null;
    unset($products[$_key]);
}

