<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 31-07-2019 16:02:55 MYT, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/

/**
 * @param      $customers
 * @param      $db PDO
 * @param      $product_id
 * @param      $threshold
 * @param bool $expand
 *
 * @return mixed
 */
function get_targeted_product_customers($customers, $db, $product_id, $threshold, $expand = true) {


    $sql  =
        "select `Customer Favourite Product Customer Key` ,`Customer Main Plain Email` from `Customer Favourite Product Fact` left join `Customer Dimension` on (`Customer Favourite Product Customer Key`=`Customer Key`)  where `Customer Favourite Product Product ID`=? and  `Customer Main Plain Email`!=''  and `Customer Send Email Marketing`='Yes'";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($product_id)
    );
    while ($row = $stmt->fetch()) {
        $customers[$row['Customer Favourite Product Customer Key']] = $row['Customer Main Plain Email'];
    }

    $estimated_recipients = count($customers);


    if ($estimated_recipients > $threshold) {
        return $customers;
    }

    $sql  =
        "select OTF.`Customer Key` ,`Customer Main Plain Email` from `Order Transaction Fact` OTF left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) where `Product ID`=? and `Order Date`>? and `Customer Send Email Marketing`='Yes'   and  `Customer Main Plain Email`!=''";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $product_id,
            gmdate('Y-m-d H:i:s', strtotime('-3 months'))
        )
    );
    while ($row = $stmt->fetch()) {
        $customers[$row['Customer Key']] = $row['Customer Main Plain Email'];
    }

    $estimated_recipients = count($customers);

    if ($estimated_recipients > $threshold) {
        return $customers;
    }

    $sql  =
        "select OTF.`Customer Key` ,`Customer Main Plain Email` from `Order Transaction Fact` OTF left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) where `Product ID`=? and `Order Date`>? and `Customer Send Email Marketing`='Yes'  and  `Customer Main Plain Email`!=''";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $product_id,
            gmdate('Y-m-d H:i:s', strtotime('-12 months'))
        )
    );
    while ($row = $stmt->fetch()) {
        $customers[$row['Customer Key']] = $row['Customer Main Plain Email'];
    }

    $estimated_recipients = count($customers);
    if ($estimated_recipients > $threshold) {
        return $customers;
    }

    $sql =
        "select OTF.`Customer Key` ,`Customer Main Plain Email` from `Order Transaction Fact` OTF left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) where `Product ID`=?  and `Customer Send Email Marketing`='Yes'  and  `Customer Main Plain Email`!=''";


    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($product_id)
    );
    while ($row = $stmt->fetch()) {
        $customers[$row['Customer Key']] = $row['Customer Main Plain Email'];
    }

    $estimated_recipients = count($customers);
    if ($estimated_recipients > $threshold) {
        return $customers;
    }


    if ($expand) {

        include_once 'elastic/assets_correlation.elastic.php';

        $result = get_elastic_sales_correlated_assets($product_id, 'products_bought', '_1y', 10);

        foreach ($result['buckets'] as $row) {
            $customers = get_targeted_product_customers($customers, $db, $row['key'], ceil($threshold), false);

            $estimated_recipients = count($customers);
            if ($estimated_recipients > $threshold) {
                return $customers;
            }
        }


    }


    return $customers;


}

/**
 * @param $customers
 * @param $db \PDO
 * @param $product_id
 * @param $threshold
 *
 * @return mixed
 */
function get_spread_product_customers($customers, $db, $product_id, $threshold) {

    include_once 'elastic/assets_correlation.elastic.php';


    $customers = get_targeted_product_customers($customers, $db, $product_id, ceil($threshold / 2));


    $product = get_object('Product', $product_id);
    $counter = 0;


    $result = get_elastic_sales_correlated_assets($product->id, 'products_bought', '_1y', 50);

    foreach ($result['buckets'] as $row) {
        $counter++;
        $customers = get_targeted_product_customers($customers, $db, $row['key'], ceil($threshold / 5));

        $estimated_recipients = count($customers);
        if ($estimated_recipients > $threshold * 2) {
            return $customers;
        }
        if ($estimated_recipients > $threshold and $counter > 10) {
            return $customers;
        }
    }






    if ($product->get('Product Family Category Key')) {
        $customers = get_targeted_categories_customers($customers, $db, $product->get('Product Family Category Key'), ceil($threshold / 3));

    }

    $result = get_elastic_sales_correlated_assets($product->id, 'products_bought', '', 50);
    foreach ($result['buckets'] as $row) {
        $customers            = get_targeted_product_customers($customers, $db, $row['key'], $threshold);
        $estimated_recipients = count($customers);
        if ($estimated_recipients > $threshold) {
            return $customers;
        }
    }




    $customers            = get_targeted_product_customers($customers, $db, $product_id, $threshold);
    $estimated_recipients = count($customers);
    if ($estimated_recipients > $threshold) {
        return $customers;
    }


    if ($product->get('Product Family Category Key')) {
        $customers = get_targeted_categories_customers($customers, $db, $product->get('Product Family Category Key'), $threshold);

    }

    return $customers;


}

/**
 * @param $customers
 * @param $db \PDO
 * @param $category_key
 * @param $threshold
 *
 * @return mixed
 */
