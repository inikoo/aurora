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

global $myconf;


$store_code='UK';

//$sql=sprintf("select P.`Page Key` from `Page Dimension` P  left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)  where `Page Type`='Store'  and `Page Store Function`='Information' ");
$sql=sprintf("select P.`Page Key` from `Page Dimension` P  left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)  where `Page Type`='Store'  ");

$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  $sql=sprintf("delete from `Page Dimension` where `Page Key`=%d",$row['Page Key']);
  // print "$sql\n";
  mysql_query($sql);
  $sql=sprintf("delete from `Page Store Dimension` where `Page Key`=%d",$row['Page Key']);
  mysql_query($sql);
  print "$sql\n";
  
}


$data=array(
	     array(
		  'Page Code'=>'home'
		  ,'Page Source Template'=>'pages/'.$store_code.'/home.tpl'
		  ,'Page URL'=>'index.php'
		  ,'Page Description'=>'Home Page'

		  ,'Page Title'=>'Ancient Wisdom Home'
		  ,'Page Short Title'=>'Home'
		  ,'Page Store Title'=>'Welcome to Ancient Wisdom'
		  ,'Page Store Subtitle'=>'Europe\'s Biggest Online Giftware Wholesaler'
		  ,'Page Store Slogan'=>'Exotic & Esoteric'
		  ,'Page Store Resume'=>'Currently we have over 10000 exotic, interesting & unique wholesale product lines spread over approaching 1000 web pages all available to order on-line for delivery next day in the UK (well we do our best)'

		  
		  )
	     ,array(
		  'Page Code'=>'register'
		  ,'Page Source Template'=>'pages/'.$store_code.'/register.tpl'
		  ,'Page URL'=>'register.php'
		  ,'Page Description'=>'Registration Page'

		  ,'Page Title'=>'Registration'
		  ,'Page Short Title'=>'Registration'
		  ,'Page Store Title'=>'Register to Ancient Wisdom'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Hello stranger'
		  ,'Page Store Resume'=>'Please note this is a wholesale site we supply wholesale to the trade.'

		  
		  )
  ,array(
		  'Page Code'=>'reset'
		  ,'Page Source Template'=>'pages/'.$store_code.'/reset.tpl'
		  ,'Page URL'=>'reset.php'
		  ,'Page Description'=>'Reset Password Page'

		  ,'Page Title'=>'Reset Password'
		  ,'Page Short Title'=>'Reset Pasword'
		  ,'Page Store Title'=>'Reset Pasword'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Set your password'
		  ,'Page Store Resume'=>'Please note this is a wholesale site we supply wholesale to the trade.'

		  
		  )

	     ,array(
		  'Page Code'=>'contact'
		  ,'Page Source Template'=>'splinters/info/'.$store_code.'/contact.tpl'
		  ,'Page URL'=>'info.php?page=contact'
		  ,'Page Description'=>'Contact information details (address, telephones, emails, and directions)'
		  
		  ,'Page Title'=>'Contact Details'
		  ,'Page Short Title'=>'Contact'
		  ,'Page Store Title'=>'Contact Page'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'You know where we are'
		  ,'Page Store Resume'=>'Please don\'t hesitate to contact us if you need more information<br>In May 2008 we moved to brand new premises, you can visit us and have a look at our showroom, to make an appoiment please click <a href="info.php?page=showroom">here</a>'
		  )

	     ,array(
		  'Page Code'=>'showroom'
		  ,'Page Source Template'=>'splinters/info/'.$store_code.'/showroom.tpl'
		  ,'Page URL'=>'info.php?page=showroom'
		  ,'Page Description'=>'Information about our showroom'
		  
		  ,'Page Title'=>'Showroom'
		  ,'Page Short Title'=>'Showroom'
		  ,'Page Store Title'=>'Showroom'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'You can visit us!'
		  ,'Page Store Resume'=>'Why not visit us... we are always delighted to see our customers.'
		  )	     
 ,array(
		  'Page Code'=>'export_guide'
		  ,'Page Source Template'=>'splinters/info/'.$store_code.'/export_guide.tpl'
		  ,'Page URL'=>'info.php?page=overseas'
		  ,'Page Description'=>'Information about overseas orders'
		  
		  ,'Page Title'=>'Export Guide'
		  ,'Page Short Title'=>'Export'
		  ,'Page Store Title'=>'Export Guide'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Shipping Worldwide'
		  ,'Page Store Resume'=>'We have experience in shipping to many countries on all continents.<br/>Philippe our dedicated export customer service advisor is at your services, he  speak English & French well and will try his best in any European language'
		  )

	      ,array(
		  'Page Code'=>'terms_and_conditions'
		  ,'Page Source Template'=>'splinters/info/'.$store_code.'/terms_and_conditions.tpl'
		  ,'Page URL'=>'info.php?page=terms_and_conditions'
		  ,'Page Description'=>'Terms and Conditions'
		  
		  ,'Page Title'=>'Terms & Conditions'
		  ,'Page Short Title'=>'T&C'
		  ,'Page Store Title'=>'Terms & Conditions'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'The small print'
		  ,'Page Store Resume'=>''
		  )	     

	     
	    );


