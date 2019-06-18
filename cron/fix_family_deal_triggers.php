<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2018 at 14:43:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/order_functions.php';

require_once 'class.Category.php';



$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Migration from inikoo)',
    'Author Alias' => 'System (Migration from inikoo)',


);



$sql = sprintf("SELECT `Deal Key` FROM `Deal Dimension` ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal = get_object('Deal', $row['Deal Key']);

        $deal->editor=$editor;

        if ($deal->get('Deal Trigger') == 'Family') {



            $store = get_object('Store', $deal->get('Store Key'));
            $store->editor=$editor;


            if (preg_match('/family\.php\?id\=(\d+)/', $deal->get('Deal Term Allowances'), $matches)) {


                $category_data = false;


                $sql = sprintf('select `Product Family Code` from `Product Family Dimension` where `Product Family Key`=%d  ', $matches[1]);

                //  print $sql;


                if ($result2 = $db->query($sql)) {
                    foreach ($result2 as $row2) {


                        //   print_r($row2);

                        $category = new Category('root_key_code', $store->get('Store Family Category Key'), $row2['Product Family Code']);


                        if($deal->get('Deal Terms Type')=='Family Quantity Ordered'){
                            $terms_type='Category Quantity Ordered';
                        }elseif($deal->get('Deal Terms Type')=='Family For Every Quantity Ordered'){
                            $terms_type='Family For Every Quantity Ordered';
                        }elseif($deal->get('Deal Terms Type')=='Family For Every Quantity Any Product Ordered'){
                            $terms_type='Family For Every Quantity Any Product Ordered';
                        }else{

                            print_r($deal);
                            exit();
                        }


                        if ($category->id) {

                            $category_data = array(
                                'Deal Trigger'=>'Category',
                                'Deal Trigger Key' => $category->id,
                                'Deal Terms Type'  => $terms_type
                            );
                            $deal->fast_update($category_data);
                            $deal->update_deal_term_allowances();



                        }else{


                            $category_data = array(
                                'Deal Trigger'=>'Category',
                                'Deal Trigger Key' => 0,
                                'Deal Terms Type'  => $terms_type
                            );
                            $deal->fast_update($category_data);
                            $deal->update_deal_term_allowances();

                            $deal->finish();

                        }


                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }




            }
            elseif (preg_match('/family\.php\?id\=(\d+)/', $deal->get('Deal Trigger XHTML Label'), $matches)) {


                $category_data = false;


                $sql = sprintf('select `Product Family Code` from `Product Family Dimension` where `Product Family Key`=%d  ', $matches[1]);

                //  print $sql;


                if ($result2 = $db->query($sql)) {
                    foreach ($result2 as $row2) {


                        //   print_r($row2);

                        $category = new Category('root_key_code', $store->get('Store Family Category Key'), $row2['Product Family Code']);


                        if($deal->get('Deal Terms Type')=='Family Quantity Ordered'){
                            $terms_type='Category Quantity Ordered';
                        }elseif($deal->get('Deal Terms Type')=='Family For Every Quantity Ordered'){
                            $terms_type='Family For Every Quantity Ordered';
                        }elseif($deal->get('Deal Terms Type')=='Family For Every Quantity Any Product Ordered'){
                            $terms_type='Family For Every Quantity Any Product Ordered';
                        }elseif($deal->get('Deal Terms Type')=='Product Quantity Ordered'){
                            $terms_type='Product Quantity Ordered';
                        }else{

                            print_r($deal);
                            exit('ZZZ');
                        }


                        if ($category->id) {

                            $category_data = array(
                                'Deal Trigger'=>'Category',
                                'Deal Trigger Key' => $category->id,
                                'Deal Terms Type'  => $terms_type
                            );
                            $deal->fast_update($category_data);
                            $deal->update_deal_term_allowances();



                        }else{


                            $category_data = array(
                                'Deal Trigger'=>'Category',
                                'Deal Trigger Key' => 0,
                                'Deal Terms Type'  => $terms_type
                            );
                            $deal->fast_update($category_data);
                            $deal->update_deal_term_allowances();

                            $deal->finish();

                        }


                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }




            }
            else{
                print_r($deal);
            }







        }

    }
}




$sql = sprintf("SELECT `Deal Component Key` FROM `Deal Component Dimension` ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal_component = get_object('DealComponent', $row['Deal Component Key']);
        $deal_component->editor=$editor;

        if ($deal_component->get('Deal Component Trigger') == 'Family') {

            $store = get_object('Store', $deal_component->get('Store Key'));


            $deal = get_object('Deal', $deal_component->get('Deal Key'));




            if ($deal_component->get('Deal Component Allowance Target') != 'Family' or $deal_component->get('Deal Component Allowance Target') != $deal_component->get('Deal Component Allowance Target Key')) {
                if ($deal_component->get('Deal Component Terms Type') == 'Family Quantity Ordered') {
                    $terms_type = 'Category Quantity Ordered';
                } elseif ($deal_component->get('Deal Component Terms Type') == 'Family For Every Quantity Ordered') {
                    $terms_type = 'Category For Every Quantity Ordered';
                } elseif ($deal_component->get('Deal Component Terms Type') == 'Family For Every Quantity Any Product Ordered') {
                    $terms_type = 'Category For Every Quantity Any Product Ordered';
                } elseif ($deal_component->get('Deal Component Terms Type') == 'Order Interval') {
                    $terms_type = 'Order Interval';
                } elseif ($deal_component->get('Deal Component Terms Type') == 'Product For Every Quantity Ordered') {
                    $terms_type = 'Product For Every Quantity Ordered';
                } else {

                    print_r($deal_component);
                    exit('---a---');
                }


                $category_data = array(
                    'Deal Component Trigger'     => 'Category',
                    'Deal Component Trigger Key' => 0,
                    'Deal Component Terms Type'  => $terms_type
                );
                $deal_component->fast_update($category_data);

            } else {

                if (preg_match('/family\.php\?id\=(\d+)/', $deal_component->get('Deal Component Allowance Target XHTML Label'), $matches)) {


                    $category_data = false;


                    $sql = sprintf('select `Product Family Code` from `Product Family Dimension` where `Product Family Key`=%d  ', $matches[1]);

                    //  print $sql;


                    if ($result2 = $db->query($sql)) {
                        foreach ($result2 as $row2) {


                            //   print_r($row2);

                            $category = new Category('root_key_code', $store->get('Store Family Category Key'), $row2['Product Family Code']);


                            if ($deal_component->get('Deal Component Terms Type') == 'Family Quantity Ordered') {
                                $terms_type = 'Category Quantity Ordered';
                            } elseif ($deal_component->get('Deal Component Terms Type') == 'Family For Every Quantity Ordered') {
                                $terms_type = 'Category For Every Quantity Ordered';
                            } elseif ($deal_component->get('Deal Component Terms Type') == 'Family For Every Quantity Any Product Ordered') {
                                $terms_type = 'Category For Every Quantity Any Product Ordered';
                            } elseif ($deal_component->get('Deal Component Terms Type') == 'Order Interval') {
                                $terms_type = 'Order Interval';
                            } elseif ($deal_component->get('Deal Component Terms Type') == 'Product For Every Quantity Ordered') {
                                $terms_type = 'Product For Every Quantity Ordered';
                            } else {

                                print_r($deal_component);
                                exit('---b---');
                            }


                            if ($category->id) {

                                $category_data = array(
                                    'Deal Component Trigger'     => 'Category',
                                    'Deal Component Trigger Key' => $category->id,
                                    'Deal Component Terms Type'  => $terms_type
                                );
                                $deal_component->fast_update($category_data);


                            } else {


                                $category_data = array(
                                    'Deal Component Trigger'     => 'Category',
                                    'Deal Component Trigger Key' => 0,
                                    'Deal Component Terms Type'  => $terms_type
                                );
                                $deal_component->fast_update($category_data);


                            }


                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                }
                else{

                    print_r($deal_component);
                    exit('---c---');


                }

            }

        }
    }
}





$sql = sprintf("SELECT `Deal Component Key` FROM `Deal Component Dimension` ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal_component = get_object('DealComponent', $row['Deal Component Key']);


        $deal_component->editor=$editor;


        if ($deal_component->get('Deal Component Allowance Target') == 'Family') {

            $store = get_object('Store', $deal_component->get('Store Key'));


            $deal = get_object('Deal', $deal_component->get('Deal Key'));






                if (preg_match('/family\.php\?id\=(\d+)/', $deal_component->get('Deal Component Allowance Target XHTML Label'), $matches)) {


                    $category_data = false;


                    $sql = sprintf('select `Product Family Code` from `Product Family Dimension` where `Product Family Key`=%d  ', $matches[1]);

                    //  print $sql;


                    if ($result2 = $db->query($sql)) {
                        foreach ($result2 as $row2) {


                            //   print_r($row2);

                            $category = new Category('root_key_code', $store->get('Store Family Category Key'), $row2['Product Family Code']);


                            if ($deal_component->get('Deal Component Allowance Type') == 'Percentage Off') {
                                $allowance_type = 'Percentage Off';
                            }elseif ($deal_component->get('Deal Component Allowance Type') == 'Get Same Free') {
                                $allowance_type = 'Get Same Free';
                            } elseif ($deal_component->get('Deal Component Allowance Type') == 'Get Cheapest Free') {
                                $allowance_type = 'Get Same Free';
                            } else {

                                print_r($deal_component);
                                exit('---b---');
                            }


                            if ($category->id) {

                                $category_data = array(
                                    'Deal Component Allowance Target'     => 'Category',
                                    'Deal Component Allowance Target Key' => $category->id,
                                    'Deal Component Allowance Type'  => $allowance_type
                                );
                                $deal_component->fast_update($category_data);


                            } else {


                                $category_data = array(
                                    'Deal Component Allowance Target'     => 'Category',
                                    'Deal Component Allowance Target Key' => 0,
                                    'Deal Component Allowance Type'  => $allowance_type
                                );
                                $deal_component->fast_update($category_data);


                            }


                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                }
                else{

                    print_r($deal_component);
                    exit('---c---');


                }



        }
    }
}


$sql=sprintf('update `Deal Dimension` set `Deal Terms Type`="Category For Every Quantity Any Product Ordered" where    `Deal Terms Type`="Family For Every Quantity Any Product Ordered"     ');
$db->exec($sql);

$sql=sprintf('update `Deal Component Dimension` set `Deal Component Terms Type`="Category For Every Quantity Any Product Ordered" where    `Deal Component Terms Type`="Family For Every Quantity Any Product Ordered"     ');
$db->exec($sql);


$sql="update  `Deal Dimension`  set `Deal Terms`=1   where `Deal Terms Type`='Category Quantity Ordered' and `Deal Terms` is null;";
$db->exec($sql);
