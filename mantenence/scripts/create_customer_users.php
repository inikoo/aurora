<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Page.php');
include_once('../../class.Store.php');
include_once('../../class.Site.php');
include_once('../../class.User.php');

error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
require_once '../../class.User.php';           

global $myconf;


$fp = fopen('file_x1.csv', 'w');

$site=new Site(1);
$store_code='UK';
$found=0;
$not_found=0;

$sql=sprintf("select * from `Customer Dimension` where (`Customer Send Email Marketing`='Yes' or `Customer Send Newsletter`='Yes') and `Customer Store Key`=1 and `Customer Main Email Key`>0  ");

$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
//    print $row['Customer Store Key']."\n";
 
 if(filter_var($row['Customer Main Plain Email'], FILTER_VALIDATE_EMAIL)){ 
 
 
 $password=generatePassword2(7,10);
 
  $data=array(
	      'User Handle'=>$row['Customer Main Plain Email']
	      ,'User Type'=>'Customer'
	      ,'User Password'=>hash('sha256',$password)
	      ,'User Active'=>'Yes'
	      ,'User Alias'=>$row['Customer Name']
	      ,'User Parent Key'=>$row['Customer Key']
	      ,'User Site Key'=>1
	      );
  // print_r($data);
   
   
  $user=new user('find',$data);
  
  
 // print_r($user);
  
  if($user->id){
//	print "Found\n";
$found++;
  }else{
// 	print "Not Found\n";
  $not_found++;
  
  
  	$data=array(
			'User Handle'=>$row['Customer Main Plain Email'],
			'User Type'=>'Customer',
			'User Password'=>hash('sha256',$password),
			'User Active'=>'Yes',
			'User Alias'=>$row['Customer Name'],
			'User Site Key'=>1,
			'User Parent Key'=>$row['Customer Key']
		);

		$user=new user('new',$data);

		$site->update_customer_data();
  
  
  $fields=array($password,$row['Customer Main Plain Email'],$row['Customer Name'],$row['Customer Main Contact Name']);
  
  print join(',',$fields)."\n";
  
  //print_r($fields);
    fputcsv($fp, $fields);

  
  
  }
 // exit;
 
 }
 
}
fclose($fp);
//print "$found $not_found \n";


function generatePassword2($length=9, $strength=0) {
	$vowels = '12345';
	$consonants = '1234567';
	if ($strength & 1) {
		$consonants .= '123456789abc';
	}
	if ($strength & 2) {
		$vowels .= "123456789abc";
	}
	if ($strength & 4) {
		$consonants .= '123456789abc';
	}
	if ($strength & 8) {
		$consonants .= '123456789abc';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(mt_rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(mt_rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

?>