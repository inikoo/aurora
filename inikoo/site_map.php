<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Site Map');
$smarty->assign('page','site_map');
$smarty->display('info_general.tpl');
?>
