<?php
error_reporting(E_ALL);

include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Order.php');
include_once('../../class.Store.php');

include_once('../../class.Invoice.php');
include_once('../../class.DeliveryNote.php');
include_once('../../class.Email.php');
include_once('../../class.TimeSeries.php');
include_once('../../class.CurrencyExchange.php');
//include_once('map_order_functions.php');
include_once('common_read_orders_functions.php');

function microtime_float() {
    list($utime, $time) = explode(" ", microtime());
    return ((float)$utime + (float)$time);
  }



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}
$tipo_his=array();
$myFile = "act_timedata.txt";
$fh = fopen($myFile, 'w') or die("can't open file");


date_default_timezone_set('UTC');
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
$convert_encoding=false;
include_once('local_map.php');


$store_data=array('Store Code'=>'UK',
                  'Store Name'=>'Ancient Wisdom',
                  'Store Locale'=>'en_GB',
                  'Store Home Country Code 2 Alpha'=>'GB',
                  'Store Currency Code'=>'GBP',
                  'Store Home Country Name'=>'United Kingdom',
                  'Store Home Country Short Name'=>'UK',
                 );
//$store=new Store('find',$store_data,'create');
$store=new Store(1);

if (!$store->id) {
//print_r($store);
    exit("can not create store\n");
}
$map_act=$_map_act;
$map_act[90]='creation_date';

$filename="actdatatmp.txt";
$row = 0;
$contacts=array();
$contacts_date=array();
if (($handle = fopen($filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, filesize($filename), ",")) !== FALSE) {
        $num = count($data);

        print "$num $row\r";
        if ($num!=105)
            break;

        if ($row==0) {
            //   print_r($data);exit;
            $row++;
            continue;
        }
        $row++;;

        $cols=array();
        foreach($data as $key=>$col) {
            if ($convert_encoding)
                $cols[$key]=mb_convert_encoding($col, "UTF-8", "ISO-8859-1");
            else
                $cols[$key]=$col;
        }



        $act_data=array();
        $act_data['name']=mb_ucwords($cols[$map_act['name']+3]);


        $act_data['contact']=mb_ucwords($cols[$map_act['contact']+3]);

        //if ($act_data['name']=='' and $act_data['contact']!='') // Fix only contact
        //    $act_data['name']=$act_data['contact'];


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

        $act_data['takenby']=$cols[41-5];

        $act_data['mob']=$cols[$map_act['mob']+3];
        $act_data['source']=$cols[$map_act['source']+3];
        $act_data['act']=$cols[$map_act['act']+3];
        $act_data['email']=$cols[96];
        $act_data['source']=$cols[25+3];
        $act_data['category']=$cols[27+3];
        $act_data['pay_method']=$cols[37+3];
        $act_data['history']=$cols[97];
        $act_data['creator']=$cols[68];
        $act_data['international_email']=$cols[$map_act['int_email']+3];
        $act_data['tax_number']=parse_tax_number($cols[$map_act['real_tax_number']+3]);


        //    print $cols[92]."\n";

        $act_data['country_d1']='';
        //  $act_data['vat_number']=$cols[88+3];
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
        $history_data=get_history_data($act_data['history']);
        $act_data['history']=$history_data;

        //print_r($history_data);

        if ($act_data['name']=='' and $act_data['contact']=='' and $act_data['email']=='' and $act_data['tel']==''
                and $act_data['fax']==''
                and $act_data['mob']==''

           ) {

            continue;
        }



        $contacts[$row]=$act_data;
        $contacts_date[$row]=$creation_time;

        // if($act_data['tax_number']!='')
        //  print ($act_data['tax_number']."\n");
         //     if($row>200)
         //  break;
        //      print "$row\r";

        // print_r($cols);

        //    print "==============================\n".$act_data['history']."\n";


        // print_r($history_data);


    }
    fclose($handle);
}
//print_r($contacts);

usort($contacts, 'compare');
//print_r($contacts);
//exit("fin\n");

