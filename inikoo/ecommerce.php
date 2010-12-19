<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','E-commerce');
$smarty->assign('page','ecommerce');



$smarty->display('info.tpl');
?>
