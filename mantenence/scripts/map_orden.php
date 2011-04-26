<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

//require_once '/home/raul/www/inikoo/dns/dns.php';         // DB connecton configuration file
$dns_pwd='ajolote1';
$dns_db='ci_1';
$dns_user='root';
$dsn = 'mysql://'.$dns_user.':'.$dns_pwd.'@localhost/'.$dns_db;


require_once 'MDB2.php';            // PEAR Database Abstraction Layer
$db =& MDB2::singleton($dsn);  
  

$debug=false;





define('DEFAULT_COUNTRY_ID',  47);
define('UNKOWN_COUNTRY_ID',  244);
//$link = mysql_connect('localhost', 'root', 'ajolote1')
//  or die('Could not connect: ' . mysql_error());
//mysql_select_db('aw') or die('Could not select database');
//include_once('Address.php');
$update_all=false;
//$xls_dir="/mnt/y/Orders/";
//$xls_dir="orders/";
//$xls_dir="tmp/";
$xls_dir="/media/sda3/share/p0/";
if ($handle= opendir($xls_dir))
  {
    while (false !== ($ofile=readdir($handle)))
      {
	
	$file=$xls_dir.$ofile;
	$ofile=str_replace(".xls","",$ofile);
	if(!preg_match('/^6[0-9]{7}$/',$ofile)    ){
	  continue;
	}
	$_date_file_mod =filectime($file);
	$_date_file_cre =filemtime($file);
	// check if we have an invoice with the same name
	//	print "$file\n";
	$sql="select  id from orden where original_file='$file'";
	$res=mysql_query($sql);
	if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	  // We have already read it
	  $order_id=$row['id'];
	  // Check if it have beeen saved after last tome we read it
	  $sql="select  file_checksum as checksum, file_date,UNIX_TIMESTAMP( file_date ) as date from orden where id=".$order_id;

	  $res2 = mysql_query($sql); 
	  if ($row2=$res2->fetchRow()){
	    $old_filedate=$row2['file_date'];
	    $new_filedate=date("Y-m-d H:i:s",strtotime('@'.$_date_file_cre));
	    
	    if($_date_file_cre>$row2['date']){
	       $old_checksum=$row2['checksum'];
	       $new_checksum=md5_file($file);
	       if($old_checksum!=$new_checksum){
		// print " $old_checksum   $new_checksum  "; 
		 print "updating order".$ofile."\n";
		 //exit;
		   readorder($file,$ofile,true,$new_checksum,$new_filedate, date("H:i:s",$_date_file_cre),  date("Y-m-d",$_date_file_cre),  date("H:i:s",$_date_file_cre-1800));

	       }

	    }
	    

	    
	  }else
	    exit("Error");
	  
	}else{
	  print "creating order ".$ofile."\n";
	  $new_filedate=date("Y-m-d H:i:s",strtotime('@'.$_date_file_cre));
	  $new_checksum=md5_file($file);

	  readorder($file,$ofile,false,$new_checksum,$new_filedate, date("H:i:s",$_date_file_cre),  date("Y-m-d",$_date_file_cre),  date("H:i:s",$_date_file_cre-1800));
	}
	
	
      }
  }





// 	$new_checksum=md5_file($file);
// 	if($debug)
// 	  print date("Y-m-d H:i:s",strtotime('@'.$_date_file_mod)) ."   $new_checksum\n";
// 	$sql="select  file_checksum from orden where public_id='$ofile'";
// 	//	$result = mysql_query($sql) or die($sql.' Query failed: ' . mysql_error() ."\n");
// 	$res=mysql_query($sql);
// 	if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
// 	  $old_checksum=$row['file_checksum'];
// 	  if($new_checksum!=$old_checksum)
// 	    readorder($file,$ofile,true,$new_checksum, date("H:i:s",$_date_file_mod),  date("Y-m-d",$_date_file_mod),  date("H:i:s",$_date_file_mod-1800));
// 	}else
// 	  readorder($file,$ofile,false,$new_checksum, date("H:i:s",$_date_file_cre),  date("Y-m-d",$_date_file_cre),  date("H:i:s",$_date_file_cre-1800));
//       }
//   }

