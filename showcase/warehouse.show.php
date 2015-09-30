<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 229 September 2015 14:30:52 BST, Sheffield, UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_warehouse_showcase($data) {

    global $smarty;
    
    $warehouse=new Warehouse($data['key']);
    
    $smarty->assign('warehouse',$warehouse);

    return $smarty->fetch('showcase/warehouse.tpl');
    


}


?>