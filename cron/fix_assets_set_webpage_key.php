<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 June 2017 at 14:42:50 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Category.php';
require_once 'class.Product.php';
include_once 'class.Page.php';

include_once 'class.Public_Webpage.php';
include_once 'class.Public_Category.php';;


$sql = sprintf(
    "SELECT `Product ID` FROM `Product Dimension` WHERE (`Product Webpage Key` IS NULL OR `Product Webpage Key`=0 )  ORDER BY `Product Code` DESC"
);
print "$sql\n";

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $subject_webpage     = new Page('scope', 'Product', $row['Product ID']);
        $subject_webpage_key = $subject_webpage->id;

        $product = new Product($row['Product ID']);
        if ($subject_webpage_key > 0) {

            print $product->id.' '.$product->get('Code')."\n";
            $product->update(array('Product Webpage Key' => $subject_webpage_key), 'no_history');
        } else {
            if ($product->get('Product Status') != 'Discontinued') {

                $store = get_object('store', $product->get('Store Key'));

                foreach ($store->get_websites('objects') as $website) {
                    printf("webpage for  %d  product %s %s , %s not found\n", $product->get('Store Key'),$product->get('Code'), $product->id, $product->get('Product Status'));

                    $website->create_product_webpage($product->id);
                }


            }


        }
    }


} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

exit;

$where = "where true";
//$where="where `Category Key`=15362";

$sql = sprintf(
    "select count(distinct `Category Key`) as num from `Category Dimension` $where and  `Category Scope`='Product' "
);

if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

$lap_time0 = date('U');
$contador  = 0;

$sql = sprintf(
    "select `Category Key` from `Category Dimension` $where and  `Category Scope`='Product'   "
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $subject = new Category($row['Category Key']);

        if ($subject->get('Product Category Webpage Key') == '') {

            $subject_webpage     = new Public_Webpage('scope', ($subject->get('Category Subject') == 'Category' ? 'Category Categories' : 'Category Products'), $subject->id);
            $subject_webpage_key = $subject_webpage->id;

            $subject->update(array('Product Category Webpage Key' => $subject_webpage_key), 'no_history');
        }

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
