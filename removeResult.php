<?php
	include('common.php');


	//$range=isset($_REQUEST['range'])?$_REQUEST['range']:''; 
	
	$_SESSION['colorArray'][] = isset($_REQUEST['colorArray'])?$_REQUEST['colorArray']:'0';
	
	$colorArray = array();

	$colorArray = array_unique($_SESSION['colorArray']);

	echo "@";

/**********************************************************************************/
	$_SESSION['getQueryString'][] = $_GET['v'];

	$result = array_unique($_SESSION['getQueryString']);
	
	foreach($result as $key=>$value)
	{
		
		echo '<input type="hidden" name="hidden_array[]" value="'.$value.'">';
	}
?>
