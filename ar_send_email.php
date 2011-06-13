<?php
require_once 'common.php';
require_once 'ar_edit_common.php';



if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case 'report_issue':
    $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json array')

                         ));
    report_issue($data);
    break;

}


function report_issue($data) {

global $message_object;

if($data['values']['summary']==''){
$response=array('state'=>400,'msg'=>_('You must specify a summary of the issue.'));
echo json_encode($response);
exit;
}

  require("external_libs/mail/smtp_mail.php");
  
  
  if($data['values']['type']=='bug')
  require("app_files/keys/requests_key.php");
  else
    require("app_files/keys/bugs_key.php");



	$message_object->smtp_debug=0;           
	$message_object->smtp_html_debug=1; 
	
	$message_object->localhost="localhost";   
	
	$message_object->smtp_host=$conection_data['smtp_host'];  
	$message_object->smtp_direct_delivery=0; 
	$message_object->smtp_exclude_address=""; 
	$message_object->smtp_user=$conection_data['smtp_user'];  
	$message_object->smtp_realm="";       
	$message_object->smtp_workstation="";    
	$message_object->smtp_password=$conection_data['smtp_password'];  
	$message_object->smtp_pop3_auth_host="";  
	   
$message_object->smtp_port=$conection_data['smtp_port'];  
$message_object->smtp_ssl=$conection_data['smtp_ssl'];  

//print_r($conection_data);
//exit;
	$from=$conection_data['email'];
	$to=$conection_data['email'];

	$subject=$data['values']['summary'];
	$message=$data['values']['description']."\n\n".$data['values']['metadata'];
	$additional_headers="From: $from\nBcc: raul@inikoo.com";
	$additional_parameters="-f ".$from;
	
	
	
	if(smtp_mail($to,$subject,$message,$additional_headers,$additional_parameters))
		 $response=array('state'=>200,'msg'=>'ok');
	else
	$response=array('state'=>400,'msg'=>$message_object->error);
  
  
  
 
    echo json_encode($response);

}