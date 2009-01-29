<?


error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');
include_once('../../classes/Order.php');
include_once('local_map.php');

include_once('map_order_functions.php');


require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';


$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}

$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  
$db->query("SET time_zone ='UTC'");
$db->query("SET NAMES 'utf8'");
$PEAR_Error_skiptrace = &PEAR::getStaticProperty('PEAR_Error','skiptrace');$PEAR_Error_skiptrace = true;// Fix memory leak
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');

$tmp_directory='/tmp/';

$old_mem=0;



$outstock_norecord=array('7927'=>true);
$partners=array('7927'=>true,'10'=>true);

//mb_ucwords('st hellen');
//exit;
$update_all=false;
$contador=1;
$do_refunds=false;
$correct_partner=true;
$force_update=false;


$orders_array_full_path = glob("/mnt/y/Orders/8*.xls");

if(count($orders_array_full_path)==0)
  exit;

foreach($orders_array_full_path as $key=>$order){
  $tmp=str_replace('.xls','',$order);
  $tmp=preg_replace('/.*rders\//i','',$tmp);
  $orders_array[]=$tmp;
}




$good_files=array();
$good_files_number=array();

foreach($orders_array as $order_index=>$order){
  if(preg_match('/^\d{4,5}$/i',$order)){
    $good_files[]=$orders_array_full_path[$order_index];
    $good_files_number[]=$order;


  }

}

if($do_refunds){

foreach($orders_array as $order_index=>$order){
  if(preg_match('/^\d{4,5}r$|^\d{4,5}ref$|^\d{4,5}\s?refund$|^\d{4,5}rr$|^\d{4,5}ra$|^\d{4,5}r2$|^\d{4,5}.2ref$/i',$order)){
    $parent_order_id=preg_replace('/[^\d]*$|r2$||\-2ref$/i','',$order);
    if(is_numeric($index=array_search($parent_order_id, $good_files_number))){
      array_splice($good_files_number, $index+1, 0, $order);
      array_splice($good_files, $index+1, 0, $orders_array_full_path[$order_index]);
    }else{
      for($i=$parent_order_id;$i>1;$i--){
	//	print "look for $i $order \n";
	if(is_numeric($index=array_search($i, $good_files_number))){
	  array_splice($good_files_number, $index+1, 0, $order);
	  array_splice($good_files, $index+1, 0, $orders_array_full_path[$order_index]);
	  continue 2;
	}
      }
      array_splice($good_files_number, 0, 0, $order);
      array_splice($good_files, 0, 0, $orders_array_full_path[$order_index]);
    }
  }
 }
 
 }



//include_once('z.php');




