<?php
/*
 File: ftp_transfer.php 
 About: Upload & Download the file via ftp using class.FTP.php
 Autor: Raul Perusquia <rulovico@gmail.com>
 Copyright (c) 2009, Kaktus 
 Version 2.0
*/
include_once('class.FTP.php');
include_once('common.php');
if(!$user->can_view('users'))
  exit();		 
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css'		 
		 );		
 	 
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'button/button-min.js',
		$yui_path.'menu/menu-min.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

       /*$ftp_server = "66.7.209.128";
	$ftp_user = "primediartco@primediart.com";
	$ftp_passwd = "prime!2345";
        $ftp_server = "213.175.222.120";
	$ftp_user = "prime@relaxingthings.com";
	$ftp_passwd = "prime11";*/

if (isset($_POST['Submit'])) {
	$ftp_server=$_POST['server'];
	$ftp_user=$_POST['username'];
	$ftp_passwd=$_POST['password'];
	$source_file=$_FILES['file']['name'];// retrieve name of the file to be uploaded
	$destination_file=$source_file;
	// make a connection to the ftp server 
	$ftp = new FTP();
	$ftp->connect($ftp_server);$ftp->login($ftp_user,$ftp_passwd);
	$confirm2="";
	$ftp->ssl_connect();
	if ($ftp->connect($ftp_server)) {
		//echo "connected";
		if ($ftp->login($ftp_user,$ftp_passwd)) {//echo "enterd into login";
			$target_path = "app_files/uploads/";
			$target_path = $target_path .basename( $_FILES['file']['name']); 
			$name=basename( $_FILES['file']['name']); 
			if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
   			 $source_file="app_files/uploads/".basename( $_FILES['file']['name']);
			}
			$ftp->put($destination_file,$source_file);
			$confirm2="Uploaded $name to $ftp_server as $destination_file" ; 
			}	
	}
else echo "not conneted";
$smarty->assign('confirm2',$confirm2);
}

if (isset($_POST['Download_Submit'])) {
	$ftp_server=$_POST['server'];
	$ftp_user=$_POST['username'];
	$ftp_passwd=$_POST['password'];
	$remote_file = $_POST['file'];
	$ftp = new FTP();
	$ftp->connect($ftp_server);$ftp->login($ftp_user,$ftp_passwd);
	$confirm4="";
	$ftp->ssl_connect();
	if ($ftp->connect($ftp_server)) {
		//echo "connected";
		if ($ftp->login($ftp_user,$ftp_passwd)) {//echo "enterd into login";
			$ftp->chmod(777,$remote_file);
			$local_file = "app_files/uploads/".$remote_file;
			$ftp->get($local_file,$remote_file);
			$confirm4="Downloaded $remote_file from $ftp_server and stored in folder app_files/uploads" ; 
			}
		}
else echo "not conneted";
$smarty->assign('confirm4',$confirm4);
}


 $smarty->display('ftp_upload.tpl'); 
?>
