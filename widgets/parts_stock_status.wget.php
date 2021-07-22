<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 October 2018 at 13:46:53 GMT+8, Kuala Lumpur,  Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/


function get_dashboard_parts_stock_status($db,$type ,$user, $smarty, $parent = '') {


    include_once 'utils/date_functions.php';

    $smarty->assign('user', $user);


    if ($parent != '') {

        $object = get_object('Warehouse',$parent);

        //  print_r($_object);
        $title = sprintf(_('Warehouse %d'),$object->get('Name'));

    } else {
        $object = new Account();
        $object->load_acc_data();
        $title = _('Warehouse');
    }



    $smarty->assign('store_title', $title);
    $smarty->assign('object', $object);
    $smarty->assign('parent', $parent);


    if($type=='inventory_excluding_production'){

        return $smarty->fetch('dashboard/parts_stock_status.dbard.tpl');

    }else if($type=='production'){
        $sql = 'SELECT `Supplier Production Supplier Key` FROM `Supplier Production Dimension` left join `Supplier Dimension` on (`Supplier Key`=`Supplier Production Supplier Key`) WHERE `Supplier Type`!=?';

        $stmt = $db->prepare($sql);
        $stmt->execute(
            array('Archived')
        );
        if ($row = $stmt->fetch()) {
            $manufacturer_key = $row['Supplier Production Supplier Key'];
            $smarty->assign('production_supplier_key', $manufacturer_key);

            return $smarty->fetch('dashboard/production_parts_stock_status.dbard.tpl');

        }



    }else{
        return '';
    }


}



