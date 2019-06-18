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

$counter = 0;


$sql = sprintf("SELECT `Deal Key` FROM `Deal Dimension` ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal         = get_object('Deal', $row['Deal Key']);
        $deal->editor = $editor;


        $store = get_object('Store', $deal->get('Store Key'));


        if ($deal->get('Deal Terms Type') == 'Product Quantity Ordered') {



            if ($deal->get('Deal Status') == 'Suspended') {
                $deal->finish();
            }else{




                if ($deal->get('Deal Trigger') == 'Category') {
                    $category = get_object('Category', $deal->get('Deal Trigger Key'));
                    $category->update_product_category_products_data();

                    if ($category->get('Product Category Status') == 'Discontinuing' or $category->get('Product Category Status') == 'Active') {

                        print $counter++."\n";
                        print_r($deal->data);

                        $deal->finish();

                    } else {

                        $deal->finish();

                    }


                }
            }







        }
    }


}

