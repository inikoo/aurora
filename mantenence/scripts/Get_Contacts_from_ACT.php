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
include_once('../../class.Category.php');
include_once('../../class.Node.php');

//include_once('map_order_functions.php');
include_once('common_read_orders_functions.php');

function microtime_float() {
    list($utime, $time) = explode(" ", microtime());
    return ((float)$utime + (float)$time);
}

$encrypt=false;

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

$filename="actdatatmp.txt";


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
$convert_encoding=true;
include_once('local_map.php');




$sql=sprintf("select `Store Key`,`Store Code` from `Store Dimension`");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
 
    if ($row['Store Code']=='UK') {
        $valid_sub_cats_referrals[$row['Store Key']]=array('StartUpPlus','Referral','Google','Ancient Wisdom','Other','Bing','Craft Focus Magazine','Facebook','Garden Shop Catalogue','Gift Focus Magazine','Gifts Today','Giftware Index','Giftware Review','Heritage Shop Catalogue','Market Times','MTN','Progressive Gifts','The Trader Magazine','The Trader Website','The Wholesaler Website','Twitter','Yahoo');
       
    } else {
        $valid_sub_cats_referrals[$row['Store Key']]=array('Referral','Google','Bing','Yahoo','Other');
       

    }

    $valid_sub_cats_type_bussiness[$row['Store Key']]=array('Gift Shop','Internet Shop','Market Trader','Party Planner','Craft Fairs','Tourist Attraction','Wedding Planner','Wholesaler','Department Store','Florist','Ebay Seller','Garden Centre','NPO','Hospitality Industry','Therapist','Event','Other');
   

}










$map_act=$_map_act;
$map_act[90]='creation_date';


