<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Intranet');
$smarty->assign('page','intranet');



$smarty->display('info.tpl');
?>
