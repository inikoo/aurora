<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
date_default_timezone_set('UTC');

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           


$calculate_same_id=true;
$calculate_discontinued=true;

//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$stores=array(1,2,3);


if($calculate_same_id){
foreach($stores as $store_key){
  print "set historic same id store $store_key\n";
$sql=sprintf("select `Product Code` from `Product Dimension` group by `Product Code`");
$res_code=mysql_query($sql);
while($row_c=mysql_fetch_array($res_code)){
  $code=$row_c['Product Code'];
  $sql=sprintf("select * from `Product Dimension` where `Product Store Key`=$store_key and `Product Code`=%s order by  `Product Valid To` desc",prepare_mysql($code));
  $res=mysql_query($sql);
  $number=mysql_num_rows($res);
  if($number>1){
    $count=0;  
    while($row=mysql_fetch_array($res)){
      $pid=$row['Product ID'];
    $to=$row['Product Valid To'];
    //print "$code $pid $to ".$row['Product Short Description']."\n";
    if($count>0){
      $sql=sprintf("update `Product Dimension` set `Product Record Type`='Historic',`Product Sales Type`='Not for Sale',`Product To Be Discontinued`='No Applicable',`Product Web Configuration`='Offline' where `Product ID`=%d",$pid);
      //exit($sql);
      mysql_query($sql);
    }

    $count++;
  }
  }
}

}
}
if($calculate_discontinued){

print "calculate discintined prods\n";
// $sql="select * from `Product History Dimension` PH  left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)   where `Product Store Key` in (".join(',',$stores).")  order by `Product Key`  desc ";
  $sql="select * from `Product Dimension` order by `Product Code`  ";


$result=mysql_query($sql);
//print $sql;
while($row=mysql_fetch_array($result)   ){
  $product=new Product('pid',$row['Product ID']);
  
  //$product->update_sales();
 
  $web_state='Online Auto';
  //print_r($product->data);
  if($product->data['Product Sales Type']=='Not for Sale'){
    
    $record_type='Historic';
    $to_be_discontinued='No Applicable';
    $sales_type='Not for Sale';
    

  }else{

    $record_type='Normal';
    $one_week=604800;
    if( date('U')-strtotime($product->data['Product For Sale Since Date'])<$one_week){
      $record_type='New';
    }
     $to_be_discontinued='No';
    $sales_type='Public Sale';
     if(preg_match('/(cartsg|bop|mos|beo|bos|bosx|eos|eosa)\-/i',$product->data['Product Code'])){
      $sales_type='Private Sale';
      
      
    }elseif(preg_match('/jbb-mix(1|2)/i',$product->data['Product Code'])){
	$sales_type='Private Sale';
    }

   if($sales_type=='Public Sale'){
     if($product->data['Product 1 Year Acc Quantity Ordered']==0 and (strtotime($product->data['Product Valid From'])<strtotime('today -1 year')    )){
       //check if has stock
       $sql=sprintf("select id,code  from aw_old.product  where product.code=%s and (stock=0 or stock<0 or stock is null)   ",prepare_mysql($product->data['Product Code']));
	  $result2a=mysql_query($sql);
	  if($row2a=mysql_fetch_array($result2a, MYSQL_ASSOC)   ){
	    $to_be_discontinued='No Applicable';
	    $record_type='Discontinued';
	  
	    

	  }
	  
	}
	
	$sql=sprintf("select id,code,stock  from aw_old.product  where product.code=%s and  condicion=2  ",prepare_mysql($product->data['Product Code']));
	$result2a=mysql_query($sql);


	if($row2a=mysql_fetch_array($result2a, MYSQL_ASSOC)   ){
	  if($row2a['stock']==0){
	    $to_be_discontinued='No Applicable';
	    $record_type='Discontinued';
	  }else{
	    $to_be_discontinued='Yes';
	    $record_type='Discontinuing';
	    
	  }
	
	}
	
   }
  }






   $sql=sprintf("update `Product Dimension` set  `Product Sales Type`=%s,`Product Record Type`=%s,`Product Web Configuration`=%s ,`Product To Be Discontinued`=%s where `Product ID`=%d"
		,prepare_mysql($sales_type)
		,prepare_mysql($record_type)
		,prepare_mysql($web_state)
		,prepare_mysql($to_be_discontinued)

		,$product->pid);
   
   //print $sql;
    if(!mysql_query($sql))
      exit("can not upodate state of the product $sql");



    

    $product->update_parts();




    


   print $row['Product ID']."\t\t ".$product->data['Product Code']." \r";






}

}



  
$sql="update `Part Dimension` set `Part Status`='Not In Use'  ";
mysql_query($sql);


  $sql="select * from `Product Dimension`   ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  if($row['Product Sales Type']!='Not for Sale' and $row['Product Record Type']!='Discontinued' ){

    $product=new Product('pid',$row['Product ID']);
    $parts=$product->get_part_list();
    foreach($parts as $sku=>$data){
      $sql=sprintf("update `Part Dimension` set `Part Status`='In Use' where `Part SKU`=%d  ",$sku);
      mysql_query($sql);
    }


  }

}


?>