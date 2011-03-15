<?php 
	include('common.php');
	
	$style = isset($_REQUEST['style'])?$_REQUEST['style']:''; 
	
	$theme = isset($_REQUEST['theme'])?$_REQUEST['theme']:''; 
	
	

	if($theme == 0)
	{
		$sql = "UPDATE `User Dimension` SET `User Themes` = '".$style."'";
	}
	else
	{
	
		
 		$sql = "UPDATE `User Dimension` SET `User Themes` = '".$style."',`User Theme Background Status`='0'  WHERE `User Dimension`.`User Key` = '".$_SESSION['user_key']."'";
	}
		
	

		$result = mysql_query($sql);
		$num = mysql_num_rows($result);

		if($num>0)
		{
			echo 1;

		}
		else
		{
			echo 0;
		}
?>

