<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  16:09:56 MYTTuesday, 14 July 2020, Kuala Lumpur Malaysia

 Copyright (c) 2020, Inikoo

 Version 3.0
*/


function get_new_shipper_showcase($data, $smarty, $account){

    include_once 'conf/api_shippers.php';




    $shippers_data=get_shippers_data($account->get('Account Country 2 Alpha Code'));

    $smarty->assign('shippers_data',$shippers_data);
    return $smarty->fetch('shipper_chooser.tpl');

    print_r($api_shippers);

}
