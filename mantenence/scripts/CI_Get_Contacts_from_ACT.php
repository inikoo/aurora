<?php
error_reporting(E_ALL);

include_once('../../app_files/db/dns.php');
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


date_default_timezone_set('Europe/London');
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/timezone.php';
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
      $row++;continue;
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
    
    $act_data['tax_type']=$cols[90];
    if($act_data['tax_type']=='No')
      $act_data['tax_type']='Default';
    else
      $act_data['tax_type']='RE';


    $act_data['tax_number']=$cols[91];

    $act_data['source']=$cols[25+3];
    $act_data['category']=$cols[27+3];
    $act_data['pay_method']=$cols[37+3];
    $act_data['history']=$cols[96];
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
    // print_r($cols);
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
   //  if($row>1000)
   //    break;
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


foreach($contacts as $act_data){

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

  $_customer_data['address_data']=$shop_address_data;
  $_customer_data['address_data']['type']='3line';
  $_customer_data['address_data']['name']=$act_data['contact'];
  $_customer_data['address_data']['company']=$act_data['name'];
  $_customer_data['address_data']['telephone']=_trim($act_data['tel']);
  $_customer_data['editor']=array('Date'=>$act_data['creation_date']);

    
  $_customer_data['Customer Source']=$act_data['source'];
  $_customer_data['Customer Meta Category']=$act_data['category'];
  $_customer_data['Customer Usual Payment Method']=$act_data['pay_method'];
    
   
    


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
    $key=preg_replace('/^telephone$/','Customer Main Telephone',$_key);
  if($_key=='fax')
    $key=preg_replace('/^fax$/','Customer Main FAX',$_key);
  if($_key=='mobile')
    $key=preg_replace('/^mobile$/','Customer Mobile',$_key);
  if($_key=='tax_number')
    $key='Customer Tax Number';
  if($_key=='tax_type')
    $key='Customer Tax Category';
  $customer_data[$key]=$value;

}
if($customer_data['Customer Type']=='Company')
  $customer_data['Customer Name']=$customer_data['Customer Company Name'];
else
  $customer_data['Customer Name']=$customer_data['Customer Main Contact Name'];
if(isset($_customer_data['address_data'])){
  
  $customer_data['Customer Store Key']=1;
  if(preg_match('/aw-geschenke/i',$_customer_data['Customer Source']))
    $customer_data['Customer Store Key']=2;
  if(preg_match('/nabil/i',$act_data['creator']))
    $customer_data['Customer Store Key']=3;
  
  
  $customer_data['Customer First Contacted Date']=$act_data['creation_date'];
   


  $customer_data['Customer Address Line 1']=$_customer_data['address_data']['address1'];
  $customer_data['Customer Address Line 2']=$_customer_data['address_data']['address2'];
  $customer_data['Customer Address Line 3']=$_customer_data['address_data']['address3'];
  $customer_data['Customer Address Town']=$_customer_data['address_data']['town'];
  $customer_data['Customer Address Postal Code']=$_customer_data['address_data']['postcode'];
  $customer_data['Customer Address Country Name']=$_customer_data['address_data']['country'];
  $customer_data['Customer Address Country Primary Division']=$_customer_data['address_data']['country_d1'];
  $customer_data['Customer Address Country Secondary Division']=$_customer_data['address_data']['country_d2'];
  unset($customer_data['address_data']);
}
$shipping_addresses=array();
$customer_data['Customer Delivery Address Link']='Contact';
// print_r($customer_data);
// print_r($act_data);
//continue;

$customer = new Customer ( 'find create',  $customer_data);
    
//   if(count($act_data['history'])>0){
//  print "Customer ".$customer->id." with History\n\n\n\n\n\n";
//  print_r($act_data['history']);
// }

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


