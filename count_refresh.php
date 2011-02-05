<?php
	include_once('common.php');

	$select = $_GET['select'];
	$where = $_GET['where'];
	$check = $_GET['check'];

	
	$query = "select `People Email` from `Email People Dimension` where `People Email` = '".$check."'";
	$result = mysql_query($query);
	$row = mysql_num_rows($result);
	
	if($row == 0)
	{
          echo 0;
	}
	else
	{
	  echo $row;
	}
	

?>
