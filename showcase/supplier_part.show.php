<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 April 2016 at 20:06:13 GMT+8,  Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_supplier_part_showcase($data, $smarty, $user, $db) {


    $supplier_part = $data['_object'];


    $part = $data['_object']->part;
    if (!$part->id) {
        return "";
    }


    $sql = sprintf(
        "SELECT `Category Label`,`Category Code`,`Category Key` FROM `Category Dimension` WHERE `Category Key`=%d ", $part->get('Part Family Category Key')
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

    $smarty->assign('supplier_part', $supplier_part);
    $smarty->assign('part', $part);
    $smarty->assign('family_data', $family_data);

    return $smarty->fetch('showcase/supplier_part.tpl');


}


?>
