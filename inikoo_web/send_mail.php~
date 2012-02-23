<?php
 require_once "class.SendEmail.php";
 


$phone='Phone: '.$_REQUEST['phone']."\n";
$email='Email: '.$_REQUEST['email']."\n";
$msg='Message: '.$_REQUEST['details']."\n";


$handle="migara@inikoo.com";
$welcome_email_subject=$_REQUEST['title'];//'Hello';
$html_message=$phone.$email.$msg;
$email_mailing_list_key=0;
$email_mailing_list_key=0;
$plain_message=$phone.$email.$msg;
$from=$_REQUEST['name'].' '.$_REQUEST['company'];

$message_data['method']='smtp';
$message_data['type']='html';
$message_data['to']=$handle;
$message_data['subject']=$welcome_email_subject;
$message_data['html']=$html_message;
$message_data['email_credentials_key']=1;
$message_data['email_matter']='Registration';
$message_data['email_matter_key']=$email_mailing_list_key;
$message_data['email_matter_parent_key']=$email_mailing_list_key;
$message_data['recipient_type']='User';
$message_data['recipient_key']=0;
$message_data['email_key']=0;
$message_data['plain']=$plain_message;
$message_data['from']=$from;

if (isset($message_data['plain']) && $message_data['plain']) {
	$message_data['plain']=$message_data['plain'];
} else
	$message_data['plain']=null;

//print_r($message_data);
$send_email=new SendEmail();

$send_email->track=true;


$send_result=$send_email->send($message_data);

header('Location: contact.php');
 ?>