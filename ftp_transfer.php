<?php
/*
 File: ftp_transfer.php 
 About: Upload & Download the file via ftp using class.FTP.php
 Autor: Raul Perusquia <rulovico@gmail.com>
 Copyright (c) 2009, Inikoo 
 Version 2.0
*/

include_once('common.php');
if(!$user->can_view('users'))
  exit();		 
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 'css/common.css',
		 'css/container.css',
		 'css/button.css',
		 'css/table.css'		 
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
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
include('class.FTP.php');
if (isset($_POST['Submit'])) {
	$ftp_server=$_POST['server'];
	$ftp_user=$_POST['username'];
	$ftp_passwd=$_POST['password'];
	$source_file=$_FILES['file']['name'];// retrieve name of the file to be uploaded
	$destination_file=$source_file;
	// make a connection to the ftp server 
	$ftp = new threessFTP($ftp_server,$ftp_user,$ftp_passwd);
	$ftp->threessFTP($ftp_server,$ftp_user,$ftp_passwd);
	$ftp->connect();$ftp->login();
	$confirm2="";
	
			$target_path = "app_files/uploads/";
			$target_path = $target_path .basename( $_FILES['file']['name']); 
			$name=basename( $_FILES['file']['name']); 
			if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
   		        $source_file="app_files/uploads/".basename( $_FILES['file']['name']);
			}else echo "not uloaded";
			chmod($source_file, 0777);
			$ftp->upload($source_file,$destination_file,'FTP_BINARY');
			//$ftp->put($destination_file,$source_file);
			$confirm2="Uploaded $name to $ftp_server as $destination_file" ; 
	$smarty->assign('confirm2',$confirm2);
}
if (isset($_POST['Download_Submit'])) {
	$ftp_server=$_POST['server'];
	$ftp_user=$_POST['username'];
	$ftp_passwd=$_POST['password'];
	$source_file = $_POST['file'];
	$ftp = new threessFTP($ftp_server,$ftp_user,$ftp_passwd);
        $ftp->threessFTP($ftp_server,$ftp_user,$ftp_passwd);
	$ftp->connect();$ftp->login();
	$confirm4="";
			//chmod($source_file, 0777);
			$destination_file = "app_files/uploads/".$source_file;
			$ftp->download($source_file,$destination_file,'FTP_BINARY');
			chmod($destination_file, 0777);
			$confirm4="Downloaded $source_file from $ftp_server and stored in folder app_files/uploads" ; 
			
$smarty->assign('confirm4',$confirm4);
}
 $smarty->assign('title',"FTP file trasfer");
 $smarty->display('ftp_upload.tpl'); 	
?>

