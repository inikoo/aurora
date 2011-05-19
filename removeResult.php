<?php
	include_once('common.php');


	$_SESSION['records_ignored_by_user'][] = isset($_REQUEST['records_ignored_by_user'])?$_REQUEST['records_ignored_by_user']:'';
	
	$records_ignored_by_user = array();

	$records_ignored_by_user = array_unique($_SESSION['records_ignored_by_user']);

	


	echo "<span style='color:white;'>@</span>";

/**********************************************************************************/
	$_SESSION['getQueryString'][] = $_GET['v'];

	$result = array_unique($_SESSION['getQueryString']);
	
	foreach($result as $key=>$value)
	{
		
		echo '<input type="hidden" name="hidden_array[]" value="'.$value.'">';
	}
?>
