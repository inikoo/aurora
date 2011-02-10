<?php
	session_start();
	
	$_SESSION['getQueryString'][] = $_GET['v'];

	$result = array_unique($_SESSION['getQueryString']);
	
	foreach($result as $key=>$value)
	{
	
		echo '<input type="hidden" name="hidden_array[]" value="'.$value.'">';
	}

?>
	
