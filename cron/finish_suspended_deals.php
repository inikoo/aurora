<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2018 at 14:43:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
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


$sql = sprintf("SELECT `Deal Key`,`Deal Store Key` FROM `Deal Dimension` where `Deal Campaign Key` is null ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal         = get_object('Deal', $row['Deal Key']);
        $deal->editor = $editor;

        $campaign = get_object('campaign_code-store_key', 'VO|'.$row['Deal Store Key']);

        $sql = sprintf(
            'update `Deal Component Dimension` set `Deal Component Campaign Key`=%d  where `Deal Component Deal Key`=%d', $campaign->id, $deal->id
        );


        $db->exec($sql);
        $deal->fast_update(
            array(
                'Deal Campaign Key' => $campaign->id
            )
        );

        $deal->finish();


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

