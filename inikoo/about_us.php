<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','About Us');
$smarty->assign('page','about_us');
$smarty->display('info_general.tpl');
?>