function ci_act_transformations($act_data){
 


 $act_data['name']=preg_replace('/\\\"/i',' ',$act_data['name']);
  $act_data['contact']=preg_replace('/\\\"/i',' ',$act_data['contact']);

 
  if($act_data['name']=='Eujopa.s.l'){
    $act_data['name']='Eujopa S.L.';
  }
if($act_data['name']=='S. coop. mad. Los Apisquillos'){
    $act_data['name']='S. Coop. Mad. Los Apisquillos';
  }

  //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  // fix contracts
  if($act_data['name']=='Antonio Laborda - Jabón Jabón'){
    $act_data['name']='Jabón Jabón';
    $act_data['contact']='Antonio Laborda';
  }

 

  if($act_data['country']==''){


    if(preg_match('/spain\s*.\s*ibiza/i',$act_data['postcode'])){
      $act_data['country']='Spain';
      $act_data['postcode']='';
      $act_data['country_d1']='Balearic Islands';
      $act_data['country_d2']='Balearic Islands';
    }
      
    

    $tmp_array=preg_split('/\s+/',$act_data['postcode']) ;

    if(count($tmp_array)==2){
      $sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($tmp_array[0]),prepare_mysql($tmp_array[0]));


      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']=$tmp_array[1];
      }

       $sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($tmp_array[1]),prepare_mysql($tmp_array[1]));
      

      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']=$tmp_array[0];
      }
    }elseif(count($tmp_array)==1){
      $sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s",prepare_mysql($tmp_array[0]),prepare_mysql($tmp_array[0]));


      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']='';
      }
	

    }
  }else{
    //    print_r($act_data);
    if(strtolower(_trim($act_data['country']))==strtolower(_trim($act_data['postcode'])))
      $act_data['postcode']='';
  }
  


    
    
  if($act_data['postcode']=="" and preg_match('/\s*\d{4,6}\s*/i',$act_data['town'],$match))
    {
  
      if($act_data['country']!="Netherlands"){
	$act_data['postcode']=_trim($match[0]);
	$act_data['town']=preg_replace('/\s*\d{4,6}\s*/','',$act_data['town']);
      }
    }


  if($act_data['a2']=='Ascheffel'){
    $act_data['town']='Ascheffel';
    $act_data['a2']='';
  }



 if(preg_match('/alicante/i',$act_data['country_d2']) and $act_data['country']==''  ){
    $act_data['country']='Spain';
    $act_data['country_d1']='Valencia';
  }

  if(preg_match('/Alfaz del Pi - Alicante/i',$act_data['town'])  ){
    $act_data['town']='Alfaz del Pi';
    $act_data['country_d2']='Alicante';
    $act_data['country_d1']='Valencia';
  }


 


  if(preg_match('/Viterbo/i',$act_data['town'])  and preg_match('/Soriano Nel Cimino/i',$act_data['a2']) ){
    $act_data['country']='Italy';
    $act_data['town']='Soriano Nel Cimino';
    $act_data['country_d1']='Lazio';
    $act_data['country_d2']='Viterbo';
    $act_data['a2']='';
    $act_data['postcode']='01028';
  }



if(preg_match('/^granada$/i',$act_data['town']) and $act_data['country']==''){
    $act_data['country']='Spain';
}




if($act_data['name']=='La Tasca de Oscar' and $act_data['contact']==''){
    $act_data['contact']='Rosa Amelia Fariña Rodriguez';

  }


if($act_data['name']=='Aventura2007s.c.p'){
  $act_data['name']='Aventura2007 S.C.P';
}
if($act_data['contact']=='Aventura2007s.c.p'){
  $act_data['contact']='Aventura2007 S.C.P';
}
    

if($act_data['name']=='0neida Beceira'){ $act_data['name']='Oneida Beceira';}
if($act_data['contact']=='0neida Beceira'){$act_data['contact']='Oneida Beceira';}


if($act_data['name']=='Kalamazad A'){ $act_data['name']='A Kalamazad';}
if($act_data['contact']=='Kalamazad A'){$act_data['contact']='A Kalamazad';}




