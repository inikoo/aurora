<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 February 2014 11:15:49 CET, Malaga Spain

 Copyright (c) 2014, Inikoo

 Version 2.0
*/

require_once 'common.php';
	include_once 'class.EmailSiteReminder.php';


require_once 'ar_edit_common.php';
if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];


switch ($tipo) {
case 'send_reminder':
	$data=prepare_values($_REQUEST,array(
			'pid'=>array('type'=>'key'),

		));
	$data['site_key']=$site->id;
	$data['store_key']=$store->id;
	send_reminder($data);
	break;


case 'cancel_send_reminder':
	$data=prepare_values($_REQUEST,array(
			'esr_key'=>array('type'=>'key'),
		));

	cancel_send_reminder($data);
	break;
default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);
}


function send_reminder($data) {



	$email_site_reminder_data=array(
		'Email Site Reminder Subject'=>'User',
		'User Key'=>$data['user']->id,
		'Customer Key'=>$data['user']->data['User Parent Key'],
		'Site Key'=>$data['site_key'],
		'Store Key'=>$data['store_key'],
		'Trigger Scope'=>'Back in Stock',
		'Trigger Scope Key'=>$data['pid'],
		'Creator Subject'=>'User',
		'Creator Subject Key'=>$data['user']->id,
		'Creation Date'=>gmdate('Y-m-d H:i:s')

	);

	$email_site_reminder=new EmailSiteReminder('find',$email_site_reminder_data,'create');

	if ($email_site_reminder->id) {
		$response= array('state'=>200,'pid'=>$data['pid'],'id'=>$email_site_reminder->id,'txt'=>'<span id="send_reminder_info_'.$data['pid'].'" >'._('Done!, an email will be send when back in stock').' <span style="cursor:pointer" id="cancel_send_reminder_'.$email_site_reminder->id.'"  onClick="cancel_send_reminder('.$email_site_reminder->id.')"  >('._('Cancel').')</span></span>');
		echo json_encode($response);
		exit;

	}else {
		$response= array('state'=>400,'msg'=>$email_site_reminder->msg);
		echo json_encode($response);
		exit;

	}


}


function cancel_send_reminder($data){
	
		$email_site_reminder=new EmailSiteReminder($data['esr_key']);
		
		if(!$email_site_reminder->id){
		$response= array('state'=>400,'msg'=>_('Oops, we had an error, please try later'));
		echo json_encode($response);
		exit;
		}
		
		$email_site_reminder->cancel();
		$response= array('state'=>200,'pid'=>$email_site_reminder->data['Trigger Scope Key'],'id'=>$email_site_reminder->id,
		'txt'=>'<span id="send_reminder_'.$email_site_reminder->data['Trigger Scope Key'].'" style="cursor:pointer;" onClick="send_reminder('.$email_site_reminder->data['Trigger Scope Key'].')">'._('Send me an email when back in stock').'</span> <img style="position:relative;bottom:-2px" src="art/send_mail.png"/></span>'
		);
		echo json_encode($response);
		exit;
		

}

?>
