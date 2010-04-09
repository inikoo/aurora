<?php


include('common.php');
require_once 'class.Customer.php';

require_once 'ar_edit_common.php';
if(!isset($_REQUEST['tipo'])){
    $response=array('state'=>400);
    echo json_encode($response);
    exit;
}

switch($_REQUEST['tipo']){
case('reset_password_send_email'):
  $customer_key=$_REQUEST['customer_key'];
  reset_password_send_email($customer_key);
  break;
case('register_customer'):
   $data=prepare_values($_REQUEST,array(
			     'values'=>array('type'=>'json array')
			   
			     ));
  register_customer($data);
  break;
case('check_email'):
if(!isset($_REQUEST['email'] )){
    $response=array('state'=>401);
    echo json_encode($response);
    exit;
}
$email=$_REQUEST['email'];


$sql=sprintf('select `User Parent Key`, `User Handle` from `User Dimension`  where  `User Type`="Customer_%d"  and `User Handle`=%s',$store_key,prepare_mysql($email));

$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){
 $response=array('state'=>200,'result'=>'found','email'=>$row['User Handle']);
 echo json_encode($response);
 exit;
 
}else{
$response=array('state'=>200,'result'=>'new');
    echo json_encode($response);
    exit;
}
break;
default:
$response=array('state'=>402);
    echo json_encode($response);
    exit;

}

function register_customer($data){


  if($data['values']['customer_is_company']){
    $data['values']['customer_is_company']='Company';
    $data['values']['customer_name']=$data['values']['company_name'];
   
  }else{
    $data['values']['customer_is_company']='Person';
    $data['values']['customer_name']=$data['values']['contact_name'];
  }

  if($data['values']['emarketing'])
    $data['values']['emarketing']='Yes';
  else
    $data['values']['emarketing']='No';
if($data['values']['newsletter'])
    $data['values']['newsletter']='Yes';
  else
    $data['values']['newsletter']='No';

  $translate=array(
		  'email'=>'Customer Main Plain Email'
		  ,'customer_type'=>'Customer Category'
		  ,'customer_type_other'=>'Customer Category Data'
		  ,'customer_is_company'=>'Customer Type'

		  ,'tax_number'=>'Customer Tax Number'
		  ,'company_name'=>'Customer Company Name'
		  ,'contact_name'=>'Customer Main Contact Name'
		  ,'customer_name'=>'Customer Name'
		  ,'internal'=>'Customer Address Line 1'
		  ,'building'=>'Customer Address Line 2'
		  ,'street'=>'Customer Address Line 3'
		  ,'town'=>'Customer Address Town'
		  ,'postal_code'=>'Customer Address Postal Code'
		  ,'country_d2'=>'Customer Address Country Second Division'
		  ,'country_d1'=>'Customer Address Country First Division'
		  ,'country'=>'Customer Address Country Name'
		  ,'newsletter'=>'Customer Send Newsletter'
		  ,'emarketing'=>'Customer Send Email Marketing'
		  );
  
  $customer_data=array();
  foreach($data['values'] as $key=>$value){
    if(array_key_exists($key,$translate))
      $customer_data[$translate[$key]]=$value;
  }
  // print_r($data);
  print_r($customer_data);

  
$customer=new Customer('find create force',$customer_data);
//print_r($customer->data);

if($customer->new){
    $response= array('state'=>200,'action'=>'created','customer_key'=>$customer->id);
   

    $data=array(
	      'User Handle'=>$customer->data['Customer Main Plain Email']
	      ,'User Type'=>'Customer_'.$customer->data['Customer Store Key']
	      ,'User Password'=>$data['values']['password']
	      ,'User Active'=>'Yes'
	      ,'User Alias'=>$customer->data['Customer Name']
	      ,'User Parent Key'=>$customer->id
	      );
    
    $user=new user('new',$data);
    $_SESSION['logged_in']=true;
    $_SESSION['user_key']=$user->id;
    send_welcome_email($customer->data['Customer Main Plain Email']);


 }else{
    if($customer->found)
      $response= array('state'=>400,'action'=>'found','customer_key'=>$customer->found_key);
    else
      $response= array('state'=>400,'action'=>'error','customer_key'=>0,'msg'=>$customer->msg);
  }

 echo json_encode($response);  
}


function send_welcome_email(){

}

