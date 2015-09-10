<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2012, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');

include_once('class.Site.php');



if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
    $site_id=$_REQUEST['id'];

} else {
   exit("xx");
}

$site=new Site($site_id);

//print_r($site);




$ftp_connection=$site->create_ftp_connection();
if($ftp_connection->error){
	print $ftp_connection->msg;
}else{
    
    $ftp_connection->upload_string('aaaaa','./this/that/do.html');
    print_r($ftp_connection);
}

$ftp_connection->end();
exit;
