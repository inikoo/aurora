<?php

require_once 'app_files/db/dns.php';
$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$default_DB_link) {
    print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
    print "Error can not access the database\n";
    exit;
}

 
$key='googled66fe666276dd7c1';

$url= 'http://'.$_SERVER['REMOTE_ADDR'].'/googled66fe666276dd7c1.html';
//print $url;


$myFile = $url;
$fh = fopen($myFile, 'r');
$theData = fread($fh, 22);
fclose($fh);

if($key!=$theData){
	//print 'false';
	exit;
}
else{
	//print 'ok';
	//exit;
}


if(isset($_REQUEST['name_from']))
	$name=$_REQUEST['name_from'];
else
	$name='';
	
if(isset($_REQUEST['Senders_Company']))
	$company=$_REQUEST['Senders_Company'];
else
	$company='';
	
if(isset($_REQUEST['Senders_Business']))
	$business=$_REQUEST['Senders_Business'];
else
	$business='';

if(isset($_REQUEST['Address']))
	$address=$_REQUEST['Address'];
else
	$address='';

if(isset($_REQUEST['City']))
	$city=$_REQUEST['City'];
else
	$city='';

if(isset($_REQUEST['County']))
	$county=$_REQUEST['County'];
else
	$county='';
	
if(isset($_REQUEST['Country']))
	$country=$_REQUEST['Country'];
else
	$country='';
	
if(isset($_REQUEST['Postcode']))
	$postcode=$_REQUEST['Postcode'];
else
	$postcode='';

if(isset($_REQUEST['Telephone']))
	$telephone=$_REQUEST['Telephone'];
else
	$telephone='';

if(isset($_REQUEST['email_from']))
	$email=$_REQUEST['email_from'];
else
	$email='';

if(isset($_REQUEST['Advertising']))
	$advertising=$_REQUEST['Advertising'];
else
	$advertising='';
	
if(isset($_REQUEST['Message']))
	$message=$_REQUEST['Message'];
else
	$message='';

$store_key=$_REQUEST['store_key'];
$scope=$_REQUEST['scope'];


$record='"'.$email.'"#'.'"'.$company.'"#'.'"'.$name.'"#'.'"'.$address.'"#'.'"'.$message.'"#'.'"'.$city.'"#'.'"'.$postcode.'"#'.'"'.$county.'"#'.'"'.$country.'"#'.'"'.$telephone.'"#'.'"'.$business.'"#'.'"'.$advertising.'"';
$sql=sprintf("insert into `External Records` (`Store Key`, `Scope`, `Record`, `IP`) values (%d, '%s', '%s', '%s')", $store_key, $scope, $record, $_SERVER['REMOTE_ADDR']);

//print $record;

$result=mysql_query($sql);
if($result){
	print 'Record Added. Thank you getting registered with us';
	
}
else
	print 'Error';
?>
<br/>
<a href="external_form.php">Back</a>


