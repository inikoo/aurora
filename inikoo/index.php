<?php
date_default_timezone_set('Europe/London');
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->assign('page_title','Inikoo');

/*
create mode 100644 inikoo/css/style.css
 create mode 100644 inikoo/images/home_header.jpg
 create mode 100644 inikoo/index.php
 create mode 100644 inikoo/templates/footer.tpl
 create mode 100644 inikoo/templates/header.tpl
 create mode 100644 inikoo/templates/index.tpl
 create mode 100644 inikoo/templates/layout.tpl
 create mode 100644 inikoo/templates/mypage.tpl
 create mode 100644 inikoo/templates/test1.tpl
*/

$smarty->display('index.tpl');
?>
