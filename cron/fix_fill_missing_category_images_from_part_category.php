<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2017 at 22:43:03 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Category.php';



$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Fill images from part family)',
    'Author Alias' => 'System (Fill images from part family)',


);

$where = " where `Category Key`=11899 ";
$where = "where true";


$sql = sprintf(
    "select `Part Category Key` from `Part Category Dimension`  left join `Category Dimension`  on (`Category Key`=`Part Category Key`)   $where   "
);

//print "$sql\n";

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $category = new Category($row['Part Category Key']);
        $category->editor=$editor;

        $image_key= $category->get_main_image_key();

        if($category->get('Code')!='' and $image_key>0){


            $sql = sprintf(
                'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Product" AND `Category Code`=%s  ', prepare_mysql($category->get('Code'))
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    $product_category         = new Category($row['Category Key']);
                    $product_category->editor = $category->editor;

                    if(!$product_category->get_main_image_key()){

                        print   $category->get('Code').' '.$image_key."\n";
                        $product_category->link_image($image_key, 'Marketing');

                    }



                }

            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }
        }



    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
