<?php
	include('common.php');
	$mail_list = array();

	$mail_list = isset($_REQUEST['mail_list'])?$_REQUEST['mail_list']:'';

	foreach($mail_list as $key=>$mail)
	{	
		
		$sql = "select * from `Email Campaign Dimension` where `Email Campaign Key` = '".$mail."'";
		$query = mysql_query($sql);
		$row = mysql_fetch_assoc($query);
		
		$name = $row['Email Campaign Name'];
	
		$objective = $row['Email Campaign Objective'];

		$content = $row['Email Campaign Content'];		

		//pick up the customer mail to send
		$runSql = "select `Customer Key` from `Email Campaign Mailing List` where `Email Campaign Key` = '".$mail."'";
		$runQuery = mysql_query($runSql);
		$fetchRow = mysql_fetch_assoc($runQuery);

		//print_r($fetchRow); die();
		
		//fetch the customer base from Customer List Customer Bridge
		$exeSql = "select `Customer Key` from `Customer List Customer Bridge` where `Customer List Key` = '".$fetchRow['Customer Key']."'";
		$exeQuery = mysql_query($exeSql);
		

		while($exeRow = mysql_fetch_assoc($exeQuery))
		{
			$printSql = "select `Customer Main Plain Email` from `Customer Dimension` where `Customer Key` = '".$exeRow['Customer Key']."'";
			$resultSet = mysql_query($printSql);
			$execute = mysql_fetch_assoc($resultSet);

			
			$to = $execute['Customer Main Plain Email'];
			$from = "rulovico@gmail.com";
			$subject = "Hello! This is HTML email";
			
		
			//begin of HTML message
			$message = <<<EOF
			<table>	
				<tr>
					<td><h2>This is an HTML email coming from kaktus</h2></td>
				</tr>

				<tr>
					<td>$content</td>
				</tr>
				
				<tr>
					<td><img src='track.php?sendkey=$mail'/></td>
				</tr>

			</table>
EOF;
			//end of message
			$headers  = "From: $from\r\n";
			$headers .= "Content-type: text/html\r\n";

			//options to send to cc+bcc
			//$headers .= "Cc: [email]maa@p-i-s.cXom[/email]";
			//$headers .= "Bcc: [email]email@maaking.cXom[/email]";

			// now lets send the email.
			mail($to, $subject, $message, $headers);

			



		}
			$_SESSION['msg'] = "Message has been sent....!"; 
			header('location:campaign_list.php');
	}
	
?>
