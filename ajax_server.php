<?php
	include_once('common.php');
	
	$name       = addSlashes($_POST['name']);
	$email      = $_POST['email'];
	$comment    = addSlashes($_POST['comment']);
	
	
	$check = mysql_query("insert into comment(name,email,comment) values('$name','$email','$comment')");
	
	//echo "insert into comment(name,email,comment,date_added) values('$name','$email','$comment','$date_added')"; die();
	
	$select = "select * from comment";
	$result = mysql_query($select);
	$row = mysql_fetch_array($result);
	
	
	$date_added = $row['date_added'];

	if($check)
		echo $date_added;
	else
		echo "0";
?>	   