//$fp = fopen('contacts_act_file.csv', 'w');
//foreach ($contacts as $line) {    fputcsv($fp, $line);}
//fclose($fp);
//print "\n";


$time_data=array();
$contador=0;
foreach($contacts as $act_data) {
    $base_time=microtime_float();
            $contador++;


    //  print "$contador\r";

// if(strtotime($act_data['creation_date'])<=strtotime('2006-10-22'))
//   continue;

//print_r($act_data);
    if ($act_data['name']!='' and $act_data['contact']!='') {
        $tipo_customer='Company';
        if ($act_data['name']==$act_data['contact'] ) {
            $person_factor=is_person($act_data['name']);
            $company_factor=is_company($act_data['name']);
            if ($company_factor>$person_factor) {
                $tipo_customer='Company';
                $act_data['contact']='';
            } else {
                $tipo_customer='Person';
                $act_data['name']='';
            }

        } else {
            $company_person_factor=is_person($act_data['name']);
            $company_company_factor=is_company($act_data['name']);
            $person_company_factor=is_company($act_data['contact']);
            $person_person_factor=is_person($act_data['contact']);

            if ($person_company_factor>$person_person_factor or $company_person_factor>$company_company_factor) {
                $_name=$act_data['name'];
                $act_data['name']=$act_data['contact'];
                $act_data['contact']=$_name;
            }



        }


    }
    elseif($act_data['name']!='') {
        $tipo_customer='Company';
        $company_person_factor=is_person($act_data['name']);
        $company_company_factor=is_company($act_data['name']);

        if ( $company_person_factor>$company_company_factor) {
            $tipo_customer='Person';
            $_name=$act_data['name'];
            $act_data['name']=$act_data['contact'];
            $act_data['contact']=$_name;
        }


    }
    elseif($act_data['contact']!='') {
        $tipo_customer='Person';
        $person_company_factor=is_company($act_data['contact']);
        $person_person_factor=is_person($act_data['contact']);

        if ($person_company_factor>$person_person_factor ) {
            $tipo_customer='Company';
            $_name=$act_data['name'];
            $act_data['name']=$act_data['contact'];
            $act_data['contact']=$_name;
        }


    }
    else {
        $tipo_customer='Person';

    }







    $email_data=guess_email($act_data['email']);

    if (!isset($act_data['town_d1']))
        $act_data['town_d1']='';
    if (!isset($act_data['town_d2']))
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
    if (isset($act_data['country_d1']))
        $address_raw_data['country_d1']=$act_data['country_d1'];


    $shop_address_data=$address_raw_data;
    $_customer_data=array();
    $_customer_data['Customer Old ID']=$act_data['act'];
    $_customer_data['type']=$tipo_customer;
    $_customer_data['date_created']=$act_data['creation_date'];
    $_customer_data['contact_name']=$act_data['contact'];
    $_customer_data['company_name']=$act_data['name'];
    $_customer_data['email']=$email_data['email'];



    $customer_data['Customer Store Key']=1;
    if (preg_match('/aw-geschenke/i',$act_data['source'])){
 $store=new Store('code','DE');     
   $customer_data['Customer Store Key']=$store->id;

    }if (preg_match('/nabil/i',$act_data['takenby'])){
      $store=new Store('code','FR');     
   $customer_data['Customer Store Key']=$store->id;
    }





    if ($customer_data['Customer Store Key']!=1)
        $_customer_data['email']=$act_data['international_email'];


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

    $_customer_data['Customer Tax Number']=$act_data['tax_number'];




    // print_r($_customer_data);


    //if(isset($header_data['tax_number']) and $header_data['tax_number']!=''){
    //  $customer_data['Customer Tax Number']=$header_data['tax_number'];
    // }

    foreach($_customer_data as $_key =>$value) {
        $key=$_key;
        if ($_key=='type')
            $key=preg_replace('/^type$/','Customer Type',$_key);
        if ($_key=='other id')
            $key='Customer Old ID';
        if ($_key=='contact_name')
            $key=preg_replace('/^contact_name$/','Customer Main Contact Name',$_key);
        if ($_key=='company_name')
            $key=preg_replace('/^company_name$/','Customer Company Name',$_key);
        if ($_key=='email')
            $key=preg_replace('/^email$/','Customer Main Plain Email',$_key);
        if ($_key=='telephone')
            $key=preg_replace('/^telephone$/','Customer Main XHTML Telephone',$_key);
        if ($_key=='fax')
            $key=preg_replace('/^fax$/','Customer Main XHTML FAX',$_key);
        if ($_key=='mobile')
            $key=preg_replace('/^mobile$/','Customer Mobile',$_key);

        $customer_data[$key]=$value;

    }
    if ($customer_data['Customer Type']=='Company')
        $customer_data['Customer Name']=$customer_data['Customer Company Name'];
    else
        $customer_data['Customer Name']=$customer_data['Customer Main Contact Name'];


    if (isset($_customer_data['address_data'])) {






        //continue;
        $customer_data['Customer First Contacted Date']=$act_data['creation_date'];



        $customer_data['Customer Address Line 1']=$_customer_data['address_data']['address1'];
        $customer_data['Customer Address Line 2']=$_customer_data['address_data']['address2'];
        $customer_data['Customer Address Line 3']=$_customer_data['address_data']['address3'];
        $customer_data['Customer Address Town']=$_customer_data['address_data']['town'];
        $customer_data['Customer Address Postal Code']=$_customer_data['address_data']['postcode'];
        $customer_data['Customer Address Country Name']=$_customer_data['address_data']['country'];
        $customer_data['Customer Address Country First Division']=$_customer_data['address_data']['country_d1'];
        $customer_data['Customer Address Country Second Division']=$_customer_data['address_data']['country_d2'];
        unset($customer_data['address_data']);
    }
    $shipping_addresses=array();
    $customer_data['Customer Delivery Address Link']='Contact';

    if ($customer_data['Customer Main Contact Name']!='' or $customer_data['Customer Company Name']!='' ) {
        //print_r($_customer_data);
        // print_r($customer_data);
        // exit;
    }

    // print_r($act_data);

    $customer = new Customer ( 'find create',  $customer_data);

    //   if(count($act_data['history'])>0){
    //  print "Customer ".$customer->id." with History\n\n\n\n\n\n";
    //  print_r($act_data['history']);
    // }

    foreach($act_data['history'] as $h_tipo=>$histories) {
        if ($h_tipo=='Note')
            foreach($histories as $date=>$history) {
            $customer->add_note($history,'',$date);
        } else {
            foreach($histories as $date=>$history) {
                $customer->add_note("Old Database Note ($h_tipo)",$history,$date);
            }
        }
    }


$time_data[]=microtime_float()-$base_time;
   if(fmod($contador,100)==0){
    list($min,$avg,$max)=get_time_averages($time_data);
     $stringData="$contador $min $avg $max\n";
    
    fwrite($fh, $stringData);

    $time_data=array();
    }
    
    


    //print "caca";
    //print_r($customer);

}

