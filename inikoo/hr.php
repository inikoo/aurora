<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Human Resources');
$smarty->assign('page','hr');
$smarty->display('info.tpl');
?>
