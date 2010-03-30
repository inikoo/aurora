<?php
include_once('common.php');
$css_files=array(
		 'css/default.css',
		 'css/dropdown.css'
		 );
$js_files=array('js/dropdown.js');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->display('form.tpl');

?>