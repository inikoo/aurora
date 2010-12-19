<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Jobs');
$smarty->assign('page','jobs');
$smarty->display('info_general.tpl');
?>
