<?php
require_once'common_splinter.php';
require_once 'class.Customer.php';
require_once 'class.User.php';
require_once 'class.SendEmail.php';

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
                             'ep'=>array('type'=>'string')
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
case('register_customer'):
    $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json array')

                         ));
    register_customer($data);
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

function register_customer($data) {
    global $store_key;

    if ($data['values']['customer_is_company']) {
        $data['values']['customer_is_company']='Company';
        $data['values']['customer_name']=$data['values']['company_name'];

    } else {
        $data['values']['customer_is_company']='Person';
        $data['values']['customer_name']=$data['values']['contact_name'];
    }

    if ($data['values']['emarketing'])
        $data['values']['emarketing']='Yes';
    else
        $data['values']['emarketing']='No';
    if ($data['values']['newsletter'])
        $data['values']['newsletter']='Yes';
    else
        $data['values']['newsletter']='No';





    $translate=array(
                   'email'=>'Customer Main Plain Email',
                   'customer_type'=>'Customer Category',
                   'customer_type_other'=>'Customer Category Data',
                   'customer_is_company'=>'Customer Type',
                   'tax_number'=>'Customer Tax Number',
                   'company_name'=>'Customer Company Name',
                   'contact_name'=>'Customer Main Contact Name',
                   'customer_name'=>'Customer Name',
                   'internal'=>'Customer Address Line 1',
                   'building'=>'Customer Address Line 2',
                   'street'=>'Customer Address Line 3',
                   'town'=>'Customer Address Town',
                   'postal_code'=>'Customer Address Postal Code',
                   'country_d2'=>'Customer Address Country Second Division',
                   'country_d1'=>'Customer Address Country First Division',
                   'country'=>'Customer Address Country Name',
                   'newsletter'=>'Customer Send Newsletter',
                   'emarketing'=>'Customer Send Email Marketing'
               );

    $customer_data=array();
    foreach($data['values'] as $key=>$value) {
        if (array_key_exists($key,$translate))
            $customer_data[$translate[$key]]=$value;
    }
    $customer_data['Customer Store Key']=$store_key;



    $customer=new Customer('find create',$customer_data);
    if ($customer->id) {
        $data=array(
                  'User Handle'=>$customer->data['Customer Main Plain Email'],
                  'User Type'=>'Customer',
                  'User Site Key'=>$customer->data['Customer Store Key'],
                  'User Password'=>$data['values']['password'],
                  'User Active'=>'Yes',
                  'User Alias'=>$customer->data['Customer Name'],
                  'User Parent Key'=>$customer->id
              );

        $user=new user('new',$data);

        // print_r($user);
        // exit;

        $_SESSION['logged_in']=true;
        $_SESSION['logged_in_page']=$customer->data['Customer Store Key'];
        $_SESSION['user_key']=$user->id;
        $_SESSION['customer_key']=$customer->id;

        $auth=new Auth(IKEY,SKEY);
        $auth->set_user_key($user->id);
        $auth->create_user_log();

        //print_r($user);
        $email_send=0;
        if ($customer->new or $user->new) {
            $email_to_send=new EmailSend();
            $email_to_send->compose_registration_email($user->id,array('Template'=>'emails/html_email_basic_template.html'));
            if (!$email_to_send->error)
                //print_r($email_to_send);
                $email_send=$email_to_send->send();
        }

        if ($user->new)
            $response= array('state'=>200,'action'=>'created','user_key'=>$user->id,'customer_key'=>$customer->id,'email_send'=>$email_send);
        else
            $response= array('state'=>200,'action'=>'found','user_key'=>$user->id,'customer_key'=>$customer->id,'email_send'=>$email_send);



    } else {
        $response= array('state'=>400,'action'=>'error','msg'=>'Customer can not be created');

    }



    echo json_encode($response);
}


function change_password($data) {
    global $user;

  //  print_r($data['values']);
  //  print "\n". $user->id;
    
    $_key=md5($user->id.'insecure_key'.$data['values']['ep2']);
    $password=AESDecryptCtr($data['values']['ep1'], $_key ,256);

    
   // exit($password);
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



function check_email_users($email,$store_key) {
    $sql=sprintf('select `User Key`,`User Parent Key`, `User Handle` from `User Dimension`  where  `User Type`="Customer" and `User Parent Key`=%d  and `User Handle`=%s',$store_key,prepare_mysql($email));

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

function create_customer_user($handle,$customer_key,$site_key,$password, $send_email=true) {
	$handle='migara@inikoo.com';
	
	$sql=sprintf("select * from `Configuration Dimension` where `Configuration Key`=%d", 1);
	$result=mysql_query($sql);
	if($row=mysql_fetch_array($result)){
		$track_path=$row['Public Path'];
	}
	
	//print $track_path;exit;
	
	
    global $site,$store;

    include_once('class.User.php');


    $sql=sprintf("select `Customer Store Key`,`Customer Name` from `Customer Dimension` where `Customer Key`=%d",
                 $customer_key);
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
                  'User Parent Key'=>$customer_key
              );

        $user=new user('new',$data);
        if (!$user->id) {

            return array(0,$user->msg);

        } else {
		$send_email=new SendEmail();
		$send_key=$send_email->update_email_dimension();
		//print $send_key;exit;
		
		
        $email_credential_key=$store->get_email_credential_key('Site Registration');
		//print $email_credential_key;exit;

            $welcome_email_subject="Thank you for your registration with ".$site->data['Site Name'];
            $welcome_email_plain="Thank you for your registration with ".$site->data['Site Name']."\nYou will now be able to see our wholesale prices and order from our big range of products.\n";
			
            $welcome_email_html="Thank you for your registration with ".$site->data['Site Name']."<br/>You will now be able to see our wholesale prices and order from our big range of products<br/>";
			
			$welcome_email_html=sprintf("Test Email with image <br/> <img src='%s/track.php?sendkey=%s'>", $track_path, $send_key);
			//print $welcome_email_html;exit;
				
		$data=array(
                
                  'subject'=>$welcome_email_subject,
                  'plain'=>$welcome_email_plain,
                  'email_credentials_key'=>$email_credential_key,
                  'to'=>$handle,
                  'html'=>$welcome_email_html,
				  'email_type'=>'Registration',
				  'recipient_type'=>'User',
				  'recipient_key'=>$user->id
              );
//print_r($data);exit;
		if($send_email){
		
        //$send_email=new SendEmail();
        $send_email->smtp('HTML', $data);
        $result=$send_email->send();
		}


            return array($user->id,$user->msg);
        }
    } else {
        return array(0,'customer not found');

    }


}







