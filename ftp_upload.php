<?php
/*
 File: users.php 

 UI user managment page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
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




if (isset($_POST['Submit'])) {
$ftp_server=$_POST['server'];
$ftp_user_name=$_POST['username'];
$ftp_user_pass=$_POST['password'];
$source_file=$_FILES['file']['name'];// retrieve name of the file to be uploaded
$destination_file=$source_file;
// make a connection to the ftp server 
$conn_id = ftp_connect($ftp_server);

// login with username and password 
$login_result = ftp_login($conn_id , $ftp_user_name , $ftp_user_pass);

// check connection 
if((!$conn_id)||(!$login_result)){ 
echo "FTP connection has failed!" ; 
echo "Attempted to connect to $ftp_server for user $ftp_user_name" ; 
exit; 
}else{ 
$confirm1="";
$confirm1="Connection to $ftp_server, for user $ftp_user_name is established";
//echo "Connected to $ftp_server, for user $ftp_user_name" ; 
} 
if(isset($_POST['Submit']))
{ 
$target_path = "app_files/uploads/";

$target_path = $target_path . basename( $_FILES['file']['name']); 
$name=basename( $_FILES['file']['name']); 
if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
    $source_file="app_files/uploads/".basename( $_FILES['file']['name']);
}
}
 
// upload the file 
$upload = ftp_put($conn_id,$destination_file,$source_file,FTP_ASCII );

// check upload status 
if(!$upload){ 
echo "FTP upload has failed!" ; 
}else{ 
$confirm2="Uploaded $name to $ftp_server as $destination_file" ; 
//echo "Uploaded $source_file to $ftp_server as $destination_file" ; 
}

// close the FTP stream 
ftp_close($conn_id); 


$smarty->assign('confirm1',$confirm1);
$smarty->assign('confirm2',$confirm2);
}
 $smarty->display('ftp_upload.tpl'); 
?>
