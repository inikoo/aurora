<?php
/*
 File: marketing.php 

 UI index page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

include_once('common.php');

include_once('class.Product.php');
include_once('class.Order.php');

$del_sql = array();
$page='marketing';
$user_key = $_SESSION['user_key'];

if(!isset($_GET['l'])){

	header('Location:marketing.php');

}else{

$current_list_id = trim($_GET['l']);

// deleting record from list table
$del_sql[0] = "DELETE FROM `Email Campaign Mailing List` WHERE `Email Campaign Mailing List Key` = '$current_list_id' and `User Key` = '$user_key'";

// deleting record(s) from people/subscribers list
$del_sql[1] = "DELETE FROM `Email People Dimension` WHERE `People List Key` = '$current_list_id'";

// deleting related record(s) from Group
$del_sql[2] = "DELETE FROM `Email Campaign Group Titile` WHERE `Email Campaign Group Titile`.`Email List Key` = '$current_list_id'";
	
$del_sql[3] = "DELETE FROM `Email Campaign Group Titile Name Bridge` WHERE `Email Campaign Group Titile Name Bridge`.`Email Campaign Group Key` = (SELECT `Email Campaign Group Titile`.`Email Campaign Group Key` FROM `Email Campaign Group Titile` WHERE `Email Campaign Group Titile`.`Email List Key` = '$current_list_id')";

//deleting related records(s) from Campaign
$del_sql[4] = "";

echo "Under progress";



}

?>
