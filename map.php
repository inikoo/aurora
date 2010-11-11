<?php
/*
file: map.php

 */
include_once('common.php');
$ammap_path='external_libs/ammap_2.5.5';


    $smarty->assign('ammap_path',$ammap_path);

require_once 'common.php';



if (isset($_REQUEST['country']))
    $tipo='country';




switch ($tipo) {
case('world'):



    break;

case('country'):

    $code=$_REQUEST['country'];
    $opt='';
    if (isset($_REQUEST['opt'])) {
        $opt=$_REQUEST['opt'];
    }

    $smarty->assign('path',$ammap_path);
    $data_file=$ammap_path.'/data/country/'.$code.$opt.'.xml';
    $smarty->assign('data_file',$data_file);
    $smarty->display('map.tpl');


    break;

}



?>