function readorder($file,$ofile,$update,$checksum,$datetime_updated='',$time_updated='',$date_updated='',$time_updated_menos30min=''){


  $db =& MDB2::singleton();
  global $debug;




  if(preg_match('/^[0-9]{8}$/',$ofile)  ){
    if($update){
      $sql="select id,titulo,date(date_processed) as dord,date(date_invoiced) as dinv  from orden where public_id=$ofile";
      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$old_order_id=$row['id'];
	$old_titulo=$row['titulo'];
	$old_fdate_inv=$row['dinv'];
	$old_fdate_order=$row['dord'];
	$sql="delete from  transaction  where order_id=$old_order_id";
	//	print "$sql\n";
	$result = mysql_query($sql);
	$sql="delete from  todo_transaction  where order_id=$old_order_id";
	//print "$sql\n";
	$result = mysql_query($sql);
	$sql="delete from  bonus  where order_id=$old_order_id";
	//print "$sql\n";
	$result = mysql_query($sql);
	$sql="delete from  outstock  where order_id=$old_order_id";
	//print "$sql\n";
	$result = mysql_query($sql);
	$sql="delete from  pick  where invoice_id=$old_order_id";
	//print "$sql\n";
	$result = mysql_query($sql);
	$sql="delete from  pack where invoice_id=$old_order_id";
	//print "$sql\n";
	$result = mysql_query($sql);
      }
    }
    

    //$file='p08bis/60004499.xls';
    $date_file_c =date("Y-m-d H:i:s",filectime($file));
    $date_file_a =date("Y-m-d H:i:s",fileatime($file));
    $date_file_m =date("Y-m-d H:i:s",filemtime($file));

    exec('/usr/local/bin/xls2csv   '.$file.' > /tmp/tmp.csv');

    $handle_csv1 = fopen("/tmp/tmp.csv", "r");
    $handle_csv2 = fopen("/tmp/tmp.csv", "r");
    $handle_csv3 = fopen("/tmp/tmp.csv", "r");
    $i=0;
    $my_total_net=0;
    $my_total_rrp=0;
    $my_total_items_order=0;
    $my_total_items_reorder=0;
    $my_total_items_bonus=0;
    $my_total_items_free=0;
    $my_total_items_dispatched=0;

    $country='';
    $cust_date1='';
    $cust_date2='';


	  
    $datos =array();
    $i=0;

    $t_data='';
    while(($cols = fgetcsv($handle_csv1))!== false){

      if($i>19){
	if($cols[0]=='Public' or    $cols[0]=='nic'  ){

	  $c_country=$cols[10];
	  if(preg_match('/^Espa/i',$c_country)   and preg_match('/a$/i',$c_country)   and strlen($c_country)==7  ){
	    $c_country="Spain";
	    
	  }
	  //  print_r($cols);
	  // exit;
	  $c_name=$cols[2];
	  $c_contact=$cols[3];
	  $c_fname=$cols[17];
	  $c_a1=$cols[4];
	  $c_a2=$cols[5];
	  $c_a3=$cols[6];
	  $c_town=$cols[7];
	  $c_district=$cols[8];
	  $c_postcode=$cols[9];
	  $c_tel=$cols[12];
	  $c_fax=$cols[13];
	  $c_mobile=$cols[15];
	  $c_source=$cols[25];
	  $c_act=$cols[38];
	  $c_email=$cols[count($cols)-1];
	  break;
	}
	$t_data.=join('|',$cols);
      }else{
	$j=0;
	foreach($cols as $col){
	  $datos[$i][$j]=$col;
	  $j++;
	}
	$i++;
      }
    }




    $t_checksum=md5($t_data);

//     $y_code=3;
//     $y_description=6;
//     $y_price=7;
//     $y_order=8;
//     $y_reorder=9;
//     $y_bonus=11;
//     $y_rrp=16;
//     $y_discount=18;
//     $y_units=5;
  
    $y_code=5;
    $y_description=8;
    $y_price=9;

    $y_order=10;
    $y_reorder=11;
    $y_bonus=12;
    $y_credit=16;
    $y_rrp=18;
    $y_discount=20;


    $y_units=7;
  




    $stipo=$datos[2][0];//+
    $ltipo=$datos[2][8];
    $pickedby=$datos[2][16];
    $parcels=$datos[2][20];
    $packedby=$datos[3][16];
    $weight=$datos[3][20];

    $trade_name=$datos[6][8];
    $takenby=$datos[6][9];
    $customer_num=$datos[6][10];
    $order_num=$datos[6][13];
    // fix coomon error
    $order_num=preg_replace('/^6/','',$order_num);
    $order_num=60000000+$order_num;


    $date_order=$datos[6][16];
    $date_inv=$datos[6][18];

    $pay_method=$datos[7][4];
    $address1=$datos[7][8];

    $history=$datos[8][4];
    $address2=$datos[8][8];
    $notes=$datos[8][10];
    $total_net=$datos[8][20];

    $gold=$datos[9][4];
    $address3=$datos[9][6];
    $charges=$datos[9][16];
    $tax1=$datos[9][20];
    
    $city=$datos[10][8];
    $tax2=$datos[10][20];
   
    $postcode=$datos[11][8];
    $tax_number=$datos[11][10];
    $total_topay=$datos[11][20];

    $shipping=$datos[12][16];

    $customer_contact=$datos[14][8];
    $phone=$datos[15][8];


    $total_order=$datos[15][$y_order];
    $total_reorder=$datos[15][$y_reorder];
    $total_bonus=$datos[15][$y_bonus];
    $total_items_charge_value=$datos[15][16];
    $total_rrp=$datos[15][18];




    if(!isset($c_name)){
      $c_name=$trade_name;
      $c_fname='';
      $c_contact=$customer_contact;
      $c_a1=$address1;
      $c_a2=$address2;
      $c_a3=$address3;
      $c_town=$city;
      $c_district='';
      $c_postcode=$postcode;
      $c_country='';
      $c_tel='';
      $c_fax='';
      $c_mobile='';
      $c_email='';
    }

    // GET customer_id;
    if($c_country=='')
      $c_country='Spain';
    
    $sql2="select country.id,name, alias from country left join country_alias on (country.code=country_alias.code) where alias='$c_country' or country.name='$c_country' group by country.id ";
    
    $result2 = mysql_query($sql2) or die('Query faileda2: ' . mysql_error());
    $matches=array();
    if ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
      $country_id=$row2['id'];
      $country=$row2['name'];
    }else{
      $country_id=244;
      $country=$c_country;
	  
    }

  if(!$update){

    $_SESSION['new_contact']=array();
	
    if($c_name==$c_contact){
      $name_array=prepare_name($c_name,$c_fname);
      
      $_SESSION['new_contact']['tipo']=array_shift($name_array);
      $_SESSION['new_contact']['name']=$name_array;
      $tel_tipo=1;
    }else{
      $_SESSION['new_contact']['tipo']=0;
      $_SESSION['new_contact']['name']=array('','','','',$c_name,$c_name,'','');
      if($c_contact!=''){
	$a_contact= prepare_name($c_contact,$c_fname);
	$_SESSION['new_contact']['contact']=$a_contact;
	$tel_tipo=1;
      }else{
	$tel_tipo=0;
      }
    }

    
    $full_address=($c_a1!=''?$c_a1."\n":'').($c_a2!=''?$c_a2."\n":'').($c_a3!=''?$c_a3."\n":'').($c_town!=''?$c_town."\n":'').($c_district!=''?$c_district."\n":'').($c_postcode!=''?$c_postcode."\n":'')."$country\n";
    $a_address=array(1,$c_a1,$c_a2,$c_a3,$c_town,$c_district,$c_postcode,$country,$country_id,$full_address,1);
    $_SESSION['new_contact']['address'][]=$a_address;
    

    // Check if front address is different and interpret as a different delivery addres 
    if($c_a1!=$address1 or $postcode!=$c_postcode or $c_town!=$city){
      $full_address=($address1!=''?$address1."\n":'').($address2!=''?$address2."\n":'').($address3!=''?$address3."\n":'').($city!=''?$city."\n":'').($c_postcode!=''?$c_postcode."\n":'')."$country\n";
      $a_address=array(2,$address1,$address2,$address3,$city,'',$postcode,$country,$country_id,$full_address,0);
      $_SESSION['new_contact']['address'][]=$a_address;
    }
    

	
	
	
    $sql='select tel_code from country where id='.$country_id;
	
    $res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
    if($data=$res->fetchRow()) {
      $tel_code=$data['tel_code'];
    }else
      exit('Error writting country Id');
	

	
    if($c_tel!=''){
      $c_tel=preg_replace('/\[.*\]/','',$c_tel);
      $c_tel=preg_replace('/[^0-9]/','',$c_tel);
      $c_tel=preg_replace('/^'.$tel_code.'/','',$c_tel);
      if($country_id==30){// If is uk delete first zero
	$c_tel=preg_replace('/^0/','',$c_tel);
      }
	  
      $atel=array($tel_tipo,'',$tel_code,$c_tel,'');
      $_SESSION['new_contact']['tel'][]=$atel;
    }


      
    if($c_mobile!=''){
      $c_mobile=preg_replace('/\[.*\]/','',$c_mobile);
      $c_mobile=preg_replace('/[^0-9]/','',$c_mobile);
      $c_mobile=preg_replace('/^'.$tel_code.'/','',$c_mobile);
      if($country_id==30){// If is uk delete first zero
	$c_mobile=preg_replace('/^0/','',$c_mobile);
      }
      $atel=array(3,'',$tel_code,$c_mobile,'');
      $_SESSION['new_contact']['tel'][]=$atel;
    }
	
	
    if($c_fax!=''){
      $c_fax=preg_replace('/\[.*\]/','',$c_fax);
      $c_fax=preg_replace('/[^0-9]/','',$c_fax);
      $c_fax=preg_replace('/^'.$tel_code.'/','',$c_fax);
      if($country_id==30){// If is uk delete first zero
	$c_fax=preg_replace('/^0/','',$c_fax);
      }
	    

      $atel=array(4,'',$tel_code,$c_fax,'');
      $_SESSION['new_contact']['tel'][]=$atel;
    }




    if($c_email!=''){
      $aemail=array(0,'',$c_email);
      $_SESSION['new_contact']['email'][]=$aemail;
	  
    }




    $filename_res = 'result.txt';
    if (!$res_handle = fopen($filename_res, 'a')) {
      echo "Cannot open file ($filename)";
      exit;
    }	
    

    $matches=get_matches($c_email,$c_mobile,$c_tel,$c_fax,$c_name,$c_contact,$c_a1,$c_a2,$c_a3,$c_town,$c_district,$c_postcode,$country);
    
    $archive='';
    $archive.=sprintf( "+++++++++++++++++++++++++++++++++++++++++++++++\n%s\n",$file);

    if(count($matches)>0){
      foreach ($matches as $m)
	$archive.=sprintf(" %s %s\n",$m['contact'],$m['score']);
    }

    //print $archive;
    if (fwrite($res_handle, $archive) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }
    fclose($res_handle);


      
    if(count($matches)==0  or (   count($matches)>0 and $matches[0]['score']<100        ) ){


      if($c_fax!=''){
	$atel=array(4,'','',$c_fax,'');
	$_SESSION['new_contact']['tel'][]=$atel;
      }
	  

      //print_r(	$_SESSION['new_contact']);
	  
      $contact_id=savecontact();
      //print "New";
    }else{
      $contact_id=$matches[0]['contact'];
      
    }
    

    }else{
      //get the address if updated

    $_SESSION['new_contact']=array();
    

    $full_address=($c_a1!=''?$c_a1."\n":'').($c_a2!=''?$c_a2."\n":'').($c_a3!=''?$c_a3."\n":'').($c_town!=''?$c_town."\n":'').($c_district!=''?$c_district."\n":'').($c_postcode!=''?$c_postcode."\n":'')."$country\n";
    $a_address=array(1,$c_a1,$c_a2,$c_a3,$c_town,$c_district,$c_postcode,$country,$country_id,$full_address,1);
    $_SESSION['new_contact']['address'][]=$a_address;
    
    
    // Check if front address is different and interpret as a different delivery addres 
    if($c_a1!=$address1 or $postcode!=$c_postcode or $c_town!=$city){
      $full_address=($address1!=''?$address1."\n":'').($address2!=''?$address2."\n":'').($address3!=''?$address3."\n":'').($city!=''?$city."\n":'').($c_postcode!=''?$c_postcode."\n":'')."$country\n";
      $a_address=array(2,$address1,$address2,$address3,$city,'',$postcode,$country,$country_id,$full_address,0);
      $_SESSION['new_contact']['address'][]=$a_address;
    }
    



    }
	  

    if($total_items_charge_value=='')
      $total_items_charge_value=0;
    if($tax1=='')
      $tax1=0;
    if($tax2=='')
      $tax2=0;
    if($charges=='')
      $charges=0;
    if($shipping=='')
      $shipping=0;
    if($total_net=='')
      $total_net=0;
    if($total_topay=='')
      $total_topay=0;
    if($total_rrp=='')
      $total_rrp=0;
    if($tax_number=='')
      $tax_number='NULL';
    else
      $tax_number="'".$tax_number."'";

