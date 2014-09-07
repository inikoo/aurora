<?php
error_reporting(E_ALL);

include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Order.php');
include_once('../../class.Invoice.php');
include_once('../../class.DeliveryNote.php');
include_once('../../class.Email.php');
include_once('../../class.TimeSeries.php');
include_once('../../class.CurrencyExchange.php');
//include_once('ci_map_order_functions.php');
include_once('common_read_orders_functions.php');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {
  print "Error can not connect with database server\n";
  exit;
}
//$dns_db='dw_tmp';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
  print "Error can not access the database\n";
  exit;
}
$tipo_his=array();


date_default_timezone_set('UTC');
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once 'timezone.php';
date_default_timezone_set(TIMEZONE) ;

include_once('../../set_locales.php');

require_once '../../conf/conf.php';
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();

$currency='GBP';
$_SESSION['lang']=1;
$convert_encoding=true;
include_once('ci_local_map.php');

$map_act=$_map_act;

//print_r($map_act);

$map_act[90]='creation_date';

$filename="ci_export_with_history.csv";
$row = 0;
$contacts=array();
$contacts_date=array();
if (($handle = fopen($filename, "r")) !== FALSE) {
  while (($data = fgetcsv($handle, filesize($filename), ",")) !== FALSE) {
    $num = count($data);
   

    if($row==0){
      // print_r($data);exit;
      $row++;
       $col_names=$data;
       continue;
    }    
    $row++;
    
    $cols=array();
    foreach($data as $key=>$col){
      if($convert_encoding)
	$cols[$key]=mb_convert_encoding($col, "UTF-8", "ISO-8859-1");
      else
	$cols[$key]=$col;
    }

    

    $act_data=array();
    $act_data['name']=mb_ucwords($cols[$map_act['name']+3]);


    $act_data['contact']=mb_ucwords($cols[$map_act['contact']+3]);
    if($act_data['name']=='' and $act_data['contact']!='') // Fix only contact
      $act_data['name']=$act_data['contact'];


    if(preg_match('/aw-regalos/',$act_data['name']))
      continue;
    $act_data['first_name']=mb_ucwords($cols[$map_act['first_name']+3]);
    $act_data['a1']=mb_ucwords($cols[$map_act['a1']+3]);
    $act_data['a2']=mb_ucwords($cols[$map_act['a2']+3]);
    $act_data['a3']=mb_ucwords($cols[$map_act['a3']+3]);
    $act_data['town']=mb_ucwords($cols[$map_act['town']+3]);
    $act_data['country_d2']=mb_ucwords($cols[$map_act['country_d2']+3]);
    $act_data['postcode']=$cols[$map_act['postcode']+3];
    
    $act_data['country']=mb_ucwords($cols[$map_act['country']+3]);
    $act_data['tel']=$cols[$map_act['tel']+3];
    $act_data['fax']=$cols[$map_act['fax']+3];
    $act_data['mob']=$cols[$map_act['mob']+3];
    
    
    
    
    $act_data['source']=$cols[$map_act['source']+3];
    $act_data['act']=$cols[$map_act['act']+3];
    $act_data['email']=$cols[95];
    $act_data['dont_send_email']=false;
   
    if($act_data['email']=='' and  preg_match('/^\s*[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})\s*$/i',$cols[72],$match)  ){
    $act_data['email']=$cols[72];
     $act_data['dont_send_email']=true;
    }
    
    $act_data['tax_type']='S1';
    
    if($cols[90]=='Si')
      $act_data['tax_type']='S3';


    $act_data['tax_number']=$cols[91];

    $act_data['source']=$cols[25+3];
    $act_data['category']=$cols[27+3];
    $act_data['pay_method']=$cols[37+3];
    $act_data['history']=$cols[96];
    $act_data['all_data']=$cols;
    $act_data['creator']=$cols[68];
    //    print $cols[92]."\n";

    $act_data['country_d1']='';
    $act_data['vat_number']=$cols[88+3];
    $tmp=preg_split('/\s/',$cols[0]);
    $tmp2=preg_split('/\//',$tmp[0]);
    $formated_time=$tmp2[2].'-'.$tmp2[1].'-'.$tmp2[0].' '.$tmp[1];
    $creation_time=strtotime($formated_time);
    $act_data['original_creation_time']=$cols[0];
    // print "+".$cols[0]." $formated_time ".$creation_time."\n";
    $act_data['creation_date']=date("Y-m-d H:i:s",$creation_time);
    $act_data['creation_datetimestap']=$creation_time;
    //print_r($act_data);
   
    $act_data=act_transformations($act_data);
     
    $act_data=ci_act_transformations($act_data);
    //print_r($cols);
    //print_r($act_data);
    $history_data=get_history_data($act_data['history']);
    $act_data['history']=$history_data;

    

    if($act_data['name']=='' and $act_data['contact']=='' and $act_data['email']=='' and $act_data['tel']=='' 
       and $act_data['fax']==''
       and $act_data['mob']==''
       
       ){
      
      continue;
    }



    $contacts[$row]=$act_data;
    $contacts_date[$row]=$creation_time;
    
    //print_r($act_data);
    //   if($row>500)
     //  break;
    print "$row\r";

    // print_r($cols);
    
    //    print "==============================\n".$act_data['history']."\n";

    
    // print_r($history_data);


  }
  fclose($handle);
}
//print_r($contacts);

