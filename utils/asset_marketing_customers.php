<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 31-07-2019 16:02:55 MYT, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/

function get_targeted_product_customers($customers, $db, $product_id, $threshold) {


    $sql  = 'select `Customer Favourite Product Customer Key` ,`Customer Main Plain Email` from `Customer Favourite Product Fact` left join `Customer Dimension` on (`Customer Favourite Product Customer Key`=`Customer Key`)  where `Customer Favourite Product Product ID`=? and  `Customer Main Plain Email`!=""  and `Customer Send Email Marketing`="Yes" ';
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($product_id)
    );
    while ($row = $stmt->fetch()) {
        $customers[$row['Customer Favourite Product Customer Key']] =$row['Customer Main Plain Email'];
    }

    $estimated_recipients = count($customers);

    if ($estimated_recipients > $threshold) {
        return $customers;
    }

    $sql  = 'select OTF.`Customer Key` ,`Customer Main Plain Email` from `Order Transaction Fact` OTF left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) where `Product ID`=? and `Date`>? and `Customer Send Email Marketing`="Yes"   and  `Customer Main Plain Email`!=""';
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $product_id,
            gmdate('Y-m-d H:i:s', strtotime('3 months'))
        )
    );
    while ($row = $stmt->fetch()) {
        $customers[$row['Customer Key']] = $row['Customer Main Plain Email'];
    }

    $estimated_recipients = count($customers);
    if ($estimated_recipients > $threshold) {
        return $customers;
    }

    $sql  = 'select OTF.`Customer Key` ,`Customer Main Plain Email` from `Order Transaction Fact` OTF left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) where `Product ID`=? and `Date`>? and `Customer Send Email Marketing`="Yes"  and  `Customer Main Plain Email`!=""';
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $product_id,
            gmdate('Y-m-d H:i:s', strtotime('12 months'))
        )
    );
    while ($row = $stmt->fetch()) {
        $customers[$row['Customer Key']] = $row['Customer Main Plain Email'];
    }

    $estimated_recipients = count($customers);
    if ($estimated_recipients > $threshold) {
        return $customers;
    }

    $sql = 'select OTF.`Customer Key` ,`Customer Main Plain Email` from `Order Transaction Fact` OTF left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) where `Product ID`=?  and `Customer Send Email Marketing`="Yes"  and  `Customer Main Plain Email`!=""';


    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($product_id)
    );
    while ($row = $stmt->fetch()) {
        $customers[$row['Customer Key']] = $row['Customer Main Plain Email'];
    }


    return $customers;


}


function get_spread_product_customers($customers, $db, $product_id, $threshold) {


    $customers = get_targeted_product_customers($customers, $db, $product_id, $threshold);


    $product = get_object('Product', $product_id);


    $sql = "SELECT `Product B ID` FROM `Product Sales Correlation`  where `Product A ID`=?   ORDER BY `Correlation` DESC limit 10";


    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($product->id)
    );
    while ($row = $stmt->fetch()) {


        $customers=get_targeted_product_customers($customers, $db, $row['Product B ID'], $threshold);

    }



    if ($product->get('Product Family Category Key')) {
        $customers = get_targeted_categories_customers($customers, $db, $product->get('Product Family Category Key'), $threshold);

    }


    $sql = "SELECT `Product B ID` FROM `Product Sales Correlation`  where `Product A ID`=?   ORDER BY `Correlation` DESC limit 10,50";


    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($product->id)
    );
    while ($row = $stmt->fetch()) {


        $customers=get_targeted_product_customers($customers, $db, $row['Product B ID'], $threshold);
        $estimated_recipients = count($customers);
        if ($estimated_recipients > $threshold) {
            return $customers;
        }
    }



    return $customers;


}