foreach($data as $page_data){
  $page_data['Page Store Order Template']='No Applicable';
  $page_data['Page Store Function']='Information';
  $page_data['Page Store Creation Date']=date('Y-m-d H:i:s');
  $page_data['Page Store Last Update Date']=date('Y-m-d H:i:s');
  $page_data['Page Store Last Structural Change Date']=date('Y-m-d H:i:s');
  $page_data['Page Type']='Store';
  $page_data['Page Store Source Type'] ='Static';
  $page=new Page('find',$page_data,'create');
  //print_r($page);
  
}

$store_data=array(
'UK'=>array(
            'Slogan'=>'Britain Biggest Online Giftware Wholesaler'
            ,'Resume'=>'Currently we have over 10000 exotic, interesting & unique wholesale product lines spread over approaching 1000 web pages all available to order on-line for delivery next day in the UK (well we do our best)'
            
           ),
           'DE'=>array(
            'Slogan'=>'Germany Biggest Online Giftware Wholesaler'
            ,'Resume'=>'Currently we have over 10000 exotic, interesting & unique wholesale product lines spread over approaching 1000 web pages all available to order on-line for delivery next day in the UK (well we do our best)'
            
           ),
           'FR'=>array(
            'Slogan'=>'France Biggest Online Giftware Wholesaler'
            ,'Resume'=>'Currently we have over 10000 exotic, interesting & unique wholesale product lines spread over approaching 1000 web pages all available to order on-line for delivery next day in the UK (well we do our best)'
            
           ),
           'PL'=>array(
            'Slogan'=>'Poland Biggest Online Giftware Wholesaler'
            ,'Resume'=>'Currently we have over 10000 exotic, interesting & unique wholesale product lines spread over approaching 1000 web pages all available to order on-line for delivery next day in the UK (well we do our best)'
            
           )

);

$sql=sprintf("select * from `Store Dimension`  ");

$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
$store=new Store($row['Store Key']);
  $data=array();
  $data['Page Store Slogan']=$store_data[$row['Store Code']]['Slogan'];
  $data['Page Store Resume']=$store_data[$row['Store Code']]['Resume'];
  $data['Showcases Layout']='Splited';
  $data['Page Store Function']='Store Catalogue';

// print_r($data); 
  $store->create_page($data);
  
  
}


$sql=sprintf("select * from `Product Department Dimension` left join  `Store Dimension` on (`Product Department Store Key`=`Store Key`)  where `Product Department Sales Type`='Public Sale'  ");

$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
$department=new Department($row['Product Department Key']);
  $data=array();
  $data['Page Store Slogan']=(isset($department_data[$row['Store Code'].'_'.$row['Product Department Code']]['Slogan'])?$department_data[$row['Store Code'].'_'.$row['Product Department Code']]['Slogan']:'');
  $data['Page Store Resume']=(isset($department_data[$row['Store Code'].'_'.$row['Product Department Code']]['Resume'])?$department_data[$row['Store Code'].'_'.$row['Product Department Code']]['Resume']:'');
$data['Page Store Function']='Department Catalogue';
  $data['Showcases Layout']='Splited';
  $department->create_page($data);
}


$sql=sprintf("select * from `Product Family Dimension` left join  `Store Dimension` on (`Product Family Store Key`=`Store Key`)  where `Product Family Sales Type`='Public Sale'  ");

$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
$family=new Family($row['Product Family Key']);
  $data=array();
  $data['Page Store Slogan']=(isset($family_data[$row['Store Code'].'_'.$row['Product Family Code']]['Slogan'])?$family_data[$row['Store Code'].'_'.$row['Product Family Code']]['Slogan']:'');
  $data['Page Store Resume']=(isset($family_data[$row['Store Code'].'_'.$row['Product Family Code']]['Resume'])?$family_data[$row['Store Code'].'_'.$row['Product Family Code']]['Resume']:'');
$data['Page Store Function']='Family Catalogue';
  $data['Showcases Layout']='Splited';
  $family->create_page($data);
}

?>