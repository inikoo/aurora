<?php
/*
 * test_smtp_mail.php
 *
 * @(#) $Header: /home/mlemos/cvsroot/mimemessage/test_smtp_mail.php,v 1.2 2003/10/05 17:48:55 mlemos Exp $
 *
 *
 */

	require("smtp_mail.php");



	$message_object->smtp_debug=1;
	$message_object->smtp_debug=0;            /* Output dialog with SMTP server */
	$message_object->smtp_html_debug=1; 
	
	$message_object->localhost="localhost";   /* This computer address */
	
	$message_object->smtp_host="smtp.gmail.com";   /* SMTP server address */
	$message_object->smtp_direct_delivery=0;  /* Deliver directly to the recipients destination SMTP server */
	$message_object->smtp_exclude_address=""; /* In directly deliver mode, the DNS may return the IP of a sub-domain of the default domain for domains that do not exist. If that is your case, set this variable with that sub-domain address. */
	$message_object->smtp_user="requests@inikoo.com";            /* authentication user name */
	$message_object->smtp_realm="";           /* authentication realm or Windows domain when using NTLM authentication */
	$message_object->smtp_workstation="";     /* authentication workstation name when using NTLM authentication */
	$message_object->smtp_password="DXggmAf1mQ";        /* authentication password */
	$message_object->smtp_pop3_auth_host="";  /* if you need POP3 authetntication before SMTP delivery, specify the host name here. The smtp_user and smtp_password above should set to the POP3 user and password */
	      /* If smtp_debug is 1, set this to 1 to output debug information in HTML */
$message_object->smtp_port=465;
$message_object->smtp_ssl=1;

	/*
	 *  Change these variables to specify your test sender and recipient addresses
	 */
	$from="mlemos@acm.org";
	$to="rulovico@gmail.com";

	$subject="Testing smtp_mail function";
	$message="Hello,\n\nThis message is just to let you know that the smtp_mail() function is working fine as expected.\n\n$from";
	$additional_headers="From: $from";
	$additional_parameters="-f ".$from;
	if(smtp_mail($to,$subject,$message,$additional_headers,$additional_parameters))
		echo "Ok.";
	else
		echo "Error: ".$message_object->error."\n";

?>