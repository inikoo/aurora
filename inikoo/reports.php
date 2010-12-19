<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Report Tools');
$smarty->assign('page','reports');



$smarty->display('info.tpl');
?>