//     $sql=sprintf("select contact.id as id from contact left join contact_relations on (contact.id=child_id) where  isnull(parent_id) and old_id=%d ",$customer_num);
//     $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
//     $num_rows = mysql_num_rows($result);
//     if($num_rows==1){
//       $row = mysql_fetch_array($result, MYSQL_ASSOC);
      
//       $sqlxx=sprintf("select id from customer where contact_id=%d ",$row['id']);
//       $resultxx = mysql_query($sqlxx) or die('Query failed: ' . mysql_error());
//       if ($rowxx = mysql_fetch_array($resultxx, MYSQL_ASSOC)) {
// 	$customer_id=$rowxx['id'];
//       }else{
// 	$sql=sprintf("insert into customer (contact_id) value (%d)",$row['id']);
// 	mysql_query($sql);
// 	$customer_id=mysql_insert_id();
//       }      
//     }else
//       $customer_id=0;
    



    // Get the type of orden

    $parent_id='null';

    if(preg_match('/pro-forma/i',$ltipo)  or preg_match('/nota de envio/i',$ltipo)){
      $tipo=1;
    }elseif(preg_match('/factura/i',$ltipo)){
      $tipo=2;
    }elseif(preg_match('/cancel/i',$ltipo)){
      $tipo=4;
    }elseif(preg_match('/^devoluci/i',$ltipo)){
      $tipo=8;
    }elseif(preg_match('/^abono por/i',$ltipo)){
      $tipo=3;
    }elseif(preg_match('/muestra/i',$ltipo)){
      $tipo=7;
    }

    elseif(preg_match('/^reemplazo por roturas/i',$ltipo)){
      $tipo=5;
      if($tmp=preg_match('/\d{5}/i',$ltipo))
	$parent_id=$tmp;
    }elseif(preg_match('/^reemplazo por falta/i',$ltipo)){
      $tipo=6;
      if($tmp=preg_match('/\d{5}/i',$ltipo))
	$parent_id=$tmp;
    }else{ 
      
      print "**************** $ltipo\n";
      exit;
    }
       
    //COSTA fix
    
    if($tipo==2 and ($date_inv=='' and $date_order!='')){
      $date_inv=$date_order;

    }
    

    $fdate_order=date("Y-m-d",mktime(0, 0, 0, 1 , $date_order-1, 1900));
    $fdate_inv=date("Y-m-d",mktime(0, 0, 0, 1 , $date_inv-1, 1900));
    $ftime_order=date("H:i:s",mktime(0, 0, 0, 1 , $date_order-1, 1900));
    $ftime_inv=date("H:i:s",mktime(0, 0, 0, 1 , $date_inv-1, 1900));






    // CREATES PUT TIME
    if($update){
      if($ltipo!=$old_titulo  ){// cambio de tipo de facira actualizar loas datos
	if($tipo==2){



	  if($date_updated ==$fdate_inv)
	    $date_charged=$date_updated." ".$time_updated;
	  else
	    $date_charged=$fdate_inv." 09:00:00";
	  $sql="update orden set date_index='$date_charged' ,date_invoiced='$date_charged' where id=$old_order_id";
	  mysql_query($sql);
	  print "$sql\n";
	}else{
	  $date_charged="NULL";
	  if($date_updated ==$fdate_order)
	    $date_processed=$date_updated." ".$time_updated;
	  else
	    $date_processed=$fdate_order." 08:30:00";
	  $sql="update orden set date_invoiced=NULL where id=$old_order_id";
	  print "$sql\n";
	  $sql="update orden set date_index='$date_processed', date_processed='$date_processed' where id=$old_order_id";
	  mysql_query($sql);
	  print "$sql\n";
	}
      }elseif($tipo==2 and  $fdate_inv!=$old_fdate_inv ){// id is facura and date invoice changed
	if($date_updated ==$fdate_inv)
	  $date_charged=$date_updated." ".$time_updated;
	else
	  $date_charged=$fdate_inv." 09:00:00";
	$sql="update orden set  date_index='$date_charged' ,date_invoiced='$date_charged' where id=$old_order_id";
	print " c1 $sql\n";
	mysql_query($sql);
      }elseif(preg_match('/^INVOICE$/i',$ltipo) and  $fdate_order!=$old_fdate_order ){// id is facura and date invoice changed
	if($date_updated ==$fdate_order)
	  $date_processed=$date_updated." ".$time_updated;
	else
	  $date_processed=$fdate_order." 09:00:00";
	$sql="update orden set  date_index='$date_processed',date_processed='$date_processed' where id=$old_order_id";
	print "c2 $sql\n";
	mysql_query($sql);
      }elseif( $tipo!=2 and  $fdate_order!=$old_fdate_order ){// id is facura and date invoice changed
	if($date_updated ==$fdate_order)
	  $date_processed=$date_updated." ".$time_updated;
	else
	  $date_processed=$fdate_order." 09:00:00";

	$sql="update orden set  date_index='$date_processed',date_processed='$date_processed' where id=$old_order_id";
	print "c3 $sql\n";
	mysql_query($sql);
      }
    }
    else{//Created
      //      print "$stipo xxxxxxxxxxxxxxxxxxxxxxxxxx   $ltipo\n";
      if($tipo==2){
	//	print "$date_updated  $fdate_inv  \n";
	if($date_updated ==$fdate_inv){
	  $date_charged="'".$date_updated." ".$time_updated."'";
	  if($fdate_inv==$fdate_order)
	    $date_processed=$fdate_order." ".$time_updated_menos30min."'";
	  $date_processed="'".$fdate_order." 08:30:00'";
	}else{
	  $date_charged="'".$fdate_inv." 09:00:00'";
	  $date_processed="'".$fdate_order." 08:30:00'";
	}
	$date_index=$date_charged;
      }else{
	$date_charged="NULL";
	if($date_updated ==$fdate_order)
	  $date_processed="'".$date_updated." ".$time_updated."'";
	else
	  $date_processed="'".$fdate_order." 08:30:00'";
	$date_index=$date_processed;
      }
    }


