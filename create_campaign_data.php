<?php
include_once('common.php');
	$campaign_name = isset($_REQUEST['campaign_name'])?$_REQUEST['campaign_name']:'';

	$campaign_obj = isset($_REQUEST['campaign_obj'])?$_REQUEST['campaign_obj']:'';
	
	$campaign_mail = isset($_REQUEST['campaign_mail'])?$_REQUEST['campaign_mail']:'';

	$customer_list_key = isset($_REQUEST['customer_list_key'])?$_REQUEST['customer_list_key']:'';

	$campaign_content = isset($_REQUEST['campaign_content'])?$_REQUEST['campaign_content']:'';
	
	
	
	if(isset($_REQUEST))
	{
		$sql = "insert into `Email Campaign Dimension` (`Email Campaign Name`, `Email Campaign Objective`, `Email Campaign Maximum Emails`, `Email Campaign Content`,`Email Campaign Engine`,`Email Campaign Status`)values('".$campaign_name."', '".$campaign_obj."', '".$campaign_mail."', '".$campaign_content."','Internal','Creating')";
		$res = mysql_query($sql);
		$campaign_id = mysql_insert_id();
			
			
		
		//fetch the number of emails from list
		//query will be here

		
		//select the customer mail from customer dimension
		$QueryString = "select * from `Customer List Customer Bridge` where `Customer List Key` = '".$customer_list_key."'";
		$QueryResult = mysql_query($QueryString);
		while($row = mysql_fetch_assoc($QueryString))
		{
		  	$fetchMailKey = "select `Customer Main Email Key`,`Customer Key` from `Customer Dimension` where `Customer Key` = '".$row['Customer Key']."'";
			$stringResult = mysql_query($fetchMailKey);
			$insert = mysql_fetch_assoc($stringResult);			
						

			$insert_mail_key = "insert into `Email Campaign Mailing List` (`Customer Key`, `Email Campaign Key`, `Email Key `)values('".$row['Customer Key']."', '".$campaign_id."', '".$insert['Customer Main Email Key']."')";
			$r = mysql_query($insert_mail_key);
		}
			
		
		//insert list key 
		$listQuery = "insert into `Email Campaign Mailing List` (`Customer Key`, `Email Campaign Key`)values('".$customer_list_key."', '".$campaign_id."')";
		$result = mysql_query($listQuery);
		
		
		
			echo '<h4 style=color:green;>Your data has been saved successfully</h4>';
	}
	else
	{
			echo '<h4 style=color:red;>There is some error while saving the data</h4>';
	}
?>
