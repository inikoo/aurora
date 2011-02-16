<?php
	session_start();
	
	
	//change the text color whether any ignore result is occured
	$_SESSION['colorArray'][] = $_REQUEST['colorArray'];
	
	$colorArray = array();
	$colorArray = array_unique($_SESSION['colorArray']);

	//echo '<pre>'; print_r($colorArray);

	foreach($colorArray as $kk=>$vv)
	{
		
		echo '<span style="color:red;"> '.($vv+1).' number data will be ignored</span>';
		echo '<br>';
		
		
	}


	echo "<div style=\"display:none;\">@</div>";


	//create array for ignore result
	$_SESSION['getQueryString'][] = $_GET['v'];

	$result = array_unique($_SESSION['getQueryString']);
	
	foreach($result as $key=>$value)
	{
		
		echo '<input type="hidden" name="hidden_array[]" value="'.$value.'">';
	}

?>
	
