<?php
require_once 'app_files/db/dns.php';
require_once 'class.Customer.php';

$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$default_DB_link) {
    print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
    print "Error can not access the database\n";
    exit;
}
mysql_query("SET NAMES 'utf8'");
require_once 'conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;
mysql_query("SET time_zone='+0:00'");


if(!isset($_GET['key']) || !isset($_GET['type'])){

	echo "<h2>Unauthorized Access</h2>";
	exit;
}



$customer=new Customer($_GET['key']);

switch(strtolower($_GET['type'])){
	case "newsletter":
		$field="Customer Send Newsletter";
		break;
	case "email_marketing":
		$field="Customer Send Email Marketing";
		break;
	default:
		$field="";
}

$customer->update_field_switcher($field, "No");
print $customer->msg;
if($customer->updated)
	print "Unsubscribed";
else
	exit("Error");



function prepare_mysql($string,$null_if_empty=true) {

    if (is_numeric($string)) {
        return "'".$string."'";
    }
    elseif($string=='' and $null_if_empty) {
        return 'NULL';
    }
    else {
        return "'".addslashes($string)."'";


    }
}


function _trim($string) {
    $string=trim($string);

    //$string=preg_replace('/\xC2\xA0\s*$/',' ',$string);
    // $string=preg_replace('/\xA0\s*/',' ',$string);
// $string=preg_replace('/\s+/',' ',trim($string));

//  $string=preg_replace('/^\s*/','',$string);
//   $string=preg_replace('/\s*$/','',$string);
//   $string=preg_replace('/\s+/',' ',$string);

    return $string;
}
?>