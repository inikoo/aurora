<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 April 2016 at 20:06:13 GMT+8,  Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_supplier_part_showcase($data, $smarty, $user, $account) {

    $account->load_acc_data();

    $supplier_part = $data['_object'];

    $part = $supplier_part->part;
    if (!$part->id) {
        return "";
    }



    $labels_data['unit']=json_decode($part->properties('label_unit'),true);
    $labels_data['sko']=json_decode($part->properties('label_sko'),true);


    if($part->get('Part Number Supplier Parts')==1){
        $supplier_part=get_object('SupplierPart',$part->get('Part Main Supplier Part Key'));
        $labels_data['carton']=json_decode($supplier_part->properties('label_carton'),true);

    }

    if($labels_data['unit']==''){

        $labels_data['unit']=json_decode($account->properties('part_label_unit'),true);
    }
    if($labels_data['sko']==''){
        $labels_data['sko']=json_decode($account->properties('part_label_sko'),true);
    }
    if($labels_data['carton']==''){
        $labels_data['carton']=json_decode($account->properties('part_label_carton'),true);
    }


    $smarty->assign('labels_data', $labels_data);



    $smarty->assign('supplier_part', $supplier_part);
    $smarty->assign('part', $part);

    $supplier=get_object('Supplier',$supplier_part->get('Supplier Part Supplier Key'));
    $smarty->assign('supplier', $supplier);


    //$smarty->assign('family_data', $family_data);


    if ($user->get('User Type') == 'Agent') {
        return $smarty->fetch('showcase/agent_part.tpl');
    } else {
        return $smarty->fetch('showcase/supplier_part.tpl');

    }


}



