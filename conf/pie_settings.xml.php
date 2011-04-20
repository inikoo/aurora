<?php
chdir("../");
include_once('common.php');
//if (!isset($_REQUEST['tipo'])) {
//    exit;
//}


    $smarty->assign('data',$_REQUEST);


$smarty->assign('locale_data',localeconv());

$smarty->display('pie_settings.xml.tpl');
?>
