<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Customer Relationship Management');
$smarty->assign('page','crm');
$smarty->display('info.tpl');
?>
