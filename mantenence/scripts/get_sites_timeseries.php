<?php
error_reporting(E_ALL);
date_default_timezone_set('UTC');

require_once '../../conf/dns.php';
require_once '../../class.TimeSeries.php';
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once 'timezone.php';
date_default_timezone_set(TIMEZONE) ;

include_once '../../set_locales.php';

require_once '../../conf/conf.php';


$_SESSION['lang']=1;


//$stores=array(1);
$forecast=true;

$sql="select * from `Site Dimension` ";
$res=mysql_query($sql);

while ( $row=mysql_fetch_array($res)) {

	print 'Site No Users Requests ('.$row['Site Key'].') '."\n";
	$tm=new TimeSeries(array('d',"Site No Users Requests (".$row['Site Key'].")"));
	$tm->get_values('save');

	print 'Site Users Requests ('.$row['Site Key'].') '."\n";
	$tm=new TimeSeries(array('d',"Site Users Requests (".$row['Site Key'].")"));
	$tm->get_values('save');



	//  $tm->save_values();
	// if($forecast)
	//  $tm->forecast();


}

?>
