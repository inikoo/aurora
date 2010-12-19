<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Terms of Use');
$smarty->assign('page','terms_use');
$smarty->display('info_general.tpl');
?>
