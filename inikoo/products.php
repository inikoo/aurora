<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Products Database');
$smarty->assign('page','products');



$smarty->display('info.tpl');
?>
