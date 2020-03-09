<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: ,  Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

include 'common.php';


$sql         = "select `Store Key` from `Store Dimension` where `Store Key`=19";
$stmt_stores = $db->prepare($sql);
$stmt_stores->execute(
    array()
);
while ($row_stores = $stmt_stores->fetch()) {


    $store = get_object('Store', $row_stores['Store Key']);

    print $store->get('Code')."\n";


    $sql =
        "select F.`Category Store Key`,`Category Code`, `Product Category Department Category Key`,`Product Category Key` from `Category Dimension` F left join `Product Category Dimension` PC on (PC.`Product Category Key`=F.`Category Key`)  
        where `Category Root Key`=? and `Category Branch Type`='Head'  and `Category Code`='ABB' ";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $store->get('Store Family Category Key')
        )
    );
    while ($row = $stmt->fetch()) {


        $family = get_object('Category', $row['Product Category Key']);


        $departments     = $family->get_categories('objects', 'root_key', $store->get('Store Department Category Key'));



        $num_departments = count($departments);


        if ($num_departments == 0) {

            if ($family->get('Product Category Department Category Key') != '') {
                print $family->get('Code').' FAM '.$family->get('Product Category Department Category Key')." ->\n";
            }



            $family->fast_update(
                [
                    'Product Category Department Category Key' => ''
                ], 'Product Category Dimension'
            );

        } elseif ($num_departments == 1) {


            $department_from_branch = array_pop($departments);

            if ($family->get('Product Category Department Category Key') != $department_from_branch->id) {
                print $family->get('Code').' FAM '.$family->get('Product Category Department Category Key')." ->".$department_from_branch->id."\n";
            }

            $family->fast_update(
                [
                    'Product Category Department Category Key' => $department_from_branch->id
                ], 'Product Category Dimension'
            );


        } elseif ($num_departments > 1) {

            exit('Ahhhhhhh');

        }


    }


    $sql = "select `Product ID` from `Product Dimension` where `Product Store Key`=?";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $store->id
        )
    );
    while ($row = $stmt->fetch()) {


        $product = get_object('Product', $row['Product ID']);


        $families     = $product->get_categories('objects', 'root_key', $store->get('Store Family Category Key'));
        $num_families = count($families);


        if ($num_families == 0) {

            if ($product->get('Product Family Category Key') != '') {
                print $product->get('Code').' F '.$product->get('Product Family Category Key')." ->\n";
            }
            if ($product->get('Product Department Category Key') != '') {
                print $product->get('Code').' D '.$product->get('Product Department Category Key')." ->\n";

            }

            $product->fast_update(
                [
                    'Product Family Category Key'     => '',
                    'Product Department Category Key' => ''
                ]
            );

        } elseif ($num_families == 1) {


            $family_from_branch = array_pop($families);


            if ($product->get('Product Family Category Key') != $family_from_branch->id) {
                print $product->get('Code').' F '.$product->get('Product Family Category Key')." ->".$family_from_branch->id."\n";
            }
            if ($product->get('Product Department Category Key') != $family_from_branch->get('Product Category Department Category Key')) {
                print $product->get('Code').'  D '.$product->get('Product Department Category Key')." ->".$family_from_branch->get('Product Category Department Category Key')."\n";

            }

            $product->fast_update(
                [
                    'Product Family Category Key'     => $family_from_branch->id,
                    'Product Department Category Key' => $family_from_branch->get('Product Category Department Category Key')
                ]
            );


        } elseif ($num_families > 1) {

            exit('Ahhhhhhh wrint fam');

        }

    }


}



