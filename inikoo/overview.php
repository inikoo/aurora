<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Inikoo Components Overview');
$smarty->assign('page','overview');
$smarty->display('info.tpl');
?>
