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

include_once 'class.Public_Webpage.php';
include_once 'class.Public_Category.php';;


$sql = sprintf(
    "SELECT `Product ID` FROM `Product Dimension` "
);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $product = new Product($row['Product ID']);

        print $product->get('Code')."\n";
        $subject_webpage     = new Public_Webpage('scope', 'Product', $product->id);
        $subject_webpage_key = $subject_webpage->id;


        //print $subject_webpage_key." xx \n";

        $product->update(array('Product Webpage Key' => $subject_webpage_key), 'no_history');
    }


} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


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
    "select `Category Key` from `Category Dimension` $where and  `Category Scope`='Product' "
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $subject = new Category($row['Category Key']);

        $subject_webpage     = new Public_Webpage('scope', ($subject->get('Category Subject') == 'Category' ? 'Category Categories' : 'Category Products'), $subject->id);
        $subject_webpage_key = $subject_webpage->id;

        $subject->update(array('Product Category Webpage Key' => $subject_webpage_key), 'no_history');


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