function reset_password_send_email($customer_key){
  global $secret_key;


include_once("external_libs/mail/email_message.php");
include_once("class.Store.php");
include_once('aes.php');

$customer=new Customer($customer_key);
$store=new Store($customer->data['Customer Store Key']);
















$from_name=$store->data['Store Name'];
$from_address=$store->data['Store Email'];
$reply_name=$from_name;
$reply_address=$from_address;
$reply_address=$from_address;
$error_delivery_name=$from_name;
$error_delivery_address=$from_address;

$to_name=$customer->data['Customer Name'];
$to_address='rulovico@gmail.com';//$customer->data['Customer Main PLain Email'];

$subject=$store->data['Store Name']." Password Reset Request";


$secret_data=json_encode(array('D'=>generatePassword(2,10).date('U') ,'C'=>$customer->id ));
$encripted_secret_data=base64_encode(AESEncryptCtr($secret_data,$secret_key.$store->key,256));

//print"$secret_data  <br>";

//print "$encripted_secret_data<br>";

//exit('');
	$email_message=new email_message_class;
	$email_message->SetEncodedEmailHeader("To",$to_address,$to_name);
	$email_message->SetEncodedEmailHeader("From",$from_address,$from_name);
	$email_message->SetEncodedEmailHeader("Reply-To",$reply_address,$reply_name);
	$email_message->SetHeader("Sender",$from_address);

/*
 *  Set the Return-Path header to define the envelope sender address to which bounced messages are delivered.
 *  If you are using Windows, you need to use the smtp_message_class to set the return-path address.
 */
	if(defined("PHP_OS")
	&& strcmp(substr(PHP_OS,0,3),"WIN"))
		$email_message->SetHeader("Return-Path",$error_delivery_address);

	$email_message->SetEncodedHeader("Subject",$subject);

	$html_message="<html>
<head>
<title>$subject</title>
<style type=\"text/css\"><!--
body { color: black ; font-family: arial, helvetica, sans-serif ; background-color: #A3C5CC }
A:link, A:visited, A:active { text-decoration: underline }
--></style>
</head>
<body>
<table width=\"100%\">
<tr>
<td>
<center><h1>$subject</h1></center>
<hr>
<P>Hello ".strtok($to_name," ").",<br><br>
We received request to reset the password associated with this email account.<br><br>
If you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.<br><br>
<b>Click the link below to reset your password</b>
<br><br>
<a href=\"http://".$store->data['Store URL']."/reset.php?p=".$encripted_secret_data."\">".$store->data['Store URL']."/reset.php?p=".$encripted_secret_data."</a>
<br></br>
If clicking the link doesn't work you can copy and paste it into your browser's address window. Once you have returned to ".$store->data['Store Name'].", we will give you instructions for reseting your password.
<br><br>
Thank you,<br>
$from_name</p>
</td>
</tr>
</table>
</body>
</html>";
	$email_message->CreateQuotedPrintableHTMLPart($html_message,"",$html_part);

/*
 *  It is strongly recommended that when you send HTML messages,
 *  also provide an alternative text version of HTML page,
 *  even if it is just to say that the message is in HTML,
 *  because more and more people tend to delete HTML only
 *  messages assuming that HTML messages are spam.
 */


	exit($html_message);

$text_message="Hello ".strtok($to_name," ").",\n\nWe received request to reset the password associated with this email account.\n\nIf you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.\n\nCopy and paste the following link to your browser's address window. Once you hace returned to ".$store->data['Store Name'].", we will give you instructions for resetting your password\n\nThank you,\n$from_name";






	$email_message->CreateQuotedPrintableTextPart($email_message->WrapText($text_message),"",$text_part);

/*
 *  Multiple alternative parts are gathered in multipart/alternative parts.
 *  It is important that the fanciest part, in this case the HTML part,
 *  is specified as the last part because that is the way that HTML capable
 *  mail programs will show that part and not the text version part.
 */
	$alternative_parts=array(
		$text_part,
		$html_part
	);
	$email_message->AddAlternativeMultipart($alternative_parts);

/*
 *  The message is now ready to be assembled and sent.
 *  Notice that most of the functions used before this point may fail due to
 *  programming errors in your script. You may safely ignore any errors until
 *  the message is sent to not bloat your scripts with too much error checking.
 */
	$error=$email_message->Send();
	if(strcmp($error,""))
		echo "Error: $error\n";
	else
		echo "Message sent to $to_name\n";









$text_message="Hello ".strtok($to_name," ").",\n\nWe received request to reset the password associated with this email account.\n\nIf you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.\n\nCpay and pase the following link to your browser address window, or retype it carefully. Once you hace returned to ".$store->data['Store Name'].", we will give you instructions for reserring your passord\n\nThank you,\n$from_name";
$email_message=new email_message_class;
$email_message->SetEncodedEmailHeader("To",$to_address,$to_name);
$email_message->SetEncodedEmailHeader("From",$from_address,$from_name);
$email_message->SetEncodedEmailHeader("Reply-To",$reply_address,$reply_name);
/*
  Set the Return-Path header to define the envelope sender address to which bounced messages are delivered.
	If you are using Windows, you need to use the smtp_memoressage_class to set the return-path address.
*/
	if(defined("PHP_OS")
	&& strcmp(substr(PHP_OS,0,3),"WIN"))
		$email_message->SetHeader("Return-Path",$error_delivery_address);
	$email_message->SetEncodedEmailHeader("Errors-To",$error_delivery_address,$error_delivery_name);
	$email_message->SetEncodedHeader("Subject",$subject);
	$email_message->AddQuotedPrintableTextPart($email_message->WrapText($message));
	$error=$email_message->Send();
	if(strcmp($error,""))
		echo "Error: $error\n";


}
function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy'.md5(mt_rand());
	$consonants = 'bdghjmnpqrstvz'.md5(mt_rand());
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZlkjhgfduytrdqwertyuiopasdfghjklzxcvbnm';
	}
	if ($strength & 2) {
		$vowels .= "AEUY4,cmoewmpaeoi8m5390m4pomeotixcmpodim";
	}
	if ($strength & 4) {
		$consonants .= '2345678906789$%^&*(';
	}
	if ($strength & 8) {
		$consonants .= '!=/[]{}~|\<>$%^&*()_+@#.,)(*%%';
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

?>