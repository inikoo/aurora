<?php
	include_once('common.php');
	
	$name       = addSlashes($_POST['name']);
	$email      = $_POST['email'];
	$comment    = addSlashes($_POST['comment']);
	//$date_added = time();

	$check = mysql_query("insert into `Comment Dimension`(`Name`,`Email`,`Comment`) values('$name','$email','$comment')");
	
	//$date_added = date("l j F Y, g:i a",time());
	
	$sel = "select * from `Comment Dimension`";
	$res = mysql_query($sel);
	$rr = mysql_fetch_array($res);

 	$date_added = $rr['Date Added'];
	
	if($check)
		echo $date_added;
	else
		echo "0";
?>	   
