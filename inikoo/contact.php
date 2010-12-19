<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Our Details');
$smarty->assign('page','contact');
$smarty->display('info_general.tpl');
?>
