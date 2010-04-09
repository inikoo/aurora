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
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');


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


$store_code='UK';


$sql=sprintf("select * from `Customer Dimension` C left join `Email Dimension` E on ( `Customer Main Email Key`=E.`Email Key`) where `Customer Main Email Key`>0  ");

$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  //  print $row['Customer Store Key'].' '.$row['Email']."\n";
 
  $data=array(
	      'User Handle'=>$row['Email']
	      ,'User Type'=>'Customer_'.$row['Customer Store Key']
	      ,'User Password'=>md5(generatePassword(21,10))
	      ,'User Active'=>'Yes'
	      ,'User Alias'=>$row['Customer Name']
	      ,'User Parent Key'=>$row['Customer Key']
	      );
  // print_r($data);
  $user=new user('new',$data);
  if(!$user->id){
    print $row['Customer Store Key'].' '.$row['Email']."  ".$user->msg."\n";
   
    
  }
  
}


function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy'.md5(mt_rand());
	$consonants = 'bdghjmnpqrstvz'.md5(mt_rand());
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZlkjhgfduytrdqwertyuiopasdfghjklzxcvbnm';
	}
	if ($strength & 2) {
		$vowels .= "AEUY4,cmoewmpaeoi8m5390m4pomeotixcmpodim";
	}
	if ($strength & 4) {
		$consonants .= '2345678906789$%^&*(';
	}
	if ($strength & 8) {
		$consonants .= '!=/[]{}~|\<>$%^&*()_+@#.,)(*%%';
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