//     if($address1==0) $address1='';
//     if($address2==0) $address2='';
//     if($address3==0) $address3='';
//     $add= new Address($address1,$address2,$address3,'','','',$city,$postcode,$country,'y');
//     $add->validate_address();
//     $add->create_db_record(false,0,0,0,0,2);
//     $address_id=$add->id;

    


    if(is_numeric(addslashes($weight)))
      $_weight=addslashes($weight);
    else
      $_weight='NULL';
    $_gold=(addslashes($gold)=='Gold Reward'?'y':'n');
    $a_taken=get_user_id($takenby,addslashes($order_num),'taken');
    if($a_taken[0]>0)
      $_taken=$a_taken[0];
    else
      $_taken='NULL';
    // Check id have the same thing!!!!!!!!
    if(!is_numeric($parcels))
      $parcels='NULL';

    $aadd=$_SESSION['new_contact']['address'][0];
    $address_bill=($aadd[9]!=''?addslashes($aadd[9]):'null');
    $address_bill="'".$address_bill."'";
    $address_del='NULL';
    
    if(isset($_SESSION['new_contact']['address'][1])){
      $aadd=$_SESSION['new_contact']['address'][1];
      $address_del=($aadd[9]!=''?addslashes($aadd[9]):'null');
      $address_del="'".$address_del."'";
      

    }
    
    if(!$update){
      
      $sql="select id from customer where contact_id=$contact_id";
      $res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
      if($data=$res->fetchRow()) {
	$customer_id=$data['id'];
      }else{
	$sql="insert into customer (contact_id) values ($contact_id)";
	mysql_query($sql);
	$customer_id = $db->lastInsertID();
	
      }
    }



    if($update){
      $sql=sprintf("update  orden set  file_checksum='%s',customer_name='%s',contact_name='%s',customer_id2='%s',customer_id3=%s,tel='%s',public_id='%s',parcels=%s,weight=%s,note='%s',order_hist='%s',gold='%s',taken_by=%s,items_charge=%d,vat=%.2f,vat2=%.2f,charges=%.2f,shipping=%.2f,net=%.2f,total=%.2f,charged_by='%s',udate=NOW(),date1='%s',date2='%s',titulo='%s',original_file='%s',address_bill=%s,address_del=%s,tipo=%s  where id=%d"
		   ,$checksum,addslashes($trade_name),addslashes($customer_contact),addslashes($customer_num),$tax_number,addslashes($phone),addslashes($order_num),addslashes($parcels),$_weight
		   ,addslashes($notes),addslashes($history),$_gold,$_taken,
		   $total_items_charge_value,$tax1,$tax2,$charges,$shipping,$total_net,$total_topay,addslashes($pay_method),$date_file_c,$date_file_a,addslashes($ltipo),addslashes($file),$address_bill,$address_del,$tipo,$old_order_id
		   );

      $order_id=$old_order_id;
      mysql_query($sql);
      //print "$sql\n";
    }else{
      $sql=sprintf("insert into orden (file_date,file_checksum,customer_name,contact_name,customer_id2,customer_id3,tel,public_id,parcels,weight,note,order_hist,gold,taken_by,items_charge,vat,vat2,charges,shipping,net,total,charged_by,date_creation,date_processed,date_invoiced,udate,date1,date2,date3,titulo,original_file,customer_id,address_bill,address_del,tipo,date_index) values
('%s','%s','%s','%s','%s',%s,'%s','%s',%s,%s,'%s','%s','%s',%s  ,%.2f,%.2f,%.2f,%.2f,%.2f,%.2f ,%.2f,'%s',%s,%s,%s,NOW(),'%s','%s','%s','%s','%s',%d,%s,%s,%s,%s)
"
		   ,$datetime_updated,$checksum,addslashes($trade_name),addslashes($customer_contact),addslashes($customer_num),$tax_number,addslashes($phone),addslashes($order_num),addslashes($parcels),$_weight
		   ,addslashes($notes),addslashes($history),$_gold,$_taken,
		   $total_items_charge_value,$tax1,$tax2,$charges,$shipping,$total_net,$total_topay,addslashes($pay_method),$date_processed,$date_processed,$date_charged,$date_file_c,$date_file_a,$date_file_m,addslashes($ltipo),addslashes($file),$customer_id,$address_bill,$address_del,$tipo,$date_index
		   );
     
      mysql_query($sql);

      if($debug)print "$sql\n";
      $order_id=mysql_insert_id();
      if(!is_numeric($order_id))
	exit("error at inserting order");
    }


    $sql="select customer_id from orden where id=$order_id";
    $res2 = mysql_query($sql); if (PEAR::isError($res2) and DEBUG ){die($res2->getMessage());}
    if ($row2 = $res2->fetchRow() ) {
      update_customer_data($row2['customer_id']);
    }

    $a_picked=get_user_id($pickedby,addslashes($order_num),'picked');
    foreach($a_picked as $_picked){
      if($_picked>0){
	$sql="insert into pick (invoice_id,picker_id) values ($order_id,$_picked)";
	mysql_query($sql);
      }
    }
    $a_packed=get_user_id($packedby,addslashes($order_num),'packed');
    foreach($a_packed as $_packed){
      if($_packed>0){
	$sql="insert into pack (invoice_id,picker_id) values ($order_id,$_packed)";
	mysql_query($sql);
      }
    }

    while(($col = fgetcsv($handle_csv2))!== false){
      if(count($col)>20){

	//print_r($col);

	if( $col[$y_description]=='Lovers Crystal Ball & Stand 50mm'){

	  $col[$y_code]='Y0055-50';

	}
	if(
	   (
	   $col[$y_code]!=''
	   and (is_numeric($col[$y_credit]) or $col[$y_discount]==1   )
	   and $col[$y_description]!='' 
	   and (is_numeric($col[$y_price]) or $col[$y_price]==''  ) 
	   and (  ( is_numeric($col[$y_order])   and  $col[$y_order]!=0   )   or ( is_numeric($col[$y_reorder])   and  $col[$y_reorder]!=0   )  or ( is_numeric($col[$y_bonus])   and  $col[$y_bonus]!=0   ) )  
	    )or (preg_match('/credito/i',$col[$y_code])   and  $col[$y_price]!='' and  $col[$y_price]!=0  )
	   

 ){
	  
	  
	  
	  if($col[$y_order]=='')
	    $col[$y_order]=0;
	  if($col[$y_reorder]=='')
	    $col[$y_reorder]=0;
	  if($col[$y_bonus]=='')
	    $col[$y_bonus]=0;
	  
	  
	  
	  if($col[$y_discount]=='')
	    $col[$y_discount]=0;
	  
	  $my_items_to_charge=$col[$y_order]-$col[$y_reorder];
	  


	  
	  $my_items_to_charge_value=$my_items_to_charge*($col[$y_price] * (1-$col[$y_discount]));

	  $my_items_to_dispach=$my_items_to_charge+$col[$y_bonus];
	  
	   if(preg_match('/credito/i',$col[$y_code])){
//	    $col[$y_credit]=-abs( $col[$y_credit]);
	    $credit_parent=$col[6];
	    $my_items_to_charge_value=$col[$y_credit];
	  }

	  $my_total_rrp+=$my_items_to_charge*($col[$y_rrp]*$col[$y_units]);
	  $my_total_net+=$my_items_to_charge_value;
	  //	  print $col[$y_code]." caca $my_total_net =$my_items_to_charge_value \n ";
	  $my_total_items_order+=$col[$y_order];
	  $my_total_items_reorder=$col[$y_reorder];
	  $my_total_items_bonus+=$col[$y_bonus];
	  $my_total_items_dispatched+=$my_items_to_dispach;

	  if($col[$y_discount]==1)
	    $my_total_items_free+=$my_total_items_dispatched;
	  $tipo_t=1;
	  if($col[$y_discount]==1)
	    $tipo_t=2;
	  $sql=sprintf("select id from product where code='%s'",addslashes($col[$y_code]));
	  $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
	  // print $col[$y_code];
	  if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	    $product_id=$row['id'];
	    $sql=sprintf("insert into transaction (tipo,order_id,product_id,ordered,dispatched,discount,charge) value (%d,%d,%d,%.2f,%.2f,%.2f,%.2f)",$tipo_t,$order_id,$product_id,$col[$y_order],$my_items_to_dispach,$col[$y_discount],$my_items_to_charge_value);
	    //if($debug)print"$sql\n";
	    mysql_query($sql);
	    if($col[$y_reorder]>0){
	      $sql=sprintf("insert into outofstock (order_id,product_id,qty) value (%d,%d,%.2f)",$order_id,$product_id,$col[$y_reorder]);
	      mysql_query($sql);
	    }
	    if($col[$y_reorder]>0  or $col[$y_discount]==1) {
	      $qty=$col[$y_reorder];
	      if($col[$y_discount]==1)
		$qty+=$my_items_to_charge;
	      $sql=sprintf("insert into bonus (order_id,product_id,qty) value (%d,%d,%.2f)",$order_id,$product_id,$qty);
	      mysql_query($sql);
	    }
	  }elseif(preg_match('/credito/i',$col[$y_code])){
	    //print_r($col);
	   // print "$y_price \n";
	    //exit("abono en codigo");
	    $parent='';
	    $parent_id='NULL';
	    $tipo=0;
	    
	    $parent_note=$col[$y_description];
	    
	    if(preg_match('/^abono debido por factura no\.:/i',$parent_note)){
	      $tipo=1;
	      if(preg_match('/[0-9]{8}/',$parent_note,$thismatch))
	      {
		$parent=$thismatch[0];
	      }

	    }

	    
	    $sql=sprintf("insert into credit (tipo,order_id,parent_id,parent,note,value) value (%d,%d,%s,%s,%s,%.2f)",$tipo,$order_id,$parent_id,($parent==''?'NULL':"'".$parent."'"),($parent_note==''?'NULL':"'".$parent_note."'"),$col[$y_price]);
	    mysql_query($sql);
//	     print "$sql\n";

	  }else{
	    $sql=sprintf("insert into todo_transaction (code,description,order_id,ordered,reorder,bonus,price,discount) value ('%s','%s',%d,  %.2f,%.2f,%.2f,%.2f,%.2f)",addslashes($col[$y_code]),addslashes($col[$y_description]),$order_id,$col[$y_order],$col[$y_reorder],$col[$y_bonus],$col[$y_price],$col[$y_discount]);
	    mysql_query($sql);
	  }
	}
	


	$i++;
      }
    }
    //printf ("dp: %s dc: %s \n",$date_processed,$date_charged);
    //printf ("total to pay: %.2f %.2f \n",$my_total_net,$total_items_charge_value);
    //printf ("total rrp   : %.2f %.2f \n",$my_total_rrp,$total_rrp);
    if(  abs($my_total_net-$total_items_charge_value)>0.02   ){
      printf( "ERROR en net:  %.2f %.2f \n  ",$my_total_net,$total_items_charge_value);
      $sql="insert into  orden_error (order_id) values ('$order_id')";
      mysql_query($sql);
    }else{
      if($update){
	$sql="delete  from  orden_error where order_id='$order_id'";
	//print "$sql\n";
	mysql_query($sql);
      }
    }
    if($_packed>0){
      $sql="insert into pack (invoice_id,packed_id) values ($order_id,$_packed)";
      mysql_query($sql);
    }
  }
}
function get_user_id($oname,$order_id,$tipo,$record=true){
  $ids=array();
  $_names=array();
  
  $_names=preg_split('/[\+\&,]+/',strtolower($oname));
  
  foreach($_names as $_name){    
    
    $_name=trim(trim(trim($_name)));
    if($_name=='michell' or $_name=='michele')
      $_name='michelle';
    
    $sql=sprintf("select id from associates where alias='$_name'");
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $id=$row['id'];
    }else
      $id=0;
    if($record){
      if($id==0 and $_name!='')
	{
	  $sql=sprintf("insert into todo_users (name,order_name,tipo) values ('%s','%s','%s')",addslashes($_name),$order_id,$tipo);
	  mysql_query($sql);
	}
    }
    $ids[]=$id;

  }
  return $ids;
}



