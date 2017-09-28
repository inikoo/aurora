<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 September 2017 at 22:54:36 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_timeseries_record_showcase($data, $smarty, $user, $db,$account) {


    $timeseries_record = $data['_object'];

   

    if (!$timeseries_record->id) {
        return "";
    }

   
    $smarty->assign('account', $account);

    $smarty->assign('timeseries_record', $timeseries_record);

    return $smarty->fetch('showcase/timeseries_record.tpl');


}


?>