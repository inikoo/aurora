<?php
date_default_timezone_set('UTC');

include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Image.php');

include_once('../../class.SupplierProduct.php');
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}

 
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           


//$sql=sprintf("select `Product Family Key`,`Product Family Code` from `Product Family Dimension`");

 // $res=mysql_query($sql);
 // while($row=mysql_fetch_array($res)){
  
//  print "wget www.ancientwisdom.biz/pics/".strtolower($row['Product Family Code']).".jpg;\n";
//  
 // }
//exit;

/* $sql="select `Product Code` from `Product Dimension`  group by `Product Code` "; */

/* $result=mysql_query($sql); */
/* while($row=mysql_fetch_array($result)   ){ */
/*   $code=$row['Product Code']; */
/*   print "$code\n"; */

$pics=array();

$path="pics/";
$img_array_full_path = glob($path."*.jpg");
//print_r($img_array_full_path);
foreach($img_array_full_path as $pic_path){
   $_pic_path=preg_replace('/.*\//','',$pic_path);
  if(preg_match('/[a-z0-9\-]+(|_l|_bis|_tris|_quad|_display|_displ|dpl|_box)+.jpg/',$_pic_path)){
 // if(preg_match('/avalo[a-z0-9\-]+(|_l|_bis|_tris|_quad|_display|_displ|dpl|_box)+.jpg/',$_pic_path)){

    // print "$pic_path\n";
   
      $root=preg_replace('/(_.*)?\..*$/','',$_pic_path);
      if(array_key_exists($root,$pics))
    	$pics[$root][]=$pic_path;
         else
	   $pics[$root]=array($pic_path);
 
    
    
    
  }
} 
//print_r($pics);


chdir('../../');
foreach($pics as $key=>$value){


$sql=sprintf("select `Product Family Key`,`Product Family Code` from `Product Family Dimension` where `Product Family Code`=%s ",prepare_mysql($key));
  $res=mysql_query($sql);
  //print "$sql\n";
  while($row=mysql_fetch_array($res)){
  // print "$sql\n";
  $family=new Family($row['Product Family Key']);
    foreach($value as $img_filename){
     
      print "F-- $key --".getcwd()."------ ".$img_filename."   \n";
      $rand=rand().rand();
      $tmp_file='app_files/pics/tmp/tmp2.jpg';
      copy('mantenence/scripts/'.$img_filename,$tmp_file );
     // exit;
     
     $data=array(
	    'file'=>'tmp2.jpg'
	    ,'path'=>'assets/'
	    ,'name'=>$row['Product Family Code']
	    ,'caption'=>''
	    );

//     print_r($data);
     
$image=new Image('find',$data,'create');
   print $image->id."\n";
   $family->add_image($image->id,'principal');
      //print_r($family);
      // print $family->msg."\n";
      $family->update_main_image();
      // unlink($tmp_file);
    }

  }
  mysql_free_result($res);



 





}


?>