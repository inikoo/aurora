<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2015 at 16:31:19 CET, Pisa-Milan (train), Italy

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_account_showcase($data, $smarty) {
    $account = $data['_object'];


    $smarty->assign('account', $account);

    return $smarty->fetch('showcase/account.tpl');


}


?>