if($act_data['name']=='0rganiza del principado S.L.'){
  $act_data['name']='Organiza del principado S.L.';
}





    if($act_data['name']=='Encarnación Jimenez Marquez' and $act_data['contact']=='0'){
    $act_data['name']='Encarnación Jimenez Marquez';
    $act_data['contact']='Encarnación Jimenez Marquez';
  }
  
    
  if(
  ($act_data['name']=='Virginia Cabrera Rivera' and $act_data['contact']=='David GTX')
  or ($act_data['name']=='Marisa Gómez' and $act_data['contact']=='Naturalmente')
  or ($act_data['name']=='Ignacio Galán Olaizola' and $act_data['contact']=='Mandala')
  or ($act_data['name']=='Sandra Romay Naixes' and $act_data['contact']=='Tribu´s')
  or ($act_data['name']=='Soledad Martin Santos' and $act_data['contact']=='Tu Luz')
  or ($act_data['name']=='Mari Carmen de La Muela Vega' and $act_data['contact']=='Joyeria Caro')
  or ($act_data['name']=='Aniceto de Leon Viñoly' and $act_data['contact']=='La Caja Roja')
  or ($act_data['name']=='Mº del Carmen López Carreira' and $act_data['contact']=='Eiroa-2')
  or ($act_data['name']=='Fermin Gutierrez' and $act_data['contact']=='Fermin')

  or ($act_data['name']=='Sonsoles Luque Delgado' and $act_data['contact']=='Isla Web')
  or ($act_data['name']=='Mercedes Manito Mantero' and $act_data['contact']=='Mercedes')

  or ($act_data['name']=='Adriana Ramos Ruiz' and $act_data['contact']=='Jaboneria')
  or ($act_data['name']=='Francisca Castillo Gil' and $act_data['contact']=='Bis a Bis')
  or ($act_data['name']=='Judit Plana Rodriguez' and $act_data['contact']=='Solluna')
 or ($act_data['name']=='Sylvie Felten' and $act_data['contact']=='Aparte')
 or ($act_data['name']=='Rosa Maria Moraleda Sanchez' and $act_data['contact']=='Miro 13')
 or ($act_data['name']=='Alberto Markuerkiaga Santiso' and $act_data['contact']=='Dra!')
 or ($act_data['name']=='Susana Rodriguez Lozano' and $act_data['contact']=='Mr Ayudas')
 or ($act_data['name']=='Juan Carlos Mirabal' and $act_data['contact']=='C')
 or ($act_data['name']=='Laudelina Saavedra Montesdeoca' and $act_data['contact']=='Herbolario Aguamar')
 or (preg_match('/Gina Younis Hevia/i',$act_data['name'])  and preg_match('/Gong Marbella/i',$act_data['contact']) )
 or (preg_match('/Maria Josefa Aparicio Arrebol/i',$act_data['name'])  and preg_match('/Duna/i',$act_data['contact']) )
 or (preg_match('/Marisa R/i',$act_data['name'])  and preg_match('/Ilusiones/i',$act_data['contact']) )
 or (preg_match('/Burgui/i',$act_data['name'])  and preg_match('/Burbuja/i',$act_data['contact']) )
  or (preg_match('/teteria|Herbolario|Perfumeria|Jauja|Herboristeria|El Rincon del Papi|Comercial Fermer.n|Ochun y Yemaya S.C.P.|Pompitas de |Artesan(í|i)a|Esoterico?|Craft Market|Artterapia|Centro De Estetica|Artesano Grabador de Vidrio|Psicolodia Logopedia Montserrat Baulenas|Centro Tiempo Crista|Mais Festa|Pompas de Jab.n|Q.guay\!/i',$act_data['contact']) )
  or (preg_match('/^Asociaci.n |^tienda |joyeria |Papeleria|^bazar|^restaurant|^el |^las |^los |^la /i',$act_data['contact']) )
  or (preg_match('/^(rayas|papel|Artesano|Gipp|La Mar de Cosas|Jabón Jabón|Angelus|Pompas|Jaboneria|Arfin|Samadhi|Zig Zag|Style|Salem|Videotarot|El duende|Sensual|Ariestética|Burbujitas|Chucotattoo|La Misma|D.e|Dunes|Dulce Pina|Naturshop|Amanatur S L|Lady Of the Stones|Splash|Fragancias|Lima Limon)$/i',$act_data['contact']) )
 or (preg_match('/^Mª /i',$act_data['name']) and  $act_data['contact']!='')
// or (preg_match('//i',$act_data['name'])  and preg_match('//i',$act_data['contact']) )
 //or ($act_data['name']=='' and $act_data['contact']=='')
  ){
   $_tmp=$act_data['name'];
   $act_data['name']=$act_data['contact'];
    $act_data['contact']=$_tmp;
  }







    
  $extra_contact=false;
  if($act_data['contact']!=''){

    $_contact=$act_data['contact'];
    $split_names=preg_split('/\s+and\s+|\&|\/|\s+or\s+/i',$act_data['contact']);
    if(count($split_names)==2){
      $split_names1=preg_split('/\s+/i',$split_names[0]);
      $split_names2=preg_split('/\s+/i',$split_names[1]);
      if(count($split_names1)==1 and count($split_names2)==2 ){
	$name1=$split_names1[0].' '.$split_names2[1];
	$name2=$split_names[1];
      }else{
	$name1=$split_names[0];
	$name2=$split_names[1];
      }
      $act_data['contact']=$name1;
      $extra_contact=$name2;
      if($_contact==$act_data['name']){
	$act_data['name']=preg_replace('/\s+and\s+|\&|\/|\s+or\s+/i',' & ',$act_data['name']);
      }

    }
    $there_is_contact=true;
  }else{
    $there_is_contact=false;
    if(!preg_match('/C \& P Trading|Peter \& Paul Ltd|Health.*Beauty.*Salon|plant.*herb|Amanatur S L/i',$act_data['name']))
      $act_data['contact']=$act_data['name'];
    if(!preg_match('/^(pompas)$/i',$act_data['name']))
      $act_data['contact']=$act_data['name'];

   

  }




 

  if($act_data['name']=='Jill Clare' and  $act_data['contact']=='Jill Clare'){
      $tipo_customer='Company';
      $act_data['contact']='';
    }
 


 

  $tmp_array=array('Burbujas Online S.L.','Sona Florida S.L.L.','Fisioglobal SCP','Naturshop','Amanatur S L');
  foreach($tmp_array as $__name){
    if($act_data['name']==$__name and $act_data['contact']==$__name  ){
      $tipo_customer='Company';
     $act_data['contact']='';
     
    }
  }

 
 
 


  $act_data['name']=preg_replace('/^m\.angeles /i','Mª Angeles ',$act_data['name']);

  $act_data['contact']=preg_replace('/^m\.angeles /i','Mª Angeles ',$act_data['contact']);
  

  $act_data['name']=preg_replace('/,? (S\s*L\.|S\.L\.|S\s*\.\s*L|SL)$/i',' S.L.',$act_data['name']);
  $act_data['name']=preg_replace('/\,? (Slu)$/i',' S.L.U.',$act_data['name']);
