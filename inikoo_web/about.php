<?php
    require('common.php');
    $smarty->assign('page_title','About');
    $smarty->assign('page_description','Inikoo About us');
    $smarty->assign('page_keywords','business solutions');
    $smarty->assign('page_name','about');
    $smarty->display('about.tpl');
?>