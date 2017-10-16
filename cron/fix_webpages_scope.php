<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2017 at 17:47:12 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Site.php';
require_once 'class.Page.php';
require_once 'class.Website.php';
require_once 'class.WebsiteNode.php';
require_once 'class.Webpage.php';
require_once 'class.Product.php';
require_once 'class.Store.php';
require_once 'class.Public_Product.php';
require_once 'class.Category.php';
require_once 'class.Webpage_Type.php';
include_once 'class.Webpage_Type.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


set_scope($db);


function set_scope($db) {


        $sql = sprintf('UPDATE  `Page Store Dimension`  SET `Webpage Code`=`Page Code`  WHERE `Webpage Code` IS NULL  ');
        $db->exec($sql);



        $sql = sprintf('UPDATE  `Page Store Dimension`  SET `Webpage Website Key`=`Page Site Key`  WHERE `Webpage Website Key` IS NULL  ');
        $db->exec($sql);

        $sql = sprintf('UPDATE  `Page Store Dimension`  SET `Webpage Store Key`=`Page Store Key`  WHERE `Webpage Store Key` IS NULL  ');
        $db->exec($sql);

      $sql = sprintf('UPDATE  `Page Store Dimension`  SET `Webpage Meta Description`=`Page Store Description`  WHERE `Webpage Meta Description` IS NULL or `Webpage Meta Description`="" ');
        $db->exec($sql);



        $sql = sprintf('UPDATE  `Page Store Dimension`  SET `Webpage Creation Date`=`Page Store Creation Date`  WHERE `Webpage Creation Date` IS NULL  ');
        $db->exec($sql);

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE (`Webpage Scope Key` IS NULL OR  `Webpage Scope Key`=0 ) and  `Page Store Section`="Product Description"  ');


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $webpage = new Page($row['Page Key']);
            $store   = new Store($row['Page Store Key']);


            //======
            $scope = false;

            if ($webpage->data['Page Store Section'] == 'Product Description') {
                include_once('class.Public_Product.php');



                $scope       = new Public_Product($webpage->data['Page Parent Key']);
                $scope_found = 'Product';


                if(!$scope->id){
                    print "delete page\n";
                    print_r($row);
                    $webpage->delete(false);
                }


            } elseif ($webpage->data['Page Store Section'] == 'Category') {
                include_once('class.Public_Category.php');

                $scope       = new Public_Category($webpage->data['Page Parent Key']);
                $scope_found = 'Category';

            } elseif ($webpage->data['Page Store Section'] == 'Family Catalogue') {

                include_once('class.Store.php');

                include_once('class.Public_Category.php');
                $store    = new Store($webpage->get('Page Store Key'));
                $category = new Public_Category('root_key_code', $store->get('Store Family Category Key'), $webpage->get('Code'));
                if ($category->id) {
                    $scope       = $category;
                    $scope_found = 'Category';

                } else {

                    $sql = sprintf('SELECT `Product Family Code`  FROM `Product Family Dimension` WHERE `Product Family Key`=%d ', $webpage->data['Page Parent Key']);


                    if ($result2 = $db->query($sql)) {
                        if ($row2 = $result2->fetch()) {

                            $category = new Public_Category('root_key_code', $store->get('Store Family Category Key'), $row2['Product Family Code']);
                            if ($category->id) {
                                $scope       = $category;
                                $scope_found = 'Category';

                            } else {
                                print 'F '.$webpage->get('Code').' '.$webpage->data['Page Parent Code'].' '."$sql\n";

                            }

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                }


            } elseif ($webpage->data['Page Store Section'] == 'Department Catalogue') {

                include_once('class.Store.php');

                include_once('class.Public_Category.php');
                $store    = new Store($webpage->get('Page Store Key'));
                $category = new Public_Category('root_key_code', $store->get('Store Department Category Key'), $webpage->get('Code'));
                if ($category->id) {
                    $scope       = $category;
                    $scope_found = 'Category';

                } else {

                    $sql = sprintf('SELECT `Product Department Code`  FROM `Product Department Dimension` WHERE `Product Department Key`=%d ', $webpage->data['Page Parent Key']);


                    if ($result2 = $db->query($sql)) {
                        if ($row2 = $result2->fetch()) {

                            $category = new Public_Category('root_key_code', $store->get('Store Department Category Key'), $row2['Product Department Code']);
                            if ($category->id) {
                                $scope       = $category;
                                $scope_found = 'Category';

                            } else {
                                print 'D '.$webpage->get('Code').' '.$webpage->data['Page Parent Code'].' '."$sql\n";

                            }

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                }


            }


            //===



            //exit;
            if ($scope and $scope->id and   $scope->get_object_name() == 'Product') {


                if ($scope->get_object_name() == 'Product') {
                    $product=$scope;
                    $webpage_type = new Webpage_Type('website_code', $webpage->get('Webpage Website Key'), 'Prod');


                    $content_data = array(
                        'description_block' => array(
                            'class' => '',

                            'content' => sprintf('<div class="description">%s</div>', $scope->get('Description'))


                        ),
                        'tabs'              => array()

                    );


                    $webpage->update(
                        array(
                            'Webpage Scope'             => 'Product',
                            'Webpage Template Filename' => 'product',
                            'Page Store Content Data'   => json_encode($content_data),
                            'Webpage Type Key'          => $webpage_type->id,
                            'Webpage Scope Key'         => $scope->id,
                            'Webpage Name'              => $product->get('Name'),
                            'Webpage Browser Title'     => $product->get('Name'),




                        ), 'no_history'
                    );

                    $webpage->update_url();


                } elseif ($scope->get_object_name() == 'Category') {


                    $category = new Category($scope->id);

                    if ($category->get('Category Subject') == 'Product') {

                        $webpage->update(
                            array(
                                'Webpage Scope'     => 'Category Products',
                                'Webpage Scope Key' => $category->id
                            ), 'no_history'
                        );


                        $webpage->update_url();

                    } elseif ($category->get('Category Subject') == 'Category') {
                        //print $category->get('Category Subject');

                        $webpage_type = new Webpage_Type('website_code', $webpage->get('Webpage Website Key'), ($category->get('Category Subject') == 'Product' ? 'Prods' : 'Cats'));


                        $webpage->update(
                            array(
                                'Webpage Scope'     => 'Category Categories',
                                'Webpage Scope Key' => $scope->id,
                                'Webpage Type Key'          => $webpage_type->id,

                            ), 'no_history'
                        );
                        $webpage->update_url();

                        if ($category->get('Category Root Key') == $store->get('Store Family Category Key')) {

                            $webpage->update(
                                array(
                                    'Webpage Scope Metadata' => 'Family',
                                ), 'no_history'
                            );
                        } elseif ($category->get('Category Root Key') == $store->get('Store Department Category Key')) {

                            $webpage->update(
                                array(
                                    'Webpage Scope Metadata' => 'Department',
                                ), 'no_history'
                            );
                        }

                    }


                }

            } else {

                if ($row['Page Store Section'] == 'Family Catalogue') {
                    //  print "Family ".$row['Page Parent Code']."\n";


                }
                if ($row['Page Store Section'] == 'Department Catalogue') {
                    //print "Dept  ".$row['Page Store Key']." ".$row['Page Parent Code']."\n";

                }


            }
            $webpage->update_version();

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    /*
        $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` ');


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                $webpage = new Page($row['Page Key']);
                $webpage->update_version();



            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }
    */

    exit;

    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE `Webpage Scope`="Category Categories" AND `Page Key`=51 ');
    $sql = sprintf('SELECT `Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code` FROM `Page Store Dimension` WHERE `Webpage Scope`="Category Categories" ');


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $webpage = new Page($row['Page Key']);
            //  print $webpage->id.' '.$webpage->get('Code')."\n";
            $webpage->reindex_items();
            $webpage->publish();


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }
}


?>
