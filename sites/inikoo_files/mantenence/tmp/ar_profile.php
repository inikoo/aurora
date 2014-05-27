<?php
require_once 'common.php';

//print_r($_SESSION);
require_once 'class.Customer.php';
require_once 'class.User.php';
require_once 'class.SendEmail.php';
require_once 'class.Site.php';
require_once 'class.Auth.php';

require_once 'ar_edit_common.php';


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>400);
	echo json_encode($response);
	exit;
}

switch ($_REQUEST['tipo']) {



case('change_password'):

//	if (!$logged_in) {
//		print "not logged in";
//		return;
//	}

	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array'),
		));

	change_password($data);
	break;


default:
	$response=array('state'=>402);
	echo json_encode($response);
	exit;

}



function change_password($data) {
	//global $user;
	//print_r($data);
	//  print_r($data['values']);
	//  print "\n". $user->id;

$user=new User($data['values']['user_key']);

	$_key=$user->id.'insecure_key'.$data['values']['ep2'];
	$password=AESDecryptCtr($data['values']['ep1'], $_key ,256);

	// print "Key:$_key\n";
	//print "Jey:\ $_key nPass:$password\n";
	//   exit($password);
	$user->change_password($password);
	if ($user->updated) {
		$response=array('state'=>200,'result'=>'ok',);
		echo json_encode($response);
		exit;
	} else {
		$response=array('state'=>200,'result'=>'error','msg'=>$user->msg);
		echo json_encode($response);
		exit;

	}
}



function check_email_customers($email,$store_key) {


	$sql=sprintf("select `Customer Key` from `Email Bridge` B left join `Email Dimension` E on (E.`Email Key`=B.`Email Key`)  left join `Customer Dimension` on (`Customer Key`=`Subject Key`) where  `Subject Type`='Customer' and `Email`=%s and `Customer Store Key`=%d",
		prepare_mysql($email),
		$store_key
	);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res)) {


		return $row['Customer Key'];



	} else {

		return false;


	}
}



function check_email_users($email,$site_key) {
	$sql=sprintf('select `User Key`,`User Parent Key`, `User Handle` from `User Dimension`  where  `User Type`="Customer" and `User Site Key`=%d  and `User Handle`=%s',$site_key,prepare_mysql($email));
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res)) {
		return $row['User Key'];


	} else {
		return 0;
	}
}





?>
