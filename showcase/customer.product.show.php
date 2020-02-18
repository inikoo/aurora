<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2018 at 00:15:31 GMT+8,  Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/


function get_customer_product_showcase($data, $smarty, $user, $db) {


    $product = $data['_object'];


    if (!$product->id) {
        return "";
    }

    $product->load_acc_data();
    $product->get_webpage();


    $images = $product->get_images_slideshow();

    if (count($images) > 0) {
        $main_image = $images[0];
    } else {
        $main_image = '';
    }


    $smarty->assign('main_image', $main_image);
    $smarty->assign('images', $images);

    $sql = sprintf(
        "SELECT `Category Label`,`Category Code`,`Category Key` FROM `Category Dimension` WHERE `Category Key`=%d ", $product->get('Product Department Category Key')
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $department_data = array(
                'id'    => $row['Category Key'],
                'code'  => $row['Category Code'],
                'label' => $row['Category Label'],
            );
        } else {
            $department_data = array('id' => false);
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $sql = sprintf(
        "SELECT `Category Label`,`Category Code`,`Category Key` FROM `Category Dimension` WHERE `Category Key`=%d ", $product->get('Product Family Category Key')
    );
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $family_data = array(
                'id'    => $row['Category Key'],
                'code'  => $row['Category Code'],
                'label' => $row['Category Label'],
            );
        } else {
            $family_data = array('id' => false);
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $smarty->assign('product', $product);
    $smarty->assign('department_data', $department_data);
    $smarty->assign('family_data', $family_data);


    return $smarty->fetch('showcase/customer.product.tpl');


}


?>
