<?php
require_once'common.php';
require_once 'class.Customer.php';
require_once 'class.User.php';
require_once 'class.SendEmail.php';
require_once 'class.Site.php';

require_once 'ar_edit_common.php';


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>400);
	echo json_encode($response);
	exit;
}

switch ($_REQUEST['tipo']) {
case('register'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array'),
			'store_key'=>array('type'=>'key'),
			'site_key'=>array('type'=>'key'),
			// 'ep'=>array('type'=>'string')
		));
	register($data);

	break;
case('forgot_password'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array'),

		));
	forgot_password($data);

	break;


case('change_password'):
	//  $password=$_REQUEST['password'];
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array'),

		));


	change_password($data);
	break;
case('send_lost_password_email'):
	$email=$_REQUEST['email'];
	send_lost_password_email($email);
	break;
case('register_customer_old'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')

		));
	register_customer_old($data);
	break;
case('check_email'):

	$data=prepare_values($_REQUEST,array(
			'login_handle'=>array('type'=>'string'),
			'store_key'=>array('type'=>'key'),
			'site_key'=>array('type'=>'key')
		));

	check_email($data);




	break;
default:
	$response=array('state'=>402);
	echo json_encode($response);
	exit;

}



function change_password($data) {
	global $user;
	//print_r($data);
	//  print_r($data['values']);
	//  print "\n". $user->id;

	$_key=$user->id.'insecure_key'.$data['values']['ep2'];
	$password=AESDecryptCtr($data['values']['ep1'], $_key ,256);

	// print "Key:$_key\n";
	// print "Jey:\ $_key nPass:$password\n";
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

function send_lost_password_email($email) {
	global $secret_key,$store_key;

	$user_key=check_email_users($email,$store_key);
	if (!$user_key) {
		$customer_key=check_email_customers($email,$store_key);
		if ($customer_key) {
			$customer = new Customer('id',$customer_key);
			$customer->create_user();
			if ($customer->user_key) {
				$user_key=$customer->user_key;
			} else {
				$response=array('state'=>200,'result'=>'error','msg'=>$customer->msg);
				echo json_encode($response);
				exit;
			}

		}

	}




	if ($user_key) {
		$email_to_send=new EmailSend();
		$email_to_send->compose_lost_password_email($user_key,array(
				'Template'=>'emails/html_email_basic_template.html',
				'secret_key'=>$secret_key.$store_key)

		);
		if ($email_to_send->error) {
			$response=array('state'=>200,'result'=>'error');


		} else {

			$email_send=$email_to_send->send();
			if ($email_to_send->error) {
				$response=array('state'=>200,'result'=>'error');
			} else {
				$response=array('state'=>200,'result'=>'send');

			}

		}

		echo json_encode($response);
		exit;

	} else {

		$response=array('state'=>200,'result'=>'new');
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






function generate_password($length=9, $strength=0) {
	$vowels = 'aeuy'.md5(mt_rand());
	$consonants = 'bdghjmnpqrstvz'.md5(mt_rand());
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZlkjhgfduytrdqwertyuipasdfghjkzxcvbnm';
	}
	if ($strength & 2) {
		$vowels .= "AEUI";
	}
	if ($strength & 4) {
		$consonants .= '2345678906789$%^&*(';
	}
	if ($strength & 8) {
		$consonants .= '!=/[]{}~\<>$%^&*()_+@#.,)(*%%';
	}

	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(mt_rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(mt_rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

function create_customer_user($handle,$customer,$site_key,$password, $send_email_flag=true) {
	//  $handle='raul@inikoo.com';

	global $site,$store;

	include_once 'class.User.php';

$customer_key=$customer->id;
	$sql=sprintf("select `Customer Store Key`,`Customer Name` from `Customer Dimension` where `Customer Key`=%d",
		$customer->id);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {



		$data=array(
			'User Handle'=>$handle,
			'User Type'=>'Customer',
			'User Password'=>$password,
			'User Parent Key'=>$row['Customer Store Key'],
			'User Site Key'=>$site_key,
			'User Active'=>'Yes',
			'User Alias'=>$row['Customer Name'],
			'User Parent Key'=>$customer->id
		);

		$user=new user('new',$data);
		//print_r($user);
		if (!$user->id) {

			return array(0,$user->msg);

		} else {

			//print $send_key;exit;

			/*
            $email_credential_key=$store->get_email_credential_key('Site Registration');
            //print $email_credential_key;exit;

            $welcome_email_subject="Thank you for your registration with ".$site->data['Site Name'];
            $welcome_email_plain="Thank you for your registration with ".$site->data['Site Name']."\nYou will now be able to see our wholesale prices and order from our big range of products.\n";

            $welcome_email_html="Thank you for your registration with ".$site->data['Site Name']."<br/>You will now be able to see our wholesale prices and order from our big range of products<br/>";
*/
			if ($send_email_flag) {
				$welcome_email_subject=$site->data['Site Welcome Email Subject'];
			


					$smarty_html_email = new Smarty();
		$smarty_html_email->compile_dir = 'server_files/smarty/templates_c';
		$smarty_html_email->cache_dir = 'server_files/smarty/cache';
 $grettings=$customer->get_greetings();
		$smarty_html_email->assign('grettings', $grettings);
		$html_message = $smarty_html_email->fetch('string:'.$site->data['Site Welcome Email HTML Body']);

		$smarty_plain_email = new Smarty();
		$smarty_plain_email->compile_dir = 'server_files/smarty/templates_c';
		$smarty_plain_email->cache_dir = 'server_files/smarty/cache';
			
		$smarty_plain_email->assign('grettings', $grettings);
		$plain_message = $smarty_plain_email->fetch('string:'.$site->data['Site Welcome Email Plain Body']);






				$email_mailing_list_key=0;//$row2['Email Campaign Mailing List Key'];
				$credentials=$site->get_email_credentials();
				$handle='raul@inikoo.com';
				if (!$credentials) {
					return array($user->id,$user->msg);
				}
				$message_data['method']='smtp';
				$message_data['type']='html';
				$message_data['to']=$handle;
				$message_data['subject']=$welcome_email_subject;
				$message_data['html']=$welcome_email_html;
				$message_data['email_credentials_key']=$credentials['Email Credentials Key'];
				$message_data['email_matter']='Registration';
				$message_data['email_matter_key']=$email_mailing_list_key;
				$message_data['email_matter_parent_key']=$email_mailing_list_key;
				$message_data['recipient_type']='User';
				$message_data['recipient_key']=0;
				$message_data['email_key']=0;
				$message_data['plain']=$welcome_email_plain;
				if (isset($message_data['plain']) && $message_data['plain']) {
					$message_data['plain']=$message_data['plain'];
				} else
					$message_data['plain']=null;

				//print_r($message_data);
				$send_email=new SendEmail();

				$send_email->track=true;


				$send_result=$send_email->send($message_data);
			}

			return array($user->id,$user->msg);
		}
	} else {
		return array(0,'customer not found');

	}


}







function forgot_password($data) {


	//global   $secret_key,$public_url;
	//$site_key=$data['values']['store_key'];
	$site_key=$data['values']['site_key'];
	$login_handle=$data['values']['login_handle'];
	$url=$data['values']['url'];



	include_once 'external_libs/securimage/securimage.php';
	$site=new Site($site_key);

	//print_r($data['values']);
	//print_r($_SESSION);

	$securimage = new Securimage();
	if ($securimage->check($data['values']['captcha_code']) == false) {

		 $response=array('state'=>200,'result'=>'capture_false');
		echo json_encode($response);
		exit;
	}

	$user_key=check_email_users($login_handle,$site->id);
	if (!$user_key) {
		$customer_key=check_email_customers($login_handle,$site->data['Site Store Key']);
		if ($customer_key) {
			$customer=new Customer($customer_key);
			if($customer->id){
			list($user_key,$msg)=create_customer_user($login_handle,$customer_key,$site_key,generate_password(10,10), false);
			}
		}

	}

	if ($user_key) {


		$user=new User($user_key);
		$customer=new Customer($user->data['User Parent Key']);



		//$email_credential_key=$site->get_email_credential_key('Site Registration');
		//$credentials=$site->get_email_credentials();
		//print $email_credential_key=1;
		//print_r($site);
		$signature_name='';
		$signature_company='';

		$master_key=$user_key.generatePassword(2,10);




		$sql=sprintf("insert into `MasterKey Dimension` (`Key`,`User Key`,`Valid Until`,`IP`) values (%s,%d,%s,%s) ",
			prepare_mysql($master_key),
			$user_key,
			prepare_mysql(date("Y-m-d H:i:s",strtotime("now +24 hours"))),
			prepare_mysql(ip())
		);

		mysql_query($sql);



		$secret_key=$site->data['Site Secret Key'];

		$encrypted_secret_data=base64_encode(AESEncryptCtr($master_key,$secret_key,256));




		$masterkey_link=$url."?p=".$encrypted_secret_data;
		$grettings=$customer->get_greetings();

		$smarty_html_email = new Smarty();
		$smarty_html_email->compile_dir = 'server_files/smarty/templates_c';
		$smarty_html_email->cache_dir = 'server_files/smarty/cache';

		$smarty_html_email->assign('grettings', $grettings);
		$smarty_html_email->assign('masterkey_link', $masterkey_link);
		$html_message = $smarty_html_email->fetch('string:'.$site->data['Site Forgot Password Email HTML Body']);

		$smarty_plain_email = new Smarty();
		$smarty_plain_email->compile_dir = 'server_files/smarty/templates_c';
		$smarty_plain_email->cache_dir = 'server_files/smarty/cache';

		$smarty_plain_email->assign('grettings', $grettings);
		$smarty_plain_email->assign('masterkey_link', $masterkey_link);
		$plain_message = $smarty_plain_email->fetch('string:'.$site->data['Site Forgot Password Email Plain Body']);



		$forgot_password_subject=$site->data['Site Forgot Password Email Subject'];

		$credentials=$site->get_email_credentials();
		$login_handle='raul@inikoo.com';
		$message_data['method']='smtp';
		$message_data['type']='html';
		$message_data['to']=$login_handle;
		$message_data['subject']=$forgot_password_subject;
		$message_data['html']=$html_message;
		$message_data['email_credentials_key']=$credentials['Email Credentials Key'];
		$message_data['email_matter']='Password Reminder';
		$message_data['email_matter_key']=0;
		$message_data['email_matter_parent_key']=0;
		$message_data['recipient_type']='User';
		$message_data['recipient_key']=0;
		$message_data['email_key']=0;
		$message_data['plain']=$plain_message;
		if (isset($message_data['plain']) && $message_data['plain']) {
			$message_data['plain']=$message_data['plain'];
		} else
			$message_data['plain']=null;

		//print_r($message_data);
		$send_email=new SendEmail();

		$send_email->track=true;


		$result=$send_email->send($message_data);
		/*
        		$data=array('email_type'=>'Password Reminder',
        				  'recipient_type'=>'User',
        				  'recipient_key'=>$user->id);

        		$send_email=new SendEmail($data);

        		$html_message=$send_email->track_sent_email($html_message);
        		//print $html_message;

        $files=array();
                $to=$login_handle;
                $data=array(
        				  'type'=>'HTML',
                          'subject'=>'Reset your password',
                          'plain'=>$plain_message,
                          'email_credentials_key'=>$email_credential_key,
                          'to'=>$to,
                          'html'=>$html_message,
        				  'attachement'=>$files

                      );
        		if(isset($data['plain']) && $data['plain']){
        			$data['plain']=$data['plain'];
        		}
        		else
        			$data['plain']=null;

                //$send_email=new SendEmail();
                $send_email->smtp('html', $data);
                $result=$send_email->send();

        		//print_r($result);
        */
		if ($result['msg']=='ok') {
			$response=array('state'=>200,'result'=>'send');
			echo json_encode($response);
			exit;

		} else {
			//print_r($result);
			$response=array('state'=>200,'result'=>'error '.join(' ',$result));
			echo json_encode($response);
			exit;
		}


	} else {
		$response=array('state'=>200,'result'=>'handle_not_found');
		echo json_encode($response);
		exit;
	}

}

function check_email($data) {
	$store_key=$data['store_key'];
	$site_key=$data['site_key'];
	$login_handle=_trim($data['login_handle']);


	if ($login_handle=='') {

		$response=array('state'=>200,'result'=>'error');
		echo json_encode($response);
		exit;
	}

	$found=check_email_customers($login_handle,$store_key);

	if ($found) {
		$response=array('state'=>200,'result'=>'found');
		echo json_encode($response);
		exit;
	} else {
		$response=array('state'=>200,'result'=>'not_found','login_handle'=>$login_handle,'epw2'=>md5($login_handle.'x**X'));
		echo json_encode($response);
		exit;

	}



}

function register($data) {



	include_once 'external_libs/securimage/securimage.php';
	$securimage = new Securimage();
	if ($securimage->check($data['values']['captcha_code']) == false) {

		$response=array('state'=>200,'result'=>'capture_false');
		echo json_encode($response);
		exit;
	}


$sql=sprintf("select `Country Code` from kbase.`Country Dimension` where `Country 2 Alpha Code`=%s",
prepare_mysql($data['values']['Customer Address Country 2 Alpha Code']));
$res=mysql_query($sql);
if($row=mysql_fetch_assoc($res)){
$data['values']['Customer Address Country Code']=$row['Country Code'];
}
	include_once 'edit_customers_functions.php';
	global $editor;

	if ($data['values']['Customer Name']=='') {

		$data['values']['Customer Type']='Person';
	} else {
		$data['values']['Customer Type']='Company';


	}

	if ($data['values']['Customer Address Country Code']=='')
		$data['values']['Customer Address Country Code']='UNK';

	$data['values']['editor']=$editor;

	$found=check_email_customers($data['values']['Customer Main Plain Email'],$data['store_key']);

	if ($found) {
		$response=array('state'=>200,'result'=>'handle_found');
		echo json_encode($response);
		exit;

	}

	$response=add_customer($data['values']) ;

	if ($response['state']==200 and $response['action']=='created' ) {
		// $ep=rawurldecode($data['ep']);
		$customer=new Customer($response['customer_key']);
		if ($data['values']['Customer Send Postal Marketing']=='Yes') {
			$sql=sprintf("insert into `Marketing Post Sent Fact` (`Marketing Post Key`, `Customer Key`, `Store Key`, `Requested Date`) values (%d, %d, %d, NOW())", 1, $customer->id, $customer->get('Customer Store Key'));
			//print $sql;
			$result=mysql_query($sql);
		}


		$password=AESDecryptCtr($data['values']['ep'],md5($data['values']['Customer Main Plain Email'].'x**X'),256);
		list($user_key,$user_msg)=create_customer_user($data['values']['Customer Main Plain Email'],$customer,$data['site_key'],$password);
		if ($user_key) {

			$_SESSION['logged_in']=true;
			$_SESSION['store_key']=$data['store_key'];
			$_SESSION['site_key']=$data['site_key'];

			$_SESSION['user_key']=$user_key;
			$_SESSION['customer_key']=$response['customer_key'];

			$response=array('state'=>200,'result'=>'logged_in');
			echo json_encode($response);
			exit;

		}else {
			$response=array('state'=>200,'result'=>'error');
			echo json_encode($response);
			exit;

		}

	}






	echo json_encode($response);
}


function hasher($info, $encdata = false) {
	$strength = "08";
	//if encrypted data is passed, check it against input ($info)
	if ($encdata) {
		if (substr($encdata, 0, 60) == crypt($info, "$2a$".$strength."$".substr($encdata, 60))) {
			return true;
		} else {
			return false;
		}
	} else {
		//make a salt and hash it with input, and add salt to end
		$salt = "";
		for ($i = 0; $i < 22; $i++) {
			$salt .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", mt_rand(0, 63), 1);
		}
		//return 82 char string (60 char hash & 22 char salt)
		return crypt($info, "$2a$".$strength."$".$salt).$salt;
	}
}


?>
