<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Order Management');
$smarty->assign('page','invoicing');
$smarty->display('info.tpl');
?>
