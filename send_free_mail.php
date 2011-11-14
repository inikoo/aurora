<?php
	include('common.php');
	if(!isset($_REQUEST['template']))
	{
		header('Location: index.php');
	    	exit();
	}
	
	$mail_list = array();		//select the list of members to send mail
	$mail_list = $_SESSION['check_email'];

	$template = $_REQUEST['template']; 

	$subject = $_REQUEST['templateSub'];
	
	$body = $_REQUEST['templatebody'];

	$new_array = array();

	$i = 0;

if($mail_list != '')
{
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

		
		
		//fetch the customer base from List Customer Bridgedata
		$exeSql = "select `Customer Key` from `List Customer Bridge` where `List Key` = '".$fetchRow['Customer Key']."'";
		$exeQuery = mysql_query($exeSql);
		

		while($exeRow = mysql_fetch_assoc($exeQuery))
		{
			$printSql = "select `Customer Main Plain Email` from `Customer Dimension` where `Customer Key` = '".$exeRow['Customer Key']."'";
			$resultSet = mysql_query($printSql);
			$execute = mysql_fetch_assoc($resultSet);

			$new_array[] = $execute;


		}//end of while	
			
	}//end of foreach

		foreach($new_array as $parent=>$fparent)
		{
				foreach($fparent as $key=>$data)
				{
					$final[$i] = $data;
					$i++;
				}	
		}

			$total_mail_id = array_filter(array_merge($final, $_SESSION['mail_added']));

			foreach($total_mail_id as $key=>$value)
			{

				$to = $value;
				//echo $to; 
				$from = "rulovico@gmail.com";
				
			
		
				//end of message
				$headers  = "From: $from\r\n";
				$headers .= "Content-type: text/html\r\n";

							
				// now lets send the email.
				mail($to, $subject, $template, $headers);
			}
		

			//successfully delivered
			$_SESSION['msg'] = "Message has been sent....!";			
			@header('location:campaign_builder.php');
}//end of if
else
{			
			//unsuccessfull 
			$_SESSION['msg'] = "Please select the campaign";
			@header('location:campaign_builder.php');
}
?>