fclose($fh);

function get_time_averages($data){
$bins=count($data);
$min=9999999999;
$max=-9999999999;
$sum=0;
foreach($data as $value){
    if($value<$min)
        $min=$value;
    if($value>$max)
        $max=$value;
    $sum+=$value;    
}
return array($min,$sum/$bins,$max);

}



function compare($x, $y) {
    if ( $x['creation_datetimestap'] == $y['creation_datetimestap'] )
        return 0;
    else if ( $x['creation_datetimestap'] < $y['creation_datetimestap'] )
        return -1;
    else
        return 1;
}


function get_history_data($raw_history) {
    global $tipo_his;


    $history=array('Field Changed'=>array(),'Note'=>array(),'E-mail Sent'=>array(),'Attachment'=>array(),'Contact Deleted'=>array(),'To-do Done'=>array(),'Call Completed'=>array(),'To-do Not Done'=>array());

    $history=array();


    if ($raw_history=='')
        return $history;

    $date_separator='/\d{2}\/\d{2}\/\d{4}\s\d{2}\:\d{2}\:\d{2}\s-------------------------------------------\s/';

    $date_splited=preg_split($date_separator,$raw_history);
    unset($date_splited[0]);
    // print_r($date_splited);

    foreach($date_splited as $y) {
        $x=preg_split('/\s+\-\s+/',$y);
        $tipo_his[$x[0]]=1;
    }

    preg_match_all($date_separator,$raw_history, $_dates);
    //print_r($_dates);
    $dates=array();
    foreach($_dates[0] as $_tmp) {


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
    foreach($date_splited as $index=>$y) {
        $x=preg_split('/\s+\-\s+/',$y);
        $tipo=$x[0];
        $note=preg_replace("/^$tipo -/",'',$y);



        if (isset($history[$tipo][$dates[$index-1]]))
            $history[$tipo][$dates[$index-1]].=";\n".$note;
        else
            $history[$tipo][$dates[$index-1]]=$note;






    }

    //print_r($dates);
    return $history;

}



function is_person($name) {
    $company_suffix="L\.?T\.?D\.?";
    $company_prefix="The";
    $company_words=array('Gifts','Chemist','Pharmacy','Company','Business');
    $name=_trim($name);
    $probability=1;
    if (preg_match('/\d/',$name)) {
        $probability*=0.00001;
    }
    if (preg_match("/\s+".$company_suffix."$/",$name)) {
        $probability*=0.001;
    }
    if (preg_match("/\s+".$company_prefix."$/",$name)) {
        $probability*=0.001;
    }
    // print_r($company_words);
    foreach($company_words as $word) {
        if (preg_match("/\b".$word."\b/i",$name)) {
            $probability*=0.01;
        }
    }


    return $probability;

}

function is_company($name) {

    $name=_trim($name);
    global $person_prefix;
    $probability=1;

    if (preg_match("/^".$person_prefix."\s+/",$name)) {
        $probability*=0.01;
    }
    $components=preg_split('/\s/',$name);


    if (count($components)>1) {
        $has_sal=false;
        $saludation=preg_replace('/\./','',$components[0]);
        $sql=sprintf('select `Salutation Key` from kbase.`Salutation Dimension` where `Salutation`=%s  ',prepare_mysql($saludation));
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $probability*=0.9;
        }



    }



    if (count($components)==2) {
        $name_ok=false;
        $surname_ok=false;
        $sql=sprintf('select `First Name Key` from kbase.`First Name Dimension` where `First Name`=%s  ',prepare_mysql($components[0]));
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $name_ok=true;
        }
        $sql=sprintf('select `Surname Key` from kbase.`Surname Dimension` where `Surname`=%s  ',prepare_mysql($components[1]));
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $surname_ok=true;
        }
        if ($surname_ok and $name_ok) {
            $probability*=0.75;
        }
        if ($name_ok) {
            $probability*=0.95;
        }
        if ($surname_ok) {
            $probability*=0.95;
        }

        if (strlen($components[0])==1) {
            $probability*=0.95;
        }



    }
    elseif(count($components)==3) {

        $name_ok=false;
        $surname_ok=false;
        $sql=sprintf('select `First Name Key` from kbase.`First Name Dimension` where `First Name`=%s  ',prepare_mysql($components[0]));
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $name_ok=true;
        }
        $sql=sprintf('select `Surname Key` from kbase.`Surname Dimension` where `Surname`=%s  ',prepare_mysql($components[2]));
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $surname_ok=true;
        }
        if ($surname_ok and $name_ok) {
            $probability*=0.75;
        }
        if ($name_ok) {
            $probability*=0.95;
        }
        if ($surname_ok) {
            $probability*=0.95;
        }

        if (strlen($components[1])==1) {
            $probability*=0.95;
        }

        if (strlen($components[1])==1 and strlen($components[0])==1 ) {
            $probability*=0.99;
        }

    }


    return $probability;
}




?>