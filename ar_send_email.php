<?php
require_once 'common.php';
require_once 'ar_edit_common.php';
include_once('class.SendEmail.php');


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

	if($data['values']['summary']==''){
	$response=array('state'=>400,'msg'=>_('You must specify a summary of the issue.'));
	echo json_encode($response);
	exit;
	}

	if($data['values']['type']=='bug')
	$email_credential_key=1;
	else
	$email_credential_key=2;
	

	
	
	//$to=$conection_data['email'];
	$to='migara@inikoo.com';

	global $message_object;
	
	
	$data=array(
		'subject'=>	$data['values']['summary'],
		'body'=>$data['values']['description']."\n\n".$data['values']['metadata'],
		'email_credentials_key'=>$email_credential_key,
		'to'=>$to,
		'bcc'=>'raul@inikoo.com'
	);

	$send_email=new SendEmail();
	$send_email->smtp('plain', $data);

	$result=$send_email->send();

	//$result=$send_email->retry();
	
	if(preg_match('/^could not resolve the host domain/',$result['msg'])){
		$sql=sprintf("insert into `Email Queue Dimension` (`To`, `Type`, `Subject`, `Plain`, `Email Credentials Key`, `BCC`) values (%s, 'Plain', %s, %s, %d, %s)	"
		,prepare_mysql($data['to'])
		,prepare_mysql($data['subject'])
		,prepare_mysql($data['body'])
		,$data['email_credentials_key']
		,prepare_mysql($data['bcc'])
		);

		if(mysql_query($sql))
			$result=array('state'=>400,'msg'=>$result['msg'].' Stored in email Queue');
		
	}
		
		
    echo json_encode($result);

}