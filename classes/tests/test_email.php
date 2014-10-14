<?php
/*
 Script: test_email.php
 Tests for email class

 This test should be performed in a clean database!!
 mysql -u MYSQL_USER -p dw < ../../mantenence/scripts/truncate/
 Warning just use it in  a development database

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/


include_once('../../conf/dns.php');
include_once('../../class.Email.php');

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}

require_once '../../common_functions.php';

mysql_set_charset('utf8');

require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');
$_SESSION['lang']=1;

// This test should be perfoermed in a clean database
// mysql -u MYSQL_USER -p dw < ../../mantenence/scripts/truncate
// Warning just use it in  a development database


// Create email & anonymous contact
$data=array('Email'=>'rulovico@gmail.com');
$email=new Email('find create',$data);
$data=array('Email'=>'raul@gmail.com');
$email=new Email('find create',$data);
$data=array('Email'=>'rulovico@gmail.com');
$email=new Email('find create',$data);
$data=array('Email'=>'test@gmail.com');
$email=new Email('find create',$data);
$data=array('Email'=>'test@hotmail.com');
$email=new Email('find create',$data);
$data=array('Email'=>'rulovico@gmail.com');
$email=new Email('find create',$data);



//print_r($email);





/* data=array('Email'=>'rulovico2@gmail.com'); */
/* $email=new Email(1); */
/* $email->update($data); */

/* print_r($email); */
/* exit; */
/* $data=array('Email Correct'=>'No'); */
/* $email=new Email(1); */
/* $email->update($data); */
/* print_r($email); */

/* $data=array('Email Correct'=>'Yes'); */
/* $email->update($data); */
/* $data=array('Email Contact Name'=>'Raul Perusquia'); */
/* $email->update($data); */
/* print_r($email); */


?>