function checksimilar($tipo,$value,$value1='',$value2=''){
  global $debug;
  switch($tipo){
  case('tel'):
    if($value==''){
      $a=array();
      return $a;
    }
    $value=preg_replace('/\s/','',$value);
    $sql2="select contact_id,parent_id  from telecom  left join contact_relations on (contact_id=child_id)  where (tipo=0 or tipo=1) and number like '%".addslashes($value)."%' ";
    $result2 = mysql_query($sql2) or die('Query faileda3: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;

    break;
  case('fax'):
    if($value==''){
      $a=array();
      return $a;
    }

    $value=preg_replace('/\s/','',$value);
    $sql2="select contact_id,parent_id  from telecom  left join contact_relations on (contact_id=child_id)  where (tipo=4) and number like '%".addslashes($value)."%' ";
    $result2 = mysql_query($sql2) or die('Query faileda4: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;

    break;
  case('mobile'):
    if($value==''){
      $a=array();
      return $a;
    }
    $value=preg_replace('/\s/','',$value);
    $sql2="select contact_id,parent_id  from telecom  left join contact_relations on (contact_id=child_id)  where (tipo=2) and number like '%".addslashes($value)."%' ";
    $result2 = mysql_query($sql2) or die('Query faileda5: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;

    break;

  case('email'):
    if($value==''){
      $a=array();
      return $a;
    }
    $value=preg_replace('/\s/','',$value);
    $sql2="select contact_id,parent_id  from email  left join contact_relations on (contact_id=child_id)  where email like '".addslashes($value)."' ";
    $result2 = mysql_query($sql2) or die('Query faileda6: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;

    break;
  case('fuzzy_address'):
    if($value==''){
      $a=array();
      return $a;
    }
    $sql2="select contact_id,parent_id, match(full_address) against ('".addslashes($value)."') as ma   from address  left join contact_relations on (contact_id=child_id)  where ma>90 ";
    $result2 = mysql_query($sql2) or die('Query faileda7: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;
     
  case('address'):
    if($value1=='' and $value2==''){
      $a=array();
      return $a;
    }
    $value=str_replace(' ','',$value);
   
    $sql2="select contact_id,parent_id   from address  left join contact_relations on (contact_id=child_id)  where  address1='".addslashes($value1)."' and town='".addslashes($value2)."' and  postcode like '".addslashes($value)."' ";
    $result2 = mysql_query($sql2) or die('Query faileda8: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;
    break;

  case('postcode'):
    $value=preg_replace('/\s/','',$value);
    $sql2="select contact_id,parent_id   from address  left join contact_relations on (contact_id=child_id)  where  postcode like '".addslashes($value)."' ";
    $result2 = mysql_query($sql2) or die('Query faileda:9 ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;
    break;
  case('fuzzy_name'):
    if($value=='' ){
      $a=array();
      return $a;
    }
    $sql2="select contact_id,parent_id, match(name) against ('".$value."') as ma   from contact  left join contact_relations on (contact.id=child_id)  where ma>90 ";
    $result2 = mysql_query($sql2) or die('Query faileda:10 ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;
    
    break;
    
  case('name'):
    if($value=='' ){
      $a=array();
      return $a;
    }
    $sql2="select contact.id as contact_id,parent_id  from contact  left join contact_relations on (contact.id=child_id)  where name='".addslashes($value)."' ";
    //if($debug)print "$sql2\n";
    $result2 = mysql_query($sql2) or die('Query faileda:11 ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;

    break;
  }


  $a=array();
  return $a;
  
}

function prepare_name($c_name,$c_fname){

  //try to get prefixes
  $tipo=1;
  $prefix='';
  $fname='';

  $c_name=trim($c_name);
  $c_name=trim($c_name);
  if(preg_match('/^Mr\s/',$c_name)){
    $tipo=1;
    $prefix='Mr';
    $c_name=preg_replace('/^Mr\s/','',$c_name);
  }elseif(preg_match('/^Mr.\s/',$c_name)){
    $tipo=1;
    $prefix='Mr';
    $c_name=preg_replace('/^Mr.\s/','',$c_name);
  }elseif(preg_match('/^Mrs.\s/',$c_name)){
    $tipo=2;
    $prefix='Mrs';
    $c_name=preg_replace('/^Mrs.\s/','',$c_name);
  }elseif(preg_match('/^Mrs\s/',$c_name)){
    $tipo=2;
    $prefix='Mrs';
    $c_name=preg_replace('/^Mrs\s/','',$c_name);

	      
  }elseif(preg_match('/^Ms.\s/',$c_name)){
    $tipo=2;
    $prefix='Mrs';
    $c_name=preg_replace('/^Ms.\s/','',$c_name);
  }elseif(preg_match('/^Ms\s/',$c_name)){
    $tipo=2;
    $prefix='Mrs';
    $c_name=preg_replace('/^Ms\s/','',$c_name);
  }elseif(preg_match('/^Miss\s/',$c_name)){
    $tipo=2;
    $prefix='Miss';
    $c_name=preg_replace('/^Miss\s/','',$c_name);
  }
  $c_name=trim($c_name);
  //print "xxx $c_fname XXX $c_name  ";

  $tmp=str_replace("/",'\/',$c_fname);
  
  if($c_fname!='' and preg_match('/^'.$tmp.'/',$c_name)){
    $fname=$c_fname;
    $cname=str_replace($c_fname,'',$c_name);
    //$c_name=preg_replace('/'.$c_fname.'/','',$c_name);//++++ Esto esta mal!!!! no se por que
    $c_name=trim($c_name);
    $order=$c_name.' '.$fname;
  }else
    $order=$c_name;
  //	    print "$fname $c_name";
  //	    exit;
  $name=trim(trim($prefix.' '.$fname.' '.$c_name));
  $oname=trim(trim($c_name.' '.$fname));
  return array($tipo,$prefix,$fname,$c_name,'',$name,$oname,'','');

}




function savecontact(){
  
  $db =& MDB2::singleton();
  global $debug;

  if(!isset($_SESSION['new_contact']['tipo']))
    break;
     



  // print_r($_SESSION['new_contact']);
  $tipo=$_SESSION['new_contact']['tipo'];
   
   
   


  $name=addslashes($_SESSION['new_contact']['name'][4]);
  $order=addslashes($_SESSION['new_contact']['name'][5]);

  $sql=sprintf("insert into contact (name,order_name,tipo,date_creation,date_updated) values ('%s','%s',%d,NOW(),NOW())",$name,$order,$tipo);
  if($debug)print "xx $sql\n";
  mysql_query($sql);
  $contact_id = $db->lastInsertID();
  

  if($tipo>0){
    $aname=$_SESSION['new_contact']['name'];
    $prefix=($aname[0]!=''?'"'.addslashes($aname[0]).'"':'null');
    $first=($aname[1]!=''?'"'.addslashes($aname[1]).'"':'null');
    $last=($aname[2]!=''?'"'.addslashes($aname[2]).'"':'null');
    $suffix=($aname[3]!=''?'"'.addslashes($aname[3]).'"':'null');
    $middle=($aname[6]!=''?'"'.addslashes($aname[6]).'"':'null');
    $alias=($aname[7]!=''?'"'.addslashes($aname[7]).'"':'null');
    $sql=sprintf("insert into name (contact_id,prefix,first,last,suffix,middle,alias) values (%d,%s,%s,%s,%s,%s,%s)",$contact_id,$prefix,$first,$last,$suffix,$middle,$alias);
    mysql_query($sql);
    if($debug)print "$sql\n";
  }

  $main_name=$name;
  if(isset($_SESSION['new_contact']['contact'])){
    $tipo=$_SESSION['new_contact']['contact'][0];
    $name=addslashes($_SESSION['new_contact']['contact'][5]);
    $order=addslashes($_SESSION['new_contact']['contact'][6]);
    $sql=sprintf("insert into contact (name,order_name,tipo,date_creation,date_updated) values ('%s','%s',%d,NOW(),NOW())",$name,$order,$tipo);
    mysql_query($sql);
    if($debug)print "x $sql\n";
    $contactincompany_id = $db->lastInsertID();
    $aname=$_SESSION['new_contact']['contact'];

    $prefix=($aname[1]!=''?'"'.addslashes($aname[1]).'"':'null');
    $first=($aname[2]!=''?'"'.addslashes($aname[2]).'"':'null');
    $last=($aname[3]!=''?'"'.addslashes($aname[3]).'"':'null');
    $suffix=($aname[4]!=''?'"'.addslashes($aname[4]).'"':'null');
    $middle=($aname[7]!=''?'"'.addslashes($aname[7]).'"':'null');
    $alias=($aname[8]!=''?'"'.addslashes($aname[8]).'"':'null');
    $sql=sprintf("insert into name (contact_id,prefix,first,last,suffix,middle,alias) values (%d,%s,%s,%s,%s,%s,%s)",$contactincompany_id,$prefix,$first,$last,$suffix,$middle,$alias);
    //     print $sql;
    if($debug)print "$sql\n";

    $contact_name=$name;
    $sql=sprintf("insert into contact_relations (child_id,parent_id) values (%d,%d)",$contactincompany_id,$contact_id);
    if($debug)print "y $sql\n";

    mysql_query($sql);
     
  }



  if(isset($_SESSION['new_contact']['email']))
    foreach($_SESSION['new_contact']['email'] as $aemail){
       
      if($aemail[2]=='')
	continue;
       
      $tipo=$aemail[0];
      $name=addslashes($aemail[1]);
      $email=addslashes($aemail[2]);
       
      if(($tipo==0 or $tipo==1) and (isset($contactincompany_id))){
	if($name=='')
	  $name=$contact_name;
	$sql=sprintf("insert into email (contact,email,tipo,contact_id) values ('%s','%s',%d,%d)",$name,$email,$tipo,$contactincompany_id);
	mysql_query($sql);
      }elseif($tipo==2){
	if($name=='')
	  $name=$main_name;
	$sql=sprintf("insert into email (contact,email,tipo,contact_id) values ('%s','%s',%d,%d)",$name,$email,$tipo,$contact_id);
      }
       
      //print $sql;
    }
  if(isset($_SESSION['new_contact']['tel']))
    foreach($_SESSION['new_contact']['tel'] as $atel){
      if($atel[3]==''  )
	continue;
       
       
      $tipotel=$atel[0];
      $name=($atel[1]!=''?'"'.addslashes($atel[1]).'"':'null');
      $code=(is_numeric($atel[2])?$atel[2]:'null');
      $number=(is_numeric($atel[3])?$atel[3]:'null');
      $ext=(is_numeric($atel[4])?$atel[4]:'null');
       
      $sql=sprintf("insert into telecom (name,code,number,ext,tipo,contact_id) values (%s,%s,%s,%s,%d,%d)",$name,$code,$number,$ext,$tipotel,$contact_id);
      mysql_query($sql);
      if($tipotel==1 and isset($contactincompany_id))
	{
   
	  $sql=sprintf("insert into telecom (name,code,number,ext,tipo,contact_id) values (%s,%s,%s,%s,%d,%d)",$name,$code,$number,$ext,$tipotel,$contactincompany_id);
	  mysql_query($sql);
	  // print "$sql\n";
	   
	}

    }
  if(isset($_SESSION['new_contact']['www']))
    foreach($_SESSION['new_contact']['www'] as $awww){
	 

      if($awww[1]=='')
	continue;
	 
      $title=($awww[0]!=''?"'".addslashes($awww[0]).'"':'null');
      $www=addslashes($awww[1]);
	 
      $sql=sprintf("insert into www (title,www,contact_id) values (%s,%s,%d)",$title,$www,$contact_id);
      mysql_query($sql);
    }
  if(isset($_SESSION['new_contact']['address']))
    foreach($_SESSION['new_contact']['address'] as $aadd){
       
       
      $tipo=$aadd[0];

      $pc=addslashes($aadd[6]);
      $address1=($aadd[1]!=''?'"'.addslashes(trim($aadd[1])).'"':'null');
      $address2=($aadd[2]!=''?'"'.addslashes($aadd[2]).'"':'null');
      $address3=($aadd[3]!=''?'"'.addslashes($aadd[3]).'"':'null');
      $town=($aadd[4]!=''?'"'.addslashes($aadd[4]).'"':'null');
      $subdistrict=($aadd[5]!=''?'"'.addslashes($aadd[5]).'"':'null');
      $postcode=($aadd[6]!=''?'"'.addslashes(str_replace(' ','',$aadd[6])).'"':'null');
      $country=($aadd[7]!=''?'"'.addslashes($aadd[7]).'"':'null');
      $country_id=$aadd[8];
      $full_address=($aadd[9]!=''?'"'.addslashes($aadd[9]).'"':'null');
      $principal=($aadd[10]==''?0:$aadd[10]);

  //    $full_address=($address1!='null'?preg_replace('/^.|.$/','',$address1)."\n":'').($address2!='null'?preg_replace('/^.|.$/','',$address2)."\n":'').($address3!='null'?preg_replace('/^.|.$/','',$address3)."\n":'').($town!='null'?preg_replace('/^.|.$/','',$town)."\n":'').($subdistrict!='null'?preg_replace('/^.|.$/','',$subdistrict)."\n":'').($postcode!='null'?$pc."\n":'').($country!='null'?preg_replace('/^.|.$/','',$country)."\n":'');


      $sql=sprintf("insert into address (principal,tipo,full_address,address1,address2,address3,town,subdistrict,postcode,country,country_id,contact_id) values (%d,%d,%s,%s,%s,%s,%s,%s,%s,%s,%d,%d)",
		   $principal,$tipo,$full_address,$address1,$address2,$address3,$town,$subdistrict,$postcode,$country,$country_id,$contact_id
		   );
     // if($debug)print "$sql\n";
      mysql_query($sql);

    }


  return $contact_id;

}



function get_matches($email,$mobile,$tel,$fax,$name,$contact,$a1,$a2,$a3,$town,$state,$postcode,$country){
  global $debug;
  $similar_email=checksimilar('email',$email);
  $similar_tel=checksimilar('tel',$tel);
  $similar_mobile=checksimilar('mobile',$mobile);
  $similar_fax=checksimilar('fax',$fax);
  $similar_name=checksimilar('name',$name);
  $similar_contact=checksimilar('name',$contact);
  $similar_address=checksimilar('address',$postcode,$contact,$town);
    
  
  $similar=array();
  foreach($similar_email as $i)
    $similar[]=$i;
  foreach($similar_tel as $i)
    $similar[]=$i;
  foreach($similar_fax as $i)
    $similar[]=$i;
  foreach($similar_mobile as $i)
    $similar[]=$i;
  foreach($similar_name as $i)
    $similar[]=$i;
  foreach($similar_contact as $i)
    $similar[]=$i;
  foreach($similar_address as $i)
    $similar[]=$i;

  
  $similar=array_unique($similar);
  
  

  $match=array();
  foreach($similar as $sim){
    $score=0;
    foreach($similar_address as $x){
      if($x==$sim){
	$score+=80;
	if($debug)print"s address\n";
	break;
      }
    }
    foreach($similar_tel as $x){
      if($x==$sim){
	$score+=67.5;
	if($debug)print"s tel\n";

	break;
      }
    }
    foreach($similar_fax as $x){
      if($x==$sim){
	$score+=67.5;
	if($debug)print"s fax\n";

	break;
      }
    }
    foreach($similar_mobile as $x){
      if($x==$sim){
	$score+=75;
	if($debug)print"s mob\n";

	break;
      }
    }
    foreach($similar_email as $x){
      if($x==$sim){
	$score+=99;
	if($debug)print"s email\n";

	break;
      }
    }
    foreach($similar_name as $x){
      if($x==$sim){
	$score+=41;
	if($debug)print"s name\n";

	break;
      }
    }
    foreach($similar_contact as $x){
      if($x==$sim){
	$score+=40;
	if($debug)print"s con\n";

	break;
      }
    }



    $match[]=array('contact'=>$sim,'score'=>$score);
  }  


  //   $match[]=array('contact'=>23,'score'=>.13);
  //   $match[]=array('contact'=>121,'score'=>.9);
  //   $match[]=array('contact'=>124,'score'=>.23);
  //   $match[]=array('contact'=>253,'score'=>.14);
  //   $match[]=array('contact'=>123,'score'=>.23);


  
  $s=array();
  foreach ($match as $key => $row) {
    //    $c[$key]  = $row['contact'];
    $s[$key] = $row['score'];
  }
  
  array_multisort($s, SORT_DESC, $match);
  
  

  return $match;

}


function update_customer_data($customer_id){
  $db =& MDB2::singleton();
  $sql=sprintf("select contact_id,id from customer where id=%d",$customer_id ) ;
  $res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  while($row=$res->fetchRow()) 
    {
      $sql="select sum(total) as total , count(*) as orders, date_index from orden where customer_id=".$row['id'].' group by customer_id order by date_index';
    //  print "$sql\n";
      $res2 = mysql_query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
      while ($row2 = $res2->fetchRow() ) {
	$orders=$row2['orders'];
	$date=$row2['date_index'];
	$total=$row2['total'];
      }
      $sql="
 select country_id,contact.name as name ,c.code2 as code ,if(!isnull(address.subdistrict),concat(c.code,' ',address.town),address.town) as loc from contact left join address on (contact_id=contact.id) left join country as c on (c.id=country_id) where contact.id=".$row['contact_id']." and principal=1 limit 1;";
      $name='';
      $loc='';
      $country_id=244;
      $res2 = mysql_query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
      while ($row2 = $res2->fetchRow() ) {
	$name=$row2['name'];
	$loc=$row2['loc'];
	$country_id=$row2['country_id'];
	
  }
  
  
  $sql=sprintf("update customer set location='%s' ,country_id='%s',total='%.2f',orders='%d',last_order='%s' ,name='%s' where id=%d   ",$loc,$country_id,$total,$orders,$date,addslashes($name),$row['id']);
  mysql_query($sql);
 }
}



?>