$row = 0;
$contacts=array();
$contacts_date=array();
if (($handle = fopen($filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, filesize($filename), ",")) !== FALSE) {
        $num = count($data);

        print "$num $row\r";

        //    if($row>1000)
        //       break;

        if ($num!=105)
            break;

        if ($row==0) {
            //   print_r($data);exit;
            $col_names=$data;

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

        $act_data['business_type']=mb_ucwords($cols[$map_act['business_type']]);
        $act_data['where_find_us']=mb_ucwords($cols[$map_act['where_find_us']]);


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
        $act_data['alt_email']=$cols[$map_act['alt_email']];



        $act_data['tax_number']=parse_tax_number($cols[$map_act['real_tax_number']+3]);
        $act_data['delivery_method']=$cols[88];
        $act_data['special_instructions']=$cols[89];

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
        $history_data=get_history_data($act_data['history'],$act_data['creation_date']);
        $act_data['history']=$history_data;
        $act_data['all_data']=$cols;

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
          if($row>5000)
           break;
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
$start_time=microtime_float();
foreach($contacts as $act_data_contact_key=>$act_data) {
    $base_time=microtime_float();
    $contador++;


    //  print "$contador\r";

// if(strtotime($act_data['creation_date'])<=strtotime('2006-10-22'))
//   continue;

//print_r($act_data);

//===XXxxxXXX

    list($tipo_customer,$act_data['name'],$act_data['contact'])=parse_company_person($act_data['name'],$act_data['contact']);

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








    $_customer_data=array();
    $_customer_data['Customer Old ID']=$act_data['act'];
    $_customer_data['type']=$tipo_customer;
    $_customer_data['date_created']=$act_data['creation_date'];
    $_customer_data['contact_name']=$act_data['contact'];
    $_customer_data['company_name']=$act_data['name'];
    $_customer_data['email']=encrypt_email($email_data['email'],$encrypt);

    $fr_customer=false;

    $de_customer=false;
    $customer_data['Customer Store Key']=1;

    if (preg_match('/aw-geschenke/i',$act_data['source'])) {
        $store=new Store('code','DE');
        $customer_data['Customer Store Key']=$store->id;
        $de_customer=true;
        if ($address_raw_data['country']=='')
            $address_raw_data['country']='Germany';
    }
    elseif (preg_match('/nabil/i',$act_data['takenby'])
            or ( $act_data['international_email']!='' and
                 (  preg_match('/cadeaux/i',$act_data['source'])  or preg_match('/^(fr|france)$/i',$act_data['source']))
               )

           ) {
        $store=new Store('code','FR');
        $customer_data['Customer Store Key']=$store->id;
        $fr_customer=true;
        if ($address_raw_data['country']=='')
            $address_raw_data['country']='France';

    }
    else {
        if ($address_raw_data['country']=='')
            $address_raw_data['country']='United Kingdom';

        //  continue;
    }
    
    


    $shop_address_data=$address_raw_data;



    $send_emails=true;


  //if ($customer_data['Customer Store Key']!=1)
   // continue;

    if ($customer_data['Customer Store Key']!=1)
        $_customer_data['email']=$act_data['international_email'];
    else {
        if ($_customer_data['email']=='' and $act_data['alt_email']!='') {
            $email_data=guess_email($act_data['alt_email']);

            if ($email_data['email']) {
                $_customer_data['email']=encrypt_email($email_data['email'],$encrypt);
                $send_emails=false;

            }
        }
    }



    $_customer_data['telephone']=encrypt_tel(_trim($act_data['tel']),$encrypt);
    $_customer_data['fax']=encrypt_tel(_trim($act_data['fax']),$encrypt);



    $_customer_data['mobile']=encrypt_tel(_trim($act_data['mob']),$encrypt);
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



//print_r($act_data);
    //print_r($_customer_data);
//exit;

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
            $key=preg_replace('/^telephone$/','Customer Main Plain Telephone',$_key);
        if ($_key=='fax')
            $key=preg_replace('/^fax$/','Customer Main Plain FAX',$_key);
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
    $customer_data['Customer Billing Address Link']='Contact';

    if ($customer_data['Customer Main Contact Name']!='' or $customer_data['Customer Company Name']!='' ) {
        //print_r($_customer_data);
        // print_r($customer_data);
        // exit;
    }

    // print_r($act_data);

    //  if($customer_data['Customer Main Plain Email']=='')
    //    continue;

    $customer_data['Customer Sticky Note']='';



    if ($act_data['delivery_method']!='') {
        $customer_data['Customer Sticky Note']='Delivery Method: '.$act_data['delivery_method']."<br/>";
    }
    if ($act_data['special_instructions']!='') {
        $customer_data['Customer Sticky Note'].='Special Instructions: '.$act_data['special_instructions']."<br/>";
    }



    $remove_customer=false;
    if (preg_match('/^(Closed Acc|Stopped Trading|Businees Closed Down|Closed Business|Not Trading-closed Down|Ceased Business|Bisiness Sold|Out OF Business|Closing Business|Blacklisted\! Ah|Fraud \- Dont Deal With|No Longer In Business|Close Down)/i',$act_data['business_type'])) {
        $remove_customer=true;
        $customer_data['Customer Sticky Note'].='Business Closed Down<br/>';
    }


    $customer_data['Customer Send Postal Marketing']='Yes';
    if (preg_match('/^(Don.t Send Catalogues)/i',$act_data['business_type']) or $remove_customer) {
        $customer_data['Customer Send Postal Marketing']='No';
    }

    if ($send_emails) {
        $customer_data['Customer Send Newsletter']='Yes';
        $customer_data['Customer Send Email Marketing']='Yes';

    } else {
        $customer_data['Customer Send Newsletter']='No';
        $customer_data['Customer Send Email Marketing']='No';

    }

    $remove_address=false;
    if (preg_match('/(Poss Gone Away|Remove From Aw Mailing|Gone Away|Wrong Address|Unknown AT This Address)/i',$act_data['business_type'])) {
        $remove_address=true;
        $customer_data['Customer Sticky Note'].='Wrong Address<br/>';
        $customer_data['Customer Send Postal Marketing']='No';

    }


    if (preg_match('/(Difficult|Customer \- DIF|Very Fussy)/i',$act_data['business_type'])) {
        $remove_address=true;
        $customer_data['Customer Sticky Note'].='Difficult Customer<br/>';
    }
    if (preg_match('/(dishonest|Blacklisted)/i',$act_data['business_type'])) {
        $remove_address=true;
        $customer_data['Customer Sticky Note'].='Dishonest Customer<br/>';
    }

    $customer_data['Customer Sticky Note']=preg_replace('/\<br\/\>$/','',$customer_data['Customer Sticky Note']);

    //print_r($customer_data);
//exit;
    $customer = new Customer ( 'find create update',  $customer_data);


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




    if ($customer->data['Customer Store Key']==1) {

        $act_data['business_type']=mb_ucwords($act_data['business_type']);
        if (preg_match('/(Carboot Sales)/i',$act_data['business_type']))
            $act_data['business_type']='Market Trader';
        if (preg_match('/(school|church|charity)/i',$act_data['business_type']))
            $act_data['business_type']='NPO';
        if (preg_match('/(gifts? shop)/i',$act_data['business_type']))
            $act_data['business_type']='Gift Shop';
        if (!preg_match('/^wedding planner$/i',$act_data['business_type'])) {
            if (preg_match('/^(wedding|Christening|Special Occasion|For Event|One Off)$/i',$act_data['business_type']))
                $act_data['business_type']='Event';

        }
        if (preg_match('/B\&b|Restaurant/i',$act_data['business_type'])) {
            $act_data['business_type']='Hospitality Industry';

        }



        if ($act_data['business_type']!=''  and  in_array($act_data['business_type'],  $valid_sub_cats_type_bussiness[$customer->data['Customer Store Key']]) ) {
          //  $data=array('Category Code'=>$act_data['business_type'],'Category Subject'=>'Customer','Category Parent Key'=>$cat_type_business[$customer->data['Customer Store Key']]->id,'Category Store Key'=>$customer->data['Customer Store Key']);
            $subcat_type_business=new Category('name_store',$act_data['business_type'],$customer->data['Customer Store Key']);

            $sql=sprintf("delete CB.* from `Category Bridge` as CB left join `Category Dimension` C on (C.`Category Key`=CB.`Category Key`)  where `Category Parent Key`=%d and `Subject`=%s and `Subject Key`=%d",
                         $subcat_type_business->data['Category Parent Key'],
                         prepare_mysql('Customer'),
                         $customer->id
                        );
            mysql_query($sql);

            $sql=sprintf("insert into `Category Bridge` values (%d,%s,%d)",
                         $subcat_type_business->id,
                         prepare_mysql('Customer'),
                         $customer->id
                        );
            mysql_query($sql);
        }


        $act_data['where_find_us']=mb_ucwords($act_data['where_find_us']);
        if (preg_match('/(startup|starplus|Startsplus)/i',$act_data['where_find_us']))
            $act_data['where_find_us']='StartUpPlus';
        if (preg_match('/(referal|referral|Refferal)/i',$act_data['where_find_us']))
            $act_data['where_find_us']='Referral';
        if (preg_match('/(Ancient Wisdiom|Ancien Wisdom|Ancient Wisdiom)/i',$act_data['where_find_us']))
            $act_data['where_find_us']='Ancient Wisdom';
        if (preg_match('/^Heritage$/i',$act_data['where_find_us']))
            $act_data['where_find_us']='Heritage Shop Catalogue';
        if (preg_match('/Giftwareindex/i',$act_data['where_find_us']))
            $act_data['where_find_us']='Giftware Index';

        if ($act_data['where_find_us']!=''   
            and  in_array($act_data['where_find_us'],  $valid_sub_cats_referrals[$customer->data['Customer Store Key']]) ) {
            /*$data=array(
                'Category Code'=>$act_data['where_find_us'],
                'Category Subject'=>'Customer',
                'Category Parent Key'=>$cat_referrer[$customer->data['Customer Store Key']]->id,
                'Category Store Key'=>$customer->data['Customer Store Key']);
*/
            $subcat_type_referrer=new Category('name_store',$act_data['where_find_us'],$customer->data['Customer Store Key']);
            if ($subcat_type_referrer->id) {
                $sql=sprintf("delete CB.* from `Category Bridge` as CB left join `Category Dimension` C on (C.`Category Key`=CB.`Category Key`)  where `Category Parent Key`=%d and `Subject`=%s and `Subject Key`=%d",
                             $subcat_type_referrer->data['Category Parent Key'],
                             prepare_mysql('Customer'),
                             $customer->id
                            );
                mysql_query($sql);

                $sql=sprintf("insert into `Category Bridge` values (%d,%s,%d)",
                             $subcat_type_referrer->id,
                             prepare_mysql('Customer'),
                             $customer->id
                            );
                mysql_query($sql);
            }

        }
    }
    // print_r($act_data);
//exit;
//print_r($customer);

    //   if(count($act_data['history'])>0){
    //  print "Customer ".$customer->id." with History\n\n\n\n\n\n";
    //  print_r($act_data['history']);
    // }

    // $store->update_customers_data();
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


    print "Customers:\t$contador\t".number_format(microtime_float()-$base_time,4)."\t Avg:".number_format( (microtime_float()-$start_time)/$contador,4)."s/c \r";

    $time_data[]=microtime_float()-$base_time;
    if (fmod($contador,100)==0) {
        list($min,$avg,$max)=get_time_averages($time_data);
        $stringData="$contador $min $avg $max\n";

        fwrite($fh, $stringData);

        $time_data=array();
    }




    //print "caca";
    //print_r($customer);
    unset($contacts[$act_data_contact_key]);
}

fclose($fh);

function get_time_averages($data) {
    $bins=count($data);
    $min=9999999999;
    $max=-9999999999;
    $sum=0;
    foreach($data as $value) {
        if ($value<$min)
            $min=$value;
        if ($value>$max)
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


function get_history_data_old($raw_history,$customer_creation_formated_time) {
    global $tipo_his;
    $customer_creation_time=strtotime($customer_creation_formated_time);

    $history=array('Field Changed'=>array(),'Note'=>array(),'E-mail Sent'=>array(),'Attachment'=>array(),'Contact Deleted'=>array(),'To-do Done'=>array(),'Call Completed'=>array(),'To-do Not Done'=>array());

    $history=array();


    if ($raw_history=='')
        return $history;

    $date_separator='/\d{2}\/\d{2}\/\d{4}\s\d{2}\:\d{2}\:\d{2}\s-------------------------------------------\s/';

    $date_splited=preg_split($date_separator,$raw_history);
    unset($date_splited[0]);
    //print_r($date_splited);

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


        if (date("Y-m-d H:i:s",$creation_time)==date("Y-m-d H:i:00",$customer_creation_time)  )
            $_date=date("Y-m-d H:i:s",$customer_creation_time);
        else
            $_date=date("Y-m-d H:i:s",$creation_time);

        // print date("Y-m-d H:i:s",$creation_time)."-$customer_creation_time-".date("Y-m-d H:i:00",$customer_creation_time)." -- $_date \n";
        $dates[]=$_date;
    }
    //  print "-----\n";
    //print_r($date_splited);
    //print_r($dates);
    // return;
    foreach($date_splited as $index=>$y) {
        $x=preg_split('/\s+\-\s+/',$y);
        $tipo=$x[0];
        $note=preg_replace("/^$tipo -/",'',$y);

        if (!preg_match('/Last contact by/',$note)) {

            if (isset($history[$tipo][$dates[$index-1]]))
                $history[$tipo][$dates[$index-1]].=_trim(";\n".$note);
            else
                $history[$tipo][$dates[$index-1]]=_trim($note);

        }




    }

//  print_r($history);
// if(count($history)>2)
// exit("**\n");
    return $history;

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