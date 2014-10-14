<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Store.php');
include_once('../../class.Customer.php');
include_once('../../class.Node.php');
include_once('../../class.Category.php');

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';           



print "checking mail data integrity\n";
$sql=sprintf("select count(Distinct `Email Key`) as num from `Email Dimension`");
$res=mysql_query($sql);
if($row=mysql_fetch_assoc($res)){
    print "Emails in DB:\t\t\t".$row['num']."\n";
}
$sql=sprintf("select count(Distinct `Email Key`) as num  from `Email Bridge`");
$res=mysql_query($sql);
if($row=mysql_fetch_assoc($res)){
    print "Emails in Bridge:\t\t".$row['num']."\n";
}
$number_email_not_in_bridge=0;
$number_email_not_main=0;
$number_email_not_in_bridge_keys='';
 $number_email_not_in_main_keys='';
$sql=sprintf("select `Customer Key`,`Customer Main Email Key` from `Customer Dimension` where `Customer Main Email Key` IS NOT NULL and `Customer Main Email Key`>0");
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
    $sql=sprintf("select `Is Main` from `Email Bridge` where `Subject Type`='Customer' and `Subject Key`=%d and `Email Key`=%d",
    $row['Customer Key'],
    $row['Customer Main Email Key']
    );
    //print $sql;
    $res2=mysql_query($sql);
    if($row2=mysql_fetch_assoc($res2)){
        if($row2['Is Main']=='No'){
            $number_email_not_main++;
                    $number_email_not_in_main_keys.=',('.$row['Customer Key'].",".$row['Customer Main Email Key'].")";

            }
    }else{
        $number_email_not_in_bridge++;
        $number_email_not_in_bridge_keys.=',('.$row['Customer Key'].",".$row['Customer Main Email Key'].")";
    }

}

    print "P Emails not in Bridge:\t\t".$number_email_not_in_bridge.": $number_email_not_in_bridge_keys\n";
    print "P Emails not Main:\t\t".$number_email_not_main." :  $number_email_not_in_main_keys\n";

print "Contacts \n";
$number_email_not_in_bridge=0;
$number_email_not_main=0;
$number_email_not_in_bridge_keys='';
 $number_email_not_in_main_keys='';
$sql=sprintf("select `Contact Key`,`Contact Main Email Key` from `Contact Dimension` where `Contact Main Email Key` IS NOT NULL and `Contact Main Email Key`>0");
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
    $sql=sprintf("select `Is Main` from `Email Bridge` where `Subject Type`='Contact' and `Subject Key`=%d and `Email Key`=%d",
    $row['Contact Key'],
    $row['Contact Main Email Key']
    );
    //print $sql;
    $res2=mysql_query($sql);
    if($row2=mysql_fetch_assoc($res2)){
        if($row2['Is Main']=='No'){
            $number_email_not_main++;
                    $number_email_not_in_main_keys.=',('.$row['Contact Key'].",".$row['Contact Main Email Key'].")";

            }
    }else{
        $number_email_not_in_bridge++;
        $number_email_not_in_bridge_keys.=',('.$row['Contact Key'].",".$row['Contact Main Email Key'].")";
    }

}

    print "P Emails not in Bridge:\t\t".$number_email_not_in_bridge.": $number_email_not_in_bridge_keys\n";
    print "P Emails not Main:\t\t".$number_email_not_main." :  $number_email_not_in_main_keys\n";


print "Companys \n";
$number_email_not_in_bridge=0;
$number_email_not_main=0;
$number_email_not_in_bridge_keys='';
 $number_email_not_in_main_keys='';
$sql=sprintf("select `Company Key`,`Company Main Email Key` from `Company Dimension` where `Company Main Email Key` IS NOT NULL and `Company Main Email Key`>0");
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
    $sql=sprintf("select `Is Main` from `Email Bridge` where `Subject Type`='Company' and `Subject Key`=%d and `Email Key`=%d",
    $row['Company Key'],
    $row['Company Main Email Key']
    );
    //print $sql;
    $res2=mysql_query($sql);
    if($row2=mysql_fetch_assoc($res2)){
        if($row2['Is Main']=='No'){
            $number_email_not_main++;
                    $number_email_not_in_main_keys.=',('.$row['Company Key'].",".$row['Company Main Email Key'].")";

            }
    }else{
        $number_email_not_in_bridge++;
        $number_email_not_in_bridge_keys.=',('.$row['Company Key'].",".$row['Company Main Email Key'].")";
    }

}

    print "P Emails not in Bridge:\t\t".$number_email_not_in_bridge.": $number_email_not_in_bridge_keys\n";
    print "P Emails not Main:\t\t".$number_email_not_main." :  $number_email_not_in_main_keys\n";

print "Address \n";


print "Companys \n";
$number_address_not_in_bridge=0;
$number_address_not_main=0;
$number_address_not_in_bridge_keys='';
 $number_address_not_in_main_keys='';
$sql=sprintf("select `Company Key`,`Company Main Address Key` from `Company Dimension` where `Company Main Address Key` IS NOT NULL and `Company Main Address Key`>0");
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
    $sql=sprintf("select `Is Main` from `Address Bridge` where `Subject Type`='Company' and `Subject Key`=%d and `Address Key`=%d",
    $row['Company Key'],
    $row['Company Main Address Key']
    );
    //print $sql;
    $res2=mysql_query($sql);
    if($row2=mysql_fetch_assoc($res2)){
        if($row2['Is Main']=='No'){
            $number_address_not_main++;
                    $number_address_not_in_main_keys.=',('.$row['Company Key'].",".$row['Company Main Address Key'].")";

            }
    }else{
        $number_address_not_in_bridge++;
        $number_address_not_in_bridge_keys.=',('.$row['Company Key'].",".$row['Company Main Address Key'].")";
    }

}

    print "P Addresss not in Bridge:\t\t".$number_address_not_in_bridge.": $number_address_not_in_bridge_keys\n";
    print "P Addresss not Main:\t\t".$number_address_not_main." :  $number_address_not_in_main_keys\n";


