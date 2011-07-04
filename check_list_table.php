<?php
	include('common.php');
	
	$sql = "select * from `List Dimension`";
	$res = mysql_query($sql);
	$rw = mysql_fetch_array($res);
	
	if(mysql_num_rows($res) == 0)
	{
	  echo 0;
	}
	else
	{
	  echo 1;
	}
?>
