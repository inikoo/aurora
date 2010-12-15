<?php
date_default_timezone_set('Europe/London');
require('../external_libs/Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Inikoo');


$smarty->display('index.tpl');
?>