// $act_data['name']=preg_replace('/\,? (Slu)$/i',' S.L.U.',$act_data['name']);

  $act_data['name']=preg_replace('/ (S\s*C\.|S\.C\.|S\.C|SC)$/i',' S.C.',$act_data['name']);
  $act_data['name']=preg_replace('/ (s\.L\s*L|SLL|S\s*L\.L\.|S\.L\.L\.|S\.LL)$/i',' S.L.L.',$act_data['name']);
  $act_data['name']=preg_replace('/ (S\s*a\.|S\.a\.|S\.a|Sa|s\.a)$/i',' S.A.',$act_data['name']);
  $act_data['name']=preg_replace('/ (C\s*B\.|C\.B\.|C\.B|CB)$/i',' C.B.',$act_data['name']);
  $act_data['name']=preg_replace('/,\s*(C\s*B\.|C\.B\.|C\.B|CB)$/i',' C.B.',$act_data['name']);
  $act_data['name']=preg_replace('/ (-?\s*L\.da|LDA|l\.d\.a)$/i',' L.D.A.',$act_data['name']);
  $act_data['name']=preg_replace('/,\s*(-?\s*L\.da|LDA|l\.d\.a)$/i',' L.D.A.',$act_data['name']);
  $act_data['name']=preg_replace('/ (s\.?\s*c\.?\s*p)$/i',' S.C.P.',$act_data['name']);
$act_data['name']=preg_replace('/ S.l.n.e$/i',' S.L.N.E.',$act_data['name']);
  $act_data['name']=preg_replace('/ S\.?l\.?u\.?$/i',' S.L.U.',$act_data['name']);


