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


$sql = sprintf("SELECT `Deal Key` FROM `Deal Dimension` where `Deal Status`='Finish' ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal         = get_object('Deal', $row['Deal Key']);
        $deal->editor = $editor;

        $campaign=get_object('Campaign',$deal->get('Deal Campaign Key'));


        if($campaign->get('Code')!='OR'){

            $sql=sprintf('update `Deal Component Dimension` set `Deal Component Status`="Finish" where `Deal Component Deal Key`=%d ',$deal->id);
            print $sql."\n";

            $db->exec($sql);

        };





    }
}





exit;

$sql = sprintf("SELECT `Deal Key` FROM `Deal Dimension` ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal         = get_object('Deal', $row['Deal Key']);
        $deal->editor = $editor;


        if ($deal->get('Deal Status') == 'Suspended') {
            $deal->finish();
        }


    }
}


$sql = sprintf("SELECT `Deal Key` FROM `Deal Dimension` where `Deal Status`!='Finish' ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal         = get_object('Deal', $row['Deal Key']);
        $deal->editor = $editor;


        $store = get_object('Store', $deal->get('Store Key'));


        if ($deal->get('Deal Trigger') == 'Category') {
            $category = get_object('Category', $deal->get('Deal Trigger Key'));
            $category->update_product_category_products_data();

            print $category->get('Product Category Status')."\n";

            if ($category->get('Product Category Status') == 'Suspended' or $category->get('Product Category Status') == 'Discontinued') {

                $deal->finish();

            }

        }


    }


}

