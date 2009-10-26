<?php
date_default_timezone_set('Europe/London');

set_include_path(get_include_path() . PATH_SEPARATOR . $path);

$path_to_liveuser_dir = '/usr/share/php/'.PATH_SEPARATOR;

ini_set('include_path', $path_to_liveuser_dir.ini_get('include_path'));

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Order.php');
include_once('local_map.php');

include_once('map_order_functions.php');


//require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';




//$db =& MDB2::factory($dsn);       
//if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('Europe/London');

  


$tmp_directory='/tmp/';

$old_mem=0;



//$outstock_norecord=array('7927'=>true);
//$partners=array('7927'=>true,'10'=>true);

//mb_ucwords('st hellen');
//exit;
$update_all=false;
$contador=1;
$do_refunds=false;
$correct_partner=true;
$force_update=false;


$orders_array_full_path = glob("/mnt/z/Orders-germany/*.xls");
$orders_array_full_path=array_reverse($orders_array_full_path);


if(count($orders_array_full_path)==0)
  exit;

foreach($orders_array_full_path as $key=>$order){
  $tmp=str_replace('.xls','',$order);
  $tmp=preg_replace('/.*rders-germany\//i','',$tmp);
  $orders_array[]=$tmp;
}




$good_files=array();
$good_files_number=array();

foreach($orders_array as $order_index=>$order){
  if(preg_match('/^DE\d{4,5}$/i',$order)){
    $good_files[]=$orders_array_full_path[$order_index];
    $good_files_number[]=$order;


  }

}


foreach($orders_array as $order_index=>$order){
  if(preg_match('/^\d{4,5}r$|^\d{4,5}ref$|^\d{4,5}\s?refund$|^\d{4,5}rr$|^\d{4,5}ra$|^\d{4,5}r2$|^\d{4,5}\-2ref$|^\d{5}rpl$|^\d{5}sht?$|^\d{5}rfn$/i',$order)){
     $good_files[]=$orders_array_full_path[$order_index];
    $good_files_number[]=$order;
  }

}




//include_once('z.php');

$cvs_repo='/data/orders_data/';


foreach($good_files_number as $order_index=>$order){

  $updated=false;

  $is_refund=false;
  $act_data=array();
  $map=array();
  if(!preg_match('/^\d{4,5}$/i',$order)){
    $is_refund=true;
  }
  $filename=$good_files[$order_index];
  //  print "$filename\n";

  $filedate=filemtime($filename);
  $filedatetime=date("Y-m-d H:i:s",strtotime('@'.$filedate));
  $just_file=preg_replace('/.*\//i','',$filename);
  $directory=preg_replace("/$just_file$/",'',$filename);
  
  $sql=sprintf("select * from de_orders_data.orders where  `filename`=%s",prepare_mysql($filename));
  $result=mysql_query($sql);
  // print "$sql\n";
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $sql=sprintf("update de_orders_data.orders set last_checked=NOW(),date=%s,timestamp=%d where id=%d",
		 prepare_mysql($filedatetime)
		 ,$filedate
		 ,$row['id']);
    mysql_query($sql);
    
    $date_read=$row['timestamp'];
    if($filedate>$date_read or $force_update){
      $random=mt_rand();
      $tmp_file=$tmp_directory.$order."_$random.xls";
      copy($filename, $tmp_file);// copy to local directory
      $checksum=md5_file($tmp_file);
      
      if($checksum!=$row['checksum'] or $force_update){
	print "Updating $filename\n";
	$csv_file=$tmp_directory.$order."_$random.csv";
	exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$tmp_file.' > '.$csv_file);
	$handle_csv = fopen($csv_file, "r");
	unlink($tmp_file);
	copy($csv_file,$row['filename_cvs'] );
	$handle_csv = fopen($csv_file, "r");
	unlink($csv_file);
	$sql=sprintf("update de_orders_data.orders set last_read=NOW() where id=%d",$row['id']);
	mysql_query($sql);
	$updated=true;
	$id =$row['id'];
      }



    }
  }else{//new
    $random=mt_rand();
    $tmp_file=$tmp_directory.$order."_$random.xls";
    copy($filename, $tmp_file);// copy to local directory
    $checksum=md5_file($tmp_file);
    $csv_file=$tmp_directory.$order."_$random.csv";
    exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$tmp_file.' > '.$csv_file);
    $handle_csv = fopen($csv_file, "r");
    unlink($tmp_file);
    print "Creating $filename\n";
    $sql=sprintf("insert into de_orders_data.orders (directory,filename,checksum,date,timestamp,last_checked,last_read) values (%s,%s,%s,%s,%s,NOW(),NOW())"
		 ,prepare_mysql($directory)
		 ,prepare_mysql($filename)
		 ,prepare_mysql($checksum)
		 ,prepare_mysql($filedatetime)
		 ,prepare_mysql($filedate)
		 );
    mysql_query($sql);
    $id =mysql_insert_id();
    

     $sql=sprintf("insert into de_orders_data.data (id) values (%d)"
		  ,$id
		 );
    mysql_query($sql);



    $cvs_filename=sprintf("%06d.csv",$id);
    copy($csv_file,$cvs_repo.$cvs_filename );
    $handle_csv = fopen($csv_file, "r");
    unlink($csv_file);
    
    $sql=sprintf("update de_orders_data.orders set filename_cvs=%s where id=%d",prepare_mysql($cvs_repo.$cvs_filename),$id);
    mysql_query($sql);
    $updated=true;
    
  }

  
  if($updated){

    $map_act=$_map_act;
      $map=$_map;
      $y_map=$_y_map;
      if($order<18803){// Change map if the orders are old
	$y_map=$_y_map_old;
	foreach($_map_old as $key=>$value)
	  $map[$key]=$value;
      }
      $prod_map=$y_map;
      if($order==53378){
	$prod_map['no_price_bonus']=true;
	$prod_map['no_reorder']=true;
	$prod_map['bonus']=11;
      }

      list($header,$products )=read_records($handle_csv,$prod_map,$number_header_rows);
      $_header=serialize($header);
      $_products=serialize($products);
      $checksum_header= md5($_header);
      $checksum_products= md5($_products);
      // print "Updating  $filename\n";
      $sql=sprintf("update de_orders_data.order_data set checksum_header=%s,checksum_prod=%s where id=%d"
		   ,prepare_mysql($checksum_header)
		   ,prepare_mysql($checksum_products)
		   ,$id);
      mysql_query($sql);
      $sql=sprintf("update de_orders_data.data set header=%s ,products=%s  where id=%d"
		   
		   ,prepare_mysql(mb_convert_encoding($_header, "UTF-8", "ISO-8859-1,UTF-8"))
		   ,prepare_mysql(mb_convert_encoding($_products, "UTF-8", "ISO-8859-1,UTF-8"))
		   ,$id);
      mysql_query($sql);
      




  }

}