usort($contacts, 'compare');
//print_r($contacts);


//$fp = fopen('contacts_act_file.csv', 'w');
//foreach ($contacts as $line) {    fputcsv($fp, $line);}
//fclose($fp);


foreach($contacts as $act_data_contact_key=>$act_data){

  //if($act_data['creation_date']!='2006-01-03 12:22:31')
  //  continue;

  if($act_data['name']!=$act_data['contact'] )
    $tipo_customer='Company';
  else{	  
    $tipo_customer='Person';
  }
  $email_data=guess_email($act_data['email']);

  if(!isset($act_data['town_d1']))
    $act_data['town_d1']='';
  if(!isset($act_data['town_d2']))
    $act_data['town_d2']='';
  $address_raw_data=get_address_raw();
  $address_raw_data['address1']=$act_data['a1'];
  $address_raw_data['address2']=$act_data['a2'];
  $address_raw_data['address3']=$act_data['a3'];
  $address_raw_data['town']=$act_data['town'];
  $address_raw_data['town_d1']=$act_data['town_d1'];
  $address_raw_data['town_d2']=$act_data['town_d2'];
  $address_raw_data['country_d2']=$act_data['country_d2'];
  $address_raw_data['postcode']=$act_data['postcode'];
  $address_raw_data['country']=$act_data['country'];
  if(isset($act_data['country_d1']))
    $address_raw_data['country_d1']=$act_data['country_d1'];


  $shop_address_data=$address_raw_data;
  $_customer_data=array();
  $_customer_data['Customer Old ID']=$act_data['act'];
  $_customer_data['type']=$tipo_customer;
  $_customer_data['date_created']=$act_data['creation_date'];
  $_customer_data['contact_name']=$act_data['contact'];
  $_customer_data['company_name']=$act_data['name'];
  $_customer_data['email']=$email_data['email'];
  $_customer_data['telephone']=_trim($act_data['tel']);
  $_customer_data['fax']=$act_data['fax'];
  $_customer_data['mobile']=$act_data['mob'];
  $_customer_data['address_data']=$shop_address_data;
  $_customer_data['address_data']['type']='3line';
  $_customer_data['tax_type']=$act_data['tax_type'];

  $_customer_data['address_data']=$shop_address_data;
  $_customer_data['address_data']['type']='3line';
  $_customer_data['address_data']['name']=$act_data['contact'];
  $_customer_data['address_data']['company']=$act_data['name'];
  $_customer_data['address_data']['telephone']=_trim($act_data['tel']);
  $_customer_data['editor']=array('Date'=>$act_data['creation_date']);

    
  $_customer_data['Customer Source']=$act_data['source'];
  $_customer_data['Customer Meta Category']=$act_data['category'];
  $_customer_data['Customer Usual Payment Method']=$act_data['pay_method'];
        $_customer_data['Customer Tax Number']=$act_data['tax_number'];

   
    


  // print_r($_customer_data);

    


foreach($_customer_data as $_key =>$value){
  $key=$_key;
  if($_key=='type')
    $key=preg_replace('/^type$/','Customer Type',$_key);
  if($_key=='other id')
    $key='Customer Old ID';
  if($_key=='contact_name')
    $key=preg_replace('/^contact_name$/','Customer Main Contact Name',$_key);
  if($_key=='company_name')
    $key=preg_replace('/^company_name$/','Customer Company Name',$_key);
  if($_key=='email')
    $key=preg_replace('/^email$/','Customer Main Plain Email',$_key);
  if($_key=='telephone')
    $key=preg_replace('/^telephone$/','Customer Main Plain Telephone',$_key);
  if($_key=='fax')
    $key=preg_replace('/^fax$/','Customer Main Plain FAX',$_key);
  if($_key=='mobile')
    $key=preg_replace('/^mobile$/','Customer Mobile',$_key);
  if($_key=='tax_number')
    $key='Customer Tax Number';
  if($_key=='tax_type')
    $key='Customer Tax Category Code';
  $customer_data[$key]=$value;

}
if($customer_data['Customer Type']=='Company')
  $customer_data['Customer Name']=$customer_data['Customer Company Name'];
else
  $customer_data['Customer Name']=$customer_data['Customer Main Contact Name'];
if(isset($_customer_data['address_data'])){
  
  $customer_data['Customer Store Key']=1;

  
  
  $customer_data['Customer First Contacted Date']=$act_data['creation_date'];
   


  $customer_data['Customer Address Line 1']=$_customer_data['address_data']['address1'];
  $customer_data['Customer Address Line 2']=$_customer_data['address_data']['address2'];
  $customer_data['Customer Address Line 3']=$_customer_data['address_data']['address3'];
  $customer_data['Customer Address Town']=$_customer_data['address_data']['town'];
  $customer_data['Customer Address Postal Code']=$_customer_data['address_data']['postcode'];
  $customer_data['Customer Address Country Name']=$_customer_data['address_data']['country'];
  $customer_data['Customer Address Country First Division']=$_customer_data['address_data']['country_d1'];
  $customer_data['Customer Address Country Second Division']=$_customer_data['address_data']['country_d2'];
  
  
  if(
    $customer_data['Customer Address Line 1']=='' and
    $customer_data['Customer Address Line 2']=='' and
    $customer_data['Customer Address Line 3']=='' and
   $customer_data['Customer Address Town']=='' and
  $customer_data['Customer Address Postal Code']=='' and
  $customer_data['Customer Address Country Name']=='' and
  $customer_data['Customer Address Country First Division']=='' and
  $customer_data['Customer Address Country Second Division']==''
   
  
  ){
  $customer_data['Customer Address Country Name']='Spain';
  
  }
  
  
  unset($customer_data['address_data']);
}
$shipping_addresses=array();
$customer_data['Customer Delivery Address Link']='Contact';
//	  print "===================================\n";

if($customer_data['Customer Main Contact Name']=='Ms' and $customer_data['Customer Company Name']=='Ms'){
$customer_data['Customer Main Contact Name']='';
$customer_data['Customer Company Name']='';
}
 $customer_data['Customer Send Postal Marketing']='Yes';

 if (!$act_data['dont_send_email']) {
        $customer_data['Customer Send Newsletter']='Yes';
        $customer_data['Customer Send Email Marketing']='Yes';

    } else {
       $customer_data['Customer Send Newsletter']='No';
       $customer_data['Customer Send Email Marketing']='No';

    }
    
    
 ///print_r($customer_data);
  ///print_r($act_data);
//continue;

$customer = new Customer ( 'find create update',  $customer_data);
   
if(_trim($customer->data['Customer Name'])==''){
  print_r($customer_data);
  print_r($act_data);
 print_r($customer);
  exit;
}

 
//   if(count($act_data['history'])>0){
//  print "Customer ".$customer->id." with History\n\n\n\n\n\n";
//  print_r($act_data['history']);
// }
/*
foreach($act_data['history'] as $h_tipo=>$histories){
  if($h_tipo=='Note')
    foreach($histories as $date=>$history){
      $customer->add_note($history,'',$date);
    }
  else{
    foreach($histories as $date=>$history){
      $customer->add_note("Old Database Note ($h_tipo)",$history,$date);
    }
  }
}
*/


    $_details='<table>';
    foreach($act_data['all_data'] as $_key=>$_value) {
        if ($_value!='' and $col_names[$_key]!='History_Generated')
            $_details.= '<tr><td>'.$col_names[$_key]."</td><td>$_value</td><tr>";
    }
    $_details.='</table>';
    $_details=_trim($_details);
    if (!$customer->new) {
        $history_found=false;
        $sql=sprintf("select `History Key` from `History Dimension` where `Direct Object`='Customer' and `Direct Object Key`=%d and (`History Abstract`='Contact data imported from Act' or `History Abstract`='Contact data imported from Act (Merged)') and `Metadata`=%s ",
                     $customer->id,
                     prepare_mysql(md5($_details))
                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {
            $history_found=true;
        }

        
$sql=sprintf("select `History Key` from `History Dimension` where `Direct Object`='Customer' and `Direct Object Key`=%d and (`History Abstract`='Contact data imported from Act' or `History Abstract`='Contact data imported from Act (Merged)') ",
                     $customer->id
                    
                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {
            $data_not_imported_yet=false;
        }else{
            $data_not_imported_yet=true;
        }

        if($data_not_imported_yet){
        $history_key=$customer->add_note('Contact data imported from Act',$_details,date("Y-m-d H:i:s"));
        $sql=sprintf("update `History Dimension` set `Metadata`=%s where `History Key`=%d",prepare_mysql(md5($_details)),$customer->new_value);
        mysql_query($sql);
        }else{
            


        if (!$history_found) {
            $history_key=$customer->add_note('Contact data imported from Act (Merged)',$_details,date("Y-m-d H:i:s"));
            $sql=sprintf("update `History Dimension` set `Metadata`=%s where `History Key`=%d",prepare_mysql(md5($_details)),$customer->new_value);
//print "$sql\n";
            mysql_query($sql);
        }
        }


    } else {
        $history_key=$customer->add_note('Contact data imported from Act',$_details,date("Y-m-d H:i:s"));
        $sql=sprintf("update `History Dimension` set `Metadata`=%s where `History Key`=%d",prepare_mysql(md5($_details)),$customer->new_value);
        mysql_query($sql);
    }

  foreach($act_data['history'] as $h_tipo=>$histories) {

        //print_r($histories);

        if ($h_tipo=='Note')
            foreach($histories as $date=>$history) {
            $history_found=false;
            if (!$customer->new) {
                $sql=sprintf("select `History Key` from `History Dimension` where `Direct Object`='Customer' and `Direct Object Key`=%d and `History Abstract`=%s  and `History Date`=%s",
                             $customer->id,
                             prepare_mysql($history),
                             prepare_mysql($date)
                            );
                //print "$sql\n";
                $res=mysql_query($sql);

                if ($row=mysql_fetch_assoc($res)) {
                    $history_found=true;
                }
            }
            if (!(!$customer->new and $history_found) )
                $customer->add_note($history,'',$date);
        } else {
            foreach($histories as $date=>$history) {


                $history_found=false;
                if (!$customer->new) {
                    $sql=sprintf("select `History Key` from `History Dimension` where `Direct Object`='Customer' and `Direct Object Key`=%d and `History Abstract`=%s  and `History Date`=%s",
                                 $customer->id,
                                 prepare_mysql("Old Database Note ($h_tipo)"),
                                 prepare_mysql($date)
                                );
                    $res=mysql_query($sql);

                    if ($row=mysql_fetch_assoc($res)) {
                        $history_found=true;
                    }
                }
                if (!(!$customer->new and $history_found) )

                    $customer->add_note("Old Database Note ($h_tipo)",$history,$date);
            }
        }
    }

//print "caca";
//print_r($customer);
   
}






function compare($x, $y)
{
  if ( $x['creation_datetimestap'] == $y['creation_datetimestap'] )
    return 0;
  else if ( $x['creation_datetimestap'] < $y['creation_datetimestap'] )
    return -1;
  else
    return 1;
}
   

function get_history_data($raw_history){
  global $tipo_his;


  $history=array('Field Changed'=>array(),'Note'=>array(),'E-mail Sent'=>array(),'Attachment'=>array(),'Contact Deleted'=>array(),'To-do Done'=>array(),'Call Completed'=>array(),'To-do Not Done'=>array());

  $history=array();

 
  if($raw_history=='')
    return $history;

  $date_separator='/\d{2}\/\d{2}\/\d{4}\s\d{2}\:\d{2}\:\d{2}\s-------------------------------------------\s/';

  $date_splited=preg_split($date_separator,$raw_history);
  unset($date_splited[0]);
  // print_r($date_splited);
  
  foreach($date_splited as $y){
    $x=preg_split('/\s+\-\s+/',$y);
    $tipo_his[$x[0]]=1;
  }

  preg_match_all($date_separator,$raw_history, $_dates);
  //print_r($_dates);  
  $dates=array();
  foreach($_dates[0] as $_tmp){

    
    $tmp=preg_split('/\s/',$_tmp);
    $tmp2=preg_split('/\//',$tmp[0]);
    $formated_time=$tmp2[2].'-'.$tmp2[1].'-'.$tmp2[0].' '.$tmp[1];
    $creation_time=strtotime($formated_time);
    $dates[]=date("Y-m-d H:i:s",$creation_time);
  }
  //  print "-----\n";
  //print_r($date_splited);
  //print_r($dates);  
  // return;
  foreach($date_splited as $index=>$y){
    $x=preg_split('/\s+\-\s+/',$y);
    $tipo=$x[0];
    $note=preg_replace("/^$tipo -/",'',$y);
      
      
      
    if(isset($history[$tipo][$dates[$index-1]]))
      $history[$tipo][$dates[$index-1]].=";\n".$note;
    else
      $history[$tipo][$dates[$index-1]]=$note;
    


  

 
  }

  //print_r($dates);
  return $history;

}





?>