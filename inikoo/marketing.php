<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Marketing');
$smarty->assign('page','marketing');



$smarty->display('info.tpl');
?>
