<?php
include 'common.php';
if (isset($_SESSION['logged_in']) and $_SESSION['logged_in'] ) {
	$response=array('state'=>200,'result'=>'ok');
	echo json_encode($response);
	exit;
}

$auth=new Auth(IKEY,SKEY);
$remember=(array_key_exists('remember_me', $_REQUEST)) ? $_REQUEST['remember_me'] : false;
$handle = (array_key_exists('login_handle', $_REQUEST)) ? $_REQUEST['login_handle'] : false;
$sk = (array_key_exists('ep', $_REQUEST)) ? $_REQUEST['ep'] : false;


if ($handle) {
	date_default_timezone_set($_REQUEST['offset']);
	$auth->site_key=$site->id;
	$auth->remember=$remember;
	$auth->authenticate($handle,rawurldecode($sk),'customer',$site->id);


	if ($auth->is_authenticated()) {
		$_SESSION['logged_in']=true;
		$_SESSION['store_key']=$store_key;
		$_SESSION['site_key']=$site->id;
		$_SESSION['user_log_key']=$auth->user_log_key;
		$_SESSION['user_key']=$auth->get_user_key();
		$_SESSION['customer_key']=$auth->get_user_parent_key();
		$_SESSION['offset']=$_REQUEST['offset'];


		if ($remember) {
			$auth->set_cookies($handle,rawurldecode($sk),'customer',$site->id);
		}
		else {
			$auth->unset_cookies($handle,rawurldecode($sk),'customer',$site->id);
		}


		$response=array('state'=>200,'result'=>'ok');
		echo json_encode($response);
		exit;


	}
	else {
		$response=array('state'=>200,'result'=>'no_valid','reason'=>$auth->pass['main_reason']);
		echo json_encode($response);
		exit;
	}
}else {
	$response=array('state'=>200,'result'=>'no_valid');
	echo json_encode($response);
	exit;

}






?>
