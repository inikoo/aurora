<?php
include_once 'common.php';
$page_key=$site->get_reset_page_key();

$error='';
if (isset($_REQUEST['error'])) {
	$error=$_REQUEST['error'];
}
$smarty->assign('error',$error);


if (isset($_REQUEST['master_key']))
	$smarty->assign('masterkey',$_REQUEST['master_key']);

include_once 'page.php';

?>
