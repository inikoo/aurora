<?php
if(isset($_REQUEST['password'])){
print '<!DOCTYPE html><html><body onload="parent.submit_login()">x</body></html>';
exit;
}


include_once('common.php');
//print_r($_REQUEST);
if($_SESSION['logged_in']){
$page_key=$site->get_profile_page_key();
}else{
$page_key=$site->get_login_page_key();
}
//$page_key=$site->get_login_page_key();


$block='login';
if(isset($_REQUEST['forgot_password'])){
$block='forgot_password';
}
$smarty->assign('block',$block);

include_once('page.php');
?>