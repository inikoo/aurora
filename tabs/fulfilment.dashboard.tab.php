<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 June 2021 21:11 GMT+8, Cyberjaya , Malaysia
 Copyright (c) 2017, Inikoo

 Version 3.0
*/
/** @var User $user */
/** @var \Smarty $smarty */
/** @var array $state */
/** @var \Account $account */

$account->load_acc_data();

/**
 * @var $warehouse \Warehouse
 */
$warehouse=$state['_object'];


$html='
<div style="background-color: blue;color: whitesmoke;padding: 20px">
<h1>Hi, aurora fulfilment is being replaced with <a style="font-size: x-large;color: white;text-decoration: underline" href="https://app.aiku.io">aiku</a></h1>

<p>You can log with your same username and password to <a style="color: white;text-decoration: underline"  href="https://app.aiku.io">https://aiku.io</a></p>


<p>Data has been transferred there, so please do not use aurora because all changes done here will be probably lost</p>



</div>

';

