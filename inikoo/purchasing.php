<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Supplier Relationship Management');
$smarty->assign('page','purchasing');



$smarty->display('info.tpl');
?>
