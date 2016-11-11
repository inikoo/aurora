<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:27 August 2015 12:49:03 GMT+8, Singapure
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';

$smarty->assign('_request', $_SERVER['REQUEST_URI']);
$smarty->assign(
    'show_help', (isset($_SESSION['show_help']) ? $_SESSION['show_help'] : false)
);

$mobile=false;

if(isset($_SESSION['device']) and $_SESSION['device']=='desktop'){
    $mobile=false;

}


if($mobile){
    $smarty->display('app.mobile.tpl');
}else{
    $smarty->display('app.tpl');
}



?>