foreach($good_files_number as $order_index=>$order){
  $is_refund=false;
  $act_data=array();
  $map=array();
  if(!preg_match('/^\d{4,5}$/i',$order)){
    $is_refund=true;
  }
  $filename=$good_files[$order_index];




  $filedate=filemtime($filename);
  $sql=sprintf("select `Order Key` as order_id, `Order Original Metadata` as metadata from `Order Dimension` where `Order Original Data Source`='Excel File' and `Order Original Data`=%s",prepare_mysql($filename));



  $res = $db->query($sql); 
  if ($row=$res->fetchRow()) {
    $metadata=split('|',$row['metadata']);
    $date_read=$metadata[0];
    $checksum_read=$metadata[1];
    $checksum_header_read=$metadata[2];
    $checksum_products_read=$metadata[3];
    $order_id=$row['order_id'];
    //print "$filedate $date_read ".date("d-m-Y H:i:s",strtotime('@'.$filedate))." => ".date("d-m-Y H:i:s",strtotime('@'.$date_read))." $filename $order_id\n";
    
    if($filedate>$date_read or $force_update){
      //print "$filedate $date_read  $force_update\n";exit;
      //exit;
      if(preg_match('/mnt.r.Orders/i',$filename))
	$is_island=1;
      else
	$is_island=0;
      

      print "Updating: $filename ";

      // UPDATEING
      //	print(date("c", $filedate)."   ".date("c", $date_read)." \n");
      $random=mt_rand();
      $tmp_file=$tmp_directory.$order."_$random.xls";
      
      copy($filename, $tmp_file);// copy to local directory
      $checksum=md5_file($tmp_file);
      $csv_file=$tmp_directory.$order."_$random.csv";
      exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$tmp_file.' > '.$csv_file);

      $handle_csv = fopen($csv_file, "r");
      unlink($tmp_file);
      unlink($csv_file);
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
      //print_r($header);
      if(isset($header[3][6]) and preg_match('/refund|credit note/i',$header[3][6]) 
or isset($header[3][5]) and preg_match('/refund|credit note/i',$header[3][5]) 
){
	print "refund file\n";
	continue;
      }
      //fclose($handle_csv);
      $checksum_header= md5(serialize($header));
      $checksum_products= md5(serialize($products));

      // check if something is changed
      $header_changed=false;
      $trans_changed=false;
      

      if( $checksum_header_read!=$checksum_header or $force_update){// Header has changed
	print " *header ";
	
	list($act_data,$header_data)=read_header($header,$map_act,$y_map,$map);
	//	print_r($header_data);

	list($tipo_order,$parent_order_id)=get_tipo_order($header_data['ltipo'],$header_data);
	
	if($is_refund){
	  if($tipo_order!=9){
	    $tipo_order==10;
	    print "Error no refund title in a refund like filename\n";
	  }
	
	  $parent_order_id=preg_replace('/[^\d]|r2$||\-2ref$/i','',$order);
	  //print "$order $parent_order_id\n";
	  $tipo_order=9;
	  
	}



	if($tipo_order==9 ){
	  
	  
	  

	}elseif($tipo_order==10  or $tipo_order==11){
	  //credut note or quote

	}else{
	  // if no refund or cretit note
	  
	  list($date_index,$date_order,$date_inv)=get_dates($filedate,$header_data,$tipo_order,true);
	  if($date_order=='')
	    $date_index2=$date_index;
	  else
	    $date_index2=$date_order;
	  //=====================================================
	  // LOCAL STUFF DELETE IF APRPIPATe

	  if($filename=='/mnt/r/Orders/8837.xls'){
	    $header_data['history']=$header_data['history']+2;
	    
	  }else if($filename=='/mnt/s/Orders/12752.xls'){
	    $header_data['history']=16;
	  }
	  
	  
	  //=====================================================
	  $customer_data=setup_contact($act_data,$header_data,$date_index2);
	  print_r($customer_data);
	  exit;
	  //update Ordern

	}
	$ltipo=$header_data['ltipo'];
	
	$header_changed=true;
	$_same_header=false;
      }else{
	$_same_header=true;
	print " same header ";

      }
      

      
      if( $checksum_products_read!=$checksum_products or  $force_update){
	print " *transactions ";
	list($act_data,$header_data)=read_header($header,$map_act,$y_map,$map);
	
	list($tipo_order,$parent_order_id)=get_tipo_order($ltipo,$header_data);
	list($date_index,$date_order,$date_inv)=get_dates($filedate,$header_data,$tipo_order,true);
	
	$record_outofstock=true;
	$customer_data=get_customer_data($customer_id);
	
	  
       
	  $record_outofstock=true;
	  if(isset($outstock_norecord[$customer_id]))
	    $record_outofstock=false;

	delete_transactions($order_id);
	$transactions=read_products($products,$prod_map);
	$sql="delete from debit where tipo=2 and order_affected_id=$order_id";
	mysql_query($sql);


	$transction_data=set_transactions($transactions,$order_id,$tipo_order,$parent_order_id,$date_index,$record_outofstock,$tax_code);


      }else
	print " same transactions ";

      update_orden_files($order_id,$filename,$checksum,$checksum_header,$checksum_products,$filedate);

      print "\n";
    }


  }else{// Is a new orden
      
    print "Creating: $filename \n";

    if(preg_match('/mnt.r.Orders/i',$filename))
      $is_island=1;
    else
      $is_island=0;
      
    $random=mt_rand();
    $tmp_file=$tmp_directory.$order."_$random.xls";
      
    copy($filename, $tmp_file);// copy to local directory
    $checksum=md5_file($tmp_file);
    $csv_file=$tmp_directory.$order."_$random.csv";
    exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$tmp_file.' > '.$csv_file);
    $handle_csv = fopen($csv_file, "r");
    unlink($tmp_file);
    unlink($csv_file);
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
    fclose($handle_csv);
    // print_r($header);

    $checksum_header= md5(serialize($header));
    $checksum_products= md5(serialize($products));
    list($act_data,$header_data)=read_header($header,$map_act,$y_map,$map);
    $transactions=read_products($products,$prod_map);
    list($tipo_order,$parent_order_id)=get_tipo_order($header_data['ltipo'],$header_data);
    if($is_refund){
      if($tipo_order!=9){
	$tipo_order==10;
	print "Error no refund title in a refund like filename\n";
      }
	
      $parent_order_id=preg_replace('/[^\d]|r2$||\-2ref$/i','',$order);
      //print "$order $parent_order_id\n";
      $tipo_order=9;
	  
    }





    if($tipo_order==9){
      list($date_index,$date_order,$date_inv)=get_dates($filedate,$header_data,$tipo_order,true);
      if($date_order=='')
	$date_index2=$date_index;
      else
	$date_index2=$date_order;

      global $tax_rate;
      $total_tax=-abs($header_data['tax1']+$header_data['tax2']);
      $total_net=-abs($header_data['total_net']);
      $total=-abs($header_data['total_topay']);

      $bal_tax=$total_tax;
      $bal=$total_net;
      if(abs($total-$total_tax-$total_net)>0.001){
	print $total_tax+$total_net." $total  Error in refund tax balance\n";
      }

      if($total==0 and $total_tax!=0  ){
	$bal_tax=$total_tax;
	$bal=0;

      }

      // Get parent_order_id
      $sql="select id from orden where public_id='".addslashes($parent_order_id)."'";


      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$parent_order_id=$row['id'];
	     
	if($bal!='' and $bal_tax!='')
	  $_tax_code='S';
	elseif($bal==0 and $bal_tax!=0  ){
	  $_tax_code='X';
	}else
	   $_tax_code='';

	if( $_tax_code!='X')
	  $_bal_tax=0;
	else
	  $_bal_tax=$bal_tax;
	$sql=sprintf("insert into debit (tipo,order_affected_id,value_net,value_tax,date_done,tax_code) values (4,%s,%.2f,%.2f,%s,%s)",prepare_mysql($parent_order_id),$bal,$_bal_tax,$date_index,prepare_mysql($_tax_code));
	mysql_query($sql);
	//  $refund_id = $db->lastInsertID();
	$refund_id=mysql_insert_id();
	$sql=sprintf("insert into debit_file (filename,debit_id) values (%s,%d)",prepare_mysql($filename),$refund_id);
	mysql_query($sql);
	     
	// Calculate debits
	$debit_value_net=0;
	$debit_value_tax=0;
	$sql=sprintf("select value_net,value_tax from debit where order_affected_id=%d",$parent_order_id);
	// print "$sql\n";
	$result = mysql_query($sql) or die('Query failed:zzasa ' . mysql_error());
	while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	  $debit_value_net+=$row['value_net'];
	  $debit_value_tax+=$row['value_tax'];
	}      
	$sql=sprintf("select total,net,tax from orden where id=%d",$parent_order_id);
	//print "$sql\n";
	//exit;
	$result = mysql_query($sql) or die('Query failed:zz ' . mysql_error());
	if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	  $balance_net=$row['net']+$debit_value_net;
	  $balance_tax=$row['tax']+$debit_value_tax;
	  $balance_total=$row['total']+$debit_value_net+$debit_value_tax;
	       
	  $sql=sprintf("update orden set balance_net='%.2f' , balance_tax='%.2f' , balance_total='%.2f' where id=%d",$balance_net,$balance_tax,$balance_total,$parent_order_id);
	  //print "$sql\n";
	  mysql_query($sql);//$db->exec($sql);
	}   

      }else{
	     


	if($bal_tax!=0 and $bal!=0){
	  $_tax_code='S';
	}else
	  $_tax_code='';
	     
	     
	if($total==0 and $total_tax!=0  ){
	  $bal_tax=$total_tax;
	  $bal=0;
	  $_tax_code='X';
	}
	     
	if( $_tax_code!='X')
	  $_bal_tax=0;
	else
	  $_bal_tax=$bal_tax;




	$sql=sprintf("insert into debit (tipo,value_net,value_tax,date_done,tax_code) values (4,%.2f,%.2f,%s,%s)",$bal,$_bal_tax,$date_index,prepare_mysql($_tax_code));
	mysql_query($sql);
	// $refund_id = $db->lastInsertID();
	$refund_id =mysql_insert_id();
	$sql=sprintf("insert into debit_file (filename,debit_id) values (%s,%d)",prepare_mysql($filename),$refund_id);
	mysql_query($sql);


	     
      }
	     
	   
	    


	    
	   


      //  exit;
      //	   print "caca";
      insert_orden_files('NULL',$filename,$checksum,$checksum_header,$checksum_products,$filedate);

	

	
    }elseif($tipo_order==10 or $tipo_order==11){
      // Credit note (note we assome the thos things are inclides in the nets invoice so ignored)
      insert_orden_files('NULL',$filename,$checksum,$checksum_header,$checksum_products,$filedate);
    }else{
      // if no refund or cretit note

      list($date_index,$date_order,$date_inv)=get_dates($filedate,$header_data,$tipo_order,true);
      if($date_order=='')
	$date_index2=$date_index;
      else
	$date_index2=$date_order;


      
      //=====================================================
      // LOCAL STUFF DELETE IF APRPIPATe

      if($filename=='/mnt/r/Orders/8837.xls'){
	$header_data['history']=$header_data['history']+2;
	
      }else if($filename=='/mnt/s/Orders/12752.xls'){
	$header_data['history']=16;
      }
	

      //=====================================================
      $customer_data=setup_contact($act_data,$header_data,$date_index2);
      $data=array();
      $data['order date']=$date_order;
      $data['order customer message']=$header_data['note2'];
      $data['order original data mime type']='text/plain';
      $data['order original data'];
      print_r($header_data);
      

      exit;
      
    }
  }


}



