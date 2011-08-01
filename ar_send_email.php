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
	
$msg="<html>
					<head>
					<title>subject</title>
					<style type=\"text/css\"><!--
					body { color: black ; font-family: arial, helvetica, sans-serif ; background-color: #A3C5CC }
					A:link, A:visited, A:active { text-decoration: underline }
					--></style>
					</head>
					<body>
					<table width=\"100%\">
					<tr>
					<td>
					<center><h1>subject</h1></center>
					<hr>
					<P>Hello Test<br><br>
					This message is just to let you know that the <a href=\"http://www.phpclasses.org/mimemessage\">MIME E-mail message composing and sending PHP class</a> is working as expected.<br><br>
					Thank you,<br>
					from_name</p>
					</td>
					</tr>
					</table>
					</body>
					</html>";
					
	$data=array(
		'subject'=>	$data['values']['summary'],
		'plain'=>$data['values']['description']."\n\n".$data['values']['metadata'],
		'email_credentials_key'=>$email_credential_key,
		'to'=>$to,
		'bcc'=>'raul@inikoo.com',
		'html'=>$msg
	);

	

	$email_type='Plain';
	
	$send_email=new SendEmail();
	$send_email->smtp('plain', $data);

	$result=$send_email->send('plain');

	//$result=$send_email->retry('plain');
	
	//$send_email->smtp('html', $data);

	//$result=$send_email->send('html');
	
	//$result=$send_email->retry('html');
	
	if(preg_match('/^could not resolve the host domain/',$result['msg'])){
		if(isset($data['html']) and $data['html']){
			$html_msg=$data['html'];
			//$email_type='HTML';
		}
		else
			$html_msg=null;
		
		$sql=sprintf("insert into `Email Queue Dimension` (`To`, `Type`, `Subject`, `Plain`, `HTML`, `Email Credentials Key`, `BCC`) values (%s, %s, %s, %s, %s, %d, %s)	"
		,prepare_mysql($data['to'])
		,prepare_mysql($email_type)
		,prepare_mysql($data['subject'])
		,prepare_mysql($data['plain'])
		,prepare_mysql($html_msg)
		,$data['email_credentials_key']
		,prepare_mysql($data['bcc'])
		);
	
		//print $sql;
		
		if(mysql_query($sql))
			$result=array('state'=>400,'msg'=>$result['msg'].' Stored in email Queue');
		
	}
		
		
    echo json_encode($result);

}