print "============================\n";
print "Company Address-Telephone \n";
$number_tel_address_not_in_bridge=0;
$number_tel_address_not_main=0;
$number_tel_address_not_in_bridge_keys='';
 $number_tel_address_not_in_main_keys='';
$sql=sprintf("select `Company Key`,`Company Main Address Key`, `Company Main Telephone Key`from `Company Dimension` where (`Company Main Address Key` IS NOT NULL and `Company Main Address Key`>0) and (`Company Main Telephone Key` IS NOT NULL and `Company Main Telephone Key`>0) ");
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
$tel_not_main=false;
$address_not_main=false;
$tel_not_in_bridge=false;
$address_not_in_bridge=false;

    $sql=sprintf("select `Is Main` from `Address Bridge` where `Subject Type`='Company' and `Subject Key`=%d and `Address Key`=%d",
    $row['Company Key'],
    $row['Company Main Address Key']
    );
    //print $sql;
    $res2=mysql_query($sql);
    if($row2=mysql_fetch_assoc($res2)){
        if($row2['Is Main']=='No'){
            $address_not_main=true;
            }
    }else{
        $address_not_in_bridge=true;;
    }
    
    $sql=sprintf("select B.`Is Main` from `Telecom Bridge` B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`) where `Subject Type`='Company' and `Subject Key`=%d and B.`Telecom Key`=%d and `Telecom Type`='Telephone'",
    $row['Company Key'],
    $row['Company Main Telephone Key']
    );
    //print $sql;
    $res2=mysql_query($sql);
    if($row2=mysql_fetch_assoc($res2)){
        if($row2['Is Main']=='No'){
            $tel_not_main=true;
            }
    }else{
        $tel_not_in_bridge=true;
    }

if($tel_not_main or $address_not_main){
$number_tel_address_not_main++;
 $number_tel_address_not_in_main_keys.=',('.$row['Company Key'].",".$row['Company Main Address Key'].",".$row['Company Main Telephone Key'].")";
}
if($tel_not_in_bridge or $address_not_in_bridge){
$number_tel_address_not_in_bridge++;
 $number_tel_address_not_in_bridge_keys.=',('.$row['Company Key'].",".$row['Company Main Address Key'].",".$row['Company Main Telephone Key'].")";
}


}

    print "P Tel Addresses not in Bridge:\t\t".$number_tel_address_not_in_bridge.": $number_tel_address_not_in_bridge_keys\n";
    print "P Tel Addresses not Main:\t\t".$number_tel_address_not_main." :  $number_tel_address_not_in_main_keys\n";

print "============================\n";
print "Customer Address-Telephone \n";
$number_tel_address_not_in_bridge=0;
$number_tel_address_not_main=0;
$number_tel_address_not_in_bridge_keys='';
 $number_tel_address_not_in_main_keys='';
$sql=sprintf("select `Customer Key`,`Customer Main Address Key`, `Customer Main Telephone Key`from `Customer Dimension` where (`Customer Main Address Key` IS NOT NULL and `Customer Main Address Key`>0) and (`Customer Main Telephone Key` IS NOT NULL and `Customer Main Telephone Key`>0) ");
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
$tel_not_main=false;
$address_not_main=false;
$tel_not_in_bridge=false;
$address_not_in_bridge=false;

    $sql=sprintf("select `Is Main` from `Address Bridge` where `Subject Type`='Customer' and `Subject Key`=%d and `Address Key`=%d",
    $row['Customer Key'],
    $row['Customer Main Address Key']
    );
    //print $sql;
    $res2=mysql_query($sql);
    if($row2=mysql_fetch_assoc($res2)){
        if($row2['Is Main']=='No'){
            $address_not_main=true;
            }
    }else{
        $address_not_in_bridge=true;;
    }
    
    $sql=sprintf("select B.`Is Main` from `Telecom Bridge` B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`) where `Subject Type`='Customer' and `Subject Key`=%d and B.`Telecom Key`=%d and `Telecom Type`='Telephone'",
    $row['Customer Key'],
    $row['Customer Main Telephone Key']
    );
    //print $sql;
    $res2=mysql_query($sql);
    if($row2=mysql_fetch_assoc($res2)){
        if($row2['Is Main']=='No'){
            $tel_not_main=true;
            }
    }else{
        $tel_not_in_bridge=true;
    }

if($tel_not_main or $address_not_main){
$number_tel_address_not_main++;
 $number_tel_address_not_in_main_keys.=',('.$row['Customer Key'].",".$row['Customer Main Address Key'].",".$row['Customer Main Telephone Key'].")";
}
if($tel_not_in_bridge or $address_not_in_bridge){
$number_tel_address_not_in_bridge++;
 $number_tel_address_not_in_bridge_keys.=',('.$row['Customer Key'].",".$row['Customer Main Address Key'].",".$row['Customer Main Telephone Key'].")";
}


}

    print "P Tel Addresses not in Bridge:\t\t".$number_tel_address_not_in_bridge.": $number_tel_address_not_in_bridge_keys\n";
    print "P Tel Addresses not Main:\t\t".$number_tel_address_not_main." :  $number_tel_address_not_in_main_keys\n";



print "\n";
?>