function forgot_password($data) {
global $store;

    global $secret_key,$public_url;
    $store_key=$data['values']['store_key'];
    $site_key=$data['values']['site_key'];
    $login_handle=$data['values']['login_handle'];
    $url=$data['values']['url'];
    include_once 'external_libs/securimage/securimage.php';


//print_r($data['values']);
//print_r($_SESSION);

    $securimage = new Securimage();
    if ($securimage->check($data['values']['captcha_code']) == false) {

        $response=array('state'=>200,'result'=>'capture_false');
        echo json_encode($response);
        exit;
    }

    $user_key=check_email_users($login_handle,$store_key);
    if (!$user_key) {
        $customer_key=check_email_customers($login_handle,$store_key);
        if ($customer_key) {
            list($user_key,$msg)=create_customer_user($login_handle,$customer_key,$site_key,generate_password(10,10), false);
        }

    }

    if ($user_key) {


        $user=new User($user_key);
        $customer=new LightCustomer($user->data['User Parent Key']);



        $email_credential_key=$store->get_email_credential_key('Site Registration');

		//print $email_credential_key=1;
//print_r($store);
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





        $encrypted_secret_data=base64_encode(AESEncryptCtr($master_key,$secret_key,256));


$formated_url=preg_replace('/^http\:\\/\\//','',$url);

        $plain_message=$customer->get_greetings()."\n\n We received request to reset the password associated with this email account.\n\nIf you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.\n\nCopy and paste the following link to your browser's address window.\n\n ".$formated_url."?p=".$encrypted_secret_data."\n\n Once you have returned to our website, you will be asked to choose a new password.\n\nThank you \n\n".$signature_name."\n".$signature_company;




        $html_message=$customer->get_greetings()."<br/>We received request to reset the password associated with this email account.<br><br>
                      If you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.<br><br>
                      <b>Click the link below to reset your password</b>
                      <br><br>
                      <a href=\"".$url."?p=".$encrypted_secret_data."\">".$formated_url."?p=".$encrypted_secret_data."</a>
                      <br></br>
                      If clicking the link doesn't work you can copy and paste it into your browser's address window. Once you have returned to our website, you will be asked to choose a new password.
                      <br><br>
                      Thank you";

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
			
        $send_email=new SendEmail();
        $send_email->smtp('plain', $data);
        $result=$send_email->send();

		//print_r($result);
		
        if ($result['msg']=='ok') {
            $response=array('state'=>200,'result'=>'send');
            echo json_encode($response);
            exit;

        } else {
            print_r($result);
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

    //print_r($data);

    //print_r($_SESSION);

    if (!$securimage->check($data['values']['captcha_code']) == false) {
        //echo $securimage->getCode();
        // the code was incorrect
        // you should handle the error so that the form processor doesn't continue

        // or you can use the following code if there is no validation or you do not know how
        //echo "The security code entered was incorrect.<br /><br />";
        //echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
        $response=array('state'=>450,'result'=>'capture_false');
        echo json_encode($response);
        exit;
    }


    //echo $data['values']['captcha_code'];


    include_once('edit_customers_functions.php');

    global $editor;






    if ($data['values']['Customer Name']=='') {

        $data['values']['Customer Type']='Person';
    } else {
        $data['values']['Customer Type']='Company';


    }

    if ($data['values']['Customer Address Country Code']=='')
        $data['values']['Customer Address Country Code']='UNK';

    $data['values']['editor']=$editor;



//print_r($data['values']);


    $found=check_email_customers($data['values']['Customer Main Plain Email'],$data['store_key']);

    if ($found) {
        $response=array('state'=>200,'result'=>'handle_found');
        echo json_encode($response);
        exit;

    }


    $response=add_customer($data['values']) ;

    if ($response['state']==200 and $response['action']=='created' ) {
        // $ep=rawurldecode($data['ep']);


        $password=AESDecryptCtr($data['values']['ep'],md5($data['values']['Customer Main Plain Email'].'x**X'),256);

        list($user_key,$user_msg)=create_customer_user($data['values']['Customer Main Plain Email'],$response['customer_key'],$data['site_key'],$password);

		//print $user_key;
		
        if ($user_key) {

            $_SESSION['logged_in']=true;
            $_SESSION['store_key']=$data['store_key'];
            $_SESSION['site_key']=$data['site_key'];

            $_SESSION['user_key']=$user_key;
            $_SESSION['customer_key']=$response['customer_key'];

            $response=array('state'=>200,'action'=>'logged_in');
            echo json_encode($response);
            exit;

        }

    }






    echo json_encode($response);
}


?>
