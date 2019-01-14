<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  10 January 2019 at 15:23:29 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/

function get_shipper_showcase($data, $smarty, $user, $db) {


    $shipper = $data['_object'];

    $smarty->assign('shipper', $shipper);


    return $smarty->fetch('showcase/shipper.tpl');


}


?>
