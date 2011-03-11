<?php 
	include('common.php');
	
	$style = isset($_REQUEST['style'])?$_REQUEST['style']:''; 
	
	$theme = isset($_REQUEST['theme'])?$_REQUEST['theme']:''; 

	if($theme == 0)
	{
		$sql = "UPDATE `kaktus`.`User Dimension` SET `User Themes` = '".$style."'";
	}
	else
	{
		$sql = "UPDATE `kaktus`.`User Dimension` SET `User Themes` = '".$style."' WHERE `User Dimension`.`User Key` = '".$_SESSION['user_key']."'";
	}
		
	//echo $sql; die();

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

