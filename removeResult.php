<?php
	session_start();

	//echo $_REQUEST['colorArray'];
	
	
	$range=$_REQUEST['range']; 
	

	//change the text color whether any ignore result is occured
	$_SESSION['colorArray'][] = $_REQUEST['colorArray'];
	
	$colorArray = array();
	$colorArray = array_unique($_SESSION['colorArray']);

	for($i=0; $i<$range; $i++)
	{

	  	$_SESSION[$i] = $_REQUEST['colorArray'];
	}
	
	echo "<div style=\"display:none;\">#@</div>";	
		
	
	foreach($colorArray as $kk=>$vv)
	{
			
		echo '<span style="color:red;"> '.$vv.' number record to be ignored</span>';
		
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
	
