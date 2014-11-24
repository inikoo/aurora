<?php
/*
 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 24 November 2014 13:45:16 GMT, Nottingham UK
 Copyright (c) 2014, Inikoo

 Version 2.0
*/

include_once 'class.SendEmail.php';

function fork_sendemail($job) {


	if (!$_data=get_fork_data($job))
		return;

	$fork_data=$_data['fork_data'];
	$fork_key=$_data['fork_key'];

	$sql=sprintf("update `Fork Dimension` set `Fork State`='In Process' ,`Fork Operations Total Operations`=1,`Fork Start Date`=NOW() where `Fork Key`=%d ",
		$fork_key
	);
	mysql_query($sql);

	switch ($fork_data['type']) {

	case 'order_confirmation':


		$send_email=new SendEmail();
		$send_email->track=false;
		$send_email->secret_key='';
		$send_email->set($fork_data['email_data']['customer_message_data']);
		$send_email->from=$fork_data['email_data']['customer_message_from'];
		$result=$send_email->send();
		foreach ($fork_data['email_data']['notification_recipients'] as $recipient ) {
			$fork_data['email_data']['notification_message_data']['to']=$recipient;

			$send_email=new SendEmail();
			$send_email->track=false;
			$send_email->secret_key='';
			$send_email->set($fork_data['email_data']['notification_message_data']);
			$send_email->from=$fork_data['email_data']['notification_message_from'];
			$result=$send_email->send();

		}

		break;

	}


	$sql=sprintf("update `Fork Dimension` set `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=1,`Fork Result`='Done' where `Fork Key`=%d ",
		$fork_key
	);
	mysql_query($sql);

	return false;
}

?>
