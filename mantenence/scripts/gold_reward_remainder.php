<?php

//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012
include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.PartLocation.php';

error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host, $dns_user, $dns_pwd );

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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;

$date1=date('Y-m-d', strtotime("now -24 days"));
$date2=date('Y-m-d', strtotime("$date1 +1 month"));
//print "$date1 $date2\n";



$email_data=array(

	'UK'=>array(
		'promotion_name' => 'Gold Reward Reminder',
		'from' => 'Ancient Wisdom <care@ancientwisdom.biz>',
		'bcc'=>'david.hardy@gmail.com',
		'subject' => 'Gold Reward Reminder',

		'email_provider_user'=>'david@ancientwisdom.biz',
		'email_provider_password'=>'447ba8315277320c130646a345136dc8',

	),
	'FR'=>array(
		'promotion_name' => 'French - Gold Reward Reminder',
		'from' => 'aw-cadeaux <mail@aw-cadeaux.com>',
		'subject' => 'Rappel de Statut Gold',
		'bcc'=>'david.hardy@gmail.com',

		'email_provider_user'=>'david@ancientwisdom.biz',
		'email_provider_password'=>'447ba8315277320c130646a345136dc8'
	),

	'DE'=>array(
		'promotion_name' => 'German - Gold Reward Reminder',
		'from' => 'aw-geschenke <info@aw-geschenke.com>',
		'bcc'=>'david.hardy@gmail.com',
		'subject' => 'Goldprämien erinnerung',
		'email_provider_user'=>'david@ancientwisdom.biz',
		'email_provider_password'=>'447ba8315277320c130646a345136dc8'
	),
	'IT'=>array(
		'promotion_name' => 'Italian - Gold Reward Reminder',
		'from' => 'aw-regali <mail@aw-regali.com>',
		'bcc'=>'david.hardy@gmail.com',
		'subject' => 'Premio fedeltá',
		'email_provider_user'=>'david@ancientwisdom.biz',
		'email_provider_password'=>'447ba8315277320c130646a345136dc8'
	),
	'PL'=>array(
		'promotion_name' => 'Polish - Gold Reward Reminder',
		'from' => 'aw-podarki <info@aw-podarki.com>',
		'subject' => 'Złotą Nagroda Przypomnienie',
		'bcc'=>'david.hardy@gmail.com',

		'email_provider_user'=>'david@ancientwisdom.biz',
		'email_provider_password'=>'447ba8315277320c130646a345136dc8',
	),
	'HAXXX'=>array(
		'promotion_name' => 'Gold Reward Reminder HA',
		'from' => 'HIP Angels <scarves@hipangels.com>',
		'bcc'=>'david.hardy@gmail.com',
		'subject' => 'Gold Reward Reminder',

		'email_provider_user'=>'david@ancientwisdom.biz',
		'email_provider_password'=>'447ba8315277320c130646a345136dc8',
	),
	'AWR'=>array(
		'promotion_name' => 'Club Oro Nuevo - ES',
		'from' => 'trini@aw-regalos.com',
		'bcc'=>'david.hardy@gmail.com',
		'subject' => 'Recordatorio de Club Oro',
		'email_provider_user'=>'carlos.awr@gmail.com',
		'email_provider_password'=>'2b9affc4f81d32f7fa57d95f6f7b5268',
	),
	'AWP'=>array(
		'promotion_name' => 'Club Oro Nuevo - PT',
		'from' => 'trini@aw-regalos.com',
		'bcc'=>'david.hardy@gmail.com',
		'subject' => 'Fidelidade Clube Ouro',

		'email_provider_user'=>'carlos.awr@gmail.com',
		'email_provider_password'=>'2b9affc4f81d32f7fa57d95f6f7b5268',
	)



);


$sql=sprintf('select `Store Code`,`Store Key`,`Store Locale` from `Store Dimension` ');
$resxx=mysql_query($sql);
while ($rowxx=mysql_fetch_assoc($resxx)) {

	$sql=sprintf("select `Customer Key`,`Customer Last Order Date` from `Customer Dimension` where Date(`Customer Last Invoiced Dispatched Date`)=%s and `Customer Main Plain Email`!=''  and `Customer Store Key`=%d and `Customer Level Type`!='VIP' ",
		prepare_mysql($date1),
		$rowxx['Store Key']

	);


	if (array_key_exists($rowxx['Store Code'], $email_data)) {

		//print "$sql";
		//exit;

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$customer=new Customer($row['Customer Key']);

			if (count($customer->get_pending_orders_keys())>0) {
				continue;
			}

			$name=$customer->data['Customer Name'];
			$email=$customer->data['Customer Main Plain Email'];
			//print "$email $name  $date1 $date2\n";

        setlocale(LC_TIME, $rowxx['Store Locale']);

			$body_array = array(
				'name' => $name,
				'date1'=>strftime("%e %b %Y", strtotime($date1.' UTC')),
				'date2'=>strftime("%a %e %b %Y", strtotime($date2.' UTC'))
			);
			$email='raul@inikoo.com';
			$options = array(
				'promotion_name' => $email_data[$rowxx['Store Code']]['promotion_name'],
				'recipients' => $email,
				'from' =>  $email_data[$rowxx['Store Code']]['from'],
				'bcc'=>$email_data[$rowxx['Store Code']]['bcc'],
				'subject' => $email_data[$rowxx['Store Code']]['subject'],

			);


			$mailer = new MadMimi(
				$email_data[$rowxx['Store Code']]['email_provider_user'],
				$email_data[$rowxx['Store Code']]['email_provider_password']

			);
			$response = $mailer->SendMessage($options, $body_array, true);
			print_r($response);

		}
	}

}
?>
