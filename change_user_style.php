<?php 
	include('common.php');
	
	$selectedIndex = isset($_REQUEST['selectedIndex'])?$_REQUEST['selectedIndex']:''; 
	
	
	
if($selectedIndex >= 0)
{
	$sql = "UPDATE `User Dimension` SET `User Themes` = '".$selectedIndex."' WHERE `User Dimension`.`User Key` = '".$_SESSION['user_key']."'";
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
}
else
{

}	
?>

