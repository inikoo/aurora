<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 August 2018 at 00:13:49 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

/**
 * @param $data
 * @param $smarty \Smarty
 *
 * @return mixed
 * @throws \SmartyException
 */
function get_payment_showcase($data, $smarty){

    $smarty->assign('payment', $data['_object']);
    return $smarty->fetch('showcase/payment.tpl');

}