<?php
	include('common.php');

	$_SESSION['mail_added'] = $_REQUEST['email'];

	//print_r($_SESSION['mail_added']);  


	$_SESSION['check_email'] = array();
	$_SESSION['check_email'] = isset($_REQUEST['check_email'])?$_REQUEST['check_email']:'';
	$_SESSION['template'] = $_REQUEST['template'];
	

	switch ($_REQUEST['template']) {

		case "1":
			header('location:free_campaign_template_create.php');
		break;

		case "2":
			header('location:customise_template_create.php');
		break;

		default:
			$_SESSION['back'] = 'Please select at least one mail';
	}

?>
