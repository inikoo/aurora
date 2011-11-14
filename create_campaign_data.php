<?php
include_once('common.php');
if(isset($_REQUEST['createCampaign'])){

	$campaign_name = isset($_REQUEST['campaign_name'])?$_REQUEST['campaign_name']:'';
	$campaign_obj = isset($_REQUEST['campaign_obj'])?$_REQUEST['campaign_obj']:'';
	$campaign_mail = isset($_REQUEST['campaign_mail'])?$_REQUEST['campaign_mail']:'';
	$customer_list_key = isset($_REQUEST['customer_list_key'])?$_REQUEST['customer_list_key']:'';
	
	//echo $customer_list_key; die();
	
	$campaign_content = isset($_REQUEST['campaign_content'])?$_REQUEST['campaign_content']:'';
	if(trim($campaign_name)== ''){
		$_SESSION['disp_msg']= '<h4 style=color:red;>Please enter the name for Campaign.</h4>';
		$_SESSION['succ'] = 'no';
		$_SESSION['campaign_name'] = $campaign_name;
	}elseif(trim($campaign_obj) == ''){
		$_SESSION['disp_msg']= '<h4 style=color:red;>Please enter the objective for Campaign.</h4>';
		$_SESSION['succ'] = 'no';
		$_SESSION['campaign_obj'] = $campaign_obj;
	}elseif(trim($campaign_mail) == ''){
		$_SESSION['disp_msg']= '<h4 style=color:red;>Please enter the maximum number of mail for Campaign.</h4>';
		$_SESSION['succ'] = 'no';
		$_SESSION['campaign_mail'] = $campaign_mail;
	}elseif(trim($campaign_content) == ''){
		$_SESSION['disp_msg']= '<h4 style=color:red;>Please enter the content for Campaign.</h4>';
		$_SESSION['succ'] = 'no';
		$_SESSION['campaign_content'] = $campaign_content;
	}
	else{
	$sql = "insert into `Email Campaign Dimension` (`Email Campaign Name`, `Email Campaign Objective`, `Email Campaign Maximum Emails`,
	`Email Campaign Content`,`Email Campaign Engine`,`Email Campaign Status`,`Campaign Creation Date`)values('".$campaign_name."', '".$campaign_obj."', '".
	$campaign_mail."', '".$campaign_content."','Internal','Creating',NOW())";
	$res = mysql_query($sql);
	$campaign_id = mysql_insert_id();

	

	//fetch the number of emails from list
	//query will be here
	//select the customer mail from customer dimension
	/*$QueryString = "select * from `List Customer Bridge` where `List Key` = '".$customer_list_key."'";
	$QueryResult = mysql_query($QueryString);
	while($row = mysql_fetch_assoc($QueryString))
	{  	$fetchMailKey = "select `Customer Main Email Key`,`Customer Key` from `Customer Dimension` where `Customer Key` = '".$row['Customer Key']."'";
		$stringResult = mysql_query($fetchMailKey);
		$insert = mysql_fetch_assoc($stringResult);
		$insert_mail_key = "insert into `Email Campaign Mailing List` (`Customer Key`, `Email Campaign Key`, `Email Key `)values('".$row['Customer Key']."',
		'".$campaign_id."', '".$insert['Customer Main Email Key']."')";
		$r = mysql_query($insert_mail_key);
	}*/



	//insert list key
	$listQuery = "insert into `Email Campaign Mailing List` (`Customer Key`, `Email Campaign Key`)values('".$customer_list_key."', '".$campaign_id."')";
	$result = mysql_query($listQuery);
		if(mysql_affected_rows()==1){
			$_SESSION['disp_msg']= '<h4 style=color:green;>Your data has been saved successfully</h4>';
			$_SESSION['succ'] = 'yes';
		}else{
			$_SESSION['disp_msg']= '<h4 style=color:red;>There is some error while saving the data</h4>';
			$_SESSION['succ'] = 'no';
		}
	unset($_REQUEST);
	}
	
	$path = "new_campaign.php?customer_list_key=$customer_list_key&link=Campaign successfully created";
	header("Location: $path");
}
?>
