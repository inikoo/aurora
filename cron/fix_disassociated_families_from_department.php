<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW


require_once 'common.php';

require_once 'class.Store.php';
require_once 'class.Category.php';
require_once 'class.Product.php';
require_once 'class.Part.php';


$counter = 0;

$editor = array(
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Script)',
    'Author Alias' => 'System (Script)',
    'v'            => 3


);


$store_old_ds = new Store('code', 'DS');
$store_new_ds = new Store('code', 'AWD');
$store_aw     = new Store('code', 'UK');

$new_ds_website = get_object('Website', $store_new_ds->get('Store Website Key'));

$fam_root = get_object('Category', 15038);


$sql = sprintf('select `Store Key`  from `Store Dimension`');


if ($result2 = $db->query($sql)) {
    foreach ($result2 as $row2) {

        $store = get_object('Store', $row2['Store Key']);

        $sql = sprintf(
            'SELECT  `Category Code`,`Category Label`,`Category Key` FROM  `Category Dimension` C  WHERE   C.`Category Root Key`=%d   ', $store->get('Store Family Category Key')
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                $cat = get_object('Category', $row['Category Key']);

                //print $cat->get('Product Category Department Category Key')."\n";

                //if($cat->get('Product Category Department Category Key')){
                $dep = get_object('Category', $cat->get('Product Category Department Category Key'));

                if ($dep->id and $cat->id){

                    $dep->associate_subject($cat->id);


                }


            }
        }


    }
}
