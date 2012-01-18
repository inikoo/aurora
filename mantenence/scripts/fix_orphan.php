<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Store.php');
include_once('../../class.DeliveryNote.php');
include_once('../../class.Order.php');

include_once('../../class.Customer.php');

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';

//$sql="select * from kbase.`Country Dimension`";
//$result=mysql_query($sql);
//while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//print "cp ../../examples/_countries/".strtolower(preg_replace('/\s/','_',$row['Country Name']))."/ammap_data.xml ".$row['Country Code'].".xml\n";
//}
//exit;
/*

$sql=sprintf("select `History Key` , `Direct Object Key` from `History Dimension`  where  `Direct Object` in ('Delivery Note','After Sale')    ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

    $dn=new DeliveryNote($row['Direct Object Key']);
    if (!$dn->id) {
    print "Deleting DN History ".$row['Direct Object Key']."\n";
        $sql=sprintf("delete from `Customer History Bridge`  where   `History Key`=%d",$row['History Key']);
        mysql_query($sql);

        $sql=sprintf("delete from `History Dimension` where `Direct Object Key`=%d and `Direct Object` in ('Delivery Note','After Sale')   ",$row['Direct Object Key']);
        mysql_query($sql);

    }
}

$sql=sprintf("select `History Key` , `Direct Object Key` from `History Dimension`  where  `Direct Object` in ('Order')   ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

    $order=new Order($row['Direct Object Key']);
    if (!$order->id) {
        print "Deleting Order History ".$row['Direct Object Key']."  ".$row['History Key']."  \n";
        $sql=sprintf("delete from `Customer History Bridge`  where   `History Key`=%d",$row['History Key']);
        mysql_query($sql);

        $sql=sprintf("delete from `History Dimension` where `Direct Object Key`=%d and `Direct Object` in ('Order') ",$row['Direct Object Key']);
        mysql_query($sql);

    }
}

*/





$sql="select * from `Contact Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
    $contact=new Contact($row['Contact Key']);
    $contact_customer_keys=$contact->get_parent_keys('Customer');
    $contact_supplier_keys=$contact->get_parent_keys('Supplier');
    $contact_company_keys=$contact->get_parent_keys('Company');
    $contact_staff_keys=$contact->get_parent_keys('Staff');

    if (count($contact_customer_keys)==0 and count($contact_supplier_keys)==0  and count($contact_staff_keys)==0) {
        $company_orphan=false;
        foreach($contact_company_keys as $company_key) {
            if (is_orphan_company($company_key))
                $company_orphan=true;
        }

        if ($company_orphan or count($contact_company_keys)==0) {
            print "Orphan contact ".$contact->id."\n";
            foreach($contact_company_keys as $company_key) {
                $company=new Company($company_key);
                $company->delete();
            }
            $contact->delete();


        } else {
            print "Posible Orphan contact ".$contact->id."\n";


        }
        // print_r($contact->data);
        //  exit("");
    }


    //print $contact->id."\t\t\r";
}


$sql="select * from `Address Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
    $address=new Address($row['Address Key']);
    if (!$address->has_parents()) {
        print $address->data['Address Key']." address\n";;
        $address->delete();
    }
}

$sql="select * from `Telecom Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
    $tel=new Telecom($row['Telecom Key']);
    if (!$tel->has_parents()) {
        print $tel->data['Telecom Key']." tel\n";;
        $tel->delete();
    }
}


$sql="select * from `Email Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
    $email=new Email($row['Email Key']);
    if (!$email->has_parents()) {
        print $email->data['Email Key'].' '.$email->data['Email']."\n";
        $email->delete();
    }
}


$sql="select * from `Email Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
    $email=new Email($row['Email Key']);
    if (!$email->has_parents()) {
        print $email->data['Email Key'].' '.$email->data['Email']."\n";
        $email->delete();
    }
}





function is_orphan_company($company_key) {

    $company=new Company($company_key);
    $company_customer_keys=$company->get_parent_keys('Customer');
    $company_supplier_keys=$company->get_parent_keys('Supplier');
    $company_hq_keys=$company->get_parent_keys('HQ');

    if (count($company_customer_keys)==0 and count($company_supplier_keys)==0  and count($company_hq_keys)==0) {
        return true;
    }
//print_r($company_hq_keys);

    return false;

}




?>