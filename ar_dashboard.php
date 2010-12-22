<?php
	include_once('common.php');
	
	$name       = addSlashes($_POST['name']);
	$email      = $_POST['email'];
	$comment    = addSlashes($_POST['comment']);
	
	
	$check = mysql_query("insert into `Comment Dimension`(`Name`,`Email`,`Comment`) values('$name','$email','$comment')");
	
	
	
	$select = "select * from `Comment Dimension`";
	$result = mysql_query($select);
	$row = mysql_fetch_array($result);
	
	
	$date_added = $row['Date Added'];

	if($check)
		echo $date_added;
	else
		echo "0";
?>	   
