<?php
	include('common.php');
	$check = array();
	$check = isset($_REQUEST['check'])$_REQUEST['check']:'';

if($check != '')
{
	foreach($check as $key=>$value)
	{

		//fetch the mail id from the checkboxes
		$query = "select `Customer Key`,`Customer Main Plain Email` from `Customer DImension` where `Customer Key` = '".$value."'";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);	

		$to = $row['Customer Main Plain Email'];

		
		// subject
		$subject = 'Welcome Notes from Kaktus';

		// message
		$message = '
		<html>
		<head>
		  <title>KAKTUS</title>
		</head>
		<body>
		  <p>Here are the upcoming news from kaktus !</p>
		  <table>
		    <tr>
		      <td>hello '.$to.'</td>
		    </tr>
		  </table>
		</body>
		</html>
		';

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		/*$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
		$headers .= 'From: Birthday Reminder <birthday@example.com>' . "\r\n";
		$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
		$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";*/

		// Mail it
		$mail = mail($to, $subject, $message, $headers);
		if($mail)
		{
			header('location:send_post.php?msg=Sent');
		}
		else
		{
			header('location:send_post.php?msg=Not Send');	
		}
	}
}
else
{
	header('location:send_post.php');
	exit();
}
?>
