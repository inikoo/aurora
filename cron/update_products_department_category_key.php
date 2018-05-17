<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 May 2018 at 12:47:04 CEST, Trnava Slovakia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';




$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


//print date('l jS \of F Y h:i:s A')."\n";


$sql = sprintf(
    "SELECT `Product ID` FROM `Product Dimension`  "
);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $product = get_object('Product',$row['Product ID']);


        $family = get_object('Category', $product->get('Product Family Category Key'));


        $product->fast_update(array('Product Department Category Key' => $family->get('Product Category Department Category Key')));


        // $product->update(array('Product Parts Data'=>json_encode($product->get_parts_data())),'no_history');

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
