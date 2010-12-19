<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Stock Control');
$smarty->assign('page','inventory');
$smarty->display('info.tpl');
?>