if ($act_data['name']==$act_data['contact'] and $act_data['contact']!='') {
  if (preg_match('/^Bazar |^Alta Bisuteria | shop$|^Perfumer.a |Sociedad Cooperativa|souvenirs|^supermercados |^bisuteria | hoteles?$|^hotels? |^eventos |^terra |Avenue de |\d|^equilibrio |^la estrella |^verde |complementos |^joyeria |^regalos |bisiter.a|est.tica|peluquer.a|yoga |el zoco|jabones|S\.L\.$|Ldª$| SL$|Herboristeria|Asoc\. |^Asociaci.n |^Centro |^FPH C\.B\.$|Fisioglobal|^Amigos de | S\.A\.$|Associació Cultural|Associaci.n Cultural| C\.B$|^Asociación [a-z]+$| S\.A\.$| S\.C\.?$|Sucrolotes SLL - La Guinda| C\.B\.?$|lenilunio S\.c\.a$|^Laboratorios |Burbujas Castellón|^Rama SC$| S\.L\.?$| S\.l\.n\.e\.?$| s\.c\.a\.?$|Tecnologias|^Papeleria | S\.L\.U\.$| L\.D\.A\.$| C\.B\.$| S\.L\.L\.$/i',$act_data['name'])) {
    $act_data['contact']='';
    
  }elseif (preg_match('/^(centro)\s+|Publicidad/i',$act_data['name'])) {
    $act_data['contact']='';
  }elseif (preg_match('/^(Fgdf|poeme|Populi|Minerales Porto Pi|Servi Print|Prince|Prysma|Carros Publicidad|Objetivo Publicidad|Publiexpress|Puerimueble|Plata Punto Com|puri|Que Punto|Expo Regalo|Expo Regalo|Don Regalo|Scruples|Scruples|seducir|Si Tu Me Dices Ven |Sol y Sol|sp|spiral|Britt-Inger St|Sthmuck|star|Dream Store|Struch|stylo|Sueños|Sunmarine|Supercien|Mai Tai|tayhe|tagore|tamy|tanisa|tauros?|aries|capricornio|Tayhe|Modas Teis|Temporada|Tendencia|they|La Tienda de Merche|Artemaniashop|arrumaco|Bolsos Arpel|arrels|Electro Aroche|Aroa y Maria del Mar|Armonia|Arlequin|Tele Arcos|archi|arco|Arantxa Bisuteria, Regalos y Complementos|Antiquo|Alhambra|Albutt|Alanb|Elemento Agua|Aguamarina|Acuario|Africa|Acuario|Acuarela|Accessorize|Accessoris|Molts Accesoris|Aires De Mexico|Al Tuntun|Al Tun Tun|Laboladecristal|Gretel|Garcivera|S Espay|Ambar Diseño|Concha y Carlos|amina|Amica|America|Ameica|ambar|Amas de Casa Virgen del Carmen |Altieri|Alternativa|Alquimia|signa|Shiam|Singular|Sol y Luna - La Tienda de Mayca|Soyzoe|Splin|Spleen|Etetica Suvita|para ti|thot|tgoreti|el tintero|la tinaja|de todo|top|toke|etnia|a tope|topaz|toque|Un Toque de Estilo|tosca|tasca|toten|totem|touch|Abalorios Trini|La Traperia de Hellin|utop.a|venus|verdi|Art I vi|tigre volador|Walkiria|Waleska|Watermelon|Xarxa|Xaica|Xacris|Whatever|Waza|HM Woman|Interbisu Xxi|Yoryera|zeppo|yerba|yesi|zeida|zaguan|azahar|zaloa|zaleos|yuca|zurron|Fengzhu Zhu|Zidarra|De Zeta|)$/i',$act_data['name'])) {
	$act_data['contact']='';
 }elseif (preg_match('/^(la|el|los|las|spa|tele) /i',$act_data['name'])) {
	$act_data['contact']='';
 }
 
 
}

    

 //  print_r($act_data);
  
  // print_r($header_data);

  //-----------------------------------------
  if(!isset($act_data['town_d1']))
    $act_data['town_d1']='';
  if(!isset($act_data['town_d2']))
    $act_data['town_d2']='';

  if(preg_match('/^c\/o/i',$act_data['a1'])){
    $co=$act_data['a1'];
    $act_data['a1']='';
  }
  if(preg_match('/^c\/o/i',$act_data['a2'])){
    $co=$act_data['a2'];
    $act_data['a2']='';
  }
  if(preg_match('/^c\/o/i',$act_data['a3'])){
    $co=$act_data['a3'];
    $act_data['a3']='';
  }

  

  return $act_data;
}



?>