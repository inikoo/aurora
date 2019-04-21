<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW


require_once 'common.php';

require_once 'class.Store.php';
require_once 'class.Category.php';
require_once 'class.Product.php';
require_once 'class.Part.php';



$con_drop = @mysql_connect($dns_host, $dns_user, $dns_pwd);
if (!$con_drop) {
    print "Error can not connect with dropshipping database server\n";
    exit;
}
$db2 = @mysql_select_db("drop", $con_drop);
if (!$db2) {
    print "Error can not access the database in drop \n";
    exit;
}

$con = @mysql_connect($dns_host, $dns_user, $dns_pwd);

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
//$dns_db='dw_avant';
$db = @mysql_select_db("dw", $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}

$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';"));
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$store = new Store('code', 'DS');


$department_bridge = array();
$family_bridge     = array();


$sql = "SELECT * FROM `drop`.`catalog_product_entity` WHERE sku IS NOT NULL AND sku NOT IN ('EO-')   ";
$res = mysql_query($sql, $con_drop);
while ($row = mysql_fetch_assoc($res)) {

    $store_code    = $store->data['Store Code'];
    $order_data_id = $row['entity_id'];

    $sql   = sprintf(
        "SELECT * FROM `Product Import Metadata` WHERE `Metadata`=%s AND `Import Date`>=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['updated_at'])

    );
    $resxx = mysql_query($sql);
    if ($rowxx = mysql_fetch_assoc($resxx)) {

        continue;
    }


    $code = $row['sku'];


    $sql  = sprintf("SELECT * FROM `drop`.`catalog_product_entity_varchar` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber($con_drop, 'name', 4));
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {
        $name = $row2['value'];
    } else {
        exit("error no name associated\n");
    }

    $sql  = sprintf("SELECT * FROM `drop`.`catalog_product_entity_varchar` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber($con_drop, 'awsku', 4));
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {
        $sku = $row2['value'];
    } else {
        //print $row['entity_id']." $code error no sku associated \n";
        exit("error no sku associated\n");
    }

    $sql  = sprintf("SELECT * FROM `drop`.`catalog_product_entity_varchar` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber($con_drop, 'relate', 4));
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {
        //print_r($row2);
        $parts_per_product = floatval($row2['value']);
    } else {
        exit("error no part_relation associated\n");
    }


    if (!is_numeric($parts_per_product) or $parts_per_product <= 0) {
        print_r($row);
        print_r($row2);
        exit("wrong parts per product\n");
    }


    if ($parts_per_product == '') {
        print "$sku $parts_per_product\n";
    }

    $sql  = sprintf("SELECT * FROM `drop`.`catalog_product_entity_text` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber($con_drop, 'description', 4));
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {
        $description = $row2['value'];
    } else {
        exit("error no description associated\n");
    }

    $sql  = sprintf("SELECT * FROM `drop`.`catalog_product_entity_decimal` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber($con_drop, 'price', 4));
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {
        $price = $row2['value'];
    } else {
        exit("error no description associated\n");
    }

    $sql  = sprintf("SELECT * FROM `drop`.`catalog_product_entity_decimal` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber($con_drop, 'weight', 4));
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {
        $weight = $row2['value'];
    } else {
        exit("error no description associated\n");
    }

    $sql  = sprintf("SELECT * FROM `drop`.`catalog_category_product` WHERE  `product_id` =%d   ", $row['entity_id']);
    $res2 = mysql_query($sql, $con_drop);
    if ($row2 = mysql_fetch_assoc($res2)) {

      // print_r($row2);


        $sql  = sprintf("SELECT * FROM `drop`.`catalog_category_entity_varchar` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row2['category_id'], getMagentoAttNumber($con_drop, 'name', 3));
        $res2 = mysql_query($sql, $con_drop);
        if ($row2 = mysql_fetch_assoc($res2)) {


            $category_code = preg_replace('/\s/i', '', $row2['value']);
            $category_code = preg_replace('/\'/i', '', $category_code);
            $category_code = preg_replace('/\&/i', '', $category_code);
            $category_code = substr($category_code, 0, 5);


        }

    }


    //print_r($family_bridge);
    $weight = $weight / 1000;
    //$weight=500;
    //print_r($family);
    //exit;
    //print $family->data['Product Family Code']."\n";
    $editor['Date'] = $row['created_at'];


    $part = new Part('sku', $sku);

    if(!$part->sku){

        print '** Error SKU not found '.$code."  $sku  \n";
        continue;
    }


    $product_data = array(


        'Product Code'            => $code,
        'Product CPNP Number'     => '',
        'Product Parts'           => json_encode(
           array( array(
                'Key'      => '',
                'Part SKU' => $part->sku,
                'Ratio'    => $parts_per_product,
                'Note'     => '',
            )
           )
        ),
        'Family Category Code'    => $category_code,
        'Product Label in Family' => '',
        'Product Units Per Case'  => 1,
        'Product Unit Label'      => 'piece',
        'Product Price'           => $price,
        'Product Name'            => $name,
        'Product Unit RRP'        => '',
        'Product Unit Weight'     => $weight,
        'Product Description'     => $description,
        'editor'                  => $editor,
    );




    $product = $store->create_product($product_data);


    if($store->error){


        if( $store->error_code=='duplicate_product_code_reference'){
            $sql = sprintf(
                "INSERT INTO `Product Import Metadata` ( `Metadata`, `Import Date`) VALUES (%s,%s) ON DUPLICATE KEY UPDATE
		`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['updated_at']), prepare_mysql($row['updated_at'])
            );
            mysql_query($sql);
        }else{
            print $store->msg."\n";
            print $store->error_code."\n";


        }


    }else {


        if ($product->id) {

            $sql = sprintf(
                "INSERT INTO `Product Import Metadata` ( `Metadata`, `Import Date`) VALUES (%s,%s) ON DUPLICATE KEY UPDATE
		`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['updated_at']), prepare_mysql($row['updated_at'])
            );
             mysql_query($sql);
        }

    }




}


function getMagentoAttNumber($dbh, $attribute_code, $entity_type_id) {

    global $con_drop;
    $Att_Got = '';
    $sql     = "SELECT `attribute_id` FROM `drop`.`eav_attribute` WHERE `attribute_code` LIKE '".$attribute_code."' AND `entity_type_id` =".$entity_type_id."  ";
    $res     = mysql_query($sql, $con_drop);
    if ($row = mysql_fetch_assoc($res)) {


        $Att_Got = $row['attribute_id'];
    } else {
        print $sql."\n";

        echo mysql_errno($con_drop).": ".mysql_error($con_drop)."\n";
        exit;
    }


    return $Att_Got;

}



