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
	

	
	
	$to=$conection_data['email'];
	//$to='migara@inikoo.com';

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


	$files=array();
	
	$files[]=array('attachement_type'=>'Text',
								'Data'=>"This is just a plain text attachment file named attachment.txt .",
								'Name'=>"attachment.txt",
								'Content-Type'=>"automatic/name",
								'Disposition'=>"attachment"	
						);
	$files[]=array('attachement_type'=>'Image',
								'FileName'=>"http://localhost/kaktus/external_libs/mail/mimemessage.gif",
								'Content-Type'=>"automatic/name",
								'Disposition'=>"attachment"	
						);
						
	//print_r($files);
	
	$data=array(
		'type'=>'HTML',
		'subject'=>	$data['values']['summary'],
		'plain'=>$data['values']['description']."\n\n".$data['values']['metadata'],
		'email_credentials_key'=>$email_credential_key,
		'to'=>$to,
		'bcc'=>'raul@inikoo.com',
		'html'=>$msg,
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

	//$result=$send_email->retry('plain');
	
	//$send_email->smtp('html', $data);

	//$result=$send_email->send();
	
	//$result=$send_email->retry('html');
	
	if(preg_match('/^could not resolve the host domain/',$result['msg'])){
		if(isset($data['html']) && $data['html']){
			$html_msg=$data['html'];
		}
		else
			$html_msg=null;
		
		$sql=sprintf("insert into `Email Queue Dimension` (`To`, `Type`, `Subject`, `Plain`, `HTML`, `Email Credentials Key`, `BCC`) values (%s, %s, %s, %s, %s, %d, %s)	"
		,prepare_mysql($data['to'])
		,prepare_mysql($data['type'])
		,prepare_mysql($data['subject'])
		,prepare_mysql($data['plain'])
		,prepare_mysql($html_msg)
		,$data['email_credentials_key']
		,prepare_mysql($data['bcc'])
		);
	
		//print $sql;
		$stat=mysql_query($sql);
		
		$email_queue_key=mysql_insert_id();
		
		if(isset($files)){
			foreach($files as $value){
				if(isset($value['Data']) && $value['Data']){
					$data_temp=$value['Data'];
				}
				else
					$data_temp=null;
					
				if(isset($value['FileName']) && $value['FileName']){
					$file_name=$value['FileName'];
				}
				else
					$file_name=null;	
					
				if(isset($value['Name']) && $value['Name']){
					$name=$value['Name'];
				}
				else
					$name=null;			
					
				$sql=sprintf("insert into `Email Queue Attachement Dimension` (`Email Queue Key`, `Data`, `FileName`, `Name`, `Content-Type`, `Disposition`, `Type`) values (%d, %s, %s, %s, %s, %s, %s)"
				,$email_queue_key
				,prepare_mysql($data_temp)
				,prepare_mysql($file_name)
				,prepare_mysql($name)
				,prepare_mysql($value['Content-Type'])
				,prepare_mysql($value['Disposition'])
				,prepare_mysql($value['attachement_type'])
				);
				
				//print prepare_mysql($file_name);
				$stat = $stat & mysql_query($sql);
			}
			
		}
		
		if($stat)
			$result=array('state'=>400,'msg'=>_('Message will send shortly');
		else
			$result=array('state'=>400,'msg'=>'Error: Message could not be sent');
		
	}
		
		
    echo json_encode($result);

}