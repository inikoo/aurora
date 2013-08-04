<?php

//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.PartLocation.php';

error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}

require 'MadMimi.class.php';
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;

$date1=date('Y-m-d',strtotime("now -24 days"));
$date2=date('Y-m-d',strtotime("$date1 +1 month"));
//print "$date1 $date2\n";
$sql=sprintf("select `Customer Key`,`Customer Last Order Date` from `Customer Dimension` where Date(`Customer Last Order Date`)=%s and `Customer Main Plain Email`!=''  and `Customer Store Key`=1 and `Customer Level Type`!='VIP' ",prepare_mysql($date1));

//print "$sql";
//exit;

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$customer=new Customer($row['Customer Key']);
	$name=$customer->get_greetings();
	$email=$customer->data['Customer Main Plain Email'];
	//print "$email $name  $date1 $date2\n";


	$body_array = array(
		'some_placeholder' => 'some content here' // This will replace "{some_placeholder}" in your promotion with "some content here".
		,'greeting' => 'Hello', 'name' => $name, 'date1'=>strftime("%e %b %Y", strtotime($date1.' UTC')), 'date2'=>strftime("%a %e %b %Y", strtotime($date2.' UTC'))
	);
	//$email='raul@inikoo.com';
	$options = array(
		'promotion_name' => 'Gold Reward Reminder',  // This is the promotion that I had manually created before executing this code
		'recipients' => $email,
		'from' => 'Katka <katka@ancientwisdom.biz>',
		'bcc'=>'david.hardy@gmail.com',
		'subject' => 'Ancient Wisdom Gold Reward Reminder'

	);

	$mailer = new MadMimi('david@ancientwisdom.biz', '447ba8315277320c130646a345136dc8');
	$response = $mailer->SendMessage($options, $body_array, true);
	print_r($response);


}

exit;

//background: url(http://2.bp.blogspot.com/-oOCWrvF6YMI/TpswCGyxlGI/AAAAAAAAD2A/yFmym6cDyS0/s380/mastercopy.png)




$body_array = array(
	'some_placeholder' => 'some content here' // This will replace "{some_placeholder}" in your promotion with "some content here".
	,'greeting' => 'Hello', 'name' => 'Nicholas', 'date1'=>'12/04/2012', 'date2'=>'13/04/2012'
);

$options = array(
	'promotion_name' => 'Gold Reward Reminder',  // This is the promotion that I had manually created before executing this code
	'recipients' => 'migara@inikoo.com',
	'from' => 'Ancient Wisdom <david@ancientwisdom.biz>',
	'subject' => 'Support API Test'

);

$mailer = new MadMimi('david@ancientwisdom.biz', '447ba8315277320c130646a345136dc8');
//$response = $mailer->SendMessage($options, $body_array, true);
//print_r($response);
?>
