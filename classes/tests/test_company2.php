<?php
/*
 Script: test_contacts.php
 Tests for contact class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/


include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Order.php');
error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");

require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');
$_SESSION['lang']=1;

$contact_data=array();
$company_data=array();
$row = 0;
$handle = fopen("somecontacts.txt", "r");
//$handle = fopen("data.txt", "r");

$with_email=0;


while (($data = fgetcsv($handle, 2000, "\t")) !== FALSE) {
  
  // print count($data)."\n";
  if(count($data)<93)
    continue;
  


  $x__data=array();

  $forbiden_names=array('sir/madam','sir,madam','manager','the manager','manageress','propietor','the propiertor','buyer');
  if(in_array(strtolower($data[3]),$forbiden_names))
      $data[3]='';

  
  $data[13]=preg_replace('/^\[1\]/','',$data[13]);
    $data[12]=preg_replace('/^\[1\]/','',$data[12]);
  $data[15]=preg_replace('/^\[1\]/','',$data[15]);


  if($data[92]!='')
    $with_email++;
  if(($data[2]=='' or $data[2]==$data[3]) and $data[3]!=''  ){
      //Personal Contact
    //  print "$row Person ============================\n";
      $x__data['Contact Name']=$data[3];
      $x__data['Contact Work Address Line 1']=$data[4];
      $x__data['Contact Work Address Line 2']=$data[5];
      $x__data['Contact Work Address Line 3']=$data[6];
      $x__data['Contact Work Address Town']=$data[7];
      $x__data['Contact Work Address Country Primary Division']=$data[8];
      $x__data['Contact Work Address Postal Code']=$data[9];
      $x__data['Contact Work Address Country Name']=$data[10];
      $x__data['Contact Main XHTML Telephone']=$data[12];
      $x__data['Contact Main XHTML FAX']=$data[13];
      $x__data['Contact Main XHTML Mobile']=$data[15];
      $x__data['Contact Main Plain Email']=$data[92];
      

    }elseif($data[2]!='' and $data[2]!=$data[3] and $data[3]!=''){
      //Company
    //  print "$row Company ============================\n";



   



      $x__data['Company Name']=$data[2];
      $x__data['Company Address Line 1']=$data[4];
      $x__data['Company Address Line 2']=$data[5];
      $x__data['Company Address Line 3']=$data[6];
      $x__data['Company Address Town']=$data[7];
      $x__data['Company Address Country Primary Division']=$data[8];
      $x__data['Company Address Postal Code']=$data[9];
      $x__data['Company Address Country Name']=$data[10];
      $x__data['Company Main XHTML Telephone']=$data[12];
      $x__data['Company Main XHTML FAX']=$data[13];
      $x__data['Company Main Mobile']=$data[15];
      $x__data['Company Main Plain Email']=$data[92];
      $x__data['Company Main Contact Name']=$data[3];

    

    }else
       continue;
       // print "$row Nothing  ============================\n";






       $adate=split('/',$data[89]);
       
       if(count($adate)==3)
	 $date=strtotime($adate[2].'-'.$adate[1].'-'.$adate[0]);
       else
	 $date=strtotime('1999-01-01');
       $_data[]=$x__data;
       $_date[]=$date;
       // print_r($x__data);
	 //     print $data[89]." $date\n";
	 $row++;

	 //	 if($row>3)
	 //  break;
	
}
fclose($handle);

//print "$row $with_email\n";exit;
asort($_date);




$count=1;


$count=1;
foreach ($_date as $key=>$val) {
  print "$count ====================================\n";
  print_r($_data[$key]);
  if(isset($_data[$key]['Company Name'])){
    //    print "caca";
    //   if(preg_match('/karen|cornes/i',$_data[$key]['Company Main Contact Name']))

    //print "Email ".$_data[$key]['Company Main Plain Email']."\n";
    $company=new Company('find create auto',$_data[$key]);
  }elseif(isset($_data[$key]['Contact Name'])){


    $_tmp=$_data[$key];
    unset($_tmp['Contact Name']);
    if(array_empty($_tmp))
      continue;
    //   if(preg_match('/karen|cornes/i',$_data[$key]['Contact Name']))
    // print "Email ".$_data[$key]['Contact Main Plain Email']."\n";
    $contact=new Contact('find create',$_data[$key]);

  }
 
  
  $count++;
  //  if($count>250)
  //  exit;
}






?>