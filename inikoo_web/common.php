<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
require('common_functions.php');
$smarty = new Smarty;
$smarty->assign('current_page_url',curPageURL());

?>