function get_targeted_categories_customers($customers, $db, $category_key, $threshold) {

    /**
     * @var $category \ProductCategory
     */
    $category = get_object('Category', $category_key);
    $store    = get_object('Store', $category->get('Store Key'));

    $products_ids = $category->get_product_ids();

    if ($products_ids != '') {
        $sql =
            "select `Customer Favourite Product Customer Key`,`Customer Main Plain Email` from `Customer Favourite Product Fact` left join `Customer Dimension` on (`Customer Favourite Product Customer Key`=`Customer Key`) where `Customer Favourite Product Product ID` in ("
            .$products_ids.") and  `Customer Main Plain Email`!=''  and `Customer Send Email Marketing`='Yes'";

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
        $category_type = '`OTF Category Family Key`';
    } elseif ($category->get('Category Root Key') == $store->get('Store Department Category Key')) {
        $category_type = '`OTF Category Department Key`';
    }


    if ($category_type) {

        $sql = sprintf(
            "select OTF.`Customer Key`,`Customer Main Plain Email` from `Order Transaction Fact`  OTF left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) where %s=? and `Order Date`>? and `Customer Send Email Marketing`='Yes'  and  `Customer Main Plain Email`!=''",
            $category_type
        );


        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $category_key,
                gmdate('Y-m-d H:i:s', strtotime('-3 months'))
            )
        );


        while ($row = $stmt->fetch()) {

            $customers[$row['Customer Key']] = $row['Customer Main Plain Email'];
        }


        $estimated_recipients = count($customers);

        if ($estimated_recipients > $threshold) {
            return $customers;
        }


        $sql  = sprintf(
            "select OTF.`Customer Key`,`Customer Main Plain Email` from `Order Transaction Fact`  OTF left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) where %s=? and `Order Date`>? and `Customer Send Email Marketing`='Yes'  and  `Customer Main Plain Email`!=''",
            $category_type
        );
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $category_key,
                gmdate('Y-m-d H:i:s', strtotime('-12 months'))
            )
        );
        while ($row = $stmt->fetch()) {
            $customers[$row['Customer Key']] = $row['Customer Main Plain Email'];
        }

        $estimated_recipients = count($customers);
        if ($estimated_recipients > $threshold) {
            return $customers;
        }

        $sql = sprintf(
            "select OTF.`Customer Key`,`Customer Main Plain Email` from `Order Transaction Fact`  OTF left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) where %s=? and `Customer Send Email Marketing`='Yes' and `Customer Main Plain Email`!=''",
            $category_type
        );


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

/**
 * @param $customers
 * @param $db \PDO
 * @param $category_key
 * @param $threshold
 *
 * @return mixed
 */
function get_spread_categories_customers($customers, $db, $category_key, $threshold) {

    include_once 'elastic/assets_correlation.elastic.php';


    $customers = get_targeted_categories_customers($customers, $db, $category_key, ceil($threshold / 2));


    $category = get_object('Category', $category_key);


    $counter = 0;

    if ($category->get('Category Subject') == 'Product') {

        $result = get_elastic_sales_correlated_assets($category->id, 'families_bought', '_1y', 25);

        foreach ($result['buckets'] as $row) {
            $customers = get_targeted_categories_customers(
                $customers, $db, $row['key'], min(ceil($threshold / 5), 1000)
            );
            $counter++;


            $estimated_recipients = count($customers);
            if ($estimated_recipients > $threshold and $counter > 5) {
                return $customers;
            }
        }




        $customers            = get_targeted_categories_customers($customers, $db, $category_key, $threshold);
        $estimated_recipients = count($customers);


        if ($estimated_recipients > $threshold and $counter > 5) {
            return $customers;
        }


        $result = get_elastic_sales_correlated_assets($category->id, 'families_bought', '', 100);

        foreach ($result['buckets'] as $row) {
            $customers = get_targeted_categories_customers(
                $customers, $db, $row['key'], $threshold
            );
            $counter++;


            $estimated_recipients = count($customers);
            if ($estimated_recipients > $threshold) {
                return $customers;
            }
        }





    } elseif ($category->get('Category Subject') == 'Category') {



        $result = get_elastic_sales_correlated_assets($category->id, 'departments_bought', '_1y', 5);

        foreach ($result['buckets'] as $row) {
            $customers = get_targeted_categories_customers($customers, $db, $row['key'], min(ceil($threshold / 5), 1000));
            $counter++;
            $estimated_recipients = count($customers);


            if ($estimated_recipients > $threshold and $counter > 2) {
                return $customers;
            }
        }





        $customers            = get_targeted_categories_customers($customers, $db, $category_key, $threshold);
        $estimated_recipients = count($customers);

        if ($estimated_recipients > $threshold and $counter > 5) {
            return $customers;
        }



        $result = get_elastic_sales_correlated_assets($category->id, 'departments_bought', '', 100);

        foreach ($result['buckets'] as $row) {
            $customers = get_targeted_categories_customers(
                $customers, $db, $row['key'], $threshold
            );
            $counter++;


            $estimated_recipients = count($customers);
            if ($estimated_recipients > $threshold) {
                return $customers;
            }
        }





    }


    return $customers;


}
