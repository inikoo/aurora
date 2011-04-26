<?php
	include('common.php');

	

	if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == "Send")
	{
		//$post_type = $_REQUEST['post_type'];

		$content = $_REQUEST['content'];

		$mailList = array();	
	
		//select customer list		
		$mailList = $_SESSION['check_mail_list'];

		print_r($mailList); die();

	/*	foreach($mailList as $k=>$v)
		{

		$insert = "INSERT INTO `inikoo`.`Customers Send Post` (`Customer Send Post Key`, `Customer Key`, `Send Post Status`, `Date Creation`, `Date Send`, `Post Type`) VALUES ('1', '".$v."', 'To Send', NOW(), NOW(), '".$post_type."');";
		mysql_query($insert);
		
		$_SESSION['queue_list'] = 'Mail is inserted in the queue';

		header('location:send_mail_list.php');
		}
	*/
	}

?>
