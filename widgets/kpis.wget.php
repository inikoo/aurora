<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 February 2017 at 12:23:16 GMT+8, Kuala Lumpur, , Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


function get_dashboard_kpis($db, $user, $smarty, $period) {


    include_once 'utils/date_functions.php';


    include_once 'class.Warehouse.php';

    include_once 'class.Supplier_Production.php';

    $smarty->assign('user', $user);



    $warehouse=new Warehouse(1);
    $smarty->assign('warehouse', $warehouse);
    $smarty->assign('kpis_period', $period);




    $sql= 'select `Supplier Production Supplier Key`  from `Supplier Production Dimension`';
    if ($result=$db->query($sql)) {
        if ($row = $result->fetch()) {
            $supplier_production=new Supplier_Production($row['Supplier Production Supplier Key']);
            $smarty->assign('supplier_production', $supplier_production);

        }
    }

    return $smarty->fetch('dashboard/kpis.dbard.tpl');

}

