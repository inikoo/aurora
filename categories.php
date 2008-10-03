<?
include_once('common.php');
include_once('classes/product.php');


list($use,$deep)=get_cat_base(2,'sname');

print_r($use);
$smarty->assign('use',$use);

$smarty->display('categories.tpl');





?>