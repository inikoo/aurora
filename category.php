<?php
/*
	File: customer.php

UI customer page

About:
Autor: Raul Perusquia <rulovico@gmail.com>

	Copyright (c) 2012, Inikoo

Version 2.0
	*/
include_once 'class.Category.php';
include_once 'common.php';


if (isset($_REQUEST['id'])  or !is_numeric($_REQUEST['id'])) {
	$category_key=$_REQUEST['id'];
} else {
	header('Location: index.php?error_no_category_key');
	exit;
}

$category=new Category($category_key);
if (!$category->id) {
	
	$sql=sprintf("select `Category Deleted Subject`,`Category Deleted Key` from `Category Deleted Dimension` where `Category Deleted Key`=%d ",$category_key);
	$res=mysql_query($sql);
	if($row=mysql_fetch_assoc($res)){
	$subject=$row['Category Deleted Subject'];
	
	}else{
	
	header('Location: index.php?error_category_key_not_found');
	exit;
	}

}else{
	$subject=$category->data['Category Subject'];

}


switch ($subject) {
case('Part'):
	header('Location: part_category.php?id='.$category_key);
	break;
case('Customer'):
	header('Location: customer_category.php?id='.$category_key);
	break;
case('Invoice'):
	header('Location: invoice_category.php?id='.$category_key);
	break;
case('Supplier'):
	header('Location: supplier_category.php?id='.$category_key);
	break;
case('Product'):
	header('Location: product_category.php?id='.$category_key);
	break;
case('Family'):
	header('Location: family_category.php?id='.$category_key);
	break;


default:
	header('Location: index.php');
}



?>
