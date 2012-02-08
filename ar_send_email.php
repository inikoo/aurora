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
	

	
	
	// TODO!!!!
	
	
	
	$to=$conection_data['email'];
	
	$files=array();
	$email_mailing_list_key=0;//$row2['Email Campaign Mailing List Key'];
	//$message_data=$email_campaign->get_message_data($email_mailing_list_key);
   
        $message_data['method']='smtp';
		$message_data['type']='html';
		$message_data['to']=$to;
		$message_data['subject']=$data['values']['summary'];
		$message_data['html']=$data['values']['description']."\n\n".$data['values']['metadata'];
        $message_data['email_credentials_key']=1;
        $message_data['email_matter']='Issue Reporting';
        $message_data['email_matter_parent_key']=0;
        $message_data['email_matter_key']=0;
        $message_data['recipient_type']='Other';
        $message_data['recipient_key']=0;
        $message_data['email_key']=0;
		if(isset($message_data['plain']) && $message_data['plain']){
			$message_data['plain']=$message_data['plain'];
		}
		else
			$message_data['plain']=null;

	 //print_r($message_data);
	$send_email=new SendEmail();

	$send_email->track=false;


	$send_result=$send_email->send($message_data);	

	$send_result=$send_email->store_in_queue($send_result, $files, $message_data);	
	//print_r($send_result);
    echo json_encode($send_result);

}