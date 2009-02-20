<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');
include_once('../../classes/Part.php');
include_once('../../classes/SupplierProduct.php');
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');


//$sql="select * from `Product Dimension` where `Product Code`='FO-A1'";
$sql="select * from `Product Dimension` ";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $product=new Product($row['Product Key']);
  $product->load('sales');
  $product->load('days');
  
  $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s order by `Product Valid From` limit 1",prepare_mysql($row['Product Code']));
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
    $same_code_from=$row2['Product Valid From'];
  }
  $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s order by `Product Valid To` desc",prepare_mysql($row['Product Code']));
  $most_recent='Yes';
  $result2=mysql_query($sql);
  while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
    if($most_recent=='Yes'){
      $most_recent_key=$row2['Product Key'];
      $same_code_to=$row2['Product Valid To'];
    }
    $sql=sprintf("update `Product Dimension` set  `Product Same Code Valid From`=%s ,`Product Same Code Valid To`=%s , `Product Same Code Most Recent Key`=%s,`Product Same Code Most Recent`=%s  where `Product Key`=%s ",prepare_mysql($same_code_from),prepare_mysql($same_code_to),$row['Product Key'],prepare_mysql($most_recent),$most_recent_key,$row2['Product Key']);
    //   print "$sql\n\n";
    mysql_query($sql);
     if($most_recent=='Yes')
       $most_recent=='No';

  }




  $sql=sprintf("select * from `Product Dimension` where `Product ID`=%s order by `Product Valid From` limit 1",prepare_mysql($row['Product ID']));
  
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
    $same_code_from=$row2['Product Valid From'];
  }else
    exit("caca1");
  $sql=sprintf("select * from `Product Dimension` where `Product ID`=%s order by `Product Valid To` desc limit 1",prepare_mysql($row['Product ID']));
  

  $most_recent='Yes';
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
    $same_code_to=$row2['Product Valid To'];
  }else
    exit("caca2");
  $sql=sprintf("update `Product Dimension` set  `Product Same ID Valid From`=%s ,`Product Same ID Valid To`=%s  where `Product Key`=%s ",prepare_mysql($same_code_from),prepare_mysql($same_code_to),$row['Product Key'],$row2['Product Key']);
  // print "$sql\n\n";
  mysql_query($sql);


  $product=new Product($row['Product Key']);
  
  if($product->data['Product Same Code Most Recent'])
    $state='For sale';
  else
    $state='History';


  if($product->data['Product 1 Year Acc Quantity Ordered']==0)
    $state='Discontinued';


  $sql=sprintf("update `Product Dimension` set  `Product Sales State`=%s where `Product Key`=%s",prepare_mysql($state),$product->id);
  // print "$sql\n\n";
  if(!mysql_query($sql))
    exit("can not upodate state of the product");

  //check iif it is in a department;

  $dept_no_dept=new Department('code','No department');
  if(!$dept_no_dept->id){
    $dept_data=array(
		     'code'=>'No Department',
		     'name'=>'Products Without Department',
		     );
    $dept_no_dept=new Department('create',$dept_data);
  }

   $promo=new Department('code','Promotional Items');
  if(!$promo->id){
    $dept_data=array(
		     'code'=>'Promotional Items',
		     'name'=>'Promotional Items',
		     );
    $promo=new Department('create',$dept_data);
  }

  $charges=new Department('code','Charges');
  if(!$charges->id){
    $dept_data=array(
		     'code'=>'Charges',
		     'name'=>'Charges & Ajustments',
		     );
    $charges=new Department('create',$dept_data);
  }

  $frc_fam=new Family('code','FRC');
  if(!$frc_fam->id){
    $fam_data=array(
		    'code'=>'FRC',
		    'name'=>'Freight Charges',
		    );
    $frc_fam=new Family('create',$fam_data);

  }

  if(preg_match('/^frc-/i',$product->data['Product Code'])){
    $sql=sprintf("update from `Product Dimension` set `Product Family Key`=%d,`Product Family Code`=%s,`Product Family Name`=%s  where `Product Key`=%d "
		 ,$frc_fam->id
		 ,prepare_mysql($frc_fam->data['Product Family Code'])
		 ,prepare_mysql($frc_fam->data['Product Family Name'])
		 ,$product->id);
    mysql_query($sql);
     $sql=sprintf("delete from `Product Department Bridge` where `Product Key`=%d ",$product->id);
    mysql_query($sql);
    $sql=sprintf("insert into  `Product Department Bridge` (`Product Key`,`Product Department Key`) values (%d,%d)",$product->id,$charges->id);
    mysql_query($sql);
  }



  if(preg_match('/^pi-|catalogue|^info|Mug-26x|OB-39x|SG-xMIXx|wsl-1275x|wsl-1474x|wsl-1474x|wsl-1479x|^FW-|^MFH-XX$|wsl-1513x|wsl-1487x|wsl-1636x|wsl-1637x/i',$product->data['Product Code'])){
    
     $sql=sprintf("update from `Product Dimension` set `Product Main Department Key`=%d,`Product Main Department Code`=%s,`Product Main Department Name`=%s  where `Product Key`=%d "
		 ,$promo->id
		 ,prepare_mysql($promo->data['Product Department Code'])
		 ,prepare_mysql($promo->data['Product Department Name'])
		 ,$product->id);
    mysql_query($sql);

    $sql=sprintf("delete from `Product Department Bridge` where `Product Key`=%d ",$product->id);
    if(!mysql_query($sql))
      exit("errir a");
    $sql=sprintf("insert into  `Product Department Bridge` (`Product Key`,`Product Department Key`) values (%d,%d)",$product->id,$promo->id);
    if(!mysql_query($sql))
      exit("errir b");

  }


  $sql=sprintf("select * from `Product Department Bridge` where `Product Key`=%d",$product->id);
  $result_a=mysql_query($sql);
  $num_deptos=0;
  $no_dep=false;
  if($row_a=mysql_fetch_array($result_a, MYSQL_ASSOC)){
    $num_deptos++;
    if($row_a['Product Department Key']==$dept_no_dept->id)
      $no_dep=true;
  }
  if($num_deptos>1 and $no_dep){
    $sql=sprintf("delete from `Product Department Bridge` where `Product Key`=%d and `Product Department Key`=%d",$product->id,$dept_no_dept->id);
    mysql_query($sql);
  }


  if($num_deptos==0){
    
   $sql=sprintf("update from `Product Dimension` set `Product Main Department Key`=%d,`Product Main Department Code`=%s,`Product Main Department Name`=%s  where `Product Key`=%d "
		 ,$dept_no_dept->id
		 ,prepare_mysql($dept_no_dept->data['Product Department Code'])
		 ,prepare_mysql($dept_no_dept->data['Product Department Name'])
		 ,$product->id);
    mysql_query($sql);
     $sql=sprintf("insert into  `Product Department Bridge` (`Product Key`,`Product Department Key`) values (%d,%d)",$product->id,$dept_no_dept->id);
    mysql_query($sql);

  }


  print $row['Product Key']."\r";




 }



?>