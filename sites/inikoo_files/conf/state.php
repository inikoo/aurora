<?php
$default_state=array(

	'customer'=>array(
		'orders'=>array(
			'sf'=>0,
			'nr'=>100,
			'order'=>'date',
			'order_dir'=>'desc'
		)

	),





);
if (!isset($_SESSION['state'])) {

	$_SESSION['state']=$default_state;
}
?>