function get_targeted_categories_customers($customers, $db, $category_key, $threshold) {

    $category = get_object('Category', $category_key);
    $store    = get_object('Store', $category->get('Store Key'));

    $products_ids = $category->get_product_ids();

    if ($products_ids != '') {
        $sql  = 'select `Customer Favourite Product Customer Key`,`Customer Main Plain Email` from `Customer Favourite Product Fact` left join `Customer Dimension` on (`Customer Favourite Product Customer Key`=`Customer Key`) where `Customer Favourite Product Product ID` in ('.$products_ids.') and  `Customer Main Plain Email`!=""  and `Customer Send Email Marketing`="Yes" ';

        $stmt = $db->prepare($sql);
        $stmt->execute(
            array($category_key)
        );
        while ($row = $stmt->fetch()) {
            $customers[$row['Customer Favourite Product Customer Key']] = $row['Customer Main Plain Email'];
        }
    }


    $estimated_recipients = count($customers);

    if ($estimated_recipients > $threshold) {
        return $customers;
    }


    $category_type = '';

    if ($category->get('Category Root Key') == $store->get('Store Family Category Key')) {
        $category_type = 'OTF Category Family Key';
    } elseif ($category->get('Category Root Key') == $store->get('Store Department Category Key')) {
        $category_type = 'OTF Department Family Key';
    }


    if ($category_type) {

        $sql  = 'select `Customer Key`,`Customer Main Plain Email` from `Order Transaction Fact`  OTF left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) where `'.$category_type.'`=? and `Date`>? and `Customer Send Email Marketing`="Yes"  and  `Customer Main Plain Email`!=""';
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $category_key,
                gmdate('Y-m-d H:i:s', strtotime('3 months'))
            )
        );
        while ($row = $stmt->fetch()) {
            $customers[$row['Customer Key']] = $row['Customer Main Plain Email'];
        }

        $estimated_recipients = count($customers);
        if ($estimated_recipients > $threshold) {
            return $customers;
        }


        $sql  = 'select `Customer Key`,`Customer Main Plain Email` from `Order Transaction Fact`  OTF left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) where `'.$category_type.'`=? and `Date`>? and `Customer Send Email Marketing`="Yes"  and  `Customer Main Plain Email`!="" ';
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $category_key,
                gmdate('Y-m-d H:i:s', strtotime('12 months'))
            )
        );
        while ($row = $stmt->fetch()) {
            $customers[$row['Customer Key']] = $row['Customer Main Plain Email'];
        }

        $estimated_recipients = count($customers);
        if ($estimated_recipients > $threshold) {
            return $customers;
        }

        $sql = 'select `Customer Key`,`Customer Main Plain Email` from `Order Transaction Fact`  OTF left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) where `'.$category_type.'`=? and `Customer Send Email Marketing`="Yes"  and  `Customer Main Plain Email`!="" ';


        $stmt = $db->prepare($sql);
        $stmt->execute(
            array($category_key)
        );
        while ($row = $stmt->fetch()) {
            $customers[$row['Customer Key']] = $row['Customer Main Plain Email'];
        }

    }


    return $customers;


}



function get_spread_categories_customers($customers, $db, $category_key, $threshold) {

    $customers = get_targeted_categories_customers($customers, $db, $category_key, $threshold);


    $category = get_object('Category', $category_key);


    if($category->get('Category Subject')=='Product'){
        $sql = sprintf(
            "SELECT `Category B Key` FROM `Product Category Sales Correlation` WHERE `Category A Key`=%d ORDER BY `Correlation` DESC ", $category->id
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                $customers=get_targeted_categories_customers($customers, $db, $row['Category B Key'], min(ceil($threshold/100),1000));

                $estimated_recipients = count($customers);
                if ($estimated_recipients > $threshold) {
                    return $customers;
                }

            }
        }

    }elseif($category->get('Category Subject')=='Category'){
        $sql = sprintf(
            "SELECT `Category B Key` FROM `Product Category Sales Correlation` WHERE `Category A Key`=%d ORDER BY `Correlation` DESC ", $category->id
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                $customers=get_targeted_categories_customers($customers, $db, $row['Category B Key'], min(ceil($threshold/100),1000));

                $estimated_recipients = count($customers);
                if ($estimated_recipients > $threshold) {
                    return $customers;
                }

            }
        }

    }





    return $customers;


}
